import { defineStore } from 'pinia'
import Http from '@/services/Http.js';

// import axios from "axios";
// No router import needed in Pinia stores

export const useAuthStore = defineStore('auth', {
  state: () => ({
    authenticated: false,
    user: {},
    role:"admin"
  }),
  getters: {
    isAuthenticated(state) {
      return state.authenticated;
    },
    getUser(state) {
      return state.user;
    },
    getRole(state) {
      return state.role;
    }
  },
  actions: {
    async signIn() {
      try {
        const { data } = await Http.get("user");
        this.user = data.data;
        this.authenticated = true;
      } catch (error) {
        this.user = {};
        this.authenticated = false;
      }
    },
    signOut() {
      this.user = {};
      this.authenticated = false;

      localStorage.clear();
      this.$reset();
      console.log("session clear");
    },
  },
  persist: {
    enabled: true,
    strategies: [
      {
        key: 'main',
        storage: localStorage, // or sessionStorage
      },
    ],
  },
});
