<?php

namespace Papimod\Cache;

use Papi\error\MissingModuleException;
use Papi\Module;
use Papimod\Dotenv\DotenvModule;

final class CacheModule extends Module
{
    public const DEFAULT_CACHE_DIRECTORY = '.cache';

    public const DI_CACHE_DIRECTORY = 'di';

    public const ACTION_CACHE_FILE = 'routes.cache';

    protected string $path = __DIR__;

    public function __construct()
    {
        $this->checkPrerequisites();
        $this->configure();
    }

    private function checkPrerequisites(): void
    {
        if (class_exists("Papimod\\Dotenv\\DotenvModule") === false) {
            throw new MissingModuleException(self::class, "Papimod\\Dotenv\\DotenvModule");
        }
    }

    private function configure(): void
    {
        if (defined("CACHE_DIRECTORY") === false) {
            if (isset($_SERVER['CACHE_DIRECTORY'])) {
                define(
                    "CACHE_DIRECTORY",
                    API_ENV_DIRECTORY
                        . DIRECTORY_SEPARATOR
                        . trim($_SERVER['CACHE_DIRECTORY'], DIRECTORY_SEPARATOR)
                );
            } else {
                define(
                    "CACHE_DIRECTORY",
                    API_ENV_DIRECTORY
                        . DIRECTORY_SEPARATOR
                        . CacheModule::DEFAULT_CACHE_DIRECTORY
                );
            }
        }
    }
}
