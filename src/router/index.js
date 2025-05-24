import { createRouter, createWebHistory } from 'vue-router'

// Import all our page components
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: 'EcoGear - Sustainable Outdoor Equipment' }
    },
    {
      path: '/products',
      name: 'products',
      component: () => import('../views/ProductsView.vue'),
      meta: { title: 'Products - EcoGear' }
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/CartView.vue'),
      meta: { title: 'Shopping Cart - EcoGear' }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { title: 'Register - EcoGear' }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { title: 'Login - EcoGear' }
    },
    {
      path: '/account',
      name: 'account',
      component: () => import('../views/AccountView.vue'),
      meta: { title: 'My Account - EcoGear' }
    },
    {
      path: '/purchases',
      name: 'purchases',
      component: () => import('../views/PurchasesView.vue'),
      meta: { title: 'My Purchases - EcoGear' }
    }
  ]
})

// Update page title on route change
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || 'EcoGear'
  next()
})

export default router