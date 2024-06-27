import axios from 'axios';
import Cookies from 'js-cookie';

// Create an Axios instance
const http = axios.create({
  baseURL: '/',
  timeout: 5000,
  headers: { 'X-Custom-Header': 'foobar' }
});

// Add a request interceptor to include the Bearer token
http.interceptors.request.use(config => {
  const token = Cookies.get('token');
  console.log(token);
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

export default http;
