<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Chat;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ChatRepository extends BaseRepository implements ChatRepositoryInterface
{

    public $relations = [
        'messages'
    ];

    public function __construct(Chat $chat)
    {
        parent::__construct($chat);
    }

    public function createIfNotExists(array $data)
    {
        return $this->model->firstOrCreate($data);
    }
}
