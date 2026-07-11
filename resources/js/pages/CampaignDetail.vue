<template>
  <div>
    <HeroBanner
      title="Campaign Detail"
      subtitle="Lihat maklumat lengkap kempen dan salurkan sumbangan anda."
    />

    <section class="bg-white py-16 sm:py-20">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-2xl bg-gray-50 shadow-sm ring-1 ring-gray-100">
          <div class="flex aspect-[16/9] items-center justify-center bg-gradient-to-br from-emerald-100 to-teal-100">
            <span class="text-sm text-gray-400">Campaign Image</span>
          </div>
          <div class="p-6 sm:p-8">
            <Badge variant="primary">Featured</Badge>
            <h1 class="mt-3 text-2xl font-bold text-gray-900 sm:text-3xl">Bantuan Banjir Kelantan</h1>
            <p class="mt-4 text-sm leading-relaxed text-gray-500 sm:text-base">
              Bantuan kecemasan untuk mangsa banjir di Kelantan. Kami menyediakan makanan, pakaian, dan keperluan asas kepada lebih 500 keluarga yang terjejas akibat banjir musim ini. Bantuan ini merangkumi keperluan harian, peralatan kebersihan, dan sokongan psikososial kepada mangsa-mangsa yang kehilangan tempat tinggal.
            </p>

            <div class="mt-8">
              <ProgressBar :collected="78000" :target="100000" />
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 border-t border-gray-200 pt-6 sm:grid-cols-4">
              <div class="text-center">
                <div class="text-lg font-bold text-emerald-700">78%</div>
                <div class="mt-0.5 text-xs text-gray-400">Funded</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-bold text-emerald-700">520</div>
                <div class="mt-0.5 text-xs text-gray-400">Donors</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-bold text-emerald-700">15</div>
                <div class="mt-0.5 text-xs text-gray-400">Days Left</div>
              </div>
              <div class="text-center">
                <div class="text-lg font-bold text-emerald-700">RM100K</div>
                <div class="mt-0.5 text-xs text-gray-400">Target</div>
              </div>
            </div>

            <router-link
              to="/donate"
              class="mt-8 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-medium text-white transition-colors hover:bg-emerald-700"
            >
              Donate to This Campaign
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import HeroBanner from '../components/HeroBanner.vue'
import Badge from '../components/Badge.vue'
import ProgressBar from '../components/ProgressBar.vue'
</script>
