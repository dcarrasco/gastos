@if ($cards->count())
<!-- ---------- Cards Container ---------- -->
<div class="col-md-12">
    <div class="mt-2 row">
    @foreach($cards as $card)
        {!! $card !!}
    @endforeach
    </div>
</div>
<!-- ---------- End Cards Container ---------- -->
@endif
