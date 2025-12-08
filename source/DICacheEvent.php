<?php

namespace Papimod\Cache;

use Papi\enumerator\EventPhases;
use Papi\Event;

final class DICacheEvent implements Event
{
    public static function getPhase(): string
    {
        return EventPhases::BEFORE_BUILD_DI;
    }

    public function __invoke(mixed ...$args): void
    {
        if (isset($_SERVER["ENVIRONMENT"]) && $_SERVER["ENVIRONMENT"] === "PRODUCTION") {
            $args[0]->enableCompilation(CACHE_DIRECTORY . DIRECTORY_SEPARATOR . 'di');
        }
    }
}
