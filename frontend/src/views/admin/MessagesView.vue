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
              <span v-if="msg.replied_at" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 flex items-center gap-1">
                <Check class="w-3 h-3 text-emerald-400" />
                <span>Replied</span>
              </span>
            </div>

            <p class="text-xs font-semibold text-slate-200 light:text-slate-800">{{ msg.subject }}</p>
            <p class="text-xs text-slate-400 line-clamp-1">{{ msg.message }}</p>
          </div>

          <div class="flex items-center gap-2 shrink-0 self-end sm:self-center" @click.stop>
            <span class="text-[11px] text-slate-500 font-mono">{{ formatDate(msg.created_at) }}</span>

            <!-- Quick Copy Email -->
            <button 
              @click="copyEmailAddress(msg.email)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-white transition-colors"
              :title="copiedEmail === msg.email ? 'Email Copied!' : 'Copy ' + msg.email"
            >
              <Check v-if="copiedEmail === msg.email" class="w-4 h-4 text-emerald-400" />
              <Copy v-else class="w-4 h-4" />
            </button>

            <!-- Quick Gmail Reply -->
            <a 
              :href="getGmailUrl(msg)"
              target="_blank"
              rel="noopener noreferrer"
              class="p-1.5 rounded-lg text-slate-400 hover:text-blue-400 transition-colors"
              title="Reply in Gmail"
            >
              <ExternalLink class="w-4 h-4" />
            </a>

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
          <div class="flex flex-wrap items-center gap-2 text-slate-300">
            <span class="font-bold text-slate-500">From:</span>
            <span class="font-semibold text-white">{{ activeMessage.name }}</span>
            <span class="text-blue-400 font-mono">&lt;{{ activeMessage.email }}&gt;</span>
            <button 
              type="button"
              @click="copyEmailAddress(activeMessage.email)"
              class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 ml-1 transition-colors"
              title="Copy email address"
            >
              <Check v-if="copiedEmail === activeMessage.email" class="w-3 h-3 text-emerald-400" />
              <Copy v-else class="w-3 h-3 text-slate-400" />
              <span>{{ copiedEmail === activeMessage.email ? 'Copied!' : 'Copy' }}</span>
            </button>
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

          <!-- Existing Reply History if present -->
          <div v-if="activeMessage.replied_at" class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs space-y-1">
            <div class="flex items-center gap-1.5 text-emerald-400 font-bold">
              <Check class="w-3.5 h-3.5" />
              <span>You replied on {{ formatDate(activeMessage.replied_at) }}:</span>
            </div>
            <p class="text-slate-300 whitespace-pre-line pl-5">{{ activeMessage.reply_message }}</p>
          </div>

          <!-- Direct In-App Reply Form -->
          <div class="pt-3 border-t border-slate-800 space-y-2">
            <div class="flex items-center justify-between">
              <label class="font-bold text-slate-300 text-xs flex items-center gap-1.5">
                <Send class="w-3.5 h-3.5 text-blue-400" />
                <span>Send Direct Email Reply:</span>
              </label>
              <span class="text-[10px] text-slate-500 font-mono">Delivered to {{ activeMessage.email }}</span>
            </div>

            <textarea 
              v-model="replyText" 
              rows="3" 
              placeholder="Write your reply directly to this client..."
              class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none focus:border-blue-500 transition-colors resize-none"
            ></textarea>

            <div class="flex flex-wrap items-center justify-between gap-2">
              <button 
                type="button" 
                @click="sendDirectReply"
                :disabled="sendingReply || !replyText.trim()"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white shadow-md shadow-blue-500/20 transition-all"
              >
                <Loader2 v-if="sendingReply" class="w-3.5 h-3.5 animate-spin" />
                <Send v-else class="w-3.5 h-3.5" />
                <span>{{ sendingReply ? 'Delivering Email...' : 'Send Direct Reply' }}</span>
              </button>

              <span v-if="replyStatus" :class="['text-xs font-semibold', replyError ? 'text-rose-400' : 'text-emerald-400']">
                {{ replyStatus }}
              </span>
            </div>
          </div>
        </div>

        <!-- Alternate Action Buttons: Gmail compose, Copy Email, Mail App -->
        <div class="pt-4 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3">
          <div class="flex flex-wrap items-center gap-2">
            <!-- Direct Gmail Web Compose -->
            <a 
              :href="getGmailUrl(activeMessage)"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition-colors"
              title="Open web Gmail compose"
            >
              <ExternalLink class="w-3.5 h-3.5 text-blue-400" />
              <span>Open in Gmail</span>
            </a>

            <!-- Copy Email Button -->
            <button 
              type="button"
              @click="copyEmailAddress(activeMessage.email)"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition-colors"
            >
              <Check v-if="copiedEmail === activeMessage.email" class="w-3.5 h-3.5 text-emerald-400" />
              <Copy v-else class="w-3.5 h-3.5 text-slate-400" />
              <span>{{ copiedEmail === activeMessage.email ? 'Copied!' : 'Copy Email' }}</span>
            </button>

            <!-- Desktop Mail App Fallback -->
            <a 
              :href="`mailto:${activeMessage.email}?subject=Re: ${encodeURIComponent(activeMessage.subject)}`"
              class="px-2.5 py-1.5 rounded-lg text-xs text-slate-400 hover:text-slate-200 transition-colors"
              title="Open default desktop mail client"
            >
              Mail App
            </a>
          </div>

          <button 
            @click="deleteMsg(activeMessage)"
            class="p-2 rounded-xl text-rose-400 hover:bg-rose-500/10 transition-colors"
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
import { getGmailComposeUrl } from '@/utils/url'
import { Search, Mail, MailCheck, Trash2, X, Send, Copy, Check, ExternalLink, Loader2 } from 'lucide-vue-next'

const messages = ref([])
const unreadCount = ref(0)
const feedback = ref('')
const filterUnread = ref(false)
const searchQuery = ref('')
const activeMessage = ref(null)
const copiedEmail = ref('')
const replyText = ref('')
const sendingReply = ref(false)
const replyStatus = ref('')
const replyError = ref(false)
let searchTimeout = null

async function sendDirectReply() {
  if (!activeMessage.value || !replyText.value.trim()) return

  sendingReply.value = true
  replyStatus.value = ''
  replyError.value = false

  try {
    const res = await api.post(`/admin/messages/${activeMessage.value.id}/reply`, {
      reply: replyText.value.trim()
    })

    replyStatus.value = 'Reply sent successfully!'
    activeMessage.value.replied_at = res.data.data.replied_at || new Date().toISOString()
    activeMessage.value.reply_message = replyText.value.trim()
    replyText.value = ''

    // Refresh list
    await loadMessages()
  } catch (err) {
    replyError.value = true
    replyStatus.value = err.response?.data?.message || 'Failed delivering email. Check SMTP settings.'
  } finally {
    sendingReply.value = false
  }
}

function getGmailUrl(msg) {
  if (!msg) return ''
  const quote = `\n\n--- Original Message from ${msg.name} (${msg.email}) ---\nSubject: ${msg.subject}\n\n${msg.message}`
  return getGmailComposeUrl({
    to: msg.email,
    subject: `Re: ${msg.subject}`,
    body: quote
  })
}

async function copyEmailAddress(email) {
  if (!email) return
  try {
    await navigator.clipboard.writeText(email)
    copiedEmail.value = email
    setTimeout(() => {
      if (copiedEmail.value === email) copiedEmail.value = ''
    }, 2500)
  } catch (err) {
    // fallback
  }
}

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
  replyText.value = ''
  replyStatus.value = ''
  replyError.value = false
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
