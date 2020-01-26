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

    protected $trendIconStyle = [
        'up' => 'transform: rotate(180deg); fill: #38c172;',
        'down' => 'transform: scaleX(-1); fill: #e3342f;',
    ];


    /**
     * Recupera datos de valor, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @return array
     */
    public function sum(Request $request, $resource = '', $sumColumn = '', $timeColumn = ''): array
    {
        $timeColumn = empty($timeColumn)
            ? (new $resource())->model()->getCreatedAtColumn()
            : $timeColumn;

        $currentDateInterval = $this->dateInterval($request);
        $previousDateInterval = $this->dateInterval($request, 'previous');

        return $this->formattedData([
            'currentValue' => $this->fetchSumData($request, $resource, $sumColumn, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchSumData($request, $resource, $sumColumn, $timeColumn, $previousDateInterval),
        ]);
    }

    /**
     * Recupera datos de valor, contando registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return array
     */
    public function count(Request $request, $resource = '', $timeColumn = ''): array
    {
        $timeColumn = empty($timeColumn)
            ? (new $resource())->model()->getCreatedAtColumn()
            : $timeColumn;

        $currentDateInterval = $this->dateInterval($request);
        $previousDateInterval = $this->dateInterval($request, 'previous');

        return $this->formattedData([
            'currentValue' => $this->fetchCountData($request, $resource, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchCountData($request, $resource, $timeColumn, $previousDateInterval),
        ]);
    }

    /**
     * Recupera datos para valor, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return int
     */
    protected function fetchSumData(
        Request $request,
        string $resource = '',
        string $sumColumn = '',
        string $timeColumn = '',
        array $dateInterval = []
    ): int {
        return $this->getModelData($request, $resource, $timeColumn, $dateInterval)
            ->sum($sumColumn);
    }

    /**
     * Recupera datos para valor, contando registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return int
     */
    protected function fetchCountData(
        Request $request,
        string $resource = '',
        string $timeColumn = '',
        array $dateInterval = []
    ): int {
        return $this->getModelData($request, $resource, $timeColumn, $dateInterval)
            ->count();
    }

    /**
     * Genera mensaje de cambio de valor periodo actual respecto periodo anterior
     *
     * @param  int|integer $currentValue
     * @param  int|integer $previousValue
     * @return string
     */
    protected function previousMessage(int $currentValue = 0, int $previousValue = 0)
    {
        if (empty($previousValue)) {
            return "Sin datos anteriores";
        }

        $percentChange = ($currentValue / $previousValue - 1) * 100;
        $textChange = $percentChange >= 0 ? 'aumento' : 'disminucion';

        return number_format($percentChange, 0, '.', ',') . '% de ' . $textChange;
    }

    /**
     * Genera arreglo con formato de datos de valor
     *
     * @param  array  $data
     * @return array
     */
    protected function formattedData(array $data = []): array
    {
        return [
            'currentValue' => "{$this->prefix} "
                . number_format(Arr::get($data, 'currentValue', 0), 0, ',', '.')
                . " {$this->suffix}",
            'previousValue' => $this->previousMessage(
                Arr::get($data, 'currentValue', 0),
                Arr::get($data, 'previousValue', 0)
            ),
            'trend' => Arr::get($data, 'currentValue', 0) >= Arr::get($data, 'previousValue', 0)
                ? Arr::get($this->trendIconStyle, 'up')
                : Arr::get($this->trendIconStyle, 'down'),
        ];
    }

    /**
     * Fija el prefijo del valor a desplegar
     *
     * @param  string $prefix
     * @return Metric
     */
    public function prefix(string $prefix = ''): Metric
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Fija el prefijo del valor a desplegar
     *
     * @param  string $prefix
     * @return Metric
     */
    public function suffix(string $suffix = ''): Metric
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Devuelve HTML con contenido de la metrica valor
     *
     * @param  Request $request
     * @return HtmlString
     */
    protected function content(Request $request): HtmlString
    {
        $data = $this->calculate($request);

        return new HtmlString(view('orm.metrics.value_content', [
            'currentValue' => Arr::get($data, 'currentValue', 0),
            'previousValue' => Arr::get($data, 'previousValue', 0),
            'trendIconStyle' => Arr::get($data, 'trend', ''),
        ]));
    }

    /**
     * Devuelve script para dibujar valores
     *
     * @param  Request $request
     * @return HtmlString
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
