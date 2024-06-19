<?php

namespace App\Services\Cache;

interface CacheServiceInterface
{
    public function ensure(string $key, string $data): string;

    public function get(string $key): array|null;
}