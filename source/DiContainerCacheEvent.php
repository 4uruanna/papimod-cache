<?php

namespace Papimod\Cache;

use Papi\enumerator\EventPhases;
use Papi\Event;

final class DiContainerCacheEvent implements Event
{
    public static function getPhase(): string
    {
        return EventPhases::BEFORE_BUILD_DI;
    }

    /**
     * Add DI cache
     */
    public function __invoke(mixed ...$args): void
    {
        if (IS_PRODUCTION) {
            $args[0]->enableCompilation(
                CACHE_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . CacheModule::DEFAULT_DI_DIRECTORY
            );
        }
    }
}
