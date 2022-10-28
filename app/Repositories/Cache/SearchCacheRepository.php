<?php

namespace App\Repositories\Cache;

use App\Models\User;
use App\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class SearchCacheRepository extends BaseCache implements SearchRepositoryInterface
{
    protected $repository;

    public function __construct(SearchRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'search');
        $this->repository = $repository;
    }

    public function searchInMostSearchedWithLimit(string $search, int $limit)
    {
        return $this->cache->tags([$this->key])->remember($this->key . "s-most-search-$search", self::TTL, function () use ($search, $limit) {
            return $this->repository->searchInMostSearchedWithLimit($search, $limit);
        });
    }

    public function searchInUserSearchesWithLimit(string $search, int $limit, User $user)
    {
        return $this->cache->tags([$this->key])->remember($this->key . "s-users-searches-$search", self::TTL, function () use ($search, $limit, $user) {
            return $this->repository->searchInUserSearchesWithLimit($search, $limit, $user);
        });
    }

    public function updateOrCreate(array $data)
    {
        $model = $this->repository->updateOrCreate($data);

        $this->cache->tags([$this->key, $this->key . "-$model->id"])->flush();

        return $model;
    }

    public function latestOfAuthenticated(UserCacheRepository $userRepository)
    {
        $user = $userRepository->authenticated()->id;
        return $this->cache->tags([$this->key])->remember($this->getRememberString(false, null, "latest-search-by-user-$user->id"), self::TTL, function () use ($userRepository) {
            return $this->repository->latestOfAuthenticated($userRepository);
        });
    }
}
