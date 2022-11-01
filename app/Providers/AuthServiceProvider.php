<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Categorie;
use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Product;
use App\Policies\AddressPolicy;
use App\Policies\CategoriePolicy;
use App\Policies\ChatPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Product::class => ProductPolicy::class,
        Address::class => AddressPolicy::class,
        Categorie::class => CategoriePolicy::class,
        Chat::class => ChatPolicy::class,
        Complaint::class => ComplaintPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
