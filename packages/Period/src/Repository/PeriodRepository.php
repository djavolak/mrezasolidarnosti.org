<?php
namespace Solidarity\Period\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Solidarity\Period\Entity\Period;
use Solidarity\Period\Factory\PeriodFactory;
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

    /**
     * All processing periods — the ones open for transaction creation (both the cron
     * and the on-demand donor flow draw from these). Distinct from `active`, which only
     * governs whether delegates can add beneficiaries.
     *
     * @return Period[]
     */
    public function fetchProcessing(): array
    {
        return $this->entityManager->getRepository(Period::class)->findBy(['processing' => true]);
    }

}
