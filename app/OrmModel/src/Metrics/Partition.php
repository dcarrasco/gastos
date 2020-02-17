<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

abstract class Partition extends Metric
{
    public function count(Request $request, string $resource = '', string $groupColumn = '', string $relation = '')
    {
        $query = $this->newQuery($request, $resource);

        if ($relation != '') {
            $query = $this->addRelationQuery($request, $query, $resource, $relation);
        }

        return $query
            ->select(DB::raw("{$groupColumn} as grupo, count(*) as cant"))
            ->groupBy($groupColumn)
            ->orderBy('cant', 'desc')
            ->get();
    }

    public function sum(Request $request, string $resource = '', string $groupColumn = '', string $sumColumn = '', string $relation = ''): Collection
    {
        $query = $this->newQuery($request, $resource);

        if ($relation != '') {
            $query = $this->addRelationQuery($request, $query, $resource, $relation);
        }

        return $query
            ->select(DB::raw("{$groupColumn} as grupo, sum({$sumColumn}) as cant"))
            ->groupBy($groupColumn)
            ->orderBy('cant', 'desc')
            ->get();
    }

    protected function addRelationQuery(Request $request, Builder $query, string $resource = '', string $relation = ''): Builder
    {
        $relation = (new $resource())->model()->{$relation}();

        return $query->join($relation->getRelated()->getTable(), $relation->getQualifiedForeignKeyName(), '=', $relation->getQualifiedOwnerKeyName());
    }

    protected function countTotal(Request $request, string $resource = ''): int
    {
        return (new $resource())->model()->count();
    }

    protected function contentScript(Request $request): HtmlString
    {
        $dataSet = $this->calculate($request);

        return new HtmlString(view(
            'orm.metrics.partition_script',
            [
                'data' => new HtmlString(json_encode($dataSet->pluck('cant'))),
                'labels' => new HtmlString(json_encode($dataSet->pluck('grupo'))),
                'cardId' => $this->cardId(),
                'urlRoute' => route('gastosConfig.ajaxCard', [request()->segment(2) ?? '']),
                'resourceParams' => json_encode($request->query()),
                'baseUrl' => asset(''),
            ]
        ));
    }
}
