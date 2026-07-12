<template>
  <div>
    <PageHeader title="Tetapan" subtitle="Uruskan tetapan platform anda.">
      <template #actions>
        <Button :loading="store.saving" @click="handleSave">
          {{ store.saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </Button>
      </template>
    </PageHeader>

    <div v-if="saved" class="mt-4 flex items-center gap-2.5 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      <Icons.check class="h-4 w-4" />
      Tetapan berjaya disimpan.
    </div>

    <div class="mt-6 space-y-6">
      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100">
            <Icons.settings class="h-4 w-4 text-emerald-600" />
          </div>
          <h3 class="text-sm font-semibold text-gray-900">Umum</h3>
        </div>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.site_name" label="Nama Laman" placeholder="INFAQABIM" />
          <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700">Logo Laman</label>
            <input type="file" @change="handleLogoChange" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml" class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100" />
            <p class="mt-1 text-xs text-gray-500">Cadangan: Saiz 500x500px, format PNG/SVG dengan latar belakang lutsinar.</p>
            <div v-if="previewLogo || store.settings.app_logo" class="mt-2">
                <img :src="previewLogo || '/storage/' + store.settings.app_logo" alt="Logo Preview" class="h-20 w-auto rounded border p-1" />
            </div>
          </div>
          <FormInput v-model="form.site_email" label="E-mel Hubungan" type="email" placeholder="hello@infaqabim.my" />
          <FormInput v-model="form.site_phone" label="Telefon Hubungan" placeholder="+60 12-345 6789" />
          <FormTextarea v-model="form.site_description" label="Penerangan Laman" placeholder="Penerangan platform..." :rows="3" />
          <FormTextarea v-model="form.home_about_text" label="Teks Halaman Utama (Tentang)" placeholder="Teks untuk bahagian Tentang di halaman utama..." :rows="4" />
          <FormTextarea v-model="form.about_mission_text" label="Teks Halaman About (Misi)" placeholder="Teks untuk bahagian Misi di halaman About..." :rows="4" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100">
            <Icons.menu class="h-4 w-4 text-blue-600" />
          </div>
          <h3 class="text-sm font-semibold text-gray-900">Label Navigasi</h3>
        </div>
        <p class="mt-1 pl-12 text-xs text-gray-400">4 item navbar kekal (Home, Campaigns, Donate, About) - hanya label boleh ditukar.</p>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <FormInput v-model="form.nav_label_1" label="Item 1 (/)" placeholder="Home" />
          <FormInput v-model="form.nav_label_2" label="Item 2 (/campaigns)" placeholder="Campaigns" />
          <FormInput v-model="form.nav_label_3" label="Item 3 (/donate)" placeholder="Donate" />
          <FormInput v-model="form.nav_label_4" label="Item 4 (/about)" placeholder="About" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-100">
            <Icons.donation class="h-4 w-4 text-purple-600" />
          </div>
          <h3 class="text-sm font-semibold text-gray-900">Sumbangan</h3>
        </div>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.min_donation" label="Sumbangan Minimum (RM)" type="number" placeholder="1" />
          <FormInput v-model="form.max_donation" label="Sumbangan Maksimum (RM)" type="number" placeholder="100000" />
          <FormSelect v-model="form.currency" label="Mata Wang" :options="[{value:'MYR',label:'Ringgit Malaysia (MYR)'},{value:'USD',label:'US Dollar (USD)'}]" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100">
              <Icons.bank class="h-4 w-4 text-amber-600" />
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Gateway Pembayaran</h3>
          </div>
          <span v-if="store.settings.senangpay_secret_key_configured" class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
            SenangPay Dikonfigurasi
          </span>
          <span v-else class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
            SenangPay Belum Dikonfigurasi
          </span>
        </div>
        <p class="mt-1 pl-12 text-xs text-gray-400">Kredential disimpan secara encrypted. Kosongkan field secret key untuk kekalkan nilai sedia ada.</p>
        <div class="mt-4 space-y-4">
          <FormSelect
            v-model="form.payment_method"
            label="Kaedah Pembayaran"
            :options="[
              { value: 'manual', label: 'Pindahan Bank Manual (muat naik resit)' },
              { value: 'senangpay', label: 'SenangPay Sahaja' },
              { value: 'both', label: 'Kedua-dua - donor pilih sendiri' },
            ]"
          />
          <FormInput v-model="form.senangpay_merchant_id" label="SenangPay Merchant ID" placeholder="cth. 190100000000" />
          <FormInput
            v-model="form.senangpay_secret_key"
            label="SenangPay Secret Key"
            type="password"
            autocomplete="off"
            :placeholder="store.settings.senangpay_secret_key_masked || 'Masukkan secret key'"
          />
          <label class="flex cursor-pointer items-center gap-2.5">
            <input type="checkbox" v-model="form.senangpay_sandbox_mode" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" />
            <span class="text-sm text-gray-600">Mod Sandbox / Ujian</span>
          </label>
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100">
            <Icons.shield class="h-4 w-4 text-sky-600" />
          </div>
          <h3 class="text-sm font-semibold text-gray-900">Maklumat Hubungan</h3>
        </div>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.contact_address" label="Alamat" placeholder="123 Jalan Contoh" />
          <FormInput v-model="form.contact_city" label="Bandar" placeholder="Kuala Lumpur" />
          <FormInput v-model="form.contact_state" label="Negeri" placeholder="Wilayah Persekutuan" />
          <FormInput v-model="form.contact_postcode" label="Poskod" placeholder="50000" />
          <FormInput v-model="form.contact_country" label="Negara" placeholder="Malaysia" />
          <FormInput v-model="form.contact_hours" label="Waktu Operasi" placeholder="Isnin-Jumaat 9AM-5PM" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-100">
            <Icons.heart class="h-4 w-4 text-rose-600" />
          </div>
          <h3 class="text-sm font-semibold text-gray-900">Pautan Sosial</h3>
        </div>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.social_facebook" label="URL Facebook" placeholder="https://facebook.com/infaqabim" />
          <FormInput v-model="form.social_twitter" label="URL Twitter / X" placeholder="https://twitter.com/infaqabim" />
          <FormInput v-model="form.social_instagram" label="URL Instagram" placeholder="https://instagram.com/infaqabim" />
          <FormInput v-model="form.social_youtube" label="URL YouTube" placeholder="https://youtube.com/@infaqabim" />
          <FormInput v-model="form.social_telegram" label="URL Telegram" placeholder="https://t.me/infaqabim" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, watch } from 'vue'
