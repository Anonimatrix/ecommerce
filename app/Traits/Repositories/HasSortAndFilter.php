<?php

namespace App\Traits\Repositories;

trait HasSortAndFilter
{
    public function callFuncwithManagedSortAndFilter(string $name, $sort_by = null, $filters = [], ...$arguments)
    {
        $sort_by_attribute = $this->request->sort_by ?? $sort_by[0] ??  'id';
        $sort_order = $this->request->sort_order ??  $sort_by[1] ?? 'DESC';

        $sort_by = [$sort_by_attribute, $sort_order];
        $filters = $this->request->filters ?? $filters;

        return call_user_func(array($this, $name), ...$arguments, ...[$sort_by, $filters]);
    }
}
