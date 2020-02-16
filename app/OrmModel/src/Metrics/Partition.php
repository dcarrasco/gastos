<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

abstract class Partition extends Metric
{
    public function count(Request $request, string $resource = '', string $column = '')
    {
        return (new $resource())->model()
            ->select(DB::raw("{$column} as grupo, count(*) as cant"))
            ->groupBy($column)
            ->get();
    }

    protected function countTotal(Request $request, string $resource = ''): int
    {
        return (new $resource())->model()->count();
    }

    public function ranges(): array
    {
        return [];
    }

    protected function contentScript(Request $request): HtmlString
    {
        $dataSet = collect($this->calculate($request));

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
