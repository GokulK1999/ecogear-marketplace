<template>
  <div class="register-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-dark mb-2">Create Your Account</h1>
            <p class="lead text-muted">Join the EcoGear community for sustainable adventures</p>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="register-card card shadow-lg border-0">
            <div class="card-body p-5">
              <!-- Registration Header -->
              <div class="text-center mb-4">
                <div class="register-icon mb-3">
                  <i class="bi bi-person-plus-fill display-4 text-success"></i>
                </div>
                <h3 class="fw-bold text-dark">Register for EcoGear</h3>
                <p class="text-muted">Start your sustainable adventure journey</p>
              </div>

              <!-- Registration Form -->
              <form @submit.prevent="submitRegistration" class="needs-validation" novalidate>
                
                <!-- Personal Information Section -->
                <div class="form-section mb-4">
                  <h5 class="section-title text-success fw-bold mb-3">
                    <i class="bi bi-person me-2"></i>Personal Information
                  </h5>
                  
                  <!-- Name Fields -->
                  <div class="row g-3 mb-3">
                    <div class="col-md-6">
                      <label for="firstName" class="form-label">
                        <i class="bi bi-person me-1"></i>First Name <span class="text-danger">*</span>
                      </label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="firstName"
                        v-model="formData.firstName"
                        :class="{ 'is-invalid': errors.firstName, 'is-valid': formData.firstName && !errors.firstName }"
                        @input="validateField('firstName')"
                        required
                      >
                      <div class="invalid-feedback">
                        {{ errors.firstName }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="lastName" class="form-label">
                        <i class="bi bi-person me-1"></i>Last Name <span class="text-danger">*</span>
                      </label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="lastName"
                        v-model="formData.lastName"
                        :class="{ 'is-invalid': errors.lastName, 'is-valid': formData.lastName && !errors.lastName }"
                        @input="validateField('lastName')"
                        required
                      >
                      <div class="invalid-feedback">
                        {{ errors.lastName }}
                      </div>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="mb-3">
                    <label for="email" class="form-label">
                      <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                    </label>
                    <input 
                      type="email" 
                      class="form-control" 
                      id="email"
                      v-model="formData.email"
                      :class="{ 'is-invalid': errors.email, 'is-valid': formData.email && !errors.email }"
                      @input="validateField('email')"
                      placeholder="your.email@example.com"
                      required
                    >
                    <div class="invalid-feedback">
                      {{ errors.email }}
                    </div>
                  </div>

                  <!-- Phone -->
                  <div class="mb-3">
                    <label for="phone" class="form-label">
                      <i class="bi bi-telephone me-1"></i>Phone Number <span class="text-danger">*</span>
                    </label>
                    <input 
                      type="tel" 
                      class="form-control" 
                      id="phone"
                      v-model="formData.phone"
                      :class="{ 'is-invalid': errors.phone, 'is-valid': formData.phone && !errors.phone }"
                      @input="validateField('phone')"
                      placeholder="+60 12-345 6789"
                      required
                    >
                    <div class="invalid-feedback">
                      {{ errors.phone }}
                    </div>
                  </div>

                  <!-- Date of Birth -->
                  <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">
                      <i class="bi bi-calendar me-1"></i>Date of Birth
                    </label>
                    <input 
                      type="date" 
                      class="form-control" 
                      id="dateOfBirth"
                      v-model="formData.dateOfBirth"
                      :class="{ 'is-invalid': errors.dateOfBirth, 'is-valid': formData.dateOfBirth && !errors.dateOfBirth }"
                      @input="validateField('dateOfBirth')"
                    >
                    <div class="invalid-feedback">
                      {{ errors.dateOfBirth }}
                    </div>
                  </div>
                </div>

                <!-- Address Information Section -->
                <div class="form-section mb-4">
                  <h5 class="section-title text-success fw-bold mb-3">
                    <i class="bi bi-geo-alt me-2"></i>Address Information
                  </h5>
                  
                  <!-- Street Address -->
                  <div class="mb-3">
                    <label for="address" class="form-label">
                      <i class="bi bi-house me-1"></i>Street Address <span class="text-danger">*</span>
                    </label>
                    <input 
                      type="text" 
                      class="form-control" 
                      id="address"
                      v-model="formData.address"
                      :class="{ 'is-invalid': errors.address, 'is-valid': formData.address && !errors.address }"
                      @input="validateField('address')"
                      placeholder="123 Jalan Example"
                      required
                    >
                    <div class="invalid-feedback">
                      {{ errors.address }}
                    </div>
                  </div>

                  <!-- City, State, Postal Code -->
                  <div class="row g-3 mb-3">
                    <div class="col-md-6">
                      <label for="city" class="form-label">
                        <i class="bi bi-building me-1"></i>City <span class="text-danger">*</span>
                      </label>
                      <input 
                        type="text" 
                        class="form-control" 
                        id="city"
                        v-model="formData.city"
                        :class="{ 'is-invalid': errors.city, 'is-valid': formData.city && !errors.city }"
                        @input="validateField('city')"
                        placeholder="Kuala Lumpur"
                        required
                      >
                      <div class="invalid-feedback">
                        {{ errors.city }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="state" class="form-label">
                        <i class="bi bi-map me-1"></i>State <span class="text-danger">*</span>
                      </label>
                      <select 
                        class="form-select" 
                        id="state"
                        v-model="formData.state"
                        :class="{ 'is-invalid': errors.state, 'is-valid': formData.state && !errors.state }"
                        @change="validateField('state')"
                        required
                      >
                        <option value="">Select State</option>
                        <option v-for="state in malaysianStates" :key="state" :value="state">
                          {{ state }}
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        {{ errors.state }}
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="postalCode" class="form-label">
                      <i class="bi bi-mailbox me-1"></i>Postal Code <span class="text-danger">*</span>
                    </label>
                    <input 
                      type="text" 
                      class="form-control" 
                      id="postalCode"
                      v-model="formData.postalCode"
                      :class="{ 'is-invalid': errors.postalCode, 'is-valid': formData.postalCode && !errors.postalCode }"
                      @input="validateField('postalCode')"
                      placeholder="50000"
                      required
                    >
                    <div class="invalid-feedback">
                      {{ errors.postalCode }}
                    </div>
                  </div>
                </div>

                <!-- Account Security Section -->
                <div class="form-section mb-4">
                  <h5 class="section-title text-success fw-bold mb-3">
                    <i class="bi bi-shield-lock me-2"></i>Account Security
                  </h5>
                  
                  <!-- Password -->
                  <div class="mb-3">
                    <label for="password" class="form-label">
                      <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                    </label>
                    <div class="password-input-group">
                      <input 
                        :type="showPassword ? 'text' : 'password'" 
                        class="form-control" 
                        id="password"
                        v-model="formData.password"
                        :class="{ 'is-invalid': errors.password, 'is-valid': formData.password && !errors.password }"
                        @input="validateField('password')"
                        required
                      >
                      <button 
                        type="button" 
                        class="btn btn-outline-secondary password-toggle"
                        @click="togglePasswordVisibility"
                      >
                        <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                      </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength mt-2" v-if="formData.password">
                      <div class="strength-bar">
                        <div 
                          class="strength-fill" 
                          :class="passwordStrength.class"
                          :style="{ width: passwordStrength.width }"
                        ></div>
                      </div>
                      <small :class="passwordStrength.class">
                        Password strength: {{ passwordStrength.text }}
                      </small>
                    </div>
                    
                    <div class="invalid-feedback">
                      {{ errors.password }}
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements mt-2">
                      <small class="text-muted">Password must contain:</small>
                      <ul class="password-checklist">
                        <li :class="{ 'valid': passwordChecks.length }">
                          <i :class="passwordChecks.length ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-muted'"></i>
                          At least 8 characters
                        </li>
                        <li :class="{ 'valid': passwordChecks.uppercase }">
                          <i :class="passwordChecks.uppercase ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-muted'"></i>
                          One uppercase letter
                        </li>
                        <li :class="{ 'valid': passwordChecks.lowercase }">
                          <i :class="passwordChecks.lowercase ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-muted'"></i>
                          One lowercase letter
                        </li>
                        <li :class="{ 'valid': passwordChecks.number }">
                          <i :class="passwordChecks.number ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-muted'"></i>
                          One number
                        </li>
                      </ul>
                    </div>
                  </div>

                  <!-- Confirm Password -->
                  <div class="mb-3">
                    <label for="confirmPassword" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i>Confirm Password <span class="text-danger">*</span>
                    </label>
                    <div class="password-input-group">
                        <input 
                        :type="showConfirmPassword ? 'text' : 'password'" 
                        class="form-control" 
                        id="confirmPassword"
                        v-model="formData.confirmPassword"
                        :class="{ 'is-invalid': errors.confirmPassword, 'is-valid': formData.confirmPassword && !errors.confirmPassword }"
                        @input="validateField('confirmPassword')"
                        required
                        >
                        <button 
                        type="button" 
                        class="btn btn-outline-secondary password-toggle"
                        @click="toggleConfirmPasswordVisibility"
                        >
                        <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>
                    </div>    
                    <div class="invalid-feedback">
                      {{ errors.confirmPassword }}
                    </div>
                  </div>
                </div>

                <!-- Terms and Preferences -->
                <div class="form-section mb-4">
                  <h5 class="section-title text-success fw-bold mb-3">
                    <i class="bi bi-gear me-2"></i>Preferences
                  </h5>
                  
                  <!-- Newsletter Subscription -->
                  <div class="form-check mb-3">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="newsletter"
                      v-model="formData.newsletter"
                    >
                    <label class="form-check-label" for="newsletter">
                      <i class="bi bi-envelope me-1"></i>
                      Subscribe to our newsletter for eco-friendly adventure tips and product updates
                    </label>
                  </div>

                  <!-- SMS Notifications -->
                  <div class="form-check mb-3">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="smsNotifications"
                      v-model="formData.smsNotifications"
                    >
                    <label class="form-check-label" for="smsNotifications">
                      <i class="bi bi-phone me-1"></i>
                      Receive SMS notifications for order updates
                    </label>
                  </div>

                  <!-- Terms and Conditions -->
                  <div class="form-check mb-3">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="termsAccepted"
                      v-model="formData.termsAccepted"
                      :class="{ 'is-invalid': errors.termsAccepted }"
                      @change="validateField('termsAccepted')"
                      required
                    >
                    <label class="form-check-label" for="termsAccepted">
                      <i class="bi bi-file-text me-1"></i>
                      I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and 
                      <a href="#" class="text-decoration-none">Privacy Policy</a> <span class="text-danger">*</span>
                    </label>
                    <div class="invalid-feedback">
                      {{ errors.termsAccepted }}
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-3">
                  <button 
                    type="submit" 
                    class="btn btn-eco-primary btn-lg"
                    :disabled="isSubmitting || !isFormValid"
                  >
                    <span v-if="isSubmitting">
                      <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                      Creating Account...
                    </span>
                    <span v-else>
                      <i class="bi bi-person-plus me-2"></i>Create Account
                    </span>
                  </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                  <p class="text-muted mb-0">
                    Already have an account? 
                    <router-link to="/login" class="text-decoration-none fw-bold">
                      Sign In
                    </router-link>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterView',
  data() {
    return {
      // Form data
      formData: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        dateOfBirth: '',
        address: '',
        city: '',
        state: '',
        postalCode: '',
        password: '',
        confirmPassword: '',
        newsletter: true,
        smsNotifications: false,
        termsAccepted: false
      },
      
      // Form validation errors
      errors: {},
      
      // Form state
      isSubmitting: false,
      showPassword: false,
      showConfirmPassword: false,
      
      // Malaysian states for dropdown
      malaysianStates: [
        'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Labuan', 'Malacca', 
        'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya',
        'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
      ]
    }
  },
  
  computed: {
    // Password strength calculation
    passwordStrength() {
      const password = this.formData.password
      if (!password) return { width: '0%', class: '', text: '' }
      
      let score = 0
      let checks = 0
      
      // Length check
      if (password.length >= 8) { score += 20; checks++ }
      if (password.length >= 12) { score += 10; checks++ }
      
      // Character variety checks
      if (/[a-z]/.test(password)) { score += 20; checks++ }
      if (/[A-Z]/.test(password)) { score += 20; checks++ }
      if (/\d/.test(password)) { score += 20; checks++ }
      if (/[^A-Za-z0-9]/.test(password)) { score += 10; checks++ }
      
      if (score < 40) return { width: '25%', class: 'text-danger', text: 'Weak' }
      if (score < 60) return { width: '50%', class: 'text-warning', text: 'Fair' }
      if (score < 80) return { width: '75%', class: 'text-info', text: 'Good' }
      return { width: '100%', class: 'text-success', text: 'Strong' }
    },
    
    // Password requirement checks
    passwordChecks() {
      const password = this.formData.password
      return {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password)
      }
    },
    
    // Form validation status
    isFormValid() {
      return Object.keys(this.errors).length === 0 && 
             this.formData.firstName && 
             this.formData.lastName && 
             this.formData.email && 
             this.formData.phone && 
             this.formData.address && 
             this.formData.city && 
             this.formData.state && 
             this.formData.postalCode && 
             this.formData.password && 
             this.formData.confirmPassword && 
             this.formData.termsAccepted
    }
  },
  
  methods: {
    // Toggle password visibility
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    },
    
    toggleConfirmPasswordVisibility() {
      this.showConfirmPassword = !this.showConfirmPassword
    },
    
    // Field validation - Vue 3 compatible
    validateField(fieldName) {
      // Clear previous error - Vue 3 way
      const newErrors = { ...this.errors }
      delete newErrors[fieldName]
      this.errors = newErrors
      
      const value = this.formData[fieldName]
      
      switch (fieldName) {
        case 'firstName':
        case 'lastName':
          if (!value || value.trim().length < 2) {
            this.errors = { ...this.errors, [fieldName]: 'Name must be at least 2 characters long' }
          }
          break
          
        case 'email':
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Email is required' }
          } else if (!emailRegex.test(value)) {
            this.errors = { ...this.errors, [fieldName]: 'Please enter a valid email address' }
          }
          break
          
        case 'phone':
          const phoneRegex = /^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Phone number is required' }
          } else if (!phoneRegex.test(value.replace(/[\s-]/g, ''))) {
            this.errors = { ...this.errors, [fieldName]: 'Please enter a valid Malaysian phone number' }
          }
          break
          
        case 'dateOfBirth':
          if (value) {
            const birthDate = new Date(value)
            const today = new Date()
            const age = today.getFullYear() - birthDate.getFullYear()
            if (age < 13) {
              this.errors = { ...this.errors, [fieldName]: 'You must be at least 13 years old' }
            }
          }
          break
          
        case 'address':
        case 'city':
          if (!value || value.trim().length < 3) {
            this.errors = { ...this.errors, [fieldName]: 'This field must be at least 3 characters long' }
          }
          break
          
        case 'state':
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Please select a state' }
          }
          break
          
        case 'postalCode':
          const postalRegex = /^\d{5}$/
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Postal code is required' }
          } else if (!postalRegex.test(value)) {
            this.errors = { ...this.errors, [fieldName]: 'Please enter a valid 5-digit postal code' }
          }
          break
          
        case 'password':
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Password is required' }
          } else if (value.length < 8) {
            this.errors = { ...this.errors, [fieldName]: 'Password must be at least 8 characters long' }
          } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
            this.errors = { ...this.errors, [fieldName]: 'Password must contain uppercase, lowercase, and number' }
          }
          
          // Re-validate confirm password if it exists
          if (this.formData.confirmPassword) {
            this.validateField('confirmPassword')
          }
          break
          
        case 'confirmPassword':
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'Please confirm your password' }
          } else if (value !== this.formData.password) {
            this.errors = { ...this.errors, [fieldName]: 'Passwords do not match' }
          }
          break
          
        case 'termsAccepted':
          if (!value) {
            this.errors = { ...this.errors, [fieldName]: 'You must accept the terms and conditions' }
          }
          break
      }
    },
    
    // Validate entire form
    validateForm() {
      // Reset errors
      this.errors = {}
      
      // Validate all required fields
      const requiredFields = [
        'firstName', 'lastName', 'email', 'phone', 'address', 
        'city', 'state', 'postalCode', 'password', 'confirmPassword', 'termsAccepted'
      ]
      
      requiredFields.forEach(field => {
        this.validateField(field)
      })
      
      return Object.keys(this.errors).length === 0
    },
    
    // Submit registration
    async submitRegistration() {
      // Validate form
      if (!this.validateForm()) {
        alert('Please fix the form errors before submitting.')
        return
      }
      
      this.isSubmitting = true
      
      try {
        // Call real backend API
        await this.callRegistrationAPI()
        
        // Show success message
        alert('Registration successful! Welcome to EcoGear! You can now login with your credentials.')
        
        // Redirect to login page
        this.$router.push('/login')
        
      } catch (error) {
        alert(`Registration failed: ${error.message}`)
        console.error('Registration error:', error)
      } finally {
        this.isSubmitting = false
      }
    },
    
    // Real registration API call
    async callRegistrationAPI() {
      try {
        console.log('Sending registration data:', this.formData)
        
        const response = await fetch('http://localhost:8000/api/auth/register.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            firstName: this.formData.firstName,
            lastName: this.formData.lastName,
            email: this.formData.email,
            phone: this.formData.phone,
            dateOfBirth: this.formData.dateOfBirth || null,
            address: this.formData.address,
            city: this.formData.city,
            state: this.formData.state,
            postalCode: this.formData.postalCode,
            gender: this.formData.gender || '',
            password: this.formData.password,
            confirmPassword: this.formData.confirmPassword,
            newsletter: this.formData.newsletter,
            smsNotifications: this.formData.smsNotifications,
            termsAccepted: this.formData.termsAccepted
          })
        })

        console.log('Response status:', response.status)
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }

        const result = await response.json()
        console.log('Registration result:', result)

        if (result.success) {
          return Promise.resolve()
        } else {
          throw new Error(result.message || 'Registration failed')
        }
        
      } catch (error) {
        console.error('Registration API error:', error)
        throw new Error(error.message || 'Registration failed. Please check your connection.')
      }
    }
  }
}
</script>

