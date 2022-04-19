<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Adress;
use App\Repositories\AdressRepositoryInterface;

class ModelRepository extends BaseRepository implements AdressRepositoryInterface
{
    public function __construct(Adress $adress)
    {
        parent::__construct($adress);
    }
}
