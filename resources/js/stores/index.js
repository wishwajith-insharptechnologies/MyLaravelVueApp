import { createPinia } from 'pinia';
import { useAuthStore } from './modules/auth';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';


const pinia = createPinia();

pinia.use(piniaPluginPersistedstate);

export { pinia, useAuthStore };
