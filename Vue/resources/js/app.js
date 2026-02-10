import '../css/app.css';
import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'; // FIXED: Added this import
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Added back Ziggy if you use route()
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import { definePreset } from '@primeuix/themes'; 
import Aura from '@primeuix/themes/aura';
import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'; // FIXED: Added this definition

// 1. Define your custom dark green preset
const MyCustomPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{emerald.50}',
            100: '{emerald.100}',
            200: '{emerald.200}',
            300: '{emerald.300}',
            400: '{emerald.400}',
            500: '#064e3b', 
            600: '#059669',
            700: '#047857',
            800: '#065f46',
            900: '#064e3b',
            950: '#022c22'
        },
        success: {
            500: '#87af49',
        },
        colorScheme: {
            light: {
                primary: {
                    color: '#87af49',
                    inverseColor: '#ffffff',
                    hoverColor: '#75983f',
                    activeColor: '#87af49'
                },
                success: {
                    color: '#87af49',
                    contrastColor: '#ffffff',
                }
            },
            dark: {
                primary: {
                    color: '#10b981',
                    inverseColor: '#ffffff',
                    hoverColor: '#34d399',
                    activeColor: '#059669'
                }
            }
        }
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue) // Standard Laravel Breeze/Inertia helper
            .use(PrimeVue, {
                theme: {
                    preset: MyCustomPreset,
                    options: {
                        darkModeSelector: '.dark',
                        cssLayer: {
                            name: 'primevue',
                            order: 'tailwind-base, primevue, tailwind-utilities'
                        }
                    }
                }
            })
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});