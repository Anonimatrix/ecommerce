<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Tag;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    public function createMany(array $titles)
    {
        $ids = [];

        foreach ($titles as $title) {
            $tag = $this->model->firstOrCreate(['title' => $title, 'user_id' => Auth::id()]);
            array_push($ids, $tag);
        }

        return $ids;
    }
}
