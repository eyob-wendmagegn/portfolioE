<template>
  <div class="space-y-6 max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Skills & Technologies</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Manage technical capabilities, categories, proficiency levels, and ordering.
        </p>
      </div>

      <button 
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto"
      >
        <Plus class="w-4 h-4" />
        <span>Add New Skill</span>
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

    <!-- Grouped Skills by Category -->
    <div class="space-y-8">
      <div 
        v-for="(skillList, categoryName) in groupedSkills" 
        :key="categoryName"
        class="rounded-3xl glass-panel border border-slate-800 light:border-slate-200 p-6 sm:p-8 space-y-4 shadow-xl"
      >
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <h2 class="text-base font-bold text-white light:text-slate-900">{{ categoryName }}</h2>
          <span class="text-xs text-slate-400 font-mono">{{ skillList.length }} entries</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div 
            v-for="skill in skillList" 
            :key="skill.id"
            class="p-4 rounded-2xl bg-slate-950/70 light:bg-slate-50 border border-slate-800/80 light:border-slate-200 flex items-start justify-between gap-3 group"
          >
            <div>
              <div class="flex items-center gap-2">
                <span class="font-bold text-sm text-white light:text-slate-900">{{ skill.name }}</span>
                <span v-if="skill.level" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-500/10 text-blue-400">
                  {{ skill.level }}
                </span>
              </div>
              <p v-if="skill.description" class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                {{ skill.description }}
              </p>
            </div>

            <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 shrink-0">
              <button 
                @click="openEditModal(skill)"
                class="p-1 rounded text-slate-400 hover:text-blue-400 hover:bg-slate-800"
                title="Edit Skill"
              >
                <Edit3 class="w-4 h-4" />
              </button>
              <button 
                @click="deleteSkill(skill)"
                class="p-1 rounded text-slate-400 hover:text-rose-400 hover:bg-slate-800"
                title="Delete Skill"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showModal = false"
    >
      <div class="relative max-w-md w-full bg-slate-900 light:bg-white rounded-3xl border border-slate-800 light:border-slate-200 p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <h3 class="text-base font-bold text-white light:text-slate-900">
            {{ isEditing ? 'Edit Skill: ' + form.name : 'Add New Skill' }}
          </h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>

        <form @submit.prevent="saveSkill" class="space-y-4">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Skill Name <span class="text-rose-400">*</span>
            </label>
            <input 
              v-model="form.name" 
              type="text" 
              required
              placeholder="e.g. Laravel"
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Category <span class="text-rose-400">*</span>
            </label>
            <select 
              v-model="form.category" 
              required
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
            >
              <option value="Backend Development">Backend Development</option>
              <option value="Frontend Development">Frontend Development</option>
              <option value="Additional Technologies">Additional Technologies</option>
              <option value="DevOps & Tools">DevOps & Tools</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Optional Proficiency Level
            </label>
            <input 
              v-model="form.level" 
              type="text" 
              placeholder="e.g. Advanced, Proficient, Intermediate"
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Short Description
            </label>
            <textarea 
              v-model="form.description" 
              rows="3"
              placeholder="Key concepts or workflows applied with this technology..."
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none resize-none"
            ></textarea>
          </div>

          <div class="flex items-center gap-2">
            <input id="skill-visible" v-model="form.is_visible" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
            <label for="skill-visible" class="text-xs text-slate-300">Visible on public portfolio</label>
          </div>

          <div class="pt-3 border-t border-slate-800 flex justify-end gap-2">
            <button 
              type="button" 
              @click="showModal = false"
              class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-5 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white"
            >
              {{ isEditing ? 'Save Changes' : 'Create Skill' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'
import { Plus, Edit3, Trash2, X } from 'lucide-vue-next'

const skills = ref([])
const feedback = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)
const saving = ref(false)

const form = ref({
  name: '',
  category: 'Backend Development',
  level: '',
  description: '',
  is_visible: true,
})

const groupedSkills = computed(() => {
  const map = {}
  skills.value.forEach(s => {
    const cat = s.category || 'Other'
    if (!map[cat]) map[cat] = []
    map[cat].push(s)
  })
  return map
})

async function loadSkills() {
  try {
    const res = await api.get('/admin/skills')
    skills.value = res.data.data || []
  } catch (e) {
    console.error('Failed loading skills', e)
  }
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  form.value = {
    name: '',
    category: 'Backend Development',
    level: '',
    description: '',
    is_visible: true,
  }
  showModal.value = true
}

function openEditModal(skill) {
  isEditing.value = true
  editingId.value = skill.id
  form.value = {
    name: skill.name,
    category: skill.category,
    level: skill.level || '',
    description: skill.description || '',
    is_visible: skill.is_visible,
  }
  showModal.value = true
}

async function saveSkill() {
  saving.value = true
  try {
    if (isEditing.value) {
      await api.put(`/admin/skills/${editingId.value}`, form.value)
      feedback.value = 'Skill updated successfully.'
    } else {
      await api.post('/admin/skills', form.value)
      feedback.value = 'Skill created successfully.'
    }
    showModal.value = false
    await loadSkills()
  } catch (err) {
    alert('Failed saving skill.')
  } finally {
    saving.value = false
  }
}

async function deleteSkill(skill) {
  if (confirm(`Delete skill "${skill.name}"?`)) {
    try {
      await api.delete(`/admin/skills/${skill.id}`)
      feedback.value = 'Skill deleted successfully.'
      await loadSkills()
    } catch (e) {
      alert('Failed deleting skill.')
    }
  }
}

onMounted(() => {
  loadSkills()
})
</script>
