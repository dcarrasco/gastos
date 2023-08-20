@props([
    'class' => '',
    'width' => 20,
    'height' => 20,
])

<svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
    width="{{ $width ?? 20 }}"
    height="{{ $height ?? 20 }}"
    class="{{ $class ?? '' }}"
    fill="none"
    stroke-width="1.5"
    stroke="currentColor"
>
    <path class="heroicon-ui" stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>

