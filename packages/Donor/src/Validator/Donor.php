<?php

namespace Solidarity\Donor\Validator;

use Skeletor\Core\Validator\ValidatorInterface;
use Volnix\CSRF\CSRF;

/**
 * Class Client.
 * User validator.
 *
 * @package Fakture\Client\Validator
 */
class Donor implements ValidatorInterface
{

    /**
     * @var CSRF
     */
    private $csrf;

    private $messages = [];

    /**
     * User constructor.
     *
     * @param CSRF $csrf
     */
    public function __construct(CSRF $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * Validates provided data, and sets errors with Flash in session.
     *
     * @param $data
     *
     * @return bool
     */
    public function isValid(array $data): bool
    {
        $valid = true;
        $this->messages = [];
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->messages['email'][] = 'Uneta email adresa nije ispravna. ' . $data['email'];
            $valid = false;
        }

        if(trim($data['firstName']) === '') {
            $this->messages['firstName'][] = 'First name is required';
        }

        if(strlen(trim($data['firstName'])) < 2) {
            $this->messages['firstName'][] = 'First name must be at least 2 characters long';
        }

        if(trim($data['lastName']) === '') {
            $this->messages['lastName'][] = 'Last name is required';
        }

        if(strlen(trim($data['lastName'])) < 2) {
            $this->messages['lastName'][] = 'Last name must be at least 2 characters long';
        }

        if (!$this->csrf->validate($data)) {
            $this->messages['general'][] = 'Expired, please try again.';
            $valid = false;
        }

        return $valid;
    }

    /**
     * Hack used for testing
     *
     * @return string
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
