<div class="card {{ $cardWidth }} px-0">
    <div class="card-body px-1 py-2">
        <div class="row px-3">
            <div class="col-md-8">
                <div id="spinner-{{ $cardId }}" class="spinner-border spinner-border-sm d-none" role="status">
                </div>
                <strong>{{ $title }}</strong>
            </div>

            <div class="col-md-4">
                {{ Form::select('range', $ranges, request('range'), ['class' => 'custom-select custom-select-sm', 'onchange' => "loadCardData('$uriKey', '$cardId')", 'id' => 'select-'.$cardId]) }}
            </div>
        </div>

        <div id="{{ $cardId }}" class="row mx-0" style="height: 100px">
            {!! $content !!}
        </div>
    </div>

    {!! $contentScript !!}
</div>

