<?php

namespace App\Statuses;

class ComplaintStatus
{
    public const STARTED = 'pending_to_take';
    public const TAKEN = 'pending_to_solve';
    public const SOLVED = 'solved';
    public const CANCELED = 'canceled';
}
