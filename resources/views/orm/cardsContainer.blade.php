@if ($cards->count())
    <!-- ---------- Cards Container ---------- -->
    <div class="row pt-4">
    @foreach($cards as $card)
        {!! $card !!}
    @endforeach
    </div>
    <!-- ---------- End Cards Container ---------- -->
@endif
