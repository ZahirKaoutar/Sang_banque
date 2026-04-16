@extends('layouts.app')

@section('title', 'Inscription - HemoLife')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-12 bg-red-gradient">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl p-8 border border-red-50">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-pale">
                <svg viewBox="0 0 24 24" class="w-8 h-8" style="fill: #C0392B;">
                    <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0 0 16 0c0-3.5-4-7-8-12z" />
                </svg>
            </div>
            <h2 class="font-display text-3xl font-bold text-dark">Inscription</h2>
            <p class="text-gray-500 mt-2">Créez votre compte HemoLife</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ bloodGroup: '' }">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Hassan"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('name') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('name')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="ahmed@mail.ma"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('email') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('email')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Téléphone</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+212 6XX XXX XXX"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('phone') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('phone')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ville</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="Casablanca"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all @error('city') border-red-500 ring-2 ring-red-100 @enderror" />
                @error('city')
                    <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Blood Group Selector -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Groupe sanguin</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                        <label class="cursor-pointer">
                            <input type="radio" name="blood_group" value="{{ $group }}" x-model="bloodGroup"
                                class="hidden" />
                            <div class="p-3 text-center rounded-xl border-2 transition-all"
                                :class="bloodGroup === '{{ $group }}' ? 'border-red-mid bg-red-pale text-red-deep font-bold' : 'border-gray-200 bg-gray-50 hover:border-gray-300'">
                                {{ $group }}
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('blood_group')
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

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-light focus:border-red-mid outline-none transition-all" />
            </div>

            @error('general')
                <div class="bg-red-50 border border-red-200 text-red-deep px-4 py-3 rounded-xl text-sm">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="w-full py-4 px-4 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 text-white font-bold text-base" style="background: #C0392B;">
                Créer un compte
            </button>

            <p class="text-center text-sm text-gray-500 mt-6">
                Vous avez déjà un compte ?
                <a href="{{ route('login') }}" class="text-red-mid hover:text-red-deep font-bold hover:underline">
                    Se connecter
                </a>
            </p>
        </form>
    </div>
</div>
@endsection
