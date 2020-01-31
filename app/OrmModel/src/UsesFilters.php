<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;

trait UsesFilters
{
    /**
     * Filtros del recurso
     *
     * @param  Request $request
     * @return array
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Aplica filtros al modelo del recurso
     *
     * @param  Request $request
     * @return Resource
     */
    protected function applyFilters(Request $request): Resource
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
     * @param Request $request
     * @return integer
     */
    public function countAppliedFilters(Request $request): int
    {
        return collect($this->filters($request))
            ->filter->isSet($request)
            ->count();
    }
}
