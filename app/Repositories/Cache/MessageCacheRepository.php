<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\MessageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class MessageCacheRepository extends BaseCache implements MessageRepositoryInterface
{
    protected $repository;

    public function __construct(MessageRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'message');
        $this->repository = $repository;
    }
}
