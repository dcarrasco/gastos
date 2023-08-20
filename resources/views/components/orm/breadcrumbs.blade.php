@props(['resource' => null, 'accion' => null])

@if (config('invfija.use_breadcrumbs'))
    @php $breadcrumbs = auth()->user()->getBreadcrumbs($resource, $accion); @endphp
    <div class="pt-0 mb-4">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->first)
                <x-heroicon.chevron-right class="inline-block pb-1 text-gray-500"/>
            @endif

            @if (empty($breadcrumb['url']))
                <span class="font-bold text-gray-600">{{ $breadcrumb['texto'] }}</span>
            @else
                <a
                    href="{{ $breadcrumb['url'] }}"
                    class="font-bold {{ themeColor('link_primary') }} hover:{{ themeColor('link_primary_hover') }}"
                >
                    {{ $breadcrumb['texto'] }}
                </a>
            @endif
        @endforeach
    </div>
@endif
