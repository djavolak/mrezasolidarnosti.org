<?php
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Session\SessionManager;
use Laminas\Session\ManagerInterface;
use Laminas\Session\Config\SessionConfig;
use Monolog\ErrorHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface as Logger;
use Laminas\Config\Config;
use Skeletor\Core\Mailer\Service\MailerInterface;
use Skeletor\Core\Security\Authorization\AuthorizationService;
use Skeletor\Core\Security\EntityRegistry;
use Solidarity\Backend\Blocks\About\About;
use Solidarity\Backend\Blocks\Banner\Banner;
use Solidarity\Backend\Blocks\Connect\Connect;
use Solidarity\Backend\Blocks\Contactcards\Contactcards;
use Solidarity\Backend\Blocks\Ctabanner\Ctabanner;
use Solidarity\Backend\Blocks\Direction\Direction;
use Solidarity\Backend\Blocks\Faq\Faq;
use Solidarity\Backend\Blocks\Herotext\Herotext;
use Solidarity\Backend\Blocks\Projectsdisplay\Projectsdisplay;
use Solidarity\Backend\Blocks\Sidebyside\Sidebyside;
use Solidarity\Backend\Blocks\Threepillars\Threepillars;
use Solidarity\Backend\Blocks\Valuecards\Valuecards;
use Solidarity\Backend\Blocks\Whotocall\Whotocall;
use Solidarity\Backend\Blocks\Howitworks\Howitworks;
use Solidarity\Backend\Blocks\Testimonials\Testimonials;
use Solidarity\Backend\Blocks\Whywearedifferent\Whywearedifferent;
use Solidarity\Backend\Blocks\Find\Find;
use Solidarity\Backend\Blocks\HeroStats\HeroStats;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Tamtamchik\SimpleFlash\Flash;
use Skeletor\Core\Acl\Acl;
use \League\Flysystem\Filesystem;
use League\Plates\Engine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$containerBuilder = new \DI\ContainerBuilder;
/* @var \DI\Container $container */
$container = $containerBuilder
//    ->addDefinitions(require_once __DIR__ . '/config_web.php')
    ->build();

$container->set(ManagerInterface::class, function() use ($container) {
    // Get config values
    $config = $container->get(Config::class);
    $redisHost = array_keys($config->redis->hosts->toArray())[0];
    $redisPort = array_values($config->redis->hosts->toArray())[0];
    $sessionName = str_replace(' ', '_', $config->appName . getenv('APPLICATION'));

    // Set session name via ini_set BEFORE creating SessionConfig
    ini_set('session.name', $sessionName);
    ini_set('session.gc_maxlifetime', (string)(60*60*24));
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', sprintf('tcp://%s:%s?weight=1&timeout=1', $redisHost, $redisPort));

    $sessionConfig = new SessionConfig();
    $sessionConfig->setOptions([
        'remember_me_seconds' => 2592000, //2592000, // 30 * 24 * 60 * 60 = 30 days
        'use_cookies'         => true,
        'cookie_lifetime'     => 30 * 24 * 60 * 60,
    ]);
    $session = new SessionManager($sessionConfig);
    $session->start();

    return $session;
});

$container->set(\Skeletor\ContentEditor\Contracts\BlockParserFactoryInterface::class, function() use ($container) {
    $blockParserFactory =  new \Skeletor\ContentEditor\Factory\BlockParserFactory(
        $container->get(\Skeletor\Image\Service\Image::class)
    );

    $blockParserFactory->registerBlockParser(HeroStats::NAME, new HeroStats());
    $blockParserFactory->registerBlockParser(Find::NAME, new Find(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));
    $blockParserFactory->registerBlockParser(Direction::NAME, new Direction());
    $blockParserFactory->registerBlockParser(Connect::NAME, new Connect());
    $blockParserFactory->registerBlockParser(Whywearedifferent::NAME, new Whywearedifferent());
    $blockParserFactory->registerBlockParser(Howitworks::NAME, new Howitworks(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));
    $blockParserFactory->registerBlockParser(Testimonials::NAME, new Testimonials());
    $blockParserFactory->registerBlockParser(Faq::NAME, new Faq());
    $blockParserFactory->registerBlockParser(Herotext::NAME, new Herotext());
    $blockParserFactory->registerBlockParser(Contactcards::NAME, new Contactcards(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));
    $blockParserFactory->registerBlockParser(Sidebyside::NAME, new Sidebyside());
    $blockParserFactory->registerBlockParser(Projectsdisplay::NAME, new Projectsdisplay(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));
    $blockParserFactory->registerBlockParser(Threepillars::NAME, new Threepillars(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));
    $blockParserFactory->registerBlockParser(Banner::NAME, new Banner());
    $blockParserFactory->registerBlockParser(Whotocall::NAME, new Whotocall());
    $blockParserFactory->registerBlockParser(Ctabanner::NAME, new Ctabanner());
    $blockParserFactory->registerBlockParser(About::NAME, new About());
    $blockParserFactory->registerBlockParser(Valuecards::NAME, new Valuecards(
        $container->get(\Skeletor\Image\Service\Image::class)
    ));

    return $blockParserFactory;
});

