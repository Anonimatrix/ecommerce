<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role as ModelsRole;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{

    protected $relations = [
        'permissions'
    ];

    public function __construct(ModelsRole $role)
    {
        parent::__construct($role);
    }
}
