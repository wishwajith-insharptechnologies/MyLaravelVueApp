import { createApp } from 'vue';
import App from './App.vue';
import Antd from 'ant-design-vue';
import router from '/resources/js/router/index.js';
import { createPinia } from 'pinia'

// import 'ant-design-vue/dist/antd.css';
const pinia = createPinia();
const app = createApp(App);
app.use(Antd);
app.use(pinia);
app.use(router);
app.mount('#app');
