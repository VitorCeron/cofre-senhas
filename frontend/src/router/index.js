import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import ListPasswords from '../views/passwords/ListPasswords.vue'
import CreatePassword from '../views/passwords/CreatePassword.vue'
import UpdatePassword from '../views/passwords/UpdatePassword.vue'

import ListLogs from '../views/logs/ListLogs.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      notAuth: true
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: {
      notAuth: true
    }
  },
  {
    path: '/list_passwords',
    name: 'ListPasswords',
    component: ListPasswords,
    meta: {
      hasAuth: true
    }
  },
  {
    path: '/create_password',
    name: 'CreatePassword',
    component: CreatePassword,
    meta: {
      hasAuth: true
    }
  },
  {
    path: '/update_password',
    name: 'UpdatePassword',
    component: UpdatePassword,
    meta: {
      hasAuth: true
    }
  },
  {
    path: '/list_logs',
    name: 'ListLogs',
    component: ListLogs,
    meta: {
      hasAuth: true
    }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
