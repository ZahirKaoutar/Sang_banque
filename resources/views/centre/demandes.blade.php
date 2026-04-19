@extends('layouts.app')

@section('title', 'Demandes - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Demandes de sang</h1>
            <p class="text-gray-600">Gestion des demandes de sang des hôpitaux</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-3 border-b border-gray-200">
            @foreach(['all' => 'Tous', 'pending' => 'En attente', 'partial' => 'Partiel', 'Fulfilled' => 'Complété'] as $val => $label)
                <button data-filter="{{ $val }}"
                        class="filter-btn {{ $val === 'all' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark' }} px-4 py-2 font-bold transition">{{ $label }}</button>
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
                            <tr data-status="{{ $req->status }}"
                                class="demande-row border-b border-gray-100 hover:bg-gray-50 transition">
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
                                    @if($req->status === 'pending' || $req->status === 'partial')
                                        <button onclick="openValidateModal({{ $req->id }}, '{{ addslashes($req->hopital->name ?? 'N/A') }}', '{{ $req->blood_group }}', {{ $req->quantity_needed }})"
                                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-bold">
                                            Détails
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

    {{-- Modal Native JS --}}
    <div id="validate-modal"
         class="js-hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-dark">Analyse de la demande</h2>
                <button onclick="closeValidateModal()" class="text-gray-400 hover:text-dark text-2xl leading-none">&times;</button>
            </div>

            <div id="modal-loading" class="text-center py-8 text-gray-500">
                <div class="animate-spin inline-block w-6 h-6 border-2 border-gray-300 border-t-red-mid rounded-full"></div>
                <p class="mt-2">Chargement des données...</p>
            </div>
            <div id="modal-error-container" class="js-hidden text-red-600 text-sm p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="font-bold">⚠️ Erreur</p>
                <p id="modal-error-text" class="mt-1"></p>
            </div>

            <div id="modal-content" class="js-hidden">
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Hôpital</span>
                        <span class="font-bold text-dark" id="modal-hopital"></span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Groupe sanguin</span>
                        <span class="font-black text-red-600" id="modal-blood-group"></span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Quantité demandée</span>
                        <span class="font-black text-blue-700" id="modal-quantity"></span>
                    </div>
                    <div id="modal-stock-container" class="flex justify-between items-center p-4 rounded-2xl">
                        <span class="text-sm font-bold text-gray-600">Stock actuel</span>
                        <span class="font-black" id="modal-stock"></span>
                    </div>
                </div>

                {{-- Indicateur visuel --}}
                <div id="modal-stock-indicators">
                    <div id="indicator-sufficient" class="js-hidden flex items-center gap-2 p-3 bg-green-100 text-green-800 rounded-xl text-sm font-bold mb-4">
                        ✅ Stock suffisant — La demande peut être satisfaite entièrement.
                    </div>
                    <div id="indicator-partial" class="js-hidden flex items-center gap-2 p-3 bg-yellow-100 text-yellow-800 rounded-xl text-sm font-bold mb-4">
                        ⚠️ Stock partiel — Des alertes seront envoyées aux donneurs pour le reste.
                    </div>
                    <div id="indicator-critical" class="js-hidden flex items-center gap-2 p-3 bg-red-100 text-red-800 rounded-xl text-sm font-bold mb-4">
                        🚨 Stock critique — Aucune unité disponible. Les donneurs seront alertés.
                    </div>
                </div>

                <div class="flex gap-2">
                    <button onclick="closeValidateModal()"
                            class="py-3 px-4 border border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button onclick="sendNotification()" id="btn-notify"
                            class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
                        Envoyer Notification
                    </button>
                    <button onclick="confirmValidateRequest()" id="btn-validate"
                            class="flex-1 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Valider la demande
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentModalId = null;

    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.demande-row');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const filter = e.target.getAttribute('data-filter');
                
                // Update active state
                filterBtns.forEach(b => {
                    b.classList.remove('border-b-2', 'border-red-mid', 'text-red-mid');
                    b.classList.add('text-gray-600', 'hover:text-dark');
                });
                e.target.classList.remove('text-gray-600', 'hover:text-dark');
                e.target.classList.add('border-b-2', 'border-red-mid', 'text-red-mid');

                // Filter rows
                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    if (filter === 'all' || status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Close modal on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeValidateModal();
        });

        // Close modal on outside click
        const modal = document.getElementById('validate-modal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeValidateModal();
            });
        }
    });

    function openValidateModal(id, hopital, blood_group, quantity_needed) {
        if (!id || id === 'undefined') {
            showModalError('ID de demande invalide');
            return;
        }

        currentModalId = id;
        document.getElementById('validate-modal').classList.remove('js-hidden');
        document.getElementById('modal-loading').classList.remove('js-hidden');
        document.getElementById('modal-error-container').classList.add('js-hidden');
        document.getElementById('modal-content').classList.add('js-hidden');

        document.getElementById('modal-hopital').textContent = hopital;
        document.getElementById('modal-blood-group').textContent = blood_group;
        document.getElementById('modal-quantity').textContent = quantity_needed + ' unités';

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
            document.getElementById('modal-loading').classList.add('js-hidden');
            document.getElementById('modal-content').classList.remove('js-hidden');
            
            const stock = data.stock || 0;
            const stockEl = document.getElementById('modal-stock');
            const stockContainer = document.getElementById('modal-stock-container');
            
            stockEl.textContent = stock + ' / ' + quantity_needed + ' unités collectées';
            
            const btnValidate = document.getElementById('btn-validate');
            let canValidate = false;

            if (stock >= quantity_needed) {
                stockContainer.className = 'flex justify-between items-center p-4 rounded-2xl bg-green-50';
                stockEl.className = 'font-black text-green-700';
                canValidate = true;
            } else {
                stockContainer.className = 'flex justify-between items-center p-4 rounded-2xl bg-red-50';
                stockEl.className = 'font-black text-red-700';
                // Check urgent condition
                if (data.priority === 'Urgent' && data.hours_passed >= 10) {
                    canValidate = true;
                }
            }

            btnValidate.disabled = !canValidate;
            if (!canValidate) {
                btnValidate.title = "Stock insuffisant. (Les urgences \> 10h peuvent être validées partiellement)";
            } else {
                btnValidate.title = "";
            }

            document.getElementById('indicator-sufficient').classList.add('js-hidden');
            document.getElementById('indicator-partial').classList.add('js-hidden');
            document.getElementById('indicator-critical').classList.add('js-hidden');

            if (stock >= quantity_needed) {
                document.getElementById('indicator-sufficient').classList.remove('js-hidden');
            } else if (stock > 0) {
                document.getElementById('indicator-partial').classList.remove('js-hidden');
            } else {
                document.getElementById('indicator-critical').classList.remove('js-hidden');
            }
        })
        .catch(err => {
            showModalError(`Erreur: ${err.message}`);
        });
    }

    function showModalError(msg) {
        document.getElementById('modal-loading').classList.add('js-hidden');
        document.getElementById('modal-content').classList.add('js-hidden');
        document.getElementById('modal-error-container').classList.remove('js-hidden');
        document.getElementById('modal-error-text').textContent = msg;
    }

    function closeValidateModal() {
        document.getElementById('validate-modal').classList.add('js-hidden');
        currentModalId = null;
    }

    function sendNotification() {
        if (!currentModalId) return;

        const btn = document.getElementById('btn-notify');
        btn.disabled = true;
        btn.textContent = 'Envoi...';

        const url = `/centre/demandes/${currentModalId}/notify`;
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
            btn.disabled = false;
            btn.textContent = 'Envoyer Notification';
            
            if (data.error) {
                alert('❌ ' + data.error);
                return;
            }

            alert('✅ ' + (data.message || 'Notifications envoyées'));
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Envoyer Notification';
            console.error('Notify fetch error:', err);
            alert('❌ Erreur: ' + err.message);
        });
    }

    function confirmValidateRequest() {
        if (!currentModalId) return;

        const url = `/centre/demandes/${currentModalId}/validate`;
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
            closeValidateModal();

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
</script>
@endsection