$container->set(\Skeletor\ContentEditor\Contracts\ContentEditorParserInterface::class, function() use ($container) {
    $parser = new \Skeletor\ContentEditor\Parser(
        $container->get(\Skeletor\ContentEditor\Contracts\BlockParserFactoryInterface::class)
    );
//    $parser->registerCustomData('customName', 'blockName or empty for all blocks');
    return $parser;
});

$container->set(\Skeletor\ContentEditor\Contracts\BlockViewInterface::class, function() use ($container) {
    $view = new \Skeletor\ContentEditor\View(
        $container->get(Engine::class),
        APP_PATH . '/themes/frontend/blocks'
    );

    $view->registerViewFilter(HeroStats::NAME, new \Solidarity\Backend\Blocks\HeroStats\HeroStatsViewFilter(
        $container->get(\Solidarity\Donor\Service\Donor::class),
        $container->get(\Solidarity\Beneficiary\Service\Beneficiary::class),
        $container->get(\Solidarity\Transaction\Service\Transaction::class)
    ));

    return $view;
});

$container->set(\Skeletor\Exporter\Contracts\ExporterFactoryInterface::class, function() use ($container) {
    return new \Skeletor\Exporter\ExporterFactory($container->get(\Skeletor\Translator\Service\Translator::class));
});

$container->set(\Skeletor\User\Repository\UserRepositoryInterface::class, function() use ($container) {
    return $container->get(\Solidarity\User\Repository\UserRepository::class);
});

$container->set(Engine::class, function() use ($container) {
    $path = 'admin';
    if (getenv('APPLICATION') === 'backend') {
        $path = 'admin';
    }
    if (getenv('APPLICATION') === 'frontend') {
        $path = 'frontend';
    }
    $defaultTheme = APP_PATH . '/vendor/dj_avolak/skeletor/themes/' . $path;
    $mailTheme = APP_PATH . '/themes/email';
    $theme = APP_PATH . '/themes/' . $path;
    $plates = new \League\Plates\Engine($theme);
    $plates->addFolder('defaultTheme', $defaultTheme, true);
    $plates->addFolder('emailTheme', $mailTheme, true);
    $plates->addFolder('layout', APP_PATH . sprintf('/themes/%s/layout', $path));
    $plates->addFolder('partialsGlobal', APP_PATH . sprintf('/themes/%s/partials/global', $path));
    $plates->addFolder('partialsGlobalDefault', $defaultTheme . '/partials/global');
    $plates->registerFunction('printError', function($error, $label) use($plates) {
        return $plates->render('partialsGlobal::error', ['error' => $error, 'label' => $label]);
    });
    $plates->registerFunction('formToken', function () { return \Volnix\CSRF\CSRF::getHiddenInputString(); });
    $plates->registerFunction('formTokenArray', function () { return  \Volnix\CSRF\CSRF::getTokenAsArray(); });
    $plates->registerFunction('t', function ($string) { return $string; });
//    $plates->loadExtension($container->get(\Skeletor\Translator\Service\Translator::class));

    return $plates;
});

$container->set(Filesystem::class, function() use ($container) {
    $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(APP_PATH);

    return new Filesystem($adapter);
});

$container->set(\FastRoute\Dispatcher::class, function() use ($container) {
    $adminPath = $container->get(Config::class)->adminPath;
    $routeList = require APP_PATH . sprintf('/config/%s/routes.php', getenv('APPLICATION'));

    /** @var \FastRoute\Dispatcher $dispatcher */
    return FastRoute\simpleDispatcher(
        function (\FastRoute\RouteCollector $r) use ($routeList) {
            foreach ($routeList as $routeDef) {
                $r->addRoute($routeDef[0], $routeDef[1], $routeDef[2]);
            }
        }
    );
});

