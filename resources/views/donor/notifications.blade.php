@extends('layouts.app')

@section('title', 'Notifications - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Alertes de sangurgence</h1>
            <p class="text-gray-600">Répondez aux demandes urgentes en fonction de votre groupe sanguin</p>
        </div>

        <!-- Notifications List -->
        @if($notifications->isEmpty())
            @include('components.empty-state', ['icon' => '🩸', 'title' => 'Aucune alerte', 'subtitle' => 'Il n\'y a actuellement aucune demande urgente pour votre groupe sanguin'])
        @else
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div x-data="{ responded: false, response: '' }" class="bg-white rounded-3xl p-6 border border-gray-100 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    @include('components.blood-badge', ['bloodGroup' => $notification->blood_group_needed])
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-deep rounded-full text-xs font-bold">🚨 URGENT</span>
                                </div>
                                <h3 class="text-lg font-bold text-dark">{{ $notification->centre->name ?? 'Centre de transfusion' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div x-show="!responded" class="flex gap-3">
                            <form method="POST" :action="`/notifications/{{ $notification->id }}/respond`" onsubmit="this.closest('.space-y-4').parentElement.querySelector('[data-notification]').style.opacity = '0.5'">
                                @csrf
                                <input type="hidden" name="response" value="accepter" />
                                <button type="submit" @click="responded = true; response = 'accepter'" class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold">
                                    ✅ Accepter
                                </button>
                            </form>
                            <form method="POST" :action="`/notifications/{{ $notification->id }}/respond`" onsubmit="this.closest('.space-y-4').parentElement.querySelector('[data-notification]').style.opacity = '0.5'">
                                @csrf
                                <input type="hidden" name="response" value="refuser" />
                                <button type="submit" @click="responded = true; response = 'refuser'" class="px-6 py-2 bg-red-deep text-white rounded-xl hover:bg-red-900 transition font-bold">
                                    ❌ Refuser
                                </button>
                            </form>
                        </div>

                        <!-- Confirmation Message -->
                        <div x-show="responded" class="text-sm font-bold" :class="response === 'accepter' ? 'text-green-600' : 'text-red-600'">
                            <span x-show="response === 'accepter'">✅ Merci pour votre acceptation!</span>
                            <span x-show="response === 'refuser'">❌ Vous avez refusé cette demande</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
