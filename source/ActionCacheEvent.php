<?php

namespace Papimod\Cache;

use Papi\enumerator\EventType;
use Papi\interface\PapiEventListener;
use Papimod\Dotenv\Environment;

final class ActionCacheEvent implements PapiEventListener
{
    public function __invoke(EventType $event_type, array $options): void
    {
        if (
            ENVIRONMENT === Environment::PRODUCTION
            && $event_type === EventType::CONFIGURE_ACTIONS
        ) {
            $options['app']
                ->getRouteCollector()
                ->setCacheFile(
                    PAPI_CACHE_DIRECTORY
                        . DIRECTORY_SEPARATOR
                        . CacheModule::CACHE_ACTION_FILE
                );
        }
    }
}
