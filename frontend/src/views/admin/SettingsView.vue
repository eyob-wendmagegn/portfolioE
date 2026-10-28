<template>
  <div class="space-y-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Site Settings & CV File</h1>
        <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
          Manage brand configuration, downloadable CV document, and system preferences.
        </p>
      </div>

      <button 
        @click="saveSettings" 
        :disabled="saving"
        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md transition-all self-start sm:self-auto disabled:opacity-50"
      >
        <Loader2 v-if="saving" class="w-3.5 h-3.5 animate-spin" />
        <Save v-else class="w-3.5 h-3.5" />
        <span>Save Settings</span>
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

    <!-- CV / Resume Document Management -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <FileText class="w-5 h-5 text-blue-400" />
        <span>Downloadable Curriculum Vitae (CV / Resume)</span>
      </h2>

      <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-center sm:text-left">
          <p class="text-xs font-bold text-white">Current CV File Status</p>
          <div class="flex items-center gap-2 justify-center sm:justify-start">
            <span 
              :class="[
                'w-2 h-2 rounded-full',
                settings.cv_file_path ? 'bg-emerald-400' : 'bg-amber-400'
              ]"
            ></span>
            <span class="text-xs font-mono text-slate-300">
              {{ settings.cv_file_path ? 'CV file uploaded and ready for public download' : 'No custom CV uploaded yet (using API fallback)' }}
            </span>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a 
            v-if="settings.cv_file_url" 
            :href="settings.cv_file_url" 
            target="_blank" 
            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-200 hover:bg-slate-700 flex items-center gap-1.5"
          >
            <Download class="w-3.5 h-3.5" />
            <span>Test Download</span>
          </a>

          <button 
            v-if="settings.cv_file_path" 
            @click="removeCv"
            class="p-2 rounded-xl text-rose-400 hover:bg-rose-500/10"
            title="Remove CV document"
          >
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- CV Upload Control -->
      <div>
        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Upload New CV File (PDF / DOC)</label>
        <div class="flex items-center gap-3">
          <input 
            type="file" 
            accept=".pdf,.doc,.docx"
            @change="handleCvFileSelect"
            class="text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-semibold hover:file:bg-blue-500"
          />
          <button 
            v-if="cvFile"
            @click="uploadCv"
            :disabled="cvUploading"
            class="px-5 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white flex items-center gap-1.5"
          >
            <Loader2 v-if="cvUploading" class="w-3.5 h-3.5 animate-spin" />
            <span>Confirm Upload</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Branding & Meta Settings -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl space-y-6">
      <h2 class="text-base font-bold text-white light:text-slate-900 flex items-center gap-2 border-b border-slate-800 pb-3">
        <Sliders class="w-5 h-5 text-indigo-400" />
        <span>General Site Configuration</span>
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Website Title</label>
          <input 
            v-model="settings.site_title" 
            type="text" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Tagline</label>
          <input 
            v-model="settings.site_tagline" 
            type="text" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Color Theme</label>
          <div class="px-3.5 py-2.5 rounded-xl text-xs bg-slate-950/60 border border-slate-800 text-slate-300 flex items-center justify-between">
            <span>Dark Theme (Deep Midnight Navy)</span>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">Enforced</span>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase text-slate-400 mb-1">SEO Keywords</label>
          <input 
            v-model="settings.seo_keywords" 
            type="text" 
            class="w-full px-3.5 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Real-Time Email Notifications & Testing Card -->
    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 shadow-xl space-y-6">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h2 class="text-base font-bold text-white flex items-center gap-2">
          <Bell class="w-5 h-5 text-amber-400" />
          <span>Real-Time Phone Email Notifications</span>
        </h2>
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
          Push Alerts
        </span>
      </div>

      <p class="text-xs text-slate-400 leading-relaxed">
        When a visitor submits the contact form on your portfolio, the system dispatches an instant email alert to your mobile phone inbox with direct reply capability.
      </p>

      <!-- Test Notification Box -->
      <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-4">
        <div>
          <label class="block text-xs font-bold uppercase text-slate-300 mb-1.5">
            Send Test Real-Time Push Notification
          </label>
          <div class="flex flex-col sm:flex-row gap-3">
            <input 
              v-model="testEmailAddress" 
              type="email" 
              placeholder="e.g. eyobwende18@gmail.com" 
              class="flex-1 px-3.5 py-2.5 rounded-xl text-xs bg-slate-900 border border-slate-800 text-white focus:outline-none focus:border-blue-500"
            />
            <button 
              type="button"
              @click="sendTestNotification" 
              :disabled="sendingTest || !testEmailAddress.trim()"
              class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-md disabled:opacity-50 transition-all shrink-0"
            >
              <Loader2 v-if="sendingTest" class="w-3.5 h-3.5 animate-spin" />
              <Send v-else class="w-3.5 h-3.5" />
              <span>{{ sendingTest ? 'Sending Alert...' : 'Test Phone Alert' }}</span>
            </button>
          </div>
        </div>

        <!-- Test Status Banner -->
        <div 
          v-if="testResult" 
          :class="[
            'p-3 rounded-xl text-xs flex items-center gap-2',
            testError ? 'bg-rose-500/10 text-rose-300 border border-rose-500/30' : 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/30'
          ]"
        >
          <span>{{ testResult }}</span>
        </div>

        <!-- Quick SMTP Instruction Helper -->
        <div class="pt-3 border-t border-slate-800/80 text-[11px] text-slate-400 space-y-1.5">
          <p class="font-bold text-slate-300">💡 To receive real-time push alerts on your phone with Gmail SMTP:</p>
          <ol class="list-decimal list-inside space-y-1 pl-1 text-slate-400">
            <li>Generate a 16-character Google App Password in your Google Account security settings.</li>
            <li>In <code class="text-blue-400 bg-slate-900 px-1 py-0.5 rounded">backend/.env</code>, configure:
              <span class="text-slate-300 block font-mono text-[10px] bg-slate-900/90 p-2 rounded mt-1 border border-slate-800">
                MAIL_MAILER=smtp<br>
                MAIL_HOST=smtp.gmail.com<br>
                MAIL_PORT=587<br>
                MAIL_ENCRYPTION=tls<br>
                MAIL_USERNAME=your_email@gmail.com<br>
                MAIL_PASSWORD=your_16_char_app_password<br>
                MAIL_FROM_ADDRESS=your_email@gmail.com
              </span>
            </li>
            <li>In your phone's Gmail app: ensure Notifications are enabled for "All new email" (not just "High priority only").</li>
          </ol>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { FileText, Download, Trash2, Sliders, Save, Loader2, X, Bell, Send } from 'lucide-vue-next'

