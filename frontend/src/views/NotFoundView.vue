<template>
  <div class="relative bg-slate-950 light:bg-slate-50 min-h-screen text-slate-100 light:text-slate-900 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-grid-pattern pointer-events-none opacity-40 z-0"></div>

    <div class="relative z-10 max-w-md w-full p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 text-center space-y-6 shadow-2xl">
      <div class="w-20 h-20 rounded-3xl bg-blue-600/10 text-blue-400 border border-blue-500/20 flex items-center justify-center mx-auto">
        <span class="text-3xl font-black font-mono">404</span>
      </div>

      <div class="space-y-2">
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900">Page Not Found</h1>
        <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 leading-relaxed">
          The page you are searching for does not exist or has been relocated.
        </p>
      </div>

      <router-link 
        to="/" 
        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-md shadow-blue-500/20 transition-all"
      >
        <Home class="w-4 h-4" />
        <span>Return to Portfolio</span>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { Home } from 'lucide-vue-next'
</script>
