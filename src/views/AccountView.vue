<template>
  <div class="account-page">
    <!-- Page Header -->
    <section class="page-header bg-light py-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-5 fw-bold text-dark mb-2">My Account</h1>
            <p class="lead text-muted mb-0">Manage your profile and account settings</p>
          </div>
          <div class="col-md-4 text-md-end">
            <div class="account-actions">
              <button class="btn btn-outline-secondary me-2" @click="toggleEditMode">
                <i :class="isEditMode ? 'bi bi-x-circle' : 'bi bi-pencil'" class="me-1"></i>
                {{ isEditMode ? 'Cancel' : 'Edit Profile' }}
              </button>
              <button class="btn btn-eco-primary" v-if="isEditMode" @click="saveProfile">
                <i class="bi bi-check-circle me-1"></i>Save Changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-4">
      <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4 mb-4">
          <div class="profile-sidebar">
            
            <!-- Profile Card -->
            <div class="card shadow-sm mb-4 stable-card" :class="{ 'loading': isLoading }">
              <div class="card-body text-center p-4">
                <!-- Loading Skeleton -->
                <div v-if="isLoading" class="loading-skeleton">
                  <!-- Profile Picture Skeleton -->
                  <div class="profile-picture mb-3">
                    <div class="avatar-container position-relative">
                      <div class="avatar rounded-circle skeleton-avatar"></div>
                    </div>
                  </div>
                  
                  <!-- User Info Skeleton -->
                  <div class="user-info-section">
                    <div class="skeleton-text skeleton-name mb-1"></div>
                    <div class="skeleton-text skeleton-email mb-2"></div>
                    
                    <!-- Badges Skeleton -->
                    <div class="user-badges mb-3">
                      <span class="skeleton-badge me-1"></span>
                      <span class="skeleton-badge"></span>
                    </div>
                  </div>
                  
                  <!-- Stats Skeleton -->
                  <div class="account-stats mt-3 pt-3">
                    <div class="row g-3 text-center">
                      <div class="col-4">
                        <div class="skeleton-text skeleton-stat mb-2"></div>
                        <div class="skeleton-text skeleton-label"></div>
                      </div>
                      <div class="col-4">
                        <div class="skeleton-text skeleton-stat mb-2"></div>
                        <div class="skeleton-text skeleton-label"></div>
                      </div>
                      <div class="col-4">
                        <div class="skeleton-text skeleton-stat mb-2"></div>
                        <div class="skeleton-text skeleton-label"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Actual Content -->
                <div v-else class="actual-content">
                  <!-- Profile Picture -->
                  <div class="profile-picture mb-3">
                    <div class="avatar-container position-relative">
                      <img 
                        :src="profilePictureUrl" 
                        :alt="userProfile.firstName + ' ' + userProfile.lastName"
                        class="avatar rounded-circle"
                        @error="$event.target.src = '/src/assets/images/default-avatar.jpg'"
                      >
                      <button 
                        class="btn btn-sm btn-eco-primary avatar-edit-btn position-absolute"
                        @click="changeProfilePicture"
                        v-if="isEditMode"
                        :disabled="isUploadingImage"
                      >
                        <span v-if="isUploadingImage">
                          <span class="spinner-border spinner-border-sm" role="status"></span>
                        </span>
                        <i v-else class="bi bi-camera"></i>
                      </button>
                    </div>
                  </div>
                  
                  <!-- User Info -->
                  <div class="user-info-section">
                    <h4 class="fw-bold text-dark mb-1 user-name">
                      {{ displayName }}
                    </h4>
                    <p class="text-muted mb-2 user-email">{{ userProfile.email || 'Loading...' }}</p>
                    <div class="user-badges mb-3">
                      <span class="badge bg-success me-1">
                        <i class="bi bi-shield-check me-1"></i>Verified
                      </span>
                      <span class="badge bg-primary">
                        <i class="bi bi-leaf me-1"></i>Eco Warrior
                      </span>
                    </div>
                  </div>
                  
                  <!-- Account Stats -->
                  <div class="account-stats mt-3 pt-3">
                    <div class="row g-3 text-center">
                      <div class="col-4">
                        <div class="stat-item">
                          <div class="stat-number fw-bold text-success">{{ userStats.totalOrders }}</div>
                          <div class="stat-label small text-muted">Orders</div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="stat-item">
                          <div class="stat-number fw-bold text-info">RM{{ userStats.totalSpent }}</div>
                          <div class="stat-label small text-muted">Spent</div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="stat-item">
                          <div class="stat-number fw-bold text-warning">{{ userStats.ecoPoints }}</div>
                          <div class="stat-label small text-muted">Eco Points</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
              <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">
                  <i class="bi bi-lightning text-warning me-2"></i>Quick Actions
                </h6>
              </div>
              <div class="card-body p-0">
                <div class="list-group list-group-flush">
                  <router-link to="/purchases" class="list-group-item list-group-item-action">
                    <i class="bi bi-bag text-primary me-2"></i>View My Orders
                  </router-link>
                  <router-link to="/cart" class="list-group-item list-group-item-action">
                    <i class="bi bi-cart text-success me-2"></i>Shopping Cart
                  </router-link>
                  <a href="#" class="list-group-item list-group-item-action" @click="downloadData">
                    <i class="bi bi-download text-info me-2"></i>Download My Data
                  </a>
                  <a href="#" class="list-group-item list-group-item-action text-danger" @click="deleteAccount">
                    <i class="bi bi-trash text-danger me-2"></i>Delete Account
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
          
          <!-- Personal Information -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-person text-success me-2"></i>Personal Information
              </h5>
            </div>
            <div class="card-body">
              <form @submit.prevent="saveProfile">
                <div class="row g-3">
                  <!-- First Name -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">First Name</label>
                    <input 
                      type="text" 
                      class="form-control"
                      v-model="userProfile.firstName"
                      :readonly="!isEditMode"
                      :class="{ 'form-control-plaintext': !isEditMode }"
                    >
                  </div>
                  
                  <!-- Last Name -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Last Name</label>
                    <input 
                      type="text" 
                      class="form-control"
                      v-model="userProfile.lastName"
                      :readonly="!isEditMode"
                      :class="{ 'form-control-plaintext': !isEditMode }"
                    >
                  </div>
                  
                  <!-- Email -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Email Address</label>
                    <input 
                      type="email" 
                      class="form-control"
                      v-model="userProfile.email"
                      :readonly="!isEditMode"
                      :class="{ 'form-control-plaintext': !isEditMode }"
                    >
                  </div>
                  
                  <!-- Phone -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Phone Number</label>
                    <input 
                      type="tel" 
                      class="form-control"
                      v-model="userProfile.phone"
                      :readonly="!isEditMode"
                      :class="{ 'form-control-plaintext': !isEditMode }"
                    >
                  </div>
                  
                  <!-- Date of Birth -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Date of Birth</label>
                    <input 
                      type="date" 
                      class="form-control"
                      v-model="userProfile.dateOfBirth"
                      :readonly="!isEditMode"
                      :class="{ 'form-control-plaintext': !isEditMode }"
                    >
                  </div>
                  
                  <!-- Gender -->
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Gender</label>
                    <select 
                      class="form-select"
                      v-model="userProfile.gender"
                      :disabled="!isEditMode"
                      v-if="isEditMode"
                    >
                      <option value="">Prefer not to say</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                    </select>
                    <input 
                      type="text" 
                      class="form-control-plaintext"
                      :value="getGenderDisplay(userProfile.gender)"
                      readonly
                      v-else
                    >
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Address Information -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-geo-alt text-success me-2"></i>Address Information
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <!-- Street Address -->
                <div class="col-12">
                  <label class="form-label fw-medium">Street Address</label>
                  <input 
                    type="text" 
                    class="form-control"
                    v-model="userProfile.address"
                    :readonly="!isEditMode"
                    :class="{ 'form-control-plaintext': !isEditMode }"
                  >
                </div>
                
                <!-- City -->
                <div class="col-md-4">
                  <label class="form-label fw-medium">City</label>
                  <input 
                    type="text" 
                    class="form-control"
                    v-model="userProfile.city"
                    :readonly="!isEditMode"
                    :class="{ 'form-control-plaintext': !isEditMode }"
                  >
                </div>
                
                <!-- State -->
                <div class="col-md-4">
                  <label class="form-label fw-medium">State</label>
                  <select 
                    class="form-select"
                    v-model="userProfile.state"
                    :disabled="!isEditMode"
                    v-if="isEditMode"
                  >
                    <option value="">Select State</option>
                    <option v-for="state in malaysianStates" :key="state" :value="state">
                      {{ state }}
                    </option>
                  </select>
                  <input 
                    type="text" 
                    class="form-control-plaintext"
                    :value="userProfile.state"
                    readonly
                    v-else
                  >
                </div>
                
                <!-- Postal Code -->
                <div class="col-md-4">
                  <label class="form-label fw-medium">Postal Code</label>
                  <input 
                    type="text" 
                    class="form-control"
                    v-model="userProfile.postalCode"
                    :readonly="!isEditMode"
                    :class="{ 'form-control-plaintext': !isEditMode }"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Account Preferences -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-gear text-success me-2"></i>Account Preferences
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-4">
                <!-- Notifications -->
                <div class="col-md-6">
                  <h6 class="fw-bold mb-3">Notifications</h6>
                  <div class="form-check mb-2">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="emailNotifications"
                      v-model="userProfile.preferences.emailNotifications"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="emailNotifications">
                      Email notifications
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="smsNotifications"
                      v-model="userProfile.preferences.smsNotifications"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="smsNotifications">
                      SMS notifications
                    </label>
                  </div>
                  <div class="form-check">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="newsletter"
                      v-model="userProfile.preferences.newsletter"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="newsletter">
                      Newsletter subscription
                    </label>
                  </div>
                </div>
                
                <!-- Privacy -->
                <div class="col-md-6">
                  <h6 class="fw-bold mb-3">Privacy</h6>
                  <div class="form-check mb-2">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="profileVisible"
                      v-model="userProfile.preferences.profileVisible"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="profileVisible">
                      Make profile visible to others
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="shareActivity"
                      v-model="userProfile.preferences.shareActivity"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="shareActivity">
                      Share activity with friends
                    </label>
                  </div>
                  <div class="form-check">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      id="dataSaving"
                      v-model="userProfile.preferences.dataSaving"
                      :disabled="!isEditMode"
                    >
                    <label class="form-check-label" for="dataSaving">
                      Enable data saving mode
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Security Settings -->
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-shield-lock text-success me-2"></i>Security Settings
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="security-item d-flex justify-content-between align-items-center p-3 border rounded">
                    <div>
                      <h6 class="mb-1">Password</h6>
                      <small class="text-muted">Last changed 3 months ago</small>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" @click="changePassword">
                      <i class="bi bi-key me-1"></i>Change
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="security-item d-flex justify-content-between align-items-center p-3 border rounded">
                    <div>
                      <h6 class="mb-1">Two-Factor Authentication</h6>
                      <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>Enabled
                      </small>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" @click="manage2FA">
                      <i class="bi bi-gear me-1"></i>Manage
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
import { useOrdersStore } from '@/stores/ordersStore'
import ToastNotification from '@/components/ToastNotification.vue'