<style scoped>
/* Registration form styles */
.register-card {
  border-radius: 1rem;
}

.register-icon i {
  color: var(--eco-primary);
}

.form-section {
  border-bottom: 1px solid #e9ecef;
  padding-bottom: 1.5rem;
}

.form-section:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.section-title {
  color: var(--eco-primary);
  font-size: 1.1rem;
}

/* Password input styling */
.password-input-group {
  position: relative;
  display: flex;
}

.password-input-group input {
  padding-right: 3rem;
}

.password-toggle {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  z-index: 4;
  width: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Password strength indicator */
.password-strength {
  margin-top: 0.5rem;
}

.strength-bar {
  height: 4px;
  background-color: #e9ecef;
  border-radius: 2px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.strength-fill {
  height: 100%;
  transition: all 0.3s ease;
  border-radius: 2px;
}

.strength-fill.text-danger {
  background-color: #dc3545;
}

.strength-fill.text-warning {
  background-color: #ffc107;
}

.strength-fill.text-info {
  background-color: #0dcaf0;
}

.strength-fill.text-success {
  background-color: #198754;
}

/* Password requirements checklist */
.password-requirements {
  margin-top: 0.5rem;
}

.password-checklist {
  list-style: none;
  padding: 0;
  margin: 0.5rem 0 0 0;
}

.password-checklist li {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
  display: flex;
  align-items: center;
}

.password-checklist li i {
  margin-right: 0.5rem;
  font-size: 0.8rem;
}

.password-checklist li.valid {
  color: var(--eco-success);
}

/* Form validation styles */
.form-control.is-valid,
.form-select.is-valid {
  border-color: var(--eco-success);
}

.form-control.is-valid:focus,
.form-select.is-valid:focus {
  border-color: var(--eco-success);
  box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.form-control.is-invalid,
.form-select.is-invalid {
  border-color: #dc3545;
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Responsive design */
@media (max-width: 768px) {
  .card-body {
    padding: 2rem !important;
  }
  
  .password-input-group {
    position: relative;
  }
  
  .password-toggle {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 2.5rem;
  }

  .display-5 {
    font-size: 2rem;
  }
}

/* Loading animation */
.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}

/* Link styling */
a {
  color: var(--eco-primary);
}

a:hover {
  color: var(--eco-secondary);
}

/* Custom checkbox styling */
.form-check-input:checked {
  background-color: var(--eco-primary);
  border-color: var(--eco-primary);
}

.form-check-input:focus {
  border-color: var(--eco-primary);
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
}

/* Terms and conditions links */
.form-check-label a {
  color: var(--eco-primary);
  font-weight: 500;
}

.form-check-label a:hover {
  color: var(--eco-secondary);
  text-decoration: underline !important;
}

/* Submit button disabled state */
.btn-eco-primary:disabled {
  background-color: #6c757d;
  border-color: #6c757d;
  opacity: 0.65;
}

/* Animation for form sections */
.form-section {
  animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>