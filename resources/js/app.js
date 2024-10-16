import '../css/app.css';
import 'bootstrap/dist/css/bootstrap.min.css'; // Bootstrap CSS
import 'admin-lte/dist/css/adminlte.min.css'; // AdminLTE CSS
import '@fortawesome/fontawesome-free/css/all.min.css'; // Font Awesome CSS
import '../css/custom.css'; // Custom CSS

// Import jQuery
import $ from 'jquery';
window.$ = window.jQuery = $; // Make jQuery available globally

// Import Popper.js and Bootstrap
import 'popper.js';
import 'bootstrap'; // Bootstrap JS

// AdminLTE JS
import 'admin-lte/dist/js/adminlte.min.js';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    title: title => (title ? `${title} - Ping CRM` : 'Ping CRM'),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
