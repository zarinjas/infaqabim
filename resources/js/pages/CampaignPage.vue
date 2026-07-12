<template>
  <div>
    <HeroBanner
      title="Semua Kempen"
      subtitle="Pilih kempen yang ingin anda sokong dan bantu mereka yang memerlukan."
    >
      <template #actions>
        <router-link
          to="/donate"
          class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-emerald-800 shadow-sm transition-colors hover:bg-emerald-50"
        >
          Sumbang Sekarang
        </router-link>
      </template>
    </HeroBanner>

    <section class="bg-gray-50 py-12 sm:py-16">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100 sm:flex-row sm:items-center sm:justify-between sm:p-5">
          <p class="text-sm text-gray-500">
            {{ loading ? 'Memuatkan...' : `Menunjukkan ${filtered.length} kempen` }}
          </p>
          <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative">
              <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
              </svg>
              <input
                v-model="search"
                type="text"
                placeholder="Cari kempen..."
                class="w-full rounded-xl border border-gray-200 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 sm:w-64"
              />
            </div>
            <select
              v-model="sortBy"
              class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-600 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            >
              <option value="newest">Terbaru</option>
              <option value="most-funded">Paling Banyak Terkumpul</option>
              <option value="least-funded">Paling Kurang Terkumpul</option>
            </select>
          </div>
        </div>
        <div v-if="loading" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="i in 6" :key="i" class="h-80 animate-pulse rounded-2xl bg-white ring-1 ring-gray-100" />
        </div>
        <div v-else-if="filtered.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <CampaignCard v-for="c in filtered" :key="c.id" :campaign="c" />
        </div>
        <EmptyState v-else title="Tiada kempen ditemui." description="Cuba kata kunci carian yang lain." />
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import HeroBanner from '../components/HeroBanner.vue'
import CampaignCard from '../components/CampaignCard.vue'
import EmptyState from '../components/EmptyState.vue'

const search = ref('')
const sortBy = ref('newest')
const loading = ref(true)
const campaigns = ref([])

function mapCampaign(c) {
  return { ...c, collected: Number(c.collected_amount), target: Number(c.target_amount) }
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/home')
    campaigns.value = (data.campaigns || []).map(mapCampaign)
  } catch {
    campaigns.value = []
  } finally {
    loading.value = false
  }
})

const filtered = computed(() => {
  let result = campaigns.value
  if (search.value) {
    const q = search.value.toLowerCase()
    result = result.filter(c => c.title.toLowerCase().includes(q) || c.description.toLowerCase().includes(q))
  }
  if (sortBy.value === 'most-funded') {
    result = [...result].sort((a, b) => b.collected - a.collected)
  } else if (sortBy.value === 'least-funded') {
    result = [...result].sort((a, b) => a.collected - b.collected)
  }
  return result
})
</script>
