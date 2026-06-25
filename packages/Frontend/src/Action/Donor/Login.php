<?php
namespace Solidarity\Frontend\Action\Donor;

use Laminas\Config\Config;
use League\Plates\Engine;
use Psr\Log\LoggerInterface as Logger;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Solidarity\Frontend\Action\BaseAction;

class Login extends BaseAction
{
    public function __construct(
        Logger $logger, Config $config, Engine $template, private \Solidarity\Donor\Service\Donor $donor,
        protected Navigation $navigationService, protected SocialLinks $socialLinks,
        \Solidarity\Frontend\Service\Session $session,
    ) {
        parent::__construct($logger, $config, $template, $this->navigationService, $this->socialLinks, $session);
    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        $data = $request->getParsedBody();
        if (!empty($data)) {
            $email = trim($data['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->respond('donor/login', ['error' => 'Unesite ispravnu email adresu.', 'data' => $data]);
            }
            $this->donor->requestLoginLink($email);

            return $this->respond('donor/login', ['sent' => true]);
        }

        return $this->respond('donor/login', []);
    }
}
