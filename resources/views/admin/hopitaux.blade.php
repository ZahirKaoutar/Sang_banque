@extends('layouts.app')

@section('title', 'Admin Hôpitaux - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ search: '' }">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-dark">Hôpitaux</h1>
                <p class="text-gray-600 mt-2">Gestion des hôpitaux</p>
            </div>
            <a href="{{ route('admin.create-hopital') }}" class="px-6 py-3 bg-red-mid text-white rounded-xl font-bold hover:bg-red-deep transition flex items-center gap-2 justify-center md:justify-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Créer un Hôpital
            </a>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input x-model="search" type="text" placeholder="Rechercher par nom, ville ou adresse..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid focus:border-transparent" />
        </div>

        <!-- Stats -->
        @if($hopitaux->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                    <p class="text-gray-400 text-xs uppercase font-bold">Total des hôpitaux</p>
                    <p class="text-3xl font-black text-dark mt-2">{{ $totalHopitaux }}</p>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                    <p class="text-gray-400 text-xs uppercase font-bold">Actifs</p>
                    <p class="text-3xl font-black text-green-600 mt-2">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center">
                    <p class="text-gray-400 text-xs uppercase font-bold">Résultats recherche</p>
                    <p class="text-3xl font-black text-blue-600 mt-2" x-text="filteredHopitaux.length">0</p>
                </div>
            </div>
        @endif

        <!-- Empty State -->
        <template x-if="filteredHopitaux.length === 0 && search !== ''">
            <div class="bg-white border border-gray-100 rounded-3xl p-8">
                @include('components.empty-state', ['icon' => '🔍', 'title' => 'Aucun hôpital trouvé', 'subtitle' => 'Essayez une autre recherche'])
            </div>
        </template>

        <!-- Hopitaux Grid -->
        <template x-if="filteredHopitaux.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="hopital in filteredHopitaux" :key="hopital.id">
                    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-dark" x-text="hopital.name"></h3>
                                <p class="text-sm text-gray-500 mt-1">📍 <span x-text="hopital.city"></span></p>
                            </div>
                            <form :action="`/admin/hopitaux/${hopital.id}`" method="POST" onsubmit="return confirm('Êtes-vous sûr?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-deep hover:bg-red-pale rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Details -->
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p>📬 <span x-text="hopital.adress"></span></p>
                            <p>🪪 <span x-text="hopital.liscence_number"></span></p>
                        </div>

                        <a :href="`/profile/${hopital.user_id}`" class="w-full px-4 py-2 text-center bg-red-pale text-red-mid rounded-lg hover:bg-red-light hover:text-white transition font-bold">
                            Voir Agent
                        </a>
                    </div>
                </template>
            </div>
        </template>

        @if($hopitaux->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-8">
                @include('components.empty-state', ['icon' => '🏥', 'title' => 'Aucun hôpital', 'subtitle' => 'Créez votre premier hôpital'])
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminHopitaux', () => ({
                search: '',
                allHopitaux: @json($hopitaux),
                get filteredHopitaux() {
                    const query = this.search.toLowerCase();
                    return this.allHopitaux.filter(h =>
                        h.name.toLowerCase().includes(query) ||
                        h.city.toLowerCase().includes(query) ||
                        (h.adress && h.adress.toLowerCase().includes(query))
                    );
                }
            }));
        });
    </script>
</div>
@endsection
