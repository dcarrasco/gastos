<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Metric
{
    use DisplayAsCard;

    /**
     * Genera rango de fechas para realizar consultas
     * @param  Request $request
     * @param  string  $period
     * @return Array
     */
    protected function dateInterval(Request $request, $period = 'current')
    {
        $dateOption = $request->input('range', collect($this->ranges())->keys()->first());

        $todayIni = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $intervalOption = [
            'MTD' => [$todayIni->copy()->startOfMonth(), $todayEnd],
            'QTD' => [$todayIni->copy()->firstOfQuarter(), $todayEnd],
            'YTD' => [$todayIni->copy()->startOfYear(), $todayEnd],
        ];

        $dateInterval = ( ! is_numeric($dateOption))
            ? Arr::get($intervalOption, $dateOption, [$todayIni, $todayEnd])
            : [$todayIni->copy()->subDays($dateOption - 1), $todayEnd];

        if ($period === 'previous') {
            $dateInterval = $this->previousDateInterval($dateInterval, $dateOption);
        }

        return $dateInterval;
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     * @param  array $dateInterval
     * @param  string/integer $dateOption
     * @return array
     */
    protected function previousDateInterval($dateInterval, $dateOption)
    {
        [$dateIni, $dateEnd] = $dateInterval;

        $intervalOption = [
            'MTD' => [$dateIni->copy()->subMonth(), $dateEnd->copy()->subMonth()],
            'QTD' => [$dateIni->copy()->subQuarter(), $dateEnd->copy()->subQuarter()],
            'YTD' => [$dateIni->copy()->subYear(), $dateEnd->copy()->subYear()],
        ];

        return ( ! is_numeric($dateOption))
            ? Arr::get($intervalOption, $dateOption, [$dateIni, $dateEnd])
            : [$dateIni->copy()->subDays($dateOption), $dateEnd->copy()->subDays($dateOption)];
    }


    /**
     * Ejecuta query y devuelve datos
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return Collection
     */
    protected function getModelData(Request $request, $resource = '', $timeColumn = '', $dateInterval = [])
    {
        $query = (new $resource)->model()->whereBetween($timeColumn, $dateInterval);

        return $this->applyResourceFilters($request, $resource, $query)
            ->get();
    }

    /**
     * Aplica los filtros definidos para el recurso
     * @param  Request $request
     * @param  string  $resource
     * @param  Builder $query
     * @return Builder
     */
    protected function applyResourceFilters(Request $request, $resource = '', Builder $query)
    {
        collect((new $resource)->filters($request))
            ->filter->isSet($request)
            ->each(function($filter) use ($request, &$query) {
                $query = $filter->apply($request, $query, $filter->getValue($request));
            });

        return $query;
    }

    /**
     * Devuelve HTML con contenido de la metrica
     * @param  Request $request
     * @return string
     */
    protected function content(Request $request)
    {
        $cardId = $this->cardId();

        return "<canvas id=\"canvas-{$cardId}\" height=\"100%\"></canvas>";
    }
}
