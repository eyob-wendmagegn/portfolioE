<template>
  <header 
    :class="[
      'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
      isScrolled 
        ? 'glass-nav border-b border-slate-800/80 light:border-slate-200/80 py-3 shadow-lg shadow-black/5' 
        : 'bg-transparent py-5'
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <a 
          href="#home" 
          class="flex items-center gap-2 group text-xl font-bold tracking-tight text-white light:text-slate-900"
        >
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
            <span class="font-black text-sm">EW</span>
          </div>
          <span class="font-extrabold tracking-tighter text-lg">
            Eyob<span class="text-blue-500">.dev</span>
          </span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-1 lg:gap-2">
          <a 
            v-for="item in navItems" 
            :key="item.name" 
            :href="item.href"
            :class="[
              'px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all duration-200',
              activeSection === item.id 
                ? 'text-blue-400 light:text-blue-600 bg-blue-500/10 light:bg-blue-50' 
                : 'text-slate-300 light:text-slate-600 hover:text-white light:hover:text-slate-900 hover:bg-slate-800/50 light:hover:bg-slate-100'
            ]"
          >
            {{ item.name }}
          </a>
        </nav>

        <!-- Right Action Controls -->
        <div class="hidden md:flex items-center gap-3">
          <!-- Theme Toggle -->
          <button 
            @click="themeStore.toggleTheme" 
            class="p-2 rounded-xl text-slate-400 hover:text-white light:hover:text-slate-900 hover:bg-slate-800/60 light:hover:bg-slate-100 transition-colors"
            :title="themeStore.isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            aria-label="Toggle theme"
          >
            <Sun v-if="themeStore.isDark" class="w-5 h-5 text-amber-400 transition-transform duration-300 hover:rotate-45" />
            <Moon v-else class="w-5 h-5 text-indigo-600 transition-transform duration-300 hover:-rotate-12" />
          </button>

          <!-- Download CV Button -->
          <a 
            :href="cvUrl"
            download="Eyob_Wendmamagegn_CV.pdf"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 light:bg-slate-100 text-slate-200 light:text-slate-800 hover:bg-slate-700 light:hover:bg-slate-200 border border-slate-700 light:border-slate-300 transition-all shadow-sm"
          >
            <Download class="w-3.5 h-3.5 text-blue-400" />
            <span>CV</span>
          </a>

          <!-- Contact / Hire Me CTA -->
          <a 
            href="#contact" 
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-md shadow-blue-500/20 hover:shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all"
          >
            <span>Let's Talk</span>
          </a>

          <!-- Admin Portal Link -->
          <router-link 
            to="/admin/login" 
            class="p-2 rounded-xl text-slate-500 hover:text-slate-300 light:hover:text-slate-700 hover:bg-slate-800/40 light:hover:bg-slate-100 transition-colors"
            title="Admin Login"
            aria-label="Admin Portal"
          >
            <Lock class="w-4 h-4" />
          </router-link>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex items-center gap-2 md:hidden">
          <button 
            @click="themeStore.toggleTheme" 
            class="p-2 rounded-xl text-slate-400 hover:text-white light:hover:text-slate-900"
            aria-label="Toggle theme"
          >
            <Sun v-if="themeStore.isDark" class="w-5 h-5 text-amber-400" />
            <Moon v-else class="w-5 h-5 text-indigo-600" />
          </button>

          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="p-2 rounded-xl text-slate-300 light:text-slate-700 hover:bg-slate-800 light:hover:bg-slate-100 focus:outline-none"
            aria-label="Open menu"
          >
            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Dropdown Menu -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div 
          v-if="mobileMenuOpen" 
          class="md:hidden mt-3 p-4 rounded-2xl glass-panel border border-slate-800 light:border-slate-200 shadow-2xl space-y-3"
        >
          <a 
            v-for="item in navItems" 
            :key="item.name" 
            :href="item.href"
            @click="mobileMenuOpen = false"
            class="block px-3 py-2 rounded-xl text-base font-medium text-slate-200 light:text-slate-800 hover:bg-blue-600/10 hover:text-blue-400 transition-colors"
          >
            {{ item.name }}
          </a>

          <div class="pt-3 border-t border-slate-800 light:border-slate-200 flex flex-col gap-2">
            <a 
              :href="cvUrl"
              download="Eyob_Wendmamagegn_CV.pdf"
              @click="mobileMenuOpen = false"
              class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold bg-slate-800 light:bg-slate-100 text-slate-200 light:text-slate-800 border border-slate-700 light:border-slate-300"
            >
              <Download class="w-4 h-4 text-blue-400" />
              Download CV
            </a>

            <router-link 
              to="/admin/login"
              @click="mobileMenuOpen = false"
              class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-slate-200"
            >
              <Lock class="w-3.5 h-3.5" />
              Admin Portal
            </router-link>
          </div>
        </div>
      </transition>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useThemeStore } from '@/stores/theme'
import { usePortfolioStore } from '@/stores/portfolio'
import { Sun, Moon, Download, Lock, Menu, X } from 'lucide-vue-next'

const themeStore = useThemeStore()
const portfolioStore = usePortfolioStore()

const isScrolled = ref(false)
const mobileMenuOpen = ref(false)
const activeSection = ref('home')

const navItems = [
  { name: 'Home', href: '#home', id: 'home' },
  { name: 'Featured', href: '#featured', id: 'featured' },
  { name: 'LMS Showcase', href: '#lms-showcase', id: 'lms-showcase' },
  { name: 'Work', href: '#work', id: 'work' },
  { name: 'Skills', href: '#skills', id: 'skills' },
  { name: 'About', href: '#about', id: 'about' },
  { name: 'Services', href: '#services', id: 'services' },
  { name: 'Contact', href: '#contact', id: 'contact' },
]

const cvUrl = computed(() => {
  return portfolioStore.settings.cv_file_url || 'http://localhost:8000/api/v1/cv/download'
})

function handleScroll() {
  isScrolled.value = window.scrollY > 30

  // Determine active section
  const scrollPos = window.scrollY + 100
  for (const item of navItems) {
    const el = document.getElementById(item.id)
    if (el) {
      const top = el.offsetTop
      const height = el.offsetHeight
      if (scrollPos >= top && scrollPos < top + height) {
        activeSection.value = item.id
      }
    }
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
