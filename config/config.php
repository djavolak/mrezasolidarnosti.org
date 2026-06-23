<?php

date_default_timezone_set('Europe/Belgrade');

const PORTRAIT_600x820 = 'portrait_600x820';

const THUMBNAIL_250x500 = 'portrait_250x500';

const SINGLE_350x150 = 'landscape_350x150';

const SINGLE_350x700 = 'portrait_350x700';

const LANDSCAPE_1200x800 = 'landscape_1200x800';

const LANDSCAPE_1000x667 = 'landscape_1000x667';

const LANDSCAPE_800x533 = 'landscape_800x533';

const LANDSCAPE_600x400 = 'landscape_600x400';

const LANDSCAPE_400x267 = 'landscape_400x267';

const LANDSCAPE_300x200 = 'landscape_300x200';

const LANDSCAPE_250x167 = 'landscape_250x167';

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
        'transaction' => \Solidarity\Backend\Controller\TransactionController::class
    ],
    'cropSizes' => [
        PORTRAIT_600x820 => [600, 820, true],
        LANDSCAPE_1200x800 => [1200,800, true],
        LANDSCAPE_1000x667 => [1000,667, true],
        LANDSCAPE_600x400 => [600,400, true],
        LANDSCAPE_400x267 => [400,267, true],
        LANDSCAPE_300x200 => [300,200, true],
        LANDSCAPE_250x167 => [250,167, true],
        THUMBNAIL_250x500 => [250,125, false],
        SINGLE_350x150 => [350,150, false],
        SINGLE_350x700 => [350, 700, false]
    ]
);

