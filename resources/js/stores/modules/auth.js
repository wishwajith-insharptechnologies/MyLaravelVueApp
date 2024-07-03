import { defineStore } from 'pinia'
import Http from '@/services/Http.js';

// import axios from "axios";
// No router import needed in Pinia stores

export const useAuthStore = defineStore('auth', {
  state: () => ({
    authenticated: false,
    user: {},
  }),
  getters: {
    isAuthenticated(state) {
      return state.authenticated;
    },
    getUser(state) {
      return state.user;
    }
  },
  actions: {
    async login() {
      try {
        const { data } = await Http.get("/api/user");
        this.user = data;
        this.authenticated = true;
      } catch (error) {
        this.user = {};
        this.authenticated = false;
      }
    },
    logout() {
      this.user = {};
      this.authenticated = false;
    },
  },
});
