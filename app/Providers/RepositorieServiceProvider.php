<?php

namespace App\Providers;

use App\Repositories\Interfaces\AdressRepositoryInterface;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use App\Repositories\EloquentRepositories\CategorieRepository;
use App\Repositories\EloquentRepositories\PermissionRepository;
use App\Repositories\EloquentRepositories\ProductRepository;
use App\Repositories\EloquentRepositories\RoleRepository;
use App\Repositories\EloquentRepositories\TagRepository;
use App\Repositories\EloquentRepositories\AdressRepository;
use App\Repositories\EloquentRepositories\OrderRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentRepositories\SubcategorieRepository;
use App\Repositories\Interfaces\SubcategorieRepositoryInterface;
use App\Repositories\EloquentRepositories\SearchRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\SearchRepositoryInterface;

class RepositorieServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductRepositoryInterface::class => ProductRepository::class,
        CategorieRepositoryInterface::class => CategorieRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        PermissionRepositoryInterface::class => PermissionRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        SubcategorieRepositoryInterface::class => SubcategorieRepository::class,
        AdressRepositoryInterface::class => AdressRepository::class,
        SearchRepositoryInterface::class => SearchRepository::class,
        OrderRepositoryInterface::class => OrderRepository::class
    ];
}
