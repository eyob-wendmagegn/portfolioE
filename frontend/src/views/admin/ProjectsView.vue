<template>
  <div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Project Management</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Create, edit, feature, and manage portfolio projects and screenshots.
        </p>
      </div>

      <button 
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto"
      >
        <Plus class="w-4 h-4" />
        <span>Add New Project</span>
      </button>
    </div>

    <!-- Alert / Toast Banner -->
    <div 
      v-if="feedbackMessage" 
      class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between"
    >
      <span>{{ feedbackMessage }}</span>
      <button @click="feedbackMessage = ''" class="text-emerald-400 hover:text-white"><X class="w-4 h-4" /></button>
    </div>

    <!-- Filter & Search Controls -->
    <div class="p-4 rounded-2xl glass-panel border border-slate-800 light:border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-3 w-full sm:w-auto">
        <select 
          v-model="filterStatus"
          class="px-3 py-2 rounded-xl text-xs bg-slate-900 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
        >
          <option value="all">All Statuses</option>
          <option value="published">Published</option>
          <option value="draft">Draft</option>
        </select>

        <select 
          v-model="filterCategory"
          class="px-3 py-2 rounded-xl text-xs bg-slate-900 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
        >
          <option value="all">All Categories</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <div class="w-full sm:w-64 relative">
        <Search class="w-4 h-4 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="searchKeyword" 
          type="text" 
          placeholder="Filter by title..." 
          class="w-full pl-9 pr-3 py-2 rounded-xl text-xs bg-slate-900 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 placeholder:text-slate-500 focus:outline-none"
        />
      </div>
    </div>

    <!-- Projects Table -->
    <div class="rounded-3xl glass-panel border border-slate-800 light:border-slate-200 overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-300 light:text-slate-700">
          <thead class="bg-slate-900/80 light:bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 light:border-slate-200">
            <tr>
              <th class="py-4 px-6">Project</th>
              <th class="py-4 px-4">Category</th>
              <th class="py-4 px-4">Technologies</th>
              <th class="py-4 px-4 text-center">Featured</th>
              <th class="py-4 px-4 text-center">Status</th>
              <th class="py-4 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 light:divide-slate-200/60">
            <tr v-if="filteredProjects.length === 0">
              <td colspan="6" class="py-12 text-center text-slate-500">
                No projects found. Click "Add New Project" above to create one.
              </td>
            </tr>
            <tr 
              v-for="project in filteredProjects" 
              :key="project.id"
              class="hover:bg-slate-800/30 light:hover:bg-slate-50 transition-colors"
            >
              <!-- Project Title & Image Thumbnail -->
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-10 rounded-lg overflow-hidden bg-slate-900 light:bg-slate-200 shrink-0 border border-slate-800">
                    <img 
                      v-if="project.cover_image_url" 
                      :src="project.cover_image_url" 
                      :alt="project.title"
                      class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-slate-600">
                      <FolderGit2 class="w-5 h-5" />
                    </div>
                  </div>
                  <div>
                    <p class="font-bold text-white light:text-slate-900 text-sm leading-tight">{{ project.title }}</p>
                    <p class="text-[10px] text-slate-500 font-mono mt-0.5">/projects/{{ project.slug }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="py-4 px-4">
                <span v-if="project.category" class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                  {{ project.category.name }}
                </span>
                <span v-else class="text-slate-500">—</span>
              </td>

              <!-- Technologies -->
              <td class="py-4 px-4">
                <div class="flex flex-wrap gap-1 max-w-xs">
                  <span 
                    v-for="t in project.technologies" 
                    :key="t.id"
                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-800 light:bg-slate-200 text-slate-300 light:text-slate-800"
                  >
                    {{ t.name }}
                  </span>
                </div>
              </td>

              <!-- Featured Toggle -->
              <td class="py-4 px-4 text-center">
                <button 
                  @click="toggleFeatured(project)"
                  class="p-1 rounded-lg hover:bg-slate-800 transition-colors"
                  :title="project.featured ? 'Unmark Featured' : 'Mark Featured'"
                >
                  <Star v-if="project.featured" class="w-4 h-4 text-amber-400 fill-amber-400" />
                  <Star v-else class="w-4 h-4 text-slate-600" />
                </button>
              </td>

              <!-- Status Toggle -->
              <td class="py-4 px-4 text-center">
                <button 
                  @click="toggleStatus(project)"
                  :class="[
                    'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase transition-colors',
                    project.status === 'published' 
                      ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' 
                      : 'bg-slate-800 text-slate-400 border border-slate-700'
                  ]"
                >
                  {{ project.status }}
                </button>
              </td>

              <!-- Action buttons -->
              <td class="py-4 px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button 
                    @click="openGalleryModal(project)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-colors"
                    title="Manage Gallery Images"
                  >
                    <Image class="w-4 h-4" />
                  </button>

                  <button 
                    @click="openEditModal(project)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-blue-400 hover:bg-slate-800 transition-colors"
                    title="Edit Project"
                  >
                    <Edit3 class="w-4 h-4" />
                  </button>

                  <button 
                    @click="confirmDelete(project)"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors"
                    title="Delete Project"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create / Edit Project Modal -->
    <div 
      v-if="showEditModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showEditModal = false"
    >
      <div class="relative max-w-3xl w-full max-h-[90vh] bg-slate-900 light:bg-white rounded-3xl border border-slate-800 light:border-slate-200 overflow-hidden flex flex-col shadow-2xl">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-800 light:border-slate-200 flex items-center justify-between">
          <h2 class="text-lg font-bold text-white light:text-slate-900">
            {{ isEditing ? 'Edit Project: ' + form.title : 'Create New Project' }}
          </h2>
          <button @click="showEditModal = false" class="text-slate-400 hover:text-white">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="saveProject" class="p-6 sm:p-8 overflow-y-auto space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Title -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                Project Title <span class="text-rose-400">*</span>
              </label>
              <input 
                v-model="form.title" 
                type="text" 
                required 
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              />
            </div>

            <!-- Slug -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                URL Slug (Optional auto-generated)
              </label>
              <input 
                v-model="form.slug" 
                type="text" 
                placeholder="e.g. library-management-system"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Category -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                Category
              </label>
              <select 
                v-model="form.category_id"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              >
                <option :value="null">None</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
            </div>

            <!-- Status -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                Publication Status
              </label>
              <select 
                v-model="form.status"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              >
                <option value="published">Published</option>
                <option value="draft">Draft</option>
              </select>
            </div>

            <!-- Featured Flag -->
            <div class="flex items-center gap-2 pt-6">
              <input 
                id="form-featured" 
                v-model="form.featured" 
                type="checkbox" 
                class="w-4 h-4 rounded text-blue-600 focus:ring-0"
              />
              <label for="form-featured" class="text-xs font-bold text-slate-300">Featured Showcase</label>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- GitHub URL -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                GitHub Repository URL
              </label>
              <input 
                v-model="form.github_url" 
                type="url" 
                placeholder="https://github.com/eyobnigata18/..."
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              />
            </div>

            <!-- Live URL -->
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                Live Demo URL
              </label>
              <input 
                v-model="form.live_url" 
                type="url" 
                placeholder="https://my-app.vercel.app"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none"
              />
            </div>
          </div>

          <!-- Cover Image File Upload -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Cover Image Upload
            </label>
            <div class="flex items-center gap-4">
              <input 
                type="file" 
                accept="image/*"
                @change="handleCoverImageSelect"
                class="text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500"
              />
              <span v-if="form.cover_image_url" class="text-[11px] text-emerald-400">Current cover loaded</span>
            </div>
          </div>

          <!-- Technologies Selector -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
              Select Technologies Used
            </label>
            <div class="flex flex-wrap gap-2 max-h-36 overflow-y-auto p-3 rounded-xl bg-slate-950 border border-slate-800">
              <label 
                v-for="tech in technologies" 
                :key="tech.id"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                :class="form.technology_ids.includes(tech.id) ? 'bg-blue-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'"
              >
                <input 
                  type="checkbox" 
                  :value="tech.id" 
                  v-model="form.technology_ids" 
                  class="sr-only"
                />
                <span>{{ tech.name }}</span>
              </label>
            </div>
          </div>

          <!-- Short Description -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Short Summary Description
            </label>
            <textarea 
              v-model="form.short_description" 
              rows="2"
              class="w-full px-3.5 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none resize-none"
            ></textarea>
          </div>

          <!-- Full Description -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
              Full Project Description & Architecture Details
            </label>
            <textarea 
              v-model="form.description" 
              rows="5"
              class="w-full px-3.5 py-2 rounded-xl text-xs bg-slate-950 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:outline-none resize-none"
            ></textarea>
          </div>

          <!-- Submit Buttons -->
          <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <button 
              type="button" 
              @click="showEditModal = false"
              class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-6 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white flex items-center gap-2"
            >
              <Loader2 v-if="saving" class="w-3.5 h-3.5 animate-spin" />
              <span>{{ isEditing ? 'Update Project' : 'Create Project' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Gallery Images Modal -->
    <div 
      v-if="showGalleryModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="showGalleryModal = false"
    >
      <div class="relative max-w-2xl w-full bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
          <h3 class="text-base font-bold text-white">
            Gallery Screenshots: {{ currentGalleryProject?.title }}
          </h3>
          <button @click="showGalleryModal = false" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>

        <!-- Upload Form -->
        <form @submit.prevent="uploadGalleryImage" class="space-y-3 p-4 rounded-2xl bg-slate-950 border border-slate-800">
          <label class="block text-xs font-bold uppercase text-slate-400">Add Gallery Screenshot</label>
          <div class="flex flex-col sm:flex-row gap-3">
            <input 
              type="file" 
              accept="image/*"
              required
              @change="handleGalleryFileSelect"
              class="text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white"
            />
            <input 
              v-model="galleryCaption" 
              type="text" 
              placeholder="Caption (e.g. User management screen)"
              class="flex-1 px-3 py-1.5 rounded-lg text-xs bg-slate-900 border border-slate-800 text-white"
            />
            <button 
              type="submit" 
              :disabled="galleryUploading"
              class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shrink-0"
            >
              Upload
            </button>
          </div>
        </form>

        <!-- Current Gallery List -->
        <div class="space-y-2 max-h-60 overflow-y-auto">
          <p class="text-xs font-bold text-slate-400">Current Screenshots ({{ currentGalleryProject?.images?.length || 0 }})</p>
          <div 
            v-for="img in currentGalleryProject?.images" 
            :key="img.id"
            class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between gap-3"
          >
            <div class="flex items-center gap-3 overflow-hidden">
              <img :src="img.image_url" class="w-12 h-8 object-cover rounded-md bg-slate-900" />
              <span class="text-xs text-slate-300 truncate">{{ img.caption || 'No caption' }}</span>
            </div>
            <button 
              @click="deleteGalleryImage(img.id)" 
              class="p-1 rounded text-rose-400 hover:bg-rose-500/10"
              title="Delete screenshot"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'
import { 
  Plus, Search, FolderGit2, Star, Image, Edit3, Trash2, X, Loader2 
} from 'lucide-vue-next'

const projects = ref([])
const categories = ref([])
const technologies = ref([])

const filterStatus = ref('all')
const filterCategory = ref('all')
const searchKeyword = ref('')
const feedbackMessage = ref('')

const showEditModal = ref(false)
const isEditing = ref(false)
const saving = ref(false)
const editingId = ref(null)

const form = ref({
  title: '',
  slug: '',
  short_description: '',
  description: '',
  category_id: null,
  featured: false,
  status: 'published',
  github_url: '',
  live_url: '',
  technology_ids: [],
  cover_image_url: null,
})
let coverImageFile = null

// Gallery
const showGalleryModal = ref(false)
const currentGalleryProject = ref(null)
const galleryCaption = ref('')
let galleryFile = null
const galleryUploading = ref(false)

const filteredProjects = computed(() => {
  return projects.value.filter(p => {
    if (filterStatus.value !== 'all' && p.status !== filterStatus.value) return false
    if (filterCategory.value !== 'all' && p.category_id !== filterCategory.value) return false
    if (searchKeyword.value.trim()) {
      const q = searchKeyword.value.toLowerCase()
      return p.title.toLowerCase().includes(q)
    }
    return true
  })
})

async function loadData() {
  try {
    const [pRes, cRes, tRes] = await Promise.all([
      api.get('/admin/projects'),
      api.get('/admin/categories'),
      api.get('/admin/technologies'),
    ])
    projects.value = pRes.data.data || []
    categories.value = cRes.data.data || []
    technologies.value = tRes.data.data || []
  } catch (err) {
    console.error('Failed loading projects', err)
  }
}

function openCreateModal() {
  isEditing.value = false
  editingId.value = null
  coverImageFile = null
  form.value = {
    title: '',
    slug: '',
    short_description: '',
    description: '',
    category_id: null,
    featured: false,
    status: 'published',
    github_url: '',
    live_url: '',
    technology_ids: [],
    cover_image_url: null,
  }
  showEditModal.value = true
}

function openEditModal(project) {
  isEditing.value = true
  editingId.value = project.id
  coverImageFile = null
  form.value = {
    title: project.title,
    slug: project.slug,
    short_description: project.short_description || '',
    description: project.description || '',
    category_id: project.category_id,
    featured: project.featured,
    status: project.status,
    github_url: project.github_url || '',
    live_url: project.live_url || '',
    technology_ids: (project.technologies || []).map(t => t.id),
    cover_image_url: project.cover_image_url,
  }
  showEditModal.value = true
}

function handleCoverImageSelect(e) {
  if (e.target.files && e.target.files[0]) {
    coverImageFile = e.target.files[0]
  }
}

async function saveProject() {
  saving.value = true
  try {
    const formData = new FormData()
    formData.append('title', form.value.title)
    if (form.value.slug) formData.append('slug', form.value.slug)
    formData.append('short_description', form.value.short_description)
    formData.append('description', form.value.description)
    if (form.value.category_id) formData.append('category_id', form.value.category_id)
    formData.append('featured', form.value.featured ? '1' : '0')
    formData.append('status', form.value.status)
    if (form.value.github_url) formData.append('github_url', form.value.github_url)
    if (form.value.live_url) formData.append('live_url', form.value.live_url)

    form.value.technology_ids.forEach((id, idx) => {
      formData.append(`technology_ids[${idx}]`, id)
    })

    if (coverImageFile) {
      formData.append('cover_image', coverImageFile)
    }

    if (isEditing.value) {
      await api.post(`/admin/projects/${editingId.value}`, formData)
      feedbackMessage.value = 'Project updated successfully.'
    } else {
      await api.post('/admin/projects', formData)
      feedbackMessage.value = 'Project created successfully.'
    }

    showEditModal.value = false
    await loadData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed saving project.')
  } finally {
    saving.value = false
  }
}

async function toggleFeatured(project) {
  try {
    await api.put(`/admin/projects/${project.id}`, {
      title: project.title,
      featured: !project.featured,
      status: project.status,
    })
    project.featured = !project.featured
    feedbackMessage.value = `Updated featured status for "${project.title}".`
  } catch (e) {
    //
  }
}

async function toggleStatus(project) {
  const newStatus = project.status === 'published' ? 'draft' : 'published'
  try {
    await api.put(`/admin/projects/${project.id}`, {
      title: project.title,
      status: newStatus,
      featured: project.featured,
    })
    project.status = newStatus
    feedbackMessage.value = `Project marked as ${newStatus}.`
  } catch (e) {
    //
  }
}

async function confirmDelete(project) {
  if (confirm(`Are you sure you want to delete project "${project.title}"? This cannot be undone.`)) {
    try {
      await api.delete(`/admin/projects/${project.id}`)
      feedbackMessage.value = 'Project deleted successfully.'
      await loadData()
    } catch (e) {
      alert('Failed deleting project.')
    }
  }
}

function openGalleryModal(project) {
  currentGalleryProject.value = project
  galleryFile = null
  galleryCaption.value = ''
  showGalleryModal.value = true
}

function handleGalleryFileSelect(e) {
  if (e.target.files && e.target.files[0]) {
    galleryFile = e.target.files[0]
  }
}

async function uploadGalleryImage() {
  if (!galleryFile || !currentGalleryProject.value) return
  galleryUploading.value = true
  try {
    const fd = new FormData()
    fd.append('image', galleryFile)
    if (galleryCaption.value) fd.append('caption', galleryCaption.value)

    await api.post(`/admin/projects/${currentGalleryProject.value.id}/images`, fd)
    galleryCaption.value = ''
    galleryFile = null
    // Refresh
    const res = await api.get(`/admin/projects/${currentGalleryProject.value.id}`)
    currentGalleryProject.value = res.data.data
    await loadData()
  } catch (e) {
    alert('Failed uploading gallery image.')
  } finally {
    galleryUploading.value = false
  }
}

async function deleteGalleryImage(imgId) {
  if (confirm('Delete this gallery screenshot?')) {
    try {
      await api.delete(`/admin/projects/images/${imgId}`)
      const res = await api.get(`/admin/projects/${currentGalleryProject.value.id}`)
      currentGalleryProject.value = res.data.data
      await loadData()
    } catch (e) {
      alert('Failed deleting gallery image.')
    }
  }
}

onMounted(() => {
  loadData()
})
</script>
