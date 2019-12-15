<script type="text/javascript">
    function loadCardData_{{ $cardId }}(uriKey, cardId) {
        $('div#' + cardId).addClass('d-none');
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
                    $('#' + cardId + ' > div > h1').text(data.currentValue);
                    $('#' + cardId + ' > div > h5 > span#text').text(data.previousValue);
                    $('#' + cardId + ' > div > h5 > span#icon > svg').attr('style', data.trend);
                    $('#spinner-' + cardId).addClass('d-none');
                    $('div#' + cardId).removeClass('d-none');
                }
            },
        });
    }
</script>
