<?php

namespace Solidarity\Backend\Blocks\Profiledata;

use Skeletor\ContentEditor\Contracts\BlockViewFilterInterface;
use Solidarity\Frontend\Service\Session;
use Solidarity\Transaction\Service\Transaction;

class ProfiledataViewFilter implements BlockViewFilterInterface
{
    public function __construct(
        protected Session $session,
        protected Transaction $transactionService
    )
    {

    }

    public function filter(array $data): array
    {
        $data['isDonorLoggedIn'] = $this->session->isDonor();
        if ($data['isDonorLoggedIn']) {
            $data['donor'] = $this->session->getUser();
        }
        $data['totalDonated'] = number_format($this->transactionService->getPaidSumAmountForDonor($data['donor']));
        $data['totalDonatedEUR'] = \Solidarity\Transaction\Entity\Transaction::rsdToEur($data['totalDonated']);
        $data['totalTransactions'] = $this->transactionService->getTransactionCountForDonor($data['donor']);

        return $data;
    }
}
