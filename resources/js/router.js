import { createWebHistory, createRouter } from "vue-router";
import {routes} from './routes'

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} | Cloud Inc.`
    const authRequired = !['/login', '/register'].includes(to.path)

    if (authRequired && !localStorage.getItem('user')) {
        next('/login')
    } else {
        next()
    }
});

export default router;
