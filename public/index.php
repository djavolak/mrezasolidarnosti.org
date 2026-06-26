<?php

use Psr\Log\LoggerInterface;
use Skeletor\Core\App\WebSkeletor;

error_reporting(E_ALL);
ini_set('display_errors', 0);

include(__DIR__ . "/../config/constants.php");
include(APP_PATH . "/vendor/autoload.php");
$path = getenv('APPLICATION');
if (!\Solidarity\Core\Environment::isProduction()) {
    \Tracy\Debugger::enable(false);
}


try {
    /* @var \DI\Container $container */
//    $container = require sprintf('%s/config/%s/bootstrap.php', APP_PATH, $path);
    $container = require sprintf('%s/config/bootstrap.php', APP_PATH);
    // Frontend i18n: resolve the locale from the URL prefix and strip it before
    // routing, so every language dispatches the same base routes. Expose the
    // resolved locale to every template as shared Plates data.
    if ($path === 'frontend') {
        $locale = $container->get(\Solidarity\Frontend\Service\Locale::class);
        $locale->detectFromRequest();
        $container->get(\League\Plates\Engine::class)->addData([
            'currentLocale'    => $locale->current(),
            'defaultLocale'    => $locale->default(),
            'availableLocales' => $locale->available(),
            'localeAlternates' => $locale->alternates(),
        ]);
    }
    $app = new WebSkeletor($container, $container->get(LoggerInterface::class));
} catch (\Exception $e) {
    if (isset($app) && $app) {
        $app->handleErrors($e);
        exit();
    }
    // @TODO handle better
    echo 'There was an unknown error with application. More info: ' . $e->getMessage() . PHP_EOL;
    echo '********************* Stack trace **********************************' . PHP_EOL;
    var_dump($e->getTrace());
    exit();
}
$app->respond();
