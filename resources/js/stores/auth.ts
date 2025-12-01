import { defineStore } from 'pinia'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

interface User {
    id: number
    email: string
    role: string
    organization_id: number
}

interface LoginForm {
    email: string
    password: string
}

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: null as User | null,
    }),
    actions: {
        checkAuth() {
            if (!this.token) {
                router.visit('/login')
            }
        },
        async login(form: LoginForm) {
            const res = await axios.post('/api/login', form)
            this.token = res.data.token
            if (this.token) {
                localStorage.setItem('token', this.token)
            }
            await this.fetchUser()
        },
        async fetchUser() {
            if (!this.token) return
            try {
                const res = await axios.get('/api/me')
                this.user = res.data
            } catch (err: any) {
                // If backend says Unauthorized, clear token and redirect
                if (err.response?.status === 401) {
                    this.logout()
                    router.visit('/login')
                }
            }
        },
        logout() {
            this.token = null
            this.user = null
            localStorage.removeItem('token')
        }
    }
})
