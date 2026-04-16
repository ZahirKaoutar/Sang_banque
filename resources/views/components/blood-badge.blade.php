@props(['bloodGroup'])

@php
$colors = [
    'A+' => 'bg-red-100 text-red-deep',
    'A-' => 'bg-pink-100 text-pink-900',
    'B+' => 'bg-orange-100 text-orange-900',
    'B-' => 'bg-amber-100 text-amber-900',
    'AB+' => 'bg-purple-100 text-purple-900',
    'AB-' => 'bg-indigo-100 text-indigo-900',
    'O+' => 'bg-green-100 text-green-900',
    'O-' => 'bg-teal-100 text-teal-900',
];

$color = $colors[$bloodGroup] ?? 'bg-gray-100 text-gray-900';
@endphp

<span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
    {{ $bloodGroup }}
</span>
