import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useSettingStore = defineStore('settings', () => {
  const settings = ref({})
  const loading = ref(false)
  const saving = ref(false)

  async function fetchAll() {
    loading.value = true
    try {
      const { data } = await axios.get('/api/admin/settings')
      settings.value = data
    } finally {
      loading.value = false
    }
  }

  async function update(payload) {
    saving.value = true
    try {
      const isFormData = payload instanceof FormData;
      const config = isFormData ? { headers: { 'Content-Type': 'multipart/form-data' } } : {};
      
      const { data } = await axios.post('/api/admin/settings', payload, config); // Use POST for FormData and Method Spoofing
      settings.value = data
    } finally {
      saving.value = false
    }
  }

  return { settings, loading, saving, fetchAll, update }
})
