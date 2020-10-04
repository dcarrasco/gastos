@if ($errors->any())
    <div x-data="{open: true}" x-show="open" class="bg-red-200 mt-3 shadow-sm p-2 border border-red-300 rounded-lg text-red-700 text-sm" role="alert">
        <div class="flex justify-between items-center">
            <div class="">
                <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
                <strong>ERROR</strong>
            </div>
            <button type="button" class="font-bold" @click="open = false">
                <span class="fa fa-close"></span>
            </button>
        </div>

        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif
