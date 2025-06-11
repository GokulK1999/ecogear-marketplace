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
            <div class="card shadow-sm mb-4">
              <div class="card-body text-center p-4">
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
                <h4 class="fw-bold text-dark mb-1">
                  {{ userProfile.firstName }} {{ userProfile.lastName }}
                </h4>
                <p class="text-muted mb-2">{{ userProfile.email }}</p>
                <div class="user-badges">
                  <span class="badge bg-success me-1">
                    <i class="bi bi-shield-check me-1"></i>Verified
                  </span>
                  <span class="badge bg-primary">
                    <i class="bi bi-leaf me-1"></i>Eco Warrior
                  </span>
                </div>
                
                <!-- Account Stats -->
                <div class="account-stats mt-3">
                  <div class="row g-3 text-center">
                    <div class="col-4">
                      <div class="stat-item">
                        <div class="stat-number fw-bold text-success">{{ userStats.totalOrders }}</div>
                        <div class="stat-label small text-muted">Orders</div>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="stat-item">
                        <div class="stat-number fw-bold text-info">{{ userStats.totalSpent }}</div>
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
  </div>
</template>

<script>
export default {
  name: 'AccountView',
  data() {
    return {
      isEditMode: false,
      isUploadingImage: false,
      
      // User profile data - demonstrates data management
      userProfile: {
        firstName: 'John',
        lastName: 'Doe',
        email: 'demo@ecogear.com',
        phone: '+60 12-345 6789',
        dateOfBirth: '1990-01-15',
        gender: 'male',
        address: '123 Jalan Sustainable',
        city: 'Kuala Lumpur',
        state: 'Kuala Lumpur',
        postalCode: '50000',
        profilePicture: null,
        preferences: {
          emailNotifications: true,
          smsNotifications: false,
          newsletter: true,
          profileVisible: true,
          shareActivity: false,
          dataSaving: false
        }
      },
      
      // User statistics
      userStats: {
        totalOrders: 12,
        totalSpent: 'RM2,450',
        ecoPoints: 1250
      },
      
      // Malaysian states
      malaysianStates: [
        'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Labuan', 'Malacca', 
        'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya',
        'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
      ]
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
    }
  },
  
  methods: {
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
        // Simulate API call
        await this.simulateProfileUpdate()
        
        // Show success message
        alert('Profile updated successfully!')
        
        // Exit edit mode
        this.isEditMode = false
        
      } catch (error) {
        alert('Failed to update profile. Please try again.')
        console.error('Profile update error:', error)
      }
    },
    
    // Save profile changes to real database
    async simulateProfileUpdate() {
      try {
        // Get user data from localStorage
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          throw new Error('User not authenticated')
        }
        
        const user = JSON.parse(userData)
        
        // Prepare data for backend
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
        // Get user data from localStorage
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          this.$router.push('/login')
          return
        }
        
        const user = JSON.parse(userData)
        console.log('Loading profile for user:', user.user_id)
        
        // Fetch real profile data from backend
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
        } else {
          console.error('Failed to load profile:', result.message)
          alert('Failed to load profile data')
        }
      } catch (error) {
        console.error('Profile loading error:', error)
        alert('Error loading profile data')
      }
    },
    
    // Change profile picture - NEW FUNCTIONALITY
    async changeProfilePicture() {
      try {
        // Create file input element
        const input = document.createElement('input')
        input.type = 'file'
        input.accept = 'image/*'
        input.style.display = 'none'
        
        // Add event listener
        input.addEventListener('change', async (event) => {
          const file = event.target.files[0]
          if (file) {
            await this.uploadProfileImage(file)
          }
        })
        
        // Trigger file picker
        document.body.appendChild(input)
        input.click()
        document.body.removeChild(input)
        
      } catch (error) {
        console.error('Error opening file picker:', error)
        alert('Error opening file picker')
      }
    },
    
    // Upload profile image - NEW FUNCTIONALITY
    async uploadProfileImage(file) {
      try {
        // Validate file
        const maxSize = 5 * 1024 * 1024 // 5MB
        if (file.size > maxSize) {
          alert('File size too large. Maximum size is 5MB.')
          return
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        if (!allowedTypes.includes(file.type)) {
          alert('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.')
          return
        }
        
        this.isUploadingImage = true
        
        // Get user data
        const userData = localStorage.getItem('ecogear_user')
        if (!userData) {
          throw new Error('User not authenticated')
        }
        
        const user = JSON.parse(userData)
        
        // Create FormData
        const formData = new FormData()
        formData.append('profile_image', file)
        formData.append('user_id', user.user_id)
        
        // Upload image
        const response = await fetch('http://localhost:8000/api/user/upload-image.php', {
          method: 'POST',
          body: formData
        })
        
        const result = await response.json()
        
        if (result.success) {
          // Update profile picture in UI
          this.userProfile.profilePicture = result.data.image_path
          alert('Profile picture updated successfully!')
        } else {
          throw new Error(result.message)
        }
        
      } catch (error) {
        console.error('Image upload error:', error)
        alert(`Failed to upload image: ${error.message}`)
      } finally {
        this.isUploadingImage = false
      }
    },
    
    // Change password
    changePassword() {
      alert('Password change functionality would be implemented here')
    },
    
    // Manage 2FA
    manage2FA() {
      alert('Two-factor authentication management would be implemented here')
    },
    
    // Download user data
    downloadData() {
      alert('Data download functionality would be implemented here')
    },
    
    // Delete account
    deleteAccount() {
      if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        alert('Account deletion functionality would be implemented here')
      }
    },
    
    // Get gender display text
    getGenderDisplay(gender) {
      const genderMap = {
        'male': 'Male',
        'female': 'Female',
        'other': 'Other',
        '': 'Prefer not to say'
      }
      return genderMap[gender] || 'Prefer not to say'
    }
  },
  
  // Load user data when component mounts
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
  
  .avatar {
    width: 100px;
    height: 100px;
  }
}

/* Animation */
.card {
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