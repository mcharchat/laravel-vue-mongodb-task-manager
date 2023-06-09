import './bootstrap';
import '../css/app.css';
import 'floating-vue/dist/style.css'
import "vue-select/dist/vue-select.css";
import "vue-toastification/dist/index.css";


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import FloatingVue from 'floating-vue'
import VueSelect from 'vue-select';
import Toast from "vue-toastification";


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const ToastOptions = {
    // You can set your default options here
};
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(FloatingVue)
            .use(Toast, ToastOptions)
            .component('vue-select', VueSelect)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
