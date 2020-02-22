<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

abstract class Metric
{
    use DisplayAsCard;


    /**
     * Genera rango de fechas para realizar consultas
     *
     * @param  Request $request
     * @param  string  $period
     * @return Array
     */
    protected function currentRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        if ($range == 'MTD') {
            return [now()->startOfMonth(), now()];
        }
        if ($range == 'QTD') {
            return [now()->firstOfQuarter(), now()];
        }
        if ($range == 'YTD') {
            return [now()->startOfYear(), now()];
        }
        if ($range == 'CURR_MONTH') {
            return [now()->startOfMonth(), now()->endOfMonth()];
        }

        return [now()->subDays($range - 1), now()];
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     *
     * @param  array $dateInterval
     * @param  string/integer $dateOption
     * @return array
     */
    protected function previousRange(Request $request): array
    {
        $range = $request->input('range', collect($this->ranges())->keys()->first());

        if ($range == 'MTD') {
            return [now()->subMonth()->startOfMonth(), now()->subMonth()];
        }
        if ($range == 'QTD') {
            return [now()->subQuarter()->firstOfQuarter(), now()->subQuarter()];
        }
        if ($range == 'YTD') {
            return [now()->subYear()->startOfYear(), now()->subYear()];
        }
        if ($range == 'CURR_MONTH') {
            return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
        }

        return [now()->subDays($range * 2), now()->subDays($range)];
    }

    /**
     * Ejecuta query y devuelve datos
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return Collection
     */
    protected function getModelData(
        Request $request,
        string $resource = '',
        string $timeColumn = '',
        array $dateInterval = []
    ): Collection {
        return $this->newQuery($request, $resource)
            ->whereBetween($timeColumn, $dateInterval)
            ->get();
    }

    /**
     * Devuelve una nueva query con todos los filtros iniciales aplicados
     *
     * @param  Request $request
     * @param  string  $resource
     * @return Builder
     */
    protected function newQuery(Request $request, string $resource): Builder
    {
        return $this->applyFilters($request, $resource, (new $resource())->model()->query());
    }

    /**
     * Filtros adicionales para la query de la metrica
     * @param  Request $request
     * @param  Builder $query
     * @return Builder
     */
    protected function extendFilter(Request $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Aplica los filtros definidos para el recurso
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  Builder $query
     * @return Builder
     */
    protected function applyFilters(Request $request, string $resource, Builder $query): Builder
    {
        $query = $this->extendFilter($request, $query);

        collect((new $resource())->filters($request))
            ->filter->isSet($request)
            ->each(function ($filter) use ($request, &$query) {
                $query = $filter->apply($request, $query, $filter->getValue($request));
            });

        return $query;
    }

    /**
     * Devuelve HTML con contenido de la metrica
     *
     * @param  Request $request
     * @return HtmlString
     */
    protected function content(Request $request): HtmlString
    {
        return new HtmlString(view('orm.metrics.metric_content', [
            'cardId' => $this->cardId(),
        ]));
    }

    /**
     * Devuelve arreglo con rangos a mostrar en card
     *
     * @return array
     */
    public function ranges(): array
    {
        return [];
    }
}
