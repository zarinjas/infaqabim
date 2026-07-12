<template>
  <router-link
    :to="`/campaigns/${campaign.id}`"
    class="group flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-gray-100 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:ring-gray-200"
  >
    <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
      <img
        v-if="campaign.image"
        :src="`/storage/${campaign.image}`"
        :alt="campaign.title"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
      />
      <div v-else class="flex h-full items-center justify-center bg-gradient-to-br from-emerald-100 to-teal-100 text-sm text-gray-400">
        {{ campaign.title }}
      </div>
      <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-sm backdrop-blur-sm">
        {{ percentage }}% terkumpul
      </span>
    </div>
    <div class="flex flex-1 flex-col gap-3 p-5">
      <h3 class="line-clamp-2 text-base font-semibold leading-snug text-gray-900 group-hover:text-emerald-700">
        {{ campaign.title }}
      </h3>
      <p class="line-clamp-2 text-sm leading-relaxed text-gray-500">{{ campaign.description }}</p>

      <div class="mt-auto space-y-2.5 pt-1">
        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
          <div class="h-full rounded-full bg-emerald-500 transition-all" :style="{ width: percentage + '%' }" />
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="font-semibold text-gray-900">RM{{ campaign.collected.toLocaleString() }}</span>
          <span class="text-xs text-gray-400">daripada RM{{ campaign.target.toLocaleString() }}</span>
        </div>
        <div v-if="campaign.donations_count !== undefined" class="flex items-center gap-1.5 text-xs text-gray-400">
          <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          {{ campaign.donations_count }} penyumbang
        </div>
      </div>

      <span class="mt-1 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 py-2.5 text-sm font-medium text-white transition-colors group-hover:bg-emerald-700">
        Sumbang Sekarang
      </span>
    </div>
  </router-link>
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
