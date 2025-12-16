<?php

namespace Papimod\Cache\Test;

use Papi\ApiBuilder;
use Papimod\Cache\CacheModule;
use Papimod\Cache\CacheService;
use Papimod\Dotenv\DotEnvModule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

#[CoversClass(CacheService::class)]
#[Small]
final class CacheServiceTest extends TestCase
{
    public const PAPI_FILE = CacheModuleTest::DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::PAPI_CACHE_FILE;

    public function setUp(): void
    {
        defined("ENVIRONMENT_DIRECTORY") || define("ENVIRONMENT_DIRECTORY", __DIR__);
        defined("ENVIRONMENT_FILE") || define("ENVIRONMENT_FILE", ".test.env");

        ApiBuilder::getInstance()
            ->setModules([
                DotEnvModule::class,
                CacheModule::class
            ])
            ->build();
    }

    public function testSetAndGet(): void
    {
        if (file_exists(self::PAPI_FILE)) {
            unlink(self::PAPI_FILE);
        }

        $service = new CacheService();
        $this->assertTrue(file_exists(self::PAPI_FILE), 'Fail to create Papi cache');

        $service->set("foo", "bar");

        $arr = json_decode(file_get_contents(self::PAPI_FILE), true);
        $this->assertEquals("bar", $arr["foo"]);

        $this->assertEquals("bar", $service->get("foo"));
    }
}
