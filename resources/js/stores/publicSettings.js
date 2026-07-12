import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const usePublicSettingStore = defineStore('publicSettings', () => {
  const settings = ref({})
  const loaded = ref(false)

  async function fetch() {
    if (loaded.value) return
    try {
      const { data } = await axios.get('/api/settings')
      settings.value = data
    } catch {
      settings.value = {}
    } finally {
      loaded.value = true
    }
  }

  return { settings, loaded, fetch }
})
