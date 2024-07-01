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
        return tap($this, fn($resource) => collect($resource->filters($request))
            ->filter->isSet($request)
            ->each(fn($filter) => $resource->modelQueryBuilder = $filter
                ->apply($request, $resource->modelQueryBuilder, $filter->getValue($request))
            )
        );
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
