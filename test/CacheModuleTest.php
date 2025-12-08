<?php

namespace Papimod\Cache\Test;

use Exception;
use Papi\ApiBuilder;
use Papi\enumerator\HttpMethods;
use Papi\Test\ApiBaseTestCase;
use Papi\Test\foo\actions\FooAction;
use Papimod\Cache\CacheModule;
use Papimod\Dotenv\DotEnvModule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[CoversClass(CacheModule::class)]
#[Small]
class CacheModuleTest extends ApiBaseTestCase
{
    public function testLoadModule(): void
    {
        define("ENVIRONMENT_DIRECTORY", __DIR__);
        define("ENVIRONMENT_FILE", ".test.env");

        $cache_directory = __DIR__ . DIRECTORY_SEPARATOR . ".cache";
        $action_cache_file = $cache_directory . DIRECTORY_SEPARATOR . 'routes.cache';
        $di_cache_directory = $cache_directory . DIRECTORY_SEPARATOR . 'di';

        if (file_exists($cache_directory)) {
            $deleted = $this->removeDirectory($cache_directory);
            $this->assertTrue($deleted);
        }

        $request = $this->createRequest(HttpMethods::GET, "/foo");

        ApiBuilder::getInstance()
            ->setModules([
                DotEnvModule::class,
                CacheModule::class
            ])
            ->setActions([FooAction::class])
            ->build()
            ->handle($request);

        $this->assertTrue(file_exists($cache_directory), 'Fail to create cache directory');
        $this->assertTrue(file_exists($di_cache_directory), 'Fail to create DI cache directory');
        $this->assertTrue(file_exists($action_cache_file), 'Fail to create Action cache');
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
