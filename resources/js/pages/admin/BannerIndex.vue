<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Banners</h1>
        <p class="mt-1 text-sm text-gray-500">Manage homepage banners.</p>
      </div>
      <router-link
        to="/admin/banners/create"
        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
      >
        Add Banner
      </router-link>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-gray-100 text-xs text-gray-400">
            <th class="px-4 py-3 font-medium">Title</th>
            <th class="px-4 py-3 font-medium">Subtitle</th>
            <th class="px-4 py-3 font-medium">Active</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <DataTable :columns="columns" :rows="store.banners" :loading="store.loading">
          <template #cell-active="{ row }">
            <Badge :variant="row.is_active ? 'success' : 'default'">{{ row.is_active ? 'Active' : 'Inactive' }}</Badge>
          </template>
          <template #cell-actions="{ row }">
            <div class="flex gap-2">
              <router-link
                :to="`/admin/banners/${row.id}/edit`"
                class="text-xs text-emerald-600 hover:text-emerald-700"
              >
                Edit
              </router-link>
              <button class="text-xs text-red-500 hover:text-red-600" @click="handleDelete(row.id)">Delete</button>
            </div>
          </template>
        </DataTable>
      </table>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useBannerStore } from '../../stores/banners'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'

const store = useBannerStore()

const columns = [
  { key: 'title', label: 'Title' },
  { key: 'subtitle', label: 'Subtitle' },
  { key: 'active', label: 'Active' },
  { key: 'actions', label: 'Actions' },
]

async function handleDelete(id) {
  if (!confirm('Delete this banner?')) return
  await store.remove(id)
}

onMounted(() => {
  store.fetchAll()
})
</script>
