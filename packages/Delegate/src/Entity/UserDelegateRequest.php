<?php

namespace Solidarity\Delegate\Entity;

use Solidarity\School\Entity\City;
use Solidarity\School\Entity\School;
use Solidarity\School\Entity\SchoolType;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Skeletor\Core\Entity\Timestampable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'userDelegateRequest')]
class UserDelegateRequest
{
    use Timestampable;

    public const STATUS_NEW = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_REJECTED = 3;

//    #[Assert\Length(min: 3, max: 100, minMessage: 'Polje mora imati bar {{ limit }} karaktera', maxMessage: 'Polje ne može imati više od {{ limit }} karaktera')]
    #[ORM\Column(length: 255)]
    private string $firstName;

//    #[Assert\Length(min: 3, max: 100, minMessage: 'Polje mora imati bar {{ limit }} karaktera', maxMessage: 'Polje ne može imati više od {{ limit }} karaktera')]
    #[ORM\Column(length: 255)]
    private string $lastName;

    //todo USER
//    #[ORM\OneToOne(inversedBy: 'userDelegateRequest')]
////    #[ORM\JoinColumn(nullable: false)]
//    private ?UserDelegateRequest $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\ManyToOne(inversedBy: 'userDelegateRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private School $school;

    #[ORM\Column(nullable: true)]
//    #[Assert\LessThan(value: 1000, message: 'Ukupan broj zaposlenih u školi ne može da bude veći od 1000')]
    private ?int $totalEducators = null;

    #[ORM\Column(nullable: true)]
//    #[Assert\LessThan(propertyPath: 'totalEducators', message: 'Ukupno u obustavi ne može da bude veće od ukupnog broja zaposlenih')]
    private ?int $totalBlockedEducators = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $status = 1;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adminComment = null;
}