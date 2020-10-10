@if ($paginator->hasPages())
    <ul class="flex h-full content-center" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="border py-2 w-8 text-center" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="text-gray-500 font-bold" aria-hidden="true">&lt;</span>
            </li>
        @else
            <li class="border py-2 w-8 text-center">
                <a class="text-blue-500 font-bold" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lt;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="border py-2 w-8 text-center" aria-disabled="true"><span class="text-gray-500 font-bold">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="border py-2 w-8 text-center" aria-current="page"><span class="text-gray-500 font-bold">{{ $page }}</span></li>
                    @else
                        <li class="border py-2 w-8 text-center"><a class="text-blue-500 font-bold" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="border py-2 w-8 text-center">
                <a class="text-blue-500 font-bold" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&gt;</a>
            </li>
        @else
            <li class="border py-2 w-8 text-center" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="text-blue-500 font-bold" aria-hidden="true">&gt;</span>
            </li>
        @endif
    </ul>
@endif
