import Vue from 'vue'

import App from './App'
import router from './router'
import Sample from './dashboard/Sample'

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: {App, Sample}
}).$mount('#app');