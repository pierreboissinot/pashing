import Vue from 'vue'
import Router from 'vue-router'
import Sample from '../views/Sample'

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/sample',
            component: Sample
        },
    ]
});

export default router;