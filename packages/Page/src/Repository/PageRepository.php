<?php

namespace Solidarity\Page\Repository;

use Solidarity\Page\Entity\Page;
use Solidarity\Page\Factory\PageFactory;

class PageRepository extends \Skeletor\Page\Repository\PageRepository
{
    const ENTITY = Page::class;
    const FACTORY = PageFactory::class;

    const STATUS_PUBLISHED = 1;

    public function getSearchableColumns(): array
    {
        return ['a.title', 'a.slug'];
    }

    /** A published page identified by its slug within a given locale. */
    public function findPublishedBySlugAndLocale(string $slug, string $localeCode): ?Page
    {
        return $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(static::ENTITY, 'p')
            ->where('p.slug = :slug')
            ->andWhere('p.status = :status')
            ->andWhere('p.languageCode = :code')
            ->setParameter('slug', $slug)
            ->setParameter('status', self::STATUS_PUBLISHED)
            ->setParameter('code', $localeCode)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** The published homepage for a given locale. */
    public function findPublishedHomeByLocale(string $localeCode): ?Page
    {
        return $this->findPublishedBySlugAndLocale('homepage', $localeCode);
    }

    /**
     * [localeCode => slug] for every published page sharing a translation group.
     * Drives the language switcher so each locale links to its own localized slug.
     */
    public function getLocalizedSlugs(?int $translationGroupId): array
    {
        if ($translationGroupId === null) {
            return [];
        }

        $rows = $this->entityManager->createQueryBuilder()
            ->select('p.languageCode AS code', 'p.slug AS slug')
            ->from(static::ENTITY, 'p')
            ->where('p.translationGroupId = :gid')
            ->andWhere('p.status = :status')
            ->setParameter('gid', $translationGroupId)
            ->setParameter('status', self::STATUS_PUBLISHED)
            ->getQuery()
            ->getArrayResult();

        $map = [];
        foreach ($rows as $row) {
            if ($row['code'] !== null) {
                $map[$row['code']] = $row['slug'];
            }
        }

        return $map;
    }
}