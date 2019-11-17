import Vue from 'vue';
import Router from 'vue-router';
import routes from './routes';

Vue.use(Router);

const router = createArchitectRouter({ prefix: window.config.prefix });

export default router;

function createArchitectRouter({ prefix }) {
    return new Router({
        base: prefix,
        mode: 'history',
        routes,
    });
}

