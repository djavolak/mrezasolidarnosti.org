<?php

namespace Solidarity\Donor\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Filter\ToInt;
use Skeletor\Core\Filter\FilterInterface;
use Skeletor\User\Service\Session;
use Volnix\CSRF\CSRF;
use Laminas\I18n\Filter\Alnum;
use Skeletor\Core\Validator\ValidatorException;
class Donor implements FilterInterface
{

    public function __construct(private \Solidarity\Donor\Validator\Donor $validator)
    {
    }

    public function getErrors()
    {
        return $this->validator->getMessages();
    }

    public function filter($postData): array
    {
        $alnum = new Alnum(true);
        $int = new ToInt();

        $data = [
            'id' => (isset($postData['id'])) ? $int->filter($postData['id']) : null,
            'email' => $postData['email'],
            'firstName' => $postData['firstName'],
            'lastName' => $postData['lastName'],
            'wantsToDonateTo' => $postData['wantsToDonateTo'],
            'comment' => $postData['comment'],
            'isActive' => $postData['isActive'],
            'projects' => $postData['projects'],
            'status' => (isset($postData['status'])) ? $postData['status'] : 1,
            CSRF::TOKEN_NAME => $postData[CSRF::TOKEN_NAME],
        ];

        // Parse paymentMethods rows from form
        // JS sends: paymentMethods[idx][project], paymentMethods[idx][paymentType],
        //           paymentMethods[idx][monthly], paymentMethods[idx][amount], paymentMethods[idx][currency]
        $paymentMethods = [];
        if (isset($postData['paymentMethods']) && is_array($postData['paymentMethods'])) {
            foreach ($postData['paymentMethods'] as $row) {
                if (empty($row['paymentType']) || $row['paymentType'] === '-1') {
                    continue;
                }
                if (empty($row['project']) || $row['project'] === '-1') {
                    continue;
                }
                $paymentMethods[] = [
                    'project' => (int) $row['project'],
                    'type' => (int) $row['paymentType'],
                    'monthly' => (int) ($row['monthly'] ?? 0),
                    'amount' => (int) ($row['amount'] ?? 0),
                    'currency' => (int) ($row['currency'] ?? \Solidarity\Donor\Entity\PaymentMethod::CURRENCY_RSD),
                ];
            }
        }
        $data['paymentMethods'] = $paymentMethods;

        if (!$this->validator->isValid($data)) {
            throw new ValidatorException();
        }
        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }

}