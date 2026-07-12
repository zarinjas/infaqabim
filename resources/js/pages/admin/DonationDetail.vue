<template>
  <div>
    <PageHeader
      :title="`Donasi #${donation?.id ?? ''}`"
      subtitle="Butiran donasi."
      :breadcrumb="[{ label: 'Donasi', to: '/admin/donations' }, { label: `#${route.params.id}` }]"
    >
      <template #actions>
        <Button tag="router-link" to="/admin/donations" variant="outline">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali
        </Button>
      </template>
    </PageHeader>

    <div v-if="loading" class="mt-6 text-sm text-gray-400">Memuatkan...</div>

    <div v-else class="mt-6 space-y-6">
      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Maklumat Penderma</h3>
        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <dt class="text-xs text-gray-400">Nama</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_name }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">E-mel</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_email }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Telefon</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_phone || '-' }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Nombor Rujukan</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.reference_number || '-' }}</dd>
          </div>
        </dl>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Butiran Donasi</h3>
        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <dt class="text-xs text-gray-400">Kempen</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.campaign?.title || '-' }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Jumlah</dt>
            <dd class="text-sm font-medium text-gray-900">RM{{ Number(donation.amount).toLocaleString() }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Status</dt>
            <dd>
              <Badge :variant="donation.status === 'completed' ? 'success' : donation.status === 'pending' ? 'warning' : 'danger'">{{ statusLabel(donation.status) }}</Badge>
            </dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Tarikh</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.created_at }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Kaedah Pembayaran</dt>
            <dd class="text-sm font-medium text-gray-900">
              {{ donation.payment_gateway === 'senangpay' ? 'SenangPay' : 'Pindahan Bank Manual' }}
            </dd>
          </div>
          <div v-if="donation.payment_gateway === 'senangpay'">
            <dt class="text-xs text-gray-400">ID Transaksi Gateway</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.gateway_transaction_id || '-' }}</dd>
          </div>
        </dl>

        <div v-if="donation.payment_gateway !== 'senangpay' && donation.receipt_image" class="mt-6">
          <dt class="text-xs text-gray-400">Bukti Pindahan</dt>
          <a :href="`/storage/${donation.receipt_image}`" target="_blank" rel="noopener noreferrer" class="mt-2 block max-w-xs overflow-hidden rounded-xl ring-1 ring-gray-200 transition-opacity hover:opacity-90">
            <img v-if="isImage(donation.receipt_image)" :src="`/storage/${donation.receipt_image}`" alt="Bukti pindahan" class="w-full object-cover" />
            <div v-else class="flex items-center gap-2 bg-gray-50 px-4 py-3 text-sm text-emerald-600">
              Lihat Resit PDF &rarr;
            </div>
          </a>
        </div>

        <div v-if="donation.status === 'pending'" class="mt-6 flex gap-3">
          <Button @click="handleApprove">Luluskan</Button>
          <Button variant="danger" @click="handleReject">Tolak</Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useDonationStore } from '../../stores/donations'
import Badge from '../../components/Badge.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const route = useRoute()
const store = useDonationStore()
const toast = useToast()

const donation = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/admin/donations/${route.params.id}`)
    donation.value = data
  } catch {
    router.push('/admin/donations')
  } finally {
    loading.value = false
  }
})

function statusLabel(status) {
  return { completed: 'Selesai', pending: 'Belum Selesai', failed: 'Gagal' }[status] || status
}

async function handleApprove() {
  if (!confirm('Luluskan donasi ini?')) return
  try {
    await store.approve(route.params.id)
    donation.value.status = 'completed'
    toast.success('Donasi diluluskan.')
  } catch {
    toast.error('Gagal meluluskan donasi.')
  }
}

async function handleReject() {
  if (!confirm('Tolak donasi ini?')) return
  try {
    await store.reject(route.params.id)
    donation.value.status = 'failed'
    toast.success('Donasi ditolak.')
  } catch {
    toast.error('Gagal menolak donasi.')
  }
}

function isImage(path) {
  return /\.(jpe?g|png|gif|webp)$/i.test(path || '')
}
</script>
