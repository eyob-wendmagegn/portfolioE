<template>
  <div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Services & Offerings</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Manage development services, descriptions, and call-to-actions.
        </p>
      </div>

      <button 
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto"
      >
        <Plus class="w-4 h-4" />
        <span>Add Service</span>
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

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="service in services" 
        :key="service.id"
        class="p-6 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl flex flex-col justify-between space-y-4"
      >
        <div>
          <div class="flex items-center justify-between gap-3 mb-3">
            <h3 class="text-base font-bold text-white light:text-slate-900">{{ service.title }}</h3>
            <span 
              :class="[
                'px-2 py-0.5 rounded text-[10px] font-bold uppercase',
                service.is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-400'
              ]"
            >
              {{ service.is_active ? 'Active' : 'Hidden' }}
            </span>
          </div>

          <p class="text-xs text-slate-400 light:text-slate-600 leading-relaxed">
            {{ service.description }}
          </p>
        </div>

        <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
          <span class="text-[11px] text-blue-400 font-medium">CTA: {{ service.action_text || 'None' }}</span>
          
          <div class="flex items-center gap-1">
            <button 
              @click="openEditModal(service)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-blue-400 hover:bg-slate-800"
              title="Edit Service"
            >
              <Edit3 class="w-4 h-4" />
            </button>
            <button 
              @click="deleteService(service)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800"
              title="Delete Service"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
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
            {{ isEditing ? 'Edit Service' : 'Add Service' }}
          </h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>

        <form @submit.prevent="saveService" class="space-y-4">
          <div>
            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Service Title</label>
            <input 
              v-model="form.title" 
              type="text" 
              required
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Description</label>
            <textarea 
              v-model="form.description" 
              rows="4" 
              required
              class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none resize-none"
            ></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Action Button Text</label>
              <input 
                v-model="form.action_text" 
                type="text" 
                placeholder="e.g. Inquire Details"
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Action Link URL</label>
              <input 
                v-model="form.action_url" 
                type="text" 
                placeholder="#contact"
                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
          </div>

          <div class="flex items-center gap-2">
            <input id="srv-active" v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded text-blue-600" />
            <label for="srv-active" class="text-xs text-slate-300">Active and visible</label>
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

const services = ref([])
const feedback = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = ref({
  title: '',
  description: '',
  action_text: '',
  action_url: '#contact',
  is_active: true,
})

async function loadServices() {
  try {
    const res = await api.get('/admin/services')
    services.value = res.data.data || []
  } catch (e) {
    console.error('Failed loading services', e)
  }
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  form.value = {
    title: '',
    description: '',
    action_text: 'Get in Touch',
    action_url: '#contact',
    is_active: true,
  }
  showModal.value = true
}

function openEditModal(srv) {
  isEditing.value = true
  editingId.value = srv.id
  form.value = {
    title: srv.title,
    description: srv.description,
    action_text: srv.action_text || '',
    action_url: srv.action_url || '#contact',
    is_active: srv.is_active,
  }
  showModal.value = true
}

async function saveService() {
  try {
    if (isEditing.value) {
      await api.put(`/admin/services/${editingId.value}`, form.value)
      feedback.value = 'Service updated successfully.'
    } else {
      await api.post('/admin/services', form.value)
      feedback.value = 'Service created successfully.'
    }
    showModal.value = false
    await loadServices()
  } catch (e) {
    alert('Failed saving service.')
  }
}

async function deleteService(srv) {
  if (confirm(`Delete service "${srv.title}"?`)) {
    try {
      await api.delete(`/admin/services/${srv.id}`)
      feedback.value = 'Service deleted successfully.'
      await loadServices()
    } catch (e) {
      alert('Failed deleting service.')
    }
  }
}

onMounted(() => {
  loadServices()
})
</script>
