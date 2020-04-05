@if ($cards->count())
    <div class="row pt-2 mb-4">
        @foreach($cards as $card)
            {{ $card }}
        @endforeach
    </div>
@endif
