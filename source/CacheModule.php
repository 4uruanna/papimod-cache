<?php

namespace Papimod\Cache;

use Papi\PapiModule;
use Papimod\Dotenv\DotEnvModule;

use function DI\create;

final class CacheModule extends PapiModule
{
    public const DEFAULT_CACHE_KEY = "__ttl__";
    public const CACHE_DI_DIRECTORY = "di";
    public const CACHE_PAPI_FILE = "papi.cache";
    public const CACHE_ACTION_FILE = "action.cache";

    public static function getPrerequisites(): array
    {
        return [DotEnvModule::class];
    }

    public static function getEvents(): array
    {
        return [
            DiContainerCacheEvent::class,
            ActionCacheEvent::class
        ];
    }

    public static function getDefinitions(): array
    {
        return [CacheService::class => create()->constructor()];
    }

    public static function configure(): void
    {
        if (defined("PAPI_CACHE_DIRECTORY") === false) {
            $cache_directory = $_ENV["CACHE_DIRECTORY"] ?? ".cache";
            $cache_directory = PAPI_DOTENV_DIRECTORY
                . DIRECTORY_SEPARATOR
                . trim($cache_directory, DIRECTORY_SEPARATOR);

            define("PAPI_CACHE_DIRECTORY", $cache_directory);
        }
        if (defined("PAPI_CACHE_TIMEOUT") === false) {
            $cache_timeout = $_ENV["CACHE_TIMEOUT"] ?? 86400;
            $cache_timeout = (int) $cache_timeout;
            define("PAPI_CACHE_TIMEOUT", $cache_timeout);
        }
    }
}
