<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Campaign</h1>
        <p class="mt-1 text-sm text-gray-500">Add a new donation campaign.</p>
      </div>
      <router-link to="/admin/campaigns" class="text-sm text-emerald-600 hover:text-emerald-700">
        &larr; Back
      </router-link>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
      <form class="space-y-4" @submit.prevent="handleSubmit">
        <FormInput v-model="form.title" label="Title" placeholder="Campaign title" :error="errors.title" />
        <FormTextarea v-model="form.description" label="Description" placeholder="Campaign description" :rows="4" :error="errors.description" />
        <FormInput v-model="form.target_amount" label="Target Amount (RM)" type="number" placeholder="10000" :error="errors.target_amount" />
        <FormSelect v-model="form.type" label="Type" :options="typeOptions" :error="errors.type" />
        <FormSelect v-model="form.is_active" label="Status" :options="statusOptions" />
        <div class="flex items-center gap-3 pt-2">
          <button
            type="submit"
            :disabled="submitting"
            class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-50"
          >
            {{ submitting ? 'Saving...' : 'Save Campaign' }}
          </button>
          <router-link to="/admin/campaigns" class="text-sm text-gray-500 hover:text-gray-700">Cancel</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCampaignStore } from '../../stores/campaigns'
import FormInput from '../../components/FormInput.vue'
import FormSelect from '../../components/FormSelect.vue'
import FormTextarea from '../../components/FormTextarea.vue'

const router = useRouter()
const store = useCampaignStore()

const form = reactive({
  title: '',
  description: '',
  target_amount: '',
  type: 'progress',
  is_active: '1',
})

const errors = reactive({})
const submitting = ref(false)

const typeOptions = [
  { value: 'progress', label: 'Progress' },
  { value: 'one_off', label: 'One-Off' },
]

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
      description: form.description,
      target_amount: parseFloat(form.target_amount),
      type: form.type,
      is_active: form.is_active === '1',
    })
    router.push('/admin/campaigns')
  } catch (e) {
    if (e.response?.data?.errors) {
      Object.assign(errors, e.response.data.errors)
    }
  } finally {
    submitting.value = false
  }
}
</script>
