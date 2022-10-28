<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\View;
use App\Repositories\Cache\UserCacheRepository;
use App\Repositories\Interfaces\ViewRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ViewRepository extends BaseRepository implements ViewRepositoryInterface
{
    public function __construct(View $view)
    {
        parent::__construct($view);
    }

    public function latestOfAuthenticated(int $quantity = 1, UserCacheRepository $userRepository)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $userRepository->authenticated();

        if (!$user) {
            if ($quantity > 1) return [];
            return null;
        };

        $query = $user->views()->latest('id');

        return $quantity === 1 ? $query->first() : $query->get();
    }
}
