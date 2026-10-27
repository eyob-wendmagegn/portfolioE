<template>
  <section id="contact" class="py-24 relative overflow-hidden">
    <!-- Ambient glow -->
    <div class="glow-blob-blue w-96 h-96 -bottom-20 -left-20 opacity-40"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 light:bg-blue-50 border border-blue-500/20 text-blue-400 light:text-blue-600 text-xs font-bold tracking-widest uppercase mb-3">
          <Send class="w-3.5 h-3.5" />
          <span>Get in Touch</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white light:text-slate-900 mb-4">
          Let's Collaborate
        </h2>
        <p class="text-sm sm:text-base text-slate-400 light:text-slate-600">
          Have an exciting project, contract inquiry, or full-stack opportunity? Send me a message and let's discuss details.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left Column: Contact Information Cards -->
        <div class="lg:col-span-5 space-y-6">
          <div class="p-8 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-xl space-y-6">
            <h3 class="text-xl font-bold text-white light:text-slate-900">
              Contact Details
            </h3>
            <p class="text-xs sm:text-sm text-slate-400 light:text-slate-600 leading-relaxed">
              Feel free to reach out via direct email, phone, or by submitting the secure form. I aim to reply within 24 to 48 hours.
            </p>

            <div class="space-y-4">
              <!-- Email Card -->
              <a 
                :href="'mailto:' + emailAddress" 
                class="flex items-center gap-4 p-4 rounded-2xl bg-slate-900/60 light:bg-slate-50 border border-slate-800/60 light:border-slate-200/60 hover:border-blue-500/40 transition-colors group"
              >
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                  <Mail class="w-5 h-5" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Email Address</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900 group-hover:text-blue-400 transition-colors">
                    {{ emailAddress }}
                  </p>
                </div>
              </a>

              <!-- Phone Card -->
              <div 
                v-if="phoneNumber"
                class="flex items-center gap-4 p-4 rounded-2xl bg-slate-900/60 light:bg-slate-50 border border-slate-800/60 light:border-slate-200/60"
              >
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                  <Phone class="w-5 h-5" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Phone / WhatsApp</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    {{ phoneNumber }}
                  </p>
                </div>
              </div>

              <!-- Location Card -->
              <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-900/60 light:bg-slate-50 border border-slate-800/60 light:border-slate-200/60">
                <div class="w-11 h-11 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                  <MapPin class="w-5 h-5" />
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-medium">Location</p>
                  <p class="text-xs sm:text-sm font-semibold text-white light:text-slate-900">
                    {{ location }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Social Profiles -->
            <div class="pt-4 border-t border-slate-800/80 light:border-slate-200/80">
              <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Professional Profiles</p>
              <div class="flex items-center gap-3">
                <a 
                  v-if="githubUrl" 
                  :href="githubUrl" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold glass-panel text-slate-300 light:text-slate-700 hover:text-white light:hover:text-black hover:border-blue-500/40 transition-colors"
                >
                  <Github class="w-4 h-4" />
                  <span>GitHub</span>
                </a>

                <a 
                  v-if="linkedinUrl" 
                  :href="linkedinUrl" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold glass-panel text-slate-300 light:text-slate-700 hover:text-blue-400 hover:border-blue-500/40 transition-colors"
                >
                  <Linkedin class="w-4 h-4" />
                  <span>LinkedIn</span>
                </a>
              </div>
            </div>

          </div>
        </div>

        <!-- Right Column: Interactive Contact Form -->
        <div class="lg:col-span-7">
          <div class="p-8 sm:p-10 rounded-3xl glass-panel border border-slate-800/80 light:border-slate-200/80 shadow-2xl">
            
            <!-- Success Alert Banner -->
            <div 
              v-if="submitSuccess" 
              class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-start gap-3"
            >
              <CheckCircle class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" />
              <div>
                <h4 class="text-sm font-bold">Message Delivered Successfully!</h4>
                <p class="text-xs text-emerald-400/90 mt-0.5">
                  Thank you for reaching out, {{ form.name }}. Your message has been saved to the database. I will review and reply promptly.
                </p>
              </div>
            </div>

            <!-- Server Error Banner -->
            <div 
              v-if="serverError" 
              class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 flex items-start gap-3"
            >
              <AlertCircle class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" />
              <div>
                <h4 class="text-sm font-bold">Submission Failed</h4>
                <p class="text-xs text-rose-400/90 mt-0.5">{{ serverError }}</p>
              </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-5">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Name -->
                <div>
                  <label for="contact-name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Your Name <span class="text-rose-400">*</span>
                  </label>
                  <input 
                    id="contact-name"
                    v-model="form.name" 
                    type="text" 
                    placeholder="e.g. Abebe Bikila"
                    required
                    :class="[
                      'w-full px-4 py-3 rounded-xl text-sm bg-slate-900/90 light:bg-slate-100 border text-white light:text-slate-900 placeholder:text-slate-500 transition-colors focus:outline-none',
                      errors.name ? 'border-rose-500 focus:border-rose-500' : 'border-slate-800 light:border-slate-300 focus:border-blue-500'
                    ]"
                  />
                  <p v-if="errors.name" class="text-[11px] text-rose-400 mt-1">{{ errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                  <label for="contact-email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Email Address <span class="text-rose-400">*</span>
                  </label>
                  <input 
                    id="contact-email"
                    v-model="form.email" 
                    type="email" 
                    placeholder="name@example.com"
                    required
                    :class="[
                      'w-full px-4 py-3 rounded-xl text-sm bg-slate-900/90 light:bg-slate-100 border text-white light:text-slate-900 placeholder:text-slate-500 transition-colors focus:outline-none',
                      errors.email ? 'border-rose-500 focus:border-rose-500' : 'border-slate-800 light:border-slate-300 focus:border-blue-500'
                    ]"
                  />
                  <p v-if="errors.email" class="text-[11px] text-rose-400 mt-1">{{ errors.email }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Subject -->
                <div>
                  <label for="contact-subject" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Subject <span class="text-rose-400">*</span>
                  </label>
                  <input 
                    id="contact-subject"
                    v-model="form.subject" 
                    type="text" 
                    placeholder="e.g. Web Application Consultation"
                    required
                    :class="[
                      'w-full px-4 py-3 rounded-xl text-sm bg-slate-900/90 light:bg-slate-100 border text-white light:text-slate-900 placeholder:text-slate-500 transition-colors focus:outline-none',
                      errors.subject ? 'border-rose-500 focus:border-rose-500' : 'border-slate-800 light:border-slate-300 focus:border-blue-500'
                    ]"
                  />
                  <p v-if="errors.subject" class="text-[11px] text-rose-400 mt-1">{{ errors.subject }}</p>
                </div>

                <!-- Project Type -->
                <div>
                  <label for="contact-project-type" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Project Type (Optional)
                  </label>
                  <select 
                    id="contact-project-type"
                    v-model="form.project_type"
                    class="w-full px-4 py-3 rounded-xl text-sm bg-slate-900/90 light:bg-slate-100 border border-slate-800 light:border-slate-300 text-white light:text-slate-900 focus:border-blue-500 focus:outline-none transition-colors"
                  >
                    <option value="">Select a category</option>
                    <option value="Laravel Backend / REST API">Laravel Backend / REST API</option>
                    <option value="Vue.js Frontend Development">Vue.js Frontend Development</option>
                    <option value="Full-Stack Application">Full-Stack Application</option>
                    <option value="Database Architecture">Database Architecture</option>
                    <option value="Bug Fixing & Improvements">Bug Fixing & Improvements</option>
                    <option value="Other Inquiries">Other Inquiries</option>
                  </select>
                </div>
              </div>

              <!-- Message -->
              <div>
                <label for="contact-message" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                  Your Message <span class="text-rose-400">*</span>
                </label>
                <textarea 
                  id="contact-message"
                  v-model="form.message" 
                  rows="5"
                  placeholder="Describe your project, timeline, and goals..."
                  required
                  :class="[
                    'w-full px-4 py-3 rounded-xl text-sm bg-slate-900/90 light:bg-slate-100 border text-white light:text-slate-900 placeholder:text-slate-500 transition-colors focus:outline-none resize-none',
                    errors.message ? 'border-rose-500 focus:border-rose-500' : 'border-slate-800 light:border-slate-300 focus:border-blue-500'
                  ]"
                ></textarea>
                <p v-if="errors.message" class="text-[11px] text-rose-400 mt-1">{{ errors.message }}</p>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit" 
                :disabled="isSubmitting"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:pointer-events-none transition-all text-sm"
              >
                <Loader2 v-if="isSubmitting" class="w-4 h-4 animate-spin" />
                <Send v-else class="w-4 h-4" />
                <span>{{ isSubmitting ? 'Sending Message...' : 'Send Message' }}</span>
              </button>
            </form>

          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePortfolioStore } from '@/stores/portfolio'
