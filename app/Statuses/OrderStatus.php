<?php

namespace App\Statuses;

class OrderStatus
{
    public const PENDING = 'pending_to_pay';
    public const PAYED = 'pending_to_shipp';
    public const CANCELED = 'canceled';
    public const SHIPPED = 'pending_to_receive';
    public const COMPLETED = 'pending_to_withdraw';
}
