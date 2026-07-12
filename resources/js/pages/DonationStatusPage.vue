<template>
  <div>
    <HeroBanner
      :title="statusTitle"
      :subtitle="statusSubtitle"
      :bg-gradient="statusGradient"
    />

    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-lg px-4 text-center sm:px-6 lg:px-8">
        <div v-if="loading" class="text-sm text-gray-400">Checking payment status...</div>

        <div v-else class="rounded-2xl bg-gray-50 p-8 shadow-sm ring-1 ring-gray-100">
          <div
            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full"
            :class="statusIconBg"
          >
            <svg v-if="status === 'completed'" class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else-if="status === 'failed'" class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg v-else class="h-8 w-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <h2 class="mt-4 text-lg font-semibold text-gray-900">{{ statusHeading }}</h2>
          <p class="mt-2 text-sm text-gray-500">{{ statusMessage }}</p>

          <div v-if="donation" class="mt-6 rounded-xl bg-white p-4 text-left text-sm ring-1 ring-gray-100">
            <div class="flex justify-between py-1">
              <span class="text-gray-400">Reference</span>
              <span class="font-medium text-gray-900">{{ donation.reference_number }}</span>
            </div>
            <div class="flex justify-between py-1">
              <span class="text-gray-400">Amount</span>
              <span class="font-medium text-gray-900">RM{{ Number(donation.amount).toLocaleString() }}</span>
            </div>
          </div>

          <router-link
            to="/"
            class="mt-6 inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
          >
            Kembali ke Laman Utama
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import HeroBanner from '../components/HeroBanner.vue'

const route = useRoute()
const loading = ref(true)
const donation = ref(null)
const status = ref('pending')

const statusTitle = computed(() => 'Status Pembayaran')
const statusSubtitle = computed(() => 'Semakan status pembayaran anda.')
const statusGradient = 'bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-700'

const statusHeading = computed(() => {
  if (status.value === 'completed') return 'Terima kasih atas sumbangan anda!'
  if (status.value === 'failed') return 'Pembayaran tidak berjaya'
  return 'Pembayaran sedang diproses'
})

const statusMessage = computed(() => {
  if (status.value === 'completed') return 'Donasi anda telah berjaya direkodkan.'
  if (status.value === 'failed') return 'Sila cuba semula atau hubungi kami jika masalah berterusan.'
  return 'Status pembayaran anda sedang disahkan. Sila semak semula sebentar lagi.'
})

const statusIconBg = computed(() => {
  if (status.value === 'completed') return 'bg-emerald-100'
  if (status.value === 'failed') return 'bg-red-100'
  return 'bg-amber-100'
})

onMounted(async () => {
  const orderId = route.query.order_id
  if (!orderId) {
    loading.value = false
    return
  }
  try {
    const { data } = await axios.get(`/api/donations/status/${orderId}`)
    donation.value = data
    status.value = data.status
  } catch {
    status.value = 'pending'
  } finally {
    loading.value = false
  }
})
</script>
