<?php

namespace App\OrmModel\src\Metrics;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

abstract class Trend extends Metric
{
    protected const BY_DAYS = 'by_days';

    protected const BY_MONTHS = 'by_months';

    protected const BY_YEARS = 'by_years';

    protected const BY_WEEKS = 'by_weeks';

    /**
     * Indica si la metrica mostrara valores en cero
     *
     * @var bool
     */
    protected bool $filtraValoresEnCero = false;

    /**
     * Devuelve el calculo de la metrica
     *
     * @param  Request  $request
     * @return Collection<array-key, int>
     */
    public function calculate(Request $request): Collection
    {
        return collect([]);
    }

    /**
     * Recupera datos de tendencia, sumando una columna
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    protected function sum(
        Request $request,
        string $resource,
        string $unit,
        string $sumColumn,
        string $timeColumn
    ): Collection {
        return $this->aggregate($request, $resource, $unit, 'sum', $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por dias
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function sumByDays(
        Request $request,
        string $resource,
        string $sumColumn,
        string $timeColumn = ''
    ): Collection {
        return $this->sum($request, $resource, Trend::BY_DAYS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por semanas
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function sumByWeeks(
        Request $request,
        string $resource,
        string $sumColumn,
        string $timeColumn = ''
    ): Collection {
        return $this->sum($request, $resource, Trend::BY_WEEKS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por meses
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function sumByMonths(
        Request $request,
        string $resource,
        string $sumColumn,
        string $timeColumn = ''
    ): Collection {
        return $this->sum($request, $resource, Trend::BY_MONTHS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, sumando una columna por años
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function sumByYears(
        Request $request,
        string $resource,
        string $sumColumn,
        string $timeColumn = ''
    ): Collection {
        return $this->sum($request, $resource, Trend::BY_YEARS, $sumColumn, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    protected function count(Request $request, string $resource, string $unit, string $timeColumn): Collection
    {
        return $this->aggregate($request, $resource, $unit, 'count', '', $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por dias
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function countByDays(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_DAYS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por semanas
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function countByWeeks(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_WEEKS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por meses
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function countByMonths(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_MONTHS, $timeColumn);
    }

    /**
     * Recupera datos de tendencia, contando registros por años
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    public function countByYears(Request $request, string $resource, string $timeColumn = ''): Collection
    {
        return $this->count($request, $resource, Trend::BY_YEARS, $timeColumn);
    }

    /**
     * Inicializa arreglo de fechas con valores en cero
     *
     * @param  Carbon[]  $dateInterval
     * @param  string  $unit
     * @return Collection<array-key, int>
     */
    protected function initRangedData(array $dateInterval, string $unit): Collection
    {
        return collect(CarbonPeriod::create(...$dateInterval)->toArray())
            ->map->format($this->dateFormatExpression($unit))
            ->flip()
            ->map(fn ($value) => 0);
    }

    /**
     * Devuelve expresion para formatear fecha Carbon
     *
     * @param  string  $unit
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

        return $rangeFormats[$unit] ?? 'Y-m-d';
    }

    /**
     * Devuelve expresion para formatear fecha en una consulta
     *
     * @param  string  $unit
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

        return $rangeFormats[$unit] ?? '%Y-%m-%d';
    }

    /**
     * Genera expresion para formatear fecha en una consulta
     *
     * @param  string  $unit
     * @param  string  $timeColumn
     * @return string
     */
    protected function selectDateExpression(string $resource, string $unit, string $timeColumn): string
    {
        $format = $this->databaseFormatExpression($unit);
        $dbDriver = $this->getModel($resource)->getConnection()->getConfig('driver');

        if ($dbDriver === 'sqlite') {
            return "strftime('{$format}', {$timeColumn})";
        }

        return "date_format({$timeColumn}, '{$format}')";
    }

    /**
     * Recupera conjunto de datos para utilizar en tendencia
     *
     * @param  Request  $request
     * @param  string  $resource
     * @param  string  $unit
     * @param  string  $function
     * @param  string  $aggregateColumn
     * @param  string  $timeColumn
     * @return Collection<array-key, int>
     */
    protected function aggregate(
        Request $request,
        string $resource,
        string $unit,
        string $function,
        string $aggregateColumn,
        string $timeColumn
    ): Collection {
        $dateInterval = $this->currentRange($request);

        $timeColumn = empty($timeColumn) ? $this->getModel($resource)->getCreatedAtColumn() : $timeColumn;

        $aggregateColumn = empty($aggregateColumn)
            ? $this->getModel($resource)->getKeyName()
            : $aggregateColumn;

        $selectDateExpression = $this->selectDateExpression($resource, $unit, $timeColumn);

        $results = $this->rangedQuery($request, $resource, $timeColumn, $dateInterval)
            ->select(DB::raw(
                "{$selectDateExpression} as date_expression, {$function}({$aggregateColumn}) as aggregate"
            ))
            ->groupBy(DB::raw($selectDateExpression))
            ->pluck('aggregate', 'date_expression');

        return $this->initRangedData($dateInterval, $unit)
            ->merge($results)
            ->filter(fn ($valor) => ! $this->filtraValoresEnCero or $valor != 0);
    }

    /**
     * Devuelve HTML con contenido de la metrica
     *
     * @param  Request  $request
     * @return HtmlString
     */
    public function content(Request $request): HtmlString
    {
        return new HtmlString(view('orm.metrics.trend_content', [
            'cardId' => $this->cardId(),
            'baseUrl' => asset(''),
            'script' => $this->contentScript($request),
        ])->render());
    }

    /**
     * Devuelve arreglo para actualizar metrica
     *
     * @param  Request  $request
     * @return string[]
     */
    public function contentAjaxRequest(Request $request): array
    {
        $cardId = $this->cardId();
        $dataSet = $this->calculate($request);
        $labels = $dataSet->keys();
        $data = $dataSet->values();

        return [
            'eval' => "drawTrendChart_{$cardId}('{$cardId}', {$labels}, {$data})",
        ];
    }

    /**
     * Devuelve script para dibujar grafico de tendencia
     *
     * @param  Request  $request
     * @return HtmlString
     */
    public function contentScript(Request $request): HtmlString
    {
        $dataSet = $this->calculate($request);

        return new HtmlString(
            view('orm.metrics.trend_script', [
                'data' => new HtmlString(json_encode($dataSet->values())),
                'labels' => new HtmlString(json_encode($dataSet->keys())),
                'cardId' => $this->cardId(),
            ])
            ->render()
        );
    }
}
