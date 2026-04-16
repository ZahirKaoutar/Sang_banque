@props(['icon' => '📦', 'title' => 'Aucune donnée', 'subtitle' => 'Aucun élément à afficher'])

<div class="text-center py-12">
    <div class="text-6xl mb-4">{{ $icon }}</div>
    <h3 class="text-xl font-bold text-dark mb-2">{{ $title }}</h3>
    <p class="text-gray-500">{{ $subtitle }}</p>
</div>
