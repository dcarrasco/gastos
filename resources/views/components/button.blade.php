@props([
    'type' => 'button',
    'color' => 'primary',
    'link' => '#',
    'class' => '',
    'colorClass' => [
        'primary' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'secondary' => 'bg-gray-300 hover:bg-gray-400 text-gray-700',
        'green' => 'bg-green-500 hover:bg-green-600 text-white',
        'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white',
    ],
])
@if ($type === 'link')
    <a
        href="{{ $link }}"
        class="{{ $colorClass[$color]}} font-bold px-4 py-2 rounded-md focus:outline-none focus:shadow-outline {{ $class }}"
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        class="{{ $colorClass[$color]}} font-bold px-4 py-2 rounded-md focus:outline-none focus:shadow-outline {{ $class }}"
        {{ $attributes }}
    >
        {{ $slot }}
    </button>
@endif
