@extends('layouts.app')

@section('title', 'Demandes de sang - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ selectedBloodGroup: '' }">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Demandes de sang</h1>
            <p class="text-gray-600">Créer une nouvelle demande urgente</p>
        </div>

        <!-- Create Request Form -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100">
            <form method="POST" action="{{ route('hopital.store') }}" class="space-y-6">
                @csrf

                <!-- Centre Selection -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Centre de transfusion</label>
                    <select name="center_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('center_id') border-red-500 @enderror">
                        <option value="">Sélectionner un centre</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}">{{ $centre->name }} - {{ $centre->city }}</option>
                        @endforeach
                    </select>
                    @error('center_id')
                        <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Blood Group Selector -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-3">Groupe sanguin</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                            <label class="cursor-pointer">
                                <input type="radio" name="blood_group" value="{{ $group }}" x-model="selectedBloodGroup" required class="hidden" />
                                <div class="p-3 text-center rounded-xl border-2 transition-all"
                                    :class="selectedBloodGroup === '{{ $group }}' ? 'border-red-mid bg-red-pale text-red-deep font-bold' : 'border-gray-200 bg-gray-50 hover:border-gray-300'">
                                    {{ $group }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('blood_group')
                        <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Quantité (ml)</label>
                    <input type="number" name="quantity_needed" value="{{ old('quantity_needed') }}" placeholder="450" min="50" max="5000" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('quantity_needed') border-red-500 @enderror" />
                    @error('quantity_needed')
                        <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-3">Priorité</label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="Normal" checked class="mr-3" />
                            <span class="text-gray-700">Normal</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="Urgent" class="mr-3" />
                            <span class="text-gray-700">Urgent</span>
                        </label>
                    </div>
                    @error('priority')
                        <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Notes supplémentaires</label>
                    <textarea name="description" placeholder="Détails supplémentaires..." rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-mid focus:border-transparent @error('description') border-red-500 @enderror"></textarea>
                    @error('description')
                        <p class="text-red-deep text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-red-mid text-white rounded-2xl font-bold hover:bg-red-deep transition">
                    Soumettre la demande
                </button>
            </form>
        </div>

        <!-- Past Requests -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-dark mb-6">Demandes précédentes</h2>

            @if($pastRequests->isEmpty())
                @include('components.empty-state', ['icon' => '📋', 'title' => 'Aucune demande', 'subtitle' => 'Vous n\'avez pas encore effectué de demande'])
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Centre</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Quantité</th>
                                <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastRequests as $request)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4">{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td class="py-4 px-4">{{ $request->centre->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-4">
                                        @include('components.blood-badge', ['bloodGroup' => $request->blood_group])
                                    </td>
                                    <td class="py-4 px-4">{{ $request->quantity_fulfilled ?? 0 }} / {{ $request->quantity_needed }}ml</td>
                                    <td class="py-4 px-4">
                                        @include('components.status-badge', ['status' => $request->status])
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
@endsection
