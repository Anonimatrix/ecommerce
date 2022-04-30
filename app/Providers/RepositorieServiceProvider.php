<?php

namespace App\Providers;

use App\Repositories\AdressRepositoryInterface;
use App\Repositories\CategorieRepositoryInterface;
use App\Repositories\EloquentRepositories\CategorieRepository;
use App\Repositories\EloquentRepositories\PermissionRepository;
use App\Repositories\EloquentRepositories\ProductRepository;
use App\Repositories\EloquentRepositories\RoleRepository;
use App\Repositories\EloquentRepositories\TagRepository;
use App\Repositories\EloquentRepositories\AdressRepository;
use App\Repositories\PermissionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentRepositories\SubcategorieRepository;
use App\Repositories\SubcategorieRepositoryInterface;

class RepositorieServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductRepositoryInterface::class => ProductRepository::class,
        CategorieRepositoryInterface::class => CategorieRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        PermissionRepositoryInterface::class => PermissionRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        SubcategorieRepositoryInterface::class => SubcategorieRepository::class,
        AdressRepositoryInterface::class => AdressRepository::class
    ];
}
