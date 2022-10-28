<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ChatCacheRepository extends BaseCache implements ChatRepositoryInterface
{
    protected $repository;

    public function __construct(ChatRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'chat');
        $this->repository = $repository;
    }

    public function createIfNotExists(array $data)
    {
        $this->cache->tags([$this->key])->flush();
        return $this->repository->createIfNotExists($data);
    }
}