export default {
  name: 'AccountView',
  components: {
    ToastNotification
  },
  setup() {
    const ordersStore = useOrdersStore()
    return { ordersStore }
  },
  data() {
    return {
      isEditMode: false,
      isUploadingImage: false,
      isLoading: true,
      
      // User profile data
      userProfile: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        dateOfBirth: '',
        gender: '',
        address: '',
        city: '',
        state: '',
        postalCode: '',
        profilePicture: null,
        preferences: {
          emailNotifications: false,
          smsNotifications: false,
          newsletter: false,
          profileVisible: false,
          shareActivity: false,
          dataSaving: false
        }
      },
      
      // User statistics - will be calculated from localStorage orders
      userStats: {
        totalOrders: 0,
        totalSpent: '0.00',
        ecoPoints: 0
      },
      
      // Malaysian states
      malaysianStates: [
        'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Labuan', 'Malacca', 
        'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya',
        'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
      ],

      // Toast notification
      toast: {
        show: false,
        message: '',
        type: 'success'
      }
    }
  },
  
  computed: {
    // Get profile picture URL
    profilePictureUrl() {
      if (this.userProfile.profilePicture) {
        // If it's an uploaded image, use the backend URL
        if (this.userProfile.profilePicture.startsWith('/uploads/')) {
          return `http://localhost:8000${this.userProfile.profilePicture}`
        }
        // Otherwise use the existing path
        return this.userProfile.profilePicture
      }
      // Default avatar
      return '/src/assets/images/default-avatar.jpg'
    },
    
    // Display name with fallback
    displayName() {
      if (this.isLoading) return 'Loading...'
      return `${this.userProfile.firstName} ${this.userProfile.lastName}`.trim() || 'User'
    }
  },
  
  methods: {
    // Calculate user statistics from localStorage orders
    calculateUserStats() {
      // Initialize orders store
      this.ordersStore.initializeOrders()
      
      // Get user orders
      const userOrders = this.ordersStore.userOrders
      
      // Calculate statistics
      const totalOrders = userOrders.length
      const totalSpent = userOrders.reduce((sum, order) => sum + parseFloat(order.total || 0), 0)
      
      // Calculate eco points (1 point per RM spent, bonus for delivered orders)
      let ecoPoints = Math.floor(totalSpent)
      userOrders.forEach(order => {
        if (order.status === 'delivered') {
          ecoPoints += 10 // Bonus points for completed orders
        }
      })
      
      // Update stats
      this.userStats = {
        totalOrders,
        totalSpent: totalSpent.toFixed(2),
        ecoPoints
      }
      
      console.log('Calculated user stats:', this.userStats)
      console.log('From orders:', userOrders)
    },
    
    // Toggle edit mode
    toggleEditMode() {
      if (this.isEditMode) {
        // Cancel editing - reset changes
        this.loadUserProfile()
      }
      this.isEditMode = !this.isEditMode
    },
    
    // Save profile changes
    async saveProfile() {
      try {
        await this.simulateProfileUpdate()
        this.showToast('Profile updated successfully!', 'success')
        this.isEditMode = false
      } catch (error) {
        this.showToast('Failed to update profile. Please try again.', 'error')
        console.error('Profile update error:', error)
      }
    },
    
    // Save profile changes to real database
    async simulateProfileUpdate() {
      try {
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          throw new Error('User not authenticated')
        }
        
        const user = JSON.parse(userData)
        
        const updateData = {
          user_id: user.user_id,
          firstName: this.userProfile.firstName,
          lastName: this.userProfile.lastName,
          email: this.userProfile.email,
          phone: this.userProfile.phone,
          dateOfBirth: this.userProfile.dateOfBirth,
          gender: this.userProfile.gender,
          address: this.userProfile.address,
          city: this.userProfile.city,
          state: this.userProfile.state,
          postalCode: this.userProfile.postalCode,
          preferences: this.userProfile.preferences
        }
        
        const response = await fetch('http://localhost:8000/api/user/update.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(updateData)
        })
        
        const result = await response.json()
        
        if (result.success) {
          console.log('Profile updated successfully')
          return Promise.resolve()
        } else {
          throw new Error(result.message)
        }
      } catch (error) {
        console.error('Profile update error:', error)
        throw new Error('Failed to update profile')
      }
    },
    
    // Load user profile from real database
    async loadUserProfile() {
      try {
        // Only set loading if not already loaded
        if (!this.userProfile.firstName) {
          this.isLoading = true
        }
        
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          this.$router.push('/login')
          return
        }
        
        const user = JSON.parse(userData)
        console.log('Loading profile for user:', user.user_id)
        
        const response = await fetch(`http://localhost:8000/api/user/profile.php?user_id=${user.user_id}`)
        const result = await response.json()
        
        if (result.success) {
          const profileData = result.data.user
          
          // Update userProfile with real data
          this.userProfile = {
            firstName: profileData.first_name || '',
            lastName: profileData.last_name || '',
            email: profileData.email || '',
            phone: profileData.phone || '',
            dateOfBirth: profileData.date_of_birth || '',
            gender: profileData.gender || '',
            address: profileData.address || '',
            city: profileData.city || '',
            state: profileData.state || '',
            postalCode: profileData.postal_code || '',
            profilePicture: profileData.profile_picture || null,
            preferences: {
              emailNotifications: profileData.email_notifications || false,
              smsNotifications: profileData.sms_notifications || false,
              newsletter: profileData.newsletter || false,
              profileVisible: profileData.profile_visible || false,
              shareActivity: profileData.share_activity || false,
              dataSaving: profileData.data_saving || false
            }
          }
          
          console.log('Profile loaded successfully:', this.userProfile)
          
          // Calculate user statistics after profile is loaded
          this.calculateUserStats()
        } else {
          console.error('Failed to load profile:', result.message)
          this.showToast('Failed to load profile data', 'error')
        }
      } catch (error) {
        console.error('Profile loading error:', error)
        this.showToast('Error loading profile data', 'error')
      } finally {
        this.isLoading = false
      }
    },
    
    // Change profile picture
    async changeProfilePicture() {
      try {
        const input = document.createElement('input')
        input.type = 'file'
        input.accept = 'image/*'
        input.style.display = 'none'
        
        input.addEventListener('change', async (event) => {
          const file = event.target.files[0]
          if (file) {
            await this.uploadProfileImage(file)
          }
        })
        
        document.body.appendChild(input)
        input.click()
        document.body.removeChild(input)
        
      } catch (error) {
        console.error('Error opening file picker:', error)
        this.showToast('Error opening file picker', 'error')
      }
    },
    
    // Upload profile image
    async uploadProfileImage(file) {
      try {
        const maxSize = 5 * 1024 * 1024 // 5MB
        if (file.size > maxSize) {
          this.showToast('File size too large. Maximum size is 5MB.', 'warning')
          return
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        if (!allowedTypes.includes(file.type)) {
          this.showToast('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.', 'warning')
          return
        }
        
        this.isUploadingImage = true
        
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          throw new Error('User not authenticated')
        }
        
        const user = JSON.parse(userData)
        
        const formData = new FormData()
        formData.append('profile_image', file)
        formData.append('user_id', user.user_id)
        
        const response = await fetch('http://localhost:8000/api/user/upload-image.php', {
          method: 'POST',
          body: formData
        })
        
        const result = await response.json()
        
        if (result.success) {
          this.userProfile.profilePicture = result.data.image_path
          this.showToast('Profile picture updated successfully!', 'success')
        } else {
          throw new Error(result.message)
        }
        
      } catch (error) {
        console.error('Image upload error:', error)
        this.showToast(`Failed to upload image: ${error.message}`, 'error')
      } finally {
        this.isUploadingImage = false
      }
    },
    
    changePassword() {
      this.showToast('Password change functionality would be implemented here', 'info')
    },
    
    manage2FA() {
      this.showToast('Two-factor authentication management would be implemented here', 'info')
    },
    
    downloadData() {
      this.showToast('Data download functionality would be implemented here', 'info')
    },
    
    deleteAccount() {
      if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        this.showToast('Account deletion functionality would be implemented here', 'warning')
      }
    },
    
    getGenderDisplay(gender) {
      const genderMap = {
        'male': 'Male',
        'female': 'Female',
        'other': 'Other',
        '': 'Prefer not to say'
      }
      return genderMap[gender] || 'Prefer not to say'
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
  
  mounted() {
    this.loadUserProfile()
  }
}
</script>

