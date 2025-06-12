<template>
  <Teleport to="body">
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
      <div 
        class="toast align-items-center border-0"
        :class="{
          'text-bg-success': type === 'success',
          'text-bg-danger': type === 'error',
          'text-bg-warning': type === 'warning',
          'text-bg-info': type === 'info',
          'show': isVisible
        }"
        role="alert" 
        aria-live="assertive" 
        aria-atomic="true"
      >
        <div class="d-flex">
          <div class="toast-body d-flex align-items-center">
            <i 
              class="me-2"
              :class="{
                'bi bi-check-circle-fill': type === 'success',
                'bi bi-exclamation-triangle-fill': type === 'error',
                'bi bi-info-circle-fill': type === 'info',
                'bi bi-exclamation-circle-fill': type === 'warning'
              }"
            ></i>
            {{ message }}
          </div>
          <button 
            type="button" 
            class="btn-close btn-close-white me-2 m-auto" 
            @click="hide"
            aria-label="Close"
          ></button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  name: 'ToastNotification',
  props: {
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'success',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    duration: {
      type: Number,
      default: 3000
    },
    show: {
      type: Boolean,
      default: false
    }
  },
  emits: ['hide'],
  data() {
    return {
      isVisible: false,
      timer: null
    }
  },
  watch: {
    show: {
      handler(newVal) {
        if (newVal) {
          this.showToast()
        } else {
          this.hide()
        }
      },
      immediate: true
    }
  },
  methods: {
    showToast() {
      this.isVisible = true
      
      // Auto hide after duration
      if (this.duration > 0) {
        this.timer = setTimeout(() => {
          this.hide()
        }, this.duration)
      }
    },
    
    hide() {
      this.isVisible = false
      if (this.timer) {
        clearTimeout(this.timer)
        this.timer = null
      }
      this.$emit('hide')
    }
  },
  
  beforeUnmount() {
    if (this.timer) {
      clearTimeout(this.timer)
    }
  }
}
</script>

<style scoped>
.toast {
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.3s ease-in-out;
}

.toast.show {
  opacity: 1;
  transform: translateX(0);
}

.toast-container {
  pointer-events: none;
}

.toast {
  pointer-events: auto;
  min-width: 300px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

@media (max-width: 576px) {
  .toast {
    min-width: 280px;
  }
}
</style>