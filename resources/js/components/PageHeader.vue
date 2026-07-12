<template>
  <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <nav v-if="breadcrumb.length" class="mb-1.5 flex items-center gap-1.5 text-xs text-gray-400">
        <template v-for="(item, i) in breadcrumb" :key="i">
          <router-link v-if="item.to" :to="item.to" class="hover:text-emerald-600">{{ item.label }}</router-link>
          <span v-else>{{ item.label }}</span>
          <svg v-if="i < breadcrumb.length - 1" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </template>
      </nav>
      <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ title }}</h1>
      <p v-if="subtitle" class="mt-1 text-sm text-gray-500">{{ subtitle }}</p>
    </div>
    <div v-if="$slots.actions" class="flex shrink-0 items-center gap-3">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  breadcrumb: { type: Array, default: () => [] },
})
</script>
