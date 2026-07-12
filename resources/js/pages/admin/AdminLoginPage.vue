<template>
  <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 px-4">
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -left-32 -top-32 h-96 w-96 animate-pulse rounded-full bg-emerald-400/20 blur-3xl" style="animation-duration: 4s" />
      <div class="absolute -bottom-32 -right-32 h-96 w-96 animate-pulse rounded-full bg-teal-400/20 blur-3xl" style="animation-duration: 4s; animation-delay: 1s" />
      <div class="absolute left-1/3 top-1/3 h-64 w-64 animate-pulse rounded-full bg-emerald-300/10 blur-3xl" style="animation-duration: 5s; animation-delay: 2s" />
      <div class="absolute bottom-1/4 right-1/4 h-48 w-48 animate-pulse rounded-full bg-cyan-400/10 blur-3xl" style="animation-duration: 5s; animation-delay: 3s" />
    </div>

    <div class="relative w-full max-w-md">
      <div class="rounded-2xl border border-white/10 bg-white/10 p-8 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-10">
        <div class="mb-8 text-center">
          <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-500/30">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-white">{{ siteName }}</h1>
          <p class="mt-1 text-sm text-emerald-200/70">Panel Admin</p>
        </div>

        <div v-if="errors.general" class="mb-6 flex items-center gap-3 rounded-lg border border-red-400/30 bg-red-500/10 px-4 py-3 backdrop-blur-sm">
          <svg class="h-5 w-5 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-sm text-red-200">{{ errors.general }}</p>
        </div>

        <form @submit.prevent="login" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-white/80">E-mel</label>
            <div class="relative mt-1.5">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg class="h-5 w-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <input
                v-model="form.email"
                type="email"
                autocomplete="email"
                placeholder="admin@infaqabim.my"
                class="w-full rounded-xl border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 text-sm text-white placeholder-white/30 backdrop-blur-sm transition-all focus:border-emerald-400/50 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                :class="errors.email ? 'border-red-400/50' : ''"
                @input="errors.email = ''"
              />
            </div>
            <p v-if="errors.email" class="mt-1 text-xs text-red-300">{{ errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-white/80">Kata Laluan</label>
            <div class="relative mt-1.5">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg class="h-5 w-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full rounded-xl border border-white/10 bg-white/5 py-2.5 pl-10 pr-10 text-sm text-white placeholder-white/30 backdrop-blur-sm transition-all focus:border-emerald-400/50 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                :class="errors.password ? 'border-red-400/50' : ''"
                @input="errors.password = ''"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-white/40 transition-colors hover:text-white/60"
                tabindex="-1"
              >
                <svg v-if="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="mt-1 text-xs text-red-300">{{ errors.password }}</p>
          </div>

          <label class="flex cursor-pointer items-center gap-2.5">
            <div class="relative">
              <input type="checkbox" v-model="form.remember" class="peer sr-only" />
              <div class="h-5 w-5 rounded-md border border-white/20 bg-white/5 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-500"></div>
              <svg class="absolute left-0.5 top-0.5 h-4 w-4 text-white opacity-0 transition-opacity peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <span class="text-sm text-white/60">Ingat saya</span>
          </label>

          <button
            type="submit"
            :disabled="submitting"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 transition-all hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
          >
            <svg v-if="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ submitting ? 'Sedang log masuk...' : 'Log Masuk' }}
          </button>
        </form>
      </div>
      <p class="mt-6 text-center text-xs text-white/20">&copy; {{ new Date().getFullYear() }} {{ siteName }}. Hak cipta terpelihara.</p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { usePublicSettingStore } from '../../stores/publicSettings'

const router = useRouter()
const auth = useAuthStore()
const publicSettings = usePublicSettingStore()

const siteName = computed(() => publicSettings.settings.site_name || 'INFAQABIM')

const form = reactive({ email: '', password: '', remember: false })
const errors = reactive({ email: '', password: '', general: '' })
const submitting = ref(false)
const showPassword = ref(false)

async function login() {
  errors.email = ''
  errors.password = ''
  errors.general = ''

  if (!form.email) { errors.email = 'E-mel diperlukan'; return }
  if (!form.password) { errors.password = 'Kata laluan diperlukan'; return }

  submitting.value = true
  try {
    await auth.login(form.email, form.password, form.remember)
    router.push('/admin/dashboard')
  } catch (e) {
    if (e.response?.status === 401) {
      errors.general = 'E-mel atau kata laluan tidak sah.'
    } else if (e.response?.status === 419) {
      // CSRF token mismatch — retry after refreshing the cookie
      errors.general = 'Sesi telah tamat. Sila cuba semula.'
    } else if (e.response?.status === 429) {
      errors.general = 'Terlalu banyak percubaan log masuk. Sila cuba semula sebentar lagi.'
    } else if (e.response?.data?.errors) {
      const errs = e.response.data.errors
      if (errs.email) errors.email = errs.email[0]
      if (errs.password) errors.password = errs.password[0]
    } else if (e.response?.data?.message) {
      errors.general = e.response.data.message
    } else if (!e.response) {
      errors.general = 'Gagal menyambung ke pelayan. Sila periksa sambungan internet anda.'
    } else {
      console.error('[Login] unhandled error:', e.response?.status, e.response?.data)
      errors.general = 'Log masuk gagal. Sila cuba semula.'
    }
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  publicSettings.fetch()
})
</script>
