<?php

namespace App\Containers\Core\Cacher\Actions;

use App\Containers\Core\Cacher\Data\Dto\CacheDto;

interface CacheActionInterface
{
    public function run(CacheDto $cacheDto, callable $data): string;
}