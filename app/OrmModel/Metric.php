<?php

namespace App\OrmModel;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class Metric extends Card
{
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


    protected function getModelData(Request $request, $model = '', $timeColumn = '', $dateInterval = [])
    {
        $query = (new $model)->whereBetween($timeColumn, $dateInterval);

        $query = $this->applyResourceFilters($request, $model, $query);

        return $query->get();
    }

    protected function applyResourceFilters(Request $request, $model = '', $query)
    {
        $resourceClass = 'App\\OrmModel\\Gastos\\' . class_basename($model);
        $resourceFilters = (new $resourceClass)->filters($request);

        foreach($resourceFilters as $filter) {
            if ($filter->isSet($request)) {
                $query = $filter->apply($request, $query, $filter->getValue($request));
            }
        }

        return $query;
    }
}
