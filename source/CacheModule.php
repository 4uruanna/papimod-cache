<?php

namespace Papimod\Cache;

use Papi\ApiModule;
use Papimod\Dotenv\DotEnvModule;

final class CacheModule extends ApiModule
{
    public function __construct()
    {
        $this->prerequisite_list = [DotEnvModule::class];

        $this->event_list = [
            ActionCacheEvent::class,
            DiCacheEvent::class
        ];
    }

    public function configure(): void
    {
        if (defined("CACHE_DIRECTORY") === false) {
            $cache_directory = ENVIRONMENT_DIRECTORY
                . DIRECTORY_SEPARATOR
                . (
                    isset($_SERVER['CACHE_DIRECTORY'])
                    ? trim($_SERVER['CACHE_DIRECTORY'], DIRECTORY_SEPARATOR)
                    : ".cache"
                );

            define("CACHE_DIRECTORY", $cache_directory);
        }
    }
}
