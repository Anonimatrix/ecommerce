<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    public function __construct(Message $message)
    {
        parent::__construct($message);
    }
}
