@props([
    'class' => '',
    'width' => 24,
    'height' => 24,
])

<svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    width="{{ $width }}"
    height="{{ $height }}"
    class="{{ $class }}"
    fill="none"
    stroke-width="2.0"
    stroke="currentColor"
>
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