import { 
  Send, Mail, Phone, MapPin, Github, Linkedin, 
  CheckCircle, AlertCircle, Loader2 
} from 'lucide-vue-next'

const portfolioStore = usePortfolioStore()

const emailAddress = computed(() => portfolioStore.settings.contact_email || 'eyobnigata18@gmail.com')
const phoneNumber = computed(() => portfolioStore.settings.contact_phone || '')
const location = computed(() => portfolioStore.settings.contact_location || 'Ethiopia')
const githubUrl = computed(() => portfolioStore.settings.contact_github || 'https://github.com')
const linkedinUrl = computed(() => portfolioStore.settings.contact_linkedin || 'https://linkedin.com')

const form = ref({
  name: '',
  email: '',
  subject: '',
  project_type: '',
  message: '',
})

const errors = ref({})
const isSubmitting = ref(false)
const submitSuccess = ref(false)
const serverError = ref('')

function validateForm() {
  errors.value = {}
  if (!form.value.name.trim()) {
    errors.value.name = 'Please provide your name.'
  }
  if (!form.value.email.trim()) {
    errors.value.email = 'Please provide your email address.'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'Please provide a valid email address.'
  }
  if (!form.value.subject.trim()) {
    errors.value.subject = 'Please enter a subject.'
  }
  if (!form.value.message.trim()) {
    errors.value.message = 'Please enter a message.'
  } else if (form.value.message.trim().length < 10) {
    errors.value.message = 'Message must be at least 10 characters.'
  }
  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  if (!validateForm()) return

  isSubmitting.value = true
  submitSuccess.value = false
  serverError.value = ''

  try {
    await portfolioStore.submitContact(form.value)
    submitSuccess.value = true
    form.value = {
      name: '',
      email: '',
      subject: '',
      project_type: '',
      message: '',
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      const serverErrs = err.response.data.errors
      Object.keys(serverErrs).forEach(field => {
        errors.value[field] = serverErrs[field][0]
      })
    } else {
      serverError.value = err.response?.data?.message || 'Failed to submit contact message. Please try again.'
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>
