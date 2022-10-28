<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
    }
}
