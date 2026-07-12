import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useBannerStore = defineStore('banners', () => {
  const banners = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/api/admin/banners')
      banners.value = data
    } finally {
      loading.value = false
    }
  }

  async function create(formData) {
    const { data } = await axios.post('/api/admin/banners', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    banners.value.unshift(data)
    return data
  }

  async function update(id, formData) {
    // PHP does not populate $_FILES for PUT/PATCH multipart requests, so we
    // POST with Laravel's _method spoofing to keep file uploads working.
    formData.append('_method', 'PUT')
    const { data } = await axios.post(`/api/admin/banners/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    const idx = banners.value.findIndex(b => b.id === id)
    if (idx !== -1) banners.value[idx] = data
    return data
  }

  async function remove(id) {
    await axios.delete(`/api/admin/banners/${id}`)
    banners.value = banners.value.filter(b => b.id !== id)
  }

  return { banners, loading, fetchAll, create, update, remove }
})
