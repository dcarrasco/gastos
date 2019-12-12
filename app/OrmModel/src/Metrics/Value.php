<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

abstract class Value extends Metric
{
    protected $dateFormat = 'Y-m-d';
    protected $prefix = '';
    protected $suffix = '';


    public function sum(Request $request, $resource = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $currentDateInterval = $this->dateInterval($request);
        $previousDateInterval = $this->dateInterval($request, 'previous');

        return $this->formattedData([
            'currentValue' => $this->fetchSumData($request, $resource, $sumColumn, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchSumData($request, $resource, $sumColumn, $timeColumn, $previousDateInterval),
        ]);
    }

    public function count(Request $request, $resource = '', $timeColumn = 'created_at')
    {
        $currentDateInterval = $this->dateInterval($request);
        $previousDateInterval = $this->dateInterval($request, 'previous');

        return $this->formattedData([
            'currentValue' => $this->fetchCountData($request, $resource, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchCountData($request, $resource, $timeColumn, $previousDateInterval),
        ]);
    }

    protected function fetchSumData(Request $request, $resource = '', $sumColumn = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->getModelData($request, $resource, $timeColumn, $dateInterval)
            ->sum($sumColumn);
    }

    protected function fetchCountData(Request $request, $resource = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->getModelData($request, $resource, $timeColumn, $dateInterval)
            ->count();
    }

    protected function previousMessage($currentValue, $previousValue)
    {
        if (empty($previousValue)) {
            return "Sin datos anteriores";
        }

        $percentChange = ($currentValue / $previousValue - 1) * 100;
        $textChange = $percentChange >= 0 ? 'aumento' : 'disminucion';

        return number_format($percentChange, 0, '.', ',') . '% de ' . $textChange;
    }

    protected function formattedData($data = []): array
    {
        return [
            'currentValue' => $this->prefix.' '.number_format(Arr::get($data, 'currentValue', 0), 0, ',', '.').' '.$this->suffix,
            'previousValue' => $this->previousMessage(Arr::get($data, 'currentValue', 0), Arr::get($data, 'previousValue', 0)),
        ];
    }

    public function prefix($prefix = ''): Metric
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function suffix($suffix = ''): Metric
    {
        $this->suffix = $suffix;

        return $this;
    }

    protected function content(Request $request): HtmlString
    {
        $data = $this->calculate($request);

        return new HtmlString(view('orm.metrics.value_content', [
            'currentValue' => Arr::get($data, 'currentValue', 0),
            'previousValue' => Arr::get($data, 'previousValue', 0),
        ]));
    }

    /**
     * Devuelve script para dibujar valores
     * @param  Request $request
     * @return string
     */
    protected function contentScript(Request $request): HtmlString
    {
        return new HtmlString(view('orm.metrics.value_script', [
            'urlRoute' => route('gastosConfig.ajaxCard', [request()->segment(2)]),
            'cardId' => $this->cardId(),
            'resourceParams' => new HtmlString(json_encode($request->query())),
        ])->render());
    }

}
