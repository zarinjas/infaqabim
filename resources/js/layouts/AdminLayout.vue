<template>
  <div class="flex h-screen overflow-hidden bg-gray-50">
    <AdminSidebar class="hidden lg:block" />
    <div class="flex flex-1 flex-col overflow-hidden">
      <header class="flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 lg:px-6">
        <button class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden" @click="sidebarOpen = !sidebarOpen">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="flex-1" />
        <div class="flex items-center gap-3">
          <span class="text-sm text-gray-600">Admin</span>
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-sm font-medium text-emerald-700">A</div>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto p-4 lg:p-6">
        <router-view />
      </main>
    </div>
    <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
      <div class="absolute inset-0 bg-black/30" @click="sidebarOpen = false" />
      <div class="relative h-full w-64 bg-white">
        <AdminSidebar />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminSidebar from '../components/AdminSidebar.vue'

const sidebarOpen = ref(false)
</script>
