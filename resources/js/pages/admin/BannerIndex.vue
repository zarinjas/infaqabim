<template>
  <div>
    <PageHeader title="Banner" subtitle="Uruskan banner halaman utama.">
      <template #actions>
        <Button tag="router-link" to="/admin/banners/create">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Banner
        </Button>
      </template>
    </PageHeader>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <DataTable :columns="columns" :rows="store.banners" :loading="store.loading">
        <template #empty>
          <EmptyState title="Belum ada banner" description="Tambah banner pertama anda untuk dipaparkan di halaman utama.">
            <template #action>
              <Button tag="router-link" to="/admin/banners/create" size="sm">Tambah Banner</Button>
            </template>
          </EmptyState>
        </template>
        <template #cell-active="{ row }">
          <Badge :variant="row.is_active ? 'success' : 'default'">{{ row.is_active ? 'Aktif' : 'Tidak Aktif' }}</Badge>
        </template>
        <template #cell-actions="{ row }">
          <div class="flex gap-3">
            <router-link
              :to="`/admin/banners/${row.id}/edit`"
              class="text-xs font-medium text-emerald-600 hover:text-emerald-700"
            >
              Edit
            </router-link>
            <button class="text-xs font-medium text-red-500 hover:text-red-600" @click="handleDelete(row.id)">Padam</button>
          </div>
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useBannerStore } from '../../stores/banners'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import EmptyState from '../../components/EmptyState.vue'
import { useToast } from '../../composables/useToast'

const store = useBannerStore()
const toast = useToast()

const columns = [
  { key: 'title', label: 'Tajuk' },
  { key: 'subtitle', label: 'Subtajuk' },
  { key: 'active', label: 'Status' },
  { key: 'actions', label: 'Tindakan' },
]

async function handleDelete(id) {
  if (!confirm('Padam banner ini?')) return
  try {
    await store.remove(id)
    toast.success('Banner berjaya dipadam.')
  } catch {
    toast.error('Gagal memadam banner.')
  }
}

onMounted(() => {
  store.fetchAll()
})
</script>
