import NProgress from 'nprogress'
import { createPinia } from 'pinia'
import { createApp, h} from 'vue';
//import 'tailwindcss/tailwind.css'; // Import Tailwind CSS
//window.md5 = require('md5');
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../../../vendor/toanld/ziggy/dist/vue.m';
import {myTrans} from "@admin/functions";
import axios from "axios";
window.translateFormData = new FormData();
window.myTrans = myTrans;
window.axios = axios;
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const pinia = createPinia()

createInertiaApp({
    progress: {
        // The delay after which the progress bar will appear, in milliseconds...
        delay: 250,

        // The color of the progress bar...
        color: '#29d',

        // Whether to include the default NProgress styles...
        includeCSS: true,

        // Whether the NProgress spinner will be shown...
        showSpinner: false,
    },
    title: (title) => `${title} - ${appName}`,
    //resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    resolve: (name) => {
        let page = null;

        let isModule = name.split("::");

        if (isModule.length > 1) {
            // let a =   import.meta.glob('@modules/Admin/Resources/Pages/Index.vue')['/modules/Admin/Resources/Pages/Index.vue']();
            let module = isModule[0];
            let pathTo = isModule[1];
            pathTo = pathTo.replace("\\", "/");
            //console.log(module, pathTo);//Admin Resources/Pages/Index
            let key = `/Modules/${module}/${pathTo}.vue`;
            console.log(key);
            //let value = import.meta.glob('@modules/*/Resources/Pages/**/*.vue');
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
            .use(pinia)
            //.use(myPlugin)
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
