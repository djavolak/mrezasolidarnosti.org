<?php

namespace Solidarity\Backend\Service;

use Doctrine\ORM\EntityManagerInterface;
use Solidarity\Beneficiary\Entity\Beneficiary;
use Solidarity\Beneficiary\Entity\PaymentMethod as BeneficiaryPaymentMethod;
use Solidarity\Beneficiary\Entity\RegisteredPeriods;
use Solidarity\Donor\Entity\Donor;
use Solidarity\Donor\Entity\PaymentMethod as DonorPaymentMethod;
use Solidarity\Transaction\Entity\Transaction;

/**
 * GDPR-compliant erasure of donors and beneficiaries.
 *
 * Personal data is removed, but the transaction rows are kept (detached and stripped of
 * any copied personal data) so financial totals per period/project stay intact. This is
 * irreversible erasure — not a soft "deactivate". The single flush is atomic.
 */
class Redaction
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Erase a donor: detach their transactions (a transaction carries no donor personal
     * data itself), drop their pledges and project links, then delete the donor row.
     * The transactions remain as anonymous records for accounting.
     */
    public function redactDonor(Donor $donor): void
    {
        foreach ($this->em->getRepository(Transaction::class)->findBy(['donor' => $donor]) as $transaction) {
            $transaction->donor = null;
        }
        foreach ($this->em->getRepository(DonorPaymentMethod::class)->findBy(['donor' => $donor]) as $paymentMethod) {
            $this->em->remove($paymentMethod);
        }
        $donor->projects->clear();

        $this->em->remove($donor);
        $this->em->flush();
    }

    /**
     * Erase a beneficiary: strip the account number / wire instructions copied onto their
     * transactions and detach them, drop the payment methods (bank accounts) and registered
     * periods, then delete the beneficiary row.
     */
    public function redactBeneficiary(Beneficiary $beneficiary): void
    {
        foreach ($this->em->getRepository(Transaction::class)->findBy(['beneficiary' => $beneficiary]) as $transaction) {
            $transaction->accountNumber = null;
            $transaction->instructions = null;
            $transaction->beneficiary = null;
        }
        foreach ($this->em->getRepository(BeneficiaryPaymentMethod::class)->findBy(['beneficiary' => $beneficiary]) as $paymentMethod) {
            $this->em->remove($paymentMethod);
        }
        foreach ($this->em->getRepository(RegisteredPeriods::class)->findBy(['beneficiary' => $beneficiary]) as $registeredPeriod) {
            $this->em->remove($registeredPeriod);
        }

        $this->em->remove($beneficiary);
        $this->em->flush();
    }
}
