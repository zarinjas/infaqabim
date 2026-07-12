<template>
  <div>
    <section v-if="banners.length" class="relative overflow-hidden bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-700">
      <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px" />
      <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-28">
        <div class="max-w-xl">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-emerald-100 ring-1 ring-white/20">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Kempen Aktif
          </span>
          <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
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
              Sumbang Sekarang
            </router-link>
            <router-link
              to="/campaigns"
              class="inline-flex items-center justify-center rounded-xl border border-emerald-400/40 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10"
            >
              Lihat Semua Kempen
            </router-link>
          </div>
        </div>
      </div>
      <div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl" />
      <div class="absolute -bottom-20 -left-20 h-60 w-60 rounded-full bg-teal-500/20 blur-3xl" />
    </section>

    <section class="border-b border-gray-100 bg-white py-8">
      <div class="mx-auto grid max-w-6xl grid-cols-3 gap-6 px-4 text-center sm:px-6 lg:px-8">
        <div>
          <div class="text-2xl font-bold text-emerald-700 sm:text-3xl">RM500K+</div>
          <div class="mt-1 text-xs text-gray-400">Jumlah Terkumpul</div>
        </div>
        <div>
          <div class="text-2xl font-bold text-emerald-700 sm:text-3xl">1,200+</div>
          <div class="mt-1 text-xs text-gray-400">Penyumbang</div>
        </div>
        <div>
          <div class="text-2xl font-bold text-emerald-700 sm:text-3xl">15+</div>
          <div class="mt-1 text-xs text-gray-400">Kempen</div>
        </div>
      </div>
    </section>

    <section v-if="featured" class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
          <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Kempen Pilihan</h2>
          <p class="mt-2 text-gray-500">Kempen yang paling memerlukan sokongan anda</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-gray-50 shadow-sm ring-1 ring-gray-100 lg:flex">
          <div class="relative flex aspect-[4/5] items-center justify-center bg-gradient-to-br from-emerald-100 to-teal-100 lg:w-1/2">
            <img v-if="featured.image" :src="`/storage/${featured.image}`" :alt="featured.title" class="h-full w-full object-cover" />
            <span v-else class="text-sm text-gray-400">{{ featured.title }}</span>
          </div>
          <div class="flex flex-col justify-center p-6 sm:p-8 lg:w-1/2">
            <span class="mb-2 inline-block w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">Pilihan Editor</span>
            <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">{{ featured.title }}</h3>
            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-500">{{ featured.description }}</p>
            <div class="mt-6">
              <ProgressBar :collected="featured.collected" :target="featured.target" />
            </div>
            <div v-if="featured.donations_count !== undefined" class="mt-3 flex items-center gap-1.5 text-xs text-gray-400">
              <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              {{ featured.donations_count }} penyumbang
            </div>
            <router-link
              :to="`/campaigns/${featured.id}`"
              class="mt-6 inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
            >
              Sumbang untuk Kempen Ini
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-gray-50 py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Kempen Aktif</h2>
            <p class="mt-2 text-gray-500">Pilih kempen yang ingin anda sokong</p>
          </div>
          <router-link to="/campaigns" class="inline-flex items-center gap-1 text-sm font-medium text-emerald-600 hover:text-emerald-700">
            Lihat semua
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </router-link>
        </div>
        <div v-if="campaigns.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <CampaignCard v-for="c in campaigns.slice(0, 6)" :key="c.id" :campaign="c" />
        </div>
        <EmptyState v-else title="Tiada kempen aktif buat masa ini." />
      </div>
    </section>

    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
          <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tentang {{ siteName }}</h2>
          <p class="mt-4 text-sm leading-relaxed text-gray-500 sm:text-base">
            {{ siteName }} adalah platform sedekah dan infaq yang ditubuhkan untuk memudahkan masyarakat Malaysia memberi sumbangan kepada golongan yang memerlukan. Kami percaya setiap sumbangan, tidak kira besar atau kecil, dapat membawa perubahan yang bermakna dalam kehidupan mereka yang kurang bernasib baik.
          </p>
          <div class="mt-6 flex justify-center gap-4">
            <router-link to="/about" class="inline-flex items-center gap-1 text-sm font-medium text-emerald-600 hover:text-emerald-700">
              Ketahui lebih lanjut
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </router-link>
            <router-link to="/admin/login" class="inline-flex items-center gap-1 text-sm font-medium text-gray-400 hover:text-gray-600">
              Log Masuk Admin
            </router-link>
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
import ProgressBar from '../components/ProgressBar.vue'
import EmptyState from '../components/EmptyState.vue'
import { usePublicSettingStore } from '../stores/publicSettings'

const publicSettings = usePublicSettingStore()
const siteName = computed(() => publicSettings.settings.site_name || 'INFAQABIM')

const banners = ref([])
const featured = ref(null)
const campaigns = ref([])

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
