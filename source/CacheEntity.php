<?php

namespace Papimod\Cache;

final class CacheEntity
{
    public readonly string $key;

    public mixed $value;

    public readonly int $ttl;

    public function __construct(string $key, mixed $value, ?int $ttl = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->ttl = time() + ($ttl ?? PAPI_CACHE_TIMEOUT);
    }
}
