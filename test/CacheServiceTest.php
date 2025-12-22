<?php

namespace Papimod\Cache\Test;

use Papi\PapiBuilder;
use Papimod\Cache\CacheModule;
use Papimod\Cache\CacheService;
use Papimod\Dotenv\DotEnvModule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\TestCase;

#[CoversClass(CacheService::class)]
#[Medium]
final class CacheServiceTest extends TestCase
{
    private PapiBuilder $builder;

    private CacheService $service;

    public function setUp(): void
    {
        defined("PAPI_DOTENV_DIRECTORY") || define("PAPI_DOTENV_DIRECTORY", __DIR__);
        defined("PAPI_DOTENV_FILE") || define("PAPI_DOTENV_FILE", ".test.env");

        $this->builder = new PapiBuilder();

        $this->builder
            ->addModule(DotEnvModule::class, CacheModule::class)
            ->build();

        $papi_cache_file = PAPI_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::CACHE_PAPI_FILE;
        if (file_exists($papi_cache_file)) {
            unlink($papi_cache_file);
        }

        $this->service = new CacheService();
    }

    public function testSetAndGet(): void
    {
        $this->service->set("foo", "bar");
        $this->assertTrue(file_exists(PAPI_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::CACHE_PAPI_FILE));

        $arr = json_decode(file_get_contents(PAPI_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::CACHE_PAPI_FILE), true);
        $this->assertEquals("bar", $arr["foo"]["value"]);
        $this->assertEquals("bar", $this->service->get("foo"));
    }

    #[Depends('testSetAndGet')]
    public function testTtl(): void
    {
        $this->service->set("foo2", "bar2", 2);
        $this->assertEquals("bar2", $this->service->get("foo2"));
        sleep(2);
        $this->assertEquals(null, $this->service->get("foo2"));
    }
}
