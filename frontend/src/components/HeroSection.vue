<template>
  <section id="home" class="relative pt-32 pb-20 md:pt-44 md:pb-32 overflow-hidden">
    <!-- Ambient Gradient Glows -->
    <div class="glow-blob-blue w-96 h-96 -top-20 -left-20 opacity-70"></div>
    <div class="glow-blob-purple w-96 h-96 top-1/3 -right-20 opacity-60"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
        
        <!-- Left Column: Introduction & Call-to-Actions -->
        <div class="lg:col-span-7 space-y-6 text-left">
          <!-- Availability Badge -->
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 light:border-blue-200 text-blue-400 light:text-blue-600 text-xs font-semibold tracking-wide">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span>{{ portfolioStore.settings.hero_availability_text || 'Available for Work & Collaboration' }}</span>
          </div>

          <!-- Greeting & Main Name -->
          <div>
            <p class="text-base sm:text-lg font-medium text-slate-400 light:text-slate-600 mb-1">
              {{ portfolioStore.settings.hero_greeting || "Hello, I'm" }}
            </p>
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white light:text-slate-900 leading-[1.1]">
              {{ portfolioStore.settings.hero_name || 'Eyob Wendmamagegn' }}
            </h1>
          </div>

          <!-- Animated Typewriter Role Title -->
          <div class="h-10 sm:h-12 flex items-center">
            <span class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-300 light:text-slate-700 mr-2">I am a</span>
            <span class="text-xl sm:text-2xl lg:text-3xl font-extrabold gradient-text border-r-2 border-blue-500 pr-1 animate-pulse min-h-[1.5em] inline-block">
              {{ currentRoleText }}
            </span>
          </div>

          <!-- Short Professional Introduction -->
          <p class="text-base sm:text-lg text-slate-400 light:text-slate-600 leading-relaxed max-w-2xl">
            {{ portfolioStore.settings.hero_bio || "I'm a passionate developer who builds modern, reliable, and user-friendly web applications. I work with Laravel and Vue.js to create full-stack solutions, while also exploring technologies like Node.js and Next.js. I enjoy solving real-world problems, writing clean code, and turning ideas into useful digital experiences." }}
          </p>

          <!-- Action Buttons & Socials -->
          <div class="pt-4 flex flex-wrap items-center gap-4">
            <a 
              href="#work" 
              class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all text-sm"
            >
              <span>Explore My Work</span>
              <ArrowDownRight class="w-4 h-4" />
            </a>

            <a 
              :href="cvUrl"
              download="Eyob_Wendmamagegn_CV.pdf"
              class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-slate-200 light:text-slate-800 bg-slate-800/80 light:bg-slate-100 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700/80 light:border-slate-300 hover:-translate-y-0.5 transition-all text-sm"
            >
              <Download class="w-4 h-4 text-blue-400" />
              <span>Download CV</span>
            </a>

            <!-- Social Links -->
            <div class="flex items-center gap-2 sm:ml-2">
              <a 
                v-if="githubUrl" 
                :href="githubUrl" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="p-3 rounded-xl glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:border-blue-500/50 hover:bg-blue-600/10 transition-all hover:scale-105"
                title="GitHub Profile"
                aria-label="GitHub Profile"
              >
                <Github class="w-5 h-5" />
              </a>

              <a 
                v-if="linkedinUrl" 
                :href="linkedinUrl" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="p-3 rounded-xl glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-blue-600 hover:border-blue-500/50 hover:bg-blue-600/10 transition-all hover:scale-105"
                title="LinkedIn Profile"
                aria-label="LinkedIn Profile"
              >
                <Linkedin class="w-5 h-5" />
              </a>

              <a 
                href="#contact" 
                class="p-3 rounded-xl glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-blue-600 hover:border-blue-500/50 hover:bg-blue-600/10 transition-all hover:scale-105"
                title="Send an Email"
                aria-label="Email Eyob"
              >
                <Mail class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>

        <!-- Right Column: Profile Frame & Tech Badges -->
        <div class="lg:col-span-5 flex justify-center relative">
          <!-- Backing ambient glow halo -->
          <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/30 to-purple-600/30 rounded-full blur-3xl scale-90 -z-10 animate-pulse"></div>

          <!-- Main Profile Container with Irregular Rounded Gradient Border -->
          <div class="relative w-72 h-80 sm:w-88 sm:h-96 md:w-96 md:h-104 p-1.5 rounded-[2.5rem] bg-gradient-to-tr from-blue-500 via-indigo-500 to-fuchsia-500 shadow-2xl shadow-blue-500/20 group">
            <div class="w-full h-full rounded-[2.35rem] bg-slate-950 light:bg-slate-900 overflow-hidden relative flex items-center justify-center">
              
              <!-- Real Profile Image if available -->
              <img 
                v-if="profileAvatarUrl" 
                :src="profileAvatarUrl" 
                alt="Eyob Wendmamagegn — Full-Stack Developer" 
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
              />

              <!-- Elegant Developer Avatar Silhouette Placeholder -->
              <div v-else class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-b from-slate-900 to-slate-950">
                <div class="w-28 h-28 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-700 flex items-center justify-center text-white mb-4 shadow-xl border-2 border-white/20">
                  <Terminal class="w-14 h-14 text-blue-200" />
                </div>
                <h3 class="text-xl font-bold text-white mb-1">Eyob Wendmamagegn</h3>
                <p class="text-xs text-blue-400 font-mono tracking-wider uppercase">Full-Stack Engineer</p>
                <span class="mt-4 px-3 py-1 rounded-full text-[10px] font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                  Laravel & Vue.js Specialist
                </span>
              </div>

              <!-- Subtle Overlay vignette -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
            </div>

            <!-- Floating Tech Badges (Laravel, Vue.js, PHP, JavaScript) -->
            <!-- Badge 1: Laravel (Top-Right) -->
            <div class="absolute -top-4 -right-4 px-3.5 py-2 rounded-2xl glass-panel shadow-xl flex items-center gap-2 border border-rose-500/30 animate-float-slow bg-slate-900/90 light:bg-white">
              <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
              <span class="text-xs font-bold text-white light:text-slate-900">Laravel</span>
            </div>

            <!-- Badge 2: Vue.js (Bottom-Left) -->
            <div class="absolute -bottom-4 -left-4 px-3.5 py-2 rounded-2xl glass-panel shadow-xl flex items-center gap-2 border border-emerald-500/30 animate-float-delayed bg-slate-900/90 light:bg-white">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
              <span class="text-xs font-bold text-white light:text-slate-900">Vue.js 3</span>
            </div>

            <!-- Badge 3: PostgreSQL / DB (Top-Left) -->
            <div class="absolute top-1/4 -left-6 hidden sm:flex px-3 py-1.5 rounded-xl glass-panel shadow-lg items-center gap-2 border border-blue-500/30 animate-float-slow bg-slate-900/90 light:bg-white">
              <Database class="w-3.5 h-3.5 text-blue-400" />
              <span class="text-[11px] font-semibold text-white light:text-slate-900">PostgreSQL</span>
            </div>

            <!-- Badge 4: REST API (Bottom-Right) -->
            <div class="absolute bottom-1/4 -right-6 hidden sm:flex px-3 py-1.5 rounded-xl glass-panel shadow-lg items-center gap-2 border border-purple-500/30 animate-float-delayed bg-slate-900/90 light:bg-white">
              <Code2 class="w-3.5 h-3.5 text-purple-400" />
              <span class="text-[11px] font-semibold text-white light:text-slate-900">REST API</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { 
  ArrowDownRight, Download, Github, Linkedin, Mail, 
  Terminal, Database, Code2 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const profileAvatarUrl = computed(() => portfolioStore.settings.hero_avatar_url)
const githubUrl = computed(() => portfolioStore.settings.contact_github || 'https://github.com')
const linkedinUrl = computed(() => portfolioStore.settings.contact_linkedin || 'https://linkedin.com')
const cvUrl = computed(() => portfolioStore.settings.cv_file_url || 'http://localhost:8000/api/v1/cv/download')

// Typewriter role rotation
const currentRoleText = ref('')
const roleIndex = ref(0)
const charIndex = ref(0)
const isDeleting = ref(false)
let typingTimer = null

const roles = computed(() => {
  return portfolioStore.heroRoles.length > 0
    ? portfolioStore.heroRoles
    : [
        'Laravel Developer',
        'Vue.js Developer',
        'Full-Stack Web Developer',
        'Backend Developer',
        'Web Application Developer',
      ]
})

function typeWriter() {
  const currentRole = roles.value[roleIndex.value % roles.value.length]

  if (isDeleting.value) {
    currentRoleText.value = currentRole.substring(0, charIndex.value - 1)
    charIndex.value--
  } else {
    currentRoleText.value = currentRole.substring(0, charIndex.value + 1)
    charIndex.value++
  }

  let delay = isDeleting.value ? 40 : 90

  if (!isDeleting.value && charIndex.value === currentRole.length) {
    delay = 2000 // Pause on full word
    isDeleting.value = true
  } else if (isDeleting.value && charIndex.value === 0) {
    isDeleting.value = false
    roleIndex.value = (roleIndex.value + 1) % roles.value.length
    delay = 400 // Pause before next word
  }

  typingTimer = setTimeout(typeWriter, delay)
}

onMounted(() => {
  typeWriter()
})

onUnmounted(() => {
  if (typingTimer) clearTimeout(typingTimer)
})
</script>
