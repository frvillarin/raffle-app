require('./bootstrap');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';
import { InertiaProgress } from '@inertiajs/progress'
import Notifications from 'vue-notification'

import route from 'ziggy-js';
import { Ziggy } from "@/ziggy";
import VueConfetti from 'vue-confetti'


Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.use(Notifications)
Vue.use(VueConfetti)

const app = document.getElementById('app');
const decodedZiggy = JSON.parse(atob(Ziggy))

Vue.mixin({
    methods: {
        route: (name, params, absolute, config = decodedZiggy) => route(name, params, absolute, config),
        isRoute(...routes) {
            return routes.some(route => this.route().current(route));
        },
        findItem(collection, property, value) {
            return _.find(collection, [property, value])
        },

    }
});

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);

InertiaProgress.init({
    // The delay after which the progress bar will
    // appear during navigation, in milliseconds.
    delay: 250,

    // The color of the progress bar.
    color: '#29d',

    // Whether to include the default NProgress styles.
    includeCSS: true,

    // Whether the NProgress spinner will be shown.
    showSpinner: false,
})
