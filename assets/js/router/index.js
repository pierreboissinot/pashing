import Vue from 'vue'
import Router from 'vue-router'
import Sample from '../dashboard/Sample'

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/sample',
            name: 'sample',
            component: Sample
        },
    ]
});

export default router;