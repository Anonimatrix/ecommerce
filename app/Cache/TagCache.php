<?php

namespace App\Cache;

use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;

class TagCache extends BaseCache implements TagRepositoryInterface
{
    protected $repository;

    public function __construct(TagRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'tag');
        $this->repository = $repository;
    }
}
