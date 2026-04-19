@extends('layouts.app')

@section('title', 'Modifier le Profil - HemoLife')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12 bg-red-gradient">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl p-8 border border-red-50">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-pale">
                <svg viewBox="0 0 24 24" class="w-8 h-8" style="fill: #C0392B;">
                    <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0 0 16 0c0-3.5-4-7-8-12z" />
                </svg>
            </div>
            <h2 class="font-display text-3xl font-bold text-dark">Modifier le Profil</h2>
            <p class="text-gray-500 mt-2">Mettez à jour vos informations personnelles</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Ahmed Hassan"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('name') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('name')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="ahmed@mail.ma"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('email') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('email')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Téléphone</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+212 6XX XXX XXX"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('phone') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('phone')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ville</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Casablanca"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('city') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('city')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>



            <hr class="my-6 border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Changer le mot de passe (optionnel)</p>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('password') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('password')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirmer nouveau mot de passe</label>
                <input type="password" name="password_confirmation" placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all" />
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('profile', $user->id) }}" class="flex-1 py-4 px-4 bg-gray-100 text-dark rounded-2xl font-bold text-center hover:bg-gray-200 transition-all">
                    Annuler
                </a>
                <button type="submit" class="flex-[2] py-4 px-4 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 text-white font-bold text-base" style="background: #C0392B;">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
