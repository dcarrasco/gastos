@php
    $styles = [
        'up' => 'transform: rotate(180deg); fill: #38c172;',
        'down' => 'transform: scaleX(-1); fill: #e3342f;',
        'none' => 'display: none;',
    ];
@endphp
<svg class="text-success fill-current mr-2" style="{{ $styles[$trend] ?? '' }}" width="20" height="12"><path d="M2 3a1 1 0 0 0-2 0v8a1 1 0 0 0 1 1h8a1 1 0 0 0 0-2H3.414L9 4.414l3.293 3.293a1 1 0 0 0 1.414 0l6-6A1 1 0 0 0 18.293.293L13 5.586 9.707 2.293a1 1 0 0 0-1.414 0L2 8.586V3z"/></svg>
