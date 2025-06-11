<template>
  <div class="login-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-dark mb-2">Welcome Back</h1>
            <p class="lead text-muted">Sign in to your EcoGear account</p>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="login-card card shadow-lg border-0">
            <div class="card-body p-5">
              <!-- Login Header -->
              <div class="text-center mb-4">
                <div class="login-icon mb-3">
                  <i class="bi bi-person-circle display-4 text-success"></i>
                </div>
                <h3 class="fw-bold text-dark">Sign In</h3>
                <p class="text-muted">Continue your sustainable journey</p>
              </div>

              <!-- Login Form -->
              <form @submit.prevent="submitLogin" class="needs-validation" novalidate>
                
                <!-- Email/Username -->
                <div class="mb-3">
                  <label for="loginEmail" class="form-label">
                    <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                  </label>
                  <input 
                    type="email" 
                    class="form-control form-control-lg" 
                    id="loginEmail"
                    v-model="loginData.email"
                    :class="{ 'is-invalid': errors.email, 'is-valid': loginData.email && !errors.email }"
                    @input="validateField('email')"
                    placeholder="your.email@example.com"
                    required
                    autocomplete="email"
                  >
                  <div class="invalid-feedback">
                    {{ errors.email }}
                  </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                  <label for="loginPassword" class="form-label">
                    <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                  </label>
                  <div class="password-input-group">
                    <input 
                      :type="showPassword ? 'text' : 'password'" 
                      class="form-control form-control-lg" 
                      id="loginPassword"
                      v-model="loginData.password"
                      :class="{ 'is-invalid': errors.password, 'is-valid': loginData.password && !errors.password }"
                      @input="validateField('password')"
                      placeholder="Enter your password"
                      required
                      autocomplete="current-password"
                    >
                    <button 
                      type="button" 
                      class="btn btn-outline-secondary password-toggle"
                      @click="togglePasswordVisibility"
                      tabindex="-1"
                    >
                      <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                  <div class="invalid-feedback">
                    {{ errors.password }}
                  </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="row mb-4">
                  <div class="col-6">
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="rememberMe"
                        v-model="loginData.rememberMe"
                      >
                      <label class="form-check-label" for="rememberMe">
                        <i class="bi bi-heart me-1"></i>Remember me
                      </label>
                    </div>
                  </div>
                  <div class="col-6 text-end">
                    <a href="#" class="text-decoration-none" @click="forgotPassword">
                      <i class="bi bi-question-circle me-1"></i>Forgot Password?
                    </a>
                  </div>
                </div>

                <!-- Error Message -->
                <div v-if="loginError" class="alert alert-danger mb-3" role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  {{ loginError }}
                </div>

                <!-- Success Message -->
                <div v-if="loginSuccess" class="alert alert-success mb-3" role="alert">
                  <i class="bi bi-check-circle me-2"></i>
                  {{ loginSuccess }}
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-4">
                  <button 
                    type="submit" 
                    class="btn btn-eco-primary btn-lg"
                    :disabled="isLoggingIn || !isFormValid"
                  >
                    <span v-if="isLoggingIn">
                      <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                      Signing In...
                    </span>
                    <span v-else>
                      <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </span>
                  </button>
                </div>

                <!-- Social Login (Optional) -->
                <div class="social-login mb-4">
                  <div class="divider mb-3">
                    <span class="divider-text text-muted">Or sign in with</span>
                  </div>
                  
                  <div class="row g-2">
                    <div class="col-6">
                      <button type="button" class="btn btn-outline-primary w-100">
                        <i class="bi bi-google me-2"></i>Google
                      </button>
                    </div>
                    <div class="col-6">
                      <button type="button" class="btn btn-outline-dark w-100">
                        <i class="bi bi-facebook me-2"></i>Facebook
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                  <p class="text-muted mb-0">
                    Don't have an account? 
                    <router-link to="/register" class="text-decoration-none fw-bold">
                      Create Account
                    </router-link>
                  </p>
                </div>
              </form>
            </div>
          </div>

          <!-- Registration Info -->
          <div class="registration-info card mt-4 border-success">
            <div class="card-header bg-success text-white">
              <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>New to EcoGear?
              </h6>
            </div>
            <div class="card-body">
              <p class="mb-2">Create an account to start your sustainable journey!</p>
              <small class="text-muted">
                Registration is free and takes less than 2 minutes. 
                You can login immediately after registering.
              </small>
              <div class="mt-3">
                <router-link to="/register" class="btn btn-success btn-sm">
                  <i class="bi bi-person-plus me-1"></i>Create Account
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      // Login form data
      loginData: {
        email: '',
        password: '',
        rememberMe: false
      },
      
      // Form validation
      errors: {},
      
      // Form state
      isLoggingIn: false,
      showPassword: false,
      loginError: '',
      loginSuccess: '',
      
      // Demo credentials
      demoCredentials: {
        email: 'gokul@ecogear.com',
        password: 'EcoDemo123'
      }
    }
  },
  
  computed: {
    // Form validation status
    isFormValid() {
      return this.loginData.email && 
             this.loginData.password && 
             Object.keys(this.errors).length === 0
    }
  },
  
  methods: {
    // Toggle password visibility
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    },
    
    // Field validation - Vue 3 reactive approach
    validateField(fieldName) {
      const value = this.loginData[fieldName]
      
      switch (fieldName) {
        case 'email':
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Email is required' }
          } else if (!emailRegex.test(value)) {
            this.errors = { ...this.errors, [fieldName]: 'Please enter a valid email address' }
          } else {
            // Remove error
            const newErrors = { ...this.errors }
            delete newErrors[fieldName]
            this.errors = newErrors
          }
          break
          
        case 'password':
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Password is required' }
          } else if (value.length < 6) {
            this.errors = { ...this.errors, [fieldName]: 'Password must be at least 6 characters' }
          } else {
            // Remove error
            const newErrors = { ...this.errors }
            delete newErrors[fieldName]
            this.errors = newErrors
          }
          break
      }
    },

    // Validate entire form
    validateForm() {
      this.errors = {}
      
      this.validateField('email')
      this.validateField('password')
      
      return Object.keys(this.errors).length === 0
    },
    
    // Submit login
    async submitLogin() {
      // Clear previous messages
      this.loginError = ''
      this.loginSuccess = ''
      
      // Validate form
      if (!this.validateForm()) {
        return
      }
      
      this.isLoggingIn = true
      
      try {
        // Simulate login API call
        await this.simulateLogin()
        
        // Show success message
        this.loginSuccess = 'Login successful! Redirecting...'
        
        // Simulate redirect after 1 second
        setTimeout(() => {
          this.$router.push('/account')
        }, 1000)
        
      } catch (error) {
        this.loginError = error.message
      } finally {
        this.isLoggingIn = false
      }
    },
    
    // Real backend login API call
    async simulateLogin() {
      try {
        const response = await fetch('http://localhost:8000/api/auth/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            email: this.loginData.email,
            password: this.loginData.password
          })
        });

        const result = await response.json();

        if (result.success) {
          // Store user data
          const userData = {
            email: result.data.user.email,
            name: result.data.user.first_name + ' ' + result.data.user.last_name,
            loginTime: new Date().toISOString(),
            token: result.data.token,
            user_id: result.data.user.id
          };
          
          localStorage.setItem('ecogear_user', JSON.stringify(userData));
          
          // Trigger navbar update
          window.dispatchEvent(new Event('userLoggedIn'));
          
          return Promise.resolve();
        } else {
          throw new Error(result.message);
        }
      } catch (error) {
        console.error('Login error:', error);
        throw new Error(error.message || 'Login failed. Please check your connection.');
      }
    },
    
    // Forgot password
    forgotPassword() {
      alert('Forgot password functionality would be implemented here. For demo, use: demo@ecogear.com / password')
    }
  },
  
  // Clear messages when component is destroyed
  beforeDestroy() {
    this.loginError = ''
    this.loginSuccess = ''
  }
}
</script>

