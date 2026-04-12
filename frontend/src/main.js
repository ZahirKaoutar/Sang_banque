import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // 1. On importe ton fichier de configuration router
import './style.css' // Si tu as du CSS (Tailwind, etc.)

const app = createApp(App)

app.use(router) 

app.mount('#app')