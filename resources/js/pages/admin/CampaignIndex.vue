<template>
  <div>
    <PageHeader title="Kempen" subtitle="Uruskan kempen sumbangan.">
      <template #actions>
        <Button tag="router-link" to="/admin/campaigns/create">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Kempen
        </Button>
      </template>
    </PageHeader>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <DataTable :columns="columns" :rows="store.campaigns" :loading="store.loading">
        <template #empty>
          <EmptyState title="Belum ada kempen" description="Tambah kempen pertama anda untuk mula menerima sumbangan.">
            <template #action>
              <Button tag="router-link" to="/admin/campaigns/create" size="sm">Tambah Kempen</Button>
            </template>
          </EmptyState>
        </template>
        <template #cell-progress="{ row }">
          <ProgressBar :collected="Number(row.collected_amount)" :target="Number(row.target_amount)" class="w-40" />
        </template>
        <template #cell-status="{ row }">
          <Badge :variant="row.is_active ? 'success' : 'default'">{{ row.is_active ? 'Aktif' : 'Tidak Aktif' }}</Badge>
        </template>
        <template #cell-actions="{ row }">
          <div class="flex gap-3">
            <router-link
              :to="`/admin/campaigns/${row.id}/edit`"
              class="text-xs font-medium text-emerald-600 hover:text-emerald-700"
            >
              Sunting
            </router-link>
            <router-link
              :to="`/admin/campaigns/${row.id}/qr`"
              class="text-xs font-medium text-blue-600 hover:text-blue-700"
            >
              QR
            </router-link>
            <button class="text-xs font-medium text-red-500 hover:text-red-600" @click="handleDelete(row.id)">
              Padam
            </button>
          </div>
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCampaignStore } from '../../stores/campaigns'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'
import ProgressBar from '../../components/ProgressBar.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import EmptyState from '../../components/EmptyState.vue'
import { useToast } from '../../composables/useToast'

const store = useCampaignStore()
const toast = useToast()

const columns = [
  { key: 'title', label: 'Tajuk' },
  { key: 'progress', label: 'Kemajuan' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Tindakan' },
]

async function handleDelete(id) {
  if (!confirm('Padam kempen ini?')) return
  try {
    await store.remove(id)
    toast.success('Kempen berjaya dipadam.')
  } catch {
    toast.error('Gagal memadam kempen.')
  }
}

onMounted(() => {
  store.fetchAll()
})
</script>
