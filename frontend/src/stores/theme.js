import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useThemeStore = defineStore('theme', () => {
  const isDark = ref(true)

  function initTheme() {
    localStorage.removeItem('theme')
    const root = document.documentElement
    root.classList.remove('light')
    root.classList.add('dark')
  }

  function toggleTheme() {
    // Permanent dark theme enforced
    initTheme()
  }

  return {
    isDark,
    initTheme,
    toggleTheme,
  }
})
