<?php

namespace Papimod\Cache;

use Exception;
use Papi\Module;

final class CacheModule extends Module
{
    public const TMP_DIRECTORY = 'tmp';
    public const DI_DIRECTORY = 'di';
    public const DI_PROXIES_FILE = 'di_proxies.cache';
    public const ROUTE_CACHE_FILE = 'routes.cache';

    protected string $path = __DIR__;

    public static function isProduction(): bool
    {
        return isset($_SERVER["ENVIRONMENT"])
            && $_SERVER["ENVIRONMENT"] === "PRODUCTION";
    }

    public function __construct()
    {
        if (defined("PAPI_DOTENV_DIRECTORY") === false) {
            throw new Exception("dotenv module is required");
        }

        if (defined("PAPI_TMP_DIRECTORY") === false) {
            define(
                "PAPI_TMP_DIRECTORY",
                join(
                    DIRECTORY_SEPARATOR,
                    [PAPI_DOTENV_DIRECTORY, $_SERVER['TMP_DIRECTORY'] ?? CacheModule::TMP_DIRECTORY]
                )
            );
        }
    }
}
