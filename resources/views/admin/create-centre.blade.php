@extends('layouts.app')

@section('title', 'Créer Centre - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Créer un Centre</h1>
            <p class="text-gray-600">Créez un nouveau centre de transfusion et son agent</p>
        </div>

        <form method="POST" action="{{ route('admin.store-centre') }}" class="mt-8 bg-white rounded-3xl p-8 border border-gray-100 space-y-8">
            @csrf

            <!-- Agent Section -->
            <div class="pb-8 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-dark mb-6">Informations de l'agent</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Hassan"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('name') border-red-500 @enderror" />
                        @error('name')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@mail.ma"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('email') border-red-500 @enderror" />
                        @error('email')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+212 6XX XXX XXX"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('phone') border-red-500 @enderror" />
                        @error('phone')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Mot de passe</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('password') border-red-500 @enderror" />
                        @error('password')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Confirmer mot de passe</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent" />
                    </div>
                </div>
            </div>

            <!-- Centre Section -->
            <div>
                <h2 class="text-2xl font-bold text-dark mb-6">Informations du centre</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Nom du centre</label>
                        <input type="text" name="center_name" value="{{ old('center_name') }}" placeholder="Centre de Transfusion X"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('center_name') border-red-500 @enderror" />
                        @error('center_name')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Adresse</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Rue de Health"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('address') border-red-500 @enderror" />
                        @error('address')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Ville</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Casablanca"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('city') border-red-500 @enderror" />
                        @error('city')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-dark mb-2">Numéro de licence</label>
                        <input type="text" name="license_number" value="{{ old('license_number') }}" placeholder="LIC-2024-001"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('license_number') border-red-500 @enderror" />
                        @error('license_number')
                            <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-4 bg-red-mid text-white rounded-2xl font-bold hover:bg-red-deep transition">
                    Créer le centre
                </button>
                <a href="{{ route('admin.centres') }}" class="flex-1 py-4 bg-gray-100 text-dark rounded-2xl font-bold hover:bg-gray-200 transition text-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
