<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Tag;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            array_push($ids, $tag->id);
        }

        return $ids;
    }

    public function suggest(string $search, int $limit)
    {
        return $this->model
            ->where('title', 'LIKE', "%$search%")
            ->orderBy(DB::raw('COUNT(*)'), 'desc')->groupBy('title')
            ->limit($limit)
            ->get();
    }
}
