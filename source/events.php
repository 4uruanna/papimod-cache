<?php

namespace Papimod\Cache;

use Papi\enumerator\AppBuilderEvents;

return [
    [
        AppBuilderEvents::BEFORE_DI,
        DICacheEvent::class
    ],
    [
        AppBuilderEvents::BEFORE_ROUTES,
        RouteCacheEvent::class
    ]
];
