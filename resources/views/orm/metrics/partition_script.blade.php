<script type="text/javascript" src="{{ $baseUrl }}js/Chart.min.js"></script>
<script type="text/javascript" src="{{ $baseUrl }}js/Chart.bundle.min.js"></script>

<script type="text/javascript">
var chartData_{{ $cardId }} = {
    labels: {{ $labels }},
    datasets: [{
        data: {{ $data }}
    }]
};
var options_{{ $cardId }} = {
    cutoutPercentage: 60,
    legend: {
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