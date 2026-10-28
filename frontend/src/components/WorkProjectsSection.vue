<template>
  <section id="work" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-12">
       
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          All Projects & Work
        </h2>
        <p class="text-sm sm:text-base text-slate-400 light:text-slate-600">
          Filter by architecture stack or search by key capability to discover how each project was designed and built.
        </p>
      </div>

      <!-- Filters & Search Bar Container -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10">
        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
          <button 
            @click="selectedCategory = 'all'" 
            :class="[
              'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all',
              selectedCategory === 'all'
                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                : 'glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:border-slate-700'
            ]"
          >
            All Works ({{ portfolioStore.allProjects.length }})
          </button>

          <button 
            v-for="cat in portfolioStore.categories" 
            :key="cat.id"
            @click="selectedCategory = cat.slug"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all',
              selectedCategory === cat.slug
                ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
                : 'glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:border-slate-700'
            ]"
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Search Input -->
        <div class="w-full md:w-72 relative">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search projects or tech..." 
            class="w-full pl-10 pr-4 py-2 rounded-xl text-xs bg-slate-900/80 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 placeholder:text-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
          />
          <button 
            v-if="searchQuery" 
            @click="searchQuery = ''"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white"
          >
            <X class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div 
        v-if="filteredProjects.length === 0" 
        class="text-center py-20 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80"
      >
        <FolderSearch class="w-12 h-12 text-slate-600 mx-auto mb-3" />
        <h4 class="text-base font-bold text-white light:text-slate-900">No matching projects found</h4>
        <p class="text-xs text-slate-400 mt-1">Try adjusting your category filter or search query.</p>
        <button 
          @click="resetFilters" 
          class="mt-4 px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600/30 transition-colors"
        >
          Reset Filters
        </button>
      </div>

      <!-- Projects Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="project in filteredProjects" 
          :key="project.id"
          class="group rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 overflow-hidden hover:border-blue-500/40 transition-all duration-300 shadow-xl flex flex-col justify-between hover:-translate-y-1"
        >
          <div>
            <!-- Cover image -->
            <div class="relative aspect-video bg-slate-900 light:bg-slate-100 overflow-hidden border-b border-slate-800/80 light:border-slate-200/80">
              <img 
                v-if="project.cover_image_url" 
                :src="project.cover_image_url" 
                :alt="project.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-900 to-slate-950 p-6 text-center">
                <Code class="w-12 h-12 text-slate-700 light:text-slate-400 group-hover:text-blue-400 transition-colors" />
              </div>

              <!-- Category Badge -->
              <div v-if="project.category" class="absolute top-4 left-4">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-950/80 light:bg-white/90 text-blue-400 light:text-blue-600 border border-white/10 backdrop-blur-md">
                  {{ project.category.name }}
                </span>
              </div>

              <!-- Links -->
              <div class="absolute top-4 right-4 flex items-center gap-1.5">
                <a 
                  v-if="project.github_url" 
                  :href="formatExternalUrl(project.github_url)" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="p-1.5 rounded-full bg-slate-950/80 light:bg-white/90 text-slate-300 light:text-slate-700 hover:text-white border border-white/10 transition-colors"
                  title="GitHub Repository"
                  aria-label="GitHub Repository"
                >
                  <Github class="w-3.5 h-3.5" />
                </a>
                <a 
                  v-if="project.live_url" 
                  :href="formatExternalUrl(project.live_url)" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="p-1.5 rounded-full bg-slate-950/80 light:bg-white/90 text-slate-300 light:text-slate-700 hover:text-white border border-white/10 transition-colors"
                  title="Live Demo"
                  aria-label="Live Demo"
                >
                  <ExternalLink class="w-3.5 h-3.5" />
                </a>
              </div>
            </div>

            <!-- Content Area -->
            <div class="p-6">
              <!-- Technologies Tags -->
              <div class="flex flex-wrap gap-1.5 mb-2.5">
                <span 
                  v-for="tech in project.technologies" 
                  :key="tech.id"
                  class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-800/80 light:bg-slate-200/80 text-slate-300 light:text-slate-800"
                >
                  {{ tech.name }}
                </span>
              </div>

              <h3 class="text-lg font-bold text-white light:text-slate-900 group-hover:text-blue-400 transition-colors mb-2">
                {{ project.title }}
              </h3>

              <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 line-clamp-2 leading-relaxed">
                {{ project.short_description || project.description }}
              </p>
            </div>
          </div>

          <!-- Bottom Action Bar -->
          <div class="p-6 pt-0 border-t border-slate-800/40 light:border-slate-200/40 flex items-center justify-between">
            <button 
              @click="$emit('select-project', project)"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-400 light:text-blue-600 hover:text-blue-300 transition-colors"
            >
              <span>View Overview</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </button>

            <span v-if="project.project_date" class="text-[11px] text-slate-500 font-mono">
              {{ project.project_date }}
            </span>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { formatExternalUrl } from '@/utils/url'
import { 
  FolderGit2, Search, X, FolderSearch, Code, Github, 
  ExternalLink, ArrowRight 
} from 'lucide-vue-next'

defineEmits(['select-project'])

const portfolioStore = usePortfolioStore()

const selectedCategory = ref('all')
const searchQuery = ref('')

const filteredProjects = computed(() => {
  return portfolioStore.allProjects.filter((project) => {
    // Category match
    const categoryMatch = 
      selectedCategory.value === 'all' || 
      (project.category && project.category.slug === selectedCategory.value)

    // Search query match
    if (!searchQuery.value.trim()) {
      return categoryMatch
    }

    const q = searchQuery.value.toLowerCase()
    const titleMatch = project.title && project.title.toLowerCase().includes(q)
    const descMatch = project.short_description && project.short_description.toLowerCase().includes(q)
    const techMatch = project.technologies && project.technologies.some(t => t.name.toLowerCase().includes(q))

    return categoryMatch && (titleMatch || descMatch || techMatch)
  })
})

function resetFilters() {
  selectedCategory.value = 'all'
  searchQuery.value = ''
}
</script>
