<?php
namespace Solidarity\Donor\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Skeletor\Core\Factory\AbstractFactory;
use Solidarity\Donor\Entity\Donor;
use Solidarity\Donor\Entity\PaymentMethod;
use Solidarity\Transaction\Entity\Project;

class DonorFactory extends AbstractFactory
{
    public static function compileEntityForCreate($data, EntityManagerInterface $em)
    {
        $paymentMethodsData = $data['paymentMethods'] ?? [];
        unset($data['paymentMethods']);

        $data['projects'] = self::resolveProjectEntities($data, $em);
        $entityId = parent::compileEntityForCreate($data, $em);

        static::syncPaymentMethods($entityId, $paymentMethodsData, $em);

        return $entityId;
    }

    public static function compileEntityForUpdate($data, $em)
    {
        $paymentMethodsData = $data['paymentMethods'] ?? [];
        unset($data['paymentMethods']);

        $projects = self::resolveProjectEntities($data, $em);
        unset($data['projects']);

        $entity = $em->getRepository(Donor::class)->find($data['id']);
        $entity->projects->clear();
        foreach ($projects as $project) {
            $entity->projects->add($project);
        }
        $entity = static::formatForWrite($entity, $data, $em);

        static::syncPaymentMethods($entity->id, $paymentMethodsData, $em);

        return $entity->id;
    }

    private static function syncPaymentMethods(int $donorId, array $rows, EntityManagerInterface $em): void
    {
        // Delete existing payment methods for this donor
        $existing = $em->getRepository(PaymentMethod::class)
            ->findBy(['donor' => $donorId]);
        foreach ($existing as $pm) {
            $em->remove($pm);
        }
        $em->flush();

        $donor = $em->getRepository(Donor::class)->find($donorId);

        foreach ($rows as $row) {
            if (empty($row['type'])) {
                continue;
            }

            $project = $em->getRepository(Project::class)->find($row['project']);
            if (!$project) {
                continue;
            }

            $pm = new PaymentMethod();
            $pm->donor = $donor;
            $pm->project = $project;
            $pm->type = (int) $row['type'];
            $pm->monthly = (int) $row['monthly'];
            $pm->amount = (int) $row['amount'];
            $pm->currency = (int) $row['currency'];
            $em->persist($pm);
        }
        $em->flush();
    }

    private static function resolveProjectEntities(array $data, EntityManagerInterface $em): array
    {
        if (!empty($data['projects']) && is_array($data['projects'])) {
            $repo = $em->getRepository(Project::class);
            $projects = array_map(fn($id) => $repo->find((int) $id), $data['projects']);
            return array_filter($projects);
        }
        return [];
    }
}
