<template>
  <div>
    <HeroBanner
      title="All Campaigns"
      subtitle="Pilih kempen yang ingin anda sokong dan bantu mereka yang memerlukan."
    >
      <template #actions>
        <router-link
          to="/donate"
          class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-emerald-800 shadow-sm transition-colors hover:bg-emerald-50"
        >
          Donate Now
        </router-link>
      </template>
    </HeroBanner>

    <section class="bg-gray-50 py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <p class="text-sm text-gray-500">Showing {{ filtered.length }} campaign{{ filtered.length !== 1 ? 's' : '' }}</p>
          <div class="flex gap-3">
            <input
              v-model="search"
              type="text"
              placeholder="Search campaigns..."
              class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 sm:w-64"
            />
            <select
              v-model="sortBy"
              class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-600 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            >
              <option value="newest">Newest</option>
              <option value="most-funded">Most Funded</option>
              <option value="least-funded">Least Funded</option>
            </select>
          </div>
        </div>
        <div v-if="filtered.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <CampaignCard v-for="c in filtered" :key="c.title" :campaign="c" />
        </div>
        <div v-else class="py-20 text-center">
          <p class="text-gray-400">No campaigns found matching your search.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import HeroBanner from '../components/HeroBanner.vue'
import CampaignCard from '../components/CampaignCard.vue'

const search = ref('')
const sortBy = ref('newest')

const campaigns = [
  { title: 'Bantuan Pendidikan Anak Yatim', description: 'Membantu pendidikan anak-anak yatim yang memerlukan sokongan kewangan untuk masa depan yang lebih cerah.', target: 50000, collected: 32500 },
  { title: 'Klinik Percuma Komuniti', description: 'Menyediakan perkhidmatan kesihatan asas secara percuma kepada komuniti kurang mampu di kawasan luar bandar.', target: 80000, collected: 45000 },
  { title: 'Bantuan Asnaf Ramadhan', description: 'Program bantuan hari raya untuk golongan asnaf dan fakir miskin di seluruh negara.', target: 30000, collected: 28500 },
  { title: 'Pusat Tahfiz Al-Quran', description: 'Membantu pembiayaan operasi pusat tahfiz untuk melahirkan generasi penghafaz Al-Quran.', target: 120000, collected: 67000 },
  { title: 'Bantuan Bencana Alam', description: 'Dana kecemasan untuk mangsa bencana alam termasuk banjir, tanah runtuh dan kebakaran.', target: 200000, collected: 89000 },
  { title: 'Program Makanan Percuma', description: 'Menyediakan makanan percuma kepada gelandangan dan golongan miskin bandar setiap hari.', target: 45000, collected: 22000 },
]

const filtered = computed(() => {
  let result = campaigns
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
