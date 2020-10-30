<!-- ------------------------------ SCRIPT DATOS PARTITION CHART ------------------------------ -->
<script type="text/javascript">
    function trendChartData(labels, data) {
        return {
            labels: labels,
            datasets: [{
                fill: true,
                backgroundColor: 'rgba(54,162,235,0.3)',
                borderColor: 'rgb(54,162,235)',
                pointBackgroundColor: 'rgb(54,162,235)',
                data: data
            }]
        }
    };

    function trendChartOptions() {
        return {
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
        }
    };

    function drawTrendChart_{{ $cardId }}(cardId, labels, data) {
        var ctx = document.getElementById('canvas-' + cardId).getContext('2d');

        if (chart_{{ $cardId }} !== undefined) {
            chart_{{ $cardId }}.destroy();
        }

        chart_{{ $cardId }} = new Chart(ctx, {
            type: 'line',
            data: trendChartData(labels, data),
            options: trendChartOptions()
        });
    };

    let chart_{{ $cardId }};

    $(document).ready(function() {
        drawTrendChart_{{ $cardId }}('{{ $cardId }}', {{ $labels }}, {{ $data }});
    });
</script>
