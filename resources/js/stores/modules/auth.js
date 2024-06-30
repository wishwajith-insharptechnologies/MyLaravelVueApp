// src/stores/modules/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        error: null,
        token: null, // Store the token
        tokenType: null, // Store the token type
    }),
    getters: {
        isAuthenticated: (state) => !!state.user,
    },
    actions: {
        async register(form) {
            this.loading = true;
            this.error = null;
            try {
                await axios.post('/api/register', form);
                await this.login({ email: form.email, password: form.password });
            } catch (error) {
                this.error = error.response?.data?.message || 'Registration failed';
            } finally {
                this.loading = false;
            }
        },
        async login(form) {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.post('/api/login', form);
                this.token = response.data.access_token;
                this.tokenType = response.data.token_type;
                axios.defaults.headers.common['Authorization'] = `${this.tokenType} ${this.token}`;
                // Fetch user data or other authenticated data after login
                await this.fetchUserData();
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            this.loading = true;
            this.error = null;
            try {
                await axios.post('/api/logout');
                this.user = null;
                this.token = null;
                this.tokenType = null;
                delete axios.defaults.headers.common['Authorization'];
            } catch (error) {
                this.error = error.response?.data?.message || 'Logout failed';
            } finally {
                this.loading = false;
            }
        },
        async fetchUser() {
            this.loading = true;
            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                this.user = null;
            } finally {
                this.loading = false;
            }
        },
        async fetchUserData() {
            try {
                const response = await axios.get('/api/user-data');
                console.log(response.data);
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch user data';
            }
        },
        async fetchProjects() {
            try {
                const response = await axios.get('/api/projects/list');
                console.log(response.data);
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch projects';
            }
        },
    },
});
