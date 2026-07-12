<template>
  <table class="w-full text-left text-sm">
    <thead>
      <tr class="border-b border-gray-100 text-xs text-gray-400">
        <th v-for="col in columns" :key="col.key" class="px-4 py-3 font-medium">{{ col.label }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="loading">
        <td :colspan="columns.length" class="px-4 py-10 text-center text-sm text-gray-400">
          <div class="flex flex-col items-center gap-2">
            <svg class="h-5 w-5 animate-spin text-gray-300" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            Memuatkan...
          </div>
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
          <slot name="empty">
            {{ emptyText }}
          </slot>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
defineProps({
  columns: { type: Array, required: true },
  rows: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  emptyText: { type: String, default: 'Tiada data.' },
})
</script>
