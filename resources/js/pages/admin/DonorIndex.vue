<template>
  <div>
    <PageHeader title="Penderma" subtitle="Lihat semua penderma dan sumbangan mereka." />
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
      <DataTable :columns="columns" :rows="store.donors" :loading="store.loading">
        <template #empty>
          <EmptyState title="Belum ada penderma." description="Penderma yang membuat sumbangan akan dipaparkan di sini." />
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDonorStore } from '../../stores/donors'
import DataTable from '../../components/DataTable.vue'
import PageHeader from '../../components/PageHeader.vue'
import EmptyState from '../../components/EmptyState.vue'

const store = useDonorStore()

const columns = [
  { key: 'donor_name', label: 'Nama' },
  { key: 'donor_email', label: 'E-mel' },
  { key: 'donor_phone', label: 'Telefon' },
]

onMounted(() => {
  store.fetchAll()
})
</script>
