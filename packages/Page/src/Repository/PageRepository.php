<?php

namespace Solidarity\Page\Repository;

use Solidarity\Page\Entity\Page;
use Solidarity\Page\Factory\PageFactory;

class PageRepository extends \Skeletor\Page\Repository\PageRepository
{
    const ENTITY = Page::class;
    const FACTORY = PageFactory::class;
    public function getSearchableColumns(): array
    {
        return ['a.title', 'a.slug'];
    }
}