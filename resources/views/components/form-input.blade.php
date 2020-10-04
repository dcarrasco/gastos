@props([
    'type' => 'text',
    'name' => 'componentName',
    'class' => '',
    'options' => [],
    'default' => null,
    'defaultClass' => 'border rounded-md px-3 py-1 outline-none focus:shadow-outline ',
])
@if ($type == 'select')
    {{ Form::select($name, $options, request($name, $default), ['class' => $defaultClass . ($class ?? '')]) }}
@elseif ($type == 'selectYear')
    {{ Form::selectYear($name, $fromYear ?? 0, $toYear ?? 0, request($name, $default), ['class' => $defaultClass . ($class ?? '')]) }}
@elseif ($type == 'selectMonth')
    {{ Form::selectMonth($name, request($name, $default), ['class' => $defaultClass . ($class ?? '')]) }}
@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $default ?? null) }}"
        autocomplete="off"
        class="{{ $defaultClass }} @error($name) is-invalid @enderror {{ $class ?? '' }}"
    >
@endif
