<template>
  <div class="home-page">
    <!-- Hero Section - Mobile Optimized -->
    <section class="hero-section">
      <div class="container">
        <div class="row align-items-center min-vh-mobile">
          <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="hero-content fade-in">
              <h1 class="hero-title fw-bold text-white mb-3 mb-md-4">
                Gear Up for 
                <span class="text-warning">Sustainable</span> 
                Adventures
              </h1>
              <p class="hero-subtitle text-white mb-3 mb-md-4">
                Discover premium outdoor equipment that doesn't compromise on performance or our planet. 
                From eco-friendly tents to solar-powered gadgets, we've got everything for your next adventure.
              </p>
              <div class="hero-buttons">
                <router-link to="/products" class="btn btn-eco-primary btn-lg btn-mobile me-2 me-md-3 mb-2 mb-md-2">
                  <i class="bi bi-compass me-2"></i>Shop Now
                </router-link>
                <button class="btn btn-outline-light btn-lg btn-mobile mb-2 mb-md-2">
                  <i class="bi bi-play-circle me-2"></i>Learn More
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-4 d-none d-md-block">
            <div class="hero-image text-center">
              <img src="/public/images/hero-outdoor.jpg" alt="Sustainable Outdoor Adventure" class="img-fluid rounded-3 shadow-lg">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Category Navigation - Mobile Optimized -->
    <section class="categories-section py-4 py-md-5 bg-light">
      <div class="container">
        <div class="row mb-4 mb-md-5">
          <div class="col-12 text-center">
            <h2 class="section-title fw-bold text-dark mb-2 mb-md-3">Shop by Category</h2>
            <p class="section-subtitle text-muted">Sustainable gear for every outdoor adventure</p>
          </div>
        </div>
        
        <!-- Category Grid - Mobile First -->
        <div class="row g-3 g-md-4">
          <div class="col-6 col-md-6 col-lg-3" v-for="category in categories" :key="category.id">
            <div class="category-card card h-100 text-center">
              <div class="card-body p-3 p-md-4">
                <div class="category-icon mb-2 mb-md-3">
                  <i :class="category.icon" class="category-icon-size text-success"></i>
                </div>
                <h5 class="card-title fw-bold category-title">{{ category.name }}</h5>
                <p class="card-text text-muted category-description d-none d-md-block">{{ category.description }}</p>
                <router-link to="/products" class="btn btn-eco-secondary btn-mobile">
                  Explore <i class="bi bi-arrow-right ms-1"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products - Mobile Optimized -->
    <section class="featured-products py-4 py-md-5">
      <div class="container">
        <div class="row mb-4 mb-md-5">
          <div class="col-12 text-center">
            <h2 class="section-title fw-bold text-dark mb-2 mb-md-3">Featured Products</h2>
            <p class="section-subtitle text-muted">Our most popular sustainable gear</p>
          </div>
        </div>
        
        <!-- Products Grid - Mobile Optimized -->
        <div class="row g-3 g-md-4">
          <div class="col-12 col-md-6 col-lg-4" v-for="product in featuredProducts" :key="product.id">
            <div class="product-card card h-100">
              <div class="product-image-container">
                <img :src="product.image" :alt="product.name" class="card-img-top product-image">
              </div>
              <div class="card-body d-flex flex-column p-3 p-md-4">
                <div class="mb-auto">
                  <h5 class="card-title fw-bold product-title">{{ product.name }}</h5>
                  <p class="card-text text-muted product-description">{{ product.description }}</p>
                </div>
                <div class="product-footer mt-3">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="product-price text-success fw-bold mb-0">RM{{ formatPrice(product.price) }}</span>
                    <div class="eco-badge d-none d-sm-block">
                      <span class="badge bg-success"><i class="bi bi-leaf me-1"></i>Eco-Friendly</span>
                    </div>
                  </div>
                  <router-link to="/products" class="btn btn-eco-primary w-100 btn-mobile">
                    <i class="bi bi-cart-plus me-2"></i>View Details
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Section - Mobile Optimized -->
    <section class="newsletter-section py-4 py-md-5 bg-dark text-white">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-10 col-lg-8 text-center">
            <h2 class="section-title fw-bold mb-3">
              <i class="bi bi-envelope-heart text-success me-2 me-md-3"></i>
              Stay Updated
            </h2>
            <p class="section-subtitle mb-3 mb-md-4">
              Get the latest sustainable gear updates and eco-friendly adventure tips delivered to your inbox.
            </p>
            <form class="newsletter-form" @submit.prevent="subscribeNewsletter">
              <div class="row g-2 g-md-3 justify-content-center">
                <div class="col-12 col-md-8">
                  <input 
                    type="email" 
                    class="form-control form-control-lg newsletter-input" 
                    placeholder="Enter your email address"
                    v-model="newsletterEmail"
                    required
                  >
                </div>
                <div class="col-12 col-md-4">
                  <button type="submit" class="btn btn-success btn-lg w-100 btn-mobile">
                    <i class="bi bi-send me-2"></i>Subscribe
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials - Mobile Optimized -->
    <section class="testimonials-section py-4 py-md-5 bg-light">
      <div class="container">
        <div class="row mb-4 mb-md-5">
          <div class="col-12 text-center">
            <h2 class="section-title fw-bold text-dark mb-2 mb-md-3">What Adventurers Say</h2>
            <p class="section-subtitle text-muted">Real reviews from sustainable outdoor enthusiasts</p>
          </div>
        </div>
        
        <!-- Testimonials Grid - Mobile Optimized -->
        <div class="row g-3 g-md-4">
          <div class="col-12 col-md-6 col-lg-4" v-for="testimonial in testimonials" :key="testimonial.id">
            <div class="testimonial-card card h-100">
              <div class="card-body text-center p-3 p-md-4">
                <div class="testimonial-avatar mb-2 mb-md-3">
                  <img 
                    :src="testimonial.avatar" 
                    :alt="testimonial.name"
                    class="testimonial-avatar-image rounded-circle"
                    @error="$event.target.style.display='none'; $event.target.nextElementSibling.style.display='inline-block'"
                  >
                  <i class="bi bi-person-circle testimonial-avatar-size text-muted" style="display: none;"></i>
                </div>
                <blockquote class="blockquote mb-2 mb-md-3">
                  <p class="mb-0 testimonial-text">"{{ testimonial.review }}"</p>
                </blockquote>
                <div class="testimonial-rating mb-2">
                  <i class="bi bi-star-fill text-warning testimonial-star" v-for="star in testimonial.rating" :key="star"></i>
                </div>
                <footer class="blockquote-footer">
                  <strong class="testimonial-name">{{ testimonial.name }}</strong>
                  <cite class="text-muted d-block d-sm-inline testimonial-location">{{ testimonial.location }}</cite>
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
          icon: 'bi bi-backpack'
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
          image: '/public/images/eco-tent.jpg'
        },
        {
          id: 2,
          name: 'Solar Backpack 35L',
          description: 'Hiking backpack with integrated solar charging panel',
          price: 892.95,
          image: '/public/images/solar-backpack.jpg'
        },
        {
          id: 3,
          name: 'Bamboo Water Bottle',
          description: 'Insulated water bottle made from sustainable bamboo',
          price: 164.45,
          image: '/public/images/bamboo-bottle.jpg'
        }
      ],
      // Testimonials - demonstrates arrays
      testimonials: [
        {
          id: 1,
          name: 'Sarah Johnson',
          location: 'Colorado, USA',
          rating: 5,
          review: 'Amazing quality gear that actually helps the environment. My EcoTent has survived multiple camping trips!',
          avatar: '/public/images/sarah-johnson.jpg'
        },
        {
          id: 2,
          name: 'Mike Chen',
          location: 'British Columbia, Canada',
          rating: 5,
          review: 'The solar backpack is a game-changer for long hiking trips. Never worried about phone battery again.',
          avatar: '/public/images/mike-chen.jpg'
        },
        {
          id: 3,
          name: 'Emma Rodriguez',
          location: 'California, USA',
          rating: 5,
          review: 'Love supporting a company that cares about sustainability. Great products, great mission!',
          avatar: '/public/images/emma-rodriguez.jpg'
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
/* Mobile-First Responsive Design */

/* Base Mobile Styles (320px+) */
.min-vh-mobile {
  min-height: 60vh;
}

/* Hero Section - Mobile Optimized */
.hero-title {
  font-size: 2rem;
  line-height: 1.2;
}

.hero-subtitle {
  font-size: 1rem;
  line-height: 1.4;
}

.hero-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.btn-mobile {
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 0.5rem;
  font-weight: 600;
}

.hero-image img {
  max-height: 300px;
  object-fit: cover;
  width: 100%;
}

/* Section Titles - Mobile Optimized */
.section-title {
  font-size: 1.75rem;
}

.section-subtitle {
  font-size: 0.95rem;
}

/* Category Cards - Mobile Optimized */
.category-card {
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
  border-radius: 1rem;
}

.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.category-icon-size {
  font-size: 2.5rem;
}

.category-title {
  font-size: 1rem;
  margin-bottom: 0.5rem;
}

.category-description {
  font-size: 0.85rem;
}

/* Product Cards - Mobile Optimized */
.product-card {
  border: none;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
  border-radius: 1rem;
  overflow: hidden;
  transition: all 0.3s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.product-image-container {
  height: 200px;
  overflow: hidden;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

.product-title {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

.product-description {
  font-size: 0.9rem;
  line-height: 1.4;
}

.product-price {
  font-size: 1.25rem;
}

/* Newsletter Section - Mobile Optimized */
.newsletter-input {
  border-radius: 0.5rem;
  padding: 0.75rem;
}

/* Testimonials - Mobile Optimized */
.testimonial-card {
  border: none;
  box-shadow: 0 2px 15px rgba(0,0,0,0.08);
  border-radius: 1rem;
}

.testimonial-avatar-size {
  font-size: 2.5rem;
}

.testimonial-text {
  font-size: 0.9rem;
  line-height: 1.4;
}

.testimonial-star {
  font-size: 0.9rem;
}

.testimonial-name {
  font-size: 0.95rem;
}

.testimonial-location {
  font-size: 0.85rem;
}

.testimonial-avatar-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border: 3px solid #e9ecef;
  transition: transform 0.3s ease;
}

.testimonial-avatar-image:hover {
  transform: scale(1.05);
}

@media (min-width: 768px) {
  .testimonial-avatar-image {
    width: 100px;
    height: 100px;
  }
}

/* Tablet Styles (768px+) */
@media (min-width: 768px) {
  .min-vh-mobile {
    min-height: 70vh;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.1rem;
  }
  
  .hero-buttons {
    flex-direction: row;
    gap: 1rem;
  }
  
  .section-title {
    font-size: 2.25rem;
  }
  
  .section-subtitle {
    font-size: 1.1rem;
  }
  
  .category-icon-size {
    font-size: 3rem;
  }
  
  .category-title {
    font-size: 1.1rem;
  }
  
  .product-image-container {
    height: 250px;
  }
  
  .product-title {
    font-size: 1.25rem;
  }
  
  .product-description {
    font-size: 1rem;
  }
  
  .testimonial-avatar-size {
    font-size: 3rem;
  }
  
  .testimonial-text {
    font-size: 1rem;
  }
  
  .hero-image img {
    max-height: 400px;
  }
}

/* Desktop Styles (1024px+) */
@media (min-width: 1024px) {
  .min-vh-mobile {
    min-height: 75vh;
  }
  
  .hero-title {
    font-size: 3rem;
  }
  
  .hero-subtitle {
    font-size: 1.25rem;
  }
  
  .section-title {
    font-size: 2.5rem;
  }
  
  .section-subtitle {
    font-size: 1.25rem;
  }
  
  .category-icon-size {
    font-size: 3.5rem;
  }
  
  .category-title {
    font-size: 1.25rem;
  }
  
  .product-image-container {
    height: 280px;
  }
  
  .testimonial-avatar-size {
    font-size: 3.5rem;
  }
}

/* Touch Optimization */
@media (hover: none) and (pointer: coarse) {
  .btn-mobile {
    min-height: 48px;
    padding: 0.875rem 2rem;
  }
  
  .category-card:hover {
    transform: none;
  }
  
  .product-card:hover {
    transform: none;
  }
  
  .product-card:hover .product-image {
    transform: none;
  }
}

/* High-resolution displays */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .hero-image img,
  .product-image {
    image-rendering: -webkit-optimize-contrast;
  }
}

/* Landscape phone optimization */
@media (max-height: 500px) and (orientation: landscape) {
  .min-vh-mobile {
    min-height: 90vh;
  }
  
  .py-4 {
    padding-top: 2rem !important;
    padding-bottom: 2rem !important;
  }
}

/* Animation for mobile */
.fade-in {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Focus states for accessibility */
.btn:focus,
.form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
  border-color: var(--eco-primary);
}
</style>