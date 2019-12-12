<script type="text/javascript" src="{{ $baseUrl }}js/Chart.min.js"></script>
<script type="text/javascript" src="{{ $baseUrl }}js/Chart.bundle.min.js"></script>

<script type="text/javascript">

var chart_{{ $cardId }};

var chartData_{{ $cardId }} = {
    labels: {{ $labels }},
    datasets: [{
        fill: true,
        backgroundColor: 'rgba(54,162,235,0.3)',
        borderColor: 'rgb(54,162,235)',
        pointBackgroundColor: 'rgb(54,162,235)',
        data: {{ $data }}
    }]
};

var options_{{ $cardId }} = {
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

function drawCardChart_{{ $cardId }}() {
    var ctx = document.getElementById('canvas-{{ $cardId }}').getContext('2d');

    chart_{{ $cardId }} = new Chart(ctx, {
        type: 'line',
        data: chartData_{{ $cardId }},
        options: options_{{ $cardId }}
    });
}

function loadCardData_{{ $cardId }}(uriKey, cardId) {
    $('div#' + cardId).addClass('d-none');
    $('#spinner-' + cardId).removeClass('d-none');
    $.ajax({
        url: '{{$urlRoute}}',
        data: {
            ...{'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
            ...{{$resourceParams}}
            },
        async: true,
        success: function(data) {
            if (data) {
                chartData_{{ $cardId }}.labels = Object.keys(data);
                chartData_{{ $cardId }}.datasets[0].data = Object.values(data);
                chart_{{ $cardId }}.update();
                $('#spinner-' + cardId).addClass('d-none');
                $('div#' + cardId).removeClass('d-none');
            }
        },
    });
}

$(document).ready(function() {
    drawCardChart_{{ $cardId }}();
});

</script>
