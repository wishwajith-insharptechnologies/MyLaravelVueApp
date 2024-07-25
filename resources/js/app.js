import { createApp } from 'vue';
import App from './App.vue';
import Antd from 'ant-design-vue';
import {pinia} from './stores';
import router from '/resources/js/router/index.js';

// import 'ant-design-vue/dist/antd.css';
const app = createApp(App);

app.use(Antd);
app.use(pinia);
app.use(router);
app.mount('#app');
