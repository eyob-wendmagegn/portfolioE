<template>
  <div class="min-h-screen bg-slate-950 light:bg-slate-100 text-slate-100 light:text-slate-900 flex flex-col md:flex-row transition-colors">
    
    <!-- Mobile Sidebar Backdrop -->
    <div 
      v-if="sidebarOpen" 
      class="fixed inset-0 z-40 bg-black/70 md:hidden"
      @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 light:bg-white border-r border-slate-800 light:border-slate-200 flex flex-col justify-between transition-transform duration-300 md:translate-x-0 md:static',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <div>
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-800 light:border-slate-200 flex items-center justify-between">
          <router-link to="/admin/dashboard" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-blue-500/20">
              CMS
            </div>
            <div>
              <span class="font-extrabold text-sm tracking-tight text-white light:text-slate-900 block leading-tight">
                Eyob.dev
              </span>
              <span class="text-[10px] text-blue-400 font-mono">Portfolio CMS</span>
            </div>
          </router-link>

          <button 
            @click="sidebarOpen = false" 
            class="md:hidden text-slate-400 hover:text-white"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1">
          <router-link 
            v-for="link in navLinks" 
            :key="link.name"
            :to="link.to"
            @click="sidebarOpen = false"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all',
              $route.name === link.name
                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                : 'text-slate-400 light:text-slate-600 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100'
            ]"
          >
            <component :is="link.icon" class="w-4 h-4 shrink-0" />
            <span>{{ link.label }}</span>
            <span 
              v-if="link.badge && unreadCount > 0" 
              class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white"
            >
              {{ unreadCount }}
            </span>
          </router-link>
        </nav>
      </div>

      <!-- User Profile & Logout Bottom -->
      <div class="p-4 border-t border-slate-800 light:border-slate-200 space-y-2">
        <router-link 
          to="/admin/profile"
          class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-800/60 light:hover:bg-slate-100 transition-colors"
        >
          <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
            EW
          </div>
          <div class="overflow-hidden">
            <p class="text-xs font-bold text-white light:text-slate-900 truncate">
              {{ authStore.user?.name || 'Eyob W.' }}
            </p>
            <p class="text-[10px] text-slate-400 truncate">
              {{ authStore.user?.email || 'eyobnigata18@gmail.com' }}
            </p>
          </div>
        </router-link>

        <button 
          @click="handleLogout"
          class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>Sign Out</span>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      
      <!-- Top Navigation Header -->
      <header class="h-16 px-4 sm:px-8 border-b border-slate-800 light:border-slate-200 bg-slate-900/60 light:bg-white/80 backdrop-blur-md flex items-center justify-between sticky top-0 z-30">
        
        <!-- Left: Mobile Menu Button & Title -->
        <div class="flex items-center gap-3">
          <button 
            @click="sidebarOpen = true" 
            class="md:hidden p-2 rounded-xl text-slate-400 hover:text-white"
            aria-label="Open sidebar"
          >
            <Menu class="w-5 h-5" />
          </button>

          <span class="text-xs sm:text-sm font-semibold text-slate-300 light:text-slate-700">
            {{ $route.meta.title || 'Admin Control Panel' }}
          </span>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
          <!-- View Live Website -->
          <router-link 
            to="/" 
            target="_blank"
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-800 light:bg-slate-100 text-slate-200 light:text-slate-800 hover:bg-slate-700 transition-colors border border-slate-700 light:border-slate-300"
          >
            <ExternalLink class="w-3.5 h-3.5 text-blue-400" />
            <span>View Public Site</span>
          </router-link>

          <!-- Theme Toggle -->
          <button 
            @click="themeStore.toggleTheme"
            class="p-2 rounded-xl text-slate-400 hover:text-white light:hover:text-black hover:bg-slate-800/60 light:hover:bg-slate-100 transition-colors"
            :title="themeStore.isDark ? 'Light Theme' : 'Dark Theme'"
          >
            <Sun v-if="themeStore.isDark" class="w-4 h-4 text-amber-400" />
            <Moon v-else class="w-4 h-4 text-indigo-600" />
          </button>
        </div>
      </header>

      <!-- Page Outlet -->
      <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
        <router-view />
      </main>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import api from '@/services/api'
import { 
  LayoutDashboard, FolderGit2, BookOpen, Cpu, Briefcase, 
  GraduationCap, Mail, FileEdit, Settings, User, LogOut, 
  Menu, X, ExternalLink, Sun, Moon 
} from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()
const themeStore = useThemeStore()

const sidebarOpen = ref(false)
const unreadCount = ref(0)

const navLinks = [
  { name: 'admin-dashboard', to: '/admin/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { name: 'admin-projects', to: '/admin/projects', label: 'Projects', icon: FolderGit2 },
  { name: 'admin-library-dashboards', to: '/admin/library-dashboards', label: 'LMS Dashboards', icon: BookOpen },
  { name: 'admin-skills', to: '/admin/skills', label: 'Skills & Stack', icon: Cpu },
  { name: 'admin-services', to: '/admin/services', label: 'Services', icon: Briefcase },
  { name: 'admin-education', to: '/admin/education', label: 'Education', icon: GraduationCap },
  { name: 'admin-messages', to: '/admin/messages', label: 'Messages', icon: Mail, badge: true },
  { name: 'admin-content', to: '/admin/content', label: 'Portfolio Content', icon: FileEdit },
  { name: 'admin-settings', to: '/admin/settings', label: 'Site Settings & CV', icon: Settings },
  { name: 'admin-profile', to: '/admin/profile', label: 'Admin Profile', icon: User },
]

async function fetchStats() {
  try {
    const res = await api.get('/admin/dashboard/stats')
    unreadCount.value = res.data.stats?.unread_messages || 0
  } catch (e) {
    //
  }
}

async function handleLogout() {
  await authStore.logout()
  router.push('/admin/login')
}

onMounted(() => {
  fetchStats()
})
</script>
