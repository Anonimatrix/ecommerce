<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\TagRepositoryInterface;
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

        return $this->cache->tags([$this->key . 's-many'])->remember($this->key .  's-' . $keyNames, self::TTL, function () use ($titles) {
            $this->cache->tags([$this->key])->flush();
            return $this->repository->createMany($titles);
        });
    }

    public function suggest(string $search, int $limit)
    {
        return $this->cache->tags([$this->key])->remember($this->key . "s-$search", self::TTL, function () use ($search, $limit) {
            return $this->repository->suggest($search, $limit);
        });
    }
}
