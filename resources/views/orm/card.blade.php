<div class="{{ $card->bootstrapCardWidth() }} px-2 my-2">
<div class="bg-white rounded-lg shadow-sm" style="height: 160px;">
    <div class="flex justify-between px-3 py-3">
        <div class="font-bold text-gray-600">
            {{ $card->title() }}
        </div>

        @if(count($card->ranges()))
            <x-form-input
                type="select"
                name="range"
                value=""
                id="select-{{ $card->cardId() }}"
                :options="$card->ranges()"
                class="text-sm bg-gray-200 px-0 py-0"
                onchange="loadCardData_{{ $card->cardId() }}('{{ $card->uriKey() }}', '{{ $card->cardId() }}')"
            />
        @endif
    </div>

    <div id="spinner-{{ $card->cardId() }}" class="row mx-4 hidden" style="height: 120px">
        <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
    </div>

    <div id="content-{{ $card->cardId() }}">
        {{ $card->content($request) }}
    </div>

    {{ $card->contentScript($request) }}
</div>
</div>

<script type="text/javascript">
    function loadCardData_{{ $card->cardId() }}(uriKey, cardId) {
        $('#content-' + cardId).addClass('d-none');
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
                    $('#spinner-' + cardId).addClass('d-none');
                    $('#content-' + cardId).html(data).removeClass('d-none');
                }
            },
        });
    }
</script>