import { useSettingStore } from '../../stores/settings'
import FormInput from '../../components/FormInput.vue'
import FormSelect from '../../components/FormSelect.vue'
import FormTextarea from '../../components/FormTextarea.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import { Icons } from '../../components/icons'
import { useToast } from '../../composables/useToast'

const store = useSettingStore()
const toast = useToast()
const saved = ref(false)
const previewLogo = ref(null)

const form = reactive({
  site_name: '',
  app_logo: null,
  site_email: '',
  site_phone: '',
  site_description: '',
  home_about_text: '',
  about_mission_text: '',
  nav_label_1: '',
  nav_label_2: '',
  nav_label_3: '',
  nav_label_4: '',
  min_donation: '',
  max_donation: '',
  currency: 'MYR',
  payment_method: 'manual',
  senangpay_merchant_id: '',
  senangpay_secret_key: '',
  senangpay_sandbox_mode: true,
  contact_address: '',
  contact_city: '',
  contact_state: '',
  contact_postcode: '',
  contact_country: '',
  contact_hours: '',
  social_facebook: '',
  social_twitter: '',
  social_instagram: '',
  social_youtube: '',
  social_telegram: '',
})

function handleLogoChange(event) {
  const file = event.target.files[0]
  if (file) {
    form.app_logo = file
    previewLogo.value = URL.createObjectURL(file)
  }
}

