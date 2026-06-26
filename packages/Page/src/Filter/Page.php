<?php

namespace Solidarity\Page\Filter;

use Laminas\Filter\ToInt;
use Skeletor\Blog\Service\UrlHelper;
use Skeletor\Core\Validator\ValidatorException;
use Volnix\CSRF\CSRF;

class Page extends \Skeletor\Page\Filter\Page
{
    public function filter(array $postData) : array
    {
        $blockData = [];
        if(isset($postData['contentEditor'][0]['blocks'])) {
            $blockData = $postData['contentEditor'][0]['blocks'];
        }
        $slug = UrlHelper::slugify($postData['title']);
        if ($postData['slug']) {
            $slug = UrlHelper::slugify($postData['slug']);
        }
        $data = [
            'id' => (isset($postData['id'])) ? (new ToInt())->filter($postData['id']) : null,
            'title' => $postData['title'],
            'slug' => $slug,
            'status' => $postData['status'],
            'featuredImageId' => $postData['featuredImageId'] ?? '',
            'blockData' => $this->parser->parse($blockData),
            'seoTitle' => $postData['seoTitle'] ?? null,
            'seoDescription' => $postData['seoDescription'] ?? null,
            'seoImageId' => $postData['seoImageId'] ?? '',
            'isLoginProtected' => $postData['isLoginProtected'] === 'on' ? true : false,
            CSRF::TOKEN_NAME => $postData[CSRF::TOKEN_NAME],
        ];
        if (!$this->validator->isValid($data)) {
            throw new ValidatorException();
        }
        unset($data[CSRF::TOKEN_NAME]);

        return $data;
    }
}