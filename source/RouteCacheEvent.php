<?php

namespace Papimod\Cache;

use Slim\App;

final class RouteCacheEvent
{
    public function __invoke(App $app): void
    {
        if (CacheModule::isProduction()) {
            $routeCollector = $app->getRouteCollector();
            $routeCollector->setCacheFile(PAPI_TMP_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::ROUTE_CACHE_FILE);
        }
    }
}
