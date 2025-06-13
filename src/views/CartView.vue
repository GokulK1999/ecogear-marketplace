<template>
  <div class="cart-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-3 py-md-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="page-title fw-bold text-dark mb-2">Shopping Cart</h1>
            <p class="page-subtitle text-muted mb-0" v-if="!cartStore.isEmpty">
              {{ cartStore.itemCount }} {{ cartStore.itemCount === 1 ? 'item' : 'items' }} in your cart
            </p>
          </div>
          <div class="col-md-4 text-md-end d-none d-md-block">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                  <router-link to="/" class="text-decoration-none">Home</router-link>
                </li>
                <li class="breadcrumb-item">
                  <router-link to="/products" class="text-decoration-none">Products</router-link>
                </li>
                <li class="breadcrumb-item active">Cart</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-3 py-md-4">
      <!-- Empty Cart State -->
      <div v-if="cartStore.isEmpty" class="empty-cart text-center py-4 py-md-5">
        <div class="empty-cart-icon mb-3 mb-md-4">
          <i class="bi bi-cart-x empty-cart-icon-size text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">Your cart is empty</h3>
        <p class="text-muted mb-4">Looks like you haven't added any sustainable gear yet!</p>
        <router-link to="/products" class="btn btn-eco-primary btn-lg mobile-btn">
          <i class="bi bi-arrow-left me-2"></i>Continue Shopping
        </router-link>
      </div>

      <!-- Cart with Items -->
      <div v-else>
        <div class="row">
          <!-- Cart Items Section -->
          <div class="col-lg-8 mb-4">
            <div class="cart-items">
              <div class="card shadow-sm">
                <div class="card-header bg-white">
                  <div class="row align-items-center">
                    <div class="col">
                      <h5 class="mb-0 fw-bold cart-header-title">
                        <i class="bi bi-cart-check text-success me-2"></i>
                        <span class="d-none d-sm-inline">Cart Items ({{ cartStore.itemCount }})</span>
                        <span class="d-sm-none">Items ({{ cartStore.itemCount }})</span>
                      </h5>
                    </div>
                    <div class="col-auto">
                      <button 
                        class="btn btn-outline-danger btn-sm mobile-btn-sm"
                        @click="clearCart"
                        :disabled="cartStore.isEmpty"
                      >
                        <i class="bi bi-trash me-1 d-none d-sm-inline"></i>
                        <i class="bi bi-trash d-sm-none"></i>
                        <span class="d-none d-sm-inline">Clear Cart</span>
                      </button>
                    </div>
                  </div>
                </div>
                
                <div class="card-body p-0">
                  <!-- Cart Item - Mobile Optimized -->
                  <div 
                    class="cart-item" 
                    v-for="(item, index) in cartStore.items" 
                    :key="item.id"
                    :class="{ 'border-bottom': index < cartStore.items.length - 1 }"
                  >
                    <div class="row g-0 align-items-center p-2 p-md-3">
                      <!-- Product Image -->
                      <div class="col-3 col-md-2">
                        <div class="item-image">
                          <img 
                            :src="item.image" 
                            :alt="item.name" 
                            class="img-fluid rounded item-img"
                          >
                        </div>
                      </div>

                      <!-- Product Details -->
                      <div class="col-9 col-md-4 ps-2 ps-md-3">
                        <div class="item-details">
                          <h6 class="fw-bold mb-1 item-title">{{ item.name }}</h6>
                          <small class="text-muted d-block mb-1 mb-md-2 item-brand">{{ item.brand }}</small>
                          
                          <!-- Price Information -->
                          <div class="price-info">
                            <span class="current-price fw-bold text-success">
                              RM{{ formatPrice(item.price) }}
                            </span>
                            <span 
                              class="original-price text-muted text-decoration-line-through ms-2 small d-none d-sm-inline"
                              v-if="item.originalPrice > item.price"
                            >
                              RM{{ formatPrice(item.originalPrice) }}
                            </span>
                          </div>

                          <!-- Savings Badge -->
                          <span 
                            class="badge bg-success-subtle text-success mt-1 d-none d-md-inline-block"
                            v-if="item.originalPrice > item.price"
                          >
                            You save RM{{ formatPrice((item.originalPrice - item.price) * item.quantity) }}
                          </span>

                          <!-- Mobile: Show quantity and total here -->
                          <div class="d-md-none mt-2">
                            <div class="d-flex justify-content-between align-items-center">
                              <div class="quantity-controls-mobile">
                                <div class="input-group input-group-sm">
                                  <button 
                                    class="btn btn-outline-secondary btn-quantity" 
                                    type="button"
                                    @click="updateQuantity(item.id, item.quantity - 1)"
                                    :disabled="item.quantity <= 1"
                                  >
                                    <i class="bi bi-dash"></i>
                                  </button>
                                  <input 
                                    type="number" 
                                    class="form-control text-center quantity-input"
                                    :value="item.quantity"
                                    @input="updateQuantity(item.id, parseInt($event.target.value))"
                                    :min="1"
                                    :max="item.maxQuantity"
                                  >
                                  <button 
                                    class="btn btn-outline-secondary btn-quantity" 
                                    type="button"
                                    @click="updateQuantity(item.id, item.quantity + 1)"
                                    :disabled="item.quantity >= item.maxQuantity"
                                  >
                                    <i class="bi bi-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <div class="item-actions-mobile">
                                <div class="total-price fw-bold text-success mb-1">
                                  RM{{ formatPrice(item.price * item.quantity) }}
                                </div>
                                <button 
                                  class="btn btn-outline-danger btn-sm"
                                  @click="removeItem(item.id, item.name)"
                                >
                                  <i class="bi bi-trash"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Desktop: Quantity Controls -->
                      <div class="col-md-3 d-none d-md-block text-center">
                        <div class="quantity-controls">
                          <label class="form-label small text-muted mb-2">Quantity</label>
                          <div class="input-group input-group-sm">
                            <button 
                              class="btn btn-outline-secondary" 
                              type="button"
                              @click="updateQuantity(item.id, item.quantity - 1)"
                              :disabled="item.quantity <= 1"
                            >
                              <i class="bi bi-dash"></i>
                            </button>
                            <input 
                              type="number" 
                              class="form-control text-center"
                              :value="item.quantity"
                              @input="updateQuantity(item.id, parseInt($event.target.value))"
                              :min="1"
                              :max="item.maxQuantity"
                            >
                            <button 
                              class="btn btn-outline-secondary" 
                              type="button"
                              @click="updateQuantity(item.id, item.quantity + 1)"
                              :disabled="item.quantity >= item.maxQuantity"
                            >
                              <i class="bi bi-plus"></i>
                            </button>
                          </div>
                          <small class="text-muted">Max: {{ item.maxQuantity }}</small>
                        </div>
                      </div>

                      <!-- Desktop: Item Total & Actions -->
                      <div class="col-md-3 d-none d-md-block text-end">
                        <div class="item-total">
                          <div class="total-price fw-bold h6 mb-2">
                            RM{{ formatPrice(item.price * item.quantity) }}
                          </div>
                          <button 
                            class="btn btn-outline-danger btn-sm"
                            @click="removeItem(item.id, item.name)"
                          >
                            <i class="bi bi-trash me-1"></i>Remove
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Continue Shopping -->
              <div class="continue-shopping mt-3">
                <router-link to="/products" class="btn btn-outline-primary mobile-btn">
                  <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </router-link>
              </div>
            </div>
          </div>

          <!-- Order Summary Section -->
          <div class="col-lg-4">
            <div class="order-summary" :class="{'sticky-top': true}">
              
              <!-- Coupon Code Section -->
              <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold card-title-mobile">
                    <i class="bi bi-percent text-success me-2"></i>
                    Discount Code
                  </h6>
                </div>
                <div class="card-body">
                  <div v-if="!cartStore.appliedCoupon">
                    <div class="input-group mb-3">
                      <input 
                        type="text" 
                        class="form-control mobile-input" 
                        placeholder="Enter coupon code"
                        v-model="couponCode"
                        @keyup.enter="applyCoupon"
                      >
                      <button 
                        class="btn btn-eco-primary mobile-btn-sm" 
                        type="button"
                        @click="applyCoupon"
                        :disabled="!couponCode.trim()"
                      >
                        Apply
                      </button>
                    </div>
                    
                    <!-- Available Coupons Hint -->
                    <div class="available-coupons">
                      <small class="text-muted">Available codes:</small>
                      <div class="d-flex flex-wrap gap-1 mt-1">
                        <span 
                          class="badge bg-light text-dark cursor-pointer coupon-badge" 
                          v-for="coupon in cartStore.availableCoupons" 
                          :key="coupon.code"
                          @click="couponCode = coupon.code"
                        >
                          {{ coupon.code }}
                        </span>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Applied Coupon -->
                  <div v-else class="applied-coupon">
                    <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
                      <div>
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>{{ cartStore.appliedCoupon }}</strong> applied
                      </div>
                      <button 
                        class="btn btn-sm btn-outline-danger"
                        @click="removeCoupon"
                      >
                        <i class="bi bi-x"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Shipping Options -->
              <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold card-title-mobile">
                    <i class="bi bi-truck text-success me-2"></i>
                    Shipping Options
                  </h6>
                </div>
                <div class="card-body">
                  <div 
                    class="form-check mb-2" 
                    v-for="shipping in cartStore.shippingOptions" 
                    :key="shipping.id"
                  >
                    <input 
                      class="form-check-input mobile-checkbox" 
                      type="radio" 
                      :id="'shipping-' + shipping.id"
                      :value="shipping.id"
                      v-model="cartStore.selectedShipping"
                      @change="onShippingChange(shipping.name)"
                    >
                    <label class="form-check-label d-flex justify-content-between w-100 shipping-label" :for="'shipping-' + shipping.id">
                      <div>
                        <div class="fw-medium shipping-name">{{ shipping.name }}</div>
                      </div>
                      <div class="fw-bold shipping-price">
                        {{ shipping.price === 0 ? 'FREE' : 'RM' + formatPrice(shipping.price) }}
                      </div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Order Summary -->
              <div class="card shadow-sm">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold card-title-mobile">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Order Summary
                  </h6>
                </div>
                <div class="card-body">
                  <!-- Subtotal -->
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span class="summary-label">Subtotal ({{ cartStore.itemCount }} items):</span>
                    <span class="fw-medium">RM{{ formatPrice(cartStore.subtotal) }}</span>
                  </div>

                  <!-- Savings -->
                  <div class="summary-row d-flex justify-content-between mb-2" v-if="cartStore.totalSavings > 0">
                    <span class="text-success summary-label">
                      <i class="bi bi-piggy-bank me-1"></i>Total Savings:
                    </span>
                    <span class="fw-medium text-success">-RM{{ formatPrice(cartStore.totalSavings) }}</span>
                  </div>

                  <!-- Shipping -->
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span class="summary-label">Shipping:</span>
                    <span class="fw-medium">
                      {{ cartStore.shippingCost === 0 ? 'FREE' : 'RM' + formatPrice(cartStore.shippingCost) }}
                    </span>
                  </div>

                  <!-- Discount -->
                  <div class="summary-row d-flex justify-content-between mb-2" v-if="cartStore.discountAmount > 0">
                    <span class="text-success summary-label">
                      <i class="bi bi-percent me-1"></i>Discount:
                    </span>
                    <span class="fw-medium text-success">-RM{{ formatPrice(cartStore.discountAmount) }}</span>
                  </div>

                  <!-- Tax -->
                  <div class="summary-row d-flex justify-content-between mb-3">
                    <span class="summary-label">Tax (8%):</span>
                    <span class="fw-medium">RM{{ formatPrice(cartStore.taxAmount) }}</span>
                  </div>

                  <hr>

                  <!-- Total -->
                  <div class="summary-total d-flex justify-content-between mb-3 mb-md-4">
                    <span class="total-label fw-bold">Total:</span>
                    <span class="total-amount fw-bold text-success">RM{{ formatPrice(cartStore.total) }}</span>
                  </div>

                  <!-- Checkout Button -->
                  <button class="btn btn-eco-primary w-100 checkout-btn mb-3" @click="proceedToCheckout">
                    <i class="bi bi-credit-card me-2"></i>
                    <span class="d-none d-sm-inline">Proceed to Checkout</span>
                    <span class="d-sm-none">Checkout</span>
                  </button>

                  <!-- Security Info -->
                  <div class="security-info text-center">
                    <small class="text-muted security-text">
                      <i class="bi bi-shield-check text-success me-1"></i>
                      <span class="d-none d-sm-inline">Secure checkout with SSL encryption</span>
                      <span class="d-sm-none">Secure checkout</span>
                    </small>
                  </div>
                </div>
              </div>
            </div>
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
  </div>
