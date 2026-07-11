# Development Plan — INFAQABIM

> **Goal:** Ship a working donation landing page (Laravel + Vue SPA) ASAP.  
> **Principle:** KISS — every task is 15–30 min, no over-engineering.  
> **Status:** `[ ]` pending `[x]` done `[v]` verified

---

### 1. Database Migrations
`[v]` Create migrations: `admins`, `banners`, `campaigns`, `donations`, `settings`. Remove `users` migration.

### 2. Eloquent Models
`[v]` Create models: `Admin` (Authenticatable), `Banner`, `Campaign`, `Donation`, `Setting`. Remove `User` model.

### 3. Seeders
`[v]` Create `AdminSeeder` (admin@infaqabim.my / password), `SettingSeeder`. Wire into `DatabaseSeeder`. Run `db:seed`.

### 4. Admin Auth (Backend)
`[v]` Configure `admins` guard in `auth.php`. Create `AdminAuthController` (login, logout, me), `AdminMiddleware`, login form request. Define `/admin/*` routes.

### 5. Admin CRUD Controllers
`[v]` Create controllers: `BannerController`, `CampaignController`, `DonationController`, `SettingController` (empty CRUD methods).
`[v]` Create `AdminDashboardController`, `AdminDonorController`. Register resource routes.
`[v]` Implement `CampaignController` CRUD (index, store, show, update, destroy).
`[v]` Implement `BannerController` CRUD (index, store, show, update, destroy).
`[v]` Implement `DonationController` (index, show, approve, reject, destroy).
`[v]` Implement `SettingController` (index, update).

### 6. Public Controllers & Routes
`[v]` Create `PublicHomeController` (returns banners, featured campaign, active campaigns). Define `/api/home` route.

### 7. Vue Router + Axios
`[v]` Install `vue-router`. Create `router/index.js` (public + admin routes). Configure Axios base URL + XSRF interceptor. Wire into `app.js`.

### 8. Vue — Public Layout & Shared Components
`[v]` Create `PublicLayout.vue` (responsive header with hamburger, footer).
`[v]` Create `CampaignCard.vue` (card with progress bar, placeholder data).
`[v]` Create `HeroBanner.vue`, `ProgressBar.vue`, `Badge.vue`.

### 9. Vue — Public Pages
`[v]` Create `HomePage.vue` (hero banner, featured campaign, campaign list, about sections, responsive layout).
`[v]` Create `CampaignPage.vue` (listing with search + sort).
`[v]` Create `DonatePage.vue` (full donation form with campaign select, preset amounts).
`[v]` Create `CampaignDetail.vue` (full detail with progress, stats, donate CTA).
`[v]` Create `AboutPage.vue` (mission, values, impact stats).

### 10. Vue — Admin Layout & Shared Components
`[v]` Create `AdminLayout.vue` (sidebar + topbar with mobile hamburger).
`[v]` Create `AdminSidebar.vue` (nav links with icons, logout).
`[v]` Create `DataTable.vue`, `FormInput.vue`, `FormSelect.vue`, `FormTextarea.vue`.

### 11. Vue — Admin Pages (V1 Complete)
`[v]` **Module 1: Banner CRUD** — BannerIndex (List), BannerCreate, BannerEdit, delete. Store: fetchAll, create, update, remove. ✅
`[v]` **Module 2: Campaign CRUD** — CampaignIndex (List), CampaignCreate, CampaignEdit, delete. Store: fetchAll, fetchOne, create, update, remove. ✅
`[v]` **Module 3: Donation Management** — DonationIndex (List with approve/reject buttons), DonationDetail (View with approve/reject). Store: fetchAll, approve, reject, remove. ✅
`[v]` **Module 4: Settings** — SettingsPage with General, Donation, Contact Information, Social Links sections. Store: fetchAll, update. ✅
`[v]` `AdminLoginPage.vue` (full login form with validation, API integration).
`[v]` `AdminDashboardPage.vue` (live stats from API, no hardcoded data).
`[v]` `DonorIndex.vue` — donor list from API.

### 12. Pinia Stores
`[v]` Create stores: `auth.js`, `banners.js`, `campaigns.js`, `donations.js`, `dashboard.js`, `settings.js`, `donors.js`.
`[v]` Convert `auth.js` to session-based auth (no tokens).

### 13. Connect Homepage to Database
`[v]` Create `PublicHomeController` endpoint that returns active banners, featured campaign (highest collected), and active campaigns.
`[v]` Add `/api/home` route (no auth middleware).
`[v]` Update `HomePage.vue`: remove hardcoded data, fetch from `/api/home` on mount, map snake_case DB columns to camelCase for `CampaignCard` props.
`[v]` Display empty state when no campaigns exist.
`[v]` Create `ContentSeeder` with sample banners and campaigns. Wire into `DatabaseSeeder`. Run seed.

### 14. Public Campaign & Donation Controllers
`[v]` Create `PublicCampaignController` (list active campaigns, single campaign detail).
`[v]` Create `PublicDonationController` (store donation).
`[v]` Define public web routes (GET `/api/campaigns`, GET `/api/campaigns/{id}`, POST `/api/donations`).

### 15. File Upload & Storage
`[v]` Verify `public` disk config. Run `storage:link`. Handle receipt upload + validation in donation controller. Serve files via public URL.

### 16. Testing — Admin
`[v]` Write feature tests: admin login (success/failure), banner CRUD, campaign CRUD.

### 17. Testing — Donations
`[v]` Write feature tests: donation submission (valid/invalid), donation approval/rejection flow.

### 18. Polish & Cleanup
`[v]` Remove welcome view. Add branded 404/500 error pages. Responsive audit (mobile/tablet/desktop). Update `README.md` with dev setup.

---

**Notes:**
- Laravel 13 (scaffolded) — no downgrade needed.
- Prohibited: Filament, Nova, Backpack, Livewire, Inertia, Docker, Sail, Spatie Permission.
- Vue Router is a standard frontend dependency, not a prohibited backend package.
- `admins` table replaces default `users`.
- Admin auth uses Laravel session-based auth (no tokens, no Sanctum).
- SPA catch-all route serves welcome view for all GET routes not matched by API.
