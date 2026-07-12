import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import App from './App.vue'
import router from './router'

axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
window.axios = axios

// Initialise session & CSRF cookie — Laravel web routes require XSRF-TOKEN
// for mutating requests. A lightweight HEAD request is enough to get it.
axios.head('/').catch(() => {})

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')
