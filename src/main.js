import { createApp } from 'vue'
import { createPinia } from 'pinia'

// Import Bootstrap CSS and JS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

import App from './App.vue'
import router from './router'

// Import custom CSS
import './assets/main.css'

const app = createApp(App)


// Custom directive for auto-focus
app.directive('focus', {
  mounted(el) {
    el.focus()
  }
})

// Custom directive for text highlighting
app.directive('highlight', {
  mounted(el, binding) {
    el.style.backgroundColor = binding.value || '#fff3cd'
    el.style.padding = '2px 4px'
    el.style.borderRadius = '3px'
  }
})

// Custom directive for tooltips
app.directive('tooltip', {
  mounted(el, binding) {
    el.title = binding.value
    el.style.cursor = 'help'
    el.style.borderBottom = '1px dotted #333'
  }
})
// =============== END CUSTOM DIRECTIVES ===============

app.use(createPinia())
app.use(router)

app.mount('#app')