import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes';
import { useAuthStore } from '@/stores';

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// router.beforeEach((to, from, next) => {
//     document.title = to.meta.title
//     if (to.meta.middleware == "guest") {
//         console.log(useAuthStore);
//         if (useAuthStore.state.authenticated) {
//             next({ name: "dashboard" })
//         }
//         next()
//     } else {
//         if (useAuthStore.state.auth.authenticated) {
//             next()
//         } else {
//             next({ name: "login" })
//         }
//     }
// });

export default router;
