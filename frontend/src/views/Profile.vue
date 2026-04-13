<template>
  <div class="min-h-screen bg-gray-100 py-10 px-4">
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="flex flex-col items-center gap-3">
        <div class="w-10 h-10 border-4 border-red-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-gray-400 text-sm">Chargement...</p>
      </div>
    </div>

    <div v-else-if="user" class="max-w-3xl mx-auto space-y-4">

     
      <div class="bg-white rounded-2xl overflow-hidden border border-gray-100">
        <div class="h-32 bg-red-600 relative">
          <div class="absolute inset-0 opacity-10"
            style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%); background-size: 12px 12px;">
          </div>
        </div>
        <div class="px-6 pb-6">
          <div class="flex items-end justify-between -mt-10 mb-4">
            <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-md flex items-center justify-center text-red-600 text-2xl font-bold ring-1 ring-red-100">
              {{ user.blood_group }}
            </div>
            <span class="mb-1 inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-green-200">
              <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
              Disponible
            </span>
          </div>
          <h1 class="text-xl font-bold text-gray-900">{{ user.name }}</h1>
          <p class="text-sm text-gray-400 mt-0.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
              <circle cx="12" cy="9" r="2.5"/>
            </svg>
            {{ user.city }}
          </p>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
          <p class="text-3xl font-bold text-red-600">{{ user.donations_count }}</p>
          <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">Dons</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
          <p class="text-lg font-semibold text-gray-800">{{ user.blood_group }}</p>
          <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">Groupe</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
          <p class="text-sm font-semibold text-gray-800 leading-tight">
            {{ user.donations_date ? formatDate(user.donations_date) : '—' }}
          </p>
          <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">Dernier don</p>
        </div>
      </div>

      <!-- HISTORIQUE -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-sm font-semibold text-gray-700">Historique des dons</h2>
          <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
            {{ user.donations?.length ?? 0 }} don{{ (user.donations?.length ?? 0) > 1 ? 's' : '' }}
          </span>
        </div>

        <div v-if="user.donations && user.donations.length > 0">
          <div v-for="(donation, index) in user.donations" :key="donation.id"
            class="flex items-center justify-between px-6 py-3.5 hover:bg-gray-50 transition-colors"
            :class="index !== user.donations.length - 1 ? 'border-b border-gray-50' : ''">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C9.5 2 5 9 5 14a7 7 0 0014 0C19 9 14.5 2 12 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">{{ formatDate(donation.donation_date) }}</p>
                <p class="text-xs text-gray-400">Don de sang</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs text-green-600 bg-green-50 border border-green-100 px-2.5 py-1 rounded-full font-medium">
                Accepté
              </span>
              <span class="text-xs text-gray-300 font-mono">#{{ donation.id }}</span>
            </div>
          </div>
        </div>

        <div v-else class="flex flex-col items-center justify-center py-14 text-center">
          <div class="w-12 h-12 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" d="M12 2C9.5 2 5 9 5 14a7 7 0 0014 0C19 9 14.5 2 12 2z"/>
            </svg>
          </div>
          <p class="text-sm text-gray-400">Aucun don enregistré</p>
          <p class="text-xs text-gray-300 mt-1">L'historique apparaîtra ici</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api.js';
import { useRoute } from 'vue-router';

const route = useRoute();
const user = ref(null);
const loading = ref(true);

const fetchProfile = async () => {
  try {
    const id = route.params.id;
    const response = await api.get(`profile/${id}`);
    let data = response.data;
    if (typeof data === 'string') {
      const jsonStart = data.indexOf('{');
      if (jsonStart !== -1) data = JSON.parse(data.substring(jsonStart));
    }
    user.value = data;
  } catch (error) {
    console.error('Erreur profil:', error);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric', month: 'short', year: 'numeric'
  });
};

onMounted(fetchProfile);
</script>