<div class="{{ $card->bootstrapCardWidth() }} px-2 my-2">
<div
    class="bg-white rounded-lg shadow-sm"
    x-data="{
        isLoading: false,
        select: '',
        url: '{{ $urlRoute }}',
        uriKey: '{{ $card->uriKey() }}',
        cardId: '{{ $card->cardId() }}',
        submit() {
            this.isLoading=true;
            fetch(this.url+'?uri-key='+this.uriKey+'&range='+this.select)
                .then(response => response.json())
                .then(data => {
                    if (data.hasOwnProperty('eval')) {
                        eval(data.eval);
                    }
                    if (data.hasOwnProperty('html')) {
                        for (const ref in data.html) {
                            this.$refs[ref].innerHTML = data.html[ref];
                        }
                    }
                    this.isLoading=false;
                });
        }
    }"
    style="height: 160px;"
>
    <div class="flex justify-between px-3 py-3">
        <div class="font-bold text-gray-600">
            {{ $card->title() }}
        </div>

        @if(count($card->ranges()))
            <x-form-input
                type="select"
                name="range"
                value=""
                id="select-{{ $card->cardId() }}"
                :options="$card->ranges()"
                defaultClass="border border-gray-400 shadow-sm rounded-md px-1 outline-none focus:shadow-outline"
                class="text-sm bg-gray-200"
                __onchange="loadCardData_{{ $card->cardId() }}('{{ $card->uriKey() }}', '{{ $card->cardId() }}')"
                x-model="select"
                @change="submit"
            />
        @endif
    </div>

    <div
        class="row mx-4 flex justify-center py-4"
        :class="{ 'hidden': ! isLoading }"
        style="height: 120px"
    >
        <x-heroicon.loading width="48" height="48" />
    </div>

    <div
        x-ref="content"
        :class="{ 'hidden': isLoading }"
    >
        {{ $card->content($request) }}
    </div>
</div>
</div>

