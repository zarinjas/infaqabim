<template>
  <aside class="flex h-full w-64 flex-col border-r border-gray-200 bg-white">
    <div class="flex h-16 items-center border-b border-gray-100 px-6">
      <router-link to="/admin/dashboard" class="text-lg font-bold tracking-tight text-emerald-700">
        INFAQABIM
      </router-link>
    </div>
    <div class="border-b border-gray-100 px-6 py-3">
      <p class="text-xs text-gray-400">Logged in as</p>
      <p class="text-sm font-medium text-gray-700">{{ auth.admin?.name || 'Admin' }}</p>
    </div>
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
      <router-link
        v-for="item in navItems"
        :key="item.to"
        :to="item.to"
        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
        :class="isActive(item.to) ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
      >
        <component :is="item.icon" class="h-5 w-5" />
        {{ item.label }}
      </router-link>
    </nav>
    <div class="border-t border-gray-100 px-3 py-3">
      <button
        class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 hover:text-red-600"
        @click="handleLogout"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

const navItems = [
  { to: '/admin/dashboard', label: 'Dashboard', icon: DashboardIcon },
  { to: '/admin/banners', label: 'Banners', icon: BannerIcon },
  { to: '/admin/campaigns', label: 'Campaigns', icon: CampaignIcon },
  { to: '/admin/donations', label: 'Donations', icon: DonationIcon },
  { to: '/admin/donors', label: 'Donors', icon: DonorIcon },
  { to: '/admin/settings', label: 'Settings', icon: SettingsIcon },
]

function isActive(to) {
  return route.path === to || route.path.startsWith(to + '/')
}

async function handleLogout() {
  await auth.logout()
  router.push('/admin/login')
}

function DashboardIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
function BannerIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
function CampaignIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
function DonationIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
function DonorIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
function SettingsIcon() {
  const h = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'
  return { template: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-html="html"></svg>', setup: () => ({ html: h }) }
}
</script>
