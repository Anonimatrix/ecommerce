<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Shipp;
use App\Repositories\Interfaces\ShippRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ShippRepository extends BaseRepository implements ShippRepositoryInterface
{
    public function __construct(Shipp $shipp)
    {
        parent::__construct($shipp);
    }
}
