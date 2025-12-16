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
    public const DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . CacheModule::DEFAULT_DIRECTORY;
    public const ACTION_FILE = self::DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::DEFAULT_ACTION_FILE;
    public const DI_DIRECTORY = self::DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::DEFAULT_DI_DIRECTORY;

    public function setUp(): void
    {
        parent::setUp();
        defined("ENVIRONMENT_DIRECTORY") || define("ENVIRONMENT_DIRECTORY", __DIR__);
        defined("ENVIRONMENT_FILE") || define("ENVIRONMENT_FILE", ".test.env");

        if (file_exists(self::DIRECTORY)) {
            $this->removeDirectory(self::DIRECTORY);
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
    }

    public function testLoadModule(): void
    {
        $this->assertTrue(file_exists(self::DIRECTORY), 'Fail to create cache directory');
    }

    public function testDiCache(): void
    {
        $this->assertTrue(file_exists(self::DI_DIRECTORY), 'Fail to create DI cache directory');
    }

    public function testActionCache(): void
    {
        $this->assertTrue(file_exists(self::ACTION_FILE), 'Fail to create Action cache');
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
