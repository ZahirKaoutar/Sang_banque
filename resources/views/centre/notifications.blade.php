@extends('layouts.app')

@section('title', 'Réponses des donneurs - HemoLife')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <div>
            <h1 class="text-4xl font-bold text-dark mb-2">Réponses des donneurs</h1>
            <p class="text-gray-600">Suivi des réponses et enregistrement des dons</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-2xl px-6 py-4 font-bold">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-orange-100 border border-orange-300 text-orange-800 rounded-2xl px-6 py-4 font-bold">
                ⚠️ {{ session('warning') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Total</p>
                <p class="text-3xl font-black text-dark mt-2">{{ $totalResponses }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Acceptées</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $accepted }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Refusées</p>
                <p class="text-3xl font-black text-red-600 mt-2">{{ $refused }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase">Taux d'acceptation</p>
                <p class="text-3xl font-black text-blue-600 mt-2">{{ $acceptanceRate }}%</p>
            </div>
        </div>

        <!-- Table -->
        @if($responses->isEmpty())
            @include('components.empty-state', ['icon' => '📋', 'title' => 'Aucune réponse', 'subtitle' => 'Il n\'y a pas encore de réponses de donneurs'])
        @else
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Donneur</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Groupe sanguin</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Réponse</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Date réponse</th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase">Test médical</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($responses as $response)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-dark">{{ $response->donor->name ?? 'Anonyme' }}</td>
                                <td class="py-4 px-4">
                                    @include('components.blood-badge', ['bloodGroup' => $response->donor->blood_group ?? 'N/A'])
                                </td>
                                <td class="py-4 px-4">
                                    @include('components.status-badge', ['status' => $response->donor_response])
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    {{ $response->responded_at ? \Carbon\Carbon::parse($response->responded_at)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($response->donor_response === 'accepter' && !$response->donation_recorded)
                                        <button onclick="openMedicalModal(
                                                    {{ $response->id }},
                                                    '{{ addslashes($response->donor->name ?? 'Anonyme') }}',
                                                    '{{ $response->blood_group_needed }}'
                                                )"
                                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs font-bold">
                                            🩺 Faire le test
                                        </button>
                                    @elseif($response->donation_recorded)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">✅ Traité</span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Modal formulaire test médical Native JS --}}
    <div id="medical-test-modal"
         class="js-hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 w-full max-w-lg shadow-2xl mx-4" onclick="event.stopPropagation()">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-dark">Test médical du donneur</h2>
                    <p class="text-sm text-gray-500 mt-1" id="modal-donor-name"></p>
                </div>
                <button onclick="closeMedicalModal()" class="text-gray-400 hover:text-dark text-2xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('centre.donations.store') }}" class="space-y-5" id="medical-test-form">
                @csrf
                <input type="hidden" name="notification_id" id="notif_id" />

                <!-- Date du don -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Date du don</label>
                    <input type="date" name="donation_date" id="donation_date"
                           value="{{ now()->format('Y-m-d') }}"
                           max="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                </div>

                <!-- Groupe sanguin observé -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">Groupe sanguin observé</label>
                    <select name="observed_blood_group" id="observed_blood_group" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Résultat du test -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-3">Résultat du test médical</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="test_result" value="accepted" class="sr-only test-result-radio" required />
                            <div id="div-accepted" class="p-4 rounded-2xl border-2 text-center font-bold transition border-gray-200 text-gray-500 hover:border-green-300">
                                ✅ Accepté<br>
                                <span class="text-xs font-normal">Donneur en bonne santé</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="test_result" value="rejected" class="sr-only test-result-radio" required />
                            <div id="div-rejected" class="p-4 rounded-2xl border-2 text-center font-bold transition border-gray-200 text-gray-500 hover:border-red-300">
                                ❌ Refusé<br>
                                <span class="text-xs font-normal">Problème de santé détecté</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Notes médicales (obligatoires si refusé) -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-2">
                        Notes médicales
                        <span id="notes-asterisk" class="js-hidden text-red-500">*</span>
                        <span id="notes-optional" class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <textarea name="medical_notes" id="medical_notes" rows="3"
                              placeholder="Ex: Taux d'hémoglobine insuffisant, tension artérielle élevée..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:outline-none resize-none text-sm"></textarea>
                </div>

                <!-- Info stock -->
                <div id="info-accepted" class="js-hidden flex items-center gap-2 p-3 bg-green-50 text-green-700 rounded-xl text-sm font-bold">
                    🩸 Le stock sera incrémenté de 1 unité pour le groupe <span id="info-blood-group" class="font-black ml-1"></span>
                </div>
                <div id="info-rejected" class="js-hidden flex items-center gap-2 p-3 bg-red-50 text-red-700 rounded-xl text-sm font-bold">
                    ⚠️ Le stock ne sera pas modifié. Les notes seront enregistrées dans le dossier du donneur.
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeMedicalModal()"
                            class="flex-1 py-3 border border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit" id="submit-btn" disabled
                            class="flex-1 py-3 rounded-xl font-bold text-white transition bg-gray-300 cursor-not-allowed">
                        <span id="btn-text-accepted" class="js-hidden">✅ Valider le don</span>
                        <span id="btn-text-rejected" class="js-hidden">❌ Enregistrer le refus</span>
                        <span id="btn-text-default">Choisir un résultat</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('medical-test-modal');
        const radios = document.querySelectorAll('.test-result-radio');
        const selectBloodGroup = document.getElementById('observed_blood_group');
        
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeMedicalModal();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('js-hidden')) {
                    closeMedicalModal();
                }
            });
        }
        
        if (selectBloodGroup) {
            selectBloodGroup.addEventListener('change', (e) => {
                document.getElementById('info-blood-group').textContent = e.target.value;
            });
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updateFormState);
        });
    });

    function updateFormState() {
        const val = document.querySelector('.test-result-radio:checked')?.value;
        const divAccepted = document.getElementById('div-accepted');
        const divRejected = document.getElementById('div-rejected');
        
        divAccepted.className = 'p-4 rounded-2xl border-2 text-center font-bold transition ' + (val === 'accepted' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-500 hover:border-green-300');
        divRejected.className = 'p-4 rounded-2xl border-2 text-center font-bold transition ' + (val === 'rejected' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-500 hover:border-red-300');

        const notesAst = document.getElementById('notes-asterisk');
        const notesOpt = document.getElementById('notes-optional');
        const notesArea = document.getElementById('medical_notes');
        
        if (val === 'rejected') {
            notesAst.classList.remove('js-hidden');
            notesOpt.classList.add('js-hidden');
            notesArea.required = true;
        } else {
            notesAst.classList.add('js-hidden');
            notesOpt.classList.remove('js-hidden');
            notesArea.required = false;
        }

        document.getElementById('info-accepted').classList.toggle('js-hidden', val !== 'accepted');
        document.getElementById('info-rejected').classList.toggle('js-hidden', val !== 'rejected');

        const btn = document.getElementById('submit-btn');
        const tAcc = document.getElementById('btn-text-accepted');
        const tRej = document.getElementById('btn-text-rejected');
        const tDef = document.getElementById('btn-text-default');

        if (val) {
            btn.disabled = false;
            if (val === 'accepted') {
                btn.className = 'flex-1 py-3 rounded-xl font-bold text-white transition bg-green-600 hover:bg-green-700';
                tAcc.classList.remove('js-hidden');
                tRej.classList.add('js-hidden');
                tDef.classList.add('js-hidden');
            } else {
                btn.className = 'flex-1 py-3 rounded-xl font-bold text-white transition bg-red-600 hover:bg-red-700';
                tAcc.classList.add('js-hidden');
                tRej.classList.remove('js-hidden');
                tDef.classList.add('js-hidden');
            }
        } else {
            btn.disabled = true;
            btn.className = 'flex-1 py-3 rounded-xl font-bold text-white transition bg-gray-300 cursor-not-allowed';
            tAcc.classList.add('js-hidden');
            tRej.classList.add('js-hidden');
            tDef.classList.remove('js-hidden');
        }
    }

    function openMedicalModal(notifId, donorName, bloodGroup) {
        document.getElementById('notif_id').value = notifId;
        document.getElementById('modal-donor-name').textContent = 'Donneur : ' + donorName;
        
        const selectBG = document.getElementById('observed_blood_group');
        selectBG.value = bloodGroup;
        document.getElementById('info-blood-group').textContent = bloodGroup;
        
        document.querySelectorAll('.test-result-radio').forEach(r => r.checked = false);
        document.getElementById('medical_notes').value = '';
        
        updateFormState();
        document.getElementById('medical-test-modal').classList.remove('js-hidden');
    }

    function closeMedicalModal() {
        document.getElementById('medical-test-modal').classList.add('js-hidden');
    }
</script>
@endsection
