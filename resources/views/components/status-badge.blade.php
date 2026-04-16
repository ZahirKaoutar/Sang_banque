@props(['status'])

@php
$statusMap = [
    'accepter' => ['bg' => 'bg-green-100', 'text' => 'text-green-900', 'label' => 'Accepté'],
    'refuser' => ['bg' => 'bg-red-100', 'text' => 'text-red-deep', 'label' => 'Refusé'],
    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-900', 'label' => 'En attente'],
    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-deep', 'label' => 'Urgent'],
    'partial' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-900', 'label' => 'Partiel'],
    'fulfilled' => ['bg' => 'bg-green-100', 'text' => 'text-green-900', 'label' => 'Complété'],
    'Fulfilled' => ['bg' => 'bg-green-100', 'text' => 'text-green-900', 'label' => 'Complété'],
    'Canceled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-900', 'label' => 'Annulé'],
];

$config = $statusMap[strtolower($status)] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-900', 'label' => $status];
@endphp

<span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }}">
    {{ $config['label'] }}
</span>
