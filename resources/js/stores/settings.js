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
      const { data } = await axios.get('/admin/settings')
      settings.value = data
    } finally {
      loading.value = false
    }
  }

  async function update(payload) {
    saving.value = true
    try {
      const { data } = await axios.put('/admin/settings', payload)
      settings.value = data
    } finally {
      saving.value = false
    }
  }

  return { settings, loading, saving, fetchAll, update }
})
