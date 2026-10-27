import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const usePortfolioStore = defineStore('portfolio', () => {
  const settings = ref({})
  const categories = ref([])
  const featuredProjects = ref([])
  const allProjects = ref([])
  const libraryDashboards = ref([])
  const skills = ref([])
  const services = ref([])
  const education = ref([])
  const stats = ref({})
  const loading = ref(false)
  const error = ref(null)

  // Computed grouped skills
  const skillsByCategory = computed(() => {
    const groups = {}
    skills.value.forEach((skill) => {
      const cat = skill.category || 'Other'
      if (!groups[cat]) {
        groups[cat] = []
      }
      groups[cat].push(skill)
    })
    return groups
  })

  // Hero roles array
  const heroRoles = computed(() => {
    const roles = settings.value.hero_roles
    if (Array.isArray(roles) && roles.length > 0) {
      return roles
    }
    return [
      'Laravel Developer',
      'Vue.js Developer',
      'Full-Stack Web Developer',
      'Backend Developer',
      'Web Application Developer',
    ]
  })

  async function fetchPortfolio() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/portfolio')
      const data = response.data
      settings.value = data.settings || {}
      categories.value = data.categories || []
      featuredProjects.value = data.featured_projects || []
      allProjects.value = data.projects || []
      libraryDashboards.value = data.library_dashboards || []
      skills.value = data.skills || []
      services.value = data.services || []
      education.value = data.education || []
      stats.value = data.stats || {}
      return data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to load portfolio content'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function submitContact(form) {
    try {
      const response = await api.post('/contact', form)
      return response.data
    } catch (err) {
      throw err
    }
  }

  return {
    settings,
    categories,
    featuredProjects,
    allProjects,
    libraryDashboards,
    skills,
    services,
    education,
    stats,
    loading,
    error,
    skillsByCategory,
    heroRoles,
    fetchPortfolio,
    submitContact,
  }
})
