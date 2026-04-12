<template>
  <nav class="fixed top-0 w-full bg-white border-b border-red-100 px-6 py-4 flex justify-between items-center z-50">
    
    <router-link to="/" class="flex items-center gap-2">
      <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">H</div>
      <span class="text-xl font-bold text-red-800">HemoLife</span>
    </router-link>

    <div class="hidden md:flex gap-6">
     
      
      <router-link v-if="isLoggedIn" to="/donors" class="text-gray-600 hover:text-red-600">Donneurs</router-link>
    </div>

    <div class="flex items-center gap-4">
      
      <template v-if="!isLoggedIn">
        <router-link to="/login" class="text-gray-600 text-sm font-semibold">Connexion</router-link>
        <router-link to="/register" class="bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold">Rejoindre</router-link>
      </template>

      <template v-else>
        <span class="text-sm font-medium">Bonjour, <b class="text-red-600">{{ userName }}</b></span>
        <button @click="logout" class="text-sm font-bold text-gray-500 border-l pl-4">Quitter</button>
      </template>

    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const isLoggedIn = ref(false);
const userName = ref('');


onMounted(() => {
  const token = localStorage.getItem('token');
  if (token) {
    isLoggedIn.value = true;
    userName.value = localStorage.getItem(user.name) || 'Utilisateur';
  }
});


const logout = () => {
  localStorage.clear(); 
  isLoggedIn.value = false;
  router.push('/login'); }
</script>