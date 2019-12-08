<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;

trait UsesFilters
{
    /**
     * Filtros del recurso
     * @param  Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Aplica filtros al modelo del recurso
     * @param  Request $request
     * @return Resource
     */
    protected function applyFilters(Request $request): Resource
    {
        foreach($this->filters($request) as $filter) {
            if ($filter->isSet($request)) {
                $this->modelQueryBuilder = $filter->apply($request, $this->modelQueryBuilder, $filter->getValue($request));
            }
        }

        return $this;
    }

    public function countAppliedFilters(Request $request): int
    {
        return collect($this->filters($request))
            ->filter->isSet($request)
            ->count();
    }

}
