<template>
  <div>
    <HeroBanner
      title="Make a Donation"
      subtitle="Salurkan sumbangan anda untuk membantu mereka yang memerlukan."
    />

    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-gray-50 p-6 shadow-sm ring-1 ring-gray-100 sm:p-8">
          <h2 class="text-xl font-bold text-gray-900">Donation Form</h2>
          <p class="mt-1 text-sm text-gray-500">Isi butiran di bawah untuk meneruskan sumbangan.</p>

          <form class="mt-6 space-y-5" @submit.prevent="submit">
            <div>
              <label class="block text-sm font-medium text-gray-700">Select Campaign</label>
              <select
                v-model="form.campaign_id"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              >
                <option value="" disabled>Choose a campaign</option>
                <option v-for="c in campaigns" :key="c.value" :value="c.value">{{ c.label }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Full Name</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="Your full name"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Email Address</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="your@email.com"
                class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Amount (RM)</label>
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
                min="1"
                placeholder="Or enter custom amount"
                class="mt-2 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Message (Optional)</label>
              <textarea
                v-model="form.message"
                rows="3"
                placeholder="Leave a message of support..."
                class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
              />
            </div>

            <button
              type="submit"
              class="w-full rounded-xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-emerald-700 active:bg-emerald-800"
            >
              Submit Donation
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import HeroBanner from '../components/HeroBanner.vue'

const campaigns = [
  { value: 1, label: 'Bantuan Pendidikan Anak Yatim' },
  { value: 2, label: 'Klinik Percuma Komuniti' },
  { value: 3, label: 'Bantuan Asnaf Ramadhan' },
  { value: 4, label: 'Pusat Tahfiz Al-Quran' },
  { value: 5, label: 'Bantuan Bencana Alam' },
  { value: 6, label: 'Program Makanan Percuma' },
]

const presetAmounts = [10, 25, 50, 100]

const form = reactive({
  campaign_id: '',
  name: '',
  email: '',
  amount: null,
  message: '',
})

function submit() {
  // TODO: POST to /api/donations
  alert('Donation submitted! (integration pending)')
}
</script>
