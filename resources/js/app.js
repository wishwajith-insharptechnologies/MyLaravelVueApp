import { createApp } from 'vue';
import App from './App.vue';
import Antd from 'ant-design-vue';
import router from '/resources/js/router/index.js';
import {pinia} from './stores';

// import 'ant-design-vue/dist/antd.css';
const app = createApp(App);
app.use(Antd);
app.use(pinia);
app.use(router);
app.mount('#app');
