<div id="{{ $cardId }}" class="mx-2 grid grid-cols-2" style="height: 110px">
    <div class="px-0 overflow-auto" id="legend-{{ $cardId }}">
    </div>

    <div class="inline-block px-0">
        <canvas id="canvas-{{$cardId}}"></canvas>
    </div>
</div>

{{ $script }}
