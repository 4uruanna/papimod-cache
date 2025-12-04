<?php

namespace Papimod\Cache;

use Papi\enumerator\EventPhases;
use Papi\Event;
use Slim\App;

final class ActionCacheEvent extends Event
{
    public int $phase = EventPhases::BEFORE_ACTIONS;

    public function __invoke(mixed ...$args): void
    {
        /** @var App */
        $app = $args[0];

        if (isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] === "PRODUCTION") {
            $app->getRouteCollector()
                ->setCacheFile(
                    CACHE_DIRECTORY
                        . DIRECTORY_SEPARATOR
                        . CacheModule::ACTION_CACHE_FILE
                );
        }
    }
}
