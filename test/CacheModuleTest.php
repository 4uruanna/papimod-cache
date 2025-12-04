<?php

namespace Papimod\Cache\Test;

use Papi\AppBuilder;
use Papimod\Cache\CacheModule;
use Papimod\Dotenv\DotenvModule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CacheModule::class)]
class CacheModuleTest extends TestCase
{
    public function testCreateCache(): void
    {
        $cache_directory = __DIR__ . DIRECTORY_SEPARATOR . ".cache";

        if (file_exists($cache_directory)) {
            rmdir($cache_directory);
        }

        define("API_ENV_DIRECTORY", __DIR__);

        $app = new AppBuilder()
            ->setModules([
                DotenvModule::class,
                CacheModule::class
            ])
            ->build();

        $this->assertTrue(file_exists($cache_directory));
        $this->assertTrue(file_exists($cache_directory . DIRECTORY_SEPARATOR . CacheModule::DI_CACHE_DIRECTORY));
        $this->assertTrue(file_exists($cache_directory . DIRECTORY_SEPARATOR . CacheModule::ACTION_CACHE_FILE));
    }
}
