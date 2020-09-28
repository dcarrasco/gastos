@props([
    'type' => 'text',
    'name' => 'componentName',
    'class' => '',
    'options' => [],
    'default' => null,
])
@if ($type == 'select')
    {{ Form::select($name, $options, request($name, $default), ['class' => 'border rounded-md p-2 ' . ($class ?? '')]) }}
@elseif ($type == 'selectYear')
    {{ Form::selectYear($name, $fromYear ?? 0, $toYear ?? 0, request($name, $default), ['class' => 'border rounded-md p-2 ' . ($class ?? '')]) }}
@elseif ($type == 'selectMonth')
    {{ Form::selectMonth($name, request($name, $default), ['class' => 'border rounded-md p-2 ' . ($class ?? '')]) }}
@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $default ?? null) }}"
        autocomplete="off"
        class="border rounded-md p-2 @error($name) is-invalid @enderror {{ $class ?? '' }}"
    >
@endif
