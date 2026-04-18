@extends('layouts.app')

@section('title', $user->name . ' - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Profile Header -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-start gap-6 mb-8">

                <!-- Avatar -->
                <div class="w-20 h-20 rounded-full bg-red-mid flex items-center justify-center shrink-0">
                    <span class="text-3xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                </div>

                <!-- Info principale -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <h1 class="text-3xl font-bold text-dark">{{ $user->name }}</h1>

                        @switch($user->role)
                            @case('Admin')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                    👑 Administrateur Système
                                </span>
                                @break
                            @case('AgentCentre')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    🏥 Agent Centre
                                </span>
                                @break
                            @case('AgentHopital')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                    🏨 Agent Hôpital
                                </span>
                                @break
                            @case('Donor')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    🩸 Donneur
                                </span>
                                @break
                        @endswitch

                        @if($user->is_banned)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-deep">Banni</span>
                        @endif
                    </div>

                    <p class="text-gray-500 text-sm">{{ $user->email }} • {{ $user->city }}</p>
                    <p class="text-gray-400 text-xs mt-1">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                </div>

                @if(auth()->id() === $user->id)
                    <a href="#" class="px-4 py-2 bg-gray-100 text-dark rounded-xl hover:bg-gray-200 transition text-sm font-bold shrink-0">
                        Modifier
                    </a>
                @endif
            </div>

            <!-- Bloc conditionnel par rôle -->
            @switch($user->role)

                @case('Donor')
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Groupe sanguin - badge visible -->
                        <div class="bg-red-pale rounded-2xl p-4 text-center border border-red-light">
                            <p class="text-red-mid text-xs font-bold uppercase mb-2">Groupe sanguin</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="inline-block px-4 py-2 bg-red-mid text-white rounded-xl text-2xl font-black">
                                    {{ $user->blood_group ?? 'N/A' }}
                                </span>
                                <div class="text-right">
                                    @if($user->is_verified)
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                            ✅ Vérifié
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">
                                            ⚠️ Non vérifié
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase">Dons totaux</p>
                            <p class="text-2xl font-black text-red-mid mt-2">{{ $totalDonations }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase">Vies sauvées</p>
                            <p class="text-2xl font-black text-green-600 mt-2">{{ $lifesSaved }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase">Disponible</p>
                            <p class="text-2xl font-black mt-2">
                                @if($user->status_availabality) ✅ @else ❌ @endif
                            </p>
                        </div>
                    </div>
                    @break

                @case('AgentCentre')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($user->centre)
                            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                                <p class="text-blue-600 text-xs font-bold uppercase mb-1">Centre rattaché</p>
                                <p class="text-dark font-bold text-lg">{{ $user->centre->name }}</p>
                                <p class="text-gray-500 text-sm mt-1">📍 {{ $user->centre->city }}</p>
                                <p class="text-gray-400 text-xs mt-1">🪪 {{ $user->centre->liscence_number }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Adresse du centre</p>
                                <p class="text-dark font-medium">{{ $user->centre->adress }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-2xl p-4 col-span-2 text-center text-gray-400">
                                Aucun centre rattaché
                            </div>
                        @endif
                    </div>
                    @break

                @case('AgentHopital')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($user->hopital)
                            <div class="bg-indigo-50 rounded-2xl p-4 border border-indigo-100">
                                <p class="text-indigo-600 text-xs font-bold uppercase mb-1">Hôpital rattaché</p>
                                <p class="text-dark font-bold text-lg">{{ $user->hopital->name }}</p>
                                <p class="text-gray-500 text-sm mt-1">📍 {{ $user->hopital->city }}</p>
                                <p class="text-gray-400 text-xs mt-1">🪪 {{ $user->hopital->liscence_number }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Adresse de l'hôpital</p>
                                <p class="text-dark font-medium">{{ $user->hopital->adress }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-2xl p-4 col-span-2 text-center text-gray-400">
                                Aucun hôpital rattaché
                            </div>
                        @endif
                    </div>
                    @break

                @case('Admin')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-purple-50 rounded-2xl p-4 border border-purple-100 text-center">
                            <p class="text-purple-600 text-xs font-bold uppercase mb-2">Rôle système</p>
                            <p class="text-dark font-black text-lg">Administrateur</p>
                            <p class="text-purple-400 text-xs mt-1">Accès complet</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase mb-2">Téléphone</p>
                            <p class="text-dark font-bold">{{ $user->phone ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-gray-500 text-xs font-bold uppercase mb-2">Ville</p>
                            <p class="text-dark font-bold">{{ $user->city }}</p>
                        </div>
                    </div>
                    @break

            @endswitch
        </div>

        <!-- Historique des dons (Donor uniquement) -->
        @if($user->role === 'Donor')
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <h2 class="text-2xl font-bold text-dark mb-6">Historique des dons</h2>

            @if($donations->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <p class="text-4xl mb-3">📋</p>
                    <p class="font-bold">Aucun don enregistré</p>
                    <p class="text-sm">Rendez-vous dans un centre pour effectuer votre premier don</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Centre</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe détecté</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4 text-sm">{{ $donation->donation_date->format('d/m/Y') }}</td>
                                    <td class="py-4 px-4 text-sm font-medium">{{ $donation->centre->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-4">
                                        @include('components.blood-badge', ['bloodGroup' => $donation->observed_blood_group ?? 'Unknown'])
                                    </td>
                                    <td class="py-4 px-4">
                                        @include('components.status-badge', ['status' => $donation->test_result ?? 'pending'])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection
