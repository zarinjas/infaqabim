<template>
  <div>
    <div v-if="loading" class="flex min-h-[60vh] items-center justify-center">
      <svg class="h-6 w-6 animate-spin text-emerald-500" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
      </svg>
    </div>

    <div v-else-if="!campaign" class="flex min-h-[60vh] flex-col items-center justify-center px-4 text-center">
      <p class="text-lg font-semibold text-gray-900">Kempen tidak ditemui</p>
      <p class="mt-1 text-sm text-gray-500">Kempen ini mungkin telah tamat atau tidak wujud.</p>
      <router-link to="/campaigns" class="mt-4 text-sm font-medium text-emerald-600 hover:text-emerald-700">
        &larr; Kembali ke senarai kempen
      </router-link>
    </div>

    <template v-else>
      <section class="bg-gray-50 py-8 sm:py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="flex aspect-[4/5] items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-100 to-teal-100">
              <img v-if="campaign.image" :src="`/storage/${campaign.image}`" :alt="campaign.title" class="h-full w-full object-cover" />
              <span v-else class="text-sm text-gray-400">{{ campaign.title }}</span>
            </div>
            <div class="p-6 sm:p-10">
              <Badge :variant="campaign.type === 'one_off' ? 'info' : 'primary'">
                {{ campaign.type === 'one_off' ? 'Sumbangan Sekali' : 'Kempen Berterusan' }}
              </Badge>
              <h1 class="mt-4 text-2xl font-bold leading-tight text-gray-900 sm:text-3xl">{{ campaign.title }}</h1>
              
              <div class="mt-8">
                <ProgressBar :collected="collected" :target="target" />
              </div>

              <div class="mt-8 grid grid-cols-3 gap-2 border-t border-gray-100 pt-6">
                <div class="text-center">
                  <div class="text-lg font-bold text-emerald-700">{{ percentage }}%</div>
                  <div class="mt-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Terkumpul</div>
                </div>
                <div class="text-center border-x border-gray-100">
                  <div class="text-lg font-bold text-emerald-700">{{ campaign.donations_count ?? 0 }}</div>
                  <div class="mt-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Penyumbang</div>
                </div>
                <div class="text-center">
                  <div class="text-lg font-bold text-emerald-700">RM{{ target.toLocaleString() }}</div>
                  <div class="mt-1 text-xs font-medium text-gray-500 uppercase tracking-wide">Sasaran</div>
                </div>
              </div>

              <router-link
                :to="`/donate?campaign=${campaign.id}`"
                class="mt-8 flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow-md active:scale-[0.98]"
              >
                Sumbang Sekarang
              </router-link>
              
              <div class="mt-10 border-t border-gray-100 pt-8">
                  <h2 class="text-lg font-semibold text-gray-900 mb-4">Penerangan Kempen</h2>
                  <p class="whitespace-pre-line text-sm leading-relaxed text-gray-600 sm:text-base">
                    {{ campaign.description }}
                  </p>
              </div>
              
              <!-- Social Share Buttons -->
              <div class="mt-8 border-t border-gray-100 pt-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-4 text-center">Kongsi Kebaikan</p>
                <div class="flex justify-center gap-4">
                  <a :href="shareLinks.whatsapp" target="_blank" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#25D366] text-white hover:bg-[#128C7E] transition-colors" title="Kongsi ke WhatsApp">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  </a>
                  <a :href="shareLinks.facebook" target="_blank" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1877F2] text-white hover:bg-[#166FE5] transition-colors" title="Kongsi ke Facebook">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                  </a>
                  <a :href="shareLinks.twitter" target="_blank" class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition-colors" title="Kongsi ke X">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 22.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  </a>
                  <button @click="copyLink" class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" title="Salin Pautan">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                  </button>
                </div>
              </div>

            </div>
          </div>
          
          <div v-if="campaign.galleries && campaign.galleries.length > 0" class="mt-8 rounded-3xl bg-white p-6 sm:p-10 shadow-sm ring-1 ring-gray-100">
             <h2 class="text-lg font-semibold text-gray-900 mb-6">Galeri Kempen</h2>
             <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
               <div v-for="gallery in campaign.galleries" :key="gallery.id" class="aspect-square rounded-2xl overflow-hidden bg-gray-100 ring-1 ring-black/5">
                  <img :src="`/storage/${gallery.image_path}`" alt="Gallery image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
               </div>
             </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Badge from '../components/Badge.vue'
import ProgressBar from '../components/ProgressBar.vue'
import { useToast } from '../composables/useToast'

const route = useRoute()
const campaign = ref(null)
const loading = ref(true)
const toast = useToast()

const collected = computed(() => Number(campaign.value?.collected_amount ?? 0))
const target = computed(() => Number(campaign.value?.target_amount ?? 1))
const percentage = computed(() => Math.min(100, Math.round((collected.value / target.value) * 100)))

const shareUrl = computed(() => window.location.href)
const shareText = computed(() => encodeURIComponent(`Sumbang ke kempen: ${campaign.value?.title}`))

const shareLinks = computed(() => ({
  whatsapp: `https://api.whatsapp.com/send?text=${shareText.value}%20${encodeURIComponent(shareUrl.value)}`,
  facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl.value)}`,
  twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl.value)}&text=${shareText.value}`
}))

function copyLink() {
  navigator.clipboard.writeText(shareUrl.value)
    .then(() => toast.success('Pautan disalin!'))
    .catch(() => toast.error('Gagal menyalin pautan.'))
}

async function fetchCampaign(id) {
  loading.value = true
  try {
    const { data } = await axios.get(`/api/campaigns/${id}`)
    campaign.value = data
  } catch {
    campaign.value = null
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchCampaign(route.params.id))
watch(() => route.params.id, (id) => fetchCampaign(id))
</script>
