<?php

namespace App\OrmModel\src\Metrics;

use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

abstract class Trend extends Metric
{
    const BY_DAYS = 'by_days';
    const BY_MONTHS = 'by_months';
    const BY_YEARS = 'by_years';
    const BY_WEEKS = 'by_weeks';


    public function calculate(Request $request): Collection
    {
        return collect([]);
    }

    /**
     * Recupera datos de tendencia, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    protected function sum(Request $request, string $resource, string $unit, string $sumColumn, string $timeColumn): Collection
    {
        return $this->aggregate($request, $resource, $unit, 'sum', $sumColumn, $timeColumn, $this->currentRange($request));
    }

    /**
     * Recupera datos de tendencia, sumando una columna por dias
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByDays(Request $request, string $resource, string $sumColumn, string $timeColumn = ''): Collection
    {
        return $this->sum($request, $resource, Trend::BY_DAYS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por semanas
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByWeeks(Request $request, string $resource, string $sumColumn, string $timeColumn = ''): Collection
    {
        return $this->sum($request, $resource, Trend::BY_WEEKS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por meses
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByMonths(Request $request, string $resource, string $sumColumn, string $timeColumn = ''): Collection
    {
        return $this->sum($request, $resource, Trend::BY_MONTHS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por años
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByYears(Request $request, string $resource, string $sumColumn, string $timeColumn = ''): Collection
    {
        return $this->sum($request, $resource, Trend::BY_YEARS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $timeColumn
     * @return Collection
     */
    protected function count(Request $request, string $resource, string $unit, string $timeColumn): Collection
    {
        return $this->aggregate($request, $resource, $unit, 'count', '', $timeColumn, $this->currentRange($request));
    }

    /**
     * Recupera datos de tendencia, contando registros por dias
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByDays(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_DAYS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por semanas
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByWeeks(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_WEEKS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por meses
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByMonths(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_MONTHS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por años
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByYears(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_YEARS, $timeColumn);
    }

    /**
     * Inicializa arreglo de fechas con valores en cero
     *
     * @param  array  $dateInterval
     * @param  string $unit
     * @return Array
     */
    protected function initRangedData(array $dateInterval, string $unit): array
    {
        [$fechaInicio, $fechaFin] = $dateInterval;

        $period = collect(CarbonPeriod::create($fechaInicio, $fechaFin))
            ->map->format($this->dateFormatExpression($unit))
            ->unique();

        return $period->combine(array_fill(0, $period->count(), 0))->all();
    }

    /**
     * Devuelve expresion para formatear fecha Carbon
     *
     * @param  string $unit
     * @return string
     */
    protected function dateFormatExpression(string $unit): string
    {
        $rangeFormats = [
            Trend::BY_DAYS => 'Y-m-d',
            Trend::BY_WEEKS => 'Y-W',
            Trend::BY_MONTHS => 'Y-m',
            Trend::BY_YEARS => 'Y',
        ];

        return Arr::get($rangeFormats, $unit, 'Y-m-d');
    }

    /**
     * Devuelve expresion para formatear fecha en una consulta
     *
     * @param  string $unit
     * @return string
     */
    protected function databaseFormatExpression(string $unit): string
    {
        $rangeFormats = [
            Trend::BY_DAYS => '%Y-%m-%d',
            Trend::BY_WEEKS => '%x-%v',
            Trend::BY_MONTHS => '%Y-%m',
            Trend::BY_YEARS => '%Y',
        ];

        return Arr::get($rangeFormats, $unit, '%Y-%m-%d');
    }

    /**
     * Genera expresion para formatear fecha en una consulta
     *
     * @param  string $unit
     * @param  string $timeColumn
     * @return string
     */
    protected function selectDateExpression(string $resource, string $unit, string $timeColumn): string
    {
        $format = $this->databaseFormatExpression($unit);
        $dbDriver = $this->newResource($resource)->model()->getConnection()->getConfig('driver');

        if ($dbDriver === 'sqlite') {
            return "strftime('{$format}', {$timeColumn})";
        }

        return "date_format({$timeColumn}, '{$format}')";
    }

    /**
     * Recupera conjunto de datos para utilizar en tendencia
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $function
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection
     */
    protected function aggregate(Request $request, string $resource, string $unit, string $function, string $sumColumn, string $timeColumn): Collection
    {
        $dateInterval = $this->currentRange($request);

        $timeColumn = empty($timeColumn) ? $this->newResource($resource)->model()->getCreatedAtColumn() : $timeColumn;

        $query = $this->rangedQuery($request, $resource, $timeColumn, $dateInterval);
        $sumColumn = empty($sumColumn) ? $query->getModel()->getKeyName() : $sumColumn;

        $selectDateExpression = $this->selectDateExpression($resource, $unit, $timeColumn);

        $results = $query
            ->select(DB::raw("{$selectDateExpression} as date_result, {$function}({$sumColumn}) as aggregate"))
            ->groupBy(DB::raw($selectDateExpression))
            ->get()
            ->mapWithKeys(function ($data) {
                return [$data->date_result => $data->aggregate];
            });

        return collect(array_merge($this->initRangedData($dateInterval, $unit), $results->all()));
    }

    /**
     * Devuelve script para dibujar grafico de tendencia
     *
     * @param  Request $request
     * @return HtmlString
     */
    public function contentScript(Request $request): HtmlString
    {
        $dataSet = $this->calculate($request);

        return new HtmlString(view('orm.metrics.trend_script', [
            'data' => new HtmlString(json_encode($dataSet->values())),
            'labels' => new HtmlString(json_encode($dataSet->keys())),
            'cardId' => $this->cardId(),
            'baseUrl' => asset(''),
        ])->render());
    }
}
