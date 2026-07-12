import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useCampaignStore = defineStore('campaigns', () => {
  const campaigns = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/api/admin/campaigns')
      campaigns.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  async function fetchOne(id) {
    const { data } = await axios.get(`/api/admin/campaigns/${id}`)
    return data
  }

  async function create(formData) {
    const { data } = await axios.post('/api/admin/campaigns', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    campaigns.value.unshift(data)
    return data
  }

  async function update(id, formData) {
    // PHP does not populate $_FILES for PUT/PATCH multipart requests, so we
    // POST with Laravel's _method spoofing to keep file uploads working.
    formData.append('_method', 'PUT')
    const { data } = await axios.post(`/api/admin/campaigns/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    const idx = campaigns.value.findIndex(c => c.id === id)
    if (idx !== -1) campaigns.value[idx] = data
    return data
  }

  async function remove(id) {
    await axios.delete(`/api/admin/campaigns/${id}`)
    campaigns.value = campaigns.value.filter(c => c.id !== id)
  }

  return { campaigns, loading, fetchAll, fetchOne, create, update, remove }
})
