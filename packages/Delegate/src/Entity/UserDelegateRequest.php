<?php

namespace Solidarity\Delegate\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Skeletor\Core\Entity\Timestampable;
use Solidarity\School\Entity\School;

#[ORM\Entity]
#[ORM\Table(name: 'userDelegateRequest')]
class UserDelegateRequest
{
    use Timestampable;

    public const STATUS_NEW = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_REJECTED = 3;

    #[ORM\Column(length: 255)]
    public string $firstName;

    #[ORM\Column(length: 255)]
    public string $lastName;

    #[ORM\Column(length: 50, nullable: true)]
    public ?string $phone = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?School $school = null;

    #[ORM\Column(nullable: true)]
    public ?int $totalEducators = null;

    #[ORM\Column(nullable: true)]
    public ?int $totalBlockedEducators = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $comment = null;

    #[ORM\Column]
    public int $status = self::STATUS_NEW;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $adminComment = null;
}
