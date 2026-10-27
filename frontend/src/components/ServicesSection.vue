<template>
  <section id="services" class="py-24 relative bg-slate-900/30 light:bg-slate-50/50 border-t border-slate-800/80 light:border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 text-blue-400 light:text-blue-600 text-xs font-bold tracking-widest uppercase mb-3">
          <Briefcase class="w-3.5 h-3.5" />
          <span>What I Offer</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          Services & Capabilities
        </h2>
        <p class="text-sm sm:text-base text-slate-400 light:text-slate-600">
          Professional engineering services tailored to startups, businesses, and academic initiatives.
        </p>
      </div>

      <!-- Services Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="service in services" 
          :key="service.id"
          class="group p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 hover:border-blue-500/40 transition-all duration-300 shadow-xl flex flex-col justify-between hover:-translate-y-1.5"
        >
          <div>
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 flex items-center justify-center text-blue-400 light:text-blue-600 mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all">
              <Server class="w-7 h-7" />
            </div>

            <h3 class="text-xl font-bold text-white light:text-slate-900 mb-3 group-hover:text-blue-400 transition-colors">
              {{ service.title }}
            </h3>

            <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 leading-relaxed mb-6">
              {{ service.description }}
            </p>
          </div>

          <a 
            :href="service.action_url || '#contact'" 
            class="inline-flex items-center gap-2 text-xs font-semibold text-blue-400 light:text-blue-600 hover:text-blue-300 transition-colors pt-4 border-t border-slate-800/60 light:border-slate-200/60"
          >
            <span>{{ service.action_text || 'Start a Project' }}</span>
            <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
          </a>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { Briefcase, Server, ArrowRight } from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const services = computed(() => {
  return portfolioStore.services.length > 0 
    ? portfolioStore.services 
    : [
        {
          id: 1,
          title: 'Laravel Web Application Development',
          description: 'Custom full-stack web applications built with Laravel, clean MVC architecture, robust security, and reliable database structures.',
          action_text: 'Get in Touch',
          action_url: '#contact',
        },
        {
          id: 2,
          title: 'Vue.js Frontend Development',
          description: 'Modern, reactive Single Page Applications (SPAs) with Vue 3, Pinia state management, and intuitive user experiences.',
          action_text: 'Discuss Project',
          action_url: '#contact',
        },
        {
          id: 3,
          title: 'REST API Development & Integration',
          description: 'Secure, well-documented RESTful APIs with Sanctum authentication, Form Request validation, and seamless frontend consumption.',
          action_text: 'Plan an API',
          action_url: '#contact',
        },
        {
          id: 4,
          title: 'Database-Driven Applications',
          description: 'PostgreSQL and MySQL database modeling, schema design, query optimization, and structured relational data management.',
          action_text: 'Inquire Details',
          action_url: '#contact',
        },
        {
          id: 5,
          title: 'Website Design Implementation',
          description: 'Converting Figma or design concepts into pixel-perfect, responsive, and accessible Tailwind CSS web interfaces.',
          action_text: 'Start Design',
          action_url: '#contact',
        },
        {
          id: 6,
          title: 'Bug Fixing & Website Improvements',
          description: 'Auditing existing codebases, resolving performance bottlenecks, upgrading dependencies, and enhancing responsive styling.',
          action_text: 'Request Support',
          action_url: '#contact',
        },
      ]
})
</script>
