<?php

namespace App\Exceptions\Payment;

use Exception;
use Inertia\Inertia;
use Throwable;

class UnavailableServiceException extends Exception
{
    public function render($request)
    {
        return Inertia::render('Payments/Error', ['status' => 503])
            ->toResponse($request)
            ->setStatusCode(503);
    }
}
