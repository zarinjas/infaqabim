<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Donations</h1>
        <p class="mt-1 text-sm text-gray-500">View and manage donations.</p>
      </div>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-gray-100 text-xs text-gray-400">
            <th class="px-4 py-3 font-medium">ID</th>
            <th class="px-4 py-3 font-medium">Donor</th>
            <th class="px-4 py-3 font-medium">Campaign</th>
            <th class="px-4 py-3 font-medium">Amount</th>
            <th class="px-4 py-3 font-medium">Status</th>
            <th class="px-4 py-3 font-medium">Date</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <DataTable :columns="columns" :rows="store.donations" :loading="store.loading">
          <template #cell-amount="{ row }">RM{{ Number(row.amount).toLocaleString() }}</template>
          <template #cell-status="{ row }">
            <Badge :variant="row.status === 'completed' ? 'success' : row.status === 'pending' ? 'warning' : 'danger'">{{ row.status }}</Badge>
          </template>
          <template #cell-actions="{ row }">
            <div class="flex gap-2">
              <router-link
                :to="`/admin/donations/${row.id}`"
                class="text-xs text-emerald-600 hover:text-emerald-700"
              >
                View
              </router-link>
              <button v-if="row.status === 'pending'" class="text-xs text-emerald-600 hover:text-emerald-700" @click="handleApprove(row.id)">Approve</button>
              <button v-if="row.status === 'pending'" class="text-xs text-red-500 hover:text-red-600" @click="handleReject(row.id)">Reject</button>
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
import { useDonationStore } from '../../stores/donations'
import DataTable from '../../components/DataTable.vue'
import Badge from '../../components/Badge.vue'

const store = useDonationStore()

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'donor_name', label: 'Donor' },
  { key: 'campaign', label: 'Campaign' },
  { key: 'amount', label: 'Amount' },
  { key: 'status', label: 'Status' },
  { key: 'created_at', label: 'Date' },
  { key: 'actions', label: 'Actions' },
]

async function handleApprove(id) {
  if (!confirm('Approve this donation?')) return
  await store.approve(id)
}

async function handleReject(id) {
  if (!confirm('Reject this donation?')) return
  await store.reject(id)
}

async function handleDelete(id) {
  if (!confirm('Delete this donation?')) return
  await store.remove(id)
}

onMounted(() => {
  store.fetchAll()
})
</script>
