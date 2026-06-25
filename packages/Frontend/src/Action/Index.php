<?php
namespace Solidarity\Frontend\Action;

use Laminas\Config\Config;
use League\Plates\Engine;
use Skeletor\ContentEditor\Contracts\BlockViewInterface;
use Skeletor\ContentEditor\Exceptions\TemplateNotFoundException;
use Skeletor\Core\Mapper\NotFoundException;
use Skeletor\Page\Service\Page;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Psr\Log\LoggerInterface as Logger;

class Index extends BaseAction
{
    public function __construct(
        Logger $logger, Config $config, Engine $template, private \Solidarity\Donor\Service\Donor $donor,
        protected Navigation $navigationService,
        protected SocialLinks $socialLinks,
        protected Page $pageService,
        protected BlockViewInterface $blockView,
        \Solidarity\Frontend\Service\Session $session,
    ) {
        parent::__construct($logger, $config, $template, $this->navigationService, $this->socialLinks, $session);

    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        $this->setGlobalVariable('isHome', true);
        $this->setGlobalVariable('title', 'Mreža Solidarnosti');
        try {
            $homepage = $this->pageService->getEntities(['slug' => 'homepage']);
            $content = [];
            if (isset($homepage[0])) {
                $content = $homepage[0]->blockData;
            } else {
                throw new NotFoundException();
            }
            $this->setSEO($homepage[0]);
            $this->setGlobalVariable('canonical', $this->getConfig()->offsetGet('baseUrl'));
            $content = $this->blockView->getView($content);
        } catch(TemplateNotFoundException $e) {
            throw new NotFoundException();
        }

        return $this->respond('index/index', [
            'webpSupport' => (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') >= 0),
            'content' => $content,
        ]);
    }
}