<?php

namespace App\Policies;

use App\Cache\AdressCacheRepository;
use App\Models\Adress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class AdressPolicy
{
    use HandlesAuthorization;

    protected $repository;
    protected $adress;

    public function setAdress(Request $request)
    {
        $adress_id = $request->route()->parameter('adress_id');

        if ($adress_id) {
            $this->adress = $this->repository->getById($adress_id);
        }
    }

    public function __construct(AdressCacheRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->setAdress($request);
    }

    public function owner(User $user, Adress $adress)
    {
        return $adress->user->id === $user->id;
    }
}
