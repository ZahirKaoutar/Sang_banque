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

      <form @submit.prevent="handleLogin" class="space-y-4">

        

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Email</label>
            <input v-model="form.email" type="email" placeholder="ahmed@mail.ma"
              :class="{'border-red-500 ring-2 ring-red-100': errors?.email}"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all" />
            <p v-if="errors?.email" class="text-red-500 text-xs mt-1 ml-1">{{ errors.email[0] }}</p>
          </div>

        </div>

        <div>
          <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 ml-1">Mot de passe</label>
          <input v-model="form.password" type="password" placeholder="••••••••"
            :class="{'border-red-500 ring-2 ring-red-100': errors?.password}"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none transition-all" />
          <p v-if="errors?.password" class="text-red-500 text-xs mt-1 ml-1">{{ errors.password[0] }}</p>
        </div>

        <button type="submit"
          class="w-full bg-red-500 hover:bg-red-deep text-white font-bold py-4 px-4 rounded-2xl shadow-lg shadow-red-200 transition-all transform hover:-translate-y-1 mt-4">
          forgot password
        </button>

        <p class="text-center text-sm text-gray-500 mt-6">
         
          pas de compte ?
          <router-link to="/register"
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
  email: '',

  password: ''
});
const errors = ref({
  
  email: '',


  password: ''
});


const handleLogin = async () => {
  try {
    errors.value={}
  
    const response = await api.post('/login', form.value);

    
    localStorage.setItem('access_token', response.data.access_token);
    
    alert("Bienvenue parmi nous !"); 
    
    router.push('/profile');

  } catch (error) {
    // 3. Si l'API renvoie une erreur (401, 500, etc.), le code saute directement ici
    if (error.response && error.response.status === 422) {
        let data=error.response.data;
        
    
  if (typeof data === 'string') {
        try {
          
          const jsonString = data.substring(data.indexOf('{'));
          data = JSON.parse(jsonString);
        } catch (e) {
          console.error("Erreur de nettoyage JSON", e);
        }
      }

    
      errors.value = data.errors || {};
    } else {
      alert("Erreur serveur");
    }
  }
}

</script>