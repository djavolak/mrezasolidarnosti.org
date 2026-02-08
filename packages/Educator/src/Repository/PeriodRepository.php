<?php
namespace Solidarity\Educator\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Solidarity\Educator\Entity\Period;
use Solidarity\Educator\Factory\PeriodFactory;
use Skeletor\Core\TableView\Repository\TableViewRepository;

class PeriodRepository extends TableViewRepository
{
    const ENTITY = Period::class;
    const FACTORY = PeriodFactory::class;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct($entityManager);
    }

    public function getSearchableColumns(): array
    {
        return ['type'];
    }

}