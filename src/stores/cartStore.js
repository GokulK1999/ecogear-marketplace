import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [
      // Sample cart items for demonstration
      {
        id: 1,
        productId: 1,
        name: "EcoTent Pro 4",
        price: 1409.95,
        originalPrice: 1644.95,
        image: "/src/assets/images/eco-tent.jpg",
        brand: "EcoGear",
        quantity: 2,
        inStock: true,
        maxQuantity: 10
      },
      {
        id: 2,
        productId: 2,
        name: "Solar Backpack 35L",
        price: 892.95,
        originalPrice: 1080.95,
        image: "/src/assets/images/solar-backpack.jpg",
        brand: "SolarTrek",
        quantity: 1,
        inStock: true,
        maxQuantity: 5
      },
      {
        id: 3,
        productId: 3,
        name: "Bamboo Water Bottle",
        price: 164.45,
        originalPrice: 187.95,
        image: "/src/assets/images/bamboo-bottle.jpg",
        brand: "BambooLife",
        quantity: 3,
        inStock: true,
        maxQuantity: 20
      }
    ],
    // Shipping options
    shippingOptions: [
      { id: 'standard', name: 'Standard Shipping (5-7 days)', price: 46.95 },
      { id: 'express', name: 'Express Shipping (2-3 days)', price: 93.95 },
      { id: 'overnight', name: 'Overnight Shipping', price: 187.95 },
      { id: 'free', name: 'Free Shipping (10-14 days)', price: 0 }
    ],
    selectedShipping: 'standard',
    
    // Discount codes
    appliedCoupon: null,
    availableCoupons: [
      { code: 'ECO10', discount: 10, type: 'percentage', minAmount: 235 },
      { code: 'GREEN20', discount: 20, type: 'percentage', minAmount: 470 },
      { code: 'SAVE25', discount: 25, type: 'fixed', minAmount: 352 }
    ],
    
    // Tax rate
    taxRate: 0.08 // 8% tax
  }),

  getters: {
    // Calculate subtotal
    subtotal: (state) => {
      return state.items.reduce((total, item) => {
        return total + (item.price * item.quantity)
      }, 0)
    },

    // Calculate savings from original prices
    totalSavings: (state) => {
      return state.items.reduce((total, item) => {
        if (item.originalPrice > item.price) {
          return total + ((item.originalPrice - item.price) * item.quantity)
        }
        return total
      }, 0)
    },

    // Get selected shipping cost
    shippingCost: (state) => {
      const shipping = state.shippingOptions.find(option => option.id === state.selectedShipping)
      return shipping ? shipping.price : 0
    },

    // Calculate discount amount
    discountAmount: (state) => {
      if (!state.appliedCoupon) return 0
      
      const coupon = state.availableCoupons.find(c => c.code === state.appliedCoupon)
      if (!coupon) return 0
      
      const subtotal = state.items.reduce((total, item) => total + (item.price * item.quantity), 0)
      
      if (subtotal < coupon.minAmount) return 0
      
      if (coupon.type === 'percentage') {
        return (subtotal * coupon.discount) / 100
      } else {
        return coupon.discount
      }
    },

    // Calculate tax
    taxAmount: (state) => {
      const subtotal = state.items.reduce((total, item) => total + (item.price * item.quantity), 0)
      const shipping = state.shippingOptions.find(option => option.id === state.selectedShipping)?.price || 0
      const discount = state.appliedCoupon ? 
        (() => {
          const coupon = state.availableCoupons.find(c => c.code === state.appliedCoupon)
          if (!coupon || subtotal < coupon.minAmount) return 0
          return coupon.type === 'percentage' ? (subtotal * coupon.discount) / 100 : coupon.discount
        })() : 0
      
      const taxableAmount = subtotal + shipping - discount
      return taxableAmount * state.taxRate
    },

    // Calculate final total
    total: (state) => {
      const subtotal = state.items.reduce((total, item) => total + (item.price * item.quantity), 0)
      const shipping = state.shippingOptions.find(option => option.id === state.selectedShipping)?.price || 0
      const discount = state.appliedCoupon ? 
        (() => {
          const coupon = state.availableCoupons.find(c => c.code === state.appliedCoupon)
          if (!coupon || subtotal < coupon.minAmount) return 0
          return coupon.type === 'percentage' ? (subtotal * coupon.discount) / 100 : coupon.discount
        })() : 0
      
      const taxableAmount = subtotal + shipping - discount
      const tax = taxableAmount * state.taxRate
      
      return subtotal + shipping + tax - discount
    },

    // Get cart item count
    itemCount: (state) => {
      return state.items.reduce((total, item) => total + item.quantity, 0)
    },

    // Check if cart is empty
    isEmpty: (state) => {
      return state.items.length === 0
    }
  },

  actions: {
    // Update item quantity
    updateQuantity(itemId, newQuantity) {
      const item = this.items.find(item => item.id === itemId)
      if (item) {
        if (newQuantity <= 0) {
          this.removeItem(itemId)
        } else if (newQuantity <= item.maxQuantity) {
          item.quantity = newQuantity
        }
      }
    },

    // Remove item from cart
    removeItem(itemId) {
      const index = this.items.findIndex(item => item.id === itemId)
      if (index !== -1) {
        this.items.splice(index, 1)
      }
    },

    // Add item to cart
    addItem(product, quantity = 1) {
      const existingItem = this.items.find(item => item.productId === product.id)
      
      if (existingItem) {
        const newQuantity = existingItem.quantity + quantity
        if (newQuantity <= existingItem.maxQuantity) {
          existingItem.quantity = newQuantity
        }
      } else {
        this.items.push({
          id: Date.now(), // Simple ID generation
          productId: product.id,
          name: product.name,
          price: product.price,
          originalPrice: product.originalPrice || product.price,
          image: product.image,
          brand: product.brand,
          quantity: quantity,
          inStock: product.inStock,
          maxQuantity: product.maxQuantity || 10
        })
      }
    },

    // Apply coupon code
    applyCoupon(code) {
      const coupon = this.availableCoupons.find(c => c.code.toLowerCase() === code.toLowerCase())
      if (coupon && this.subtotal >= coupon.minAmount) {
        this.appliedCoupon = code.toUpperCase()
        return { success: true, message: `Coupon ${code} applied successfully!` }
      } else if (coupon && this.subtotal < coupon.minAmount) {
        return { success: false, message: `Minimum order amount of $${coupon.minAmount} required for this coupon.` }
      } else {
        return { success: false, message: 'Invalid coupon code.' }
      }
    },

    // Remove coupon
    removeCoupon() {
      this.appliedCoupon = null
    },

    // Update shipping method
    updateShipping(shippingId) {
      const shipping = this.shippingOptions.find(option => option.id === shippingId)
      if (shipping) {
        this.selectedShipping = shippingId
      }
    },

    // Clear cart
    clearCart() {
      this.items = []
      this.appliedCoupon = null
      this.selectedShipping = 'standard'
    }
  }
})