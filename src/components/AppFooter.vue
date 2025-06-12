<template>
  <footer class="footer bg-dark text-white">
    <div class="container">
      <!-- Main Footer Content -->
      <div class="row py-5">
        <!-- Company Info -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="footer-brand mb-3">
            <i class="bi bi-leaf-fill text-success me-2 fs-3"></i>
            <span class="fs-4 fw-bold">EcoGear</span>
          </div>
          <p class="text-light">
            Your trusted partner for sustainable outdoor adventures. 
            We provide premium eco-friendly gear that doesn't compromise on performance or our planet.
          </p>
          <div class="social-links mt-3">
            <a href="#" class="text-light me-3 fs-5"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-light me-3 fs-5"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-light me-3 fs-5"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-light me-3 fs-5"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-success mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <router-link to="/" class="text-light text-decoration-none">
                <i class="bi bi-house me-2"></i>Home
              </router-link>
            </li>
            <li class="mb-2">
              <router-link to="/products" class="text-light text-decoration-none">
                <i class="bi bi-grid me-2"></i>Products
              </router-link>
            </li>
            <li class="mb-2">
              <router-link to="/cart" class="text-light text-decoration-none">
                <i class="bi bi-cart me-2"></i>Cart
              </router-link>
            </li>
            <li class="mb-2">
              <router-link to="/account" class="text-light text-decoration-none">
                <i class="bi bi-person me-2"></i>Account
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-success mb-3">Categories</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-tree me-2"></i>Camping Gear
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-backpack me-2"></i>Hiking Equipment
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-water me-2"></i>Water Sports
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-sun me-2"></i>Solar Tech
              </a>
            </li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-success mb-3">Support</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-question-circle me-2"></i>Help Center
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-truck me-2"></i>Shipping Info
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-arrow-return-left me-2"></i>Returns
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light text-decoration-none">
                <i class="bi bi-envelope me-2"></i>Contact Us
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="text-success mb-3">Contact</h5>
          <div class="contact-info">
            <div class="mb-2">
              <i class="bi bi-geo-alt text-success me-2"></i>
              <small>Swinburne University of Technology</small>
            </div>
            <div class="mb-2">
              <i class="bi bi-telephone text-success me-2"></i>
              <small>+1 (555) 112-6745</small>
            </div>
            <div class="mb-2">
              <i class="bi bi-envelope text-success me-2"></i>
              <small>101233195@students.swinburne.edu.my</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="row border-top border-secondary pt-4 pb-3">
        <div class="col-md-6 mb-2">
          <small class="text-muted">
            © 2024 EcoGear. All rights reserved. 
            <span class="text-success">Made by Gokul Kalla with ♻️ for a sustainable future.</span>
          </small>
        </div>
        <div class="col-md-6 text-md-end">
          <small class="text-muted">
            <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
            <a href="#" class="text-light text-decoration-none me-3">Terms of Service</a>
            <a href="#" class="text-light text-decoration-none">Cookie Policy</a>
          </small>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter'
}
</script>

<style scoped>
.footer {
  margin-top: auto;
}

.footer-brand {
  display: flex;
  align-items: center;
}

.social-links a {
  transition: all 0.3s ease;
}

.social-links a:hover {
  color: var(--eco-success) !important;
  transform: translateY(-2px);
}

.footer a:hover {
  color: var(--eco-success) !important;
}

.contact-info small {
  line-height: 1.6;
}

@media (max-width: 768px) {
  .footer .col-lg-2, .footer .col-lg-4 {
    margin-bottom: 2rem;
  }
  
  .text-md-end {
    text-align: center !important;
  }
}
</style>