<style scoped>
/* Login form styles */
.login-card {
  border-radius: 1rem;
}

.login-icon i {
  color: var(--eco-primary);
}

/* Password input styling */
.password-input-group {
  position: relative;
}

.password-toggle {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  z-index: 4;
}

/* Social login divider */
.divider {
  position: relative;
  text-align: center;
}

.divider:before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: #e9ecef;
}

.divider-text {
  background: white;
  padding: 0 1rem;
  font-size: 0.875rem;
}

/* Demo credentials card */
.registration-info {
  border-left: 4px solid #0dcaf0;
}

/* Form validation styles */
.form-control.is-valid {
  border-color: var(--eco-success);
}

.form-control.is-valid:focus {
  border-color: var(--eco-success);
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.form-control.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Links */
a {
  color: var(--eco-primary);
}

a:hover {
  color: var(--eco-secondary);
}

/* Remember me checkbox */
.form-check-input:checked {
  background-color: var(--eco-primary);
  border-color: var(--eco-primary);
}

.form-check-input:focus {
  border-color: var(--eco-primary);
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
}

/* Button disabled state */
.btn-eco-primary:disabled {
  background-color: #6c757d;
  border-color: #6c757d;
  opacity: 0.65;
}

/* Responsive design */
@media (max-width: 768px) {
  .card-body {
    padding: 2rem !important;
  }
  
  .password-toggle {
    position: static;
    margin-top: 0.5rem;
    width: 100%;
  }
  
  .display-5 {
    font-size: 2rem;
  }
}

/* Animation */
.login-card {
  animation: fadeInUp 0.5s ease-out;
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

/* Loading spinner */
.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}
</style>