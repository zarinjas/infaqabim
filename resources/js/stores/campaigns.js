import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useCampaignStore = defineStore('campaigns', () => {
  const campaigns = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/admin/campaigns')
      campaigns.value = data
    } finally {
      loading.value = false
    }
  }

  async function fetchOne(id) {
    const { data } = await axios.get(`/admin/campaigns/${id}`)
    return data
  }

  async function create(payload) {
    const { data } = await axios.post('/admin/campaigns', payload)
    campaigns.value.unshift(data)
    return data
  }

  async function update(id, payload) {
    const { data } = await axios.put(`/admin/campaigns/${id}`, payload)
    const idx = campaigns.value.findIndex(c => c.id === id)
    if (idx !== -1) campaigns.value[idx] = data
    return data
  }

  async function remove(id) {
    await axios.delete(`/admin/campaigns/${id}`)
    campaigns.value = campaigns.value.filter(c => c.id !== id)
  }

  return { campaigns, loading, fetchAll, fetchOne, create, update, remove }
})
