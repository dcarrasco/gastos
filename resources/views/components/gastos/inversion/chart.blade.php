@props(['datosInversion'])

<div id="chartInversion" class="col-md-10 offset-md-1 my-5 bg-white border rounded" style="height: 400px">
    <canvas id="canvas-chartInversion"></canvas>
</div>

<script type="text/javascript" src="{{ asset('js/Chart.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Chart.bundle.min.js') }}"></script>


<script type="text/javascript">

var chartInversion;
var datosInversion = {!! $datosInversion !!}

var chartDataInversion = {
    labels: datosInversion.label,
    datasets: [{
        label: 'Rentabilidad (%)',
        fill: true,
        backgroundColor: 'rgba(54,162,235,0.1)',
        borderColor: 'rgb(54,162,235)',
        pointBackgroundColor: 'rgb(54,162,235)',
        yAxisID: 'y-axis-1',
        data: datosInversion.rentabilidad
    }, {
        label: 'Saldo ($)',
        fill: true,
        backgroundColor: 'rgba(249,143,54,0.0)',
        borderColor: 'rgb(249,143,54)',
        pointBackgroundColor: 'rgb(249,143,54)',
        yAxisID: 'y-axis-2',
        data: datosInversion.saldo
    }]
};

var optionsInversion = {
    backgroundColor: 'rgba(0,0,0,1)',
    borderColor: 'rgba(0,0,0,1)',
    title: {
        display: true,
        text: 'Desempeño Inversion'
    },
    legend: {
        display: true,
        position: "bottom",
        labels: {
            boxWidth: 20,
            padding: 40
        }
    },
    elements: {
        line: {
            tension: 0,
        }
    },
    tooltips: {
        callbacks: {
            label: function(toolTipItem, data) {
                if (toolTipItem.datasetIndex == 0) {
                    return 'Rentabilidad ' + Math.round(toolTipItem.yLabel*100) / 100 + '%';
                } else {
                    return 'Saldo $ ' + toolTipItem.yLabel.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                }
            }
        }
    },
    scales: {
        xAxes: [{
            display: true,
            ticks: {
                fontSize: 10
            },
        }],
        yAxes: [{
            id: 'y-axis-1',
            type: 'linear',
            display: true,
            position: 'left',
            scaleLabel: {
                display: true,
                labelString: 'Rentabilidad'
            },
            ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                    return value + '%';
                }
            }
        }, {
            id: 'y-axis-2',
            type: 'linear',
            display: true,
            position: 'right',
            scaleLabel: {
                display: true,
                labelString: 'Saldo'
            },
            gridLines: {
                display: false
            },
            ticks: {
                callback: function(value, index, values) {
                    return 'MM$ ' + (Math.round(value/100000)/10).toString().replace(/\./, ',');
                }
            }
        }]
    }
};

function drawCardChartInversion() {
    var ctx = document.getElementById('canvas-chartInversion').getContext('2d');

    chartInversion = new Chart(ctx, {
        type: 'line',
        data: chartDataInversion,
        options: optionsInversion
    });
}

$(document).ready(function() {
    drawCardChartInversion();
});

</script>
