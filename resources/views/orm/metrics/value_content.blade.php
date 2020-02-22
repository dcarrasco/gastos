<div id="{{ $cardId }}" class="row mx-2">
    <div class="col-md-12 pt-3">
        <h1 class="">{{ $currentValue }}</h1>
    </div>
    <div class="col-md-12">
        <h5 class="text-secondary">
            <span id="icon">
                <svg class="text-success fill-current mr-2" style="{{ $trendIconStyle }}" width="20" height="12"><path d="M2 3a1 1 0 0 0-2 0v8a1 1 0 0 0 1 1h8a1 1 0 0 0 0-2H3.414L9 4.414l3.293 3.293a1 1 0 0 0 1.414 0l6-6A1 1 0 0 0 18.293.293L13 5.586 9.707 2.293a1 1 0 0 0-1.414 0L2 8.586V3z"/></svg>
            </span>
            <span id="text">
                {{ $previousValue }}
            </span>
        </h5>
    </div>
</div>
