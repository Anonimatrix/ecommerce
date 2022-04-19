<?php

namespace App\Repositories;

interface CategorieRepositoryInterface extends BaseRepositoryInterface
{
    public function allOrderByTitle();
}
