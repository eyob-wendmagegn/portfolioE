<template>
  <div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Academic History</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Manage university degrees, graduation details, and institution highlights.
        </p>
      </div>

      <button 
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto"
      >
        <Plus class="w-4 h-4" />
        <span>Add Education</span>
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

    <!-- Education List -->
    <div class="space-y-4">
      <div 
        v-for="edu in educationList" 
        :key="edu.id"
        class="p-6 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl flex items-start justify-between gap-4"
      >
        <div class="space-y-1.5">
          <div class="flex items-center gap-2">
            <h3 class="text-base font-bold text-white light:text-slate-900">{{ edu.degree_or_title }}</h3>
            <span class="text-xs font-mono text-blue-400">({{ edu.start_year }} - {{ edu.end_year }})</span>
          </div>
          <p class="text-xs font-semibold text-indigo-400">{{ edu.institution }} — {{ edu.location }}</p>
          <p class="text-xs text-slate-400 leading-relaxed mt-2">{{ edu.description }}</p>
        </div>

        <div class="flex items-center gap-1 shrink-0">
          <button 
            @click="openEditModal(edu)"
            class="p-1.5 rounded-lg text-slate-400 hover:text-blue-400 hover:bg-slate-800"
            title="Edit"
          >
            <Edit3 class="w-4 h-4" />
          </button>
          <button 
            @click="deleteEdu(edu)"
            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800"
            title="Delete"
          >
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showModal = false"
    >
      <div class="relative max-w-md w-full bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <h3 class="text-base font-bold text-white">
            {{ isEditing ? 'Edit Education' : 'Add Education' }}
          </h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>

        <form @submit.prevent="saveEdu" class="space-y-4">
          <div>
            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Degree or Qualification</label>
            <input 
              v-model="form.degree_or_title" 
              type="text" 
              required
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">University / Institution</label>
            <input 
              v-model="form.institution" 
              type="text" 
              required
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Location</label>
              <input 
                v-model="form.location" 
                type="text" 
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Start Year</label>
              <input 
                v-model="form.start_year" 
                type="text" 
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold uppercase text-slate-400 mb-1">End Year</label>
              <input 
                v-model="form.end_year" 
                type="text" 
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Academic Highlights / Coursework</label>
            <textarea 
              v-model="form.description" 
              rows="4" 
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
            ></textarea>
          </div>

          <div class="pt-3 border-t border-slate-800 flex justify-end gap-2">
            <button 
              type="button" 
              @click="showModal = false"
              class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="px-5 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { Plus, Edit3, Trash2, X } from 'lucide-vue-next'

const educationList = ref([])
const feedback = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = ref({
  degree_or_title: '',
  institution: '',
  location: '',
  start_year: '',
  end_year: '',
  description: '',
})

async function loadEducation() {
  try {
    const res = await api.get('/admin/education')
    educationList.value = res.data.data || []
  } catch (e) {
    console.error('Failed loading education', e)
  }
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  form.value = {
    degree_or_title: '',
    institution: '',
    location: 'Ethiopia',
    start_year: '',
    end_year: '',
    description: '',
  }
  showModal.value = true
}

function openEditModal(edu) {
  isEditing.value = true
  editingId.value = edu.id
  form.value = {
    degree_or_title: edu.degree_or_title,
    institution: edu.institution,
    location: edu.location || '',
    start_year: edu.start_year || '',
    end_year: edu.end_year || '',
    description: edu.description || '',
  }
  showModal.value = true
}

async function saveEdu() {
  try {
    if (isEditing.value) {
      await api.put(`/admin/education/${editingId.value}`, form.value)
      feedback.value = 'Education entry updated.'
    } else {
      await api.post('/admin/education', form.value)
      feedback.value = 'Education entry added.'
    }
    showModal.value = false
    await loadEducation()
  } catch (e) {
    alert('Failed saving education.')
  }
}

async function deleteEdu(edu) {
  if (confirm(`Delete education record "${edu.degree_or_title}"?`)) {
    try {
      await api.delete(`/admin/education/${edu.id}`)
      feedback.value = 'Education entry deleted.'
      await loadEducation()
    } catch (e) {
      alert('Failed deleting education entry.')
    }
  }
}

onMounted(() => {
  loadEducation()
})
</script>
