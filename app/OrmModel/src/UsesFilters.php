<?php

namespace App\OrmModel\src;

use App\OrmModel\src\Filters\Filter;
use Illuminate\Http\Request;

trait UsesFilters
{
    /**
     * Filtros del recurso
     *
     * @param  Request  $request
     * @return Filter[]
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Aplica filtros al modelo del recurso
     *
     * @param  Request  $request
     * @return resource
     */
    public function applyFilters(Request $request): Resource
    {
        collect($this->filters($request))
            ->filter->isSet($request)
            ->each(function ($filter) use ($request) {
                $this->modelQueryBuilder = $filter
                    ->apply($request, $this->modelQueryBuilder, $filter->getValue($request));
            });

        return $this;
    }

    /**
     * Devuelve la cantidad de filtros aplicados en el request
     *
     * @param  Request  $request
     * @return int
     */
    public function countAppliedFilters(Request $request): int
    {
        return collect($this->filters($request))
            ->filter->isSet($request)
            ->count();
    }
}
