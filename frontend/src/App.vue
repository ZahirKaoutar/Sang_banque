<script setup>

</script>

<template>
  <div id="app">
    <nav class="p-4 bg-white shadow flex gap-4">
      <router-link to="/home" class="text-red-600 font-bold">Accueil</router-link>
      <router-link to="/about" class="text-gray-600">À Propos</router-link>
    </nav>

    <router-view />
    
  </div>
</template>
