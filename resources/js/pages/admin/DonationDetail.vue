<template>
  <div>
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Donation #{{ donation.id }}</h1>
        <p class="mt-1 text-sm text-gray-500">Donation details.</p>
      </div>
      <router-link to="/admin/donations" class="text-sm text-emerald-600 hover:text-emerald-700">
        &larr; Back
      </router-link>
    </div>

    <div v-if="loading" class="mt-6 text-sm text-gray-400">Loading...</div>

    <div v-else class="mt-6 space-y-6">
      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Donor Information</h3>
        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <dt class="text-xs text-gray-400">Name</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_name }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Email</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_email }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Phone</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.donor_phone || '-' }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Reference Number</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.reference_number || '-' }}</dd>
          </div>
        </dl>
      </div>

      <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h3 class="text-sm font-semibold text-gray-900">Donation Details</h3>
        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <dt class="text-xs text-gray-400">Campaign</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.campaign?.title || '-' }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Amount</dt>
            <dd class="text-sm font-medium text-gray-900">RM{{ Number(donation.amount).toLocaleString() }}</dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Status</dt>
            <dd>
              <Badge :variant="donation.status === 'completed' ? 'success' : donation.status === 'pending' ? 'warning' : 'danger'">{{ donation.status }}</Badge>
            </dd>
          </div>
          <div>
            <dt class="text-xs text-gray-400">Date</dt>
            <dd class="text-sm font-medium text-gray-900">{{ donation.created_at }}</dd>
          </div>
        </dl>
        <div v-if="donation.status === 'pending'" class="mt-6 flex gap-3">
          <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700" @click="handleApprove">Approve</button>
          <button class="rounded-xl bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600" @click="handleReject">Reject</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useDonationStore } from '../../stores/donations'
import Badge from '../../components/Badge.vue'

const router = useRouter()
const route = useRoute()
const store = useDonationStore()

const donation = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/admin/donations/${route.params.id}`)
    donation.value = data
  } catch {
    router.push('/admin/donations')
  } finally {
    loading.value = false
  }
})

async function handleApprove() {
  if (!confirm('Approve this donation?')) return
  await store.approve(route.params.id)
  donation.value.status = 'completed'
}

async function handleReject() {
  if (!confirm('Reject this donation?')) return
  await store.reject(route.params.id)
  donation.value.status = 'failed'
}
</script>
