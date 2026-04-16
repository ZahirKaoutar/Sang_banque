@extends('layouts.app')

@section('title', 'Connexion - HemoLife')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12 bg-red-gradient">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 border border-red-50">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-pale">
                <svg viewBox="0 0 24 24" class="w-8 h-8" style="fill: #C0392B;">
                    <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0 0 16 0c0-3.5-4-7-8-12z" />
                </svg>
            </div>
            <h2 class="font-display text-3xl font-bold text-dark">Connexion</h2>
            <p class="text-gray-500 mt-2">Bienvenue sur HemoLife</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@mail.ma"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('email') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('email')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('password') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('password')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @error('general')
                <div class="bg-red-50 border border-red-200 text-red-deep px-4 py-3 rounded-xl text-sm">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="w-full py-4 px-4 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 text-white font-bold text-base" style="background: #C0392B;">
                Connexion
            </button>

            <p class="text-center text-sm text-gray-500 mt-6">
                Pas de compte ?
                <a href="{{ route('register') }}" class="text-red-mid hover:text-red-deep font-bold hover:underline">
                    S'inscrire ici
                </a>
            </p>
        </form>
    </div>
</div>
@endsection