$container->set(Acl::class, function() use ($container) {
    return new Acl(
        $container->get(ManagerInterface::class),
        $container->get(Config::class),
        require APP_PATH . sprintf('/config/%s/acl.php', getenv('APPLICATION')),
        require APP_PATH . sprintf('/config/%s/aclMessages.php', getenv('APPLICATION'))
    );
});

if (getenv('APPLICATION') === 'backend') {
    $container->set(Skeletor\Core\Middleware\MiddlewareInterface::class, function () use ($container) {
        return new \Skeletor\Core\Middleware\AuthMiddleware(
            $container->get(ManagerInterface::class),
            $container->get(Config::class),
            $container->get(Flash::class),
            $container->get(Acl::class),
            $container->get(\Skeletor\Core\Security\EntityRegistry::class),
            $container->get(AuthorizationService::class),
            true  // Enable voter-based authorization
        );
    });
}

$container->set(Config::class, function() use ($container) {
    $config = new Config(include(APP_PATH . "/config/config.php"), true);
    $config = $config->merge(new Config(include(APP_PATH . "/config/config-local.php"), true));
    if (file_exists(APP_PATH . sprintf("/config/%s/config-local.php", getenv('APPLICATION')))) {
        $config = $config->merge(new Config(include(APP_PATH . sprintf("/config/%s/config-local.php", getenv('APPLICATION'))), true));
    }

    return $config;
});

$container->set(\Skeletor\Core\Action\Web\NotFoundInterface::class, function() use ($container) {
    return $container->get(\Skeletor\Core\Action\Web\NotFound::class);
});

$container->set(Logger::class, function() use ($container) {
    $logger = new \Monolog\Logger($container->get(Config::class)->appName . getenv('APPLICATION'));
    $date = $container->get(\DateTime::class);
    $logDir = DATA_PATH . '/logs/';
    $logSubDir = $logDir . $date->format('Y') . '-' . $date->format('m');
    $logFile = $logSubDir . '/' . gethostname() . '-'. getenv('APPLICATION') .'-' . $date->format('d') . '.log';
    $debugLog = DATA_PATH . '/logs/'. gethostname() . '-'. getenv('APPLICATION') .'-debug.log';
    // create dir or file if needed
    if (!is_dir($logDir)) {
        mkdir($logDir);
    }
    if (!is_dir($logSubDir)) {
        mkdir($logSubDir);
    }
    if (!is_file($logFile)) {
        touch($logFile);
    }
    $logger->pushHandler(
        new StreamHandler($debugLog,\Monolog\Level::Info)
    );

    $logger->pushHandler(
        new StreamHandler($logFile, \Monolog\Level::Error, false)
    );
    $env = strtolower(getenv('APPLICATION_ENV'));
    if ($env && strtolower($env) === 'production') {
        $mailHandler = new \Skeletor\Core\Mailer\Service\MonologHandler(\Monolog\Level::Error, true);
        $mailHandler->setMail($container->get(\Skeletor\Core\Mailer\Service\PhpMailer::class));
        $logger->pushHandler($mailHandler);
    }

    if ($env !== 'production') {
        $logger->pushHandler(new BrowserConsoleHandler());
    }
    ErrorHandler::register($logger);

    return $logger;
});

$container->set(\Redis::class, function() use ($container) {
    $config = $container->get(Config::class);
    $redis = new \Redis();
    foreach ($config->redis->hosts as $host => $port) {
        $redis->connect($host, $port);
    }
    return $redis;
});

$container->set(\DateTime::class, function() use ($container) {
    $dt = new \DateTime('now', new \DateTimeZone($container->get(Config::class)->offsetGet('timezone')));
    return $dt;
});

$container->set(Flash::class, function () use ($container) {
    //session needs to be started for flash
    $container->get(ManagerInterface::class);
    $flash = new Flash();
    $flash->setTemplate(new \Skeletor\Flash\Template\SkeletorTemplate());
    return $flash;
});

