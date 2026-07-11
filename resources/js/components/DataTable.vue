<template>
  <tbody>
    <tr v-if="loading">
      <td :colspan="columns.length" class="px-4 py-10 text-center text-sm text-gray-400">
        Loading...
      </td>
    </tr>
    <tr v-for="(row, i) in rows" :key="i" class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
      <td v-for="col in columns" :key="col.key" class="whitespace-nowrap px-4 py-3 text-sm" :class="col.class">
        <slot :name="'cell-' + col.key" :row="row" :value="row[col.key]">
          {{ row[col.key] }}
        </slot>
      </td>
    </tr>
    <tr v-if="!loading && !rows.length">
      <td :colspan="columns.length" class="px-4 py-10 text-center text-sm text-gray-400">
        No data available.
      </td>
    </tr>
  </tbody>
</template>

<script setup>
defineProps({
  columns: { type: Array, required: true },
  rows: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
})
</script>