watch(() => store.settings, (val) => {
  if (val.site_name) form.site_name = val.site_name
  if (val.site_email) form.site_email = val.site_email
  if (val.site_phone) form.site_phone = val.site_phone
  if (val.site_description) form.site_description = val.site_description
  if (val.home_about_text) form.home_about_text = val.home_about_text
  if (val.about_mission_text) form.about_mission_text = val.about_mission_text
  if (val.nav_label_1) form.nav_label_1 = val.nav_label_1
  if (val.nav_label_2) form.nav_label_2 = val.nav_label_2
  if (val.nav_label_3) form.nav_label_3 = val.nav_label_3
  if (val.nav_label_4) form.nav_label_4 = val.nav_label_4
  if (val.min_donation) form.min_donation = val.min_donation
  if (val.max_donation) form.max_donation = val.max_donation
  if (val.currency) form.currency = val.currency
  if (val.payment_method) form.payment_method = val.payment_method
  if (val.senangpay_merchant_id) form.senangpay_merchant_id = val.senangpay_merchant_id
  if (val.senangpay_sandbox_mode !== undefined) form.senangpay_sandbox_mode = val.senangpay_sandbox_mode === '1' || val.senangpay_sandbox_mode === true
  if (val.contact_address) form.contact_address = val.contact_address
  if (val.contact_city) form.contact_city = val.contact_city
  if (val.contact_state) form.contact_state = val.contact_state
  if (val.contact_postcode) form.contact_postcode = val.contact_postcode
  if (val.contact_country) form.contact_country = val.contact_country
  if (val.contact_hours) form.contact_hours = val.contact_hours
  if (val.social_facebook) form.social_facebook = val.social_facebook
  if (val.social_twitter) form.social_twitter = val.social_twitter
  if (val.social_instagram) form.social_instagram = val.social_instagram
  if (val.social_youtube) form.social_youtube = val.social_youtube
  if (val.social_telegram) form.social_telegram = val.social_telegram
}, { immediate: true })

async function handleSave() {
  saved.value = false
  try {
    const formData = new FormData()
    formData.append('site_name', form.site_name)
    if (form.app_logo) {
      formData.append('app_logo', form.app_logo)
    }
    formData.append('site_email', form.site_email)
    formData.append('site_phone', form.site_phone)
    formData.append('site_description', form.site_description)
    if (form.home_about_text) formData.append('home_about_text', form.home_about_text)
    if (form.about_mission_text) formData.append('about_mission_text', form.about_mission_text)
    if (form.nav_label_1) formData.append('nav_label_1', form.nav_label_1)
    if (form.nav_label_2) formData.append('nav_label_2', form.nav_label_2)
    if (form.nav_label_3) formData.append('nav_label_3', form.nav_label_3)
    if (form.nav_label_4) formData.append('nav_label_4', form.nav_label_4)
    if (form.min_donation) formData.append('min_donation', form.min_donation)
    if (form.max_donation) formData.append('max_donation', form.max_donation)
    formData.append('currency', form.currency)
    formData.append('payment_method', form.payment_method)
    if (form.senangpay_merchant_id) formData.append('senangpay_merchant_id', form.senangpay_merchant_id)
    if (form.senangpay_secret_key) formData.append('senangpay_secret_key', form.senangpay_secret_key)
    formData.append('senangpay_sandbox_mode', form.senangpay_sandbox_mode ? 1 : 0)
    if (form.contact_address) formData.append('contact_address', form.contact_address)
    if (form.contact_city) formData.append('contact_city', form.contact_city)
    if (form.contact_state) formData.append('contact_state', form.contact_state)
    if (form.contact_postcode) formData.append('contact_postcode', form.contact_postcode)
    if (form.contact_country) formData.append('contact_country', form.contact_country)
    if (form.contact_hours) formData.append('contact_hours', form.contact_hours)
    if (form.social_facebook) formData.append('social_facebook', form.social_facebook)
    if (form.social_twitter) formData.append('social_twitter', form.social_twitter)
    if (form.social_instagram) formData.append('social_instagram', form.social_instagram)
    if (form.social_youtube) formData.append('social_youtube', form.social_youtube)
    if (form.social_telegram) formData.append('social_telegram', form.social_telegram)

    await store.update(formData)
    
    form.senangpay_secret_key = ''
    saved.value = true
    toast.success('Tetapan berjaya disimpan.')
    setTimeout(() => { saved.value = false }, 3000)
  } catch (error) {
    console.error(error);
    toast.error('Gagal menyimpan tetapan.')
  }
}

onMounted(() => {
  store.fetchAll()
})
</script>
