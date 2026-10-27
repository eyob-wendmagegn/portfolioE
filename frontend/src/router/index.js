import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import HomeView from '@/views/HomeView.vue'
import ProjectDetailView from '@/views/ProjectDetailView.vue'
import NotFoundView from '@/views/NotFoundView.vue'

// Admin Views
import AdminLoginView from '@/views/admin/LoginView.vue'
import AdminLayout from '@/views/admin/AdminLayout.vue'
import AdminDashboardView from '@/views/admin/DashboardView.vue'
import AdminProjectsView from '@/views/admin/ProjectsView.vue'
import AdminLibraryDashboardsView from '@/views/admin/LibraryDashboardsView.vue'
import AdminSkillsView from '@/views/admin/SkillsView.vue'
import AdminServicesView from '@/views/admin/ServicesView.vue'
import AdminEducationView from '@/views/admin/EducationView.vue'
import AdminMessagesView from '@/views/admin/MessagesView.vue'
import AdminContentView from '@/views/admin/ContentView.vue'
import AdminSettingsView from '@/views/admin/SettingsView.vue'
import AdminProfileView from '@/views/admin/ProfileView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { title: 'Eyob.dev — Full-Stack Developer Portfolio' },
  },
  {
    path: '/projects/:slug',
    name: 'project-detail',
    component: ProjectDetailView,
    meta: { title: 'Project Details — Eyob.dev' },
  },

  // Admin Authentication
  {
    path: '/admin/login',
    name: 'admin-login',
    component: AdminLoginView,
    meta: { guestOnly: true, title: 'Admin Login — Eyob.dev' },
  },

  // Protected Admin Dashboard
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: { name: 'admin-dashboard' },
      },
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: AdminDashboardView,
        meta: { title: 'Admin Overview — Eyob.dev' },
      },
      {
        path: 'projects',
        name: 'admin-projects',
        component: AdminProjectsView,
        meta: { title: 'Manage Projects — Eyob.dev' },
      },
      {
        path: 'library-dashboards',
        name: 'admin-library-dashboards',
        component: AdminLibraryDashboardsView,
        meta: { title: 'Library Dashboard Screenshots — Eyob.dev' },
      },
      {
        path: 'skills',
        name: 'admin-skills',
        component: AdminSkillsView,
        meta: { title: 'Manage Skills — Eyob.dev' },
      },
      {
        path: 'services',
        name: 'admin-services',
        component: AdminServicesView,
        meta: { title: 'Manage Services — Eyob.dev' },
      },
      {
        path: 'education',
        name: 'admin-education',
        component: AdminEducationView,
        meta: { title: 'Manage Education — Eyob.dev' },
      },
      {
        path: 'messages',
        name: 'admin-messages',
        component: AdminMessagesView,
        meta: { title: 'Contact Messages — Eyob.dev' },
      },
      {
        path: 'content',
        name: 'admin-content',
        component: AdminContentView,
        meta: { title: 'Portfolio Content Editor — Eyob.dev' },
      },
      {
        path: 'settings',
        name: 'admin-settings',
        component: AdminSettingsView,
        meta: { title: 'Site Settings & CV — Eyob.dev' },
      },
      {
        path: 'profile',
        name: 'admin-profile',
        component: AdminProfileView,
        meta: { title: 'Admin Profile — Eyob.dev' },
      },
    ],
  },

  // 404 Catch-All
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFoundView,
    meta: { title: 'Page Not Found — Eyob.dev' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }
    return { top: 0, behavior: 'smooth' }
  },
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.title) {
    document.title = to.meta.title
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      next({ name: 'admin-login', query: { redirect: to.fullPath } })
      return
    }
  }

  if (to.matched.some((record) => record.meta.guestOnly)) {
    if (authStore.isAuthenticated) {
      next({ name: 'admin-dashboard' })
      return
    }
  }

  next()
})

export default router
