@if (($type ?? 'text') == 'select')
    {{ Form::select($name, $options ?? [], request($name, $default ?? null), ['class' => 'form-control ' . ($class ?? '')]) }}
@elseif (($type ?? 'text') == 'selectYear')
    {{ Form::selectYear($name, $fromYear ?? 0, $toYear ?? 0, request($name, $default ?? null), ['class' => 'form-control ' . ($class ?? '')]) }}
@elseif (($type ?? 'text') == 'selectMonth')
    {{ Form::selectMonth($name, request($name, $default ?? null), ['class' => 'form-control ' . ($class ?? '')]) }}
@else
    <input
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        value="{{ old($name, $default ?? null) }}"
        autocomplete="off"
        class="form-control @error($name) is-invalid @enderror {{ $class ?? '' }}"
    >
@endif
