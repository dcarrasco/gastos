<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

abstract class Partition extends Metric
{
    /**
     * Devuelve el calculo de la metrica
     *
     * @param Request $request
     * @return Collection<array-key, int>
     */
    public function calculate(Request $request): Collection
    {
        return collect();
    }

    /**
     * Recupera datos de la particion, contando los registros
     *
     * @param  Request $request
     * @param  string  $resource
     * @param  string  $groupColumn
     * @param  string  $relation
     * @return Collection<array-key, int>
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
     * @return Collection<array-key, int>
     */
    public function sum(
        Request $request,
        string $resource,
        string $groupColumn,
        string $sumColumn,
        string $relation = ''
    ): Collection {
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
     * @return Collection<array-key, int>
     */
    protected function aggregate(
        Request $request,
        string $resource,
        string $groupColumn,
        string $function,
        string $sumColumn,
        string $relation
    ): Collection {
        return $this->newPartitionQuery($request, $resource, $relation)
            ->select(DB::raw("{$groupColumn} as label, {$function}({$sumColumn}) as aggregate"))
            ->groupBy($groupColumn)
            ->orderBy('aggregate', 'desc')
            ->pluck('aggregate', 'label');
    }

    /**
     * Devuelve una nueva query para Partition
     *
     * @param Request $request
     * @param string  $resource
     * @param string  $relation
     * @return Builder<Model>
     */
    protected function newPartitionQuery(Request $request, string $resource, string $relation): Builder
    {
        if (empty($relation)) {
            return $this->newQuery($request, $resource);
        }

        $relation = $this->getModel($resource)->{$relation}();

        return $this->newQuery($request, $resource)
            ->join(
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
            'baseUrl' => asset(''),
            'script' => $this->contentScript($request),
        ]));
    }

    /**
     * Devuelve arreglo para actualizar metrica
     *
     * @param  Request $request
     * @return string[]
     */
    public function contentAjaxRequest(Request $request): array
    {
        $cardId = $this->cardId();
        $dataSet = $this->calculate($request);
        $labels = $dataSet->keys();
        $data = $dataSet->values();

        return [
            'eval' => "drawPartitionChart_{$cardId}('{$cardId}', {$labels}, {$data})",
        ];
    }

    /**
     * Devuelve script para dibujar grafico de particion
     *
     * @param  Request $request
     * @return HtmlString
     */
    public function contentScript(Request $request): HtmlString
    {
        $dataSet = $this->calculate($request);

        return new HtmlString(
            view('orm.metrics.partition_script', [
                'data' => new HtmlString(json_encode($dataSet->values())),
                'labels' => new HtmlString(json_encode($dataSet->keys())),
                'cardId' => $this->cardId(),
                'baseUrl' => asset(''),
            ])
            ->render()
        );
    }
}
