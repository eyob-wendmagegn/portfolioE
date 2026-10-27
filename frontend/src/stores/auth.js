import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('admin_token') || null)
  const user = ref(JSON.parse(localStorage.getItem('admin_user') || 'null'))
  const loading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  async function login(email, password) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/admin/login', { email, password })
      token.value = response.data.token
      user.value = response.data.user

      localStorage.setItem('admin_token', token.value)
      localStorage.setItem('admin_user', JSON.stringify(user.value))

      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Authentication failed'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await api.post('/admin/logout')
      }
    } catch (e) {
      // Continue cleanup even if server logout fails
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
    }
  }

  async function fetchUser() {
    if (!token.value) return null
    try {
      const response = await api.get('/admin/me')
      user.value = response.data.user
      localStorage.setItem('admin_user', JSON.stringify(user.value))
      return user.value
    } catch (err) {
      logout()
      return null
    }
  }

  async function updateProfile(data) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put('/admin/profile', data)
      user.value = response.data.user
      localStorage.setItem('admin_user', JSON.stringify(user.value))
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update profile'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    token,
    user,
    loading,
    error,
    isAuthenticated,
    login,
    logout,
    fetchUser,
    updateProfile,
  }
})
