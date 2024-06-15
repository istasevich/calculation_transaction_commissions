<?php

namespace App\Services\Cache;

use Cicnavi\SimpleFileCache\Exceptions\CacheException;
use Cicnavi\SimpleFileCache\Exceptions\InvalidArgumentException;
use Cicnavi\SimpleFileCache\SimpleFileCache;

class FileCacheService implements CacheServiceInterface
{
    private SimpleFileCache $fileCache;

    /**
     * @throws CacheException
     */
    public function __construct(string $cacheCategoryName)
    {
        $this->fileCache = new SimpleFileCache($cacheCategoryName, 'cache');
    }

    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function ensure(string $key, string $data): string
    {
        if (! $this->get($key)) {
            $this->fileCache->set($key, $data, 3600);
        }

        return $this->fileCache->get($key);
    }

    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function get(string $key): array|null
    {
        return @json_decode($this->fileCache->get($key), true);
    }
}