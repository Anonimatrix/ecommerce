<?php

namespace App\Cache;

use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class TagCache extends BaseCache implements TagRepositoryInterface
{
    protected $repository;

    public function __construct(TagRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'tag');
        $this->repository = $repository;
    }

    public function createMany(array $titles)
    {
        $keyNames = implode('-', $titles);
        $this->cache->tags([$this->key . 's'])->remember($this->key .  's-' . $keyNames, self::TTL, function () use ($titles) {
            return $this->repository->createMany($titles);
        });
    }
}
