<?php

namespace App\Providers;

use App\Events\ShipmentCreated;
use App\Listeners\ShipmentCreatedListener;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Observers\ComplaintObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ShipmentCreated::class => [
            ShipmentCreatedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        Order::observe(OrderObserver::class);
        Complaint::observe(ComplaintObserver::class);
    }
}
