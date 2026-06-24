<?php
namespace Solidarity\Frontend\Action;

use Laminas\Config\Config;
use Laminas\Session\SessionManager as Session;
use League\Plates\Engine;
use Skeletor\Core\Validator\ValidatorException;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Solidarity\Frontend\Action\BaseAction;
use Psr\Log\LoggerInterface as Logger;

class Donor extends BaseAction
{
    public function __construct(
        Logger $logger, Config $config, Engine $template, private \Solidarity\Donor\Service\Donor $donor,
        protected Navigation $navigationService,
        protected SocialLinks $socialLinks,
    ) {
        parent::__construct($logger, $config, $template, $this->navigationService, $this->socialLinks);

    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        $this->setGlobalVariable('title', 'Forma za donatore');
        $data = $request->getParsedBody();
        if (!empty($data)) {
            try {
                $this->donor->create($data);
                // @TODO send mail
                return $this->redirect('/hvalaDonatoru');
            } catch (ValidatorException $e) {
                $errors = [];
                foreach ($this->donor->parseErrors() as $key => $error) {
                    unset($data[$key]);
                    $errors[] = $error['message'];
                }
                return $this->respond('donor/signup', ['errors' => $errors, 'data' => $data]);
            }
        }

        return $this->respond('donor/signup', []);
    }
}