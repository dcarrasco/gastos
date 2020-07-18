<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

abstract class Partition extends Metric
{
    /**
     * Recupera datos de la particion, contando los registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $groupColumn
     * @param  string  $relation
     * @return Collection
     */
    public function count(Request $request, string $resource, string $groupColumn, string $relation = ''): Collection
    {
        return $this->aggregate($request, $resource, $groupColumn, 'count', '*', $relation);
    }

    /**
     * Recupera datos de la particion, sumando una columna
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $groupColumn
     * @param  string  $sumColumn
     * @param  string  $relation
     * @return Collection
     */
    public function sum(Request $request, string $resource, string $groupColumn, string $sumColumn, string $relation = ''): Collection
    {
        return $this->aggregate($request, $resource, $groupColumn, 'sum', $sumColumn, $relation);
    }

    /**
     * Recupera datos de la particion
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $groupColumn
     * @param  string  $function
     * @param  string  $sumColumn
     * @param  string  $relation
     * @return Collection
     */
    protected function aggregate(Request $request, string $resource, string $groupColumn, string $function, string $sumColumn, string $relation): Collection
    {
        $query = $this->newQuery($request, $resource);

        if ($relation != '') {
            $query = $this->addRelationQuery($query, $resource, $relation);
        }

        return $query
            ->select(DB::raw("{$groupColumn} as label, {$function}({$sumColumn}) as aggregate"))
            ->groupBy($groupColumn)
            ->orderBy('aggregate', 'desc')
            ->get()
            ->mapWithKeys(function ($data) {
                return [$data->label => $data->aggregate];
            });
    }

    /**
     * Agrega la relacion a la consulta de datos de la particion
     *
     * @param Builder $query
     * @param string  $resource
     * @param string  $relation
     * @return Builder
     */
    protected function addRelationQuery(Builder $query, string $resource, string $relation): Builder
    {
        $relation = (new $resource())->model()->{$relation}();

        return $query->join(
            $relation->getRelated()->getTable(),
            $relation->getQualifiedForeignKeyName(),
            '=',
            $relation->getQualifiedOwnerKeyName()
        );
    }

    /**
     * Devuelve HTML con contenido de la metrica
     *
     * @param  Request $request
     * @return HtmlString
     */
    public function content(Request $request): HtmlString
    {
        return new HtmlString(view('orm.metrics.partition_content', [
            'cardId' => $this->cardId(),
            'script' => $this->contentScript($request),
        ]));
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

        return new HtmlString(view('orm.metrics.partition_script', [
            'data' => new HtmlString(json_encode($dataSet->values())),
            'labels' => new HtmlString(json_encode($dataSet->keys())),
            'cardId' => $this->cardId(),
            'baseUrl' => asset(''),
        ])->render());
    }
}
