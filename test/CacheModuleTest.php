<?php

namespace Papimod\Cache\Test;

use Papi\ApiBuilder;
use Papi\Test\ApiBaseTestCase;
use Papimod\Cache\CacheModule;
use Papimod\Dotenv\DotEnvModule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CacheModule::class)]
class CacheModuleTest extends ApiBaseTestCase
{
    public function testCreateCache(): void
    {
        define("ENVIRONMENT_DIRECTORY", __DIR__);
        define("ENVIRONMENT_FILE", ".test.env");

        $cache_directory = __DIR__ . DIRECTORY_SEPARATOR . ".cache";
        $action_cache_file = $cache_directory . DIRECTORY_SEPARATOR . 'routes.cache';
        $di_cache_directory = $cache_directory . DIRECTORY_SEPARATOR . 'di';

        if (file_exists($cache_directory)) {
            rmdir($cache_directory);
        }

        ApiBuilder::getInstance()
            ->setModules([
                DotEnvModule::class,
                CacheModule::class
            ])
            ->build();

        $this->assertTrue(file_exists($cache_directory));
        $this->assertTrue(file_exists($action_cache_file));
        $this->assertTrue(file_exists($di_cache_directory));
    }
}
