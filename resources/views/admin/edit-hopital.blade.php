@extends('layouts.app')

@section('title', 'Modifier Hôpital - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        <a href="{{ route('admin.show-hopital', $hopital->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-red-mid transition text-sm mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour à l'hôpital
        </a>

        <h1 class="text-4xl font-bold text-dark mb-2">Modifier l'Hôpital</h1>
        <p class="text-gray-600">{{ $hopital->name }}</p>

        @if(session('error'))
            <div class="mt-4 bg-red-100 border border-red-300 text-red-800 rounded-2xl px-6 py-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.update-hopital', $hopital->id) }}" class="mt-8 bg-white rounded-3xl p-8 border border-gray-100 space-y-8">
            @csrf
            @method('PUT')

            <!-- Agent -->
            <div class="pb-8 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-dark mb-6">Informations de l'agent</h2>
                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name', $hopital->user->name) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('name') border-red-500 @enderror" />
                        @error('name') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $hopital->user->email) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('email') border-red-500 @enderror" />
                        @error('email') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $hopital->user->phone) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('phone') border-red-500 @enderror" />
                        @error('phone') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <!-- Hopital -->
            <div>
                <h2 class="text-2xl font-bold text-dark mb-6">Informations de l'hôpital</h2>
                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Nom de l'hôpital</label>
                        <input type="text" name="hospital_name" value="{{ old('hospital_name', $hopital->name) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('hospital_name') border-red-500 @enderror" />
                        @error('hospital_name') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Adresse</label>
                        <input type="text" name="adress" value="{{ old('adress', $hopital->adress) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('adress') border-red-500 @enderror" />
                        @error('adress') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $hopital->city) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('city') border-red-500 @enderror" />
                        @error('city') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Numéro de licence</label>
                        <input type="text" name="license_number" value="{{ old('license_number', $hopital->liscence_number) }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('license_number') border-red-500 @enderror" />
                        @error('license_number') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-4 bg-red-mid text-white rounded-2xl font-bold hover:bg-red-deep transition">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('admin.show-hopital', $hopital->id) }}" class="flex-1 py-4 bg-gray-100 text-dark rounded-2xl font-bold hover:bg-gray-200 transition text-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
