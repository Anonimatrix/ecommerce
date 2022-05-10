<?php

namespace App\Providers;

use App\Shipping\AndreaniGateway;
use App\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        ShippGatewayInterface::class => AndreaniGateway::class
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
