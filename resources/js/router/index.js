import { createRouter, createWebHistory } from 'vue-router'
import PublicLayout from '../layouts/PublicLayout.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('../pages/HomePage.vue'),
      },
      {
        path: 'campaigns',
        name: 'campaigns',
        component: () => import('../pages/CampaignPage.vue'),
      },
      {
        path: 'campaigns/:id',
        name: 'campaign.detail',
        component: () => import('../pages/CampaignDetail.vue'),
      },
      {
        path: 'donate',
        name: 'donate',
        component: () => import('../pages/DonatePage.vue'),
      },
      {
        path: 'about',
        name: 'about',
        component: () => import('../pages/AboutPage.vue'),
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    redirect: { name: 'admin.login' },
    children: [
      {
        path: 'login',
        name: 'admin.login',
        meta: { guest: true },
        component: () => import('../pages/admin/AdminLoginPage.vue'),
      },
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        meta: { auth: true },
        component: () => import('../pages/admin/AdminDashboardPage.vue'),
      },
      {
        path: 'banners',
        name: 'admin.banners',
        meta: { auth: true },
        component: () => import('../pages/admin/BannerIndex.vue'),
      },
      {
        path: 'banners/create',
        name: 'admin.banners.create',
        meta: { auth: true },
        component: () => import('../pages/admin/BannerCreate.vue'),
      },
      {
        path: 'banners/:id/edit',
        name: 'admin.banners.edit',
        meta: { auth: true },
        component: () => import('../pages/admin/BannerEdit.vue'),
      },
      {
        path: 'campaigns',
        name: 'admin.campaigns',
        meta: { auth: true },
        component: () => import('../pages/admin/CampaignIndex.vue'),
      },
      {
        path: 'campaigns/create',
        name: 'admin.campaigns.create',
        meta: { auth: true },
        component: () => import('../pages/admin/CampaignCreate.vue'),
      },
      {
        path: 'campaigns/:id/edit',
        name: 'admin.campaigns.edit',
        meta: { auth: true },
        component: () => import('../pages/admin/CampaignEdit.vue'),
      },
      {
        path: 'donations',
        name: 'admin.donations',
        meta: { auth: true },
        component: () => import('../pages/admin/DonationIndex.vue'),
      },
      {
        path: 'donations/:id',
        name: 'admin.donations.detail',
        meta: { auth: true },
        component: () => import('../pages/admin/DonationDetail.vue'),
      },
      {
        path: 'donors',
        name: 'admin.donors',
        meta: { auth: true },
        component: () => import('../pages/admin/DonorIndex.vue'),
      },
      {
        path: 'settings',
        name: 'admin.settings',
        meta: { auth: true },
        component: () => import('../pages/admin/SettingsPage.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.auth && !auth.admin) {
    await auth.check()
    if (!auth.admin) {
      next({ name: 'admin.login' })
      return
    }
  }

  if (to.meta.guest && auth.admin) {
    next({ name: 'admin.dashboard' })
    return
  }

  next()
})

export default router
