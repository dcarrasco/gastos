@if (($type ?? 'text') == 'select')
    {{ Form::select($name, $options ?? [], request($name, $default ?? null), ['class' => 'custom-select ' . ($class ?? '')]) }}
@elseif (($type ?? 'text') == 'selectYear')
    {{ Form::selectYear($name, $fromYear ?? 0, $toYear ?? 0, request($name, $default ?? null), ['class' => 'custom-select ' . ($class ?? '')]) }}
@elseif (($type ?? 'text') == 'selectMonth')
    {{ Form::selectMonth($name, request($name, $default ?? null), ['class' => 'custom-select ' . ($class ?? '')]) }}
@else
    <input
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        value="{{ old($name, $default ?? null) }}"
        autocomplete="off"
        class="border @error($name) is-invalid @enderror {{ $class ?? '' }}"
    >
@endif
