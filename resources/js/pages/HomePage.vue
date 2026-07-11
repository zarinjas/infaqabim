<template>
  <div>
    <section v-if="banners.length" class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-700">
      <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8 lg:py-36">
        <div class="max-w-xl">
          <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">
            {{ banners[0].title }}
          </h1>
          <p v-if="banners[0].subtitle" class="mt-4 text-base leading-relaxed text-emerald-100 sm:text-lg">
            {{ banners[0].subtitle }}
          </p>
          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <router-link
              to="/donate"
              class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-emerald-800 shadow-sm transition-colors hover:bg-emerald-50 active:bg-emerald-100"
            >
              Donate Now
            </router-link>
            <router-link
              to="/campaigns"
              class="inline-flex items-center justify-center rounded-xl border border-emerald-400/40 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10"
            >
              View Campaigns
            </router-link>
          </div>
        </div>
      </div>
      <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl" />
      <div class="absolute -bottom-20 -left-20 h-60 w-60 rounded-full bg-teal-500/20 blur-3xl" />
    </section>

    <section v-if="featured" class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
          <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Featured Campaign</h2>
          <p class="mt-2 text-gray-500">Kempen yang paling memerlukan sokongan anda</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-gray-50 shadow-sm ring-1 ring-gray-100 lg:flex">
          <div class="flex aspect-[4/3] items-center justify-center bg-gradient-to-br from-emerald-100 to-teal-100 lg:w-1/2">
            <span class="text-sm text-gray-400">Campaign Image</span>
          </div>
          <div class="flex flex-col justify-center p-6 sm:p-8 lg:w-1/2">
            <span class="mb-2 inline-block w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">Featured</span>
            <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ featured.title }}</h3>
            <p class="mt-3 text-sm leading-relaxed text-gray-500">{{ featured.description }}</p>
            <div class="mt-6 space-y-2">
              <div class="h-2.5 overflow-hidden rounded-full bg-gray-200">
                <div class="h-full rounded-full bg-emerald-500" :style="{ width: featuredPercentage + '%' }" />
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="font-medium text-emerald-600">RM{{ featured.collected.toLocaleString() }}</span>
                <span class="text-gray-400">Goal RM{{ featured.target.toLocaleString() }}</span>
              </div>
            </div>
            <router-link
              :to="'/donate?campaign=' + featured.id"
              class="mt-6 inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
            >
              Donate to This Campaign
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-gray-50 py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
          <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Active Campaigns</h2>
          <p class="mt-2 text-gray-500">Pilih kempen yang ingin anda sokong</p>
        </div>
        <div v-if="campaigns.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <CampaignCard v-for="c in campaigns" :key="c.id" :campaign="c" />
        </div>
        <div v-else class="rounded-2xl bg-white py-16 text-center shadow-sm ring-1 ring-gray-100">
          <p class="text-gray-400">Tiada kempen aktif buat masa ini.</p>
        </div>
      </div>
    </section>

    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
          <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tentang INFAQABIM</h2>
          <p class="mt-4 text-sm leading-relaxed text-gray-500 sm:text-base">
            INFAQABIM adalah platform sedekah dan infaq yang ditubuhkan untuk memudahkan masyarakat Malaysia memberi sumbangan kepada golongan yang memerlukan. Kami percaya setiap sumbangan, tidak kira besar atau kecil, dapat membawa perubahan yang bermakna dalam kehidupan mereka yang kurang bernasib baik.
          </p>
          <p class="mt-4 text-sm leading-relaxed text-gray-500 sm:text-base">
            Dengan telus dan akauntabiliti sebagai prinsip utama, kami memastikan setiap ringgit yang disumbangkan sampai kepada mereka yang benar-benar memerlukan.
          </p>
          <div class="mt-8 grid grid-cols-3 gap-4 border-t border-gray-100 pt-8 text-center">
            <div>
              <div class="text-2xl font-bold text-emerald-700">RM500K+</div>
              <div class="mt-1 text-xs text-gray-400">Total Donations</div>
            </div>
            <div>
              <div class="text-2xl font-bold text-emerald-700">1,200+</div>
              <div class="mt-1 text-xs text-gray-400">Donors</div>
            </div>
            <div>
              <div class="text-2xl font-bold text-emerald-700">15+</div>
              <div class="mt-1 text-xs text-gray-400">Campaigns</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import CampaignCard from '../components/CampaignCard.vue'

const banners = ref([])
const featured = ref(null)
const campaigns = ref([])

const featuredPercentage = computed(() => {
  if (!featured.value) return 0
  return Math.min(100, Math.round((featured.value.collected / featured.value.target) * 100))
})

function mapCampaign(c) {
  return { ...c, collected: Number(c.collected_amount), target: Number(c.target_amount) }
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/home')
    banners.value = data.banners || []
    featured.value = data.featured ? mapCampaign(data.featured) : null
    campaigns.value = (data.campaigns || []).map(mapCampaign)
  } catch {
    banners.value = []
    featured.value = null
    campaigns.value = []
  }
})
</script>
