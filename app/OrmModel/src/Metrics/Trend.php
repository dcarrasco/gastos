<?php

namespace App\OrmModel\src\Metrics;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

abstract class Trend extends Metric
{
    protected $dateFormat = 'Y-m-d';

    protected $trend = [];


    protected function sum(Request $request, string $resource = '', string $sumColumn = '', string $timeColumn = ''): Collection
    {
        $timeColumn = empty($timeColumn)
            ? (new $resource)->model()->getCreatedAtColumn()
            : $timeColumn;

        $dateInterval = $this->dateInterval($request);

        return $this->fetchData($request, $resource, $sumColumn, $timeColumn, $dateInterval);
    }

    public function sumByDays(Request $request, $resource = '', $sumColumn = '', $timeColumn = ''): Collection
    {
        return $this->sum($request, $resource, $sumColumn, $timeColumn);
    }

    public function sumByWeeks(Request $request, $resource = '', $sumColumn = '', $timeColumn = ''): Collection
    {
        $this->dateFormat = 'W';

        return $this->sum($request, $resource, $sumColumn, $timeColumn);
    }

    public function sumByMonths(Request $request, $resource = '', $sumColumn = '', $timeColumn = ''): Collection
    {
        $this->dateFormat = 'Y-m';

        return $this->sum($request, $resource, $sumColumn, $timeColumn);
    }

    protected function count(Request $request, $resource = '', $timeColumn = ''): Collection
    {
        $timeColumn = empty($timeColumn)
            ? (new $resource)->model()->getCreatedAtColumn()
            : $timeColumn;

        $dateInterval = $this->dateInterval($request);

        return $this->fetchData($request, $resource, '__count__', $timeColumn, $dateInterval);
    }

    public function countByDays(Request $request, $resource = '', $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, $timeColumn);
    }

    public function countByWeeks(Request $request, $resource = '', $timeColumn = ''): Collection
    {
        $this->dateFormat = 'W';

        return $this->count($request, $resource, $timeColumn);
    }

    public function countByMonths(Request $request, $resource = '', $timeColumn = ''): Collection
    {
        $this->dateFormat = 'Y-m';

        return $this->count($request, $resource, $timeColumn);
    }

    /**
     * Inicializa arreglo de fechas con valores en cero
     * @param  array  $dateInterval
     * @return Collection
     */
    protected function initTotalizedData($dateInterval = []): Collection
    {
        [$fechaInicio, $fechaFin] = $dateInterval;

        $period = collect(CarbonPeriod::create($fechaInicio, $fechaFin))
            ->map->format($this->dateFormat);

        return $period->combine(array_fill(0, $period->count(), 0));
    }

    /**
     * Recupera conjunto de datos para utilizar
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return Collection
     */
    protected function fetchData(Request $request, $resource = '', $sumColumn = '', $timeColumn = '', $dateInterval = []): Collection
    {
        $data = $this->getModelData($request, $resource, $timeColumn, $dateInterval)
            ->map(function($data) use ($sumColumn, $timeColumn) {
                return [
                    'value' => $sumColumn === '__count__' ? 1 : $data->{$sumColumn},
                    'time' => $data->{$timeColumn}->format($this->dateFormat)
                ];
            });

        return $this->initTotalizedData($dateInterval)
            ->map(function($value, $date) use ($data) {
                return $data->where('time', $date)->sum('value');
            });
    }

    /**
     * Devuelve script para dibujar grafico de tendencia
     * @param  Request $request
     * @return string
     */
    protected function contentScript(Request $request): HtmlString
    {
        $dataSet = $this->calculate($request);

        return new HtmlString(view('orm.metrics.trend_script', [
            'data' => new HtmlString(json_encode($dataSet->values())),
            'labels' => new HtmlString(json_encode($dataSet->keys())),
            'cardId' => $this->cardId(),
            'urlRoute' => route('gastosConfig.ajaxCard', [request()->segment(2)]),
            'resourceParams' => new HtmlString(json_encode($request->query())),
            'baseUrl' => asset(''),
        ])->render());
    }

}
