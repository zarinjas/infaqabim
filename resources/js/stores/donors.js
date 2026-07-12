import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useDonorStore = defineStore('donors', () => {
  const donors = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/api/admin/donors')
      donors.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  return { donors, loading, fetchAll }
})
