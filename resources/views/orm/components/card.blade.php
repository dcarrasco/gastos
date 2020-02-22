<div class="{{ $cardWidth }} px-2 my-2">
<div class="card px-0 rounded-lg shadow-sm" style="height: 160px;">
    <div class="card-body px-1 pt-2 pb-1">
        <div class="row px-3 pb-3">
            <div class="{{ count($ranges) ? 'col-6' : 'col-12' }}">
                <strong>{{ $title }}</strong>
            </div>

            @if(count($ranges))
            <div class="col-6">
                {{ Form::select('range', $ranges, request('range'), ['class' => 'custom-select custom-select-sm', 'onchange' => "loadCardData_{$cardId}('$uriKey', '$cardId')", 'id' => 'select-'.$cardId]) }}
            </div>
            @endif
        </div>

        <div id="spinner-{{ $cardId }}" class="row mx-4 d-none" style="height: 120px">
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
            <div class="spinner-grow text-secondary mx-1 my-4" role="status"></div>
        </div>

        {{ $content }}

    </div>

    {{ $contentScript }}
</div>
</div>

