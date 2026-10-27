<template>
  <div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-extrabold text-white light:text-slate-900 tracking-tight">Admin Profile & Security</h1>
      <p class="text-xs text-slate-400 light:text-slate-600 mt-1">
        Update administrator credentials, name, and login password.
      </p>
    </div>

    <!-- Alert / Toast Banner -->
    <div 
      v-if="feedback" 
      class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between"
    >
      <span>{{ feedback }}</span>
      <button @click="feedback = ''" class="text-emerald-400 hover:text-white"><X class="w-4 h-4" /></button>
    </div>

    <div 
      v-if="errorMessage" 
      class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center justify-between"
    >
      <span>{{ errorMessage }}</span>
      <button @click="errorMessage = ''" class="text-rose-400 hover:text-white"><X class="w-4 h-4" /></button>
    </div>

    <div class="p-6 sm:p-8 rounded-3xl glass-panel border border-slate-800 light:border-slate-200 shadow-xl">
      <form @submit.prevent="updateProfile" class="space-y-5">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
            Administrator Full Name <span class="text-rose-400">*</span>
          </label>
          <input 
            v-model="name" 
            type="text" 
            required 
            class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
            Email Address <span class="text-rose-400">*</span>
          </label>
          <input 
            v-model="email" 
            type="email" 
            required 
            class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
          />
        </div>

        <div class="pt-4 border-t border-slate-800 space-y-4">
          <p class="text-xs font-bold text-slate-300">Change Password (Leave blank to keep current password)</p>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
              Current Password (Required only if changing password)
            </label>
            <input 
              v-model="currentPassword" 
              type="password" 
              placeholder="••••••••••••"
              class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                New Password
              </label>
              <input 
                v-model="newPassword" 
                type="password" 
                placeholder="At least 8 characters"
                class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                Confirm New Password
              </label>
              <input 
                v-model="newPasswordConfirmation" 
                type="password" 
                placeholder="Repeat new password"
                class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-950 border border-slate-800 text-white focus:outline-none"
              />
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end">
          <button 
            type="submit" 
            :disabled="saving"
            class="px-6 py-2.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white flex items-center gap-2"
          >
            <Loader2 v-if="saving" class="w-3.5 h-3.5 animate-spin" />
            <Save v-else class="w-3.5 h-3.5" />
            <span>Update Profile</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { Save, Loader2, X } from 'lucide-vue-next'

const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const currentPassword = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')

const feedback = ref('')
const errorMessage = ref('')
const saving = ref(false)

onMounted(async () => {
  if (authStore.user) {
    name.value = authStore.user.name
    email.value = authStore.user.email
  } else {
    const user = await authStore.fetchUser()
    if (user) {
      name.value = user.name
      email.value = user.email
    }
  }
})

async function updateProfile() {
  saving.value = true
  feedback.value = ''
  errorMessage.value = ''

  try {
    const payload = {
      name: name.value,
      email: email.value,
    }

    if (newPassword.value) {
      payload.current_password = currentPassword.value
      payload.new_password = newPassword.value
      payload.new_password_confirmation = newPasswordConfirmation.value
    }

    await authStore.updateProfile(payload)
    feedback.value = 'Profile updated successfully.'
    currentPassword.value = ''
    newPassword.value = ''
    newPasswordConfirmation.value = ''
  } catch (err) {
    errorMessage.value = err.response?.data?.message || authStore.error || 'Failed to update profile.'
  } finally {
    saving.value = false
  }
}
</script>
