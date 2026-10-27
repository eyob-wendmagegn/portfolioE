<template>
  <div 
    v-if="project" 
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md animate-fade-in"
    @click.self="$emit('close')"
  >
    <div 
      class="relative max-w-4xl w-full max-h-[90vh] bg-slate-900 light:bg-white rounded-3xl border border-slate-800 light:border-slate-200 shadow-2xl overflow-hidden flex flex-col"
    >
      <!-- Header -->
      <div class="p-6 border-b border-slate-800 light:border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span 
            v-if="project.category" 
            class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 light:text-blue-600 border border-blue-500/20"
          >
            {{ project.category.name }}
          </span>
          <span v-if="project.project_date" class="text-xs text-slate-400 font-mono">
            {{ project.project_date }}
          </span>
        </div>

        <button 
          @click="$emit('close')" 
          class="p-2 rounded-xl text-slate-400 hover:text-white light:hover:text-black hover:bg-slate-800 light:hover:bg-slate-100 transition-colors"
          aria-label="Close Modal"
        >
          <X class="w-5 h-5" />
        </button>
      </div>

      <!-- Scrollable Body -->
      <div class="p-6 sm:p-8 overflow-y-auto space-y-6">
        <!-- Title -->
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white light:text-slate-900">
          {{ project.title }}
        </h2>

        <!-- Cover Image -->
        <div 
          v-if="project.cover_image_url" 
          class="aspect-video w-full rounded-2xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-950"
        >
          <img 
            :src="project.cover_image_url" 
            :alt="project.title" 
            class="w-full h-full object-cover"
          />
        </div>

        <!-- Tech Stack -->
        <div>
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Technologies Used</h4>
          <div class="flex flex-wrap gap-2">
            <span 
              v-for="tech in project.technologies" 
              :key="tech.id"
              class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-300 light:text-blue-700 border border-blue-500/20"
            >
              {{ tech.name }}
            </span>
          </div>
        </div>

        <!-- Detailed Description -->
        <div>
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Description & Architecture</h4>
          <div class="prose prose-invert light:prose-slate max-w-none text-sm text-slate-300 light:text-slate-700 leading-relaxed whitespace-pre-line">
            {{ project.description || project.short_description }}
          </div>
        </div>

        <!-- Gallery Images if any -->
        <div v-if="project.images && project.images.length > 0">
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Project Screenshots & Gallery</h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div 
              v-for="img in project.images" 
              :key="img.id"
              class="rounded-xl overflow-hidden border border-slate-800 light:border-slate-200 bg-slate-950 aspect-video group relative"
            >
              <img 
                :src="img.image_url" 
                :alt="img.alt_text || 'Screenshot'" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div v-if="img.caption" class="absolute bottom-0 inset-x-0 p-2 text-[11px] bg-black/70 text-white truncate">
                {{ img.caption }}
              </div>
            </div>
          </div>
        </div>

        <!-- Action Links -->
        <div class="pt-4 border-t border-slate-800 light:border-slate-200 flex flex-wrap gap-3">
          <a 
            v-if="project.live_url" 
            :href="project.live_url" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all"
          >
            <ExternalLink class="w-4 h-4" />
            <span>Visit Live Application</span>
          </a>

          <a 
            v-if="project.github_url" 
            :href="project.github_url" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold bg-slate-800 light:bg-slate-100 hover:bg-slate-700 text-slate-200 light:text-slate-800 border border-slate-700 light:border-slate-300 transition-all"
          >
            <Github class="w-4 h-4" />
            <span>View Source Code on GitHub</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { X, ExternalLink, Github } from 'lucide-vue-next'

defineProps({
  project: {
    type: Object,
    default: null,
  },
})

defineEmits(['close'])
</script>
