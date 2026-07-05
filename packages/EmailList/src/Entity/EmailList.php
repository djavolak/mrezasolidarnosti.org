<?php

namespace Solidarity\EmailList\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Skeletor\Core\Entity\Timestampable;

#[ORM\Entity]
#[ORM\Table(name: 'email_list')]
class EmailList
{
    use Timestampable;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    public string $email;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $isActive = true;
}
