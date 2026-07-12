<template>
  <aside class="flex h-full w-64 flex-col bg-gray-900">
    <div class="flex h-16 items-center gap-2.5 border-b border-white/5 px-6">
      <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-sm font-bold text-white">
        {{ (auth.admin?.name || siteName).charAt(0) }}
      </span>
      <router-link to="/admin/dashboard" class="text-base font-bold tracking-tight text-white">
        {{ siteName }}
      </router-link>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
      <div v-for="group in navGroups" :key="group.label" class="space-y-1">
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">{{ group.label }}</p>
        <router-link
          v-for="item in group.items"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
          :class="isActive(item.to) ? 'bg-emerald-500/10 text-emerald-400' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
        >
          <component :is="item.icon" class="h-5 w-5" />
          {{ item.label }}
        </router-link>
      </div>
    </nav>

    <div class="border-t border-white/5 p-3">
      <div class="flex items-center gap-3 rounded-lg px-3 py-2.5">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-sm font-semibold text-emerald-400">
          {{ (auth.admin?.name || 'A').charAt(0) }}
        </div>
        <div class="min-w-0">
          <p class="truncate text-sm font-medium text-white">{{ auth.admin?.name || 'Admin' }}</p>
          <p class="truncate text-xs text-gray-500">{{ auth.admin?.email || '' }}</p>
        </div>
      </div>
      <button
        class="mt-1 flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-400 transition-colors hover:bg-white/5 hover:text-red-400"
        @click="handleLogout"
      >
        <Icons.logout class="h-5 w-5" />
        Log Keluar
      </button>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { usePublicSettingStore } from '../stores/publicSettings'
import { Icons } from './icons'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const publicSettings = usePublicSettingStore()

const siteName = computed(() => publicSettings.settings.site_name || 'INFAQABIM')

const navGroups = [
  {
    label: 'Utama',
    items: [
      { to: '/admin/dashboard', label: 'Dashboard', icon: Icons.dashboard },
    ],
  },
  {
    label: 'Pengurusan Kandungan',
    items: [
      { to: '/admin/banners', label: 'Banner', icon: Icons.banner },
      { to: '/admin/campaigns', label: 'Kempen', icon: Icons.campaign },
    ],
  },
  {
    label: 'Sumbangan',
    items: [
      { to: '/admin/donations', label: 'Sumbangan', icon: Icons.donation },
      { to: '/admin/donors', label: 'Penderma', icon: Icons.donor },
    ],
  },
  {
    label: 'Sistem',
    items: [
      { to: '/admin/settings', label: 'Tetapan', icon: Icons.settings },
    ],
  },
]

function isActive(to) {
  return route.path === to || route.path.startsWith(to + '/')
}

async function handleLogout() {
  await auth.logout()
  router.push('/admin/login')
}
</script>
