import Http from '@/services/Http.js';
import Csrf from "@/services/Csrf";

export default {
  async register(form) {
    await Csrf.getCookie();
    return Http.post("register", form);
  },

  async login(form) {
    await Csrf.getCookie();
    return Http.post("login", form);
  },

  async logout() {
    try{

        return Http.post("logout");
    }catch(error){
        console.log(error);

    }
  },

  auth() {
    return Http.get("user");
  },
};
