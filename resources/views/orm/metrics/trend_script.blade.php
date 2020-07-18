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
    tooltips: {
        callbacks: {
            label: function (tooltipItem, data) {
                return  new Intl.NumberFormat('es-ES').format(tooltipItem.yLabel);
            }
        }
    },
    elements: {
        line: {
            tension: 0,
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

$(document).ready(function() {
    drawCardChart_{{ $cardId }}();
});

</script>
