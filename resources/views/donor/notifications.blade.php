@extends('layouts.app')

@section('title', 'Notifications - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Alertes de sang d'urgence</h1>
            <p class="text-gray-600">Répondez aux demandes urgentes en fonction de votre groupe sanguin</p>
        </div>

        @if($notifications->isEmpty())
            @include('components.empty-state', ['icon' => '🩸', 'title' => 'Aucune alerte', 'subtitle' => 'Il n\'y a actuellement aucune demande urgente pour votre groupe sanguin'])
        @else
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    {{-- Utilisation d'Alpine.js pour gérer l'état local de chaque notification --}}
                    <div x-data="{ responded: false, response: '' }" 
                         :class="responded ? 'opacity-50 pointer-events-none' : ''" 
                         class="bg-white rounded-3xl p-6 border border-gray-100 hover:shadow-md transition duration-300">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    {{-- Badge du groupe sanguin --}}
                                    @include('components.blood-badge', ['bloodGroup' => $notification->blood_group_needed])
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold font-mono">🚨 URGENT</span>
                                </div>
                                <h3 class="text-lg font-bold text-dark">{{ $notification->centre->name ?? 'Centre de transfusion' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                       <div x-show="!responded" class="flex gap-3 mt-4">
    {{-- Formulaire Accepter --}}
    <form method="POST" action="{{ route('donor.respond', $notification) }}">
        @csrf
        <input type="hidden" name="response" value="accepter" />
        <button type="submit"
                @click="responded = true; response = 'accepter'"
                class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold shadow-sm">
            ✅ Accepter
        </button>
    </form>

    {{-- Formulaire Refuser --}}
    <form method="POST" action="{{ route('donor.respond', $notification) }}">
        @csrf
        <input type="hidden" name="response" value="refuser" />
        <button type="submit"
                @click="responded = true; response = 'refuser'"
                class="px-6 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-bold shadow-sm">
            ❌ Refuser
        </button>
    </form>
</div>

                        <div x-show="responded" x-cloak class="text-sm font-bold mt-4 p-3 rounded-lg border" 
                             :class="response === 'accepter' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'">
                            <span x-show="response === 'accepter'">✅ Merci ! Votre acceptation a été enregistrée.</span>
                            <span x-show="response === 'refuser'">❌ Vous avez refusé cette demande.</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection