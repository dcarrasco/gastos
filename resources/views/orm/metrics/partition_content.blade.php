<div id="{{ $cardId }}" class="row mx-2" style="height: 100px">
    <div class="col-6 px-0 overflow-auto h-100" id="legend-{{ $cardId }}">
    </div>

    <div class="col-6 d-inline-block h-100 px-0">
        <canvas id="canvas-{{$cardId}}"></canvas>
    </div>
</div>

{{ $script }}
