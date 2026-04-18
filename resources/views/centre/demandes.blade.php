@extends('layouts.app')

@section('title', 'Demandes - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{
    filter: 'all',
    modal: false,
    modalData: { id: null, hopital: '', blood_group: '', quantity_needed: 0, stock: 0, loading: false, error: '' },
    openModal(id, hopital, blood_group, quantity_needed) {
        // Vérifie que l'utilisateur est authentifié
        if (!id || id === 'undefined') {
            this.modalData.error = 'ID de demande invalide';
            return;
        }

        this.modalData = { id, hopital, blood_group, quantity_needed, stock: null, loading: true, error: '' };
        this.modal = true;

        const url = `/centre/demandes/${id}/details`;
        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            this.modalData.stock = data.stock || 0;
            this.modalData.loading = false;
        })
        .catch(err => {
            this.modalData.error = `Erreur: ${err.message}`;
            this.modalData.loading = false;
        });
    },
    validateRequest() {
        const url = `/centre/demandes/${this.modalData.id}/validate`;
        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;

        fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            this.modal = false;

            if (data.error) {
                alert('❌ ' + data.error);
                return;
            }

            alert('✅ ' + (data.message || 'Validation réussie'));
            window.location.reload();
        })
        .catch(err => {
            console.error('Validation fetch error:', err);
            alert('❌ Erreur: ' + err.message);
        });
    }
}">
    <div class="max-w-6xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Demandes de sang</h1>
            <p class="text-gray-600">Gestion des demandes de sang des hôpitaux</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-3 border-b border-gray-200">
            @foreach(['all' => 'Tous', 'pending' => 'En attente', 'partial' => 'Partiel', 'Fulfilled' => 'Complété'] as $val => $label)
                <button @click="filter = '{{ $val }}'"
                        :class="filter === '{{ $val }}' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark'"
                        class="px-4 py-2 font-bold transition">{{ $label }}</button>
            @endforeach
        </div>

        @if($allRequests->isEmpty())
            @include('components.empty-state', ['icon' => '📋', 'title' => 'Aucune demande', 'subtitle' => 'Il n\'y a actuellement aucune demande de sang'])
        @else
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Hôpital</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Quantité</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Priorité</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Statut</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allRequests as $req)
                            <tr x-show="filter === 'all' || filter === '{{ $req->status }}'"
                                class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4">{{ $req->hopital->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4">
                                    @include('components.blood-badge', ['bloodGroup' => $req->blood_group])
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-bold">{{ $req->quantity_fulfilled ?? 0 }}</span> / {{ $req->quantity_needed }} unités
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $req->priority === 'Urgent' ? 'bg-red-100 text-red-deep' : 'bg-blue-100 text-blue-900' }}">
                                        {{ $req->priority }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @include('components.status-badge', ['status' => $req->status])
                                </td>
                                <td class="py-4 px-4">
                                    @if($req->status === 'pending')
                                        <button @click="openModal({{ $req->id }}, '{{ addslashes($req->hopital->name ?? 'N/A') }}', '{{ $req->blood_group }}', {{ $req->quantity_needed }})"
                                                class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-bold">
                                            Valider
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Modal Alpine.js --}}
    <div x-show="modal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="modal = false">
        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-dark">Analyse de la demande</h2>
                <button @click="modal = false" class="text-gray-400 hover:text-dark text-2xl leading-none">&times;</button>
            </div>

            <div x-show="modalData.loading" class="text-center py-8 text-gray-500">
                <div class="animate-spin inline-block w-6 h-6 border-2 border-gray-300 border-t-red-mid rounded-full"></div>
                <p class="mt-2">Chargement des données...</p>
            </div>
            <div x-show="modalData.error" class="text-red-600 text-sm p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="font-bold">⚠️ Erreur</p>
                <p x-text="modalData.error" class="mt-1"></p>
            </div>

            <div x-show="!modalData.loading && !modalData.error">
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Hôpital</span>
                        <span class="font-bold text-dark" x-text="modalData.hopital"></span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Groupe sanguin</span>
                        <span class="font-black text-red-600" x-text="modalData.blood_group"></span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Quantité demandée</span>
                        <span class="font-black text-blue-700" x-text="modalData.quantity_needed + ' unités'"></span>
                    </div>
                    <div class="flex justify-between items-center p-4 rounded-2xl"
                         :class="modalData.stock >= modalData.quantity_needed ? 'bg-green-50' : 'bg-red-50'">
                        <span class="text-sm font-bold text-gray-600">Stock actuel</span>
                        <span class="font-black" :class="modalData.stock >= modalData.quantity_needed ? 'text-green-700' : 'text-red-700'"
                              x-text="(modalData.stock ?? 0) + ' unités'"></span>
                    </div>
                </div>

                {{-- Indicateur visuel --}}
                <div x-show="modalData.stock !== null">
                    <div x-show="modalData.stock >= modalData.quantity_needed"
                         class="flex items-center gap-2 p-3 bg-green-100 text-green-800 rounded-xl text-sm font-bold mb-4">
                        ✅ Stock suffisant — La demande peut être satisfaite entièrement.
                    </div>
                    <div x-show="modalData.stock < modalData.quantity_needed && modalData.stock > 0"
                         class="flex items-center gap-2 p-3 bg-yellow-100 text-yellow-800 rounded-xl text-sm font-bold mb-4">
                        ⚠️ Stock partiel — Des alertes seront envoyées aux donneurs pour le reste.
                    </div>
                    <div x-show="modalData.stock === 0"
                         class="flex items-center gap-2 p-3 bg-red-100 text-red-800 rounded-xl text-sm font-bold mb-4">
                        🚨 Stock critique — Aucune unité disponible. Les donneurs seront alertés.
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="modal = false"
                            class="flex-1 py-3 border border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button @click="validateRequest()"
                            class="flex-1 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition">
                        Confirmer la validation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
