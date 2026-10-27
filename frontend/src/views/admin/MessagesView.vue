<template>
  <div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Contact Inquiries</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Review, respond to, and manage communications from clients and collaborators.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button 
          @click="toggleUnreadFilter"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold transition-colors',
            filterUnread 
              ? 'bg-rose-500 text-white' 
              : 'bg-slate-800 text-slate-300 hover:text-white'
          ]"
        >
          Unread Only ({{ unreadCount }})
        </button>
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

    <!-- Search Input -->
    <div class="relative max-w-sm">
      <Search class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
      <input 
        v-model="searchQuery" 
        @input="debouncedSearch"
        type="text" 
        placeholder="Search sender, email, subject..." 
        class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs bg-slate-900 border border-slate-800 text-white placeholder:text-slate-500 focus:outline-none"
      />
    </div>

    <!-- Messages List -->
    <div class="rounded-3xl glass-panel border border-slate-800 light:border-slate-200 overflow-hidden shadow-xl">
      <div v-if="messages.length === 0" class="py-16 text-center text-slate-500 text-xs">
        No contact messages found.
      </div>

      <div v-else class="divide-y divide-slate-800/60 light:divide-slate-200/60">
        <div 
          v-for="msg in messages" 
          :key="msg.id"
          :class="[
            'p-5 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 cursor-pointer',
            !msg.is_read ? 'bg-blue-600/5' : 'hover:bg-slate-800/30'
          ]"
          @click="openMessageDetail(msg)"
        >
          <div class="space-y-1 overflow-hidden">
            <div class="flex items-center gap-2">
              <span 
                v-if="!msg.is_read" 
                class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0" 
                title="Unread message"
              ></span>
              <p class="text-sm font-bold text-white light:text-slate-900">{{ msg.name }}</p>
              <span class="text-xs text-blue-400 font-mono">&lt;{{ msg.email }}&gt;</span>
              <span v-if="msg.project_type" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ msg.project_type }}
              </span>
            </div>

            <p class="text-xs font-semibold text-slate-200 light:text-slate-800">{{ msg.subject }}</p>
            <p class="text-xs text-slate-400 line-clamp-1">{{ msg.message }}</p>
          </div>

          <div class="flex items-center gap-3 shrink-0 self-end sm:self-center" @click.stop>
            <span class="text-[11px] text-slate-500 font-mono">{{ formatDate(msg.created_at) }}</span>

            <button 
              @click="toggleRead(msg)"
              :class="[
                'p-1.5 rounded-lg transition-colors',
                msg.is_read ? 'text-slate-500 hover:text-slate-300' : 'text-blue-400 hover:text-blue-300'
              ]"
              :title="msg.is_read ? 'Mark Unread' : 'Mark Read'"
            >
              <MailCheck v-if="msg.is_read" class="w-4 h-4" />
              <Mail v-else class="w-4 h-4" />
            </button>

            <button 
              @click="deleteMsg(msg)"
              class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 transition-colors"
              title="Delete Message"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Message Detail Modal -->
    <div 
      v-if="activeMessage" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="activeMessage = null"
    >
      <div class="relative max-w-xl w-full bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
          <div>
            <span class="text-[10px] font-mono text-slate-500">{{ formatDate(activeMessage.created_at) }}</span>
            <h3 class="text-lg font-bold text-white mt-0.5">{{ activeMessage.subject }}</h3>
          </div>
          <button @click="activeMessage = null" class="text-slate-400 hover:text-white"><X class="w-5 h-5" /></button>
        </div>

        <div class="space-y-3 text-xs">
          <div class="flex items-center gap-2 text-slate-300">
            <span class="font-bold text-slate-500">From:</span>
            <span class="font-semibold text-white">{{ activeMessage.name }}</span>
            <a :href="'mailto:' + activeMessage.email" class="text-blue-400 hover:underline">
              &lt;{{ activeMessage.email }}&gt;
            </a>
          </div>

          <div v-if="activeMessage.project_type" class="flex items-center gap-2 text-slate-300">
            <span class="font-bold text-slate-500">Project Type:</span>
            <span class="px-2.5 py-0.5 rounded text-[11px] bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
              {{ activeMessage.project_type }}
            </span>
          </div>

          <div class="pt-3 border-t border-slate-800">
            <span class="font-bold text-slate-500 block mb-2">Message:</span>
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-slate-200 leading-relaxed whitespace-pre-line text-sm">
              {{ activeMessage.message }}
            </div>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
          <a 
            :href="`mailto:${activeMessage.email}?subject=Re: ${encodeURIComponent(activeMessage.subject)}`"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white"
          >
            <Send class="w-3.5 h-3.5" />
            <span>Reply via Email</span>
          </a>

          <button 
            @click="deleteMsg(activeMessage)"
            class="p-2 rounded-xl text-rose-400 hover:bg-rose-500/10"
            title="Delete this message"
          >
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { Search, Mail, MailCheck, Trash2, X, Send } from 'lucide-vue-next'

const messages = ref([])
const unreadCount = ref(0)
const feedback = ref('')
const filterUnread = ref(false)
const searchQuery = ref('')
const activeMessage = ref(null)
let searchTimeout = null

async function loadMessages() {
  try {
    let url = '/admin/messages?'
    if (filterUnread.value) url += 'unread=1&'
    if (searchQuery.value.trim()) url += `search=${encodeURIComponent(searchQuery.value)}&`

    const res = await api.get(url)
    messages.value = res.data.data || []
    unreadCount.value = res.data.unread_count || 0
  } catch (e) {
    console.error('Failed loading messages', e)
  }
}

function debouncedSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(loadMessages, 300)
}

function toggleUnreadFilter() {
  filterUnread.value = !filterUnread.value
  loadMessages()
}

function formatDate(iso) {
  if (!iso) return ''
  const d = new Date(iso)
  return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })
}

async function openMessageDetail(msg) {
  activeMessage.value = msg
  if (!msg.is_read) {
    try {
      await api.get(`/admin/messages/${msg.id}`)
      msg.is_read = true
      unreadCount.value = Math.max(0, unreadCount.value - 1)
    } catch (e) {
      //
    }
  }
}

async function toggleRead(msg) {
  try {
    const res = await api.patch(`/admin/messages/${msg.id}/toggle-read`)
    msg.is_read = res.data.data.is_read
    feedback.value = res.data.message
    await loadMessages()
  } catch (e) {
    //
  }
}

async function deleteMsg(msg) {
  if (confirm(`Delete message from ${msg.name}?`)) {
    try {
      await api.delete(`/admin/messages/${msg.id}`)
      feedback.value = 'Message deleted.'
      if (activeMessage.value && activeMessage.value.id === msg.id) {
        activeMessage.value = null
      }
      await loadMessages()
    } catch (e) {
      alert('Failed deleting message.')
    }
  }
}

onMounted(() => {
  loadMessages()
})
</script>
