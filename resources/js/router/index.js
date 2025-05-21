// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Plans from '../views/Plans.Vue'
import Dashboard from '../views/Dashboard.vue'
import Signature from '../views/Signature.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/planos', component: Plans },
    { path: '/login', component: Login },
    { path: '/assinar/:id', component: Signature },
    { path: '/dashboard', component: Dashboard },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token') != null

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login')
    } else {
        next()
    }
})

export default router
