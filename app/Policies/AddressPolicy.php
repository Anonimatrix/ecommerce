<?php

namespace App\Policies;

use App\Repositories\Cache\AddressCacheRepository;
use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class AddressPolicy
{
    use HandlesAuthorization;

    protected $repository;
    protected $address;

    public function setAddress(Request $request)
    {
        $address_id = $request->route()->parameter('address_id');

        if ($address_id) {
            $this->address = $this->repository->getById($address_id);
        }
    }

    public function __construct(AddressCacheRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->setAddress($request);
    }

    public function edit(User $user, Address $address)
    {
        return $address->user->id === $user->id || $user->can('edit foreign address');
    }

    public function delete(User $user, Address $address)
    {
        return $address->user->id === $user->id || $user->can('delete foreign address');
    }
}
