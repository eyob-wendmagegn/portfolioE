<template>
  <section id="skills" class="py-24 relative bg-slate-900/30 light:bg-slate-50/50 border-y border-slate-800/80 light:border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 text-blue-400 light:text-blue-600 text-xs font-bold tracking-widest uppercase mb-3">
          <Cpu class="w-3.5 h-3.5" />
          <span>Technical Expertise</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          Skills & Technologies
        </h2>
        <p class="text-sm sm:text-base text-slate-400 light:text-slate-600">
          Core toolsets and frameworks I leverage to design, construct, and scale modern web applications.
        </p>
      </div>

      <!-- Categories Container -->
      <div class="space-y-12">
        <div 
          v-for="(skillList, categoryName) in portfolioStore.skillsByCategory" 
          :key="categoryName"
          class="space-y-4"
        >
          <!-- Category Header -->
          <div class="flex items-center gap-3">
            <h3 class="text-lg font-bold text-white light:text-slate-900 tracking-tight">
              {{ categoryName }}
            </h3>
            <span class="h-px flex-1 bg-slate-800 light:bg-slate-200"></span>
            <span class="text-xs font-mono text-slate-500">{{ skillList.length }} skills</span>
          </div>

          <!-- Skills Cards Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div 
              v-for="skill in skillList" 
              :key="skill.id"
              class="group p-5 rounded-2xl glass-panel border border-slate-800/80 light:border-slate-200/80 hover:border-blue-500/40 transition-all duration-300 shadow-md hover:-translate-y-1"
            >
              <div class="flex items-start justify-between gap-3 mb-2">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 flex items-center justify-center text-blue-400 light:text-blue-600 group-hover:scale-105 transition-transform">
                    <Terminal class="w-5 h-5" />
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-white light:text-slate-900 group-hover:text-blue-400 transition-colors">
                      {{ skill.name }}
                    </h4>
                    <span v-if="skill.level" class="text-[10px] font-medium text-slate-400 light:text-slate-500">
                      {{ skill.level }}
                    </span>
                  </div>
                </div>
              </div>

              <p v-if="skill.description" class="text-xs text-slate-400 light:text-slate-600 leading-relaxed mt-2">
                {{ skill.description }}
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { usePortfolioStore } from '@/stores/portfolio'
import { Cpu, Terminal } from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()
</script>
