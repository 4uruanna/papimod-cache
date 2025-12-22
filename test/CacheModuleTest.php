<?php

namespace Papimod\Cache\Test;

use Exception;
use Papi\enumerator\HttpMethod;
use Papi\PapiBuilder;
use Papi\Test\mock\FooGet;
use Papi\Test\PapiTestCase;
use Papimod\Cache\CacheModule;
use Papimod\Dotenv\DotEnvModule;
use PHPUnit\Framework\Attributes\CoversClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[CoversClass(CacheModule::class)]
class CacheModuleTest extends PapiTestCase
{
    private PapiBuilder $builder;

    public function setUp(): void
    {
        parent::setUp();
        defined("PAPI_DOTENV_DIRECTORY") || define("PAPI_DOTENV_DIRECTORY", __DIR__);
        defined("PAPI_DOTENV_FILE") || define("PAPI_DOTENV_FILE", ".test.env");
        $this->builder = new PapiBuilder();
    }

    public function testLoadModule(): void
    {
        $cache_path = __DIR__ . DIRECTORY_SEPARATOR . ".cache";

        if (file_exists($cache_path)) {
            $this->removeDirectory($cache_path);
        }

        $request = $this->createRequest(HttpMethod::GET, "/");

        $this->builder
            ->addModule(
                DotEnvModule::class,
                CacheModule::class
            )
            ->addAction(FooGet::class)
            ->build()
            ->handle($request);

        $this->assertTrue(file_exists($cache_path));

        $this->assertTrue(
            file_exists(
                PAPI_CACHE_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . CacheModule::CACHE_DI_DIRECTORY
            )
        );

        $this->assertTrue(
            file_exists(
                PAPI_CACHE_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . CacheModule::CACHE_ACTION_FILE
            )
        );
    }

    private function removeDirectory(string $directory): bool
    {
        try {
            $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                $path = $file->getPathname();
                if ($file->isDir()) {
                    $this->removeDirectory($path);
                } else {
                    unlink($file->getPathname());
                }
            }

            rmdir($directory);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
