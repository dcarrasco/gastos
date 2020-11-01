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
    $value = old($name, request($name, $value));
@endphp
@if ($type == 'selectYear')
    @php
        $type = 'select';
        $options = array_combine(range($fromYear, $toYear), range($fromYear, $toYear));
    @endphp
@elseif ($type == 'selectMonth')
    @php
        $type = 'select';
        $options = collect(range(1,12))->mapWithKeys(function($mes) { return [$mes => now()->create(2020, $mes, 01)->formatLocalized('%B')];});
    @endphp
@endif
@if($type == 'select')
    @php
        $value = is_array($value) ? $value : (empty($value) ? [] : [$value]);
    @endphp
    <select
        name="{{ $name }}"
        class="{{ $defaultClass }} {{ $class }} @error($name) border-red-400 @enderror"
        {{ $multiple == 'multiple' ? 'multiple=multiple' : '' }}
        {{ empty($size) ? '' : "size={$size}" }}
        {{ $attributes }}
    >
    @if(!empty($placeholder))
        <option value="" disabled="disabled" {{ empty($value) ? 'selected' : '' }}>{!! $placeholder !!}</option>
    @endif
    @foreach ($options as $optionValue => $optionText)
        @if(is_array($optionText))
            <optgroup label="{{ $optionValue }}">
            @foreach ($optionText as $displayValue => $displayText)
                <option value="{{ $displayValue }}" {{ in_array($displayValue, $value) ? 'selected' : '' }}>{{ $displayText }}</option>
            @endforeach
            </optgroup>
        @else
            <option value="{{ $optionValue }}" {{ in_array($optionValue, $value) ? 'selected' : '' }}>{{ $optionText }}</option>
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
