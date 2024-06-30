import axios from "axios";

let Http = axios.create({ baseURL: import.meta.env.VITE_BASE_URL });

Http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

Http.defaults.withCredentials = true;

export default Http;
