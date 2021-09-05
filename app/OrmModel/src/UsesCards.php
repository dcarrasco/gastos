<?php

namespace App\OrmModel\src;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Metric;

trait UsesCards
{
    /**
     * Cards del recurso
     *
     * @param  Request $request
     * @return array<Metric>
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Genera vistas para cada una de las cards del recurso
     *
     * @param Request $request
     * @return Collection
     */
    public function renderCards(Request $request): Collection
    {
        return collect($this->cards($request))->map->render($request);
    }
}
