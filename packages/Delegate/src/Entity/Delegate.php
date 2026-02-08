<?php

namespace Solidarity\Delegate\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Skeletor\Core\Entity\Timestampable;
use Solidarity\School\Entity\School;
use Solidarity\User\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: 'delegate')]
class Delegate
{
    use Timestampable;

    const STATUS_NEW = 1;
    const STATUS_VERIFIED = 2;
    const STATUS_PROBLEM = 3;

    #[ORM\Column(type: Types::SMALLINT)]
    public int $status;
    #[ORM\Column(type: Types::STRING, length: 16)]
    public string $phone;
    #[ORM\Column(type: Types::STRING, length: 1024, nullable: true)]
    public ?string $comment;
    #[ORM\Column(type: Types::STRING, length: 1024, nullable: true)]
    public ?string $adminComment;
    #[ORM\Column(type: Types::STRING, length: 512)]
    public string $verifiedBy;
    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: 'delegates')]
    #[ORM\JoinColumn(name: 'schoolId', referencedColumnName: 'id', unique: false, nullable: false)]
    public ?School $school;

    #[ORM\OneToOne(targetEntity: User::class, mappedBy: 'delegate')]
    public User $user;

    public static function getHrStatuses(): array
    {
        return array(
            self::STATUS_NEW => 'New',
            self::STATUS_VERIFIED => 'Verified',
            self::STATUS_PROBLEM => 'Problem',
        );
    }

    public static function getHrStatus($status): string
    {
        return static::getHrStatuses()[$status];
    }
}