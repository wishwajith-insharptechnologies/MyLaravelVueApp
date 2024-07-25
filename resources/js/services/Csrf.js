import Http from "./Http";

export default {
  getCookie() {
    return Http.get("/sanctum/csrf-cookie");
  },
};
