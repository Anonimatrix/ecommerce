<?php

namespace App\Console\Commands;

use App\Facades\OrderRepository;
use App\Facades\ShippGateway;
use App\Filters\Filters;
use App\Models\Order;
use App\Statuses\OrderStatus;
use Illuminate\Console\Command;

class UpdateShippStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipps:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of orders when shipp was change';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    //TODO test this
    public function handle()
    {
        $ordersPendingToChanges = OrderRepository::getPendingToChangeShippStatus();

        if ($ordersPendingToChanges->isEmpty()) {
            return;
        }

        $ordersPendingToChanges->each(function ($order, $index) {
            $status = ShippGateway::getStatusShipp($order->shipp->tracking_id);

            if ($status == 'Entregado') {
                OrderRepository::update(['status' => OrderStatus::COMPLETED], $order);
                return;
            }

            if ($status != 'Pendiente') {
                OrderRepository::update(['status' => OrderStatus::SHIPPED], $order);
                return;
            }
        });
    }
}
