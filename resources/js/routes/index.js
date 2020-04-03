import Vue from 'vue';
import Router from 'vue-router';
import routes from './routes';

Vue.use(Router);

const router = createArchitectRouter({prefix: window.config.prefix});

export default router;

function createArchitectRouter({prefix}) {
    const router = new Router({
        base: prefix,
        mode: 'history',
        routes,
    });

    router.beforeEach((to, from, next) => {
        if (to.name !== 'login' && !document.querySelector('meta[name="api-token"]')) {
            next('/login');
            return;
        }

        if (to.name === 'login' && document.querySelector('meta[name="api-token"]')) {
            next('/');
            return;
        }

        next();
    });

    return router;
}

