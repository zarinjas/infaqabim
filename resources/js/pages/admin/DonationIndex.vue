<template>
  <div>
    <PageHeader title="Donasi" subtitle="Lihat dan uruskan donasi." />
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <DataTable :columns="columns" :rows="store.donations" :loading="store.loading">
        <template #empty>
          <EmptyState title="Belum ada donasi." description="Donasi daripada penderma akan dipaparkan di sini." />
        </template>
        <template #cell-amount="{ row }">RM{{ Number(row.amount).toLocaleString() }}</template>
        <template #cell-status="{ row }">
          <Badge :variant="row.status === 'completed' ? 'success' : row.status === 'pending' ? 'warning' : 'danger'">{{ statusLabel(row.status) }}</Badge>
        </template>
        <template #cell-actions="{ row }">
          <div class="flex gap-3">
            <router-link
              :to="`/admin/donations/${row.id}`"
              class="text-xs font-medium text-emerald-600 hover:text-emerald-700"
            >
              Lihat
            </router-link>
            <button v-if="row.status === 'pending'" class="text-xs font-medium text-emerald-600 hover:text-emerald-700" @click="handleApprove(row.id)">Lulus</button>
            <button v-if="row.status === 'pending'" class="text-xs font-medium text-red-500 hover:text-red-600" @click="handleReject(row.id)">Tolak</button>
            <button class="text-xs font-medium text-red-500 hover:text-red-600" @click="handleDelete(row.id)">Padam</button>
          </div>
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDonationStore } from '../../stores/donations'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'
import PageHeader from '../../components/PageHeader.vue'
import EmptyState from '../../components/EmptyState.vue'
import { useToast } from '../../composables/useToast'

const store = useDonationStore()
const toast = useToast()

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'donor_name', label: 'Penderma' },
  { key: 'campaign', label: 'Kempen' },
  { key: 'amount', label: 'Jumlah' },
  { key: 'status', label: 'Status' },
  { key: 'created_at', label: 'Tarikh' },
  { key: 'actions', label: 'Tindakan' },
]

function statusLabel(status) {
  return { completed: 'Selesai', pending: 'Belum Selesai', failed: 'Gagal' }[status] || status
}

async function handleApprove(id) {
  if (!confirm('Luluskan donasi ini?')) return
  try {
    await store.approve(id)
    toast.success('Donasi diluluskan.')
  } catch {
    toast.error('Gagal meluluskan donasi.')
  }
}

async function handleReject(id) {
  if (!confirm('Tolak donasi ini?')) return
  try {
    await store.reject(id)
    toast.success('Donasi ditolak.')
  } catch {
    toast.error('Gagal menolak donasi.')
  }
}

async function handleDelete(id) {
  if (!confirm('Padam donasi ini?')) return
  try {
    await store.remove(id)
    toast.success('Donasi berjaya dipadam.')
  } catch {
    toast.error('Gagal memadam donasi.')
  }
}

onMounted(() => {
  store.fetchAll()
})
</script>
