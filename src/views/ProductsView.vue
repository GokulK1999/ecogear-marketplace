<template>
  <div class="products-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-3 py-md-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="page-title fw-bold text-dark mb-2">Sustainable Outdoor Gear</h1>
            <p class="page-subtitle text-muted mb-0">
              Discover {{ filteredProducts.length }} eco-friendly products for your next adventure
            </p>
          </div>
          <div class="col-md-4 text-md-end d-none d-md-block">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                  <router-link to="/" class="text-decoration-none">Home</router-link>
                </li>
                <li class="breadcrumb-item active">Products</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-3 py-md-4">
      <!-- Mobile Filter Toggle -->
      <div class="d-md-none mb-3">
        <button 
          class="btn btn-outline-primary w-100 filter-toggle" 
          @click="showMobileFilters = !showMobileFilters"
        >
          <i class="bi bi-funnel me-2"></i>Filters & Search
          <i :class="showMobileFilters ? 'bi bi-chevron-up' : 'bi bi-chevron-down'" class="ms-2"></i>
        </button>
      </div>

      <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div 
            class="filters-sidebar" 
            :class="{'d-none d-md-block': !showMobileFilters}"
          >
            
            <!-- Category Filter -->
            <div class="filter-section mb-4">
              <h5 class="filter-title fw-bold mb-3">
                <i class="bi bi-funnel text-success me-2"></i>Categories
              </h5>
              <div class="category-filters">
                <div 
                  class="form-check mb-2" 
                  v-for="category in categories" 
                  :key="category.id"
                >
                  <input 
                    class="form-check-input" 
                    type="radio" 
                    :id="'category-' + category.id"
                    :value="category.id"
                    v-model="selectedCategory"
                    @change="filterProducts"
                  >
                  <label class="form-check-label d-flex justify-content-between" :for="'category-' + category.id">
                    <span>{{ category.name }}</span>
                    <span class="badge bg-light text-dark">{{ category.count }}</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Price Range Filter -->
            <div class="filter-section mb-4">
              <h5 class="filter-title fw-bold mb-3">
                <i class="bi bi-currency-dollar text-success me-2"></i>Price Range
              </h5>
              <div class="price-filters">
                <div class="form-check mb-2" v-for="range in priceRanges" :key="range.id">
                  <input 
                    class="form-check-input" 
                    type="radio" 
                    :id="'price-' + range.id"
                    :value="range.id"
                    v-model="selectedPriceRange"
                    @change="filterProducts"
                  >
                  <label class="form-check-label" :for="'price-' + range.id">
                    {{ range.label }}
                  </label>
                </div>
              </div>
            </div>

            <!-- Availability Filter -->
            <div class="filter-section mb-4">
              <h5 class="filter-title fw-bold mb-3">
                <i class="bi bi-check-circle text-success me-2"></i>Availability
              </h5>
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  id="inStock"
                  v-model="showInStockOnly"
                  @change="filterProducts"
                >
                <label class="form-check-label" for="inStock">
                  In Stock Only
                </label>
              </div>
            </div>

            <!-- Rating Filter -->
            <div class="filter-section mb-4">
              <h5 class="filter-title fw-bold mb-3">
                <i class="bi bi-star text-success me-2"></i>Minimum Rating
              </h5>
              <select 
                class="form-select" 
                v-model="minRating" 
                @change="filterProducts"
              >
                <option value="0">All Ratings</option>
                <option value="4">4+ Stars</option>
                <option value="4.5">4.5+ Stars</option>
              </select>
            </div>

            <!-- Clear Filters -->
            <button 
              class="btn btn-outline-secondary w-100 mb-3" 
              @click="clearFilters"
              v-if="hasActiveFilters"
            >
              <i class="bi bi-x-circle me-2"></i>Clear All Filters
            </button>
          </div>
        </div>

        <!-- Products Section -->
        <div class="col-lg-9 col-md-8">
          
          <!-- Search and Sort Bar -->
          <div class="products-controls mb-4">
            <div class="row g-2 g-md-3 align-items-center">
              <!-- Search Input -->
              <div class="col-12 col-md-6">
                <div class="search-box position-relative">
                  <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                  <input 
                    type="text" 
                    class="form-control ps-5 search-input" 
                    placeholder="Search products..."
                    v-model="searchQuery"
                    @input="filterProducts"
                  >
                </div>
              </div>

              <!-- Sort Dropdown -->
              <div class="col-12 col-md-3">
                <select 
                  class="form-select sort-select" 
                  v-model="sortBy" 
                  @change="sortProducts"
                >
                  <option value="name">Sort by Name</option>
                  <option value="price-low">Price: Low to High</option>
                  <option value="price-high">Price: High to Low</option>
                  <option value="rating">Highest Rated</option>
                  <option value="newest">Newest First</option>
                </select>
              </div>

              <!-- Results Count -->
              <div class="col-12 col-md-3 text-center text-md-end">
                <small class="text-muted results-count">
                  Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products
                </small>
              </div>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="products-grid" v-if="paginatedProducts.length > 0">
            <div class="row g-3 g-md-4">
              <div 
                class="col-6 col-md-6 col-lg-4" 
                v-for="product in paginatedProducts" 
                :key="product.id"
              >
                <div class="product-card card h-100 shadow-sm">
                  <!-- Product Image -->
                  <div class="product-image position-relative">
                    <img 
                      :src="product.image" 
                      :alt="product.name" 
                      class="card-img-top"
                      loading="lazy"
                    >
                    
                    <!-- Stock Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
                      <span 
                        class="badge badge-mobile" 
                        :class="product.inStock ? 'bg-success' : 'bg-danger'"
                      >
                        {{ product.inStock ? 'In Stock' : 'Out of Stock' }}
                      </span>
                    </div>

                    <!-- Sustainability Badge -->
                    <div class="position-absolute top-0 end-0 m-2 d-none d-sm-block">
                      <span class="badge bg-primary badge-mobile">
                        <i class="bi bi-leaf me-1"></i>{{ product.sustainability }}% Eco
                      </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="product-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                      <div class="quick-actions">
                        <button 
                          class="btn btn-eco-primary btn-sm me-2 quick-action-btn"
                          @click="addToCart(product)"
                          :disabled="!product.inStock"
                        >
                          <i class="bi bi-cart-plus"></i>
                        </button>
                        <button class="btn btn-outline-light btn-sm quick-action-btn">
                          <i class="bi bi-eye"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Product Info -->
                  <div class="card-body d-flex flex-column p-3 p-md-4">
                    <!-- Brand and Category -->
                    <div class="product-meta mb-2">
                      <small class="text-muted">{{ product.brand }} • {{ getCategoryName(product.category) }}</small>
                    </div>

                    <!-- Product Name -->
                    <h5 class="card-title fw-bold mb-2">{{ product.name }}</h5>

                    <!-- Rating and Reviews -->
                    <div class="product-rating mb-2">
                      <div class="d-flex align-items-center">
                        <div class="stars me-2">
                          <i 
                            class="bi star-icon" 
                            :class="i <= product.rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'"
                            v-for="i in 5" 
                            :key="i"
                          ></i>
                        </div>
                        <small class="text-muted review-count">({{ product.reviews }} reviews)</small>
                      </div>
                    </div>

                    <!-- Description -->
                    <p class="card-text text-muted mb-3 product-description">{{ product.description }}</p>

                    <!-- Features -->
                    <div class="product-features mb-3 d-none d-md-block">
                      <span 
                        class="badge bg-light text-dark me-1 mb-1" 
                        v-for="feature in product.features.slice(0, 2)" 
                        :key="feature"
                      >
                        {{ feature }}
                      </span>
                    </div>

                    <!-- Price and Actions -->
                    <div class="mt-auto">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="price-section">
                          <span class="current-price h5 text-success fw-bold mb-0">
                            RM{{ formatPrice(product.price) }}
                          </span>
                          <small class="original-price text-muted text-decoration-line-through ms-2 d-none d-sm-inline" v-if="product.originalPrice > product.price">
                            RM{{ formatPrice(product.originalPrice) }}
                          </small>
                        </div>
                        <small class="text-muted product-weight d-none d-md-block">{{ product.weight }}</small>
                      </div>

                      <button 
                        class="btn btn-eco-primary w-100 add-to-cart-btn"
                        @click="addToCart(product)"
                        :disabled="!product.inStock"
                      >
                        <i class="bi bi-cart-plus me-2"></i>
                        <span class="d-none d-sm-inline">{{ product.inStock ? 'Add to Cart' : 'Out of Stock' }}</span>
                        <span class="d-sm-none">{{ product.inStock ? 'Add' : 'Out of Stock' }}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- No Products Found -->
          <div v-else class="no-products text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h3 class="text-muted mb-3">No products found</h3>
            <p class="text-muted mb-4">Try adjusting your filters or search terms</p>
            <button class="btn btn-eco-primary" @click="clearFilters">
              <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
            </button>
          </div>

          <!-- Pagination -->
          <nav aria-label="Products pagination" v-if="totalPages > 1" class="mt-4 mt-md-5">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">
                  <i class="bi bi-chevron-left"></i>
                </button>
              </li>
              
              <li 
                class="page-item" 
                :class="{ active: page === currentPage }"
                v-for="page in visiblePages" 
                :key="page"
              >
                <button class="page-link" @click="changePage(page)">{{ page }}</button>
              </li>
              
              <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">
                  <i class="bi bi-chevron-right"></i>
                </button>
              </li>
            </ul>
          </nav>
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
// Import JSON data
import productsData from '@/assets/data/products.json'
import { useCartStore } from '@/stores/cartStore'
import ToastNotification from '@/components/ToastNotification.vue'

