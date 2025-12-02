import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import Login from '@/pages/Login.vue'
import Dashboard from '@/pages/Dashboard.vue'
import User from '@/pages/User.vue'
// import Vendors from '@/views/vendors/VendorList.vue'
// import Invoices from '@/views/invoices/InvoiceList.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login, meta: { guest: true } },

    {
      path: '/dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },

    {
      path: '/users',
      component: User,
      meta: { requiresAuth: true }
    },

    // {
    //   path: '/vendors',
    //   component: Vendors,
    //   meta: { requiresAuth: true }
    // },

    // {
    //   path: '/invoices',
    //   component: Invoices,
    //   meta: { requiresAuth: true }
    // },
  ]
})

router.beforeEach(async (to, _, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth) {
    if (!auth.token) return next('/login')

    if (!auth.user) {
      await auth.fetchUser().catch(() => next('/login'))
    }
  }

  if (to.meta.guest && auth.token) return next('/dashboard')

  next()
})

export default router