$container->set(\MailerSend\MailerSend::class, function() use ($container) {
    return new \MailerSend\MailerSend(['api_key' => $container->get(Config::class)->mailer->server->mailersend->apiKey]);
});

$container->set(MailerInterface::class, function() use ($container) {
    return $container->get(\Skeletor\Core\Mailer\Service\MailerSendMailer::class);
});

if (getenv('APPLICATION') === 'backend') {
    // Configure Permission Registry for voter-based authorization
    $container->set(\Skeletor\Core\Security\Authorization\PermissionRegistry::class, function() use ($container) {
        $config = require APP_PATH . '/config/backend/permissions.php';
        return new \Skeletor\Core\Security\Authorization\PermissionRegistry($config);
    });

    $container->set(EntityRegistry::class, function() use ($container) {
        $registry = new EntityRegistry();
        $registry->register(
            'user',
            \Solidarity\User\Entity\User::class,
            $container->get(\Solidarity\User\Repository\UserRepository::class)
        );
        $registry->register(
            'delegate',
            \Solidarity\Delegate\Entity\Delegate::class,
            $container->get(\Solidarity\Delegate\Repository\DelegateRepository::class)
        );

        return $registry;
    });

    $container->set(\Skeletor\Login\Provider\ProviderInterface::class, function() use ($container) {
        return new \Skeletor\Login\Provider\DbProvider(
            $container->get(\Skeletor\User\Repository\UserRepositoryInterface::class)
        );
    });

    $container->set(\Skeletor\Login\Validator\ResetPasswordInterface::class, function() use ($container) {
        return $container->get(\Skeletor\Login\Validator\ResetPasswordLoose::class);
    });
}
$container->set(TagAwareAdapter::class, function() use ($container) {
    $config = $container->get(Config::class);

    //@TODO add failover
    $dsn = "redis://" . array_key_first($config->redis->hosts->toArray()) . $config->redis->hosts[0];
    $redisClient = RedisAdapter::createConnection($dsn);
    $redisAdapter = new RedisAdapter($redisClient);
    $cache = new TagAwareAdapter($redisAdapter);

    return $cache;
});

$container->set(EntityManagerInterface::class, function() use ($container) {
    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: [
            APP_PATH . "/packages/Delegate/src/Entity",
            APP_PATH . "/packages/Donor/src/Entity",
            APP_PATH . "/packages/Transaction/src/Entity",
            APP_PATH . "/packages/Period/src/Entity",
            APP_PATH . "/packages/Beneficiary/src/Entity",
            APP_PATH . "/packages/School/src/Entity",
            APP_PATH . "/packages/User/src/Entity",
            APP_PATH . "/packages/Page/src/Entity",
            APP_PATH . '/vendor/dj_avolak/skeletor/src/ThemeSettings',
            APP_PATH . "/vendor/dj_avolak/skeletor/src/Image",
            APP_PATH . '/vendor/dj_avolak/skeletor/src/File',
            APP_PATH . "/vendor/dj_avolak/skeletor/src/Image",
            APP_PATH . "/vendor/dj_avolak/skeletor/src/Login",
            APP_PATH . '/vendor/dj_avolak/skeletor/src/ThemeSettings',

        ],
//            APP_PATH . "/packages"],
        isDevMode: true,
    );
    $config->setAutoGenerateProxyClasses(true);
//    $resultCache = new Symfony\Component\Cache\Adapter\RedisTagAwareAdapter($container->get(\Redis::class));
//    $config->setResultCache($resultCache);
//    $config->setMetadataCache($resultCache);
//    $config->setHydrationCache($resultCache);
    $dbConfig = $container->get(Config::class);
    $connection = \Doctrine\DBAL\DriverManager::getConnection([
        'dbname' => $dbConfig->db->write->name,
        'user' => $dbConfig->db->write->user,
        'password' => $dbConfig->db->write->pass,
        'host' => $dbConfig->db->write->host,
        'driver' => 'pdo_mysql',
    ], $config);
    $eventManager = new \Doctrine\Common\EventManager();
    $config->addCustomStringFunction('DATE', function () {
        return new DoctrineExtensions\Query\Mysql\Date('DATE');
    });
    $config->addCustomStringFunction('YEAR', function () {
        return new DoctrineExtensions\Query\Mysql\Year('YEAR');
    });

    $em = new EntityManager($connection, $config, $eventManager);

    return $em;
});

return $container;