export default {
  name: 'ProductsView',
  components: {
    ToastNotification
  },
  setup() {
    const cartStore = useCartStore()
    return { cartStore }
  },
  data() {
    return {
      // Mobile state
      showMobileFilters: false,
      
      // Arrays - demonstrating use of arrays
      products: productsData.products,
      categories: productsData.categories,
      filteredProducts: [],
      
      // Filter states
      selectedCategory: 'all',
      selectedPriceRange: 'all',
      showInStockOnly: false,
      minRating: 0,
      searchQuery: '',
      sortBy: 'name',
      
      // Pagination
      currentPage: 1,
      itemsPerPage: 6,
      
      // Toast notification
      toast: {
        show: false,
        message: '',
        type: 'success'
      },
      
      // Price ranges for filtering
      priceRanges: [
        { id: 'all', label: 'All Prices' },
        { id: 'under-50', label: 'Under RM50' },
        { id: '50-100', label: 'RM50 - RM100' },
        { id: '100-200', label: 'RM100 - RM200' },
        { id: 'over-200', label: 'Over RM200' }
      ]
    }
  },
  
  computed: {
    // Pagination calculations
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage)
    },
    
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredProducts.slice(start, end)
    },
    
    visiblePages() {
      const pages = []
      const total = this.totalPages
      const current = this.currentPage
      
      if (total <= 5) {
        for (let i = 1; i <= total; i++) {
          pages.push(i)
        }
      } else {
        if (current <= 3) {
          pages.push(1, 2, 3, 4, 5)
        } else if (current >= total - 2) {
          pages.push(total - 4, total - 3, total - 2, total - 1, total)
        } else {
          pages.push(current - 2, current - 1, current, current + 1, current + 2)
        }
      }
      
      return pages
    },
    
    hasActiveFilters() {
      return this.selectedCategory !== 'all' || 
             this.selectedPriceRange !== 'all' || 
             this.showInStockOnly || 
             this.minRating > 0 || 
             this.searchQuery.trim() !== ''
    }
  },
  
  methods: {
    // Filter products - demonstrates directives and filters
    filterProducts() {
      let filtered = [...this.products]
      
      // Category filter
      if (this.selectedCategory !== 'all') {
        filtered = filtered.filter(product => product.category === this.selectedCategory)
      }
      
      // Price range filter
      if (this.selectedPriceRange !== 'all') {
        filtered = filtered.filter(product => {
          switch (this.selectedPriceRange) {
            case 'under-50':
              return product.price < 50
            case '50-100':
              return product.price >= 50 && product.price < 100
            case '100-200':
              return product.price >= 100 && product.price < 200
            case 'over-200':
              return product.price >= 200
            default:
              return true
          }
        })
      }
      
      // Stock filter
      if (this.showInStockOnly) {
        filtered = filtered.filter(product => product.inStock)
      }
      
      // Rating filter
      if (this.minRating > 0) {
        filtered = filtered.filter(product => product.rating >= this.minRating)
      }
      
      // Search filter
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(product => 
          product.name.toLowerCase().includes(query) ||
          product.description.toLowerCase().includes(query) ||
          product.brand.toLowerCase().includes(query) ||
          product.features.some(feature => feature.toLowerCase().includes(query))
        )
      }
      
      this.filteredProducts = filtered
      this.currentPage = 1 // Reset pagination
      this.sortProducts()
    },
    
    // Sort products
    sortProducts() {
      switch (this.sortBy) {
        case 'name':
          this.filteredProducts.sort((a, b) => a.name.localeCompare(b.name))
          break
        case 'price-low':
          this.filteredProducts.sort((a, b) => a.price - b.price)
          break
        case 'price-high':
          this.filteredProducts.sort((a, b) => b.price - a.price)
          break
        case 'rating':
          this.filteredProducts.sort((a, b) => b.rating - a.rating)
          break
        case 'newest':
          this.filteredProducts.sort((a, b) => b.id - a.id)
          break
      }
    },
    
    // Pagination methods
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page
        window.scrollTo({ top: 0, behavior: 'smooth' })
      }
    },
    
    // Utility methods - demonstrates filters
    formatPrice(price) {
      return price.toFixed(2)
    },
    
    getCategoryName(categoryId) {
      const category = this.categories.find(cat => cat.id === categoryId)
      return category ? category.name : categoryId
    },
    
    // Clear all filters
    clearFilters() {
      this.selectedCategory = 'all'
      this.selectedPriceRange = 'all'
      this.showInStockOnly = false
      this.minRating = 0
      this.searchQuery = ''
      this.sortBy = 'name'
      this.filterProducts()
    },
    
    // Add to cart functionality
    addToCart(product) {
      if (product.inStock) {
        try {
          // Add to cart store
          this.cartStore.addItem(product, 1)
          
          // Show success toast
          this.showToast(`${product.name} added to cart!`, 'success')
          
        } catch (error) {
          console.error('Error adding to cart:', error)
          this.showToast('Error adding item to cart. Please try again.', 'error')
        }
      } else {
        this.showToast('This item is currently out of stock.', 'warning')
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
    }
  },
  
  // Initialize data when component mounts
  mounted() {
    this.filterProducts()
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

.filter-toggle {
  padding: 0.75rem;
  font-size: 1rem;
  border-radius: 0.5rem;
  min-height: 48px;
}

/* Product card styles - Mobile Optimized */
.product-card {
  transition: all 0.3s ease;
  border: none;
  overflow: hidden;
  border-radius: 1rem;
}

.product-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.product-image {
  overflow: hidden;
  height: 160px;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-overlay {
  background: rgba(0,0,0,0.7);
  opacity: 1;
  transition: all 0.3s ease;
}

.quick-action-btn {
  min-height: 40px;
  min-width: 40px;
  border-radius: 50%;
}

/* Typography - Mobile Optimized */
.card-title {
  font-size: 0.95rem;
  line-height: 1.3;
}

.product-description {
  font-size: 0.8rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.4;
}

.current-price {
  font-size: 1rem;
}

.product-meta {
  font-size: 0.75rem;
}

.review-count {
  font-size: 0.75rem;
}

/* Badges - Mobile Optimized */
.badge-mobile {
  font-size: 0.65rem;
  padding: 0.25rem 0.4rem;
}

/* Buttons - Mobile Optimized */
.add-to-cart-btn {
  padding: 0.6rem 0.75rem;
  font-size: 0.85rem;
  min-height: 44px;
  border-radius: 0.5rem;
  font-weight: 600;
}

/* Filter sidebar - Mobile */
.filters-sidebar {
  background: #f8f9fa;
  border-radius: 0.75rem;
  padding: 1rem;
  position: static !important;
}

.filter-title {
  color: var(--eco-primary);
  border-bottom: 2px solid var(--eco-secondary);
  padding-bottom: 0.5rem;
  font-size: 1rem;
}

.filter-section {
  margin-bottom: 1.5rem;
}

/* Search and Controls - Mobile */
.search-input,
.sort-select {
  padding: 0.75rem;
  font-size: 1rem;
  min-height: 48px;
  border-radius: 0.5rem;
}

.search-box i {
  z-index: 5;
}

.results-count {
  font-size: 0.85rem;
}

/* Stars - Mobile */
.star-icon {
  font-size: 0.8rem;
}

/* Pagination - Mobile */
.pagination {
  font-size: 0.9rem;
}

.page-link {
  padding: 0.5rem 0.75rem;
  min-height: 44px;
  color: var(--eco-primary);
  border-color: var(--eco-secondary);
}

.pagination .page-item.active .page-link {
  background-color: var(--eco-primary);
  border-color: var(--eco-primary);
}

.pagination .page-link:hover {
  background-color: var(--eco-secondary);
  border-color: var(--eco-secondary);
  color: var(--eco-dark);
}

/* Tablet Styles (768px+) */
@media (min-width: 768px) {
  .page-title {
    font-size: 2.5rem;
  }
  
  .page-subtitle {
    font-size: 1.1rem;
  }
  
  .product-image {
    height: 220px;
  }
  
  .card-title {
    font-size: 1.1rem;
  }
  
  .product-description {
    font-size: 0.9rem;
    -webkit-line-clamp: 3;
  }
  
  .current-price {
    font-size: 1.25rem;
  }
  
  .product-meta {
    font-size: 0.85rem;
  }
  
  .filters-sidebar {
    padding: 1.5rem;
    position: sticky !important;
    top: 100px;
  }
  
  .product-overlay {
    opacity: 0;
  }
  
  .product-card:hover .product-overlay {
    opacity: 1;
  }
}

/* Desktop Styles (1024px+) */
@media (min-width: 1024px) {
  .product-image {
    height: 250px;
  }
  
  .card-title {
    font-size: 1.25rem;
  }
  
  .product-description {
    font-size: 1rem;
  }
}

/* Touch Optimization */
@media (hover: none) and (pointer: coarse) {
  .form-check-input,
  .form-select,
  .form-control,
  .btn {
    min-height: 44px;
  }
  
  .product-card:hover {
    transform: none;
  }
  
  .product-card:hover .product-image img {
    transform: none;
  }
}

/* Focus states for accessibility */
.btn:focus,
.form-control:focus,
.form-select:focus,
.form-check-input:focus {
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
  border-color: var(--eco-primary);
}
</style>