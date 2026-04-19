@extends('layouts.app')

@section('title', 'Centres - HemoLife')

@section('content')
@php
    $userCity = auth()->user()->city;
    $cities = $centres->pluck('city')->unique()->sort()->values();
@endphp

<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Centres de transfusion</h1>
            <p class="text-gray-600">Trouvez un centre proche de vous</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
            <input id="search-input" type="text" placeholder="Rechercher par nom, ville ou adresse..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-mid focus:border-transparent" />
        </div>

        <!-- City Filter Buttons -->
        <div class="flex flex-wrap gap-2">
            <button data-city=""
                    class="city-btn bg-red-mid text-white px-4 py-2 rounded-full text-sm font-bold transition">
                Toutes les villes
            </button>
            @foreach($cities as $city)
                <button data-city="{{ $city }}"
                        class="city-btn bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-bold transition">
                    {{ $city }}
                    @if($city === $userCity)
                        <span class="ml-1">📍</span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="js-hidden bg-white border border-gray-100 rounded-3xl p-8">
            @include('components.empty-state', ['icon' => '🏥', 'title' => 'Aucun centre trouvé', 'subtitle' => 'Essayez une autre recherche'])
        </div>

        <!-- Centres Grid -->
        <div id="centres-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Populated by JS -->
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const allCentres = @json($centres);
        const userCity = @json($userCity);
        
        const searchInput = document.getElementById('search-input');
        const cityBtns = document.querySelectorAll('.city-btn');
        const gridContainer = document.getElementById('centres-grid');
        const emptyState = document.getElementById('empty-state');
        
        let currentSearch = '';
        let currentCity = '';

        function renderCentres() {
            let result = allCentres;
            if (currentSearch) {
                const q = currentSearch.toLowerCase();
                result = result.filter(c => 
                    c.name.toLowerCase().includes(q) ||
                    c.city.toLowerCase().includes(q) ||
                    (c.adress && c.adress.toLowerCase().includes(q))
                );
            }
            if (currentCity) {
                result = result.filter(c => c.city === currentCity);
            }

            if (result.length === 0) {
                emptyState.classList.remove('js-hidden');
                gridContainer.classList.add('js-hidden');
            } else {
                emptyState.classList.add('js-hidden');
                gridContainer.classList.remove('js-hidden');
                
                gridContainer.innerHTML = '';
                result.forEach(centre => {
                    const card = document.createElement('div');
                    card.className = 'bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition';
                    
                    let cityBadge = '';
                    if (centre.city === userCity) {
                        cityBadge = `<span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Votre ville</span>`;
                    }
                    
                    card.innerHTML = `
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-bold text-dark">${escapeHtml(centre.name)}</h3>
                            ${cityBadge}
                        </div>
                        <p class="text-sm text-gray-600 mb-2">📍 <span>${escapeHtml(centre.city)}</span></p>
                        <p class="text-sm text-gray-600 mb-2">📬 <span>${escapeHtml(centre.adress || '')}</span></p>
                        <p class="text-sm text-gray-500 mb-4">🏷️ <span>${escapeHtml(centre.liscence_number || '')}</span></p>
                        <button class="w-full px-4 py-2 bg-red-mid text-white rounded-xl hover:bg-red-deep transition font-semibold">
                            Contacter
                        </button>
                    `;
                    gridContainer.appendChild(card);
                });
            }
        }

        function escapeHtml(unsafe) {
            if(!unsafe) return '';
            return unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }

        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value;
            renderCentres();
        });

        cityBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const city = e.currentTarget.getAttribute('data-city');
                currentCity = city;
                
                cityBtns.forEach(b => {
                    b.classList.remove('bg-red-mid', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                
                e.currentTarget.classList.remove('bg-gray-100', 'text-gray-700');
                e.currentTarget.classList.add('bg-red-mid', 'text-white');
                
                renderCentres();
            });
        });

        renderCentres();
    });
</script>
@endsection
