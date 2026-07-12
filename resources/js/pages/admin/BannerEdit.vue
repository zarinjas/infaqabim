<template>
  <div>
    <PageHeader
      title="Edit Banner"
      subtitle="Kemaskini butiran banner."
      :breadcrumb="[{ label: 'Banner', to: '/admin/banners' }, { label: 'Edit' }]"
    >
      <template #actions>
        <Button tag="router-link" to="/admin/banners" variant="outline">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali
        </Button>
      </template>
    </PageHeader>

    <div v-if="loading" class="mt-6 text-sm text-gray-400">Memuatkan...</div>

    <div v-else class="mt-6 max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
      <form class="space-y-4" @submit.prevent="handleSubmit">
        <FormInput v-model="form.title" label="Tajuk" placeholder="Tajuk banner" :error="errors.title" />
        <FormInput v-model="form.subtitle" label="Subtajuk" placeholder="Subtajuk banner" :error="errors.subtitle" />

        <div>
          <label class="block text-sm font-medium text-gray-700">Imej Banner</label>
          <div v-if="imagePreview" class="mt-1.5 overflow-hidden rounded-xl ring-1 ring-gray-200">
            <img :src="imagePreview" alt="Preview" class="aspect-[16/9] w-full object-cover" />
          </div>
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            @change="handleFileChange"
          />
          <p class="mt-1 text-xs text-gray-400">Kosongkan untuk kekalkan imej sedia ada. JPG, PNG atau WEBP, maksimum 4MB. Cadangan: Saiz 1920px H x 1080px W.</p>
          <p v-if="errors.image" class="mt-1 text-xs text-red-500">{{ errors.image }}</p>
        </div>

        <FormInput v-model="form.link" label="Pautan (pilihan)" placeholder="https://example.com" :error="errors.link" />
        <FormInput v-model="form.sort_order" label="Susunan" type="number" placeholder="0" :error="errors.sort_order" />
        <FormSelect v-model="form.is_active" label="Status" :options="statusOptions" />
        <div class="flex items-center gap-3 pt-2">
          <Button type="submit" :loading="submitting">
            {{ submitting ? 'Menyimpan...' : 'Kemaskini Banner' }}
          </Button>
          <router-link to="/admin/banners" class="text-sm text-gray-500 hover:text-gray-700">Batal</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useBannerStore } from '../../stores/banners'
import FormInput from '../../components/FormInput.vue'
import FormSelect from '../../components/FormSelect.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const route = useRoute()
const store = useBannerStore()
const toast = useToast()

const form = reactive({
  title: '',
  subtitle: '',
  link: '',
  sort_order: '0',
  is_active: '1',
})

const errors = reactive({})
const submitting = ref(false)
const loading = ref(true)
const imageFile = ref(null)
const imagePreview = ref('')

const statusOptions = [
  { value: '1', label: 'Aktif' },
  { value: '0', label: 'Tidak Aktif' },
]

function handleFileChange(e) {
  const file = e.target.files[0]
  if (!file) return
  imageFile.value = file
  imagePreview.value = URL.createObjectURL(file)
}

onMounted(async () => {
  try {
    const { data } = await axios.get(`/admin/banners/${route.params.id}`)
    form.title = data.title
    form.subtitle = data.subtitle || ''
    form.link = data.link || ''
    form.sort_order = String(data.sort_order ?? 0)
    form.is_active = data.is_active ? '1' : '0'
    imagePreview.value = data.image ? `/storage/${data.image}` : ''
  } catch {
    router.push('/admin/banners')
  } finally {
    loading.value = false
  }
})

async function handleSubmit() {
  submitting.value = true
  Object.keys(errors).forEach(k => delete errors[k])

  try {
    const formData = new FormData()
    formData.append('title', form.title)
    if (form.subtitle) formData.append('subtitle', form.subtitle)
    if (imageFile.value) formData.append('image', imageFile.value)
    if (form.link) formData.append('link', form.link)
    formData.append('sort_order', form.sort_order)
    formData.append('is_active', form.is_active === '1' ? '1' : '0')

    await store.update(route.params.id, formData)
    toast.success('Banner berjaya dikemaskini.')
    router.push('/admin/banners')
  } catch (e) {
    if (e.response?.data?.errors) {
      Object.assign(errors, e.response.data.errors)
    }
    toast.error('Gagal mengemaskini banner.')
  } finally {
    submitting.value = false
  }
}
</script>
