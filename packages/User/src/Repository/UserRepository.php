<?php
namespace Solidarity\User\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Skeletor\Core\Mapper\NotFoundException;
use Skeletor\Tenant\Model\Tenant;

/**
 * Class UserRepository.
 *
 */
class UserRepository extends \Skeletor\User\Repository\UserRepository
{
    const FACTORY = \Solidarity\User\Factory\UserFactory::class;
    const ENTITY = \Solidarity\User\Entity\User::class;

    public function __construct(EntityManagerInterface $em, \DateTime $dt)
    {
        parent::__construct($em, $dt);
    }

    public function getSearchableColumns(): array
    {
        return ['a.email', 'a.firstName', 'a.lastName'];
    }


}