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
                defaultClass="border border-gray-400 shadow-sm rounded-md px-1 outline-none focus:shadow-outline"
                class="text-sm bg-gray-200"
                onchange="loadCardData_{{ $card->cardId() }}('{{ $card->uriKey() }}', '{{ $card->cardId() }}')"
            />
        @endif
    </div>

    <div id="spinner-{{ $card->cardId() }}" class="row mx-4 flex justify-center hidden py-4" style="height: 120px">
        <x-heroicon.loading width="48" height="48" />
    </div>

    <div id="content-{{ $card->cardId() }}">
        {{ $card->content($request) }}
    </div>

    {{ $card->contentScript($request) }}
</div>
</div>

<script type="text/javascript">
    function loadCardData_{{ $card->cardId() }}(uriKey, cardId) {
        $('#content-' + cardId).addClass('hidden');
        $('#spinner-' + cardId).removeClass('hidden');
        $.ajax({
            url: '{{ $urlRoute }}',
            data: {
                ...{'range': $('#select-' + cardId + ' option:selected').val(), 'uri-key': uriKey},
                ...{{ $resourceParams }}
            },
            async: true,
            success: function(data) {
                if (data) {
                    $('#spinner-' + cardId).addClass('hidden');
                    $('#content-' + cardId).html(data).removeClass('hidden');
                }
            },
        });
    }
</script>

