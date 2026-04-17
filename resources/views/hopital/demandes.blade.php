@extends('layouts.app')

@section('title', 'Demandes de sang - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">

        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Demandes de sang</h1>
            <p class="text-gray-600">Créer une nouvelle demande pour votre hôpital</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-2xl px-6 py-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-2xl px-6 py-4">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-dark mb-6">Nouvelle demande</h2>

            <form method="POST" action="{{ route('hopital.store') }}" class="space-y-6">
                @csrf

                <!-- Centre -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Centre de transfusion</label>
                    <select name="center_id" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('center_id') border-red-500 @enderror">
                        <option value="">Sélectionner un centre</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}" {{ old('center_id') == $centre->id ? 'selected' : '' }}>
                                {{ $centre->name }} — {{ $centre->city }}
                            </option>
                        @endforeach
                    </select>
                    @error('center_id') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Groupe sanguin -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-3">Groupe sanguin requis</label>
                    <div class="grid grid-cols-4 gap-2" id="bloodGroupGrid">
                        @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                            <label class="cursor-pointer">
                                <input type="radio" name="blood_group" value="{{ $group }}"
                                    {{ old('blood_group') === $group ? 'checked' : '' }}
                                    class="hidden blood-radio" />
                                <div class="p-3 text-center rounded-xl border-2 border-gray-200 bg-gray-50 hover:border-red-mid transition font-bold text-sm blood-label">
                                    {{ $group }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('blood_group') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Quantité -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Quantité (unités)</label>
                    <input type="number" name="quantity_needed" value="{{ old('quantity_needed') }}"
                        placeholder="10" min="1" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:outline-none @error('quantity_needed') border-red-500 @enderror" />
                    @error('quantity_needed') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Priorité -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-3">Priorité</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="priority" value="Normal"
                                {{ old('priority', 'Normal') === 'Normal' ? 'checked' : '' }} />
                            <span class="font-medium text-gray-700">Normal</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="priority" value="Urgent"
                                {{ old('priority') === 'Urgent' ? 'checked' : '' }} />
                            <span class="font-medium text-red-deep">🚨 Urgent</span>
                        </label>
                    </div>
                    @error('priority') <p class="text-red-deep text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-red-mid text-white rounded-2xl font-bold hover:bg-red-deep transition">
                    Soumettre la demande
                </button>
            </form>
        </div>

        <!-- Historique -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <h2 class="text-2xl font-bold text-dark mb-6">Demandes précédentes</h2>

            @if($pastRequests->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <p class="text-4xl mb-3">📋</p>
                    <p class="font-bold">Aucune demande</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Centre</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Groupe</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Quantité</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Priorité</th>
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastRequests as $req)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm">{{ $req->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4 text-sm font-medium">{{ $req->centre->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 bg-red-pale text-red-deep rounded-lg text-xs font-bold">
                                            {{ $req->blood_group }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">{{ $req->quantity_fulfilled ?? 0 }} / {{ $req->quantity_needed }}</td>
                                    <td class="py-3 px-4">
                                        @if($req->priority === 'Urgent')
                                            <span class="px-2 py-1 bg-red-100 text-red-deep rounded-lg text-xs font-bold">🚨 Urgent</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">Normal</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @include('components.status-badge', ['status' => $req->status])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.blood-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.blood-label').forEach(function(label) {
                label.classList.remove('border-red-mid', 'bg-red-pale', 'text-red-deep');
                label.classList.add('border-gray-200', 'bg-gray-50');
            });
            this.nextElementSibling.classList.remove('border-gray-200', 'bg-gray-50');
            this.nextElementSibling.classList.add('border-red-mid', 'bg-red-pale', 'text-red-deep');
        });

        // Restore on page load if old() value
        if (this.checked) {
            this.nextElementSibling.classList.remove('border-gray-200', 'bg-gray-50');
            this.nextElementSibling.classList.add('border-red-mid', 'bg-red-pale', 'text-red-deep');
        }
    });
</script>
@endsection
