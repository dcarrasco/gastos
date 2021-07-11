@props([
    'type' => 'text',
    'name' => 'componentName',
    'value' => '',
    'class' => '',
    'options' => [],
    'placeholder' => '',
    'multiple' => '',
    'size' => 0,
    'cols' => 0,
    'rows' => 0,
    'maxlength' => '',
    'defaultClass' => 'border border-gray-400 shadow-sm rounded-md px-3 py-1 outline-none focus:shadow-outline',
    'fromYear' => today()->year,
    'toYear' => 2015,
])
@php
    $value = old($name, request(str_replace(' ', '_', $name), $value));

    if ($type == 'selectYear') {
        $type = 'select';
        $options = collect(range($fromYear, $toYear))->combine(range($fromYear, $toYear));
        $value = empty($value) ? today()->year : $value;
    }
    else if ($type == 'selectMonth') {
        $type = 'select';
        $options = collect(range(1,12))->mapWithKeys(fn($mes) => [
            $mes => trans('fechas.' . now()->create(2020, $mes, 01)->formatLocalized('%B'))
        ]);
        $value = empty($value) ? today()->month : $value;
    }

    if ($type == 'select') {
        $value = is_array($value) ? $value : (empty($value) ? [] : [$value]);

        if (! is_array(collect($options)->first())) {
            $options = collect(['' => collect($options)->all()]);
        }
    }
@endphp

@if ($type == 'select')
    <select
        name="{{ $name }}"
        class="{{ $defaultClass }} {{ $class }} @error($name) border-red-400 @enderror"
        {{ $multiple == 'multiple' ? 'multiple=multiple' : '' }}
        {{ empty($size) ? '' : "size={$size}" }}
        {{ $attributes }}
    >

    @if (!empty($placeholder))
        <option value="" disabled="disabled" {{ empty($value) ? 'selected' : '' }}>
            {!! $placeholder !!}
        </option>
    @endif

    @foreach ($options as $groupName => $groupOptions)
        @if (!empty($groupName))
            <optgroup label="{{ $groupName }}">
        @endif

        @foreach ($groupOptions as $optionValue => $optionText)
            <option value="{{ $optionValue }}" {{ in_array($optionValue, $value) ? 'selected' : '' }}>
                {{ $optionText }}
            </option>
        @endforeach

        @if (!empty($groupName))
            </optgroup>
        @endif
    @endforeach
    </select>

@elseif($type == 'textarea')
    <textarea
        name="{{ $name }}"
        cols="{{ $cols }}"
        rows="{{ $rows }}"
        class="{{ $defaultClass }} {{ $class }} @error($name) border-red-400 @enderror"
        {{ empty($maxlength) ? '' : "maxlength={$maxlength}"}}
        {{ $attributes }}
    >{{ $value }}</textarea>

@else
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        class="{{ $defaultClass }} {{ $class }} @error($name) border-red-400 @enderror"
        placeholder="{{ $placeholder }}"
        {{ empty($maxlength) ? '' : "maxlength={$maxlength}"}}
        {{ $attributes }}
    >
@endif
