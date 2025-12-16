<?php

namespace Papimod\Cache;

use Papi\enumerator\EventPhases;
use Papi\Event;
use Slim\App;

final class ActionCacheEvent implements Event
{
    public static function getPhase(): string
    {
        return EventPhases::BEFORE_ACTIONS;
    }

    /**
     * Add the cache file to the route collector
     */
    public function __invoke(mixed ...$args): void
    {
        if (IS_PRODUCTION) {
            /** @var App */
            $app = $args[0];
            $app->getRouteCollector()
                ->setCacheFile(
                    CACHE_DIRECTORY
                        . DIRECTORY_SEPARATOR
                        . CacheModule::DEFAULT_ACTION_FILE
                );
        }
    }
}
