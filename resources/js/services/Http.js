import axios from "axios";

let Http = axios.create({ baseURL: import.meta.env.VITE_API_URL});

Http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

Http.defaults.withXSRFToken = true;

Http.defaults.withCredentials = true;

export default Http;
