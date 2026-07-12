<template>
  <div class="flex h-screen overflow-hidden bg-gray-50">
    <AdminSidebar class="hidden lg:block" />
    <div class="flex flex-1 flex-col overflow-hidden">
      <header class="flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white px-4 lg:px-6">
        <button class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden" @click="sidebarOpen = !sidebarOpen">
          <Icons.menu class="h-6 w-6" />
        </button>
        <div class="flex-1" />
        <router-link
          to="/"
          target="_blank"
          class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-medium text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 sm:flex"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          Lihat Laman Awam
        </router-link>
        <div class="flex items-center gap-3 border-l border-gray-100 pl-4">
          <div class="hidden text-right sm:block">
            <p class="text-sm font-medium text-gray-700">{{ auth.admin?.name || 'Admin' }}</p>
          </div>
          <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">
            {{ auth.admin?.name?.charAt(0) || 'A' }}
          </div>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto p-4 lg:p-6">
        <router-view />
      </main>
    </div>
    <Transition name="fade">
      <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
        <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false" />
        <div class="relative h-full w-64">
          <AdminSidebar />
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminSidebar from '../components/AdminSidebar.vue'
import { useAuthStore } from '../stores/auth'
import { Icons } from '../components/icons'

const auth = useAuthStore()
const sidebarOpen = ref(false)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
