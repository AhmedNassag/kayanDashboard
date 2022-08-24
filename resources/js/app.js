require('./bootstrap');

import { createApp } from 'vue';
import store from './store/admin';
import router from './router/adminRoute';
import Admin from "./Admin.vue";
import mitt from 'mitt';
import Notifications from '@kyvg/vue3-notification';
import i18n from './lang/admin';
import VueGoogleMaps from '@fawmi/vue-google-maps'
const emitter = mitt();

const admin = createApp(Admin);

admin.provide('emitter', emitter);

import loader from './components/loader';
import LaravelVuePagination from 'laravel-vue-pagination';

admin.component('loader', loader);
admin.component('Pagination', LaravelVuePagination);

admin.use(i18n)
    .use(store)
    .use(VueGoogleMaps, {
        load: {
            key: 'YOUR_API_KEY_COMES_HERE',
        },
    })
    .use(router).use(Notifications).mount('#admin');





