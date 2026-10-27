<template>
  <div class="min-h-screen bg-slate-950 light:bg-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Dark Background with Subtle Grid Pattern & Ambient Gradients -->
    <div class="fixed inset-0 bg-grid-pattern opacity-30 pointer-events-none"></div>
    <div class="glow-blob-blue w-96 h-96 -top-20 -left-20 opacity-40"></div>
    <div class="glow-blob-purple w-96 h-96 -bottom-20 -right-20 opacity-30"></div>

    <div class="relative z-10 w-full max-w-md">
      <!-- Centered Card inspired by screenshot -->
      <div class="p-8 sm:p-10 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-2xl backdrop-blur-xl">
        
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white mx-auto mb-4 shadow-lg shadow-blue-500/25 border border-white/20">
            <Lock class="w-6 h-6" />
          </div>

          <h1 class="text-2xl font-extrabold text-white tracking-tight">Admin CMS Portal</h1>
          <p class="text-xs text-slate-400 mt-1.5">
            Sign in to manage projects, screenshots, and portfolio content.
          </p>
        </div>

        <!-- Alert Error Banner -->
        <div 
          v-if="errorMessage" 
          class="mb-6 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start gap-2.5"
        >
          <AlertCircle class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" />
          <span>{{ errorMessage }}</span>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email Field -->
          <div>
            <label for="admin-email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
              Email Address
            </label>
            <div class="relative">
              <Mail class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input 
                id="admin-email"
                v-model="email" 
                type="email" 
                required
                autocomplete="email"
                placeholder="eyobnigata18@gmail.com"
                class="w-full pl-10 pr-4 py-3 rounded-xl text-sm bg-slate-950/80 border border-slate-800 text-white placeholder:text-slate-600 focus:border-blue-500 focus:outline-none transition-colors"
              />
            </div>
          </div>

          <!-- Password Field with Show/Hide Toggle -->
          <div>
            <label for="admin-password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
              Password
            </label>
            <div class="relative">
              <KeyRound class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input 
                id="admin-password"
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                required
                autocomplete="current-password"
                placeholder="••••••••••••"
                class="w-full pl-10 pr-11 py-3 rounded-xl text-sm bg-slate-950/80 border border-slate-800 text-white placeholder:text-slate-600 focus:border-blue-500 focus:outline-none transition-colors"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 focus:outline-none"
                tabindex="-1"
              >
                <EyeOff v-if="showPassword" class="w-4 h-4" />
                <Eye v-else class="w-4 h-4" />
              </button>
            </div>
          </div>

          <!-- Gradient Sign-in Button -->
          <button 
            type="submit" 
            :disabled="authStore.loading"
            class="w-full py-3.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-500 hover:via-indigo-500 hover:to-purple-500 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
          >
            <Loader2 v-if="authStore.loading" class="w-4 h-4 animate-spin" />
            <span v-else>Sign In to Dashboard</span>
          </button>
        </form>

        <!-- Back to Portfolio -->
        <div class="mt-8 pt-6 border-t border-slate-800 text-center">
          <router-link 
            to="/" 
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors"
          >
            <ArrowLeft class="w-3.5 h-3.5" />
            <span>Return to Public Portfolio</span>
          </router-link>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { 
  Lock, Mail, KeyRound, Eye, EyeOff, Loader2, ArrowLeft, AlertCircle 
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const email = ref('eyobnigata18@gmail.com')
const password = ref('')
const showPassword = ref(false)
const errorMessage = ref('')

async function handleLogin() {
  errorMessage.value = ''
  try {
    await authStore.login(email.value, password.value)
    const redirect = route.query.redirect || '/admin/dashboard'
    router.push(redirect)
  } catch (err) {
    errorMessage.value = authStore.error || 'Invalid credentials. Please verify your email and password.'
  }
}
</script>
