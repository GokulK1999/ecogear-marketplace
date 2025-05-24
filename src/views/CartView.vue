<template>
  <div class="cart-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-2">Shopping Cart</h1>
            <p class="lead text-muted mb-0" v-if="!cartStore.isEmpty">
              {{ cartStore.itemCount }} {{ cartStore.itemCount === 1 ? 'item' : 'items' }} in your cart
            </p>
          </div>
          <div class="col-md-4 text-md-end">
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

    <div class="container py-4">
      <!-- Empty Cart State -->
      <div v-if="cartStore.isEmpty" class="empty-cart text-center py-5">
        <div class="empty-cart-icon mb-4">
          <i class="bi bi-cart-x display-1 text-muted"></i>
        </div>
        <h3 class="text-muted mb-3">Your cart is empty</h3>
        <p class="text-muted mb-4">Looks like you haven't added any sustainable gear yet!</p>
        <router-link to="/products" class="btn btn-eco-primary btn-lg">
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
                      <h5 class="mb-0 fw-bold">
                        <i class="bi bi-cart-check text-success me-2"></i>
                        Cart Items ({{ cartStore.itemCount }})
                      </h5>
                    </div>
                    <div class="col-auto">
                      <button 
                        class="btn btn-outline-danger btn-sm"
                        @click="clearCart"
                        :disabled="cartStore.isEmpty"
                      >
                        <i class="bi bi-trash me-1"></i>Clear Cart
                      </button>
                    </div>
                  </div>
                </div>
                
                <div class="card-body p-0">
                  <!-- Cart Item - Demonstrates Repetition -->
                  <div 
                    class="cart-item" 
                    v-for="(item, index) in cartStore.items" 
                    :key="item.id"
                    :class="{ 'border-bottom': index < cartStore.items.length - 1 }"
                  >
                    <div class="row g-0 align-items-center p-3">
                      <!-- Product Image -->
                      <div class="col-md-2 col-3">
                        <div class="item-image">
                          <img 
                            :src="item.image" 
                            :alt="item.name" 
                            class="img-fluid rounded"
                          >
                        </div>
                      </div>

                      <!-- Product Details -->
                      <div class="col-md-4 col-9 ps-3">
                        <div class="item-details">
                          <h6 class="fw-bold mb-1">{{ item.name }}</h6>
                          <small class="text-muted d-block mb-2">{{ item.brand }}</small>
                          
                          <!-- Price Information -->
                          <div class="price-info">
                            <span class="current-price fw-bold text-success">
                              RM{{ formatPrice(item.price) }}
                            </span>
                            <span 
                              class="original-price text-muted text-decoration-line-through ms-2 small"
                              v-if="item.originalPrice > item.price"
                            >
                              RM{{ formatPrice(item.originalPrice) }}
                            </span>
                          </div>

                          <!-- Savings Badge -->
                          <span 
                            class="badge bg-success-subtle text-success mt-1"
                            v-if="item.originalPrice > item.price"
                          >
                            You save RM{{ formatPrice((item.originalPrice - item.price) * item.quantity) }}
                          </span>
                        </div>
                      </div>

                      <!-- Quantity Controls -->
                      <div class="col-md-3 col-6 text-center">
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

                      <!-- Item Total & Actions -->
                      <div class="col-md-3 col-6 text-end">
                        <div class="item-total">
                          <div class="total-price fw-bold h6 mb-2">
                            RM{{ formatPrice(item.price * item.quantity) }}
                          </div>
                          <button 
                            class="btn btn-outline-danger btn-sm"
                            @click="removeItem(item.id)"
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
                <router-link to="/products" class="btn btn-outline-primary">
                  <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </router-link>
              </div>
            </div>
          </div>

          <!-- Order Summary Section -->
          <div class="col-lg-4">
            <div class="order-summary sticky-top" style="top: 100px;">
              
              <!-- Coupon Code Section -->
              <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold">
                    <i class="bi bi-percent text-success me-2"></i>
                    Discount Code
                  </h6>
                </div>
                <div class="card-body">
                  <div v-if="!cartStore.appliedCoupon">
                    <div class="input-group mb-3">
                      <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Enter coupon code"
                        v-model="couponCode"
                        @keyup.enter="applyCoupon"
                      >
                      <button 
                        class="btn btn-eco-primary" 
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
                          class="badge bg-light text-dark cursor-pointer" 
                          v-for="coupon in cartStore.availableCoupons" 
                          :key="coupon.code"
                          @click="couponCode = coupon.code"
                          style="cursor: pointer;"
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
                  
                  <!-- Coupon Message -->
                  <div 
                    v-if="couponMessage" 
                    class="alert mt-3 mb-0"
                    :class="couponMessage.success ? 'alert-success' : 'alert-danger'"
                  >
                    {{ couponMessage.text }}
                  </div>
                </div>
              </div>

              <!-- Shipping Options -->
              <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold">
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
                      class="form-check-input" 
                      type="radio" 
                      :id="'shipping-' + shipping.id"
                      :value="shipping.id"
                      v-model="cartStore.selectedShipping"
                    >
                    <label class="form-check-label d-flex justify-content-between w-100" :for="'shipping-' + shipping.id">
                      <div>
                        <div class="fw-medium">{{ shipping.name }}</div>
                      </div>
                      <div class="fw-bold">
                        {{ shipping.price === 0 ? 'FREE' : 'RM' + formatPrice(shipping.price) }}
                      </div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Order Summary -->
              <div class="card shadow-sm">
                <div class="card-header bg-white">
                  <h6 class="mb-0 fw-bold">
                    <i class="bi bi-receipt text-success me-2"></i>
                    Order Summary
                  </h6>
                </div>
                <div class="card-body">
                  <!-- Subtotal -->
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span>Subtotal ({{ cartStore.itemCount }} items):</span>
                    <span class="fw-medium">RM{{ formatPrice(cartStore.subtotal) }}</span>
                  </div>

                  <!-- Savings -->
                  <div class="summary-row d-flex justify-content-between mb-2" v-if="cartStore.totalSavings > 0">
                    <span class="text-success">
                      <i class="bi bi-piggy-bank me-1"></i>Total Savings:
                    </span>
                    <span class="fw-medium text-success">-RM{{ formatPrice(cartStore.totalSavings) }}</span>
                  </div>

                  <!-- Shipping -->
                  <div class="summary-row d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span class="fw-medium">
                      {{ cartStore.shippingCost === 0 ? 'FREE' : 'RM' + formatPrice(cartStore.shippingCost) }}
                    </span>
                  </div>

                  <!-- Discount -->
                  <div class="summary-row d-flex justify-content-between mb-2" v-if="cartStore.discountAmount > 0">
                    <span class="text-success">
                      <i class="bi bi-percent me-1"></i>Discount:
                    </span>
                    <span class="fw-medium text-success">-RM{{ formatPrice(cartStore.discountAmount) }}</span>
                  </div>

                  <!-- Tax -->
                  <div class="summary-row d-flex justify-content-between mb-3">
                    <span>Tax (8%):</span>
                    <span class="fw-medium">RM{{ formatPrice(cartStore.taxAmount) }}</span>
                  </div>

                  <hr>

                  <!-- Total -->
                  <div class="summary-total d-flex justify-content-between mb-4">
                    <span class="h5 fw-bold">Total:</span>
                    <span class="h5 fw-bold text-success">RM{{ formatPrice(cartStore.total) }}</span>
                  </div>

                  <!-- Checkout Button -->
                  <button class="btn btn-eco-primary w-100 btn-lg mb-3">
                    <i class="bi bi-credit-card me-2"></i>
                    Proceed to Checkout
                  </button>

                  <!-- Security Info -->
                  <div class="security-info text-center">
                    <small class="text-muted">
                      <i class="bi bi-shield-check text-success me-1"></i>
                      Secure checkout with SSL encryption
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useCartStore } from '@/stores/cartStore'

