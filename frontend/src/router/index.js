import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'



const routes = [
  {
    path: '/home',
    name: 'home',
    component: Home
  },
 
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router