<style scoped>
/* Profile picture styling */
.avatar-container {
  display: inline-block;
}

.avatar {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border: 4px solid var(--eco-light);
}

.avatar-edit-btn {
  bottom: 0;
  right: 0;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* User badges */
.user-badges .badge {
  font-size: 0.75rem;
}

/* Account stats */
.account-stats {
  border-top: 1px solid #e9ecef;
  padding-top: 1rem;
}

.stat-number {
  font-size: 1.2rem;
}

/* Quick actions */
.list-group-item {
  transition: all 0.2s ease;
}

.list-group-item:hover {
  background-color: var(--eco-light);
  transform: translateX(5px);
}

/* Form styling */
.form-control-plaintext {
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0;
}

.form-control:focus {
  border-color: var(--eco-primary);
  box-shadow: 0 0 0 0.2rem rgba(45, 80, 22, 0.25);
}

/* Security items */
.security-item {
  transition: all 0.2s ease;
}

.security-item:hover {
  border-color: var(--eco-primary) !important;
  background-color: var(--eco-light);
}

/* Card styling */
.card {
  border: none;
  border-radius: 0.75rem;
}

.card-header {
  border-bottom: 1px solid #e9ecef;
  border-radius: 0.75rem 0.75rem 0 0 !important;
}

/* Button styling */
.btn-eco-primary {
  transition: all 0.3s ease;
}

.btn-eco-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 80, 22, 0.3);
}