export default {
  name: 'CartView',
  setup() {
    const cartStore = useCartStore()
    return { cartStore }
  },
  data() {
    return {
      couponCode: '',
      couponMessage: null
    }
  },
  methods: {
    // Update item quantity - demonstrates data manipulation
    updateQuantity(itemId, newQuantity) {
      if (newQuantity >= 1) {
        this.cartStore.updateQuantity(itemId, newQuantity)
      }
    },

    // Remove item from cart
    removeItem(itemId) {
      if (confirm('Are you sure you want to remove this item from your cart?')) {
        this.cartStore.removeItem(itemId)
      }
    },

    // Clear entire cart
    clearCart() {
      if (confirm('Are you sure you want to clear your entire cart?')) {
        this.cartStore.clearCart()
      }
    },

    // Apply coupon code
    applyCoupon() {
      if (!this.couponCode.trim()) return

      const result = this.cartStore.applyCoupon(this.couponCode.trim())
      
      this.couponMessage = {
        success: result.success,
        text: result.message
      }

      if (result.success) {
        this.couponCode = ''
      }

      // Clear message after 3 seconds
      setTimeout(() => {
        this.couponMessage = null
      }, 3000)
    },

    // Remove applied coupon
    removeCoupon() {
      this.cartStore.removeCoupon()
      this.couponMessage = {
        success: false,
        text: 'Coupon removed'
      }

      setTimeout(() => {
        this.couponMessage = null
      }, 2000)
    },

    // Format price - demonstrates filters
    formatPrice(price) {
      return price.toFixed(2)
    }
  }
}
</script>

<style scoped>
/* Cart item styles */
.cart-item {
  transition: background-color 0.2s ease;
}

.cart-item:hover {
  background-color: #f8f9fa;
}

.item-image img {
  width: 100%;
  height: 80px;
  object-fit: cover;
  border-radius: 0.375rem;
}

/* Quantity controls */
.quantity-controls .input-group {
  max-width: 120px;
  margin: 0 auto;
}

.quantity-controls input {
  max-width: 50px;
}

/* Summary styles */
.summary-row {
  font-size: 0.95rem;
}

.summary-total {
  font-size: 1.1rem;
}

/* Empty cart styles */
.empty-cart-icon i {
  font-size: 5rem;
}

/* Order summary sticky positioning */
@media (min-width: 992px) {
  .order-summary {
    position: sticky;
    top: 100px;
  }
}

/* Mobile responsive */
@media (max-width: 768px) {
  .cart-item .row > div {
    margin-bottom: 1rem;
  }
  
  .cart-item .col-md-3:last-child,
  .cart-item .col-md-3:nth-last-child(2) {
    text-align: center !important;
  }
  
  .order-summary {
    position: static !important;
  }
}

/* Available coupons styling */
.available-coupons .badge:hover {
  background-color: var(--eco-secondary) !important;
  color: var(--eco-dark) !important;
}

/* Animation for messages */
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
</style>