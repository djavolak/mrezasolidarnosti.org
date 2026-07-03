<?php
namespace Solidarity\EmailList\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Skeletor\Core\TableView\Repository\TableViewRepository;
use Solidarity\EmailList\Entity\EmailList;
use Solidarity\EmailList\Factory\EmailListFactory;

class EmailListRepository extends TableViewRepository
{
    const ENTITY = EmailList::class;
    const FACTORY = EmailListFactory::class;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct($entityManager);
    }

    public function findByEmail(string $email): ?EmailList
    {
        return $this->entityManager->getRepository(EmailList::class)->findOneBy(['email' => $email]);
    }

    public function getSearchableColumns(): array
    {
        return ['a.email'];
    }
}
