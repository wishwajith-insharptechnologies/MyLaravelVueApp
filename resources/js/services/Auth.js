import Http from '@/services/Http.js';
import Csrf from "@/services/Csrf";

export default {
  async register(form) {
    await Csrf.getCookie();
    return Http.post("/api/register", form);
  },

  async login(form) {
    await Csrf.getCookie();
    return Http.post("/api/login", form);
  },

  async logout() {
    return Http.get("/api/logout");
  },

  auth() {
    return Http.get("/api/user");
  },
};
