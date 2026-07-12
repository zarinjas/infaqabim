<template>
  <div class="mx-auto max-w-lg px-4 py-8 text-center sm:px-6">
    <div v-if="loading" class="flex items-center justify-center py-20">
      <svg class="h-6 w-6 animate-spin text-emerald-500" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
      </svg>
    </div>

    <template v-else-if="campaign">
      <div id="qr-print-area" class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-xl font-bold text-gray-900">{{ campaign.title }}</h1>
        <p class="mt-1 text-sm text-gray-500">Imbas kod QR untuk ke halaman kempen</p>

        <div class="mt-6 flex justify-center">
          <canvas ref="canvas" class="rounded-xl ring-1 ring-gray-200"></canvas>
        </div>

        <p class="mt-4 text-xs text-gray-400 break-all">{{ campaignUrl }}</p>
      </div>

      <div class="mt-6 flex justify-center gap-3">
        <button @click="handlePrint" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-700">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Cetak QR
        </button>
        <router-link to="/admin/campaigns" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-6 py-3 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50">
          Kembali
        </router-link>
      </div>
    </template>

    <div v-else class="py-20 text-center">
      <p class="text-gray-500">Kempen tidak ditemui.</p>
      <router-link to="/admin/campaigns" class="mt-4 inline-block text-sm font-medium text-emerald-600">Kembali ke senarai kempen</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import QRCode from 'qrcode'

const route = useRoute()
const campaign = ref(null)
const loading = ref(true)
const canvas = ref(null)

const campaignUrl = computed(() => `${window.location.origin}/campaigns/${campaign.value?.id}`)

async function generateQR() {
  await nextTick()
  if (canvas.value) {
    try {
      await QRCode.toCanvas(canvas.value, campaignUrl.value, {
        width: 280,
        margin: 2,
        color: { dark: '#166534', light: '#ffffff' },
      })
    } catch (err) {
      console.error('QR generation failed', err)
    }
  }
}

function handlePrint() {
  window.print()
}

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/admin/campaigns/${route.params.id}`)
    campaign.value = data
    await generateQR()
  } catch {
    campaign.value = null
  } finally {
    loading.value = false
  }
})
</script>

<style>
@media print {
  header, footer, nav, .sidebar, .sticky, button, .no-print {
    display: none !important;
  }
  #qr-print-area {
    box-shadow: none !important;
    border: none !important;
    padding: 0 !important;
  }
  body {
    background: white !important;
  }
}
</style>