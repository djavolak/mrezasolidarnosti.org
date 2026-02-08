<?php
namespace Solidarity\User\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Skeletor\Core\Behaviors\Entity\Timestampable;
use Skeletor\User\Model\User as DtoModel;
use Solidarity\Delegate\Entity\Delegate;
use Solidarity\Educator\Entity\Educator;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    use \Skeletor\Core\Entity\Timestampable;

    const ROLE_GUEST = 0;
    const ROLE_ADMIN = 1;
    const ROLE_DELEGATE = 2;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    public string $firstName;
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    public string $lastName;
    #[ORM\Column(type: Types::STRING, length: 128, unique: true)]
    public string $email;
    #[ORM\Column(type: Types::STRING, length: 128)]
    public string $password;
    #[ORM\Column(type: Types::SMALLINT, length: 1)]
    public int $role;
    #[ORM\Column(type: Types::SMALLINT)]
    public int $isActive;
    #[ORM\Column(type: Types::STRING, length: 128)]
    public string $displayName;
    #[ORM\Column(type: Types::INTEGER, nullable: true, options:["unsigned"=>true])]
    public ?string $ipv4;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTime $lastLogin;

    #[ORM\OneToOne(targetEntity: Delegate::class, inversedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(name: 'delegate_id', referencedColumnName: 'id', nullable: true)]
    public ?Delegate $delegate;

    protected $redirectPath = '/';

//    #[ORM\ManyToOne(targetEntity: Tenant::class, fetch: 'EAGER')]
//    #[ORM\JoinColumn(name: 'tenantId', referencedColumnName: 'id')]
//    private Tenant $tenant;

//    public function setTenant(Tenant $tenant)
//    {
//        $this->tenant = $tenant;
//    }
//
//    public function getTenant()
//    {
//        return $this->tenant;
//    }

    public function getId()
    {
        return $this->id;
    }

    public function updateLoginInfo($ipv4, $lastLogin)
    {
        $this->ipv4 = $ipv4;
        $this->lastLogin = $lastLogin;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getIpv4()
    {
        return long2ip((int) $this->ipv4);
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function getRedirectPath()
    {
        return $this->redirectPath;
    }

    public static function getHrRole($type)
    {
        return static::getHrRoles()[$type];
    }

    /**
     * @return array
     */
    public static function getHrRoles(): array
    {
        return array(
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_DELEGATE => 'Delegate',
        );
    }

    public function getRole(): int
    {
        return (int) $this->role;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool) $this->isActive;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}