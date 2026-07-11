<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
        <p class="mt-1 text-sm text-gray-500">Manage donation campaigns.</p>
      </div>
      <router-link
        to="/admin/campaigns/create"
        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
      >
        Add Campaign
      </router-link>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-gray-100 text-xs text-gray-400">
            <th class="px-4 py-3 font-medium">Title</th>
            <th class="px-4 py-3 font-medium">Target</th>
            <th class="px-4 py-3 font-medium">Collected</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <DataTable :columns="columns" :rows="store.campaigns" :loading="store.loading">
          <template #cell-target="{ row }">RM{{ Number(row.target_amount).toLocaleString() }}</template>
          <template #cell-collected="{ row }">RM{{ Number(row.collected_amount).toLocaleString() }}</template>
          <template #cell-status="{ row }">
            <Badge :variant="row.is_active ? 'success' : 'default'">{{ row.is_active ? 'Active' : 'Inactive' }}</Badge>
          </template>
          <template #cell-actions="{ row }">
            <div class="flex gap-2">
              <router-link
                :to="`/admin/campaigns/${row.id}/edit`"
                class="text-xs text-emerald-600 hover:text-emerald-700"
              >
                Edit
              </router-link>
              <button class="text-xs text-red-500 hover:text-red-600" @click="handleDelete(row.id)">
                Delete
              </button>
            </div>
          </template>
        </DataTable>
      </table>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCampaignStore } from '../../stores/campaigns'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'

const store = useCampaignStore()

const columns = [
  { key: 'title', label: 'Title' },
  { key: 'target', label: 'Target' },
  { key: 'collected', label: 'Collected' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Actions' },
]

async function handleDelete(id) {
  if (!confirm('Delete this campaign?')) return
  await store.remove(id)
}

onMounted(() => {
  store.fetchAll()
})
</script>
