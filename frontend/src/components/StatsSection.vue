<template>
  <section class="py-12 border-y border-slate-800/80 light:border-slate-200/80 bg-slate-900/40 light:bg-slate-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Professional Pillars Row -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div 
          v-for="pillar in pillars" 
          :key="pillar.title"
          class="p-4 rounded-2xl glass-panel text-center hover:border-blue-500/30 transition-all hover:-translate-y-1"
        >
          <div class="w-10 h-10 mx-auto rounded-xl bg-blue-500/10 text-blue-400 light:text-blue-600 flex items-center justify-center mb-2.5">
            <component :is="pillar.icon" class="w-5 h-5" />
          </div>
          <h4 class="text-xs font-bold text-white light:text-slate-900 leading-tight">{{ pillar.title }}</h4>
          <p class="text-[11px] text-slate-400 light:text-slate-600 mt-1">{{ pillar.subtitle }}</p>
        </div>
      </div>

      <!-- Live Dynamic Metrics Row -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-4 border-t border-slate-800/50 light:border-slate-200/50">
        <div class="text-center">
          <p class="text-3xl sm:text-4xl font-extrabold text-white light:text-slate-900 tracking-tight">
            {{ portfolioStore.stats.total_projects || '0' }}+
          </p>
          <p class="text-xs sm:text-sm font-medium text-slate-400 light:text-slate-600 mt-1 uppercase tracking-wider">
            Projects Built
          </p>
        </div>

        <div class="text-center">
          <p class="text-3xl sm:text-4xl font-extrabold text-blue-400 light:text-blue-600 tracking-tight">
            {{ portfolioStore.stats.technologies_count || '15' }}+
          </p>
          <p class="text-xs sm:text-sm font-medium text-slate-400 light:text-slate-600 mt-1 uppercase tracking-wider">
            Technologies
          </p>
        </div>

        <div class="text-center">
          <p class="text-3xl sm:text-4xl font-extrabold text-indigo-400 light:text-indigo-600 tracking-tight">
            {{ portfolioStore.stats.total_skills || '15' }}+
          </p>
          <p class="text-xs sm:text-sm font-medium text-slate-400 light:text-slate-600 mt-1 uppercase tracking-wider">
            Core Skills
          </p>
        </div>

        <div class="text-center">
          <p class="text-3xl sm:text-4xl font-extrabold text-emerald-400 light:text-emerald-600 tracking-tight">
            100%
          </p>
          <p class="text-xs sm:text-sm font-medium text-slate-400 light:text-slate-600 mt-1 uppercase tracking-wider">
            Clean Architecture
          </p>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { usePortfolioStore } from '@/stores/portfolio'
import { 
  Server, Layout, Webhook, Database, Smartphone, Flame 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const pillars = [
  { title: 'Full-Stack Dev', subtitle: 'Laravel & Vue.js', icon: Server },
  { title: 'RESTful APIs', subtitle: 'Sanctum & JSON', icon: Webhook },
  { title: 'Database Design', subtitle: 'PostgreSQL & MySQL', icon: Database },
  { title: 'Responsive UI', subtitle: 'Tailwind CSS', icon: Smartphone },
  { title: 'Component Arch', subtitle: 'Vue 3 & Pinia', icon: Layout },
  { title: 'Continuous Growth', subtitle: 'Clean Code', icon: Flame },
]
</script>
