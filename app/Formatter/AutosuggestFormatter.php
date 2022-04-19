<?php

namespace App\Formatter;

use Illuminate\Support\Collection;

class AutosuggestFormatter
{

    protected function searchFormat(Collection $collection, $type): Collection
    {
        return $collection->map(function ($search) use ($type) {
            $search['type'] = $type;
            return $search;
        });
    }

    public function historyUserFormat(Collection $collection): Collection
    {
        return $this->searchFormat($collection, 'history');
    }

    public function mostSearchedFormat(Collection $collection): Collection
    {
        return $this->searchFormat($collection, 'most-searched');
    }
}
