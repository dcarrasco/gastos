<?php

namespace App\OrmModel\src;

use App\OrmModel\src\Metrics\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait UsesCards
{
    /**
     * Cards del recurso
     *
     * @param  Request  $request
     * @return Metric[]
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Genera vistas para cada una de las cards del recurso
     *
     * @param  Request  $request
     * @return Collection
     */
    public function renderCards(Request $request): Collection
    {
        return collect($this->cards($request))
            ->map->render($request);
    }
}
