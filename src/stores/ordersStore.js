import { defineStore } from 'pinia'

export const useOrdersStore = defineStore('orders', {
  state: () => ({
    orders: [], // Will load user-specific orders from localStorage
    isLoading: false
  }),

  getters: {
    // Get orders for current user
    userOrders: (state) => {
      const userData = localStorage.getItem('ecogear_user')
      if (!userData) return []
      
      const user = JSON.parse(userData)
      const userId = user.id || user.email
      return state.orders.filter(order => order.userId === userId)
    },

    // Get orders by status
    ordersByStatus: (state) => (status) => {
      const userData = localStorage.getItem('ecogear_user')
      if (!userData) return []
      
      const user = JSON.parse(userData)
      return state.orders.filter(order => 
        order.userId === (user.id || user.email) && 
        (status === 'all' || order.status === status)
      )
    }
  },

  actions: {
    // Initialize orders from localStorage
    initializeOrders() {
      try {
        const savedOrders = localStorage.getItem('ecogear_orders')
        if (savedOrders) {
          this.orders = JSON.parse(savedOrders)
        } else {
          this.orders = []
        }
      } catch (error) {
        console.error('Error loading orders from localStorage:', error)
        this.orders = []
      }
    },

    // Save orders to localStorage
    saveOrders() {
      try {
        localStorage.setItem('ecogear_orders', JSON.stringify(this.orders))
      } catch (error) {
        console.error('Error saving orders to localStorage:', error)
      }
    },

    // Create new order from cart
    createOrder(cartData, shippingInfo = null) {
      const userData = localStorage.getItem('ecogear_user')
      if (!userData) {
        throw new Error('User not logged in')
      }

      const user = JSON.parse(userData)
      const orderNumber = `ECO-${new Date().getFullYear()}-${String(Date.now()).slice(-6)}`
      
      // Default shipping info if not provided
      const defaultShipping = {
        name: user.name || 'Customer',
        address: user.address || '123 Main Street',
        city: user.city || 'Kuala Lumpur',
        state: user.state || 'Kuala Lumpur',
        postalCode: user.postalCode || '50000'
      }

      const newOrder = {
        id: Date.now(),
        userId: user.id || user.email, 
        orderNumber,
        orderDate: new Date().toISOString().split('T')[0],
        status: 'pending',
        items: cartData.items.map(item => ({
          id: item.productId,
          name: item.name,
          brand: item.brand,
          price: item.price,
          quantity: item.quantity,
          image: item.image
        })),
        subtotal: cartData.subtotal,
        shipping: cartData.shippingCost,
        tax: cartData.taxAmount,
        discount: cartData.discountAmount,
        total: cartData.total,
        shippingAddress: shippingInfo || defaultShipping,
        createdAt: new Date().toISOString()
      }

      this.orders.unshift(newOrder) // Add to beginning of array
      this.saveOrders()
      
      return newOrder
    },

    // Update order status
    updateOrderStatus(orderId, newStatus) {
      const order = this.orders.find(o => o.id === orderId)
      if (order) {
        order.status = newStatus
        order.updatedAt = new Date().toISOString()
        this.saveOrders()
        return true
      }
      return false
    },

    // Delete order (only for cancelled/pending orders)
    deleteOrder(orderId) {
      const userData = localStorage.getItem('ecogear_user')
      if (!userData) return false

      const user = JSON.parse(userData)
      const orderIndex = this.orders.findIndex(o => 
        o.id === orderId && o.userId === (user.id || user.email)
      )
      
      if (orderIndex !== -1) {
        const order = this.orders[orderIndex]
        // Only allow deletion of pending or cancelled orders
        if (order.status === 'pending' || order.status === 'cancelled') {
          this.orders.splice(orderIndex, 1)
          this.saveOrders()
          return true
        }
      }
      return false
    },

    // Get order by ID (for current user only)
    getOrderById(orderId) {
      const userData = localStorage.getItem('ecogear_user')
      if (!userData) return null

      const user = JSON.parse(userData)
      return this.orders.find(o => 
        o.id === orderId && o.userId === (user.id || user.email)
      )
    },

    // Clear all orders (for testing purposes)
    clearOrders() {
      this.orders = []
      this.saveOrders()
    }
  }
})