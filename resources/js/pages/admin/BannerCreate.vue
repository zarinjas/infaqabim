<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Banner</h1>
        <p class="mt-1 text-sm text-gray-500">Add a new homepage banner.</p>
      </div>
      <router-link to="/admin/banners" class="text-sm text-emerald-600 hover:text-emerald-700">
        &larr; Back
      </router-link>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
      <form class="space-y-4" @submit.prevent="handleSubmit">
        <FormInput v-model="form.title" label="Title" placeholder="Banner title" :error="errors.title" />
        <FormInput v-model="form.subtitle" label="Subtitle" placeholder="Banner subtitle" :error="errors.subtitle" />
        <FormInput v-model="form.image" label="Image URL" placeholder="https://example.com/image.jpg" :error="errors.image" />
        <FormInput v-model="form.link" label="Link (optional)" placeholder="https://example.com" :error="errors.link" />
        <FormInput v-model="form.sort_order" label="Sort Order" type="number" placeholder="0" :error="errors.sort_order" />
        <FormSelect v-model="form.is_active" label="Status" :options="statusOptions" />
        <div class="flex items-center gap-3 pt-2">
          <button
            type="submit"
            :disabled="submitting"
            class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-50"
          >
            {{ submitting ? 'Saving...' : 'Save Banner' }}
          </button>
          <router-link to="/admin/banners" class="text-sm text-gray-500 hover:text-gray-700">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useBannerStore } from '../../stores/banners'
import FormInput from '../../components/FormInput.vue'
import FormSelect from '../../components/FormSelect.vue'

const router = useRouter()
const store = useBannerStore()

const form = reactive({
  title: '',
  subtitle: '',
  image: '',
  link: '',
  sort_order: '0',
  is_active: '1',
})

const errors = reactive({})
const submitting = ref(false)

const statusOptions = [
  { value: '1', label: 'Active' },
  { value: '0', label: 'Inactive' },
]

async function handleSubmit() {
  submitting.value = true
  Object.keys(errors).forEach(k => delete errors[k])

  try {
    await store.create({
      title: form.title,
      subtitle: form.subtitle || null,
      image: form.image,
      link: form.link || null,
      sort_order: parseInt(form.sort_order, 10),
      is_active: form.is_active === '1',
    })
    router.push('/admin/banners')
  } catch (e) {
    if (e.response?.data?.errors) {
      Object.assign(errors, e.response.data.errors)
    }
  } finally {
    submitting.value = false
  }
}
</script>
