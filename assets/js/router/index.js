import Vue from 'vue'
import Router from 'vue-router'
import CestPasSorcier from '../views/CestPasSorcier'
import Projets from '../views/Projects'

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/cest-pas-sorcier',
            component: CestPasSorcier
        },
        {
            path: '/projets',
            component: Projets
        }
    ]
});

export default router;