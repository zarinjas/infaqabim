<template>
  <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100">
      <slot name="icon">
        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h3.28a1 1 0 00.948-.684l.72-2.16A1 1 0 0113.28 2H16a2 2 0 012 2v3M5 19h14a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2z" />
        </svg>
      </slot>
    </div>
    <p class="mt-4 text-sm font-medium text-gray-900">{{ title }}</p>
    <p v-if="description" class="mt-1 max-w-sm text-sm text-gray-400">{{ description }}</p>
    <div v-if="$slots.action" class="mt-5">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, default: 'No data' },
  description: { type: String, default: '' },
})
</script>
