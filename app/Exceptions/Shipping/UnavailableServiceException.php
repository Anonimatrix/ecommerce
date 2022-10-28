<?php

namespace App\Exceptions\Shipping;

use Exception;
use Inertia\Inertia;

class UnavailableServiceException extends Exception
{
    public function render($request)
    {
        return Inertia::render('Shipping/Error', ['status' => 503]);
    }
}
