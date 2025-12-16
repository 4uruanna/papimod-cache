<?php

namespace Papimod\Cache;

use Papi\ApiModule;
use Papimod\Dotenv\DotEnvModule;

use function DI\create;

final class CacheModule extends ApiModule
{
    public const DEFAULT_DIRECTORY = ".cache";
    public const DEFAULT_CACHE_TIMEOUT = 900;
    public const DEFAULT_CACHE_AT_KEY = "__at__";
    public const PAPI_CACHE_FILE = "papi.cache";

    public function __construct()
    {
        $this->prerequisite_list = [DotEnvModule::class];

        $this->event_list = [
            ActionCacheEvent::class,
            DiContainerCacheEvent::class
        ];

        $this->definition_list = [
            CacheService::class => create()->constructor()
        ];
    }

    /**
     * Configures the module
     */
    public function configure(): void
    {
        $this->defineDirectory();
        $this->defineCacheTimeout();
    }

    /**
     * Defines the cache directory
     */
    private function defineDirectory(): void
    {
        if (defined("CACHE_DIRECTORY") === false) {
            $directory = ENVIRONMENT_DIRECTORY . DIRECTORY_SEPARATOR;

            if (isset($_SERVER["CACHE_DIRECTORY"])) {
                $directory .= trim($_SERVER["CACHE_DIRECTORY"], DIRECTORY_SEPARATOR);
            } else {
                $directory .= self::DEFAULT_DIRECTORY;
            }

            define("CACHE_DIRECTORY", $directory);
        }
    }

    /**
     * Defines the cache timeout
     */
    private function defineCacheTimeout(): void
    {
        if (defined("CACHE_TIMEOUT") === false) {
            $timeout = self::DEFAULT_CACHE_TIMEOUT;

            if (isset($_SERVER["CACHE_TIMEOUT"])) {
                $timeout = (int) $_SERVER["CACHE_TIMEOUT"];
            }

            define("CACHE_TIMEOUT", $timeout);
        }
    }
}
