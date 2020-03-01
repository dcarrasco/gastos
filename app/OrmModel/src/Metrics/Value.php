<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Route;

abstract class Value extends Metric
{
    protected $dateFormat = 'Y-m-d';
    protected $prefix = '';
    protected $suffix = '';

    protected $trendIconStyle = [
        'up' => 'transform: rotate(180deg); fill: #38c172;',
        'down' => 'transform: scaleX(-1); fill: #e3342f;',
        'none' => 'display: none;',
    ];


    /**
     * Recupera datos de valor, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $column
     * @param  string  $timeColumn
     * @return array
     */
    public function sum(Request $request, string $resource, string $column, string $timeColumn = ''): array
    {
        return $this->aggregate($request, $resource, $column, $timeColumn, 'sum');
    }

    /**
     * Recupera datos de valor, minimo de una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $column
     * @param  string  $timeColumn
     * @return array
     */
    public function min(Request $request, string $resource, string $column, string $timeColumn = ''): array
    {
        return $this->aggregate($request, $resource, $column, $timeColumn, 'min');
    }

    /**
     * Recupera datos de valor, maximo de una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $column
     * @param  string  $timeColumn
     * @return array
     */
    public function max(Request $request, string $resource, string $column, $timeColumn = ''): array
    {
        return $this->aggregate($request, $resource, $column, $timeColumn, 'max');
    }

    /**
     * Recupera datos de valor, promedio de una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $column
     * @param  string  $timeColumn
     * @return array
     */
    public function average(Request $request, string $resource, string $column, $timeColumn = ''): array
    {
        return $this->aggregate($request, $resource, $column, $timeColumn, 'avg');
    }

    /**
     * Recupera datos de valor, contando registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $timeColumn
     * @return array
     */
    public function count(Request $request, string $resource, string $timeColumn = ''): array
    {
        return $this->aggregate($request, $resource, '', $timeColumn, 'count');
    }

    /**
     * Recupera datos de valor, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @param  string  $function
     * @return array
     */
    public function aggregate(Request $request, string $resource, string $sumColumn, string $timeColumn, string $function): array
    {
        $timeColumn = empty($timeColumn) ? (new $resource())->model()->getCreatedAtColumn() : $timeColumn;

        return $this->formattedData([
            'currentValue' => $this->rangedQuery($request, $resource, $timeColumn, $this->currentRange($request))
                ->get()->{$function}($sumColumn),
            'previousValue' => $this->rangedQuery($request, $resource, $timeColumn, $this->previousRange($request))
                ->get()->{$function}($sumColumn),
        ]);
    }

    /**
     * Genera mensaje de cambio de valor periodo actual respecto periodo anterior
     *
     * @param  int|integer $currentValue
     * @param  int|integer $previousValue
     * @return string
     */
    protected function previousMessage(int $currentValue, int $previousValue): string
    {
        if (empty($previousValue)) {
            return "Sin datos anteriores";
        }

        $percentChange = number_format(($currentValue / $previousValue - 1) * 100, 0, '.', ',');
        $textChange = $percentChange >= 0 ? 'aumento' : 'disminucion';

        return "{$percentChange}% de {$textChange}";
    }

    /**
     * Genera arreglo con formato de datos de valor
     *
     * @param  array  $data
     * @return array
     */
    protected function formattedData(array $data): array
    {
        $currentValue = Arr::get($data, 'currentValue', 0);
        $previousValue = Arr::get($data, 'previousValue', 0);
        $formattedCurrentValue = number_format($currentValue, 0, ',', '.');

        return [
            'currentValue' => "{$this->prefix} {$formattedCurrentValue} {$this->suffix}",
            'previousValue' => $this->previousMessage($currentValue, $previousValue),
            'trendIconStyle' => empty($previousValue)
                ? Arr::get($this->trendIconStyle, 'none')
                : Arr::get($this->trendIconStyle, $currentValue >= $previousValue ? 'up' : 'down'),
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
            'cardId' => $this->cardId(),
            'currentValue' => Arr::get($data, 'currentValue', 0),
            'previousValue' => Arr::get($data, 'previousValue', 0),
            'trendIconStyle' => Arr::get($data, 'trendIconStyle', ''),
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
            'urlRoute' => $this->urlRoute($request),
            'cardId' => $this->cardId(),
            'resourceParams' => new HtmlString(json_encode($request->query())),
        ])->render());
    }
}
