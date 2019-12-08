<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait UsesCards
{
    /**
     * Cards del recurso
     * @param  Request $request
     * @return array
     */
    public function cards(Request $request): array
    {
        return [];
    }

    public function renderCards(Request $request): Collection
    {
        return collect($this->cards($request))->map->render($request);
    }
}
