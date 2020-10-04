@props([
    'type' => 'text',
    'name' => 'componentName',
    'class' => '',
    'options' => [],
    'default' => null,
])
@if ($type == 'select')
    {{ Form::select($name, $options, request($name, $default), ['class' => 'border rounded-md px-3 py-1 outline-none focus:shadow-outline ' . ($class ?? '')]) }}
@elseif ($type == 'selectYear')
    {{ Form::selectYear($name, $fromYear ?? 0, $toYear ?? 0, request($name, $default), ['class' => 'border rounded-md px-3 py-1 outline-none focus:shadow-outline ' . ($class ?? '')]) }}
@elseif ($type == 'selectMonth')
    {{ Form::selectMonth($name, request($name, $default), ['class' => 'border rounded-md px-3 py-1 outline-none focus:shadow-outline ' . ($class ?? '')]) }}
@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $default ?? null) }}"
        autocomplete="off"
        class="border rounded-md px-3 py-1 outline-none focus:shadow-outline @error($name) is-invalid @enderror {{ $class ?? '' }}"
    >
@endif
