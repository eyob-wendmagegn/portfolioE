<template>
  <div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Portfolio Content Editor</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Dynamically configure all copy, hero texts, biographies, and social links in real time.
        </p>
      </div>

      <button 
        @click="saveAllSettings" 
        :disabled="saving"
        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto disabled:opacity-50"
      >
        <Loader2 v-if="saving" class="w-3.5 h-3.5 animate-spin" />
        <Save v-else class="w-3.5 h-3.5" />
        <span>Save All Content</span>
      </button>
    </div>

    <!-- Alert / Toast Banner -->
    <div 
      v-if="feedback" 
      class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between"
    >
      <span>{{ feedback }}</span>
      <button @click="feedback = ''" class="text-emerald-400 hover:text-white"><X class="w-4 h-4" /></button>
    </div>

    <!-- Section 1: Hero Section Content & Avatar Upload -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <Sparkles class="w-5 h-5 text-blue-400" />
        <span>Hero Banner & Profile Picture</span>
      </h2>

      <!-- Profile Avatar File Upload -->
      <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col sm:flex-row items-center gap-6">
        <div class="relative w-24 h-24 rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 shrink-0 flex items-center justify-center">
          <img 
            v-if="avatarPreview || settings.hero_avatar_url" 
            :src="avatarPreview || settings.hero_avatar_url" 
            class="w-full h-full object-cover" 
          />
          <User v-else class="w-10 h-10 text-slate-600" />
        </div>

        <div class="space-y-2 text-center sm:text-left flex-1">
          <p class="text-xs font-bold text-white">Eyob's Profile Portrait</p>
          <p class="text-[11px] text-slate-400">
            Upload your professional photo. It will appear on the right side of the hero section with the gradient glowing border.
          </p>
          <div class="flex flex-wrap items-center gap-3 pt-1">
            <input 
              type="file" 
              accept="image/*"
              @change="handleAvatarFileChange"
              class="text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-500"
            />
            <button 
              v-if="settings.hero_avatar"
              @click="removeAvatar"
              type="button" 
              class="text-xs text-rose-400 hover:underline"
            >
              Revert to default placeholder
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Small Greeting</label>
          <input 
            v-model="settings.hero_greeting" 
            type="text" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Full Name</label>
          <input 
            v-model="settings.hero_name" 
            type="text" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>

      <!-- Typewriter Animated Roles -->
      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">
          Animated Typewriter Roles (One per line)
        </label>
        <textarea 
          v-model="heroRolesText" 
          rows="4" 
          placeholder="Laravel Developer&#10;Vue.js Developer&#10;Full-Stack Web Developer"
          class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none font-mono resize-none"
        ></textarea>
      </div>

      <!-- Short Hero Introduction -->
      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Hero Introduction Paragraph</label>
        <textarea 
          v-model="settings.hero_bio" 
          rows="3" 
          class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
        ></textarea>
      </div>

      <!-- Availability Indicator Text -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Availability Badge Text</label>
          <input 
            v-model="settings.hero_availability_text" 
            type="text" 
            placeholder="Available for Work"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Section 2: About Me Biography & Location -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <User class="w-5 h-5 text-indigo-400" />
        <span>About Me & Background Information</span>
      </h2>

      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Detailed Biography</label>
        <textarea 
          v-model="settings.about_bio" 
          rows="7" 
          class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
        ></textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Location</label>
          <input 
            v-model="settings.about_location" 
            type="text" 
            placeholder="Ethiopia"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">University / Academic Institution</label>
          <input 
            v-model="settings.about_university" 
            type="text" 
            placeholder="Woldia University"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Section 3: Contact Channels & Social Profile Links -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <Share2 class="w-5 h-5 text-emerald-400" />
        <span>Contact Information & Social Links</span>
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Contact Email</label>
          <input 
            v-model="settings.contact_email" 
            type="email" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Phone / WhatsApp</label>
          <input 
            v-model="settings.contact_phone" 
            type="text" 
            placeholder="+251 900 000 000"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">GitHub Profile URL</label>
          <input 
            v-model="settings.contact_github" 
            type="text" 
            placeholder="https://github.com/eyobnigata18"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">LinkedIn Profile URL</label>
          <input 
            v-model="settings.contact_linkedin" 
            type="text" 
            placeholder="https://linkedin.com/in/eyob-w"
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Section 4: Footer Copy & SEO Metadata -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <Globe class="w-5 h-5 text-cyan-400" />
        <span>Footer Copy & SEO Metadata</span>
      </h2>

      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Footer Short Bio</label>
        <textarea 
          v-model="settings.footer_bio" 
          rows="2" 
          class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
        ></textarea>
      </div>

      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">SEO Meta Description</label>
        <textarea 
          v-model="settings.seo_meta_description" 
          rows="2" 
          class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
        ></textarea>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { Sparkles, User, Share2, Globe, Save, Loader2, X } from 'lucide-vue-next'

const settings = ref({})
const heroRolesText = ref('')
const feedback = ref('')
const saving = ref(false)
const avatarPreview = ref(null)
let avatarFile = null

async function loadSettings() {
  try {
    const res = await api.get('/admin/settings')
    settings.value = res.data.data || {}
    const roles = settings.value.hero_roles || []
    heroRolesText.value = Array.isArray(roles) ? roles.join('\n') : ''
  } catch (e) {
    console.error('Failed loading settings', e)
  }
}

function handleAvatarFileChange(e) {
  if (e.target.files && e.target.files[0]) {
    avatarFile = e.target.files[0]
    avatarPreview.value = URL.createObjectURL(avatarFile)
  }
}

async function removeAvatar() {
  if (confirm('Revert profile picture to default silhouette placeholder?')) {
    try {
      await api.delete('/admin/settings/avatar')
      avatarPreview.value = null
      avatarFile = null
      settings.value.hero_avatar = null
      settings.value.hero_avatar_url = null
      feedback.value = 'Reverted avatar to placeholder.'
    } catch (e) {
      alert('Failed removing avatar.')
    }
  }
}

async function saveAllSettings() {
  saving.value = true
  try {
    // If avatar file was chosen, upload first
    if (avatarFile) {
      const fd = new FormData()
      fd.append('avatar', avatarFile)
      const aRes = await api.post('/admin/settings/avatar', fd)
      settings.value.hero_avatar = aRes.data.hero_avatar
      settings.value.hero_avatar_url = aRes.data.hero_avatar_url
      avatarFile = null
    }

    // Convert hero roles text back to array
    const rolesArray = heroRolesText.value
      .split('\n')
      .map(r => r.trim())
      .filter(r => r.length > 0)

    const payload = {
      settings: {
        ...settings.value,
        hero_roles: rolesArray,
      }
    }

    const res = await api.post('/admin/settings', payload)
    settings.value = res.data.data
    feedback.value = 'All portfolio content and settings updated successfully.'
  } catch (e) {
    alert('Failed updating content.')
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  loadSettings()
})
</script>
