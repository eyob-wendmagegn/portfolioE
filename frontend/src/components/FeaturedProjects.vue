<template>
  <section id="featured" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Title Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
        <div>
         
          <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white light:text-slate-900">
            Featured Projects
          </h2>
          <p class="text-sm sm:text-base text-slate-400 light:text-slate-600 mt-2 max-w-xl">
            Selected full-stack and backend systems demonstrating robust software architecture and interactive user interfaces.
          </p>
        </div>

        <a 
          href="#work" 
          class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-400 light:text-blue-600 hover:underline group"
        >
          <span>View All Projects</span>
          <ChevronRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
        </a>
      </div>

      <!-- Featured Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="project in featuredProjects" 
          :key="project.id"
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-blue-500/40 transition-all duration-300 shadow-xl flex flex-col justify-between hover:-translate-y-1.5"
        >
          <div>
            <!-- Cover Image Container -->
            <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
              <img 
                v-if="project.cover_image_url" 
                :src="project.cover_image_url" 
                :alt="project.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-900 to-indigo-950/40 p-6 text-center">
                <Terminal class="w-12 h-12 text-slate-600 light:text-slate-400 group-hover:text-blue-400 transition-colors" />
              </div>

              <!-- Category Badge -->
              <div v-if="project.category" class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-950/80 light:bg-white/90 text-blue-400 light:text-blue-600 border border-white/10 backdrop-blur-md">
                  {{ project.category.name }}
                </span>
              </div>

              <!-- Quick Links Overlay -->
              <div class="absolute top-4 right-4 flex items-center gap-2">
                <a 
                  v-if="project.github_url" 
                  :href="formatExternalUrl(project.github_url)" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="p-2 rounded-full bg-slate-950/80 light:bg-white/90 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black border border-white/10 hover:border-blue-500 transition-colors"
                  title="Source Code"
                  aria-label="View Source Code"
                >
                  <Github class="w-4 h-4" />
                </a>
                <a 
                  v-if="project.live_url" 
                  :href="formatExternalUrl(project.live_url)" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="p-2 rounded-full bg-slate-950/80 light:bg-white/90 text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black border border-white/10 hover:border-blue-500 transition-colors"
                  title="Live Demo"
                  aria-label="View Live Demo"
                >
                  <ExternalLink class="w-4 h-4" />
                </a>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6">
              <!-- Tech tags -->
              <div class="flex flex-wrap gap-1.5 mb-3">
                <span 
                  v-for="tech in project.technologies" 
                  :key="tech.id"
                  class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 light:text-blue-700 border border-blue-500/20"
                >
                  {{ tech.name }}
                </span>
              </div>

              <h3 class="text-xl font-bold text-white light:text-slate-900 group-hover:text-blue-400 transition-colors mb-2">
                {{ project.title }}
              </h3>

              <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 line-clamp-3 leading-relaxed">
                {{ project.short_description || project.description }}
              </p>
            </div>
          </div>

          <!-- Bottom Button Bar -->
          <div class="p-6 pt-0 border-t border-slate-800/40 light:border-slate-200/40 flex items-center justify-between">
            <button 
              @click="$emit('select-project', project)"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-400 light:text-blue-600 hover:text-blue-300 transition-colors"
            >
              <span>View Details</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </button>

            <span v-if="project.project_date" class="text-[11px] text-slate-500">
              {{ project.project_date }}
            </span>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { formatExternalUrl } from '@/utils/url'
import { 
  Star, ChevronRight, Terminal, Github, ExternalLink, ArrowRight 
} from 'lucide-vue-next'

defineEmits(['select-project'])

const portfolioStore = usePortfolioStore()

const featuredProjects = computed(() => {
  return portfolioStore.featuredProjects.length > 0 
    ? portfolioStore.featuredProjects 
    : portfolioStore.allProjects.filter(p => p.featured)
})
</script>
