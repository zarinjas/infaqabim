<template>
  <div class="flex min-h-screen flex-col bg-gray-50 font-sans text-gray-900">
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/80 backdrop-blur-lg">
      <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <router-link to="/" class="text-xl font-bold tracking-tight text-emerald-700">
          INFAQABIM
        </router-link>
        <button
          class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden"
          @click="menuOpen = !menuOpen"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!menuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        <nav class="hidden items-center gap-8 lg:flex">
          <router-link to="/" class="text-sm font-medium text-gray-600 transition-colors hover:text-emerald-700">Home</router-link>
          <router-link to="/campaigns" class="text-sm font-medium text-gray-600 transition-colors hover:text-emerald-700">Campaigns</router-link>
          <router-link to="/donate" class="text-sm font-medium text-gray-600 transition-colors hover:text-emerald-700">Donate</router-link>
          <router-link to="/about" class="text-sm font-medium text-gray-600 transition-colors hover:text-emerald-700">About</router-link>
        </nav>
      </div>
      <div v-if="menuOpen" class="border-t border-gray-100 bg-white px-4 pb-4 pt-2 lg:hidden">
        <nav class="flex flex-col gap-3">
          <router-link to="/" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50" @click="menuOpen = false">Home</router-link>
          <router-link to="/campaigns" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50" @click="menuOpen = false">Campaigns</router-link>
          <router-link to="/donate" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50" @click="menuOpen = false">Donate</router-link>
          <router-link to="/about" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50" @click="menuOpen = false">About</router-link>
        </nav>
      </div>
    </header>

    <main class="flex-1">
      <router-view />
    </main>

    <footer class="border-t border-gray-100 bg-white">
      <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <div>
            <h3 class="text-lg font-bold text-emerald-700">INFAQABIM</h3>
            <p class="mt-2 text-sm leading-relaxed text-gray-500">
              Platform sedekah dan infaq untuk membantu mereka yang memerlukan.
            </p>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-gray-900">Quick Links</h4>
            <ul class="mt-3 space-y-2">
              <li><router-link to="/" class="text-sm text-gray-500 transition-colors hover:text-emerald-700">Home</router-link></li>
              <li><router-link to="/campaigns" class="text-sm text-gray-500 transition-colors hover:text-emerald-700">Campaigns</router-link></li>
              <li><router-link to="/about" class="text-sm text-gray-500 transition-colors hover:text-emerald-700">About</router-link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-gray-900">Contact</h4>
            <ul class="mt-3 space-y-2 text-sm text-gray-500">
              <li>hello@infaqabim.my</li>
              <li>+60 12-345 6789</li>
            </ul>
          </div>
        </div>
        <div class="mt-8 border-t border-gray-100 pt-6 text-center text-sm text-gray-400">
          &copy; {{ new Date().getFullYear() }} INFAQABIM. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const menuOpen = ref(false)
</script>
