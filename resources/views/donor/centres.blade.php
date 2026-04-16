@extends('layouts.app')

@section('title', 'Centres - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ search: '' }">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Centres de transfusion</h1>
            <p class="text-gray-600">Trouvez un centre proche de vous</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input x-model="search" type="text" placeholder="Rechercher par nom, ville ou adresse..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid focus:border-transparent" />
        </div>

        <!-- Empty State -->
        <div x-show="filteredCentres.length === 0" class="bg-white border border-gray-100 rounded-3xl p-8">
            @include('components.empty-state', ['icon' => '🏥', 'title' => 'Aucun centre trouvé', 'subtitle' => 'Essayez une autre recherche'])
        </div>

        <!-- Centres Grid -->
        <template x-if="filteredCentres.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="centre in filteredCentres" :key="centre.id">
                    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition">
                        <h3 class="text-lg font-bold text-dark mb-2" x-text="centre.name"></h3>
                        <p class="text-sm text-gray-600 mb-4">📍 <span x-text="centre.city"></span></p>
                        <p class="text-sm text-gray-600 mb-4">📬 <span x-text="centre.adress"></span></p>
                        <button class="w-full px-4 py-2 bg-red-mid text-white rounded-xl hover:bg-red-deep transition">
                            Contacter
                        </button>
                    </div>
                </template>
            </div>
        </template>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('centres', () => ({
                    search: '',
                    allCentres: @json($centres),
                    get filteredCentres() {
                        const query = this.search.toLowerCase();
                        return this.allCentres.filter(c =>
                            c.name.toLowerCase().includes(query) ||
                            c.city.toLowerCase().includes(query) ||
                            (c.adress && c.adress.toLowerCase().includes(query))
                        );
                    }
                }));
            });
        </script>
    </div>
</div>
@endsection
