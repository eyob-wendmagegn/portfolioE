<template>
  <section id="about" class="py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          About Me
        </h2>
        <p class="text-sm sm:text-base text-slate-400 light:text-slate-600">
          Dedicated junior full-stack developer committed to continuous learning, engineering best practices, and building clean web systems.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Biography & Details Left Column -->
        <div class="lg:col-span-7 space-y-6">
          <div class="p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-xl space-y-4">
            <h3 class="text-xl font-bold text-white light:text-slate-900">
              Passionate Developer from Ethiopia
            </h3>
            
            <div class="text-sm sm:text-base text-slate-300 light:text-slate-700 leading-relaxed whitespace-pre-line space-y-3">
              {{ aboutBio }}
            </div>

            <!-- Quick Specs Metadata Grid -->
            <div class="pt-6 border-t border-slate-800/80 light:border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                  <MapPin class="w-4 h-4" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Location</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    {{ portfolioStore.settings.about_location || 'Ethiopia' }}
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                  <GraduationCap class="w-4 h-4" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Education</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    {{ portfolioStore.settings.about_university || 'Woldia University Graduate' }}
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                  <Code class="w-4 h-4" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Primary Focus</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    Laravel & Vue.js Full-Stack
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                  <Mail class="w-4 h-4" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Direct Email</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    {{ portfolioStore.settings.contact_email || 'eyobnigata18@gmail.com' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Academic & Goals Right Column -->
        <div class="lg:col-span-5 space-y-6">
          <!-- Academic Timeline Card -->
          <div class="p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-xl space-y-4">
            <h4 class="text-lg font-bold text-white light:text-slate-900 flex items-center gap-2">
              <GraduationCap class="w-5 h-5 text-blue-400" />
              <span>Academic Foundation</span>
            </h4>

            <div v-if="portfolioStore.education.length > 0" class="space-y-4">
              <div 
                v-for="edu in portfolioStore.education" 
                :key="edu.id"
                class="p-4 rounded-2xl bg-slate-900/60 light:bg-slate-50 border border-slate-800/50 light:border-slate-200/50"
              >
                <div class="flex items-start justify-between gap-2">
                  <h5 class="text-sm font-bold text-white light:text-slate-900">{{ edu.degree_or_title }}</h5>
                  <span v-if="edu.start_year || edu.end_year" class="text-[11px] font-mono text-blue-400">
                    {{ edu.start_year }} - {{ edu.end_year }}
                  </span>
                </div>
                <p class="text-xs text-indigo-400 font-medium mt-0.5">{{ edu.institution }}</p>
                <p v-if="edu.location" class="text-[11px] text-slate-400">{{ edu.location }}</p>
                <p v-if="edu.description" class="text-xs text-slate-300 light:text-slate-600 mt-2 leading-relaxed">
                  {{ edu.description }}
                </p>
              </div>
            </div>

            <div v-else class="p-4 rounded-2xl bg-slate-900/60 light:bg-slate-50">
              <h5 class="text-sm font-bold text-white light:text-slate-900">Woldia University</h5>
              <p class="text-xs text-slate-400 mt-1">
                Completed comprehensive coursework in software engineering, algorithms, database systems, and full-stack web applications.
              </p>
            </div>
          </div>

          <!-- Career Vision & Ambition Card -->
          <div class="p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-xl space-y-3">
            <h4 class="text-lg font-bold text-white light:text-slate-900 flex items-center gap-2">
              <Compass class="w-5 h-5 text-indigo-400" />
              <span>Career Philosophy</span>
            </h4>
            <p class="text-xs sm:text-sm text-slate-300 light:text-slate-600 leading-relaxed">
              I believe in writing clean, readable code and approaching software problems methodically. Whether designing a relational database schema or crafting a responsive interface in Vue.js, my focus remains on building software that is reliable, accessible, and practical.
            </p>
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
  User, MapPin, GraduationCap, Code, Mail, Compass 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const aboutBio = computed(() => {
  return portfolioStore.settings.about_bio || 
    "My name is Eyob Wendmamagegn. I am a junior developer and a graduate of Woldia University. During my studies and project work, I developed an interest in building modern web applications and solving practical problems through software.\n\nI work with technologies including Laravel, PHP, Vue.js, JavaScript, Node.js, and Next.js. I enjoy creating responsive interfaces, developing backend functionality, designing database-driven applications, and connecting frontend experiences with reliable APIs.\n\nMy goal is to continue growing as a developer, strengthen my full-stack engineering skills, and contribute to meaningful projects that make people's work easier."
})
</script>
