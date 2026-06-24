<?php

namespace Solidarity\Backend\Blocks\HeroStats;

use Skeletor\ContentEditor\Contracts\BlockViewFilterInterface;
use Solidarity\Beneficiary\Service\Beneficiary;
use Solidarity\Donor\Entity\Donor as DonorEntity;
use Solidarity\Donor\Service\Donor;
use Solidarity\Transaction\Entity\Transaction as TransactionEntity;
use Solidarity\Transaction\Service\Transaction;

class HeroStatsViewFilter implements BlockViewFilterInterface
{
    public function __construct(
        protected Donor $donorService,
        protected Beneficiary $beneficiaryService,
        protected Transaction $transactionService
    )
    {

    }

    public function filter(array $data): array
    {
        $donorCount = $this->donorService->getDonorCount(DonorEntity::STATUS_VERIFIED, true);
        $data['donorCount'] = number_format($donorCount, 0, ',', '.');

        $totalAmount = $this->transactionService->getTotalNetworkedAmount();
        $data['totalAmount'] = number_format($totalAmount, 0, ',', '.');
        $data['totalAmountEur'] = number_format(TransactionEntity::rsdToEur($totalAmount), 2, ',', '.');

        $supportedCount = $this->beneficiaryService->getBeneficiaryCount();
        $data['supportedCount'] = number_format($supportedCount, 0, ',', '.');

        return $data;
    }
}
