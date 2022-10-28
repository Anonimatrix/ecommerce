<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }
}
