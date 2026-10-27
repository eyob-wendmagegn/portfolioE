<template>
  <div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">
          Library Management System Screenshots
        </h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Upload and manage high-resolution UI screenshots for the four distinct dashboard roles.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <a 
          href="/#lms-showcase" 
          target="_blank" 
          class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white border border-slate-700 transition-colors"
        >
          <Eye class="w-3.5 h-3.5" />
          <span>Preview Showcase</span>
        </a>
      </div>
    </div>

    <!-- Alert / Toast Banner -->
    <div 
      v-if="feedback" 
      class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between"
    >
      <span>{{ feedback }}</span>
      <button @click="feedback = ''" class="text-emerald-400 hover:text-white"><X class="w-4 h-4" /></button>
    </div>

    <!-- 4 Dashboards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div 
        v-for="dash in dashboards" 
        :key="dash.id"
        class="rounded-3xl glass-panel border border-slate-800 light:border-slate-200 overflow-hidden shadow-xl flex flex-col justify-between"
      >
        <div>
          <!-- Image Preview Area -->
          <div class="relative aspect-video bg-slate-950 light:bg-slate-100 overflow-hidden border-b border-slate-800 light:border-slate-200 flex items-center justify-center">
            <!-- Uploaded Screenshot Preview -->
            <img 
              v-if="dash.image_url" 
              :src="dash.image_url" 
              :alt="dash.title" 
              class="w-full h-full object-cover"
            />
            
            <!-- Placeholder UI if no screenshot uploaded -->
            <div v-else class="text-center p-6 space-y-2">
              <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center mx-auto mb-2 border border-blue-500/20">
                <UploadCloud class="w-6 h-6" />
              </div>
              <p class="text-xs font-bold text-white light:text-slate-900">Placeholder Active</p>
              <p class="text-[11px] text-slate-400 max-w-xs">
                No screenshot uploaded yet. Upload a screenshot below to replace the default placeholder.
              </p>
            </div>

            <!-- Role Badge -->
            <div class="absolute top-4 left-4">
              <span 
                :class="[
                  'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-white shadow-md backdrop-blur-md',
                  badgeColorClass(dash.role)
                ]"
              >
                {{ dash.role }}
              </span>
            </div>

            <!-- Remove Button if custom image is set -->
            <button 
              v-if="dash.image_url" 
              @click="removeScreenshot(dash)"
              class="absolute top-4 right-4 p-2 rounded-full bg-slate-950/80 text-rose-400 hover:bg-rose-500 hover:text-white transition-colors"
              title="Remove screenshot and revert to placeholder"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>

          <!-- Content Details Form -->
          <div class="p-6 space-y-4">
            <div>
              <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                Dashboard Title
              </label>
              <input 
                v-model="dash.title" 
                type="text" 
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                Description / Responsibilities
              </label>
              <textarea 
                v-model="dash.description" 
                rows="3"
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none resize-none"
              ></textarea>
            </div>

            <!-- Upload File Input -->
            <div>
              <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                Upload / Replace Screenshot File
              </label>
              <input 
                type="file" 
                accept="image/*"
                @change="(e) => handleFileChange(e, dash.id)"
                class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-500"
              />
            </div>
          </div>
        </div>

        <!-- Card Footer -->
        <div class="p-6 pt-0 border-t border-slate-800/60 light:border-slate-200/60 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <input 
              :id="'visible-' + dash.id" 
              v-model="dash.is_visible" 
              type="checkbox" 
              class="w-4 h-4 rounded text-blue-600 focus:ring-0"
            />
            <label :for="'visible-' + dash.id" class="text-xs text-slate-400">Publicly Visible</label>
          </div>

          <button 
            @click="saveDashboard(dash)" 
            :disabled="savingId === dash.id"
            class="px-5 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white flex items-center gap-1.5 disabled:opacity-50 transition-all"
          >
            <Loader2 v-if="savingId === dash.id" class="w-3.5 h-3.5 animate-spin" />
            <span>Save Changes</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { Eye, UploadCloud, Trash2, X, Loader2 } from 'lucide-vue-next'

const dashboards = ref([])
const feedback = ref('')
const savingId = ref(null)
const filesMap = {}

function badgeColorClass(role) {
  switch (role) {
    case 'administrator': return 'bg-rose-500'
    case 'librarian': return 'bg-indigo-600'
    case 'teacher': return 'bg-emerald-600'
    case 'student': return 'bg-amber-500 text-slate-950'
    default: return 'bg-blue-600'
  }
}

async function loadDashboards() {
  try {
    const res = await api.get('/admin/library-dashboards')
    dashboards.value = res.data.data || []
  } catch (e) {
    console.error('Failed loading dashboards', e)
  }
}

function handleFileChange(e, id) {
  if (e.target.files && e.target.files[0]) {
    filesMap[id] = e.target.files[0]
  }
}

async function saveDashboard(dash) {
  savingId.value = dash.id
  try {
    const formData = new FormData()
    formData.append('title', dash.title)
    formData.append('description', dash.description || '')
    formData.append('is_visible', dash.is_visible ? '1' : '0')
    if (dash.badge_color) formData.append('badge_color', dash.badge_color)
    if (dash.alt_text) formData.append('alt_text', dash.alt_text)

    if (filesMap[dash.id]) {
      formData.append('screenshot', filesMap[dash.id])
    }

    const res = await api.post(`/admin/library-dashboards/${dash.id}`, formData)
    feedback.value = res.data.message || 'Saved successfully.'
    delete filesMap[dash.id]
    await loadDashboards()
  } catch (e) {
    alert(e.response?.data?.message || 'Failed updating dashboard screenshot.')
  } finally {
    savingId.value = null
  }
}

async function removeScreenshot(dash) {
  if (confirm(`Remove custom screenshot for "${dash.title}" and revert to placeholder?`)) {
    try {
      await api.delete(`/admin/library-dashboards/${dash.id}/image`)
      feedback.value = `Reverted "${dash.title}" to placeholder.`
      await loadDashboards()
    } catch (e) {
      alert('Failed removing screenshot.')
    }
  }
}

onMounted(() => {
  loadDashboards()
})
</script>
