<?php

namespace Papimod\Cache;

use DI\ContainerBuilder;
use Papi\enumerator\EventPhases;
use Papi\Event;

final class DICacheEvent extends Event
{
    public int $phase = EventPhases::BEFORE_DI;

    public function __invoke(mixed ...$args): void
    {
        /** @var ContainerBuilder */
        $builder = $args[0];

        if (isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] === "PRODUCTION") {
            $builder->enableCompilation(
                CACHE_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . CacheModule::DI_CACHE_DIRECTORY
            );
        }
    }
}
