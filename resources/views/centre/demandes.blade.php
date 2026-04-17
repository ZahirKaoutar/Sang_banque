@extends('layouts.app')

@section('title', 'Demandes - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ filter: 'all' }">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Demandes de sang</h1>
            <p class="text-gray-600">Gestion des demandes de sang des hôpitaux</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-3 border-b border-gray-200">
            <button @click="filter = 'all'" :class="filter === 'all' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark'" class="px-4 py-2 font-bold transition">
                Tous
            </button>
            <button @click="filter = 'pending'" :class="filter === 'pending' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark'" class="px-4 py-2 font-bold transition">
                En attente
            </button>
            <button @click="filter = 'partial'" :class="filter === 'partial' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark'" class="px-4 py-2 font-bold transition">
                Partiel
            </button>
            <button @click="filter = 'Fulfilled'" :class="filter === 'Fulfilled' ? 'border-b-2 border-red-mid text-red-mid' : 'text-gray-600 hover:text-dark'" class="px-4 py-2 font-bold transition">
                Complété
            </button>
        </div>

        <!-- Requests Table -->
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
                        @foreach($allRequests as $request)
                            <tr x-show="filter === 'all' || filter === '{{ $request->status }}'" class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4">{{ $request->hopital->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4">
                                    @include('components.blood-badge', ['bloodGroup' => $request->blood_group])
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-bold">{{ $request->quantity_fulfilled ?? 0 }}</span> / {{ $request->quantity_needed }}ml
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $request->priority === 'Urgent' ? 'bg-red-100 text-red-deep' : 'bg-blue-100 text-blue-900' }}">
                                        {{ $request->priority }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @include('components.status-badge', ['status' => $request->status])
                                </td>
                                <td class="py-4 px-4">
                                    @if($request->status === 'pending')
                                        <button
                                            onclick="validateRequest({{ $request->id }}, this)"
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
</div>

<script>
function validateRequest(id, btn) {
    if (!confirm('Valider cette demande ?')) return;

    btn.disabled = true;
    btn.textContent = '...';

    fetch('/centre/demandes/' + id + '/validate', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({})
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) {
            btn.disabled = false;
            btn.textContent = 'Valider';
            alert(data.error);
            return;
        }
        var statusColors = {
            'Fulfilled': 'bg-green-100 text-green-800',
            'partial':   'bg-yellow-100 text-yellow-800',
            'pending':   'bg-orange-100 text-orange-800'
        };
        var color = statusColors[data.status] || 'bg-gray-100 text-gray-600';
        var row = btn.closest('tr');
        row.querySelector('td:nth-child(5)').innerHTML =
            '<span class="inline-block px-3 py-1 rounded-full text-xs font-bold ' + color + '">' + data.status + '</span>';
        btn.closest('td').innerHTML = '<span class="text-gray-400 text-sm">-</span>';

        var banner = document.createElement('div');
        banner.className = 'fixed top-4 right-4 z-50 bg-white border border-gray-200 rounded-2xl px-6 py-4 shadow-lg text-sm font-medium text-dark max-w-sm';
        banner.textContent = data.message;
        document.body.appendChild(banner);
        setTimeout(function() { banner.remove(); }, 4000);
    })
    .catch(function() {
        btn.disabled = false;
        btn.textContent = 'Valider';
        alert('Erreur serveur. Verifiez les logs.');
    });
}
</script>
@endsection
