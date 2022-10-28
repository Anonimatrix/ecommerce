<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Search;
use App\Models\User;
use App\Repositories\Cache\UserCacheRepository;
use App\Repositories\Interfaces\SearchRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class SearchRepository extends BaseRepository implements SearchRepositoryInterface
{
    public function __construct(Search $search)
    {
        parent::__construct($search);
    }

    public function searchInMostSearchedWithLimit(string $search, int $limit)
    {
        return $this->model->where('content', 'LIKE', "%$search%")
            ->orderBy(DB::raw('COUNT(*)'), 'desc')->groupBy('content')
            ->distinct()
            ->limit($limit)
            ->get();
    }

    public function searchInUserSearchesWithLimit(string $search, int $limit, User $user)
    {
        return $user->searches()->where('content', 'LIKE', "%$search%")->limit($limit)->get();
    }

    public function updateOrCreate(array $data)
    {
        return $this->model->updateOrCreate($data);
    }

    public function latestOfAuthenticated(UserCacheRepository $userRepository)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $userRepository->authenticated();

        return $user->searches()->latest('id')->first();
    }
}
