<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-700 px-4">
    <div class="w-full max-w-sm">
      <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-white">INFAQABIM</h1>
        <p class="mt-1 text-sm text-emerald-200">Admin Panel</p>
      </div>
      <div class="rounded-2xl bg-white p-6 shadow-xl sm:p-8">
        <h2 class="text-lg font-semibold text-gray-900">Sign In</h2>
        <p class="mt-1 text-sm text-gray-500">Masuk ke panel admin.</p>
        <form class="mt-6 space-y-4" @submit.prevent="login">
          <FormInput v-model="form.email" label="Email" type="email" placeholder="admin@infaqabim.my" :error="errors.email" />
          <FormInput v-model="form.password" label="Password" type="password" placeholder="Enter password" :error="errors.password" />
          <p v-if="errors.general" class="text-sm text-red-500">{{ errors.general }}</p>
          <button
            type="submit"
            :disabled="submitting"
            class="w-full rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700 active:bg-emerald-800 disabled:opacity-50"
          >
            {{ submitting ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import FormInput from '../../components/FormInput.vue'

const router = useRouter()
const auth = useAuthStore()

const form = reactive({ email: '', password: '' })
const errors = reactive({ email: '', password: '', general: '' })
const submitting = ref(false)

async function login() {
  errors.email = ''
  errors.password = ''
  errors.general = ''
  if (!form.email) errors.email = 'Email is required'
  if (!form.password) errors.password = 'Password is required'
  if (errors.email || errors.password) return

  submitting.value = true
  try {
    await auth.login(form.email, form.password)
    router.push('/admin/dashboard')
  } catch (e) {
    if (e.response?.status === 401) {
      errors.general = 'Invalid credentials'
    } else if (e.response?.data?.errors) {
      const errs = e.response.data.errors
      if (errs.email) errors.email = errs.email[0]
      if (errs.password) errors.password = errs.password[0]
    } else {
      errors.general = 'Login failed. Please try again.'
    }
  } finally {
    submitting.value = false
  }
}
</script>
