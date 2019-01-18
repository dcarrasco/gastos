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
        return $this->formatGoogleGraphData($this->calculate($request));
    }

    protected function formatGoogleGraphData($data = [])
    {
        $formattedData = collect([['Fecha', 'Valor']])
            ->merge($data->map(function($valor, $fecha) {
                return [$fecha, $valor];
            })->values())->all();

        return $formattedData;
    }

    public function sum(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        $data = $this->fetchSumData($model, $sumColumn, $timeColumn, $dateInterval);

        return $this->totalizedData($data, $dateInterval, $sumColumn, $timeColumn);
    }

    public function count(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $dateInterval = $this->dateInterval($request);

        $data = $this->fetchCountData($model, $timeColumn, $dateInterval);

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
        $period = CarbonPeriod::create($dateInterval[0], $dateInterval[1]);

        $baseDates = [];
        foreach ($period as $date) {
            $baseDates[$date->format($this->dateFormat)] = 0;
       }

       return collect($baseDates);
    }

    protected function fetchSumData($model = '', $sumColumn = '', $timeColumn = '', $dateInterval = [])
    {
        return (new $model)->whereBetween($timeColumn, $dateInterval)
            ->get()
            ->map(function($modelObject) use($sumColumn, $timeColumn) {
                return [
                    $sumColumn => $modelObject->$sumColumn,
                    $timeColumn => $modelObject->$timeColumn->format($this->dateFormat),
                ];
            });
    }


    protected function fetchCountData($model = '', $timeColumn = '', $dateInterval = [])
    {
        return (new $model)->whereBetween($timeColumn, $dateInterval)
            ->get()
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
        return '';
    }

    protected function contentScript(Request $request)
    {
        $data = json_encode($this->data($request));
        $cardId = $this->cardId();
        $urlRoute = route('gastosConfig.ajaxCard', [request()->segment(2)]);

        $script = <<<EOD
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});

    function drawChart(arrayData, cardId) {
        var data = google.visualization.arrayToDataTable(arrayData);

        var options = {
          legend: { position: 'none' },
          hAxis: { textPosition: 'none'},
          chartArea: {width: '100%', height: '100%'},
          vAxis: {gridlines: {color: 'none'}},
          series: {
            0: {pointSize: 3}
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById(cardId));

        chart.draw(data, options);
    }

    function loadCardData(uriKey, cardId) {
        $('#spinner-' + cardId).removeClass('d-none');
        $.ajax({
            url: '$urlRoute',
            data: {'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
            async: true,
            success: function(data) {
                if (data) {
                    drawChart(data, cardId);
                    $('#spinner-' + cardId).addClass('d-none');
                }
            },
        });
    }
</script>

<script type="text/javascript">
    google.charts.setOnLoadCallback(initDrawChart);

    function initDrawChart() {
        arrayData = $data;
        initDrawChart = drawChart(arrayData, '$cardId');
    }
</script>
EOD;

        return $script;
    }

}
