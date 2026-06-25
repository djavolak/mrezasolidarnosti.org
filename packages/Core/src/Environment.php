<?php
namespace Solidarity\Core;

/**
 * Single source of truth for the two environment variables the app keys off:
 *
 *   APPLICATION_ENV  — the deployment environment (production | development | ...)
 *   APPLICATION      — which of the two apps is running (frontend | backend)
 *
 * Both are set per-vhost by nginx (fastcgi_param) and exported by the crontab
 * for CLI runs. Reading them through this class (instead of scattered
 * getenv()/strtolower() calls) centralises the magic strings and the
 * case-normalisation, and — unlike a constant in config/constants.php — it is
 * autoloaded, so it works in CLI/cron too (cli.php does not load constants.php).
 *
 * Values are read lazily on each call so a test can override them with putenv().
 */
final class Environment
{
    public const PRODUCTION = 'production';
    public const STAGING = 'staging';
    public const DEVELOPMENT = 'development';

    public const FRONTEND = 'frontend';
    public const BACKEND = 'backend';

    /** Deployment environment, lower-cased; empty string when unset. */
    public static function name(): string
    {
        return strtolower((string) getenv('APPLICATION_ENV'));
    }

    public static function isProduction(): bool
    {
        return self::name() === self::PRODUCTION;
    }

    /** Which app is running, lower-cased; empty string when unset. */
    public static function application(): string
    {
        return strtolower((string) getenv('APPLICATION'));
    }

    public static function isBackend(): bool
    {
        return self::application() === self::BACKEND;
    }

    public static function isFrontend(): bool
    {
        return self::application() === self::FRONTEND;
    }
}
