<?php

namespace App\Repositories;

use App\Models\User;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface SearchRepositoryInterface extends BaseRepositoryInterface
{
    public function searchInMostSearchedWithLimit(string $search, int $limit);

    public function searchInUserSearchesWithLimit(string $search, int $limit, User $user);

    public function updateOrCreate(array $data);
}
