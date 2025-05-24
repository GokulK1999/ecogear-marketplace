<template>
  <div class="home-page">
    <!-- Hero Section - Demonstrates Grid System -->
    <section class="hero-section">
      <div class="container">
        <div class="row align-items-center min-vh-75">
          <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="hero-content fade-in">
              <h1 class="display-4 fw-bold text-white mb-4">
                Gear Up for 
                <span class="text-warning">Sustainable</span> 
                Adventures
              </h1>
              <p class="lead text-white mb-4">
                Discover premium outdoor equipment that doesn't compromise on performance or our planet. 
                From eco-friendly tents to solar-powered gadgets, we've got everything for your next adventure.
              </p>
              <div class="hero-buttons">
                <router-link to="/products" class="btn btn-eco-primary btn-lg me-3 mb-2">
                  <i class="bi bi-compass me-2"></i>Shop Now
                </router-link>
                <button class="btn btn-outline-light btn-lg mb-2">
                  <i class="bi bi-play-circle me-2"></i>Learn More
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-4 d-none d-md-block">
            <div class="hero-image text-center">
              <img src="/src/assets/images/hero-outdoor.jpg" alt="Sustainable Outdoor Adventure" class="img-fluid rounded-3 shadow-lg">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Category Navigation - Grid System -->
    <section class="categories-section py-5 bg-light">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <h2 class="display-6 fw-bold text-dark mb-3">Shop by Category</h2>
            <p class="lead text-muted">Sustainable gear for every outdoor adventure</p>
          </div>
        </div>
        
        <!-- Category Grid - Demonstrates Row-Column System -->
        <div class="row g-4">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12" v-for="category in categories" :key="category.id">
            <div class="category-card card h-100 text-center">
              <div class="card-body p-4">
                <div class="category-icon mb-3">
                  <i :class="category.icon" class="display-4 text-success"></i>
                </div>
                <h5 class="card-title fw-bold">{{ category.name }}</h5>
                <p class="card-text text-muted">{{ category.description }}</p>
                <router-link to="/products" class="btn btn-eco-secondary">
                  Explore <i class="bi bi-arrow-right ms-1"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products - Grid System -->
    <section class="featured-products py-5">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <h2 class="display-6 fw-bold text-dark mb-3">Featured Products</h2>
            <p class="lead text-muted">Our most popular sustainable gear</p>
          </div>
        </div>
        
        <!-- Products Grid -->
        <div class="row g-4">
          <div class="col-lg-4 col-md-6 col-12" v-for="product in featuredProducts" :key="product.id">
            <div class="product-card card h-100">
              <img :src="product.image" :alt="product.name" class="card-img-top">
              <div class="card-body d-flex flex-column">
                <div class="mb-auto">
                  <h5 class="card-title fw-bold">{{ product.name }}</h5>
                  <p class="card-text text-muted">{{ product.description }}</p>
                </div>
                <div class="product-footer mt-3">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="h5 text-success fw-bold mb-0">RM{{ formatPrice(product.price) }}</span>
                    <div class="eco-badge">
                      <span class="badge bg-success"><i class="bi bi-leaf me-1"></i>Eco-Friendly</span>
                    </div>
                  </div>
                  <router-link to="/products" class="btn btn-eco-primary w-100">
                    <i class="bi bi-cart-plus me-2"></i>View Details
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Section - Grid System -->
    <section class="newsletter-section py-5 bg-dark text-white">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-md-10 col-12 text-center">
            <h2 class="display-6 fw-bold mb-3">
              <i class="bi bi-envelope-heart text-success me-3"></i>
              Stay Updated
            </h2>
            <p class="lead mb-4">
              Get the latest sustainable gear updates and eco-friendly adventure tips delivered to your inbox.
            </p>
            <form class="newsletter-form" @submit.prevent="subscribeNewsletter">
              <div class="row g-3 justify-content-center">
                <div class="col-md-8 col-12">
                  <input 
                    type="email" 
                    class="form-control form-control-lg" 
                    placeholder="Enter your email address"
                    v-model="newsletterEmail"
                    required
                  >
                </div>
                <div class="col-md-4 col-12">
                  <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-send me-2"></i>Subscribe
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials - Grid System -->
    <section class="testimonials-section py-5 bg-light">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <h2 class="display-6 fw-bold text-dark mb-3">What Adventurers Say</h2>
            <p class="lead text-muted">Real reviews from sustainable outdoor enthusiasts</p>
          </div>
        </div>
        
        <div class="row g-4">
          <div class="col-lg-4 col-md-6 col-12" v-for="testimonial in testimonials" :key="testimonial.id">
            <div class="testimonial-card card h-100">
              <div class="card-body text-center p-4">
                <div class="testimonial-avatar mb-3">
                  <i class="bi bi-person-circle display-4 text-muted"></i>
                </div>
                <blockquote class="blockquote mb-3">
                  <p class="mb-0">"{{ testimonial.review }}"</p>
                </blockquote>
                <div class="testimonial-rating mb-2">
                  <i class="bi bi-star-fill text-warning" v-for="star in testimonial.rating" :key="star"></i>
                </div>
                <footer class="blockquote-footer">
                  <strong>{{ testimonial.name }}</strong>
                  <cite class="text-muted">{{ testimonial.location }}</cite>
                </footer>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return {
      newsletterEmail: '',
      // Categories data - demonstrates use of arrays
      categories: [
        {
          id: 1,
          name: 'Camping Gear',
          description: 'Eco-friendly tents, sleeping bags, and camp essentials',
          icon: 'bi bi-tree'
        },
        {
          id: 2,
          name: 'Hiking Equipment',
          description: 'Sustainable backpacks, boots, and trail accessories',
          icon: 'bi bi-mountain'
        },
        {
          id: 3,
          name: 'Water Sports',
          description: 'Environmentally conscious kayaks and water gear',
          icon: 'bi bi-water'
        },
        {
          id: 4,
          name: 'Solar Tech',
          description: 'Solar-powered chargers and sustainable electronics',
          icon: 'bi bi-sun'
        }
      ],
      // Featured products with RM prices - demonstrates arrays and data structure
      featuredProducts: [
        {
          id: 1,
          name: 'EcoTent Pro 4',
          description: 'Premium 4-person tent made from recycled materials',
          price: 1409.95,
          image: '/src/assets/images/eco-tent.jpg'
        },
        {
          id: 2,
          name: 'Solar Backpack 35L',
          description: 'Hiking backpack with integrated solar charging panel',
          price: 892.95,
          image: '/src/assets/images/solar-backpack.jpg'
        },
        {
          id: 3,
          name: 'Bamboo Water Bottle',
          description: 'Insulated water bottle made from sustainable bamboo',
          price: 164.45,
          image: '/src/assets/images/bamboo-bottle.jpg'
        }
      ],
      // Testimonials - demonstrates arrays
      testimonials: [
        {
          id: 1,
          name: 'Sarah Johnson',
          location: 'Colorado, USA',
          rating: 5,
          review: 'Amazing quality gear that actually helps the environment. My EcoTent has survived multiple camping trips!'
        },
        {
          id: 2,
          name: 'Mike Chen',
          location: 'British Columbia, Canada',
          rating: 5,
          review: 'The solar backpack is a game-changer for long hiking trips. Never worried about phone battery again.'
        },
        {
          id: 3,
          name: 'Emma Rodriguez',
          location: 'California, USA',
          rating: 5,
          review: 'Love supporting a company that cares about sustainability. Great products, great mission!'
        }
      ]
    }
  },
  methods: {
    // Newsletter subscription method
    subscribeNewsletter() {
      if (this.newsletterEmail) {
        // Here we would normally send to backend
        alert(`Thank you for subscribing with email: ${this.newsletterEmail}`)
        this.newsletterEmail = ''
      }
    },
    
    // Format price - demonstrates filters
    formatPrice(price) {
      return price.toFixed(2)
    }
  }
}
</script>

<style scoped>
/* Component-specific styles */
.min-vh-75 {
  min-height: 75vh;
}

.category-card {
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.category-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.hero-image img {
  max-height: 400px;
  object-fit: cover;
}

.testimonial-card {
  border: none;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

@media (max-width: 768px) {
  .hero-buttons .btn {
    display: block;
    width: 100%;
    margin-bottom: 0.5rem;
  }
  
  .display-4 {
    font-size: 2.5rem;
  }
}
</style>