<template>
  <footer class="border-t border-slate-800/80 light:border-slate-200/80 bg-slate-950 light:bg-slate-50 pt-16 pb-12 text-slate-400 light:text-slate-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
        
        <!-- Column 1 & 2: Brand & Developer Introduction -->
        <div class="lg:col-span-2 space-y-4">
          <a href="#home" class="flex items-center gap-2 group text-xl font-bold tracking-tight text-white light:text-slate-900">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
              EW
            </div>
            <span class="font-extrabold tracking-tighter">
              Eyob<span class="text-blue-500">.dev</span>
            </span>
          </a>

          <p class="text-xs sm:text-sm leading-relaxed max-w-sm">
            {{ portfolioStore.settings.footer_bio || 'Junior full-stack web developer and Laravel & Vue.js specialist based in Ethiopia, crafting clean, scalable, and database-driven software applications.' }}
          </p>

          <!-- Socials -->
          <div class="flex items-center gap-2 pt-2">
            <a 
              v-if="githubUrl" 
              :href="githubUrl" 
              target="_blank" 
              rel="noopener noreferrer"
              class="p-2 rounded-lg bg-slate-900 light:bg-white text-slate-400 hover:text-white light:hover:text-black border border-slate-800 light:border-slate-200 transition-colors"
              title="GitHub"
              aria-label="GitHub Profile"
            >
              <Github class="w-4 h-4" />
            </a>

            <a 
              v-if="linkedinUrl" 
              :href="linkedinUrl" 
              target="_blank" 
              rel="noopener noreferrer"
              class="p-2 rounded-lg bg-slate-900 light:bg-white text-slate-400 hover:text-blue-500 border border-slate-800 light:border-slate-200 transition-colors"
              title="LinkedIn"
              aria-label="LinkedIn Profile"
            >
              <Linkedin class="w-4 h-4" />
            </a>

            <a 
              :href="gmailUrl"
              target="_blank"
              rel="noopener noreferrer"
              class="p-2 rounded-lg bg-slate-900 light:bg-white text-slate-400 hover:text-blue-500 border border-slate-800 light:border-slate-200 transition-colors"
              title="Compose Email in Gmail"
              aria-label="Email Eyob via Gmail"
            >
              <Mail class="w-4 h-4" />
            </a>
          </div>
        </div>

        <!-- Column 3: Quick Navigation -->
        <div>
          <h4 class="text-xs font-bold uppercase tracking-wider text-white light:text-slate-900 mb-4">
            Navigation
          </h4>
          <ul class="space-y-2 text-xs">
            <li><a href="#home" @click.prevent="scrollToSection('home')" class="hover:text-blue-400 transition-colors">Home</a></li>
            <li><a href="#featured" @click.prevent="scrollToSection('featured')" class="hover:text-blue-400 transition-colors">Featured Projects</a></li>
            <li><a href="#lms-showcase" @click.prevent="scrollToSection('lms-showcase')" class="hover:text-blue-400 transition-colors">LMS Showcase</a></li>
            <li><a href="#work" @click.prevent="scrollToSection('work')" class="hover:text-blue-400 transition-colors">All Work</a></li>
            <li><a href="#skills" @click.prevent="scrollToSection('skills')" class="hover:text-blue-400 transition-colors">Skills & Stack</a></li>
            <li><a href="#about" @click.prevent="scrollToSection('about')" class="hover:text-blue-400 transition-colors">About Eyob</a></li>
            <li><a href="#services" @click.prevent="scrollToSection('services')" class="hover:text-blue-400 transition-colors">Services</a></li>
            <li><a href="#contact" @click.prevent="scrollToSection('contact')" class="hover:text-blue-400 transition-colors">Contact</a></li>
          </ul>
        </div>

        <!-- Column 4: Technology Highlights -->
        <div>
          <h4 class="text-xs font-bold uppercase tracking-wider text-white light:text-slate-900 mb-4">
            Core Technologies
          </h4>
          <ul class="space-y-2 text-xs">
            <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Laravel (PHP)</li>
            <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Vue.js 3 & Vite</li>
            <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>PostgreSQL & MongoDB</li>
            <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>Tailwind CSS</li>
            <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>Laravel Sanctum</li>
          </ul>
        </div>

        <!-- Column 5: Legal & Admin -->
        <div>
          <h4 class="text-xs font-bold uppercase tracking-wider text-white light:text-slate-900 mb-4">
            Administration & Legal
          </h4>
          <ul class="space-y-2 text-xs">
            <li>
              <router-link to="/admin/login" class="text-blue-400 hover:underline flex items-center gap-1">
                <Lock class="w-3 h-3" />
                <span>Admin CMS Login</span>
              </router-link>
            </li>
            <li>
              <button @click="showPrivacy = true" class="hover:text-blue-400 transition-colors">
                Privacy Policy
              </button>
            </li>
            <li>
              <button @click="showTerms = true" class="hover:text-blue-400 transition-colors">
                Terms of Service
              </button>
            </li>
            <li class="pt-2 text-[11px] text-slate-500">
              Host: Local / Production API
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 border-t border-slate-800/60 light:border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <p>
          &copy; {{ currentYear }} Eyob Wendmamagegn. All rights reserved.
        </p>
        <p class="flex items-center gap-1 text-[11px] text-slate-500">
          Crafted with 
          <span class="text-rose-500">&hearts;</span> 
          using Laravel REST API & Vue.js 3
        </p>
      </div>

    </div>

    <!-- Privacy Policy Modal -->
    <div 
      v-if="showPrivacy" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showPrivacy = false"
    >
      <div class="max-w-lg w-full bg-slate-900 light:bg-white rounded-3xl border border-slate-800 light:border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold text-white light:text-slate-900">Privacy Policy</h3>
          <button @click="showPrivacy = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>
        <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed">
          This portfolio website collects contact form submissions solely to communicate with prospective clients and employers. Information submitted is never shared or sold to third parties.
        </p>
      </div>
    </div>

    <!-- Terms of Service Modal -->
    <div 
      v-if="showTerms" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showTerms = false"
    >
      <div class="max-w-lg w-full bg-slate-900 light:bg-white rounded-3xl border border-slate-800 light:border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold text-white light:text-slate-900">Terms of Service</h3>
          <button @click="showTerms = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>
        <p class="text-xs text-slate-300 light:text-slate-600 leading-relaxed">
          The project code and materials displayed on this portfolio are authored by Eyob Wendmamagegn for demonstration, portfolio review, and engineering collaboration purposes.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { formatExternalUrl, getGmailComposeUrl } from '@/utils/url'
import { Github, Linkedin, Mail, Lock, X } from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const currentYear = new Date().getFullYear()
const showPrivacy = ref(false)
const showTerms = ref(false)

const emailAddress = computed(() => portfolioStore.settings.contact_email || 'eyobnigata18@gmail.com')
const githubUrl = computed(() => formatExternalUrl(portfolioStore.settings.contact_github || 'https://github.com/eyobnigata18'))
const linkedinUrl = computed(() => formatExternalUrl(portfolioStore.settings.contact_linkedin || 'https://linkedin.com/in/eyob-w'))
const gmailUrl = computed(() => getGmailComposeUrl({ to: emailAddress.value, subject: 'Collaboration Inquiry — Eyob.dev' }))

function scrollToSection(id) {
  const el = document.getElementById(id)
  if (!el) return
  const yOffset = -70
  const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset
  window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' })
}
</script>
