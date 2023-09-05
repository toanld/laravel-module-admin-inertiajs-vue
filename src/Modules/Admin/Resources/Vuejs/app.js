import NProgress from 'nprogress'
//import { createPinia } from 'pinia'
import { createApp, h} from 'vue';
//import 'tailwindcss/tailwind.css'; // Import Tailwind CSS
//window.md5 = require('md5');
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../../../vendor/toanld/ziggy/dist/vue.m';
import { ModelSelect,ModelListSelect,MultiListSelect,MultiSelect } from "vue-search-select"
import {myTrans,takephoto,resizeBase64Img} from "@admin/functions";
import {MyForm} from "./form/MyForm";
import axios from "axios";
import { apiGet, apiPost, apiPut, apiPatch, apiDelete } from './api.js';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.addEventListener('error', function(event) {
    console.error('Có lỗi xảy ra:', event.error);
});
window.apiGet = apiGet;
window.apiPost = apiPost;
window.apiPut = apiPut;
window.apiPatch = apiPatch;
window.apiDelete = apiDelete;
window.takephoto = takephoto;
window.resizeBase64Img = resizeBase64Img;
window.MyForm = MyForm;
window.translateFormData = new FormData();
window.myTrans = myTrans;
window.axios = axios;
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found in the page.');
}
// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    NProgress.start();
    console.log('start NProgress');
    return config;
}, function (error) {
    // Do something with request error
    console.error(error)
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use( async function (response) {
    // Do something with response data
    NProgress.done();
    return response;
},  async function (error) {
    NProgress.done();
    console.error("loi o day" + window.location.href);
    //var base64Error = await takephoto();
    //console.log(base64Error);

    console.error(error);
    // Do something with response error
    return Promise.reject(error);
});
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

//const pinia = createPinia()

createInertiaApp({
    progress: {
        // The delay after which the progress bar will appear, in milliseconds...
        delay: 250,

        // The color of the progress bar...
        color: '#29d',

        // Whether to include the default NProgress styles...
        includeCSS: false,

        // Whether the NProgress spinner will be shown...
        showSpinner: true,
    },
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        let page = null;

        let isModule = name.split("::");

        if (isModule.length > 1) {
            let module = isModule[0];
            let pathTo = isModule[1];
            pathTo = pathTo.replace("\\", "/");
            let key = `/Modules/${module}/${pathTo}.vue`;
            console.log(key);
            let value = import.meta.glob('@modules/*/Resources/Vuejs/Pages/**/*.vue');
            page = resolvePageComponent(key, value);

        } else {
            page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        let app = createApp({ render: () => h(App, props) })
            .use(plugin)
            //.use(pinia)
            //.use(myPlugin)
            //.use(ModelSelect).use(ModelListSelect).use(MultiListSelect).use(MultiSelect)
            .use(ZiggyVue, Ziggy);
        return app.mount(el);
    },
});
if ('serviceWorker' in navigator) {
    window.addEventListener('load', async () => {
        try {
            const registration = await navigator.serviceWorker.register('/build/sw.js');
            console.log('ServiceWorker registered with scope:', registration.scope);
        } catch (error) {
            console.log('ServiceWorker registration failed:', error);
        }
    });
}
router.on('start', () => NProgress.start())
router.on('finish', () => {
    NProgress.done();
})
window.onerror = function(message, source, lineno, colno, error) {
    console.error('Có lỗi xảy ra:', error);
};
window.addEventListener('unhandledrejection', function(event) {
    console.error('Promise bị từ chối:', event.reason);
});

