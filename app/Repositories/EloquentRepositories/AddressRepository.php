<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Address;
use App\Models\User;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{

    protected $relations = [
        'user'
    ];

    public function __construct(Address $address)
    {
        parent::__construct($address);
    }

    public function paginatedUserAddresses(int $quantity, User $user)
    {
        return $user->addresses()->paginate($quantity);
    }
}
