<?php

namespace Solidarity\Page\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Skeletor\Core\Entity\Seo;
use Skeletor\Image\Entity\Image;

#[ORM\Entity]
#[ORM\Table(name: 'page')]
class Page
{
    use \Skeletor\Core\Entity\Timestampable;

    use Seo;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true)]
    public string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description;

    #[ORM\Column(type: Types::STRING, length: 128, unique: true, nullable: true)]
    public string $slug;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public ?array $blockData;

    #[ORM\ManyToOne(targetEntity: Image::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'featuredImageId', referencedColumnName: 'id', unique: false, nullable: true)]
    public ?Image $featuredImage;

    #[ORM\Column(type: Types::INTEGER)]
    public int $status;
}