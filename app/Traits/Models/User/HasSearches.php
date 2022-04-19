<?php

namespace App\Traits\Models\User;

use App\Models\Search;

trait HasSearches
{
    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function searchInSearches($search, $limit)
    {
        return $this->searches()->where('content', 'LIKE', "%$search%")->limit($limit)->get();
    }
}
