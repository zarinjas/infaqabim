<template>
  <div>
    <PageHeader
      title="Edit Kempen"
      subtitle="Kemaskini butiran kempen."
      :breadcrumb="[{ label: 'Kempen', to: '/admin/campaigns' }, { label: 'Sunting' }]"
    >
      <template #actions>
        <Button tag="router-link" to="/admin/campaigns" variant="outline">
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
        <FormInput v-model="form.title" label="Tajuk" placeholder="Tajuk kempen" :error="errors.title" />
        <FormTextarea v-model="form.description" label="Penerangan" placeholder="Penerangan kempen" :rows="4" :error="errors.description" />

        <div>
          <label class="block text-sm font-medium text-gray-700">Imej Utama Kempen (pilihan)</label>
          <div v-if="imagePreview" class="mt-1.5 overflow-hidden rounded-xl ring-1 ring-gray-200">
            <img :src="imagePreview" alt="Preview" class="w-full object-cover" />
          </div>
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            @change="handleFileChange"
          />
          <p class="mt-1 text-xs text-gray-400">Kosongkan untuk kekalkan imej sedia ada. JPG, PNG atau WEBP, maksimum 4MB. Cadangan: Saiz poster Instagram (1350px H x 1080px W / nisbah 4:5).</p>
          <p v-if="errors.image" class="mt-1 text-xs text-red-500">{{ errors.image }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Galeri Kempen</label>
          
          <div v-if="existingGalleries.length > 0" class="mt-2 mb-4">
              <p class="text-xs text-gray-500 mb-1">Galeri Sedia Ada (Klik pangkah untuk buang)</p>
              <div class="grid grid-cols-3 gap-2">
                  <div v-for="gallery in existingGalleries" :key="gallery.id" class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 aspect-square">
                      <img :src="'/storage/' + gallery.image_path" alt="Gallery Image" class="w-full h-full object-cover" />
                      <button type="button" @click="markGalleryForDeletion(gallery.id)" class="absolute top-1 right-1 rounded-full bg-red-500 p-1 text-white hover:bg-red-600">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                      </button>
                  </div>
              </div>
          </div>

          <div class="mt-1.5 grid grid-cols-3 gap-2">
            <div v-for="(preview, index) in galleryPreviews" :key="index" class="relative overflow-hidden rounded-xl ring-1 ring-gray-200 aspect-square">
              <img :src="preview" alt="New Gallery Preview" class="w-full h-full object-cover" />
              <button type="button" @click="removeNewGalleryImage(index)" class="absolute top-1 right-1 rounded-full bg-red-500 p-1 text-white hover:bg-red-600">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
            </div>
          </div>
          <input
            type="file"
            multiple
            accept="image/jpeg,image/png,image/webp"
            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            @change="handleGalleryChange"
          />
          <p class="mt-1 text-xs text-gray-400">Pilih berbilang gambar serentak. JPG, PNG atau WEBP.</p>
        </div>

        <FormInput v-model="form.target_amount" label="Jumlah Sasaran (RM)" type="number" placeholder="10000" :error="errors.target_amount" />
        <FormSelect v-model="form.type" label="Jenis" :options="typeOptions" :error="errors.type" />
        <FormSelect v-model="form.is_active" label="Status" :options="statusOptions" />
        <div class="flex items-center gap-3 pt-2">
          <Button type="submit" :loading="submitting">
            {{ submitting ? 'Menyimpan...' : 'Kemaskini Kempen' }}
          </Button>
          <router-link to="/admin/campaigns" class="text-sm text-gray-500 hover:text-gray-700">Batal</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useCampaignStore } from '../../stores/campaigns'
import FormInput from '../../components/FormInput.vue'
import FormSelect from '../../components/FormSelect.vue'
import FormTextarea from '../../components/FormTextarea.vue'
import PageHeader from '../../components/PageHeader.vue'
import Button from '../../components/Button.vue'
import { useToast } from '../../composables/useToast'

const router = useRouter()
const route = useRoute()
const store = useCampaignStore()
const toast = useToast()

const form = reactive({
  title: '',
  description: '',
  target_amount: '',
  type: 'progress',
  is_active: '1',
})

const errors = reactive({})
const submitting = ref(false)
const loading = ref(true)
const imageFile = ref(null)
const imagePreview = ref('')
const existingGalleries = ref([])
const galleriesToDelete = ref([])
const galleryFiles = ref([])
const galleryPreviews = ref([])

const typeOptions = [
  { value: 'progress', label: 'Kemajuan' },
  { value: 'one_off', label: 'Sekali' },
]

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

function handleGalleryChange(e) {
  const files = Array.from(e.target.files)
  files.forEach(file => {
    galleryFiles.value.push(file)
    galleryPreviews.value.push(URL.createObjectURL(file))
  })
}

function removeNewGalleryImage(index) {
  galleryFiles.value.splice(index, 1)
  galleryPreviews.value.splice(index, 1)
}

function markGalleryForDeletion(id) {
    galleriesToDelete.value.push(id);
    existingGalleries.value = existingGalleries.value.filter(g => g.id !== id);
}

onMounted(async () => {
  try {
    const campaign = await store.fetchOne(route.params.id)
    form.title = campaign.title
    form.description = campaign.description
    form.target_amount = campaign.target_amount
    form.type = campaign.type
    form.is_active = campaign.is_active ? '1' : '0'
    imagePreview.value = campaign.image ? `/storage/${campaign.image}` : ''
    existingGalleries.value = campaign.galleries || []
  } catch {
    router.push('/admin/campaigns')
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
    formData.append('description', form.description)
    formData.append('target_amount', parseFloat(form.target_amount))
    formData.append('type', form.type)
    formData.append('is_active', form.is_active === '1' ? '1' : '0')
    if (imageFile.value) formData.append('image', imageFile.value)

    galleryFiles.value.forEach((file, index) => {
        formData.append(`gallery[${index}]`, file)
    })

    galleriesToDelete.value.forEach((id, index) => {
        formData.append(`delete_gallery[${index}]`, id)
    })

    await store.update(route.params.id, formData)
    toast.success('Kempen berjaya dikemaskini.')
    router.push('/admin/campaigns')
  } catch (e) {
    if (e.response?.data?.errors) {
      Object.assign(errors, e.response.data.errors)
    }
    toast.error('Gagal mengemaskini kempen.')
  } finally {
    submitting.value = false
  }
}
</script>
