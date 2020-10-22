import Vue from 'vue'
import VueMeta from 'vue-meta'
import PortalVue from 'portal-vue'
import { InertiaApp } from '@inertiajs/inertia-vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueSweetalert2 from 'vue-sweetalert2'

import 'sweetalert2/dist/sweetalert2.min.css'
window.axios = axios;
window.Vue = require('vue');

Vue.use(VueAxios, axios)

Vue.use(InertiaApp)
Vue.use(PortalVue)
Vue.use(VueMeta)
const options = {
    confirmButtonColor: '#e53e3e',
    cancelButtonColor: '#5661b3',
};
Vue.use(VueSweetalert2, options)

var filter = function(text, length, clamp) {
    clamp = clamp || '...';
    return text ? (text.length > length ? text.slice(0, length) + clamp : text) : '';
};

Vue.filter('truncate', filter);
/* Vue.config.productionTip = false
Vue.config.devtools = false */

let app = document.getElementById('app')


new Vue({
    metaInfo: {
        titleTemplate: (title) => title ? `${title} - PSS` : 'PSS'
    },
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name =>
                import (`@/Pages/${name}`).then(module => module.default),
        },
    }),
}).$mount(app)
