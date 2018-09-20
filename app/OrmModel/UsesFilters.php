<?php

namespace App\OrmModel;

use Illuminate\Http\Request;

trait UsesFilters
{
    /**
     * Filtros del recurso
     * @param  Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Aplica filtros al modelo del recurso
     * @param  Request $request
     * @return Resource
     */
    protected function applyFilters(Request $request)
    {
        $this->makeModelObject($request);

        foreach($this->filters($request) as $filter) {
            if ($filter->isSet($request)) {
                $this->modelObject = $filter->apply($request, $this->modelObject, $filter->getValue($request));
            }
        }

        return $this;
    }

}
