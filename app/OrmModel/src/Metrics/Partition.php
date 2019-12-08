<?php

namespace App\OrmModel\src\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Partition extends Metric
{
    public function count(Request $request, $resource = '', $column = ''): int
    {
        return (new $resource)->model()
            ->select(DB::raw($column . ' as grupo, count(*) as cant'))
            ->groupBy($column)
            ->get();
    }

    protected function countTotal(Request $request, $resource = ''): int
    {
        return (new $resource)->model()->count();
    }

    public function ranges(): array
    {
        return [];
    }

    protected function contentScript(Request $request): string
    {
        $dataSet = $this->calculate($request);
        $data = json_encode($dataSet->pluck('cant'));
        $labels = json_encode($dataSet->pluck('grupo'));
        $cardId = $this->cardId();
        $urlRoute = route('gastosConfig.ajaxCard', [request()->segment(2)]);
        $resourceParams = json_encode($request->query());
        $baseUrl = asset('');

        $script = <<<EOD
<script type="text/javascript" src="{$baseUrl}js/Chart.min.js"></script>
<script type="text/javascript" src="{$baseUrl}js/Chart.bundle.min.js"></script>

<script type="text/javascript">
var chartData_{$cardId} = {
    labels: $labels,
    datasets: [{
        data: $data
    }]
};
var options_{$cardId} = {
    cutoutPercentage: 60,
    legend: {
        position: 'left',
        labels: {
           fontSize: 10,
           boxWidth: 10
        }
    }
};

function drawCardChart_{$cardId}() {
    var ctx = document.getElementById('canvas-{$cardId}').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: chartData_{$cardId},
        options: options_{$cardId}
    });
}

$(document).ready(function() {
    drawCardChart_{$cardId}();
});

function loadCardData_{$cardId}(uriKey, cardId) {
    $('#spinner-' + cardId).removeClass('d-none');
    $.ajax({
        url: '$urlRoute',
        data: {
            ...{'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
            ...{$resourceParams}
            },
        async: true,
        success: function(data) {
            if (data) {
                chartData_{$cardId}.labels = Object.keys(data);
                chartData_{$cardId}.datasets[0].data = Object.values(data);
                drawCardChart_{$cardId}();
                $('#spinner-' + cardId).addClass('d-none');
            }
        },
    });
}
</script>
EOD;

        return $script;
    }


}
