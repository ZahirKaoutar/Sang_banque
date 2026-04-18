@extends('layouts.app')

@section('title', 'Centres - HemoLife')

@section('content')
@php
    $userCity = auth()->user()->city;
    $cities = $centres->pluck('city')->unique()->sort()->values();
@endphp

<div class="py-12 px-4 sm:px-6 lg:px-8"
     x-data="{
         search: '',
         selectedCity: '',
         allCentres: @json($centres),
         get filteredCentres() {
             let result = this.allCentres;

             // Filter by search query
             if (this.search) {
                 const q = this.search.toLowerCase();
                 result = result.filter(c =>
                     c.name.toLowerCase().includes(q) ||
                     c.city.toLowerCase().includes(q) ||
                     (c.adress && c.adress.toLowerCase().includes(q))
                 );
             }

             // Filter by selected city
             if (this.selectedCity) {
                 result = result.filter(c => c.city === this.selectedCity);
             }

             return result;
         }
     }">
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

        <!-- City Filter Buttons -->
        <div class="flex flex-wrap gap-2">
            <button @click="selectedCity = ''"
                    :class="selectedCity === '' ? 'bg-red-mid text-white' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-full text-sm font-bold transition">
                Toutes les villes
            </button>
            @foreach($cities as $city)
                <button @click="selectedCity = @json($city)"
                        :class="selectedCity === @json($city) ? 'bg-red-mid text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-4 py-2 rounded-full text-sm font-bold transition">
                    {{ $city }}
                    @if($city === $userCity)
                        <span class="ml-1">📍</span>
                    @endif
                </button>
            @endforeach
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
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-bold text-dark" x-text="centre.name"></h3>
                            <template x-if="centre.city === @json($userCity)">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                    Votre ville
                                </span>
                            </template>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📍 <span x-text="centre.city"></span></p>
                        <p class="text-sm text-gray-600 mb-2">📬 <span x-text="centre.adress"></span></p>
                        <p class="text-sm text-gray-500 mb-4">🏷️ <span x-text="centre.liscence_number"></span></p>
                        <button class="w-full px-4 py-2 bg-red-mid text-white rounded-xl hover:bg-red-deep transition font-semibold">
                            Contacter
                        </button>
                    </div>
                </template>
            </div>
        </template>

    </div>
</div>
@endsection
