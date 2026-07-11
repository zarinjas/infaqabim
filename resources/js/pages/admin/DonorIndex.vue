<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Donors</h1>
        <p class="mt-1 text-sm text-gray-500">View all donors and their contributions.</p>
      </div>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-gray-100 text-xs text-gray-400">
            <th class="px-4 py-3 font-medium">Name</th>
            <th class="px-4 py-3 font-medium">Email</th>
            <th class="px-4 py-3 font-medium">Phone</th>
          </tr>
        </thead>
        <DataTable :columns="columns" :rows="store.donors" :loading="store.loading" />
      </table>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDonorStore } from '../../stores/donors'
import DataTable from '../../components/DataTable.vue'

const store = useDonorStore()

const columns = [
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Phone' },
]

onMounted(() => {
  store.fetchAll()
})
</script>
