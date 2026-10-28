<template>
  <div class="relative bg-slate-950 light:bg-slate-50 min-h-screen text-slate-100 light:text-slate-900">
    <div class="fixed inset-0 bg-grid-pattern pointer-events-none opacity-40 z-0"></div>
    <Navbar />

    <main class="relative z-10 pt-32 pb-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Back Button -->
      <router-link 
        to="/#work" 
        class="inline-flex items-center gap-2 text-xs font-semibold text-blue-400 light:text-blue-600 hover:underline mb-8"
      >
        <ArrowLeft class="w-4 h-4" />
        <span>Back to All Projects</span>
      </router-link>

      <div v-if="loading" class="py-24 text-center">
        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-xs text-slate-400 font-mono">Loading project details...</p>
      </div>

      <div v-else-if="project" class="space-y-8">
        <!-- Title & Category -->
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <span v-if="project.category" class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
              {{ project.category.name }}
            </span>
            <span v-if="project.project_date" class="text-xs text-slate-500 font-mono">
              {{ project.project_date }}
            </span>
          </div>

          <h1 class="text-3xl sm:text-5xl font-extrabold text-white light:text-slate-900 tracking-tight">
            {{ project.title }}
          </h1>

          <p class="text-base text-slate-400 light:text-slate-600 leading-relaxed max-w-3xl">
            {{ project.short_description }}
          </p>
        </div>

        <!-- Cover Image -->
        <div v-if="project.cover_image_url" class="rounded-3xl overflow-hidden border border-slate-800 light:border-slate-200 aspect-video bg-slate-950 shadow-2xl">
          <img :src="project.cover_image_url" :alt="project.title" class="w-full h-full object-cover" />
        </div>

        <!-- Tech Stack -->
        <div>
          <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Technology Stack</h3>
          <div class="flex flex-wrap gap-2">
            <span 
              v-for="tech in project.technologies" 
              :key="tech.id"
              class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-500/10 text-blue-300 light:text-blue-700 border border-blue-500/20"
            >
              {{ tech.name }}
            </span>
          </div>
        </div>

        <!-- Detailed Description -->
        <div class="p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-xl space-y-4">
          <h3 class="text-lg font-bold text-white light:text-slate-900">System Architecture & Overview</h3>
          <div class="prose prose-invert light:prose-slate max-w-none text-sm text-slate-300 light:text-slate-700 leading-relaxed whitespace-pre-line">
            {{ project.description }}
          </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-wrap gap-4 pt-4">
          <a 
            v-if="project.live_url" 
            :href="formatExternalUrl(project.live_url)" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/20 transition-all"
          >
            <ExternalLink class="w-4 h-4" />
            <span>Launch Live Application</span>
          </a>

          <a 
            v-if="project.github_url" 
            :href="formatExternalUrl(project.github_url)" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold glass-panel text-slate-200 light:text-slate-800 border border-slate-800 light:border-slate-300 hover:border-blue-500/50 transition-all"
          >
            <Github class="w-4 h-4" />
            <span>View Source on GitHub</span>
          </a>
        </div>
      </div>

      <div v-else class="text-center py-20 rounded-3xl glass-panel">
        <h3 class="text-lg font-bold text-white light:text-slate-900">Project Not Found</h3>
        <p class="text-xs text-slate-400 mt-2">The requested project could not be located.</p>
        <router-link to="/#work" class="mt-4 inline-block px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white">
          Back to Portfolio
        </router-link>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import { formatExternalUrl } from '@/utils/url'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import { ArrowLeft, ExternalLink, Github } from 'lucide-vue-next'

const route = useRoute()
const project = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const slug = route.params.slug
    const res = await api.get(`/projects/${slug}`)
    project.value = res.data.data
  } catch (err) {
    console.error('Failed fetching project', err)
  } finally {
    loading.value = false
  }
})
</script>
