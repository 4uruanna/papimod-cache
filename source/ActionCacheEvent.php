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

    public function __invoke(mixed ...$args): void
    {
        if (isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] === "PRODUCTION") {
            /** @var App */
            $app = $args[0];
            $app->getRouteCollector()
                ->setCacheFile(CACHE_DIRECTORY . DIRECTORY_SEPARATOR . 'routes.cache');
        }
    }
}
