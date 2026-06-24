<?php

date_default_timezone_set('Europe/Belgrade');

return array(
    'baseUrl' => 'https://solid.djavolak.info',
    'siteName' => 'Mreža Solidarnosti',
    'appName' => 'Mreža Solidarnosti',
    'appType' => '',
    'redirectUri' => '/user/view/',
    'timezone' => 'Europe/Belgrade',
    'adminPath' => '',
    'imageBasePath' => IMAGES_PATH,
    'ignoreTrailingSlash' => true,
    'compileAssets' => false,
    'mailer' => [
        'from' => 'noreply@mrezasolidarnosti.org',
        'fromName' => 'Mreža Solidarnosti',
        // Outside production, mail is caught here via SMTP (Mailpit)
        'smtp' => [
            'host' => '127.0.0.1',
            'port' => 1025,
        ],
        'recipients' => [
            'errorNotice' => [
                'djavolak@mail.ru',
            ],
            'general' => [
                'djavolak@mail.ru',
            ],
        ],
        'server' => [],
    ],
    'captcha' => [
        'siteKey' => '',
    ],
    'cliMap' =>  [
        'test' => \Solidarity\Backend\Action\Index::class,
        'donor' => \Solidarity\Backend\Controller\DonorController::class,
        'delegate' => \Solidarity\Backend\Controller\DelegateController::class,
        'educator' => \Solidarity\Backend\Controller\EducatorController::class,
        'educatorImport' => \Solidarity\Backend\Controller\EducatorImportController::class,
        'transactionImport' => \Solidarity\Backend\Controller\TransactionImportController::class,
        'transaction' => \Solidarity\Backend\Controller\TransactionController::class,
        // Cron entry point. CliSkeletor invokes Action classes via __invoke().
        // Run: php public/cli.php createTransactions run   (the 2nd arg is ignored)
        'createTransactions' => \Solidarity\Backend\Action\CreateTransaction::class,
        // Legacy data migration. Dry-run: `php public/cli.php migrateLegacy run`
        // Commit:                `php public/cli.php migrateLegacy commit`
        'migrateLegacy' => \Solidarity\Backend\Action\MigrateLegacy::class,
    ],

);

