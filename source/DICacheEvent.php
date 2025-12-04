<?php

namespace Papimod\Cache;

use DI\ContainerBuilder;

/**
 * Slim documentation:
 * [Route expressions caching](https://www.slimframework.com/docs/v4/objects/routing.html#route-expressions-caching)
 */
final class DICacheEvent
{
    public function __invoke(ContainerBuilder $builder): void
    {
        if (CacheModule::isProduction()) {
            $builder->enableCompilation(PAPI_TMP_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::DI_DIRECTORY);
            $builder->writeProxiesToFile(true, PAPI_TMP_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::DI_PROXIES_FILE);
        }
    }
}
