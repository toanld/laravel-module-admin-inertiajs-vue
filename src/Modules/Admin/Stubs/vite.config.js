import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    build: {
        //outDir: 'public/assets/build/all',
        emptyOutDir: true,
        manifest: true,
    },
    resolve:{
        alias:{
            '@modules' : __dirname + '/Modules',
            '@erp' : __dirname + '/Modules/Erp/Resources/Vuejs',
            '@admin' : __dirname + '/Modules/Admin/Resources/Vuejs',
            '@' : __dirname+'/resource/js'
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
           // buildDirectory: 'assets/build/all',
            input: [
                'Modules/Admin/Resources/Vuejs/app.js',
                'Modules/Admin/Resources/assets/css/app.scss',
                'resources/css/web/web.scss',
                'resources/js/app.js'
            ],

            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ]
});
// glob.sync('./Modules/*/*/vite.config.js').forEach(item => require(item));
