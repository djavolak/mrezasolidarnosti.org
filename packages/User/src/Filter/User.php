<?php
namespace Solidarity\User\Filter;

use Laminas\Filter\ToInt;
use Laminas\I18n\Filter\Alnum;
use Skeletor\Core\Validator\ValidatorException;
use Solidarity\User\Validator\User as UserValidator;
use Volnix\CSRF\CSRF;

class User extends \Skeletor\User\Filter\User
{

    protected $validator;

    public function __construct(UserValidator $validator)
    {
        parent::__construct($validator);
    }

    public function getErrors()
    {
        return $this->validator->getMessages();
    }

    public function filter(array $postData) : array
    {
        $alnum = new Alnum(true);
        $int = new ToInt();
        if ((int) $postData['role'] === \Solidarity\User\Entity\User::ROLE_ADMIN) {
            $postData['delegate'] = null;
        }
        $data = [
            'id' => (isset($postData['id'])) ? $postData['id'] : null,
            'email' => $postData['email'],
            'role' => $postData['role'],
            'isActive' => $int->filter($postData['isActive']),
            'displayName' => (strlen($alnum->filter($postData['displayName'])) > 0) ? $alnum->filter($postData['displayName']) :
                $alnum->filter($postData['firstName'] .' '. $postData['lastName']),
            'firstName' => $alnum->filter($postData['firstName']),
            'lastName' => $alnum->filter($postData['lastName']),
            'delegate' => $postData['delegate'],
            CSRF::TOKEN_NAME => $postData[CSRF::TOKEN_NAME],
        ];
        if (!$this->validator->isValid($data)) {
            throw new ValidatorException();
        }
        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }

}