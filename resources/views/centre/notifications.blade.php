@extends('layouts.app')

@section('title', 'Réponses des donneurs - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Réponses des donneurs</h1>
            <p class="text-gray-600">Suivi des réponses aux demandes d'urgence</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Total</p>
                <p class="text-3xl font-black text-dark mt-2">{{ $totalResponses }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Acceptées</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $accepted }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Refusées</p>
                <p class="text-3xl font-black text-red-deep mt-2">{{ $refused }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Taux d'acceptation</p>
                <p class="text-3xl font-black text-blue-600 mt-2">{{ $acceptanceRate }}%</p>
            </div>
        </div>

        <!-- Responses Table -->
        @if($responses->isEmpty())
            @include('components.empty-state', ['icon' => '📋', 'title' => 'Aucune réponse', 'subtitle' => 'Il n\'y a pas encore de réponses de donneurs'])
        @else
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Donneur</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe sanguin</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Réponse</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($responses as $response)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-dark">{{ $response->donor->name ?? 'Anonyme' }}</td>
                                <td class="py-4 px-4">
                                    @include('components.blood-badge', ['bloodGroup' => $response->donor->blood_group ?? 'Unknown'])
                                </td>
                                <td class="py-4 px-4">
                                    @include('components.status-badge', ['status' => $response->donor_response])
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $response->responded_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
