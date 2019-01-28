<?php

namespace App\OrmModel;

use Carbon\Carbon;
use App\OrmModel\Card;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class Trend extends Card
{
    protected $dateFormat = 'Y-m-d';


    public function data(Request $request)
    {
        return $this->calculate($request)->values()->all();
    }

    public function sum(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        $data = $this->fetchSumData($request, $model, $sumColumn, $timeColumn, $dateInterval);

        return $this->totalizedData($data, $dateInterval, $sumColumn, $timeColumn);
    }

    public function count(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        $data = $this->fetchCountData($request, $model, $timeColumn, $dateInterval);

        return $this->totalizedData($data, $dateInterval, 'count', $timeColumn);
    }


    protected function totalizedData($data = [], $dateInterval = [], $sumColumn = '', $timeColumn = '')
    {
        return $this->initTotalizedData($dateInterval)
            ->map(function($value, $date) use ($data, $sumColumn, $timeColumn) {
                return $data->where($timeColumn, $date)->sum($sumColumn);
            });
    }

    protected function initTotalizedData($dateInterval = [])
    {
        [$fechaInicio, $fechaFin] = $dateInterval;

        return collect(CarbonPeriod::create($fechaInicio, $fechaFin))->mapWithKeys(function($date) {
            return [$date->format($this->dateFormat) => 0];
        });
    }

    protected function fetchSumData(Request $request, $model = '', $sumColumn = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->fetchData($request, $model, $timeColumn, $dateInterval)
            ->map(function($modelObject) use($sumColumn, $timeColumn) {
                return [
                    $sumColumn => $modelObject->$sumColumn,
                    $timeColumn => $modelObject->$timeColumn->format($this->dateFormat),
                ];
            });
    }

    protected function fetchData(Request $request, $model = '', $timeColumn = '', $dateInterval = [])
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


    protected function fetchCountData(Request $request, $model = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->fetchData($request, $model, $timeColumn, $dateInterval)
            ->map(function($modelObject) use($timeColumn) {
                return [
                    'count' => 1,
                    $timeColumn => $modelObject->$timeColumn->format($this->dateFormat),
                ];
            });
    }

    protected function dateInterval(Request $request)
    {
        $dateIntervalOption = $this->dateIntervalOption($request);

        if (is_numeric($dateIntervalOption)) {
            return [Carbon::now()->subDays($dateIntervalOption), Carbon::now()];
        } else if ($dateIntervalOption === 'MTD') {
            return [Carbon::create(Carbon::now()->year, Carbon::now()->month, 1), Carbon::now()];
        } else if ($dateIntervalOption === 'QTD') {
            return [Carbon::now()->firstOfQuarter(), Carbon::now()];
        } else if ($dateIntervalOption === 'YTD') {
            return [Carbon::create(Carbon::now()->year, 1, 1), Carbon::now()];
        }
    }

    protected function dateIntervalOption(Request $request)
    {
        return $request->input('range', collect($this->ranges())->keys()->first());
    }

    protected function content(Request $request)
    {
        $cardId = $this->cardId();

        return "<canvas id=\"canvas-{$cardId}\" height=\"100%\"></canvas>";
    }

    protected function contentScript(Request $request)
    {
        $data = json_encode($this->data($request));
        $cardId = $this->cardId();
        $urlRoute = route('gastosConfig.ajaxCard', [request()->segment(2)]);
        $baseUrl = asset('');

        $script = <<<EOD
<script type="text/javascript" src="{$baseUrl}js/Chart.min.js"></script>
<script type="text/javascript" src="{$baseUrl}js/Chart.bundle.min.js"></script>

<script type="text/javascript">
var chartData_{$cardId} = {
    labels: $data,
    datasets: [{
        fill: true,
        backgroundColor: 'rgba(54,162,235,0.5)',
        borderColor: 'rgb(54,162,235)',
        data: $data
    }]
};
var options_{$cardId} = {
    legend: {display: false},
    elements: {
        line: {
            tension: 0.001,
        }
    },
    scales: {
        xAxes: [{
            display: false
        }],
        yAxes: [{
            display: false
        }]
    }
};

function drawCardChart_{$cardId}() {
    var ctx = document.getElementById('canvas-{$cardId}').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
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
        data: {'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
        async: true,
        success: function(data) {
            if (data) {
                chartData_{$cardId}.labels = data;
                chartData_{$cardId}.datasets[0].data = data;
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
