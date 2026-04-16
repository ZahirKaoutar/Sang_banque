@extends('layouts.app')

@section('title', 'Mes Dons - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Mes dons</h1>
            <p class="text-gray-600">Suivi de vos donations et éligibilité</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Dons totaux</p>
                <p class="text-3xl font-black text-dark mt-2">{{ $totalDonations }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Dons acceptés</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $acceptedDonations }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Vies sauvées</p>
                <p class="text-3xl font-black text-red-mid mt-2">{{ $lifesSaved }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Groupe</p>
                <p class="text-3xl font-black text-dark mt-2">{{ $user->blood_group }}</p>
            </div>
        </div>

        <!-- Eligibility Progress Bar -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100">
            <h2 class="text-xl font-bold text-dark mb-4">Éligibilité au prochain don</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Délai minimum: 90 jours</span>
                    <span class="font-bold text-red-mid">{{ $daysUntilEligible }} jours restants</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    @php
                        $progress = max(0, 90 - $daysUntilEligible);
                        $progressPercent = ($progress / 90) * 100;
                    @endphp
                    <div class="h-full transition-all" style="width: {{ $progressPercent }}%; background: #C0392B;"></div>
                </div>
                @if($daysUntilEligible === 0)
                    <p class="text-green-600 font-bold">✅ Vous êtes éligible pour donner!</p>
                @else
                    <p class="text-gray-600 text-sm">Vous pourrez donner dans {{ $daysUntilEligible }} jour(s)</p>
                @endif
            </div>
        </div>

        <!-- Donation History -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100">
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
                                    <td class="py-4 px-4">{{ $donation->donation_date?->format('d/m/Y') ?? 'N/A' }}</td>
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
