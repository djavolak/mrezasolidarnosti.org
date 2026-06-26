<?php

/**
 * Define routes here.
 *
 * Routes follow this format:
 *
 * [METHOD, ROUTE, CALLABLE] or
 * [METHOD, ROUTE, [Class => method]]
 *
 * When controller is used without method (as string), it needs to have a magic __invoke method defined.
 *
 * Routes can use optional segments and regular expressions. See nikic/fastroute
 */
//@TODO find a proper way to use adminPath
/**
 * @var $adminPath string secret path to admin
 */

use Solidarity\Frontend\Action\PageAction;

return [
    // backend
    [['GET'], '/', \Solidarity\Frontend\Action\Index::class],
    [['POST'], '/donor/register', \Solidarity\Frontend\Action\Donor\Register::class],
    [['GET'], '/donor/verifyEmail', \Solidarity\Frontend\Action\Donor\VerifyEmail::class],
    [['POST'], '/donor/login', \Solidarity\Frontend\Action\Donor\Login::class],
    [['GET'], '/donor/logout', \Solidarity\Frontend\Action\Donor\Logout::class],
    [['POST'], '/donor/updateProfileData', \Solidarity\Frontend\Action\Donor\UpdateProfileData::class],

    [['GET'], '/{slug}', PageAction::class],

];
