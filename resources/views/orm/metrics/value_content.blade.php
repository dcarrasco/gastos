<div class="row mx-2">
    <div class="col-md-12 pt-3">
        <h1 class="">{{ $currentValue }}</h1>
    </div>
    <div class="col-md-12">
        <h5 class="text-secondary">
            <span id="icon">
                <x-heroicon.trend :trend="$trendIconStyle"/>
            </span>
            <span id="text">
                {{ $previousValue }}
            </span>
        </h5>
    </div>
</div>

{{ $script }}