</template>

<script>
import { useCartStore } from '@/stores/cartStore'
import { useOrdersStore } from '@/stores/ordersStore'
import ToastNotification from '@/components/ToastNotification.vue'

export default {
  name: 'CartView',
  components: {
    ToastNotification
  },
  setup() {
    const cartStore = useCartStore()
    const ordersStore = useOrdersStore()
    return { cartStore, ordersStore }
  },
  data() {
    return {
      couponCode: '',
      // Toast notification
      toast: {
        show: false,
        message: '',
        type: 'success'
      }
    }
  },
  methods: {
    // Update item quantity - demonstrates data manipulation
    updateQuantity(itemId, newQuantity) {
      if (newQuantity >= 1) {
        const item = this.cartStore.items.find(item => item.id === itemId)
        const oldQuantity = item ? item.quantity : 0
        
        this.cartStore.updateQuantity(itemId, newQuantity)
        
        if (item && newQuantity !== oldQuantity) {
          this.showToast(`Quantity updated to ${newQuantity}`, 'info')
        }
      }
    },

    // Remove item from cart
    removeItem(itemId, itemName) {
      try {
        this.cartStore.removeItem(itemId)
        this.showToast(`${itemName} removed from cart`, 'success')
      } catch (error) {
        this.showToast('Error removing item from cart', 'error')
      }
    },

    // Clear entire cart
    clearCart() {
      try {
        const itemCount = this.cartStore.itemCount
        this.cartStore.clearCart()
        this.showToast(`Cart cleared! ${itemCount} items removed`, 'success')
      } catch (error) {
        this.showToast('Error clearing cart', 'error')
      }
    },

    // Apply coupon code
    applyCoupon() {
      if (!this.couponCode.trim()) return

      try {
        const result = this.cartStore.applyCoupon(this.couponCode.trim())
        
        if (result.success) {
          this.couponCode = ''
          this.showToast(result.message, 'success')
        } else {
          this.showToast(result.message, 'error')
        }
      } catch (error) {
        this.showToast('Error applying coupon', 'error')
      }
    },

    // Remove applied coupon
    removeCoupon() {
      try {
        this.cartStore.removeCoupon()
        this.showToast('Coupon removed successfully', 'info')
      } catch (error) {
        this.showToast('Error removing coupon', 'error')
      }
    },

    // Handle shipping method change
    onShippingChange(shippingName) {
      this.showToast(`Shipping updated to: ${shippingName}`, 'info')
    },

    // Proceed to checkout
    proceedToCheckout() {
      const userData = localStorage.getItem('ecogear_user')
      
      if (!userData) {
        this.showToast('Please login first to proceed with checkout', 'warning')
        setTimeout(() => {
          this.$router.push('/login')
        }, 1500)
      } else {
        try {
          const cartData = {
            items: this.cartStore.items,
            subtotal: this.cartStore.subtotal,
            shippingCost: this.cartStore.shippingCost,
            taxAmount: this.cartStore.taxAmount,
            discountAmount: this.cartStore.discountAmount,
            total: this.cartStore.total
          }

          const newOrder = this.ordersStore.createOrder(cartData)
          this.showToast(`Order #${newOrder.orderNumber} placed successfully! 🎉`, 'success')
          
          setTimeout(() => {
            this.cartStore.clearCart()
            this.$router.push('/purchases')
          }, 2000)
          
        } catch (error) {
          console.error('Error creating order:', error)
          this.showToast('Error placing order. Please try again.', 'error')
        }
      }
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

    // Format price
    formatPrice(price) {
      return price.toFixed(2)
    }
  }
}
</script>

<style scoped>
/* Mobile-First Responsive Design */

/* Base Mobile Styles (320px+) */
.page-title {
  font-size: 1.75rem;
}

.page-subtitle {
  font-size: 0.95rem;
}

.empty-cart-icon-size {
  font-size: 4rem;
}

/* Mobile Buttons */
.mobile-btn {
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  min-height: 48px;
  border-radius: 0.5rem;
  font-weight: 600;
}

.mobile-btn-sm {
  padding: 0.5rem 0.75rem;
  font-size: 0.9rem;
  min-height: 44px;
  border-radius: 0.4rem;
}

/* Cart Items - Mobile Optimized */
.cart-item {
  transition: background-color 0.2s ease;
}

.cart-item:hover {
  background-color: #f8f9fa;
}

.item-img {
  width: 100%;
  height: 60px;
  object-fit: cover;
  border-radius: 0.375rem;
}

.item-title {
  font-size: 0.95rem;
  line-height: 1.3;
}

.item-brand {
  font-size: 0.75rem;
}

.current-price {
  font-size: 0.9rem;
}

/* Mobile Quantity Controls */
.quantity-controls-mobile .input-group {
  max-width: 100px;
}

.btn-quantity {
  min-width: 32px;
  min-height: 32px;
  padding: 0.25rem;
}

.quantity-input {
  max-width: 36px;
  font-size: 0.85rem;
  padding: 0.25rem;
}

.item-actions-mobile {
  text-align: right;
}

.total-price {
  font-size: 0.9rem;
}

/* Card Headers */
.cart-header-title {
  font-size: 1rem;
}

.card-title-mobile {
  font-size: 0.95rem;
}

/* Mobile Inputs and Controls */
.mobile-input {
  padding: 0.75rem;
  font-size: 1rem;
  min-height: 48px;
  border-radius: 0.5rem;
}

.mobile-checkbox {
  min-width: 20px;
  min-height: 20px;
}

.shipping-label {
  padding: 0.5rem 0;
  cursor: pointer;
}

.shipping-name {
  font-size: 0.9rem;
}

.shipping-price {
  font-size: 0.9rem;
}

/* Summary Section - Mobile */
.summary-row {
  font-size: 0.85rem;
}

.summary-label {
  font-size: 0.85rem;
}

.total-label {
  font-size: 1rem;
}

.total-amount {
  font-size: 1.1rem;
}

.checkout-btn {
  padding: 1rem;
  font-size: 1.1rem;
  min-height: 56px;
  border-radius: 0.75rem;
  font-weight: 700;
}

.security-text {
  font-size: 0.8rem;
}

/* Coupon Badges */
.coupon-badge {
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.8rem;
  padding: 0.4rem 0.6rem;
}

.coupon-badge:hover {
  background-color: var(--eco-secondary) !important;
  color: var(--eco-dark) !important;
  transform: translateY(-1px);
}

/* Order Summary Positioning */
.order-summary {
  position: static;
}

/* Tablet Styles (768px+) */
@media (min-width: 768px) {
  .page-title {
    font-size: 2.5rem;
  }
  
  .page-subtitle {
    font-size: 1.1rem;
  }
  
  .empty-cart-icon-size {
    font-size: 5rem;
  }
  
  .item-img {
    height: 80px;
  }
  
  .item-title {
    font-size: 1.1rem;
  }
  
  .item-brand {
    font-size: 0.85rem;
  }
  
  .current-price {
    font-size: 1rem;
  }
  
  .cart-header-title {
    font-size: 1.25rem;
  }
  
  .card-title-mobile {
    font-size: 1.1rem;
  }
  
  .summary-row {
    font-size: 0.95rem;
  }
  
  .summary-label {
    font-size: 0.95rem;
  }
  
  .total-label {
    font-size: 1.1rem;
  }
  
  .total-amount {
    font-size: 1.25rem;
  }
  
  .security-text {
    font-size: 0.85rem;
  }
}

/* Desktop Styles (1024px+) */
@media (min-width: 1024px) {
  .order-summary {
    position: sticky;
    top: 100px;
  }
  
  .quantity-controls .input-group {
    max-width: 120px;
    margin: 0 auto;
  }
  
  .quantity-controls input {
    max-width: 50px;
  }
  
  .summary-total {
    font-size: 1.1rem;
  }
}

/* Touch Optimization */
@media (hover: none) and (pointer: coarse) {
  .btn,
  .form-control,
  .form-check-input {
    min-height: 44px;
  }
  
  .coupon-badge:hover {
    transform: none;
  }
}

/* Animation */
.alert {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Security info */
.security-info {
  border-top: 1px solid #e9ecef;
  padding-top: 1rem;
}

/* Focus states for accessibility */
.btn:focus,
.form-control:focus,
.form-check-input:focus {
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
  border-color: var(--eco-primary);
}
</style>