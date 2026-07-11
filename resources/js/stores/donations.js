import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useDonationStore = defineStore('donations', () => {
  const donations = ref([])
  const loading = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/admin/donations')
      donations.value = data
    } finally {
      loading.value = false
    }
  }

  async function approve(id) {
    const { data } = await axios.patch(`/admin/donations/${id}/approve`)
    const idx = donations.value.findIndex(d => d.id === id)
    if (idx !== -1) donations.value[idx] = data
    return data
  }

  async function reject(id) {
    const { data } = await axios.patch(`/admin/donations/${id}/reject`)
    const idx = donations.value.findIndex(d => d.id === id)
    if (idx !== -1) donations.value[idx] = data
    return data
  }

  async function remove(id) {
    await axios.delete(`/admin/donations/${id}`)
    donations.value = donations.value.filter(d => d.id !== id)
  }

  return { donations, loading, fetchAll, approve, reject, remove }
})
