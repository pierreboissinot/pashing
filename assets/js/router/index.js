import Vue from 'vue'
import Router from 'vue-router'
import CestPasSorcier from '../views/CestPasSorcier'

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/cest-pas-sorcier',
            component: CestPasSorcier
        },
    ]
});

export default router;