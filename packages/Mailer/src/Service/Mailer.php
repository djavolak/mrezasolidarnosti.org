<?php
namespace Solidarity\Mailer\Service;

use Laminas\Config\Config;
use League\Plates\Engine;
use MailerSend\Helpers\Builder\Attachment;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;
use Monolog\LogRecord;
use Psr\Log\LoggerInterface as Logger;

class Mailer extends \Skeletor\Core\Mailer\Service\MailerSendMailer
{
    public function __construct(MailerSend $mail, Config $config, Engine $template)
    {
        parent::__construct($mail, $config, $template);
    }

    /**
     * Only production sends through MailerSend. Everywhere else (development,
     * staging, CLI/cron without APPLICATION_ENV set) the mail is caught via SMTP
     * — Mailpit in dev — so no real email is ever sent outside production.
     */
    protected function send($recipients, $subject, $html)
    {
        if (strtolower((string) getenv('APPLICATION_ENV')) === 'production') {
            parent::send($recipients, $subject, $html);
            return;
        }

        $this->catchViaSmtp($recipients, $subject, $html);
    }

    /**
     * @param \MailerSend\Helpers\Builder\Recipient[] $recipients
     */
    private function catchViaSmtp($recipients, string $subject, string $html): void
    {
        $smtp = $this->config->mailer->smtp;

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtp->host;
        $mail->Port = (int) $smtp->port;
        $mail->SMTPAuth = false;
        $mail->CharSet = \PHPMailer\PHPMailer\PHPMailer::CHARSET_UTF8;
        $mail->setFrom($this->config->mailer->from, $this->config->offsetGet('appName'));
        foreach ($recipients as $recipient) {
            $data = $recipient->toArray();
            $mail->addAddress($data['email'], (string) ($data['name'] ?? ''));
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);
        $mail->send();
    }

    // @todo
    public function sendTransactionListToDelegate($email, $listPath)
    {
        $body = $this->render('transactionList', []);
        $recipients = [
            new Recipient($email, $email),
        ];
        $emailParams = (new \MailerSend\Helpers\Builder\EmailParams())
            ->setFrom('delegati@mrezasolidarnosti.org')
            ->setFromName('Mreža solidarnosti')
            ->setRecipients($recipients)
            ->setSubject('Nerealizovane isplate za 1. deo februara')
            ->setHtml($body)
            ->setReplyTo('delegati@mrezasolidarnosti.org')
            ->setReplyToName('Mreža solidarnosti')
            ->setAttachments([new Attachment(file_get_contents($listPath), basename($listPath))]);

        $this->send($emailParams);
    }

    // @todo, might not be required
    public function sendRoundStartMailToDelegate($email)
    {
        $body = $this->render('roundStart', []);
        $recipients = [
            new Recipient($email, $email),
        ];
        $uputstvoPath = DATA_PATH .'/Uputstvo-prijava-2.-deo-februar.pdf';
        $emailParams = (new \MailerSend\Helpers\Builder\EmailParams())
            ->setFrom('delegati@mrezasolidarnosti.org')
            ->setFromName('Mreža solidarnosti')
            ->setRecipients($recipients)
            ->setSubject('Prijava oštećenih, 2. deo februar')
            ->setHtml($body)
            ->setReplyTo('delegati@mrezasolidarnosti.org')
            ->setReplyToName('Mreža solidarnosti')
            ->setAttachments([new Attachment(file_get_contents($uputstvoPath), 'Uputstvo prijava 2. deo februar.pdf')]);

        $this->send($emailParams);
    }

    // @todo, might not be required
    public function sendDelegateRegisteredMail($email)
    {
        $body = $this->render('delegateRegistered', []);
        $recipients = [
            new Recipient($email, $email),
        ];
        $emailParams = (new \MailerSend\Helpers\Builder\EmailParams())
            ->setFrom('delegati@mrezasolidarnosti.org')
            ->setFromName('Mreža solidarnosti')
            ->setRecipients($recipients)
            ->setSubject('Potvrda registracije za delegata na Mrežu solidarnosti')
            ->setHtml($body)
            ->setReplyTo('delegati@mrezasolidarnosti.org')
            ->setReplyToName('Mreža solidarnosti');

        $this->send($emailParams);
    }

    // @todo
    public function sendDonorRegisteredMail($email)
    {
        $body = $this->render('donorRegistered', [
//            'email' => $email,
//            'baseUrl' => $this->config->offsetGet('baseUrl')
        ]);

        $recipients = [
            new Recipient($email, $email),
        ];
        $emailParams = (new \MailerSend\Helpers\Builder\EmailParams())
            ->setFrom('donatori@mrezasolidarnosti.org')
            ->setFromName('Mreža solidarnosti')
            ->setRecipients($recipients)
            ->setSubject('Potvrda registracije na Mrežu solidarnosti')
            ->setHtml($body)
            ->setReplyTo('donatori@mrezasolidarnosti.org')
            ->setReplyToName('Mreža solidarnosti');

        $this->send($emailParams);
    }

    public function sendDashboardMagicLinkMail(string $email, string $magicLinkUrl, string $displayName): void
    {
        $recipients = [
            new Recipient($email, $email),
        ];

        $body = $this->render('magicLink', [
            'displayName' => $displayName,
            'magicLinkUrl' => $magicLinkUrl,
            'baseUrl' => $this->config->offsetGet('baseUrl')
        ]);
        $subject = "Vaš link za prijavu na Mrežu solidarnosti";

        $this->send($recipients, $subject, $body);
    }

}