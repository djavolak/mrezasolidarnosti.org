<?php

namespace Solidarity\Donor\Filter;

use Laminas\Filter\ToInt;
use Laminas\I18n\Filter\Alnum;
use Skeletor\Core\Validator\ValidatorException;
use Volnix\CSRF\CSRF;

class DonorProfileData
{
    public function __construct()
    {
    }

    public function filter($postData): array
    {
        $data = [
            'id' => filter_var($postData['id'] ?? null, FILTER_VALIDATE_INT) ?: null,
            'email' => trim($postData['email'] ?? ''),
            'firstName' => trim($postData['firstName'] ?? ''),
            'lastName' => trim($postData['lastName'] ?? ''),
            CSRF::TOKEN_NAME => $postData[CSRF::TOKEN_NAME] ?? '',
        ];

        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }
}