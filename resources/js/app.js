import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import "../css/app.css"; // Import the reset.css file

createInertiaApp({
    title: (title) => "Bruxy",
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });

        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
