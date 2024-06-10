import { createPinia } from 'pinia';
import { useAuthStore } from './modules/auth';

// Create the Pinia instance
const pinia = createPinia();

// Export the Pinia instance and store modules
export { pinia, useAuthStore };
