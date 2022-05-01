<?php

namespace App\Traits\Models\User;

use App\Cache\SearchCacheRepository;
use App\Models\Search;

trait HasSearches
{
    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function searchInSearches(SearchCacheRepository $searchRepository, $search, $limit)
    {
        return $searchRepository->searchInUserSearchesWithLimit($search, $limit, $this);
    }
}
