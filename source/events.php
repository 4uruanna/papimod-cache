<?php

namespace Papimod\Cache;

use Papi\enumerator\AppBuilderEvents;

return [
    [AppBuilderEvents::BEFORE_DI, new DICacheEvent],
    [AppBuilderEvents::BEFORE_ROUTES, new RouteCacheEvent]
];
