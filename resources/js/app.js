import './bootstrap';

import { createApp } from "vue";
import router from './routes/index.js';  // Importer le routeur configuré
import store from './store/auth.js';

// Sweet alert
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css';

// Toast
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// Loader
import 'vue-loading-overlay/dist/css/index.css';

// Components
import App from './App.vue';

// const router = createRouter({
//     history: createWebHistory(),
//     mode: "history",
//     // base: __dirname,
//     linkActiveClass: "active",
//     scrollBehavior(to, from, savedPosition) {
//         if (savedPosition) {
//             return savedPosition
//         } else {
//             return { top: 0 };
//         }
//     },
//     routes: routes,
// })

const options = {
    position: "bottom-right",
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
};


// 3. On monte l'application Vue sur l'élément #app
// createApp(App)
// .use(router)
// .use(Swal)
// .use(Toast, options)
// .mount("#app")

createApp(App)
  .use(router)  // Utiliser Vue Router
  .use(store)  // Utilise le store avec l'application Vue
  .use(Toast, options)
  .mount('#app');