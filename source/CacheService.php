<?php

namespace Papimod\Cache;

/**
 * Manages a cache stored in a JSON file.
 * It handles automatic cache expiration based on a `CACHE_TIMEOUT`
 * value and provides methods to get and set cached values.
 */
final class CacheService
{
    private array $cache;
    private string $file_path;

    public function __construct()
    {
        $this->file_path = PAPI_CACHE_DIRECTORY
            . DIRECTORY_SEPARATOR
            . CacheModule::CACHE_PAPI_FILE;

        $this->read();
    }

    public function get(string $key): mixed
    {
        $result = null;

        if (isset($this->cache[$key])) {
            if ($this->cache[$key]->ttl > time()) {
                $result = $this->cache[$key]->value;
            } else {
                $this->delete($key);
            }
        }

        return $result;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $this->cache[$key] = new CacheEntity($key, $value, $ttl);
        $this->write();
    }

    public function delete(string $key): void
    {
        unset($this->cache[$key]);
        $this->write();
    }

    private function write(): void
    {
        file_put_contents(
            $this->file_path,
            json_encode($this->cache),
            JSON_PRETTY_PRINT
        );
    }

    private function read(): void
    {
        $this->cache = [];

        if (file_exists($this->file_path)) {
            $cache = json_decode(file_get_contents($this->file_path), true);
            $now = time();

            foreach ($cache as $value) {
                [
                    "key" => $key,
                    "value" => $value,
                    "ttl" => $ttl
                ] = $value;

                if ($ttl !== null && (int) $ttl > $now) {
                    $this->cache[$key] = new CacheEntity($key, $value, $ttl);
                }
            }
        }
    }
}
