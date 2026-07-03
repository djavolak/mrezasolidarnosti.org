<?php

namespace Solidarity\EmailList\Filter;

use Laminas\Filter\ToInt;
use Skeletor\Core\Filter\FilterInterface;
use Skeletor\Core\Validator\ValidatorException;
use Volnix\CSRF\CSRF;

class EmailList implements FilterInterface
{
    public function __construct(private \Solidarity\EmailList\Validator\EmailList $validator)
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
            'email' => trim($postData['email'] ?? ''),
            'isActive' => isset($postData['isActive']) ? (int)$postData['isActive'] : 1
        ];

        if (isset($postData['id'])) {
            $data['id'] = $int->filter($postData['id']);
        }

        if (!$this->validator->isValid($data)) {
            throw new ValidatorException();
        }
        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }
}
