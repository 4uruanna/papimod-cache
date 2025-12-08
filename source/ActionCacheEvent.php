<?php

namespace Papimod\Cache;

use Papi\enumerator\EventPhases;
use Papi\Event;

final class ActionCacheEvent implements Event
{
    public static function getPhase(): string
    {
        return EventPhases::BEFORE_ACTIONS;
    }

    public function __invoke(mixed ...$args): void
    {
        if (isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] === "PRODUCTION") {
            $args[0]->getRouteCollector()
                ->setCacheFile(CACHE_DIRECTORY . DIRECTORY_SEPARATOR . 'routes.cache');
        }
    }
}
