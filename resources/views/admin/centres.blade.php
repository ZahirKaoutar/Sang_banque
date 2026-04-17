@extends('layouts.app')

@section('title', 'Admin Centres - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-dark">Centres de Transfusion</h1>
                <p class="text-gray-600 mt-2">Gestion des centres de collecte</p>
            </div>
            <a href="{{ route('admin.create-centre') }}" class="px-6 py-3 bg-red-mid text-white rounded-xl font-bold hover:bg-red-deep transition flex items-center gap-2 justify-center md:justify-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un Centre
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-2xl px-6 py-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input id="searchInput" type="text" placeholder="Rechercher par nom, ville ou adresse..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid" />
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                <p class="text-gray-400 text-xs uppercase font-bold">Total</p>
                <p class="text-3xl font-black text-dark mt-2">{{ $totalCentres }}</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                <p class="text-gray-400 text-xs uppercase font-bold">Actifs</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                <p class="text-gray-400 text-xs uppercase font-bold">Résultats</p>
                <p id="resultsCount" class="text-3xl font-black text-blue-600 mt-2">{{ $totalCentres }}</p>
            </div>
        </div>

        <!-- Grid -->
        @if($centres->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center text-gray-400">
                <p class="text-5xl mb-4">🏥</p>
                <p class="font-bold text-lg">Aucun centre</p>
                <p class="text-sm">Créez votre premier centre de transfusion</p>
            </div>
        @else
            <div id="centresGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($centres as $centre)
                    <div class="centre-card bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition flex flex-col gap-4"
                         data-name="{{ strtolower($centre->name) }}"
                         data-city="{{ strtolower($centre->city) }}"
                         data-adress="{{ strtolower($centre->adress) }}">

                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-dark">{{ $centre->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">📍 {{ $centre->city }}</p>
                            </div>
                            <form action="{{ route('admin.delete-centre', $centre->id) }}" method="POST"
                                onsubmit="return confirm('Supprimer {{ addslashes($centre->name) }} ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-deep hover:bg-red-pale rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="space-y-1 text-sm text-gray-600">
                            <p>📬 {{ $centre->adress }}</p>
                            <p>🪪 {{ $centre->liscence_number }}</p>
                        </div>

                        <a href="{{ route('admin.show-centre', $centre->id) }}"
                            class="mt-auto px-4 py-2 text-center bg-red-pale text-red-mid rounded-lg hover:bg-red-mid hover:text-white transition font-bold text-sm">
                            Voir Détails
                        </a>
                    </div>
                @endforeach
            </div>

            <p id="emptySearch" class="hidden text-center text-gray-400 py-8">🔍 Aucun centre trouvé</p>
        @endif
    </div>
</div>

<script>
    const input = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.centre-card');
    const count = document.getElementById('resultsCount');
    const emptyMsg = document.getElementById('emptySearch');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const matches = card.dataset.name.includes(q) ||
                            card.dataset.city.includes(q) ||
                            card.dataset.adress.includes(q);
            card.style.display = matches ? '' : 'none';
            if (matches) visible++;
        });

        count.textContent = visible;
        if (emptyMsg) emptyMsg.classList.toggle('hidden', visible > 0);
    });
</script>
@endsection
