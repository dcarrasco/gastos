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
     * Intervalos de fechas periodo actual
     *
     * @return array
     */
    public function currentIntervalOptions(): array
    {
        $today = Carbon::now()->startOfDay();

        return [
            'MTD' => [$today->copy()->startOfMonth(), $today->copy()->endOfDay()],
            'QTD' => [$today->copy()->firstOfQuarter(), $today->copy()->endOfDay()],
            'YTD' => [$today->copy()->startOfYear(), $today->copy()->endOfDay()],
            'CURR_MONTH' => [$today->copy()->startOfMonth(), $today->copy()->endOfDay()->endOfMonth()],
        ];
    }

    /**
     * Intervalos de fechas periodo anterior
     *
     * @return array
     */
    public function previousIntervalOptions(): array
    {
        $today = Carbon::now()->startOfDay();

        return [
            'MTD' => [$today->copy()->subMonth()->startOfMonth(), $today->copy()->endOfDay()->subMonth()],
            'QTD' => [$today->copy()->subQuarter()->firstOfQuarter(), $today->copy()->endOfDay()->subQuarter()],
            'YTD' => [$today->copy()->subYear()->startOfYear(), $today->copy()->endOfDay()->subYear()],
            'CURR_MONTH' => [
                $today->copy()->subMonth()->startOfMonth(),
                $today->copy()->endOfDay()->subMonth()->endOfMonth()
            ],
        ];
    }


    /**
     * Genera rango de fechas para realizar consultas
     *
     * @param  Request $request
     * @param  string  $period
     * @return Array
     */
    protected function currentDateInterval(Request $request): array
    {
        $dateOption = $request->input('range', collect($this->ranges())->keys()->first());
        $today = Carbon::now()->startOfDay();

        return (! is_numeric($dateOption))
            ? Arr::get($this->currentIntervalOptions(), $dateOption, [$today, $today->copy()->endOfDay()])
            : [$today->copy()->subDays($dateOption - 1), $today->copy()->endOfDay()];
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     *
     * @param  array $dateInterval
     * @param  string/integer $dateOption
     * @return array
     */
    protected function previousDateInterval(Request $request): array
    {
        $dateOption = $request->input('range', collect($this->ranges())->keys()->first());
        $today = Carbon::now()->startOfDay();

        return (! is_numeric($dateOption))
            ? Arr::get($this->previousIntervalOptions(), $dateOption, [$today, $today->copy()->endOfDay()])
            : [$today->copy()->subDays($dateOption * 2), $today->copy()->subDays($dateOption)];
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
        $query = (new $resource())->model()->whereBetween($timeColumn, $dateInterval);

        return $this->applyFilters($request, $resource, $query)
            ->get();
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
