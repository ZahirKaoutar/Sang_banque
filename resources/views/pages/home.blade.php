@extends('layouts.app')

@section('title', 'Accueil - HemoLife')

@section('content')
<!-- Hero Section -->
<section class="bg-red-gradient py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-5xl md:text-6xl font-black text-dark mb-4">
            Sauvez des vies en donnant du sang
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            HemoLife facilite le don de sang et relie les donneurs, les centres de transfusion et les hôpitaux pour sauver des vies.
        </p>

        @guest
            <div class="flex gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl text-white font-bold shadow-lg transition-all transform hover:-translate-y-1" style="background: #C0392B;">
                    Commencer
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl text-red-mid border-2 border-red-mid font-bold hover:bg-red-pale transition-all">
                    Connexion
                </a>
            </div>
        @endguest
    </div>
</section>

<!-- Blood Type Compatibility Selector (Alpine.js) -->
<section class="py-20" x-data="{ selectedType: 'O+' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-4xl font-bold text-dark mb-12 text-center">
            Compatibilité des groupes sanguins
        </h2>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Blood Type Selector -->
            <div>
                <h3 class="text-lg font-bold text-dark mb-4">Sélectionnez votre groupe sanguin</h3>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                        <button @click="selectedType = '{{ $group }}'" :class="selectedType === '{{ $group }}' ? 'bg-red-mid text-white' : 'bg-gray-100 text-dark hover:bg-gray-200'" class="p-4 rounded-2xl font-bold transition-all">
                            {{ $group }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Compatibility Info -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <div x-show="selectedType === 'O+'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">O+ (Donneur universel)</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">Tous les groupes sanguins (O+, O-, A+, A-, B+, B-, AB+, AB-)</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O+ et O-</p>
                    </div>
                </div>

                <div x-show="selectedType === 'O-'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">O- (Donneur universel d'urgence)</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">Tous les groupes sanguins</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O- uniquement</p>
                    </div>
                </div>

                <div x-show="selectedType === 'A+'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">A+</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">A+, A-, AB+, AB-</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O+, O-, A+, A-</p>
                    </div>
                </div>

                <div x-show="selectedType === 'A-'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">A-</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">A+, A-, AB+, AB-</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O-, A-</p>
                    </div>
                </div>

                <div x-show="selectedType === 'B+'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">B+</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">B+, B-, AB+, AB-</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O+, O-, B+, B-</p>
                    </div>
                </div>

                <div x-show="selectedType === 'B-'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">B-</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">B+, B-, AB+, AB-</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O-, B-</p>
                    </div>
                </div>

                <div x-show="selectedType === 'AB+'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">AB+ (Receveur universel)</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">AB+ uniquement</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">Tous les groupes sanguins</p>
                    </div>
                </div>

                <div x-show="selectedType === 'AB-'" class="space-y-4">
                    <h4 class="text-2xl font-bold text-red-mid">AB-</h4>
                    <div>
                        <p class="font-bold text-dark mb-2">✅ Peut donner à:</p>
                        <p class="text-gray-600">AB+, AB-</p>
                    </div>
                    <div>
                        <p class="font-bold text-dark mb-2">🩸 Peut recevoir de:</p>
                        <p class="text-gray-600">O-, A-, B-, AB-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Donate Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-4xl font-bold text-dark mb-12 text-center">
            Pourquoi donner du sang ?
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-pale rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl">❤️</span>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Sauver des vies</h3>
                <p class="text-gray-600">Un don de sang peut sauver jusqu'à 3 vies. Votre geste généeux peut faire toute la différence</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-pale rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl">✅</span>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Santé personnelle</h3>
                <p class="text-gray-600">Le don de sang aide à maintenir une bonne circulation sanguine et réduit les risques de maladies cardiovasculaires</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-pale rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl">🤝</span>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">Communauté</h3>
                <p class="text-gray-600">Rejoignez une communauté de donneurs altruistes et contribuez à un système de santé plus fort</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-4xl font-bold text-dark mb-12 text-center">
            Comment ça marche
        </h2>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-pale flex items-center justify-center mx-auto mb-4 font-bold text-red-mid text-2xl">01</div>
                <h3 class="text-lg font-bold text-dark mb-2">Inscription</h3>
                <p class="text-gray-600 text-sm">Créez votre compte et indiquez votre groupe sanguin</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-pale flex items-center justify-center mx-auto mb-4 font-bold text-red-mid text-2xl">02</div>
                <h3 class="text-lg font-bold text-dark mb-2">Localiser</h3>
                <p class="text-gray-600 text-sm">Trouvez le centre de transfusion le plus proche</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-pale flex items-center justify-center mx-auto mb-4 font-bold text-red-mid text-2xl">03</div>
                <h3 class="text-lg font-bold text-dark mb-2">Donner</h3>
                <p class="text-gray-600 text-sm">Donnez 450ml de sang en toute sécurité</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-pale flex items-center justify-center mx-auto mb-4 font-bold text-red-mid text-2xl">04</div>
                <h3 class="text-lg font-bold text-dark mb-2">Recevoir</h3>
                <p class="text-gray-600 text-sm">Recevez une notification et des récompenses</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
@guest
<section class="py-20 bg-dark">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl font-bold text-white mb-4">
            Prêt à sauver des vies ?
        </h2>
        <p class="text-lg text-gray-300 mb-8">
            Rejoignez notre communauté de donneurs et faites la différence
        </p>
        <a href="{{ route('register') }}" class="inline-block px-8 py-4 rounded-2xl text-white font-bold shadow-lg transition-all transform hover:-translate-y-1" style="background: #C0392B;">
            S'inscrire maintenant
        </a>
    </div>
</section>
@endguest

@endsection
