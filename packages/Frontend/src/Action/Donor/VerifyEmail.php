<?php
namespace Solidarity\Frontend\Action\Donor;

use Laminas\Config\Config;
use League\Plates\Engine;
use Psr\Log\LoggerInterface as Logger;
use Skeletor\Core\Security\Authentication\MagicLinkCredentials;
use Skeletor\Core\Security\Authenticator\AuthenticatorRegistry;
use Skeletor\Core\Security\EntityRegistry;
use Skeletor\Login\Exception\InvalidCredentials;
use Skeletor\Login\Service\Login;
use Skeletor\ThemeSettings\Navigation\Service\Navigation;
use Skeletor\ThemeSettings\SocialLinks\Service\SocialLinks;
use Solidarity\Frontend\Action\BaseAction;

class VerifyEmail extends BaseAction
{
    public function __construct(
        Logger $logger, Config $config, Engine $template, private \Solidarity\Delegate\Service\Delegate $delegate,
        protected Navigation $navigationService, protected SocialLinks $socialLinks,
        protected AuthenticatorRegistry $authenticatorRegistry, protected EntityRegistry $entityRegistry,
        protected Login $loginService,
        \Solidarity\Frontend\Service\Session $session,
    ) {
        parent::__construct($logger, $config, $template, $this->navigationService, $this->socialLinks, $session);

    }

    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        try {
            $token = $request->getQueryParams()['token'] ?? null;
            if (!$token) {
                return $this->redirect('/'); // @TODO "invalid/missing token" page
            }
            $credentials = new MagicLinkCredentials($token, 'donor');
            $donor = $this->authenticatorRegistry->authenticate($credentials);   // validates + consumes the token

            if ($donor->status === \Solidarity\Donor\Entity\Donor::STATUS_NEW) {        // first click = email verified
                $donor->status = \Solidarity\Donor\Entity\Donor::STATUS_VERIFIED;
                $this->entityRegistry->getRepository('donor')->updateLoginInfo($donor); // persist
            }

            $this->loginService->login($donor, 'donor');
            return $this->redirect($donor->getRedirectPath());
        } catch (InvalidCredentials $e) {
            echo $e->getMessage();

            die();
            return $this->redirect('/'); // @TODO could only be invalid/expired token, need to display message
        }

        return $this->respond('donor/thankyou', []); // @TODO go to step 3
    }
}