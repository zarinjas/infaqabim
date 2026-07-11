import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useBannerStore = defineStore('banners', () => {
  const banners = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/admin/banners')
      banners.value = data
    } finally {
      loading.value = false
    }
  }

  async function create(payload) {
    const { data } = await axios.post('/admin/banners', payload)
    banners.value.unshift(data)
    return data
  }

  async function update(id, payload) {
    const { data } = await axios.put(`/admin/banners/${id}`, payload)
    const idx = banners.value.findIndex(b => b.id === id)
    if (idx !== -1) banners.value[idx] = data
    return data
  }

  async function remove(id) {
    await axios.delete(`/admin/banners/${id}`)
    banners.value = banners.value.filter(b => b.id !== id)
  }

  return { banners, loading, fetchAll, create, update, remove }
})
