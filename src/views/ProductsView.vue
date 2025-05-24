<template>
  <div class="products-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-2">Sustainable Outdoor Gear</h1>
            <p class="lead text-muted mb-0">
              Discover {{ filteredProducts.length }} eco-friendly products for your next adventure
            </p>
          </div>
          <div class="col-md-4 text-md-end">
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

    <div class="container py-4">
      <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="filters-sidebar sticky-top" style="top: 100px;">
            
            <!-- Category Filter - Demonstrates Selection -->
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
            <div class="row g-3 align-items-center">
              <!-- Search Input -->
              <div class="col-md-6">
                <div class="search-box position-relative">
                  <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                  <input 
                    type="text" 
                    class="form-control ps-5" 
                    placeholder="Search products..."
                    v-model="searchQuery"
                    @input="filterProducts"
                  >
                </div>
              </div>

              <!-- Sort Dropdown -->
              <div class="col-md-3">
                <select 
                  class="form-select" 
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
              <div class="col-md-3 text-md-end">
                <small class="text-muted">
                  Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} products
                </small>
              </div>
            </div>
          </div>

          <!-- Products Grid - Demonstrates Repetition -->
          <div class="products-grid" v-if="paginatedProducts.length > 0">
            <div class="row g-4">
              <div 
                class="col-lg-4 col-md-6 col-sm-6 col-12" 
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
                        class="badge" 
                        :class="product.inStock ? 'bg-success' : 'bg-danger'"
                      >
                        {{ product.inStock ? 'In Stock' : 'Out of Stock' }}
                      </span>
                    </div>

                    <!-- Sustainability Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                      <span class="badge bg-primary">
                        <i class="bi bi-leaf me-1"></i>{{ product.sustainability }}% Eco
                      </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="product-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                      <div class="quick-actions">
                        <button 
                          class="btn btn-eco-primary btn-sm me-2"
                          @click="addToCart(product)"
                          :disabled="!product.inStock"
                        >
                          <i class="bi bi-cart-plus"></i>
                        </button>
                        <button class="btn btn-outline-light btn-sm">
                          <i class="bi bi-eye"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Product Info -->
                  <div class="card-body d-flex flex-column">
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
                            class="bi" 
                            :class="i <= product.rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'"
                            v-for="i in 5" 
                            :key="i"
                          ></i>
                        </div>
                        <small class="text-muted">({{ product.reviews }} reviews)</small>
                      </div>
                    </div>

                    <!-- Description -->
                    <p class="card-text text-muted small mb-3">{{ product.description }}</p>

                    <!-- Features -->
                    <div class="product-features mb-3">
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
                          <small class="original-price text-muted text-decoration-line-through ms-2" v-if="product.originalPrice > product.price">
                            RM{{ formatPrice(product.originalPrice) }}
                          </small>
                        </div>
                        <small class="text-muted">{{ product.weight }}</small>
                      </div>

                      <button 
                        class="btn btn-eco-primary w-100"
                        @click="addToCart(product)"
                        :disabled="!product.inStock"
                      >
                        <i class="bi bi-cart-plus me-2"></i>
                        {{ product.inStock ? 'Add to Cart' : 'Out of Stock' }}
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

          <!-- Pagination - Demonstrates Pagination -->
          <nav aria-label="Products pagination" v-if="totalPages > 1" class="mt-5">
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
  </div>
</template>

<script>
// Import JSON data
import productsData from '@/assets/data/products.json'

export default {
  name: 'ProductsView',
  data() {
    return {
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
        // Here we would normally add to cart store/state
        alert(`${product.name} added to cart!`)
      }
    }
  },
  
  // Initialize data when component mounts
  mounted() {
    this.filterProducts()
  }
}
</script>

<style scoped>
/* Product card styles */
.product-card {
  transition: all 0.3s ease;
  border: none;
  overflow: hidden;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.product-image {
  overflow: hidden;
  height: 250px;
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
  opacity: 0;
  transition: all 0.3s ease;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

/* Filter sidebar */
.filters-sidebar {
  background: #f8f9fa;
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.filter-title {
  color: var(--eco-primary);
  border-bottom: 2px solid var(--eco-secondary);
  padding-bottom: 0.5rem;
}

/* Search box */
.search-box i {
  z-index: 5;
}

/* Pagination */
.pagination .page-link {
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

/* Stars rating */
.stars i {
  font-size: 0.9rem;
}

/* Price styling */
.current-price {
  font-size: 1.25rem;
}

/* Responsive design */
@media (max-width: 768px) {
  .filters-sidebar {
    position: static !important;
    margin-bottom: 2rem;
  }
  
  .product-overlay {
    opacity: 1;
  }
  
  .products-controls .row > div {
    margin-bottom: 1rem;
  }
}

/* Badge styles */
.badge {
  font-size: 0.75rem;
}

/* Loading and transitions */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>