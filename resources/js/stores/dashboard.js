import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useDashboardStore = defineStore('dashboard', () => {
  const stats = ref(null)
  const loading = ref(false)

  async function fetchStats() {
    loading.value = true
    try {
      const { data } = await axios.get('/admin/dashboard')
      stats.value = data
    } finally {
      loading.value = false
    }
  }

  return { stats, loading, fetchStats }
})
