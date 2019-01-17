<div class="card {{ $cardWidth }} px-0">
    <div class="card-body px-1 py-2">
        <h5 class="card-title pl-4">{{ $title }}</h5>
        <div id="{{ $cardId }}" class="row mx-0" style="height: 100px"></div>
    </div>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable({!! $data !!});

        var options = {
          legend: { position: 'none' },
          hAxis: { textPosition: 'none'},
          chartArea: {width: '100%', height: '100%'},
          vAxis: {gridlines: {color: 'none'}},
          series: {
            0: {pointSize: 3}
          }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('{{ $cardId }}'));

        chart.draw(data, options);
      }
    </script>
</div>
