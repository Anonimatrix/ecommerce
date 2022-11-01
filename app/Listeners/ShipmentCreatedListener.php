<?php

namespace App\Listeners;

use App\Events\ShipmentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShipmentCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ShipmentCreated $event)
    {
        $res = $event->getRes();
        $order = $event->getOrder();

        $tracking_id = $res['bultos'][0]['numeroDeEnvio'];

        $shipp = $order->shipp;

        $shipp->tracking_id = $tracking_id;

        $shipp->save();
    }
}
