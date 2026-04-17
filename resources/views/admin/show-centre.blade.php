@extends('layouts.app')

@section('title', $centre->name . ' - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">

        <a href="{{ route('admin.centres') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-red-mid transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour aux centres
        </a>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-2xl px-6 py-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Centre Card -->
        <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-dark">{{ $centre->name }}</h1>
                    <p class="text-gray-500 mt-1">📍 {{ $centre->city }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.edit-centre', $centre->id) }}"
                        class="px-4 py-2 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition font-bold text-sm">
                        Modifier
                    </a>
                    <form action="{{ route('admin.delete-centre', $centre->id) }}" method="POST"
                        onsubmit="return confirm('Supprimer ce centre ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-pale text-red-deep rounded-xl hover:bg-red-deep hover:text-white transition font-bold text-sm">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Adresse</p>
                    <p class="text-dark font-medium">{{ $centre->adress }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Ville</p>
                    <p class="text-dark font-medium">{{ $centre->city }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Numéro de licence</p>
                    <p class="text-dark font-medium">{{ $centre->liscence_number }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Créé le</p>
                    <p class="text-dark font-medium">{{ $centre->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Agent Card -->
        @if($centre->user)
        <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
            <h2 class="text-xl font-bold text-dark mb-6">Agent Responsable</h2>

            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-full bg-red-pale flex items-center justify-center text-2xl font-bold text-red-mid">
                    {{ strtoupper(substr($centre->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-dark text-lg">{{ $centre->user->name }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        {{ $centre->user->role }}
                    </span>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    @if($centre->user->is_banned)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-deep">Banni</span>
                        <form method="POST" action="{{ route('admin.unban-user', $centre->user->id) }}">
                            @csrf
                            <button class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition">Débannir</button>
                        </form>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Actif</span>
                        <form method="POST" action="{{ route('admin.ban-user', $centre->user->id) }}"
                            onsubmit="return confirm('Bannir cet agent ?')">
                            @csrf
                            <button class="px-3 py-1 bg-red-deep text-white rounded-lg text-xs font-bold hover:bg-red-900 transition">Bannir</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Email</p>
                    <p class="text-dark font-medium">{{ $centre->user->email }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Téléphone</p>
                    <p class="text-dark font-medium">{{ $centre->user->phone ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Ville</p>
                    <p class="text-dark font-medium">{{ $centre->user->city ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Membre depuis</p>
                    <p class="text-dark font-medium">{{ $centre->user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
