<?php

namespace Solidarity\EmailList\Validator;

use Skeletor\Core\Validator\ValidatorInterface;

class EmailList implements ValidatorInterface
{
    private array $messages = [];

    public function isValid(array $data): bool
    {
        $this->messages = [];

        $email = trim($data['email'] ?? '');
        if ($email === '') {
            $this->messages['email'][] = 'Email adresa je obavezna.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->messages['email'][] = 'Uneta email adresa nije ispravna. ' . $email;
        }

        return empty($this->messages);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
