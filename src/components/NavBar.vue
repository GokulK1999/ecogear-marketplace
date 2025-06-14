<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <!-- Brand Logo -->
      <router-link class="navbar-brand d-flex align-items-center" to="/">
        <i class="bi bi-leaf-fill text-success me-2 fs-3"></i>
        <span class="fw-bold">EcoGear</span>
      </router-link>

      <!-- Mobile Menu Toggle -->
      <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbarNav"
        aria-controls="navbarNav" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation Links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/" active-class="active">
              <i class="bi bi-house me-1"></i>Home
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/products" active-class="active">
              <i class="bi bi-grid me-1"></i>Products
            </router-link>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-list me-1"></i>Categories
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/products"><i class="bi bi-tree me-2"></i>Camping Gear</a></li>
              <li><a class="dropdown-item" href="/products"><i class="bi bi-backpack me-2"></i>Hiking Equipment</a></li>
              <li><a class="dropdown-item" href="/products"><i class="bi bi-water me-2"></i>Water Sports</a></li>
              <li><a class="dropdown-item" href="/products"><i class="bi bi-sun me-2"></i>Solar Tech</a></li>
            </ul>
          </li>
        </ul>

        <!-- Right Side Actions -->
        <div class="navbar-nav">
          <!-- Cart Icon with Badge -->
          <router-link class="nav-link position-relative me-3" to="/cart">
            <i class="bi bi-cart3 fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" v-if="cartItemCount > 0">
                {{ cartItemCount }}
            </span>
          </router-link>

          <!-- User Menu -->
          <div class="nav-item dropdown" v-if="!isLoggedIn">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person me-1"></i>Account
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link class="dropdown-item" to="/login">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </router-link>
              </li>
              <li>
                <router-link class="dropdown-item" to="/register">
                  <i class="bi bi-person-plus me-2"></i>Register
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Logged In User Menu -->
          <div class="nav-item dropdown" v-else>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i>{{ userName }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link class="dropdown-item" to="/account">
                  <i class="bi bi-person-gear me-2"></i>My Account
                </router-link>
              </li>
              <li>
                <router-link class="dropdown-item" to="/purchases">
                  <i class="bi bi-bag me-2"></i>My Purchases
                </router-link>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="#" @click="logout">
                  <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <ToastNotification
      :show="toast.show"
      :message="toast.message"
      :type="toast.type"
      @hide="hideToast"
    />
  </nav>
</template>

<script>
import { useCartStore } from '@/stores/cartStore'
import ToastNotification from '@/components/ToastNotification.vue'

export default {
  name: 'NavBar',
  components: {
    ToastNotification
  },
  setup() {
    const cartStore = useCartStore()
    return { cartStore }
  },
  data() {
    return {
      isLoggedIn: false,
      userName: '',
      // Toast notification
      toast: {
        show: false,
        message: '',
        type: 'success'
      }
    }
  },
  computed: {
    cartItemCount() {
      return this.cartStore.itemCount
    }
  },
  methods: {
    // Check if user is logged in
    checkAuthStatus() {
      const userData = localStorage.getItem('ecogear_user')
      if (userData) {
        try {
          const user = JSON.parse(userData)
          this.isLoggedIn = true
          this.userName = user.name || 'User'
        } catch (error) {
          console.error('Error parsing user data:', error)
          this.logout()
        }
      } else {
        this.isLoggedIn = false
        this.userName = ''
      }
    },

    // Handle logout - UPDATED WITH TOAST
    logout() {
      // Clear localStorage
      localStorage.removeItem('ecogear_user')
      
      // Update navbar state
      this.isLoggedIn = false
      this.userName = ''
      
      // Redirect to home page
      this.$router.push('/')
      
      // Show logout toast message
      this.showToast('You have been logged out successfully! 👋', 'success')
      
      // Trigger custom event for other components
      window.dispatchEvent(new Event('userLoggedOut'))
    },

    // Show toast notification  
    showToast(message, type = 'success') {
      this.toast = {
        show: true,
        message,
        type
      }
    },

    // Hide toast notification
    hideToast() {
      this.toast.show = false
    }
  },
  
  mounted() {
    // Check auth status when component mounts
    this.checkAuthStatus()
    
    // Listen for login events
    window.addEventListener('userLoggedIn', this.checkAuthStatus)
    window.addEventListener('userLoggedOut', this.checkAuthStatus)
  },
  
  beforeUnmount() {
    // Clean up event listeners
    window.removeEventListener('userLoggedIn', this.checkAuthStatus)
    window.removeEventListener('userLoggedOut', this.checkAuthStatus)
  }
}
</script>

<style scoped>
.navbar-brand {
  font-size: 1.5rem;
}

.nav-link {
  font-weight: 500;
  transition: all 0.3s ease;
}

.nav-link:hover {
  color: var(--eco-primary) !important;
  transform: translateY(-1px);
}

.nav-link.active {
  color: var(--eco-primary) !important;
  font-weight: 600;
}

.dropdown-menu {
  border: none;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  border-radius: 0.5rem;
}

.dropdown-item {
  padding: 0.75rem 1.25rem;
  transition: all 0.3s ease;
}

.dropdown-item:hover {
  background-color: var(--eco-light);
  color: var(--eco-primary);
}

.badge {
  font-size: 0.7rem;
}

@media (max-width: 991px) {
  .navbar-nav {
    text-align: center;
  }
  
  .navbar-nav .nav-link {
    padding: 0.75rem 0;
  }
}
</style>