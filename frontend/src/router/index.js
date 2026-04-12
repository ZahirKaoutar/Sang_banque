import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue' 
import Profile from '../views/Profile.vue' 



const routes = [
  {
    path: '/home',
    name: 'home',
    component: Home
  },
 
  {
    path: '/register',
    name: 'register',
    component: Register
  },
    {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router