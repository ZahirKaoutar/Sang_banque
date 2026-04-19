@extends('layouts.app')

@section('title', 'Notifications - Hôpital')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Notifications du Centre</h1>
            <p class="text-gray-600">Alertes et informations concernant vos demandes de sang</p>
        </div>

        @if($notifications->isEmpty())
            @include('components.empty-state', ['icon' => '📭', 'title' => 'Aucune notification', 'subtitle' => 'Vous n\'avez reçu aucune alerte pour le moment.'])
        @else
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 hover:shadow-md transition duration-300 relative overflow-hidden">
                        
                        <!-- Ligne décorative rouge -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-mid"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold font-mono">⚠️ ALERTE</span>
                                    <span class="text-sm font-bold text-gray-500">{{ $notification->centre->name ?? 'Centre' }}</span>
                                </div>
                                <p class="text-lg text-dark mt-2 font-medium">{{ $notification->message }}</p>
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
