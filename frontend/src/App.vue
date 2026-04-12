<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png">
  <HelloWorld msg="Welcome to Your Vue.js App"/> -->
  
   
<Navbar/>
  

<router-view />
  
</template>
<script>
import Navbar from './components/Navbar.vue';

export default {
 name: 'App',
 components: {
   Navbar,
  
 }
}

</script>