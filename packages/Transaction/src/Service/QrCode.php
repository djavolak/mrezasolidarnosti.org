<?php
namespace Solidarity\Transaction\Service;

use chillerlan\QRCode\QRCode as QrCodeRenderer;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use Solidarity\Donor\Entity\PaymentMethod;
use Solidarity\Transaction\Entity\Transaction;

/**
 * Builds an NBS IPS QR (the payment QR Serbian banking apps scan) for a
 * transaction and returns it as an <img src>-ready data URI.
 *
 * Typical use from an Action:
 *
 *     $src = $this->qrCode->forTransaction($transaction);
 *     $this->setGlobalVariable('paymentQr', $src);   // <img src="<?= $paymentQr ?>">
 *
 * The IPS payload spec (tag order K,V,C,R,N,I,SF,S,RO; '|' separated; UTF-8):
 * https://ips.nbs.rs/PDF/Smernice_IPS_QR_.pdf
 *
 * Field mapping / assumptions — adjust here if the business rules differ:
 *  - R  (account)   ← transaction->accountNumber (digits only)
 *  - N  (recipient) ← beneficiary->name (throws if the transaction has none)
 *  - I  (amount)    ← transaction->amount, which is ALWAYS stored in RSD
 *                     (amountEur holds the EUR figure); IPS is domestic RSD only
 *  - SF (pay code)  ← DEFAULT_PAYMENT_CODE (289 = citizen transfer/donation)
 *  - S  (purpose)   ← "Donacija <referenceCode>"
 *  - RO (reference) ← model 00 + transaction id
 */
class QrCode
{
    /** Šifra plaćanja — 289 = other transfer, the usual code for a citizen donation. */
    private const DEFAULT_PAYMENT_CODE = '289';

    /**
     * Full pipeline: transaction → IPS payload → QR → data URI.
     * Returns e.g. "data:image/svg+xml;base64,…" ready for an <img src>.
     */
    public function forTransaction(Transaction $transaction): string
    {
        return $this->render($this->buildIpsPayload($transaction));
    }

    /**
     * Whether a payment QR can be built for this transaction. Lets a caller
     * decide to show the QR without catching the exception buildIpsPayload()
     * throws for a transaction that has no beneficiary (recipient).
     */
    public function canBuildFor(Transaction $transaction): bool
    {
        return $transaction->beneficiary !== null && $transaction->paymentType === PaymentMethod::TYPE_BANK_TRANSFER
            && in_array($transaction->status, [Transaction::STATUS_NEW, Transaction::STATUS_WAITING_CONFIRMATION]);
    }

    /**
     * Assemble the raw NBS IPS payload string from the transaction.
     * Pure (no rendering) so it can be unit-tested without the QR library.
     */
    public function buildIpsPayload(Transaction $transaction): string
    {
        if ($transaction->beneficiary === null) {
            throw new \RuntimeException(sprintf(
                'Cannot build a payment QR for transaction %s: it has no beneficiary (recipient).',
                $transaction->getId()
            ));
        }

        $account = preg_replace('/\D/', '', (string) $transaction->accountNumber);

        $tags = [
            'K'  => 'PR',
            'V'  => '01',
            'C'  => '1',
            'R'  => $account,
            'N'  => $this->clip($transaction->beneficiary->name, 70),
            'I'  => 'RSD' . number_format($transaction->amount, 2, ',', ''),
            'SF' => self::DEFAULT_PAYMENT_CODE,
            'S'  => $this->clip('Donacija ' . $transaction->getReferenceCode(), 35),
            'RO' => '00' . $transaction->getId(),
        ];

        $parts = [];
        foreach ($tags as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            $parts[] = $key . ':' . $value;
        }

        return implode('|', $parts);
    }

    /**
     * Render any payload to an SVG data URI. SVG output needs no GD/Imagick
     * extension and stays crisp at any size.
     */
    public function render(string $payload): string
    {
        $options = new QROptions([
            'eccLevel'     => EccLevel::M,
            'scale'        => 5,
            'outputBase64' => true,
        ]);

        return (new QrCodeRenderer($options))->render($payload);
    }

    /** Collapse whitespace and hard-limit to the IPS field length. */
    private function clip(string $value, int $max): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value));

        return mb_substr($value, 0, $max);
    }
}
