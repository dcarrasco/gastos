<?php

namespace App\OrmModel;

use App\OrmModel\Metric;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class Trend extends Metric
{
    protected $dateFormat = 'Y-m-d';

    protected $trend = [];


    /**
     * Suma los registros
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    protected function sum(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        return $this->fetchData($request, $model, $sumColumn, $timeColumn, $dateInterval);
    }

    /**
     * Suma los registros por dias
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByDays(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        return $this->sum($request, $model, $sumColumn, $timeColumn);
    }

    /**
     * Suma los registros por semanas
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByWeeks(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $this->dateFormat = 'W';

        return $this->sum($request, $model, $sumColumn, $timeColumn);
    }

    /**
     * Suma los registros por meses
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function sumByMonths(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $this->dateFormat = 'M';

        return $this->sum($request, $model, $sumColumn, $timeColumn);
    }

    /**
     * Cuenta los registros
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    protected function count(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        return $this->fetchData($request, $model, '__count__', $timeColumn, $dateInterval);
    }

    /**
     * Cuenta los registros por dias
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByDays(Request $request, $model = '', $timeColumn = 'created_at')
    {
        return $this->count($request, $model, $timeColumn);
    }

    /**
     * Cuenta los registros por semanas
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByWeeks(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $this->dateFormat = 'W';

        return $this->count($request, $model, $timeColumn);
    }

    /**
     * Cuenta los registros por meses
     * @param  Request $request
     * @param  string  $model
     * @param  string  $timeColumn
     * @return Collection
     */
    public function countByMonths(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $this->dateFormat = 'M';

        return $this->count($request, $model, $timeColumn);
    }

    /**
     * Inicializa arreglo de fechas con valores en cero
     * @param  array  $dateInterval
     * @return Collection
     */
    protected function initTotalizedData($dateInterval = [])
    {
        [$fechaInicio, $fechaFin] = $dateInterval;

        $period = collect(CarbonPeriod::create($fechaInicio, $fechaFin))->map(function($date) {
            return $date->format($this->dateFormat);
        });

        return $period->combine(array_fill(0, $period->count(), 0));
    }

    /**
     * Recupera conjunto de datos para utilizar
     * @param  Request $request
     * @param  string  $model
     * @param  string  $sumColumn
     * @param  string  $timeColumn
     * @param  array   $dateInterval
     * @return Collection
     */
    protected function fetchData(Request $request, $model = '', $sumColumn = '', $timeColumn = '', $dateInterval = [])
    {
        $data = $this->getModelData($request, $model, $timeColumn, $dateInterval)
            ->map(function($data) use ($sumColumn, $timeColumn) {
                return [
                    'value' => $sumColumn === '__count__' ? 1 : $data->{$sumColumn},
                    'time' => $data->{$timeColumn}->format($this->dateFormat)
                ];
            });

        return $this->initTotalizedData($dateInterval)
            ->map(function($value, $date) use ($data) {
                return $data->where('time', $date)->sum('value');
            });
    }

    protected function content(Request $request)
    {
        $cardId = $this->cardId();

        return "<canvas id=\"canvas-{$cardId}\" height=\"100%\"></canvas>";
    }

    protected function contentScript(Request $request)
    {
        $dataSet = $this->calculate($request);
        $data = json_encode($dataSet->values());
        $labels = json_encode($dataSet->keys());
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
        fill: true,
        backgroundColor: 'rgba(54,162,235,0.3)',
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
