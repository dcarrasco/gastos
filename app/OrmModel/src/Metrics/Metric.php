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
    protected function dateInterval(Request $request, string $period = 'current'): array
    {
        $dateOption = $request->input('range', collect($this->ranges())->keys()->first());

        $todayIni = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $intervalOption = [
            'MTD' => [$todayIni->copy()->startOfMonth(), $todayEnd],
            'QTD' => [$todayIni->copy()->firstOfQuarter(), $todayEnd],
            'YTD' => [$todayIni->copy()->startOfYear(), $todayEnd],
            'CURR_MONTH' => [$todayIni->copy()->startOfMonth(), $todayEnd->copy()->endOfMonth()],
        ];

        $dateInterval = (! is_numeric($dateOption))
            ? Arr::get($intervalOption, $dateOption, [$todayIni, $todayEnd])
            : [$todayIni->copy()->subDays($dateOption - 1), $todayEnd];

        if ($period === 'previous') {
            $dateInterval = $this->previousDateInterval($dateInterval, $dateOption);
        }

        return $dateInterval;
    }

    /**
     * Devuelve el intervalo de fechas del periodo anterior
     *
     * @param  array $dateInterval
     * @param  string/integer $dateOption
     * @return array
     */
    protected function previousDateInterval(array $dateInterval, string $dateOption): array
    {
        [$dateIni, $dateEnd] = $dateInterval;

        $intervalOption = [
            'CURR_MONTH' => [$dateIni->copy()->subMonth(), $dateEnd->copy()->subMonth()->endOfMonth()],
            'MTD' => [$dateIni->copy()->subMonth(), $dateEnd->copy()->subMonth()],
            'QTD' => [$dateIni->copy()->subQuarter(), $dateEnd->copy()->subQuarter()],
            'YTD' => [$dateIni->copy()->subYear(), $dateEnd->copy()->subYear()],
        ];

        return (! is_numeric($dateOption))
            ? Arr::get($intervalOption, $dateOption, [$dateIni, $dateEnd])
            : [$dateIni->copy()->subDays($dateOption), $dateEnd->copy()->subDays($dateOption)];
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

        return $this->applyResourceFilters($request, $resource, $query)
            ->get();
    }

    /**
     * Aplica los filtros definidos para el recurso
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  Builder $query
     * @return Builder
     */
    protected function applyResourceFilters(
        Request $request,
        string $resource,
        Builder $query
    ): Builder {
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
}
