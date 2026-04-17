@extends('layouts.app')

@section('title', 'Stock de sang - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-8">

        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Stock de sang</h1>
            <p class="text-gray-600">Gérez les réserves de sang de votre centre</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-2xl px-6 py-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-2xl px-6 py-4 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Formulaire ajout stock -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-dark mb-6">Ajouter du stock</h2>

            <form method="POST" action="{{ route('centre.stock.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Groupe sanguin</label>
                    <select name="blood_group" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('blood_group') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                            <option value="{{ $group }}" {{ old('blood_group') === $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Quantité (unités)</label>
                    <input type="number" name="quantity_units" value="{{ old('quantity_units') }}"
                        placeholder="50" min="1" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('quantity_units') border-red-500 @enderror" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Date d'expiration</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('expiry_date') border-red-500 @enderror" />
                </div>

                <button type="submit"
                    class="py-3 px-6 bg-red-mid text-white rounded-xl font-bold hover:bg-red-deep transition">
                    + Ajouter
                </button>
            </form>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                @php
                    $qty = $stocks->where('blood_group', $group)->sum('quantity_units');
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center shadow-sm">
                    <span class="inline-block px-3 py-1 bg-red-mid text-white rounded-lg text-sm font-black mb-2">
                        {{ $group }}
                    </span>
                    <p class="text-2xl font-black {{ $qty === 0 ? 'text-red-deep' : ($qty < 10 ? 'text-yellow-500' : 'text-green-600') }}">
                        {{ $qty }}
                    </p>
                    <p class="text-xs text-gray-400">unités</p>
                </div>
            @endforeach
        </div>

        <!-- Table stock -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-dark">Détail du stock</h2>
                <span class="text-sm text-gray-500">Total : <span class="font-bold text-dark">{{ $totalUnits }}</span> unités</span>
            </div>

            @if($stocks->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <p class="text-4xl mb-3">🩸</p>
                    <p class="font-bold">Aucun stock enregistré</p>
                    <p class="text-sm">Ajoutez du stock via le formulaire ci-dessus</p>
                </div>
            @else
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left py-3 px-6 text-xs font-bold text-gray-500 uppercase">Groupe</th>
                            <th class="text-left py-3 px-6 text-xs font-bold text-gray-500 uppercase">Quantité</th>
                            <th class="text-left py-3 px-6 text-xs font-bold text-gray-500 uppercase">Expiration</th>
                            <th class="text-left py-3 px-6 text-xs font-bold text-gray-500 uppercase">État</th>
                            <th class="text-left py-3 px-6 text-xs font-bold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                            @php
                                $expiry = \Carbon\Carbon::parse($stock->expiry_date);
                                $daysLeft = now()->diffInDays($expiry, false);
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-red-mid text-white rounded-lg text-sm font-black">
                                        {{ $stock->blood_group }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-bold text-dark">{{ $stock->quantity_units }} unités</td>
                                <td class="py-4 px-6 text-sm text-gray-600">{{ $expiry->format('d/m/Y') }}</td>
                                <td class="py-4 px-6">
                                    @if($daysLeft < 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-deep rounded-lg text-xs font-bold">Expiré</span>
                                    @elseif($daysLeft <= 7)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold">Expire bientôt</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Valide</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('centre.stock.delete', $stock->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce stock ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-pale text-red-deep rounded-lg hover:bg-red-deep hover:text-white transition text-xs font-bold">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
@endsection
