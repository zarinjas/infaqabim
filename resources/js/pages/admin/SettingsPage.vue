<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Manage platform settings.</p>
      </div>
      <button
        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-50"
        :disabled="store.saving"
        @click="handleSave"
      >
        {{ store.saving ? 'Saving...' : 'Save Changes' }}
      </button>
    </div>

    <div class="mt-6 space-y-6">
      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">General</h3>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.site_name" label="Site Name" placeholder="INFAQABIM" />
          <FormInput v-model="form.site_email" label="Contact Email" type="email" placeholder="hello@infaqabim.my" />
          <FormInput v-model="form.site_phone" label="Contact Phone" placeholder="+60 12-345 6789" />
          <FormTextarea v-model="form.site_description" label="Site Description" placeholder="Platform description..." :rows="3" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Donation</h3>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.min_donation" label="Minimum Donation (RM)" type="number" placeholder="1" />
          <FormInput v-model="form.max_donation" label="Maximum Donation (RM)" type="number" placeholder="100000" />
          <FormSelect v-model="form.currency" label="Currency" :options="[{value:'MYR',label:'Malaysian Ringgit (MYR)'},{value:'USD',label:'US Dollar (USD)'}]" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Contact Information</h3>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.contact_address" label="Address" placeholder="123 Jalan Example" />
          <FormInput v-model="form.contact_city" label="City" placeholder="Kuala Lumpur" />
          <FormInput v-model="form.contact_state" label="State" placeholder="Wilayah Persekutuan" />
          <FormInput v-model="form.contact_postcode" label="Postcode" placeholder="50000" />
          <FormInput v-model="form.contact_country" label="Country" placeholder="Malaysia" />
          <FormInput v-model="form.contact_hours" label="Operating Hours" placeholder="Mon-Fri 9AM-5PM" />
        </div>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Social Links</h3>
        <div class="mt-4 space-y-4">
          <FormInput v-model="form.social_facebook" label="Facebook URL" placeholder="https://facebook.com/infaqabim" />
          <FormInput v-model="form.social_twitter" label="Twitter / X URL" placeholder="https://twitter.com/infaqabim" />
          <FormInput v-model="form.social_instagram" label="Instagram URL" placeholder="https://instagram.com/infaqabim" />
          <FormInput v-model="form.social_youtube" label="YouTube URL" placeholder="https://youtube.com/@infaqabim" />
          <FormInput v-model="form.social_telegram" label="Telegram URL" placeholder="https://t.me/infaqabim" />
        </div>
      </div>

      <div v-if="saved" class="rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-700">
        Settings saved successfully.
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

const store = useSettingStore()
const saved = ref(false)

const form = reactive({
  site_name: '',
  site_email: '',
  site_phone: '',
  site_description: '',
  min_donation: '',
  max_donation: '',
  currency: 'MYR',
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

watch(() => store.settings, (val) => {
  if (val.site_name) form.site_name = val.site_name
  if (val.site_email) form.site_email = val.site_email
  if (val.site_phone) form.site_phone = val.site_phone
  if (val.site_description) form.site_description = val.site_description
  if (val.min_donation) form.min_donation = val.min_donation
  if (val.max_donation) form.max_donation = val.max_donation
  if (val.currency) form.currency = val.currency
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
  await store.update({
    site_name: form.site_name,
    site_email: form.site_email,
    site_phone: form.site_phone,
    site_description: form.site_description,
    min_donation: form.min_donation ? parseFloat(form.min_donation) : null,
    max_donation: form.max_donation ? parseFloat(form.max_donation) : null,
    currency: form.currency,
    contact_address: form.contact_address || null,
    contact_city: form.contact_city || null,
    contact_state: form.contact_state || null,
    contact_postcode: form.contact_postcode || null,
    contact_country: form.contact_country || null,
    contact_hours: form.contact_hours || null,
    social_facebook: form.social_facebook || null,
    social_twitter: form.social_twitter || null,
    social_instagram: form.social_instagram || null,
    social_youtube: form.social_youtube || null,
    social_telegram: form.social_telegram || null,
  })
  saved.value = true
  setTimeout(() => { saved.value = false }, 3000)
}

onMounted(() => {
  store.fetchAll()
})
</script>
