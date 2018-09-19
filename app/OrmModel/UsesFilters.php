<?php

namespace App\OrmModel;

use Illuminate\Http\Request;

trait UsesFilters
{
    public function filters(Request $request)
    {
        return [];
    }

    public function renderFilters()
    {
        return;
    }

    public function hasFilters()
    {
        return count($this->filters()) > 0;
    }

    protected function applyFilters(Request $request)
    {
        $this->makeModelObject();

        foreach($this->filters($request) as $filter) {
            if ($filter->isSet($request)) {
                $this->modelObject = $filter->apply($request, $this->modelObject, $filter->getUrlValue($request));
            }
        }

        return $this;
    }

}