/* FIXED LOADING SKELETON STYLES - NO MORE FLUCTUATION */
.stable-card {
  min-height: 420px; /* Fixed height to prevent fluctuation */
}

.stable-card .card-body {
  position: relative;
  height: 100%;
}

.loading-skeleton,
.actual-content {
  position: absolute;
  top: 1.5rem;
  left: 1.5rem;
  right: 1.5rem;
  bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.loading-skeleton {
  animation: none; /* Disable card animation during loading */
}

/* Profile picture section - consistent sizing */
.profile-picture {
  height: 144px; /* Fixed height: 120px avatar + 24px margin */
  display: flex;
  align-items: center;
  justify-content: center;
}

/* User info section - consistent sizing */
.user-info-section {
  height: 100px; /* Fixed height for name, email, badges */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.user-name {
  height: 32px; /* Fixed height for h4 */
  display: flex;
  align-items: center;
  line-height: 1.2;
  margin-bottom: 0.25rem !important;
}

.user-email {
  height: 24px; /* Fixed height for email */
  display: flex;
  align-items: center;
  line-height: 1.2;
  margin-bottom: 0.5rem !important;
}

.user-badges {
  height: 28px; /* Fixed height for badges */
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.75rem !important;
}

/* Account stats section - consistent sizing */
.account-stats {
  border-top: 1px solid #e9ecef;
  padding-top: 1rem;
  height: 100px; /* Fixed height */
  margin-top: auto; /* Push to bottom */
}

.stat-item {
  height: 60px; /* Fixed height for stat items */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.stat-number {
  font-size: 1.2rem;
  height: 28px; /* Fixed height */
  display: flex;
  align-items: center;
  line-height: 1;
}

.stat-label {
  height: 20px; /* Fixed height */
  display: flex;
  align-items: center;
  line-height: 1;
}

/* Skeleton elements with exact same dimensions */
.skeleton-avatar {
  width: 120px;
  height: 120px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border: 4px solid var(--eco-light);
}

.skeleton-text {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 4px;
}

.skeleton-name {
  height: 32px; /* Match user-name height */
  width: 180px;
  margin-bottom: 0.25rem !important;
}

.skeleton-email {
  height: 24px; /* Match user-email height */
  width: 220px;
  margin-bottom: 0.5rem !important;
}

.skeleton-badge {
  display: inline-block;
  width: 70px;
  height: 24px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 12px;
  margin-right: 0.25rem;
}

.skeleton-stat {
  height: 28px; /* Match stat-number height */
  width: 40px;
  margin: 0 auto;
  margin-bottom: 8px;
}

.skeleton-label {
  height: 20px; /* Match stat-label height */
  width: 50px;
  margin: 0 auto;
}

@keyframes skeleton-loading {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Disable card animation during loading */
.card.loading {
  animation: none !important;
}

/* Responsive design */
@media (max-width: 768px) {
  .account-actions {
    text-align: center;
    margin-top: 1rem;
  }
  
  .account-actions .btn {
    display: block;
    width: 100%;
    margin-bottom: 0.5rem;
  }
  
  .profile-sidebar {
    margin-bottom: 2rem;
  }
  
  .avatar, .skeleton-avatar {
    width: 100px;
    height: 100px;
  }
  
  .stable-card {
    min-height: 380px; /* Slightly smaller for mobile */
  }
  
  .profile-picture {
    height: 124px; /* Adjusted for smaller avatar */
  }
}

/* Animation for main content cards only */
.col-lg-8 .card {
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