<template>
  <div>
    <HeroBanner
      title="Buat Sumbangan"
      :subtitle="`Salurkan sumbangan anda untuk ${campaign?.title || 'kempen ini'}.`"
    />

    <section class="bg-gray-50 py-16 sm:py-20">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
              <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900">Borang Sumbangan</h2>
              <p class="text-sm text-gray-500">Isi butiran di bawah untuk meneruskan sumbangan.</p>
            </div>
          </div>

          <div v-if="campaign" class="mt-6 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-100">
            <p class="text-xs text-emerald-600 font-medium">Kempen</p>
            <p class="text-base font-bold text-emerald-900">{{ campaign.title }}</p>
            <p class="mt-1 text-xs text-emerald-600">
              RM{{ Number(campaign.target_amount || 0).toLocaleString() }} &middot;
              {{ campaign.donations_count || 0 }} penyumbang
            </p>
          </div>

          <div v-if="errors.general" class="mt-4 flex items-start gap-2.5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
            <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ errors.general }}
          </div>

          <form class="mt-6 space-y-5" @submit.prevent="submit">
            <FormInput v-model="form.donor_name" label="Nama Penuh" placeholder="Nama penuh anda" :error="errors.donor_name" />

            <FormInput v-model="form.donor_email" label="Alamat E-mel" type="email" placeholder="anda@email.com" :error="errors.donor_email" />

            <FormInput v-model="form.donor_phone" label="Nombor Telefon" type="tel" placeholder="012-345 6789" :error="errors.donor_phone" />

            <div>
              <label class="block text-sm font-medium text-gray-700">Jumlah Sumbangan (RM)</label>
              <div class="mt-1.5 grid grid-cols-4 gap-2">
                <button
                  v-for="amount in presetAmounts"
                  :key="amount"
                  type="button"
                  class="rounded-xl border py-2.5 text-sm font-medium transition-colors"
                  :class="form.amount === amount ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                  @click="form.amount = amount"
                >
                  RM{{ amount }}
                </button>
              </div>
              <input
                v-model="form.amount"
                type="number"
                :min="minDonation"
                :max="maxDonation"
                placeholder="Atau masukkan jumlah sendiri"
                class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              />
              <p v-if="errors.amount" class="mt-1 text-xs text-red-500">{{ errors.amount }}</p>
            </div>

            <div v-if="availableMethods.length > 1">
              <label class="block text-sm font-medium text-gray-700">Kaedah Pembayaran</label>
              <div class="mt-1.5 grid grid-cols-2 gap-2">
                <button
                  v-for="method in availableMethods"
                  :key="method.value"
                  type="button"
                  class="rounded-xl border py-2.5 text-sm font-medium transition-colors"
                  :class="form.payment_method === method.value ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                  @click="form.payment_method = method.value"
                >
                  {{ method.label }}
                </button>
              </div>
            </div>

            <div v-if="form.payment_method === 'manual'">
              <label class="block text-sm font-medium text-gray-700">Muat Naik Resit / Bukti Pindahan</label>
              <input
                type="file"
                accept="image/jpeg,image/png,application/pdf"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                @change="handleFileChange"
              />
              <p class="mt-1 text-xs text-gray-400">JPG, PNG atau PDF. Maksimum 5MB.</p>
              <p v-if="errors.receipt_image" class="mt-1 text-xs text-red-500">{{ errors.receipt_image }}</p>
            </div>

            <button
              type="submit"
              :disabled="submitting"
              class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-emerald-700 active:bg-emerald-800 disabled:opacity-50"
            >
              <svg v-if="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              {{ submitting ? 'Sedang memproses...' : 'Hantar Sumbangan' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import HeroBanner from '../components/HeroBanner.vue'
import FormInput from '../components/FormInput.vue'

const route = useRoute()

const campaign = ref(null)
const submitting = ref(false)
const receiptFile = ref(null)
const publicSettings = ref({})

const presetAmounts = [10, 25, 50, 100]

const form = reactive({
  donor_name: '',
  donor_email: '',
  donor_phone: '',
  amount: null,
  payment_method: 'manual',
})

const errors = reactive({
  donor_name: '', donor_email: '', donor_phone: '', amount: '', receipt_image: '', general: '',
})

const minDonation = computed(() => Number(publicSettings.value.min_donation) || 1)
const maxDonation = computed(() => Number(publicSettings.value.max_donation) || 100000)

const availableMethods = computed(() => {
  const setting = publicSettings.value.payment_method || 'manual'
  if (setting === 'both') {
    return [
      { value: 'manual', label: 'Pindahan Bank' },
      { value: 'senangpay', label: 'SenangPay' },
    ]
  }
  return [{ value: setting, label: setting === 'senangpay' ? 'SenangPay' : 'Pindahan Bank' }]
})

function handleFileChange(e) {
  receiptFile.value = e.target.files[0] || null
}

function clearErrors() {
  Object.keys(errors).forEach((k) => { errors[k] = '' })
}

function validate() {
  clearErrors()
  let valid = true
  if (!form.donor_name) { errors.donor_name = 'Nama diperlukan'; valid = false }
  if (!form.donor_email) { errors.donor_email = 'E-mel diperlukan'; valid = false }
  if (!form.donor_phone) { errors.donor_phone = 'Nombor telefon diperlukan'; valid = false }
  if (!form.amount || Number(form.amount) < minDonation.value) {
    errors.amount = `Sumbangan minimum adalah RM${minDonation.value}`
    valid = false
  } else if (Number(form.amount) > maxDonation.value) {
    errors.amount = `Sumbangan maksimum adalah RM${maxDonation.value}`
    valid = false
  }
  if (form.payment_method === 'manual' && !receiptFile.value) {
    errors.receipt_image = 'Sila muat naik bukti pindahan anda'
    valid = false
  }
  return valid
}

async function submit() {
  if (!validate()) return

  submitting.value = true
  try {
    const payload = new FormData()
    payload.append('campaign_id', route.params.campaignId)
    payload.append('donor_name', form.donor_name)
    payload.append('donor_email', form.donor_email)
    payload.append('donor_phone', form.donor_phone)
    payload.append('amount', form.amount)
    payload.append('payment_method', form.payment_method)
    if (receiptFile.value) payload.append('receipt_image', receiptFile.value)

    const { data } = await axios.post('/api/donations', payload, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    if (data.redirect_url) {
      window.location.href = data.redirect_url
      return
    }

    alert(data.message || 'Sumbangan berjaya dihantar.')
    Object.assign(form, { donor_name: '', donor_email: '', donor_phone: '', amount: null })
    receiptFile.value = null
  } catch (e) {
    if (e.response?.data?.errors) {
      const errs = e.response.data.errors
      Object.keys(errs).forEach((key) => {
        if (key in errors) errors[key] = errs[key][0]
      })
    } else if (e.response?.data?.message) {
      errors.general = e.response.data.message
    } else {
      errors.general = 'Ralat tidak dijangka. Sila cuba semula.'
    }
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  try {
    const [{ data: camp }, { data: settings }] = await Promise.all([
      axios.get(`/api/campaigns/${route.params.campaignId}`),
      axios.get('/api/settings'),
    ])
    campaign.value = camp
    publicSettings.value = settings || {}
    form.payment_method = availableMethods.value[0]?.value || 'manual'
  } catch {
    campaign.value = null
  }
})
</script>