<template>
  <div class="purchases-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-2">My Purchases</h1>
            <p class="lead text-muted mb-0">
              Track and manage your orders - {{ orders.length }} total orders
            </p>
          </div>
          <div class="col-md-4 text-md-end">
            <div class="purchase-actions">
              <button class="btn btn-eco-primary" @click="showAddOrderModal">
                <i class="bi bi-plus-circle me-2"></i>Add Order
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-4">
      <!-- Filter and Search Bar -->
      <div class="orders-controls mb-4">
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
                    class="btn btn-outline-primary btn-sm me-2"
                    @click="editOrder(order)"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button 
                    class="btn btn-outline-danger btn-sm"
                    @click="deleteOrder(order.id)"
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

    <!-- Add/Edit Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="bi bi-plus-circle text-success me-2"></i>
              {{ editingOrder ? 'Edit Order' : 'Add New Order' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveOrder">
              <div class="row g-3">
                <!-- Order Number -->
                <div class="col-md-6">
                  <label class="form-label">Order Number</label>
                  <input 
                    type="text" 
                    class="form-control"
                    v-model="orderForm.orderNumber"
                    required
                  >
                </div>
                
                <!-- Status -->
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" v-model="orderForm.status" required>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
                
                <!-- Order Date -->
                <div class="col-md-6">
                  <label class="form-label">Order Date</label>
                  <input 
                    type="date" 
                    class="form-control"
                    v-model="orderForm.orderDate"
                    required
                  >
                </div>
                
                <!-- Total Amount -->
                <div class="col-md-6">
                  <label class="form-label">Total Amount (RM)</label>
                  <input 
                    type="number" 
                    step="0.01"
                    class="form-control"
                    v-model="orderForm.total"
                    required
                  >
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-eco-primary" @click="saveOrder">
              <i class="bi bi-check-circle me-1"></i>
              {{ editingOrder ? 'Update Order' : 'Add Order' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PurchasesView',
  data() {
    return {
      // Filter states
      searchQuery: '',
      statusFilter: 'all',
      dateFilter: 'all',
      
      // Modal states
      editingOrder: null,
      orderForm: {
        orderNumber: '',
        status: 'pending',
        orderDate: '',
        total: 0
      },
      
      // Orders data - demonstrates arrays and data management
      orders: [
        {
          id: 1,
          orderNumber: 'ECO-2024-001',
          orderDate: '2024-01-15',
          status: 'delivered',
          subtotal: 1597.90,
          shipping: 46.95,
          tax: 131.59,
          discount: 0,
          total: 1776.44,
          items: [
            {
              id: 1,
              name: 'EcoTent Pro 4',
              brand: 'EcoGear',
              price: 1409.95,
              quantity: 1,
              image: '/src/assets/images/eco-tent.jpg'
            },
            {
              id: 2,
              name: 'Bamboo Water Bottle',
              brand: 'BambooLife',
              price: 164.45,
              quantity: 1,
              image: '/src/assets/images/bamboo-bottle.jpg'
            }
          ],
          shippingAddress: {
            name: 'John Doe',
            address: '123 Jalan Sustainable',
            city: 'Kuala Lumpur',
            state: 'Kuala Lumpur',
            postalCode: '50000'
          }
        },
        {
          id: 2,
          orderNumber: 'ECO-2024-002',
          orderDate: '2024-02-03',
          status: 'shipped',
          subtotal: 892.95,
          shipping: 46.95,
          tax: 75.19,
          discount: 89.30,
          total: 925.79,
          items: [
            {
              id: 1,
              name: 'Solar Backpack 35L',
              brand: 'SolarTrek',
              price: 892.95,
              quantity: 1,
              image: '/src/assets/images/solar-backpack.jpg'
            }
          ],
          shippingAddress: {
            name: 'John Doe',
            address: '123 Jalan Sustainable',
            city: 'Kuala Lumpur',
            state: 'Kuala Lumpur',
            postalCode: '50000'
          }
        },
        {
          id: 3,
          orderNumber: 'ECO-2024-003',
          orderDate: '2024-02-20',
          status: 'processing',
          subtotal: 751.90,
          shipping: 46.95,
          tax: 63.91,
          discount: 0,
          total: 862.76,
          items: [
            {
              id: 1,
              name: 'Solar Power Bank',
              brand: 'SolarCharge',
              price: 375.95,
              quantity: 2,
              image: '/src/assets/images/bamboo-bottle.jpg'
            }
          ],
          shippingAddress: {
            name: 'John Doe',
            address: '123 Jalan Sustainable',
            city: 'Kuala Lumpur',
            state: 'Kuala Lumpur',
            postalCode: '50000'
          }
        }
      ],
      
      filteredOrders: []
    }
  },
  
  methods: {
    // Filter orders based on search and filters
    filterOrders() {
      let filtered = [...this.orders]
      
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
    
    // Show add order modal
    showAddOrderModal() {
      this.editingOrder = null
      this.orderForm = {
        orderNumber: 'ECO-' + new Date().getFullYear() + '-' + String(Date.now()).slice(-3),
        status: 'pending',
        orderDate: new Date().toISOString().split('T')[0],
        total: 0
      }
      
      // Show Bootstrap modal
      const modal = new bootstrap.Modal(document.getElementById('orderModal'))
      modal.show()
    },
    
    // Edit order
    editOrder(order) {
      this.editingOrder = order
      this.orderForm = {
        orderNumber: order.orderNumber,
        status: order.status,
        orderDate: order.orderDate,
        total: order.total
      }
      
      // Show Bootstrap modal
      const modal = new bootstrap.Modal(document.getElementById('orderModal'))
      modal.show()
    },
    
    // Save order (add or edit)
    saveOrder() {
      if (this.editingOrder) {
        // Update existing order
        const index = this.orders.findIndex(o => o.id === this.editingOrder.id)
        if (index !== -1) {
          this.orders[index] = {
            ...this.orders[index],
            orderNumber: this.orderForm.orderNumber,
            status: this.orderForm.status,
            orderDate: this.orderForm.orderDate,
            total: parseFloat(this.orderForm.total)
          }
        }
        alert('Order updated successfully!')
      } else {
        // Add new order
        const newOrder = {
          id: Date.now(),
          orderNumber: this.orderForm.orderNumber,
          orderDate: this.orderForm.orderDate,
          status: this.orderForm.status,
          subtotal: parseFloat(this.orderForm.total) * 0.85,
          shipping: 46.95,
          tax: parseFloat(this.orderForm.total) * 0.08,
          discount: 0,
          total: parseFloat(this.orderForm.total),
          items: [
            {
              id: 1,
              name: 'Sample Product',
              brand: 'EcoGear',
              price: parseFloat(this.orderForm.total) * 0.85,
              quantity: 1,
              image: '/src/assets/images/eco-tent.jpg'
            }
          ],
          shippingAddress: {
            name: 'John Doe',
            address: '123 Jalan Sustainable',
            city: 'Kuala Lumpur',
            state: 'Kuala Lumpur',
            postalCode: '50000'
          }
        }
        
        this.orders.unshift(newOrder)
        alert('Order added successfully!')
      }
      
      // Hide modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('orderModal'))
      modal.hide()
      
      // Refresh filtered orders
      this.filterOrders()
    },
    
    // Delete order
    deleteOrder(orderId) {
      if (confirm('Are you sure you want to delete this order?')) {
        const index = this.orders.findIndex(o => o.id === orderId)
        if (index !== -1) {
          this.orders.splice(index, 1)
          this.filterOrders()
          alert('Order deleted successfully!')
        }
      }
    },
    
    // Track order
    trackOrder(order) {
      alert(`Tracking information for Order #${order.orderNumber}:\n\nStatus: ${order.status}\nEstimated Delivery: 3-5 business days`)
    },
    
    // Reorder items
    reorderItems(order) {
      if (confirm('Add these items to your cart?')) {
        alert('Items added to cart! Redirecting to cart...')
        // Would normally add items to cart store
        this.$router.push('/cart')
      }
    },
    
    // Download invoice
    downloadInvoice(order) {
      alert(`Invoice for Order #${order.orderNumber} would be downloaded here`)
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
  
  // Initialize filtered orders
  mounted() {
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

/* Modal styling */
.modal-content {
  border-radius: 0.75rem;
  border: none;
}

.modal-header {
  border-bottom: 1px solid #e9ecef;
  border-radius: 0.75rem 0.75rem 0 0;
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