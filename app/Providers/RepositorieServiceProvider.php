<?php

namespace App\Providers;

use App\Repositories\CategorieRepositoryInterface;
use App\Repositories\EloquentRepositories\CategorieRepository;
use App\Repositories\EloquentRepositories\ProductRepository;
use App\Repositories\EloquentRepositories\TagRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositorieServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductRepositoryInterface::class => ProductRepository::class,
        CategorieRepositoryInterface::class => CategorieRepository::class,
        TagRepositoryInterface::class => TagRepository::class
    ];
}
