<template>
  <div class="min-h-screen flex items-center justify-center bg-red-pale/30 px-4 py-12">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl p-8 border border-red-50">

      <div class="text-center mb-8">
        <div class="w-16 h-16 rounded-full bg-red-pale flex items-center justify-center mx-auto mb-4">
          <svg viewBox="0 0 24 24" class="w-8 h-8 fill-red-mid">
            <path d="M12 2C8 7 4 10.5 4 14a8 8 0 0016 0c0-3.5-4-7-8-12z" />
          </svg>
        </div>
        <h2 class="font-display text-3xl font-bold text-dark">Créer un compte</h2>
        <p class="text-gray-500 mt-2">Rejoignez le réseau HemoLife et sauvez des vies.</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">

        <div>
          <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Nom complet</label>
          <input v-model="form.name" type="text" placeholder="Ex: Ahmed Benali"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all"
            required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Email</label>
            <input v-model="form.email" type="email" placeholder="ahmed@mail.ma"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all"
              required />
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Téléphone</label>
            <input v-model="form.phone" type="tel" placeholder="06XXXXXXXX"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all"
              required />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Ville</label>
            <input v-model="form.city" type="text" placeholder="Ex: Marrakech"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all"
              required />
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Groupe
              Sanguin</label>
            <select v-model="form.blood_group"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all appearance-none"
              required>
              <option value="Unknown">Inconnu</option>
              <option v-for="group in ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']" :key="group" :value="group">{{
                group }}</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Mot de passe</label>
          <input v-model="form.password" type="password" placeholder="••••••••"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all"
            required />
        </div>

        <button type="submit"
          class="w-full bg-red-500 hover:bg-red-deep text-white font-bold py-4 px-4 rounded-2xl shadow-lg shadow-red-200 transition-all transform hover:-translate-y-1 mt-4">
          S'inscrire comme donneur
        </button>

        <p class="text-center text-sm text-gray-500 mt-6">
          Déjà membre ?
          <router-link to="/login"
            class="text-red-mid hover:text-red-deep font-bold underline-offset-4 hover:underline">
            Connectez-vous ici
          </router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api.js';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  phone: '',
  city: '',
  blood_group: 'Unknown',
  password: ''
});

const handleRegister = async () => {
  try {
    
    await api.post('/register', form.value);                            
    alert("Bienvenue parmi nous ! Votre compte a été créé.");
    router.push('/login');
  } catch (error) {
  console.log("ERROR:", error.response);

  if (error.response?.data?.errors) {
    alert(
      "Données invalides :\n" +
      Object.values(error.response.data.errors).flat().join('\n')
    );
  } else {
    alert(error.response?.data?.message || "Erreur serveur");
  }
}
};
</script>