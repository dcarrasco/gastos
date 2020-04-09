<div class="{{ $card->bootstrapCardWidth() }} px-2 my-2">
<div class="card px-0 rounded-lg shadow-sm" style="height: 160px;">
    <div class="card-body px-1 pt-2 pb-1">
        <div class="row px-3 pb-3">
            <div class="{{ count($card->ranges()) ? 'col-6' : 'col-12' }}">
                <strong>{{ $card->title() }}</strong>
            </div>

            @if(count($card->ranges()))
                <div class="col-6">
                    {{ Form::select('range', $card->ranges(), request('range'), ['class' => 'custom-select custom-select-sm', 'onchange' => 'loadCardData_'.$card->cardId()."('".$card->uriKey()."', '".$card->cardId()."')", 'id' => 'select-'.$card->cardId()]) }}
                </div>
            @endif
        </div>

        <div id="spinner-{{ $card->cardId() }}" class="row mx-4 d-none" style="height: 120px">
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        </div>

        {{ $card->content($request) }}

    </div>

    {{ $card->contentScript($request) }}
</div>
</div>

