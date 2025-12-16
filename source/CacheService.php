<?php

namespace Papimod\Cache;

final class CacheService
{
    private array $cache;

    public function __construct()
    {
        $file_path = CACHE_DIRECTORY . DIRECTORY_SEPARATOR . CacheModule::PAPI_CACHE_FILE;

        if (file_exists($file_path)) {
            $this->cache = json_decode(file_get_contents($file_path), true);
            $at = (int) $this->get(CacheModule::DEFAULT_CACHE_AT_KEY);

            if (time() - $at > CACHE_TIMEOUT) {
                unlink($file_path);
                $this->reset();
            }
        } else {
            $this->reset();
        }
    }

    /**
     * Retrieves a value from the cache by key
     *
     * @param string $key - The key to look up in the cache
     * @return mixed - The cached value or null if the key doesn't exist
     */
    public function get(string $key): mixed
    {
        return $this->cache[$key] ?? null;
    }

    /**
     * Stores a value in the cache and persists it to the cache file
     *
     * @param string $key - The key used to store the value
     * @param mixed $value - The value to cache
     */
    public function set(string $key, mixed $value): void
    {
        $this->cache[$key] = $value;
        file_put_contents(CACHE_DIRECTORY, json_encode($this->cache), JSON_PRETTY_PRINT);
    }

    /**
     * Resets the cache to its initial state
     */
    private function reset(): void
    {
        $this->cache = [
            CacheModule::DEFAULT_CACHE_AT_KEY => time()
        ];
    }
}
