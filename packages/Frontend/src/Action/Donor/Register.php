<?php
namespace Solidarity\Frontend\Action\Donor;

use Laminas\Config\Config;
use League\Plates\Engine;
use Psr\Log\LoggerInterface as Logger;
use Skeletor\Core\Validator\ValidatorException;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Solidarity\Frontend\Action\BaseAction;

class Register extends BaseAction
{
    public function __construct(
        Logger $logger, Config $config, Engine $template, private \Solidarity\Donor\Service\Donor $donor,
        protected Navigation $navigationService,
        protected SocialLinks $socialLinks,
        \Solidarity\Frontend\Service\Session $session,
    ) {
        parent::__construct($logger, $config, $template, $this->navigationService, $this->socialLinks, $session);

    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        $data = $request->getParsedBody();
        if (!empty($data)) {
            try {
                $data['isActive'] = 1;
                $data['wantsToDonateTo'] = null;
                $data['comment'] = '';
                $data['projects'] = [];
                $data['status'] = \Solidarity\Donor\Entity\Donor::STATUS_NEW;
                $this->donor->create($data);

                return $this->redirect('/hvalaDonatoru'); // @TODO step one complete, magic link sent, redirect or return json
            } catch (\Exception $e) {
                if ($e->getMessage() === "Donor already exists") {
                    //@TODO redirect to login with message?
                    var_dump($e->getMessage());
                    die();
                }
                var_dump($e->getMessage());
                die();

                return $this->respond('donor/signup', ['errors' => $errors, 'data' => $data]);
            }
        }

        return $this->respond('donor/signup', []);
    }
}