const settings = ref({})
const feedback = ref('')
const saving = ref(false)
const cvFile = ref(null)
const cvUploading = ref(false)

const testEmailAddress = ref('eyobwende18@gmail.com')
const sendingTest = ref(false)
const testResult = ref('')
const testError = ref(false)

async function sendTestNotification() {
  if (!testEmailAddress.value.trim()) return

  sendingTest.value = true
  testResult.value = ''
  testError.value = false

  try {
    const res = await api.post('/admin/settings/test-email', {
      email: testEmailAddress.value.trim()
    })
    testResult.value = res.data.message
  } catch (err) {
    testError.value = true
    testResult.value = err.response?.data?.message || 'Failed delivering test notification. Check SMTP configuration in backend/.env.'
  } finally {
    sendingTest.value = false
  }
}

async function loadSettings() {
  try {
    const res = await api.get('/admin/settings')
    settings.value = res.data.data || {}
  } catch (e) {
    console.error('Failed loading settings', e)
  }
}

function handleCvFileSelect(e) {
  if (e.target.files && e.target.files[0]) {
    cvFile.value = e.target.files[0]
  }
}

async function uploadCv() {
  if (!cvFile.value) return
  cvUploading.value = true
  try {
    const fd = new FormData()
    fd.append('cv', cvFile.value)
    const res = await api.post('/admin/settings/cv', fd)
    settings.value.cv_file_path = res.data.cv_file_path
    settings.value.cv_file_url = res.data.cv_file_url
    cvFile.value = null
    feedback.value = 'CV document uploaded successfully.'
  } catch (e) {
    alert('Failed uploading CV document.')
  } finally {
    cvUploading.value = false
  }
}

async function removeCv() {
  if (confirm('Delete the current CV file?')) {
    try {
      await api.delete('/admin/settings/cv')
      settings.value.cv_file_path = null
      settings.value.cv_file_url = null
      feedback.value = 'CV document removed.'
    } catch (e) {
      alert('Failed removing CV.')
    }
  }
}

async function saveSettings() {
  saving.value = true
  try {
    const res = await api.post('/admin/settings', { settings: settings.value })
    settings.value = res.data.data
    feedback.value = 'Settings saved successfully.'
  } catch (e) {
    alert('Failed saving settings.')
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  loadSettings()
})
</script>
