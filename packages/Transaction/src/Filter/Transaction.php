<?php

namespace Solidarity\Transaction\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Filter\ToInt;
use Skeletor\Core\Filter\FilterInterface;
use Skeletor\User\Service\Session;
use Volnix\CSRF\CSRF;
use Laminas\I18n\Filter\Alnum;
use Skeletor\Core\Validator\ValidatorException;
class Transaction implements FilterInterface
{

    public function __construct(private \Solidarity\Transaction\Validator\Transaction $validator)
    {
    }

    public function getErrors()
    {
        return $this->validator->getMessages();
    }

    public function filter($postData): array
    {
        $int = new ToInt();

        $data = [
            'id' => (isset($postData['id'])) ? $int->filter($postData['id']) : null,
            'beneficiary' => $postData['beneficiary'] ?? null,
            'project' => $postData['project'],
            'period' => $postData['period'],
            'amount' => (int) ($postData['amount'] ?? 0),
            'comment' => $postData['comment'] ?? '',
            'status' => (int) ($postData['status'] ?? 1),
            'donor' => $postData['donor'] ?? null,
            'donorConfirmed' => (int) ($postData['donorConfirmed'] ?? 0),
            CSRF::TOKEN_NAME => $postData[CSRF::TOKEN_NAME],
        ];
        if (!$this->validator->isValid($data)) {
            throw new ValidatorException();
        }
        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }

}
