<template>
  <section id="lms-showcase" class="py-20 relative overflow-hidden bg-slate-950/70">
    <!-- Ambient backlights -->
    <div class="glow-blob-blue w-96 h-96 top-1/4 -left-32 opacity-30"></div>
    <div class="glow-blob-purple w-96 h-96 bottom-1/4 -right-32 opacity-30"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold tracking-widest uppercase mb-3">
          <span>Flagship Project</span>
        </div>
        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-3">
          Library Management System
        </h2>
        <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">
          A multi-tiered campus library automation platform engineered with role-based access control and dedicated workflows for four user groups.
        </p>
      </div>

      <!-- 1-ROW COMPACT GRID ON DESKTOP (4 Columns) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-12">
        <div 
          v-for="card in orderedCards" 
          :key="card.role"
          :class="[
            'group rounded-2xl glass-panel border border-slate-800/80 overflow-hidden transition-all duration-300 shadow-lg flex flex-col hover:-translate-y-1',
            card.config.borderHover
          ]"
        >
          <!-- Compact Screenshot Preview -->
          <div 
            class="relative h-44 sm:h-40 bg-slate-900 overflow-hidden border-b border-slate-800/80"
          >
            <!-- Real Screenshot Image -->
            <img 
              v-if="card.image_url" 
              :src="card.image_url" 
              :alt="card.alt_text || card.title"
              class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
            />
            
            <!-- Elegant Fallback UI if not uploaded -->
            <div 
              v-else 
              :class="[
                'w-full h-full flex flex-col items-center justify-center p-4 bg-gradient-to-br',
                card.config.glowColor
              ]"
            >
              <div class="w-12 h-12 rounded-xl bg-slate-800/80 border border-slate-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <component :is="card.config.icon" class="w-6 h-6 text-slate-200" />
              </div>
              <span class="text-xs font-bold text-white text-center">{{ card.title }}</span>
            </div>

            <!-- Role Badge Overlay -->
            <div class="absolute top-2.5 left-2.5">
              <span :class="['px-2.5 py-0.5 rounded-full text-[10px] font-bold shadow-md flex items-center gap-1 backdrop-blur-md', card.config.badgeBg]">
                <component :is="card.config.icon" class="w-3 h-3" />
                <span>{{ card.config.label }}</span>
              </span>
            </div>
          </div>

          <!-- Compact Content Area -->
          <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
            <div>
              <h3 class="text-sm font-bold text-white group-hover:text-blue-400 transition-colors">
                {{ card.title }}
              </h3>
              <p class="text-xs text-slate-400 leading-relaxed line-clamp-3 mt-1">
                {{ card.description }}
              </p>
            </div>

            <!-- Chips / Highlights -->
            <div class="pt-2.5 border-t border-slate-800/60 space-y-1.5">
              <div 
                v-for="(chip, i) in card.config.chips" 
                :key="i"
                class="flex items-center gap-1.5 text-[11px] text-slate-300"
              >
                <Check class="w-3 h-3 text-emerald-400 shrink-0" />
                <span class="truncate">{{ chip }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Narrative Section: Overview, Tech Stack, Learnings -->
      <div class="p-6 sm:p-8 rounded-2xl glass-panel border border-slate-800/80 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Column 1: Project Overview -->
          <div>
            <h4 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
              <Layers class="w-4 h-4 text-blue-400" />
              <span>Project Overview</span>
            </h4>
            <p class="text-xs text-slate-400 leading-relaxed">
              Designed as a comprehensive solution for Woldia University library system. The architecture separates concerns between administrative controls, operational tasks by librarians, instructional requests by teachers, and self-service book lookup for students.
            </p>
          </div>

          <!-- Column 2: Tech Stack -->
          <div>
            <h4 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
              <Cpu class="w-4 h-4 text-indigo-400" />
              <span>Core Technologies</span>
            </h4>
            <div class="flex flex-wrap gap-1.5">
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-rose-500/10 text-rose-300 border border-rose-500/20">Node.js</span>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">Next.js</span>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-500/10 text-blue-300 border border-blue-500/20">MongoDB</span>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">Tailwind CSS</span>
              <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/20">RESTful API</span>
            </div>
          </div>

          <!-- Column 3: Key Learnings -->
          <div>
            <h4 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
              <CheckCircle class="w-4 h-4 text-emerald-400" />
              <span>Key Learnings</span>
            </h4>
            <ul class="space-y-1.5 text-xs text-slate-400">
              <li class="flex items-start gap-1.5">
                <span class="text-blue-400 font-bold">•</span>
                <span>Optimized database transactions for concurrent book checkout and return.</span>
              </li>
              <li class="flex items-start gap-1.5">
                <span class="text-blue-400 font-bold">•</span>
                <span>Implemented granular role authorization preventing privilege escalation.</span>
              </li>
              <li class="flex items-start gap-1.5">
                <span class="text-blue-400 font-bold">•</span>
                <span>Built reactive dashboard state with Next.js for instant filtering.</span>
              </li>
            </ul>
          </div>

        </div>
      </div>

    </div>

  </section>
</template>

<script setup>
import { computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { 
  ShieldCheck, BookmarkCheck, GraduationCap, 
  BookOpenCheck, Check, 
  Layers, Cpu, CheckCircle 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const roleConfig = {
  administrator: {
    label: 'Administrator',
    icon: ShieldCheck,
    badgeBg: 'bg-rose-500 text-white',
    borderHover: 'hover:border-rose-500/50',
    glowColor: 'from-rose-950/30 via-slate-900 to-slate-900',
    fallbackTitle: 'Administrator Dashboard',
    fallbackDesc: 'User registration, activation, deactivation, and system metrics overview.',
    chips: ['User activation & roles', 'System metrics overview']
  },
  librarian: {
    label: 'Librarian',
    icon: BookmarkCheck,
    badgeBg: 'bg-indigo-600 text-white',
    borderHover: 'hover:border-indigo-500/50',
    glowColor: 'from-indigo-950/30 via-slate-900 to-slate-900',
    fallbackTitle: 'Librarian Dashboard',
    fallbackDesc: 'Manage book inventory, circulation queue, and track overdue loans.',
    chips: ['Book catalog manage', 'Borrow & return queue']
  },
  teacher: {
    label: 'Teacher',
    icon: GraduationCap,
    badgeBg: 'bg-emerald-600 text-white',
    borderHover: 'hover:border-emerald-500/50',
    glowColor: 'from-emerald-950/30 via-slate-900 to-slate-900',
    fallbackTitle: 'Teacher Dashboard',
    fallbackDesc: 'Add academic references, browse resources, and course reading lists.',
    chips: ['Add curriculum books', 'Resource search & lists']
  },
  student: {
    label: 'Student',
    icon: BookOpenCheck,
    badgeBg: 'bg-amber-500 text-slate-950 font-bold',
    borderHover: 'hover:border-amber-500/50',
    glowColor: 'from-amber-950/30 via-slate-900 to-slate-900',
    fallbackTitle: 'Student Dashboard',
    fallbackDesc: 'Search live catalog, view copy availability, and track borrow history.',
    chips: ['Browse & search books', 'Live availability & loans']
  }
}

const orderedCards = computed(() => {
  const roles = ['administrator', 'librarian', 'teacher', 'student']
  const apiData = portfolioStore.libraryDashboards || []

  return roles.map(role => {
    const found = apiData.find(d => d.role === role) || {}
    const config = roleConfig[role]
    return {
      role,
      config,
      title: found.title || config.fallbackTitle,
      description: found.description || config.fallbackDesc,
      image_url: found.image_url || null,
      alt_text: found.alt_text || config.fallbackTitle,
    }
  })
})
</script>
