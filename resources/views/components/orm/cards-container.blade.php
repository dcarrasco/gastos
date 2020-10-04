@if ($cards->count())
    <div class="grid grid-cols-12 pt-2 mb-4">
        @foreach($cards as $card)
            {{ $card }}
        @endforeach
    </div>
@endif
