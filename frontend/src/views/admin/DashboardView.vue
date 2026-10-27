<template>
  <div class="space-y-8 max-w-7xl mx-auto">
    
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900 tracking-tight">
          Welcome back, {{ authStore.user?.name || 'Eyob' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 mt-1">
          Here is what's happening with your portfolio website and inquiries.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <router-link 
          to="/admin/projects"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all"
        >
          <Plus class="w-3.5 h-3.5" />
          <span>New Project</span>
        </router-link>

        <router-link 
          to="/admin/library-dashboards"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 light:bg-white hover:bg-slate-700 text-slate-200 light:text-slate-800 border border-slate-700 light:border-slate-300 transition-all"
        >
          <BookOpen class="w-3.5 h-3.5 text-indigo-400" />
          <span>LMS Screenshots</span>
        </router-link>
      </div>
    </div>

    <!-- Live Statistics Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <!-- Total Projects -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Projects</p>
        <p class="text-2xl sm:text-3xl font-black text-white light:text-slate-900 mt-1">
          {{ stats.total_projects || 0 }}
        </p>
        <p class="text-[10px] text-blue-400 mt-1">{{ stats.published_projects || 0 }} Published</p>
      </div>

      <!-- Featured Projects -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Featured</p>
        <p class="text-2xl sm:text-3xl font-black text-amber-400 mt-1">
          {{ stats.featured_projects || 0 }}
        </p>
        <p class="text-[10px] text-slate-500 mt-1">Top priority</p>
      </div>

      <!-- Total Skills -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Skills & Tech</p>
        <p class="text-2xl sm:text-3xl font-black text-indigo-400 mt-1">
          {{ stats.total_skills || 0 }}
        </p>
        <p class="text-[10px] text-slate-500 mt-1">Grouped stack</p>
      </div>

      <!-- Unread Inquiries -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unread Messages</p>
        <p class="text-2xl sm:text-3xl font-black text-rose-400 mt-1">
          {{ stats.unread_messages || 0 }}
        </p>
        <p class="text-[10px] text-rose-400/80 mt-1">Needs attention</p>
      </div>

      <!-- LMS Screenshots -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">LMS Uploaded</p>
        <p class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1">
          {{ stats.uploaded_dashboards || 0 }} / 4
        </p>
        <p class="text-[10px] text-emerald-400/80 mt-1">Dashboards</p>
      </div>

      <!-- Active Services -->
      <div class="p-5 rounded-2xl glass-panel border border-slate-800 light:border-slate-200">
        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Services</p>
        <p class="text-2xl sm:text-3xl font-black text-cyan-400 mt-1">
          {{ stats.total_services || 0 }}
        </p>
        <p class="text-[10px] text-slate-500 mt-1">Active offerings</p>
      </div>
    </div>

    <!-- Split Section: Recent Messages & Recent Projects -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Recent Messages (7 cols) -->
      <div class="lg:col-span-7 p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 space-y-4 shadow-xl">
        <div class="flex items-center justify-between">
          <h2 class="text-base sm:text-lg font-bold text-white light:text-slate-900 flex items-center gap-2">
            <Mail class="w-5 h-5 text-blue-400" />
            <span>Recent Contact Messages</span>
          </h2>
          <router-link to="/admin/messages" class="text-xs font-semibold text-blue-400 hover:underline">
            View All
          </router-link>
        </div>

        <div v-if="recentMessages.length === 0" class="text-center py-10 text-slate-500 text-xs">
          No contact inquiries submitted yet.
        </div>

        <div v-else class="space-y-3">
          <div 
            v-for="msg in recentMessages" 
            :key="msg.id"
            class="p-4 rounded-2xl bg-slate-950/60 light:bg-slate-50 border border-slate-800/60 light:border-slate-200 flex items-start justify-between gap-4"
          >
            <div class="space-y-1 overflow-hidden">
              <div class="flex items-center gap-2">
                <span 
                  v-if="!msg.is_read" 
                  class="w-2 h-2 rounded-full bg-rose-500 shrink-0" 
                  title="Unread"
                ></span>
                <p class="text-xs font-bold text-white light:text-slate-900 truncate">
                  {{ msg.name }}
                </p>
                <span class="text-[10px] text-slate-500">&lt;{{ msg.email }}&gt;</span>
              </div>
              <p class="text-xs font-medium text-blue-400 truncate">{{ msg.subject }}</p>
              <p class="text-xs text-slate-400 line-clamp-1">{{ msg.message }}</p>
            </div>

            <router-link 
              to="/admin/messages"
              class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-800 light:bg-white text-slate-300 light:text-slate-700 hover:bg-slate-700 shrink-0"
            >
              Open
            </router-link>
          </div>
        </div>
      </div>

      <!-- Recent Projects (5 cols) -->
      <div class="lg:col-span-5 p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 space-y-4 shadow-xl">
        <div class="flex items-center justify-between">
          <h2 class="text-base sm:text-lg font-bold text-white light:text-slate-900 flex items-center gap-2">
            <FolderGit2 class="w-5 h-5 text-indigo-400" />
            <span>Recent Projects</span>
          </h2>
          <router-link to="/admin/projects" class="text-xs font-semibold text-blue-400 hover:underline">
            Manage
          </router-link>
        </div>

        <div v-if="recentProjects.length === 0" class="text-center py-10 text-slate-500 text-xs">
          No projects configured yet.
        </div>

        <div v-else class="space-y-3">
          <div 
            v-for="project in recentProjects" 
            :key="project.id"
            class="p-3.5 rounded-2xl bg-slate-950/60 light:bg-slate-50 border border-slate-800/60 light:border-slate-200 flex items-center justify-between gap-3"
          >
            <div class="overflow-hidden">
              <div class="flex items-center gap-2">
                <span 
                  :class="[
                    'w-2 h-2 rounded-full',
                    project.status === 'published' ? 'bg-emerald-400' : 'bg-slate-500'
                  ]"
                ></span>
                <p class="text-xs font-bold text-white light:text-slate-900 truncate">{{ project.title }}</p>
              </div>
              <p v-if="project.category" class="text-[10px] text-slate-400 mt-0.5">
                {{ project.category.name }}
              </p>
            </div>

            <router-link 
              to="/admin/projects"
              class="p-1.5 rounded-lg text-slate-400 hover:text-white"
            >
              <ArrowRight class="w-4 h-4" />
            </router-link>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import { 
  Plus, BookOpen, Mail, FolderGit2, ArrowRight 
} from 'lucide-vue-next'

const authStore = useAuthStore()

const stats = ref({})
const recentMessages = ref([])
const recentProjects = ref([])

async function loadDashboard() {
  try {
    const res = await api.get('/admin/dashboard/stats')
    stats.value = res.data.stats || {}
    recentMessages.value = res.data.recent_messages || []
    recentProjects.value = res.data.recent_projects || []
  } catch (err) {
    console.error('Failed loading stats', err)
  }
}

onMounted(() => {
  loadDashboard()
})
</script>
