<div class="bg-white rounded-t-lg py-3 px-3 flex justify-end">
    <div x-data="{openFilter: false}" class="relative">
        <button @click="openFilter=true" id="button-filters" class="flex p-2 border rounded-md bg-gray-100 outline-none focus:shadow-outline">
            <x-heroicon.filter />
            <span class="px-1 fa fa-icon fa-caret-down"></span>
            {{ $resource->countAppliedFilters(request()) ?: '' }}
        </button>

         <div x-show="openFilter" @click.away="openFilter=false" class="absolute right-0 bg-white border rounded-md shadow" aria-labelledby="button-filters" style="min-width: 20em;">
            @foreach(array_merge([$perPageFilter], $resource->filters(request())) as $filter)
                <div class="bg-gray-200 py-2 px-2 uppercase text-sm font-bold" href="#">
                    {{ $filter->getLabel() }}
                </div>

                @foreach($filter->options() as $option => $value)
                    <a class="block py-2 px-5" href="{{ $filter->getOptionUrl(request(), $value) }}">
                        <span class="{{ $filter->isActive(request(), $value) ? 'fa fa-check' : '' }}"></span>
                        {{ $option }}
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
