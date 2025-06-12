<template>
  <div class="purchases-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-2">My Purchases</h1>
            <p class="lead text-muted mb-0">
              Track and manage your orders - {{ userOrders.length }} total orders
            </p>
          </div>
          <div class="col-md-4 text-md-end">
            <div class="purchase-actions">
              <router-link to="/products" class="btn btn-eco-primary">
                <i class="bi bi-cart-plus me-2"></i>Continue Shopping
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-4">
      <!-- Filter and Search Bar -->
      <div class="orders-controls mb-4" v-if="userOrders.length > 0">
        <div class="row g-3 align-items-center">
          <!-- Search -->
          <div class="col-md-4">
            <div class="search-box position-relative">
              <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input 
                type="text" 
                class="form-control ps-5" 
                placeholder="Search orders..."
                v-model="searchQuery"
                @input="filterOrders"
              >
            </div>
          </div>

          <!-- Status Filter -->
          <div class="col-md-3">
            <select 
              class="form-select" 
              v-model="statusFilter" 
              @change="filterOrders"
            >
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="shipped">Shipped</option>
              <option value="delivered">Delivered</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Date Range -->
          <div class="col-md-3">
            <select 
              class="form-select" 
              v-model="dateFilter" 
              @change="filterOrders"
            >
              <option value="all">All Time</option>
              <option value="30">Last 30 Days</option>
              <option value="90">Last 3 Months</option>
              <option value="365">Last Year</option>
            </select>
          </div>

          <!-- Results Count -->
          <div class="col-md-2 text-md-end">
            <small class="text-muted">
              {{ filteredOrders.length }} orders
            </small>
          </div>
        </div>
      </div>

      <!-- Orders List -->
      <div class="orders-list" v-if="filteredOrders.length > 0">
        <div 
          class="order-card card shadow-sm mb-4" 
          v-for="order in filteredOrders" 
          :key="order.id"
        >
          <!-- Order Header -->
          <div class="card-header bg-white">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h6 class="mb-1 fw-bold">
                  <i class="bi bi-receipt text-success me-2"></i>
                  Order #{{ order.orderNumber }}
                </h6>
                <small class="text-muted">
                  Placed on {{ formatDate(order.orderDate) }}
                </small>
              </div>
              <div class="col-md-3 text-md-center">
                <span 
                  class="badge fs-6 px-3 py-2"
                  :class="getStatusBadgeClass(order.status)"
                >
                  <i :class="getStatusIcon(order.status)" class="me-1"></i>
                  {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                </span>
              </div>
              <div class="col-md-3 text-md-end">
                <div class="order-actions">
                  <button 
                    class="btn btn-outline-danger btn-sm"
                    @click="deleteOrder(order.id, order.orderNumber)"
                    v-if="order.status === 'pending' || order.status === 'cancelled'"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Body -->
          <div class="card-body">
            <div class="row">
              <!-- Order Items -->
              <div class="col-md-8">
                <h6 class="fw-bold mb-3">Items Ordered</h6>
                <div class="order-items">
                  <div 
                    class="item-row d-flex align-items-center mb-3 pb-3 border-bottom"
                    v-for="item in order.items" 
                    :key="item.id"
                  >
                    <img 
                      :src="item.image" 
                      :alt="item.name"
                      class="item-image me-3"
                    >
                    <div class="item-details flex-grow-1">
                      <h6 class="mb-1">{{ item.name }}</h6>
                      <p class="text-muted mb-1 small">{{ item.brand }}</p>
                      <div class="item-meta">
                        <span class="text-muted small">
                          Qty: {{ item.quantity }} × RM{{ formatPrice(item.price) }}
                        </span>
                      </div>
                    </div>
                    <div class="item-total">
                      <span class="fw-bold text-success">
                        RM{{ formatPrice(item.price * item.quantity) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Order Summary -->
              <div class="col-md-4">
                <div class="order-summary bg-light p-3 rounded">
                  <h6 class="fw-bold mb-3">Order Summary</h6>
                  
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>RM{{ formatPrice(order.subtotal) }}</span>
                  </div>
                  
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>RM{{ formatPrice(order.shipping) }}</span>
                  </div>
                  
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span>Tax:</span>
                    <span>RM{{ formatPrice(order.tax) }}</span>
                  </div>
                  
                  <div class="summary-row d-flex justify-content-between mb-3" v-if="order.discount > 0">
                    <span class="text-success">Discount:</span>
                    <span class="text-success">-RM{{ formatPrice(order.discount) }}</span>
                  </div>
                  
                  <hr>
                  
                  <div class="summary-total d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold text-success h6">RM{{ formatPrice(order.total) }}</span>
                  </div>

                  <!-- Shipping Address -->
                  <div class="shipping-address mt-3">
                    <h6 class="fw-bold mb-2">
                      <i class="bi bi-truck me-1"></i>Shipping Address
                    </h6>
                    <address class="small text-muted mb-0">
                      {{ order.shippingAddress.name }}<br>
                      {{ order.shippingAddress.address }}<br>
                      {{ order.shippingAddress.city }}, {{ order.shippingAddress.state }}<br>
                      {{ order.shippingAddress.postalCode }}
                    </address>
                  </div>

                  <!-- Order Actions -->
                  <div class="order-actions-detailed mt-3">
                    <div class="d-grid gap-2">
                      <button 
                        class="btn btn-eco-primary btn-sm"
                        @click="trackOrder(order)"
                      >
                        <i class="bi bi-geo-alt me-1"></i>Track Order
                      </button>
                      <button 
                        class="btn btn-outline-secondary btn-sm"
                        @click="reorderItems(order)"
                      >
                        <i class="bi bi-arrow-repeat me-1"></i>Reorder
                      </button>
                      <button 
                        class="btn btn-outline-info btn-sm"
                        @click="downloadInvoice(order)"
                      >
                        <i class="bi bi-download me-1"></i>Download Invoice
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- No Orders State -->
      <div v-else class="no-orders text-center py-5">
        <div class="no-orders-icon mb-4">
          <i class="bi bi-bag-x display-1 text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">No orders found</h3>
        <p class="text-muted mb-4">
          {{ searchQuery || statusFilter !== 'all' ? 'Try adjusting your filters' : 'You haven\'t placed any orders yet' }}
        </p>
        <router-link to="/products" class="btn btn-eco-primary btn-lg">
          <i class="bi bi-arrow-left me-2"></i>Start Shopping
        </router-link>
      </div>
    </div>

    <!-- Toast Notifications -->
    <ToastNotification
      :show="toast.show"
      :message="toast.message"
      :type="toast.type"
      @hide="hideToast"
    />
  </div>
</template>

<script>
import { useOrdersStore } from '@/stores/ordersStore'
import { useCartStore } from '@/stores/cartStore'
import ToastNotification from '@/components/ToastNotification.vue'

export default {
  name: 'PurchasesView',
  components: {
    ToastNotification
  },
  setup() {
    const ordersStore = useOrdersStore()
    const cartStore = useCartStore()
    return { ordersStore, cartStore }
  },
  data() {
    return {
      // Filter states
      searchQuery: '',
      statusFilter: 'all',
      dateFilter: 'all',
      
      filteredOrders: [],

      // Toast notification
      toast: {
        show: false,
        message: '',
        type: 'success'
      }
    }
  },

  computed: {
    userOrders() {
      return this.ordersStore.userOrders
    }
  },
  
  methods: {
    // Filter orders based on search and filters
    filterOrders() {
      let filtered = [...this.userOrders]
      
      // Search filter
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(order => 
          order.orderNumber.toLowerCase().includes(query) ||
          order.items.some(item => 
            item.name.toLowerCase().includes(query) ||
            item.brand.toLowerCase().includes(query)
          )
        )
      }
      
      // Status filter
      if (this.statusFilter !== 'all') {
        filtered = filtered.filter(order => order.status === this.statusFilter)
      }
      
      // Date filter
      if (this.dateFilter !== 'all') {
        const days = parseInt(this.dateFilter)
        const cutoffDate = new Date()
        cutoffDate.setDate(cutoffDate.getDate() - days)
        
        filtered = filtered.filter(order => {
          const orderDate = new Date(order.orderDate)
          return orderDate >= cutoffDate
        })
      }
      
      this.filteredOrders = filtered
    },
    
    // Delete order - UPDATED WITH TOAST
    deleteOrder(orderId, orderNumber) {
      try {
        const success = this.ordersStore.deleteOrder(orderId)
        if (success) {
          this.showToast(`Order #${orderNumber} deleted successfully`, 'success')
          this.filterOrders() // Refresh the filtered list
        } else {
          this.showToast('Cannot delete this order. Only pending or cancelled orders can be deleted.', 'warning')
        }
      } catch (error) {
        this.showToast('Error deleting order', 'error')
      }
    },
    
    // Track order - UPDATED WITH TOAST
    trackOrder(order) {
      const trackingMessages = {
        pending: 'Your order is being prepared for processing.',
        processing: 'Your order is being processed and will ship soon.',
        shipped: 'Your order is on the way! Expected delivery in 2-3 business days.',
        delivered: 'Your order has been delivered successfully!',
        cancelled: 'This order has been cancelled.'
      }

      const message = trackingMessages[order.status] || 'Tracking information unavailable.'
      this.showToast(`Order #${order.orderNumber}: ${message}`, 'info')
    },
    
    // Reorder items - UPDATED WITH TOAST
    reorderItems(order) {
      try {
        // Add each item from the order to the cart
        order.items.forEach(item => {
          const product = {
            id: item.id,
            name: item.name,
            price: item.price,
            originalPrice: item.originalPrice || item.price,
            image: item.image,
            brand: item.brand,
            inStock: true // Assume in stock for reorder
          }
          this.cartStore.addItem(product, item.quantity)
        })

        this.showToast(`${order.items.length} items added to cart from Order #${order.orderNumber}`, 'success')
        
        // Navigate to cart after a short delay
        setTimeout(() => {
          this.$router.push('/cart')
        }, 1500)
      } catch (error) {
        this.showToast('Error adding items to cart', 'error')
      }
    },
    
    // Download invoice - UPDATED WITH TOAST
    downloadInvoice(order) {
      this.showToast(`Invoice for Order #${order.orderNumber} would be downloaded in a real application`, 'info')
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
    },
    
    // Utility methods
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      })
    },
    
    formatPrice(price) {
      return price.toFixed(2)
    },
    
    getStatusBadgeClass(status) {
      const classes = {
        pending: 'bg-warning text-dark',
        processing: 'bg-info text-white',
        shipped: 'bg-primary text-white',
        delivered: 'bg-success text-white',
        cancelled: 'bg-danger text-white'
      }
      return classes[status] || 'bg-secondary text-white'
    },
    
    getStatusIcon(status) {
      const icons = {
        pending: 'bi bi-clock-history',
        processing: 'bi bi-gear-fill',
        shipped: 'bi bi-truck',
        delivered: 'bi bi-check-circle-fill',
        cancelled: 'bi bi-x-circle-fill'
      }
      return icons[status] || 'bi bi-circle'
    }
  },
  
  // Initialize when component mounts
  mounted() {
    // Initialize orders store
    this.ordersStore.initializeOrders()
    
    // Filter orders after initialization
    this.filterOrders()
  }
}
</script>

<style scoped>
/* Order card styling */
.order-card {
  border: none;
  border-radius: 0.75rem;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Item image */
.item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 0.5rem;
}

/* Order summary */
.order-summary {
  border-left: 4px solid var(--eco-primary);
}

.summary-row {
  font-size: 0.9rem;
}

/* Status badges */
.badge {
  font-size: 0.8rem;
}

/* Search box */
.search-box i {
  z-index: 5;
}

/* No orders state */
.no-orders-icon i {
  font-size: 5rem;
}

/* Button styling */
.btn-eco-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3);
}

/* Responsive design */
@media (max-width: 768px) {
  .order-actions {
    margin-top: 1rem;
    text-align: center;
  }
  
  .purchase-actions {
    text-align: center;
    margin-top: 1rem;
  }
  
  .item-row {
    flex-direction: column;
    text-align: center;
  }
  
  .item-image {
    margin-bottom: 1rem;
  }
}

/* Animation */
.order-card {
  animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>