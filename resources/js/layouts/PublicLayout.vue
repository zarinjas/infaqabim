<template>
  <div class="flex min-h-screen flex-col bg-gray-50 font-sans text-gray-900">
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur-lg">
      <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        <router-link to="/" class="flex items-center gap-2">
          <img v-if="settings.app_logo" :src="`/storage/${settings.app_logo}`" alt="" class="h-20 w-auto" />
          <template v-else>
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-sm text-white">
              {{ siteName.charAt(0) }}
            </span>
            <span class="text-xl font-bold tracking-tight text-emerald-700">{{ siteName }}</span>
          </template>
        </router-link>
        <nav class="hidden items-center gap-1 lg:flex">
          <router-link
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="rounded-lg px-3.5 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 hover:text-emerald-700"
            active-class="text-emerald-700"
          >
            {{ item.label }}
          </router-link>
        </nav>
        <div class="hidden items-center gap-3 lg:flex">
          <router-link
            to="/donate"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-700"
          >
            Sumbang Sekarang
          </router-link>
        </div>
        <button
          class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden"
          @click="menuOpen = !menuOpen"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!menuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div v-if="menuOpen" class="border-t border-gray-100 bg-white px-4 pb-4 pt-2 lg:hidden">
        <nav class="flex flex-col gap-1">
          <router-link
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            class="rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50"
            @click="menuOpen = false"
          >
            {{ item.label }}
          </router-link>
          <router-link
            to="/donate"
            class="mt-2 inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white"
            @click="menuOpen = false"
          >
            Sumbang Sekarang
          </router-link>
        </nav>
      </div>
    </header>

    <main class="flex-1">
      <router-view />
    </main>

    <footer class="border-t border-gray-100 bg-white">
      <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
          <div class="flex items-center gap-2">
            <img v-if="settings.app_logo" :src="`/storage/${settings.app_logo}`" alt="" class="h-20 w-auto" />
            <template v-else>
              <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-sm text-white">
                {{ siteName.charAt(0) }}
              </span>
              <span class="text-sm font-semibold text-gray-900">{{ siteName }}</span>
            </template>
          </div>
          <div v-if="socialLinks.length" class="flex gap-3">
            <a
              v-for="social in socialLinks"
              :key="social.key"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors hover:bg-emerald-100 hover:text-emerald-700"
              :aria-label="social.key"
            >
              <component :is="social.icon" class="h-4 w-4" />
            </a>
          </div>
        </div>
        <div class="mt-6 text-center text-xs text-gray-400">
          &copy; {{ new Date().getFullYear() }} {{ siteName }}. Hak cipta terpelihara.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, h } from 'vue'
import { usePublicSettingStore } from '../stores/publicSettings'

const menuOpen = ref(false)
const store = usePublicSettingStore()
const settings = computed(() => store.settings)

const siteName = computed(() => settings.value.site_name || 'INFAQABIM')

const navItems = computed(() => [
  { to: '/', label: settings.value.nav_label_1 || 'Home' },
  { to: '/campaigns', label: settings.value.nav_label_2 || 'Campaigns' },
  { to: '/donate', label: settings.value.nav_label_3 || 'Donate' },
  { to: '/about', label: settings.value.nav_label_4 || 'About' },
])

function icon(path) {
  return { render: () => h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { d: path })]) }
}

const SOCIAL_ICONS = {
  social_facebook: icon('M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.891h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.891h-2.33v6.987C18.343 21.128 22 16.991 22 12z'),
  social_twitter: icon('M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'),
  social_instagram: icon('M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.248.637.416 1.363.465 2.428.05 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.217 1.79-.465 2.428a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.637.248-1.363.416-2.428.465-1.066.05-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.217-2.428-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.248-.637-.416-1.363-.465-2.428C2.01 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.065.217-1.79.465-2.428a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.637-.248 1.363-.416 2.428-.465C8.944 2.01 9.283 2 12 2zm0 3.784a6.216 6.216 0 100 12.432 6.216 6.216 0 000-12.432zm0 10.253a4.037 4.037 0 110-8.074 4.037 4.037 0 010 8.074zm7.914-10.507a1.44 1.44 0 11-2.881 0 1.44 1.44 0 012.881 0z'),
  social_youtube: icon('M21.582 7.186a2.51 2.51 0 00-1.768-1.768C18.254 5 12 5 12 5s-6.254 0-7.814.418A2.51 2.51 0 002.418 7.186C2 8.746 2 12 2 12s0 3.254.418 4.814a2.51 2.51 0 001.768 1.768C5.746 19 12 19 12 19s6.254 0 7.814-.418a2.51 2.51 0 001.768-1.768C22 15.254 22 12 22 12s0-3.254-.418-4.814zM10 15.5v-7l6 3.5-6 3.5z'),
  social_telegram: icon('M22 2L2 10.5l6.5 2.5L11 20l3-4.5 5.5 3.5L22 2zM9 13l9-7-7 8-.5 3.5L9 13z'),
}

const socialLinks = computed(() =>
  Object.keys(SOCIAL_ICONS)
    .filter((key) => settings.value[key])
    .map((key) => ({ key, url: settings.value[key], icon: SOCIAL_ICONS[key] }))
)

onMounted(() => {
  store.fetch()
})
</script>
