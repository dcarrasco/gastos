<script type="text/javascript" src="{{ $baseUrl }}js/Chart.min.js"></script>
<script type="text/javascript" src="{{ $baseUrl }}js/Chart.bundle.min.js"></script>

<script type="text/javascript">
var chartData_{{ $cardId }} = {
    labels: {{ $labels }},
    datasets: [{
        data: {{ $data }},
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
var options_{{ $cardId }} = {
    cutoutPercentage: 60,
    legend: {
        display: false,
        position: 'left',
        labels: {
           fontSize: 10,
           boxWidth: 10
        }
    }
};

function drawCardChart_{{ $cardId }}() {
    var ctx = document.getElementById('canvas-{{ $cardId }}').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: chartData_{{ $cardId }},
        options: options_{{ $cardId }}
    });
}

$(document).ready(function() {
    drawCardChart_{{ $cardId }}();
});

function loadCardData_{{ $cardId }}(uriKey, cardId) {
    $('#spinner-' + cardId).removeClass('d-none');
    $.ajax({
        url: '{{ $urlRoute }}',
        data: {
            ...{'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
            ...{{ $resourceParams }}
            },
        async: true,
        success: function(data) {
            if (data) {
                chartData_{{ $cardId }}.labels = Object.keys(data);
                chartData_{{ $cardId }}.datasets[0].data = Object.values(data);
                drawCardChart_{{ $cardId }}();
                $('#spinner-' + cardId).addClass('d-none');
            }
        },
    });
}
</script>
