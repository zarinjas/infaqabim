<template>
  <div>
    <PageHeader title="Dashboard" subtitle="Gambaran keseluruhan platform anda." />

    <div v-if="loading" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div v-for="i in 4" :key="i" class="h-24 animate-pulse rounded-2xl bg-gray-100" />
    </div>

    <template v-else>
      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard label="Jumlah Terkumpul" :value="`RM${Number(stats?.total_collected ?? 0).toLocaleString()}`" :icon="Icons.donation" color="emerald" />
        <StatCard label="Jumlah Kempen" :value="stats?.total_campaigns ?? 0" :icon="Icons.campaign" color="blue" />
        <StatCard label="Sumbangan Belum Selesai" :value="stats?.pending_donations ?? 0" :icon="Icons.clock" color="amber" />
        <StatCard label="Sumbangan Selesai" :value="stats?.completed_donations ?? 0" :icon="Icons.check" color="purple" />
      </div>

      <div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
          <h2 class="text-sm font-semibold text-gray-900">Sumbangan Terkini</h2>
          <router-link to="/admin/donations" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">
            Lihat semua &rarr;
          </router-link>
        </div>
        <EmptyState v-if="!stats?.recent_donations?.length" title="Belum ada sumbangan." />
        <ul v-else class="divide-y divide-gray-50">
          <li v-for="d in stats.recent_donations" :key="d.id" class="flex items-center justify-between gap-4 px-5 py-3.5">
            <div class="flex min-w-0 items-center gap-3">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-500">
                {{ d.donor_name?.charAt(0) || '?' }}
              </div>
              <div class="min-w-0">
                <p class="truncate text-sm font-medium text-gray-900">{{ d.donor_name }}</p>
                <p class="truncate text-xs text-gray-400">{{ d.campaign || 'Umum' }} &middot; {{ d.created_at }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-sm font-medium text-gray-900">RM{{ Number(d.amount).toLocaleString() }}</span>
              <Badge :variant="d.status === 'completed' ? 'success' : d.status === 'pending' ? 'warning' : 'danger'">{{ statusLabel(d.status) }}</Badge>
            </div>
          </li>
        </ul>
      </div>
    </template>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useDashboardStore } from '../../stores/dashboard'
import Badge from '../../components/Badge.vue'
import PageHeader from '../../components/PageHeader.vue'
import StatCard from '../../components/StatCard.vue'
import EmptyState from '../../components/EmptyState.vue'
import { Icons } from '../../components/icons'

const dash = useDashboardStore()

const loading = computed(() => dash.loading)
const stats = computed(() => dash.stats)

function statusLabel(status) {
  return { completed: 'Selesai', pending: 'Belum Selesai', failed: 'Gagal' }[status] || status
}

onMounted(() => {
  dash.fetchStats()
})
</script>
