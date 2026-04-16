@extends('layouts.app')

@section('title', 'Profil - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-6 mb-8">
                <!-- Avatar -->
                <div class="w-20 h-20 rounded-full flex items-center justify-center" style="background: #C0392B;">
                    <span class="text-4xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-dark">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }} • {{ $user->city }}</p>
                </div>

                @if(auth()->id() === $user->id)
                    <a href="#" class="px-4 py-2 bg-gray-100 text-dark rounded-xl hover:bg-gray-200 transition">Modifier</a>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase">Groupe sanguin</p>
                    <p class="text-2xl font-black text-dark mt-2">{{ $user->blood_group }}</p>
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
                    <p class="text-gray-500 text-xs font-bold uppercase">État</p>
                    <p class="text-2xl font-black text-blue-600 mt-2">@if($user->status_availabality === 1 || $user->status_availabality === true) ✅ @else ❌ @endif</p>
                </div>
            </div>
        </div>

        <!-- Donation History -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <h2 class="text-2xl font-bold text-dark mb-6">Historique des dons</h2>

            @if($donations->isEmpty())
                @include('components.empty-state', ['icon' => '📋', 'title' => 'Aucun don', 'subtitle' => 'Vous n\'avez pas encore effectué de don'])
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Centre</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe détecté</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donations as $donation)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4">{{ $donation->donation_date->format('d/m/Y') }}</td>
                                    <td class="py-4 px-4">{{ $donation->centre->name ?? 'N/A' }}</td>
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
    </div>
</div>
@endsection
