<?php

namespace Solidarity\Frontend\Action;

use Laminas\Config\Config;
use Laminas\Session\SessionManager as Session;
use League\Plates\Engine;
use Psr\Log\LoggerInterface as Logger;
use Skeletor\ContentEditor\Contracts\BlockViewInterface;
use Skeletor\ContentEditor\Exceptions\TemplateNotFoundException;
use Skeletor\Core\Mapper\NotFoundException;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Solidarity\Page\Service\Page;

class PageAction extends BaseAction
{
    public function __construct(
        Logger $logger,
        Config $config,
        Engine $template,
        Navigation $navigationService,
        SocialLinks $socialLinks,
        \Solidarity\Frontend\Service\Session $session,
        protected Page $pageService,
        protected BlockViewInterface $blockView
    ) {
        parent::__construct($logger, $config, $template, $navigationService, $socialLinks, $session);
        $this->setGlobalVariable('isHome', false);
        $this->setGlobalVariable(
            'homeSocialImgPath',
            $this->getConfig()->offsetGet('baseUrl') . FRONT_ASSET_URL . '/images/home.jpg'
        );
    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ): \GuzzleHttp\Psr7\Response {
        try {
            if(!$request->getAttribute('slug')) {
                throw new NotFoundException();
            }
            $page = $this->pageService->getEntities(['slug' => $request->getAttribute('slug'), 'status' => 1]);
            if(!isset($page[0])) {
                throw new NotFoundException();
            }
            if($page[0]->isLoginProtected && !$this->session->isLoggedIn()) {
                throw new NotFoundException();
            }
            $this->setSEO($page[0]);
            $this->setGlobalVariable(
                'canonical',
                $this->getConfig()->offsetGet('baseUrl') . '/' . $page[0]->slug
            );
            $content = [];
            if (isset($page[0])) {
                $content = $page[0]->blockData;
            }
            $content = $this->blockView->getView($content);
            $mainClassName = 'content';
            if($page[0]->slug === 'homepage') {
                $mainClassName = '';
            }
        } catch(TemplateNotFoundException $e) {
            throw new NotFoundException();
        }
        return $this->respond('page/page', [
            'webpSupport' => (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') >= 0),
            'content' => $content,
        ]);
    }
}