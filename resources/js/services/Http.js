import axios from "axios";
import { message } from 'ant-design-vue';


let Http = axios.create({ baseURL: import.meta.env.VITE_API_URL});

Http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

Http.defaults.withXSRFToken = true;

Http.defaults.withCredentials = true;


Http.interceptors.request.use(config => {
    console.log('Outgoing request:', config);
    return config;
});

Http.interceptors.response.use(response => {
    return response;
},
error => {

    if (!error.response) {
      console.error('Network error:', error.message);
      return Promise.reject(error);
    }

    const statusCode = error.response.status;
    switch (statusCode) {
      case 400:
        console.log('Bad Request (400):', error.response.data.message);
        message.warning( error.response.data.message || 'Something went wrong.');

        break;
      case 401:
        console.error('Unauthorized (401):', error.response.data);


        break;
      case 403:
        console.error('Forbidden (403):', error.response.data);
        message.error('Resource not found. Please check the URL or try another action.');

        break;
      case 404:
        console.error('Not Found (404):', error.response.data);

        break;
     case 422:
        console.log('Bad Request (400):', error.response.data.message);
        message.warning( error.response.data.message || 'Something went wrong.');

        break;
      case 500:
        console.error('Internal Server Error (500):', error.response.data);
        message.error('Internal server error. Please try again later.');
        break;

        break;
      default:
        console.error('Unexpected server error:', statusCode, error.response.data);
        message.error('Unexpected error!');
    }

    return Promise.reject(error);
  }
);


export default Http;
