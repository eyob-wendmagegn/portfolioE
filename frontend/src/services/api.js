import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Accept': 'application/json',
  },
})

// Request interceptor to attach bearer token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('admin_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Response interceptor to handle errors cleanly
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      // Clear token if unauthorized on a protected endpoint
      if (localStorage.getItem('admin_token')) {
        localStorage.removeItem('admin_token')
        localStorage.removeItem('admin_user')
        if (window.location.pathname.startsWith('/admin') && window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login'
        }
      }
    }
    return Promise.reject(error)
  }
)

export default api
