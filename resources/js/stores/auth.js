import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const admin = ref(null)
  const loading = ref(false)

  async function login(email, password) {
    const { data } = await axios.post('/admin/login', { email, password })
    admin.value = data.admin
  }

  async function check() {
    loading.value = true
    try {
      const { data } = await axios.get('/admin/me')
      admin.value = data.admin
    } catch {
      admin.value = null
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await axios.post('/admin/logout')
    } catch {
      //
    }
    admin.value = null
  }

  return { admin, loading, login, check, logout }
})
