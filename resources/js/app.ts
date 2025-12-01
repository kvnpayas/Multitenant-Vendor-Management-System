import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia'
import axios from 'axios';
import { router } from '@inertiajs/vue3'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {

        const pinia = createPinia()

        axios.interceptors.response.use(
            (res) => res,
            (err) => {
                if (err.response?.status === 401) {
                    localStorage.removeItem("token")
                    router.visit("/login")
                }
                return Promise.reject(err)
            }
        )

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
