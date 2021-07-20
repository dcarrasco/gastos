<div class="px-4">
    <h1 class="text-4xl py-2">{{ $currentValue }}</h1>

    <div class="flex items-center font-bold text-gray-600">
        <h5>
            <div id="icon" class="inline-block">
                <x-heroicon.trend :trend="$trendIconStyle"/>
            </div>
            {{ $previousValue }}
        </h5>
    </div>
</div>

{{ $script }}
