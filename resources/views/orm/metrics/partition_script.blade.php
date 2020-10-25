<!-- ------------------------------ SCRIPT DATOS PARTITON CHART ------------------------------ -->
<script type="text/javascript" src="{{ $baseUrl }}js/Chart.min.js"></script>
<script type="text/javascript" src="{{ $baseUrl }}js/Chart.bundle.min.js"></script>

<script type="text/javascript">
    function partitionChartData(labels, data) {
        return {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    "rgba(245, 87, 59, 1)",
                    "rgba(249, 144, 55, 1)",
                    "rgba(242, 203, 34, 1)",
                    "rgba(143, 193, 93, 1)",
                    "rgba(9, 143, 86, 1)",
                    "rgba(71, 193, 191, 1)",
                    "rgba(22, 147, 235, 1)",
                    "rgba(110, 116, 215, 1)",
                    "rgba(156, 106, 222, 1)",
                    "rgba(228, 113, 222, 1)",
                ]
            }]
        };
    }

    function partitionChartOptions() {
        return {
            cutoutPercentage: 70,
            layout: {
               padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
               }
            },
            legend: {
                display: false,
                // fullWidth: false,
                position: 'left',
                labels: {
                   fontSize: 10,
                   boxWidth: 10
                }
            }
        };
    }

    function drawPartitionChart_{{ $cardId }}(cardId, labels, data) {
        var ctx = document.getElementById('canvas-' + cardId).getContext('2d');

        chart_{{ $cardId }} = new Chart(ctx, {
            type: 'doughnut',
            data: partitionChartData(labels, data),
            options: partitionChartOptions(),
        });

        $('#legend-' + cardId).empty();
        $('#legend-' + cardId).append(chart_{{ $cardId }}.generateLegend());
        $('#legend-' + cardId + ' > ul').addClass('px-0');
        $('#legend-' + cardId + ' > ul > li').css('font-size', '10px');
        $('#legend-' + cardId + ' > ul > li > span').css('height', '10px');
        $('#legend-' + cardId + ' > ul > li > span').css('width', '10px');
        $('#legend-' + cardId + ' > ul > li > span').addClass('mx-2');
        $('#legend-' + cardId + ' > ul > li > span').css('display', 'inline-block');
        $('#legend-' + cardId + ' > ul > li > span').css('border-radius', '50%');
    }

    var chart_{{ $cardId }};

    $(document).ready(function() {
        drawPartitionChart_{{ $cardId }}(
            '{{ $cardId }}',
            {{ $labels }},
            {{ $data }},
        );
    });
</script>
