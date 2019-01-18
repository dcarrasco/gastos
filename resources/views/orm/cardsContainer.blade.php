@if ($cards->count())
<!-- ---------- Cards Container ---------- -->
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
            url: '{{ route('gastosConfig.ajaxCard', [request()->segment(2)]) }}',
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

<div class="col-md-12">
    <div class="mt-2 row">
    @foreach($cards as $card)
        {!! $card !!}
    @endforeach
    </div>
</div>
<!-- ---------- End Cards Container ---------- -->
@endif
