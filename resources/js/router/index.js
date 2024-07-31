import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes';
import { useAuthStore } from '@/stores/modules/auth.js';

const router = createRouter({
  history: createWebHistory(),
  routes,
});


router.beforeEach((to, from, next) => {


    const store = useAuthStore();

    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        console.log(useAuthStore);
        if (store.isAuthenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        console.log('login');
        console.log(store.isAuthenticated);
        if (store.isAuthenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
});

export default router;

