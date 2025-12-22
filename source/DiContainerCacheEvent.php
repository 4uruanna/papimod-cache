<?php

namespace Papimod\Cache;

use Papi\enumerator\EventType;
use Papi\interface\PapiEventListener;
use Papimod\Dotenv\Environment;

final class DiContainerCacheEvent implements PapiEventListener
{
    public function __invoke(EventType $event_type, array $options): void
    {
        if (
            Environment::PRODUCTION === ENVIRONMENT
            && $event_type === EventType::CONFIGURE_DEFINITIONS
        ) {
            $options['container_builder']->enableCompilation(
                PAPI_CACHE_DIRECTORY
                    . DIRECTORY_SEPARATOR
                    . CacheModule::CACHE_DI_DIRECTORY
            );
        }
    }
}
