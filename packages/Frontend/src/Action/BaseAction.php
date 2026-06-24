<?php
namespace Solidarity\Frontend\Action;

use Laminas\Config\Config;
use League\Plates\Engine;
use Psr\Log\LoggerInterface as Logger;
use Skeletor\Core\Action\Web\Html;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Tamtamchik\SimpleFlash\Flash;

class BaseAction extends Html
{

    public function __construct(
        Logger $logger,
        Config $config,
        Engine $template,
        protected Navigation $navigationService,
        protected SocialLinks $socialLinks,
    ) {
        parent::__construct($logger, $config, $template);
	    $this->setGlobalVariable( 'url', $this->getConfig()->offsetGet( 'baseUrl' ));
        $this->setGlobalVariable('isHome', false);
        $this->setGlobalVariable('mainNavigation', $this->navigationService->getByTitle("Main Navigation"));
        $socialLinks = $this->socialLinks->getSocialItems();
        $this->setGlobalVariable('socialLinks', \Solidarity\Frontend\Service\SocialLinks\SocialLinks::getSocialLinks($socialLinks));
        if (Flash::hasMessages('error')) { // print only errors
            $this->setGlobalVariable('messages', Flash::display());
        }
    }

    public function return404()
    {
        return $this->respond('/index/404');
    }

    protected function setSEO($model): void
    {
        if (property_exists($model, 'seoTitle')) {
            if(property_exists($model, 'slug') && $model->slug === 'homepage') {
                $this->setGlobalVariable('title', $model->seoTitle);
            } else {
                $this->setGlobalVariable('title', $model->seoTitle . ' - ' . $this->getConfig()->siteName);
            }
        } else {
            $this->setGlobalVariable('title', $this->getConfig()->siteName);
        }

        if (property_exists($model, 'seoDescription')) {
            $this->setGlobalVariable('description', $model->seoDescription);
        } else {
            $this->setGlobalVariable('description', $this->getConfig()->siteName);
        }

        if(property_exists($model, 'seoImage') && $model->seoImage) {
            $this->setGlobalVariable('seoImageSrc', $this->getConfig()->offsetGet('baseUrl') . '/images' . $model->seoImage->filename);
            $this->setGlobalVariable('seoImageAlt', $model->seoImage->alt);
        } else {
            $this->setGlobalVariable('seoImageSrc', $this->getConfig()->offsetGet('baseUrl') . FRONT_ASSET_URL . '/images/home.png');
            $this->setGlobalVariable('seoImageAlt', $this->getConfig()->siteName);
        }
    }
}