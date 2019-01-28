<?php

namespace App\OrmModel;

use Carbon\Carbon;
use App\OrmModel\Card;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class Value extends Card
{
    protected $dateFormat = 'Y-m-d';
    protected $prefix = '';
    protected $suffix = '';


    public function data(Request $request)
    {
        return $this->formattedData($this->calculate($request));
    }

    public function sum(Request $request, $model = '', $sumColumn = '', $timeColumn = 'created_at')
    {
        $currentDateInterval = $this->currentDateInterval($request);
        $previousDateInterval = $this->previousDateInterval($request);

        return [
            'currentValue' => $this->fetchSumData($request, $model, $sumColumn, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchSumData($request, $model, $sumColumn, $timeColumn, $previousDateInterval),
        ];
    }

    public function count(Request $request, $model = '', $timeColumn = 'created_at')
    {
        $currentDateInterval = $this->currentDateInterval($request);
        $previousDateInterval = $this->previousDateInterval($request);

        return [
            'currentValue' => $this->fetchCountData($request, $model, $timeColumn, $currentDateInterval),
            'previousValue' => $this->fetchCountData($request, $model, $timeColumn, $previousDateInterval),
        ];
    }

    protected function fetchSumData(Request $request, $model = '', $sumColumn = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->fetchData($request, $model, $timeColumn, $dateInterval)->sum($sumColumn);
    }


    protected function fetchCountData(Request $request, $model = '', $timeColumn = '', $dateInterval = [])
    {
        return $this->fetchData($request, $model, $timeColumn, $dateInterval)->count();
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

    protected function currentDateInterval(Request $request)
    {
        $dateIntervalOption = $this->dateIntervalOption($request);
        $today = Carbon::now();

        if (is_numeric($dateIntervalOption)) {
            return [Carbon::now()->subDays($dateIntervalOption), $today];
        } else if ($dateIntervalOption === 'MTD') {
            return [Carbon::create($today->year, $today->month, 1), $today];
        } else if ($dateIntervalOption === 'QTD') {
            return [$today->firstOfQuarter(), $today];
        } else if ($dateIntervalOption === 'YTD') {
            return [Carbon::create($today->year, 1, 1), $today];
        }
    }

    protected function previousDateInterval(Request $request)
    {
        $dateIntervalOption = $this->dateIntervalOption($request);
        $today = Carbon::now();
        $todayLastYear = Carbon::create($today->year - 1, $today->month, $today->day);

        if (is_numeric($dateIntervalOption)) {
            return [Carbon::now()->subDays($dateIntervalOption * 2), Carbon::now()->subDays($dateIntervalOption)];
        } else if ($dateIntervalOption === 'MTD') {
            return [Carbon::create($todayLastYear->year, $todayLastYear->month, 1), $todayLastYear];
        } else if ($dateIntervalOption === 'QTD') {
            return [$todayLastYear->firstOfQuarter(), $todayLastYear];
        } else if ($dateIntervalOption === 'YTD') {
            return [Carbon::create($todayLastYear->year, 1, 1), $todayLastYear];
        }
    }

    protected function dateIntervalOption(Request $request)
    {
        return $request->input('range', collect($this->ranges())->keys()->first());
    }

    protected function previousMessage($currentValue, $previousValue)
    {
        if (empty($previousValue)) {
            return "Sin datos anteriores";
        }

        return number_format(($currentValue / $previousValue) * 100, 0, '.', ',') . '% de aumento';
    }

    protected function formattedData($data = [])
    {
        return [
            'currentValue' => $this->prefix.' '.number_format(array_get($data, 'currentValue', 0), 0, ',', '.').' '.$this->suffix,
            'previousValue' => $this->previousMessage(array_get($data, 'currentValue', 0), array_get($data, 'previousValue', 0)),
        ];
    }

    public function prefix($prefix = '')
    {
        $this->prefix = $prefix;

        return $this;
    }


    public function suffix($suffix = '')
    {
        $this->suffix = $suffix;

        return $this;
    }

    protected function content(Request $request)
    {
        $data = $this->data($request);

        $currentValue = array_get($data, 'currentValue', 0);
        $previousValue = array_get($data, 'previousValue', 0);

        $content = <<<EOD
            <div class="col-md-12">
                <h1 class="">$currentValue</h1>
            </div>
            <div class="col-md-12">
                <h5 class="text-secondary">$previousValue</h2>
            </div>
EOD;

        return $content;
    }

    protected function contentScript(Request $request)
    {
        $urlRoute = route('gastosConfig.ajaxCard', [request()->segment(2)]);
        $cardId = $this->cardId();

        return <<<EOD
<script type="text/javascript">
    function loadCardData_{$cardId}(uriKey, cardId) {
        $('#spinner-' + cardId).removeClass('d-none');
        $.ajax({
            url: '$urlRoute',
            data: {'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
            async: true,
            success: function(data) {
                if (data) {
                    $('#' + cardId + '> div > h1').text(data.currentValue);
                    $('#' + cardId + '> div > h5').text(data.previousValue);
                    $('#spinner-' + cardId).addClass('d-none');
                }
            },
        });
    }
</script>
EOD;
    }

}
