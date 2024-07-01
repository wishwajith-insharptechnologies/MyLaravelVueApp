import { defineStore } from 'pinia'
import axios from "axios";
// No router import needed in Pinia stores

export const useAuthStore = defineStore('auth', {
  state: () => ({
    authenticated: false,
    user: {},
    redirectUrl: {},
  }),
  getters: {
    isAuthenticated(state) {
      return state.authenticated;
    },
    getUser(state) {
      return state.user;
    },
    getSavedUrl(state) {
      return state.redirectUrl;
    },
  },
  actions: {
    async login() {
      try {
        const { data } = await axios.get("/api/user");
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
      this.redirectUrl = {};
    },
  },
});
