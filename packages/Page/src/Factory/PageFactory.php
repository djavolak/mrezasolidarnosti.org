<?php

namespace Solidarity\Page\Factory;

use Skeletor\Image\Entity\Image;

class PageFactory extends \Skeletor\Page\Factory\PageFactory
{
    public static function compileEntityForCreate($data, $em)
    {
        $page = new \Solidarity\Page\Entity\Page();
        $page->title = $data['title'];
        $page->slug = $data['slug'];
        $page->status = $data['status'];
        if(isset($data['featuredImageId'])) {
            $image = $em->getRepository(Image::class)->find($data['featuredImageId']);
            $page->featuredImage = $image;
        }
        $page->blockData = $data['blockData'];
        $page->seoTitle = $data['seoTitle'] ?? $page->title;
        $page->seoDescription = $data['seoDescription'] ?? '';
        $page->isLoginProtected = $data['isLoginProtected'] ?? false;
        $page->languageCode = $data['languageCode'] ?? 'sr';
        if(isset($data['seoImageId'])) {
            $image = $em->getRepository(Image::class)->find($data['seoImageId']);
            $page->seoImage = $image;
        } else {
            $page->seoImage = null;
        }
        $em->persist($page);
        $em->flush();

        return $page->getId();
    }

    public static function compileEntityForUpdate($data, $em)
    {
        $page = $em->getRepository(\Solidarity\Page\Entity\Page::class)->find($data['id']);
        $page->title = $data['title'];
        $page->slug = $data['slug'];
        $page->status = $data['status'];
        if(isset($data['featuredImageId'])) {
            $image = $em->getRepository(Image::class)->find($data['featuredImageId']);
            $page->featuredImage = $image;
        } else {
            $page->featuredImage = null;
        }
        $page->blockData = $data['blockData'];
        $page->seoTitle = $data['seoTitle'] ?? $page->title;
        $page->seoDescription = $data['seoDescription'] ?? '';
        $page->isLoginProtected = $data['isLoginProtected'] ?? false;
        $page->languageCode = $data['languageCode'] ?? 'sr';
        if(isset($data['seoImageId'])) {
            $image = $em->getRepository(Image::class)->find($data['seoImageId']);
            $page->seoImage = $image;
        } else {
            $page->seoImage = null;
        }

        return $page->getId();
    }
}