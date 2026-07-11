<template>
  <div class="group rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md">
    <div class="aspect-[16/9] overflow-hidden rounded-t-2xl bg-gray-100">
      <div class="flex h-full items-center justify-center bg-gradient-to-br from-emerald-100 to-teal-100 text-sm text-gray-400">
        Campaign Image
      </div>
    </div>
    <div class="space-y-3 p-5">
      <h3 class="text-lg font-semibold text-gray-900">{{ campaign.title }}</h3>
      <p class="text-sm leading-relaxed text-gray-500">{{ campaign.description }}</p>
      <div class="space-y-2">
        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
          <div class="h-full rounded-full bg-emerald-500 transition-all" :style="{ width: percentage + '%' }" />
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="font-medium text-emerald-600">RM{{ campaign.collected.toLocaleString() }}</span>
          <span class="text-gray-400">Goal RM{{ campaign.target.toLocaleString() }}</span>
        </div>
      </div>
      <button class="w-full rounded-xl bg-emerald-600 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700 active:bg-emerald-800">
        Donate Now
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  campaign: {
    type: Object,
    default: () => ({
      title: 'Bantuan Pendidikan Anak Yatim',
      description: 'Membantu pendidikan anak-anak yatim yang memerlukan sokongan kewangan untuk masa depan yang lebih cerah.',
      target: 50000,
      collected: 32500,
    }),
  },
})

const percentage = computed(() => {
  return Math.min(100, Math.round((props.campaign.collected / props.campaign.target) * 100))
})
</script>
