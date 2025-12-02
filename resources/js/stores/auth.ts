import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import router from '@/router'

interface Organization {
  id: number
  name: string
}

interface User {
  id: number
  email: string
  name: string
  role: string
  organization_id: number
  organization: Organization
}

interface LoginForm {
  email: string
  password: string
}

interface ApiError {
  message: string
}

export const useAuthStore = defineStore('auth', {
 state: () => ({
    token: localStorage.getItem('token') || '',
    user: null as User | null,
  }),

  actions: {
    async login(form: LoginForm) {
      try {
        // NO NEED FOR "/api/login" BECAUSE axios.baseUrl = "/api"
        const res = await axios.post('/login', form)

        this.token = res.data.token
        localStorage.setItem('token', this.token)

        await this.fetchUser()

        // Redirect after successful login
        router.push('/dashboard')

      } catch (error) {
        const err = error as AxiosError<ApiError>
        throw err
      }
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const res = await axios.get('/me')
        this.user = res.data
      } catch (error) {
        // If token is invalid → logout automatically
        this.logout()
      }
    },

    logout() {
      this.token = ''
      this.user = null
      localStorage.removeItem('token')

      router.push('/login')
    },

    isLoggedIn(): boolean {
      return !!this.token
    }
  }
})
