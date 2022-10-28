<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Adress;
use App\Models\User;
use App\Repositories\Interfaces\AdressRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class AdressRepository extends BaseRepository implements AdressRepositoryInterface
{

    protected $relations = [
        'user'
    ];

    public function __construct(Adress $adress)
    {
        parent::__construct($adress);
    }

    public function paginatedUserAdresses(int $quantity, User $user)
    {
        return $user->adresses()->paginate($quantity);
    }
}
