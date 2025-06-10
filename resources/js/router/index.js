// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Plans from '../views/Plans.Vue'
import Dashboard from '../views/Dashboard.vue'
import Signature from '../views/Signature.vue'
import Forgot from '../views/Forgot.vue'
import ChangePassword from '../views/ChangePassword.vue'
import Welcome from '../views/Welcome.vue'
import Assinaturas from '../views/Dashboard/Assinaturas.vue'
import Afiliados from '../views/Dashboard/Afiliados.vue'
import Rolers from '../views/Dashboard/Rolers.vue'
import PerfilAffiliate from '../views/Dashboard/PerfilAffiliate.vue'
import Clientes from '../views/Dashboard/Clientes.vue'
import Assinatura from '../views/Dashboard/Assinatura.vue'
import Estoque from '../views/Dashboard/Estoque.vue'
import Empresas from '../views/Dashboard/Empresas.vue'
import Categorias from '../views/Dashboard/Categorias.vue'
import Fornecedores from '../views/Dashboard/Fornecedores.vue'
import Template from '../views/Dashboard/Template.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/planos', component: Plans },
    { path: '/login', component: Login },
    { path: '/welcome', component: Welcome },
    { path: '/forgot', component: Forgot },
    { path: '/change-password', component: ChangePassword },
    { path: '/assinar/:id', component: Signature },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/dashboard/assinaturas', component: Assinaturas, meta: { requiresAuth: true } },
    { path: '/dashboard/afiliados', component: Afiliados, meta: { requiresAuth: true } },
    { path: '/dashboard/roler', component: Rolers, meta: { requiresAuth: true } },
    { path: '/dashboard/perfil-afiliado', component: PerfilAffiliate, meta: { requiresAuth: true } },
    { path: '/dashboard/clientes', component: Clientes, meta: { requiresAuth: true } },
    { path: '/dashboard/assinatura', component: Assinatura, meta: { requiresAuth: true } },
    { path: '/dashboard/estoque', component: Estoque, meta: { requiresAuth: true } },
    { path: '/dashboard/empresas', component: Empresas, meta: { requiresAuth: true } },
    { path: '/dashboard/categorias', component: Categorias, meta: { requiresAuth: true } },
    { path: '/dashboard/fornecedores', component: Fornecedores, meta: { requiresAuth: true } },
    { path: '/dashboard/template', component: Template, meta: { requiresAuth: true } },
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
