<template>
  <span class="inline-block w-fit rounded-full px-3 py-1 text-xs font-medium" :class="variantClasses">
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'default' },
})

const variantClasses = computed(() => {
  const map = {
    default: 'bg-gray-100 text-gray-700',
    primary: 'bg-emerald-100 text-emerald-700',
    warning: 'bg-amber-100 text-amber-700',
    danger: 'bg-red-100 text-red-700',
    info: 'bg-blue-100 text-blue-700',
    success: 'bg-green-100 text-green-700',
  }
  return map[props.variant] || map.default
})
</script>
