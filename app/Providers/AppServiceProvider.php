<?php

namespace App\Providers;

use App\Repositories\EloquentRepositories\ComplaintRepository;
use App\Repositories\Interfaces\ComplaintRepositoryInterface;

use App\Repositories\EloquentRepositories\ViewRepository;
use App\Repositories\Interfaces\ViewRepositoryInterface;

use App\Repositories\EloquentRepositories\MessageRepository;
use App\Repositories\Interfaces\MessageRepositoryInterface;

use App\Repositories\EloquentRepositories\RoleRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;

use App\Repositories\EloquentRepositories\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;

use App\Repositories\EloquentRepositories\ChatRepository;
use App\Repositories\Interfaces\ChatRepositoryInterface;

use App\Repositories\EloquentRepositories\ShippRepository;
use App\Repositories\Interfaces\ShippRepositoryInterface;

use App\Repositories\EloquentRepositories\PaymentRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

use App\Services\Billing\Contracts\PaymentGatewayInterface;
use App\Services\Billing\MercadoPagoGateway;
use App\Repositories\Cache\AdressCacheRepository;
use App\Repositories\Cache\ChatCacheRepository;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\PaymentCacheRepository;
use App\Repositories\Cache\ProductCache;
use App\Repositories\Cache\ShippCacheRepository;
use App\Services\Shipping\AndreaniGateway;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        ComplaintRepositoryInterface::class => ComplaintRepository::class,
        ViewRepositoryInterface::class => ViewRepository::class,
        MessageRepositoryInterface::class => MessageRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        ChatRepositoryInterface::class => ChatRepository::class,
        ShippRepositoryInterface::class => ShippRepository::class,
        PaymentRepositoryInterface::class => PaymentRepository::class,
        ShippGatewayInterface::class => AndreaniGateway::class,
        'productRepository' => ProductCache::class,
        'paymentRepository' => PaymentCacheRepository::class,
        'adressRepository' => AdressCacheRepository::class,
        'shippRepository' => ShippCacheRepository::class,
        'chatRepository' => ChatCacheRepository::class,
        'shippGateway' => AndreaniGateway::class,
        'orderRepository' => OrderCacheRepository::class
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $gateway = $app->make('router')->input('gateway');

            if ($gateway === 'mp') {
                return new MercadoPagoGateway();
            } else {
                return new MercadoPagoGateway();
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('less_or_equal_than_field', function ($attribute, $value, $parameters, $validator) {
            $validatorData = $validator->getData();
            $idName = $parameters[0];
            $fieldName = $parameters[1];
            $repository = $parameters[2];

            $idValue = $validatorData[$idName];

            if (gettype($idValue) !== 'integer') {
                return false;
            }

            $item = $repository::getById($idValue);
            $fieldValue = $item[$fieldName];

            return $value <= $fieldValue;
        });

        Validator::replacer('less_or_equal_than_field', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[1], $message);
        });
    }
}
