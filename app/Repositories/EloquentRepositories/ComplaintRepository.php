<?php

namespace App\Repositories\EloquentRepositories;

use App\Statuses\ComplaintStatus;
use App\Models\Complaint;
use App\Repositories\Interfaces\ComplaintRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ComplaintRepository extends BaseRepository implements ComplaintRepositoryInterface
{
    protected $relations = [
        'chat',
        'order.chat'
    ];

    public function __construct(Complaint $complaint)
    {
        parent::__construct($complaint);
    }
}
