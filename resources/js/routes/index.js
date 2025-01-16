import { createWebHistory, createRouter } from 'vue-router';
import store from '../store/auth';
import { toast } from "vue3-toastify";

import Login from '../pages/auth/Login.vue';
import Dashboard from '../pages/Home.vue';
import Settings from '../pages/Settings.vue';
import NotFound from '../pages/NotFound.vue';
import DashboardLayout from '../pages/layouts/Dashboard.vue';
import ProjectList from '../pages/projects/List.vue';
import ProjectDetail from '../pages/projects/Detail.vue';
import UserList from '../pages/users/List.vue';
import ResourceList from '../pages/ressources/List.vue';
import ResourceDetail from '../pages/ressources/Detail.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/',
    component: DashboardLayout, // Layout principal pour le tableau de bord
    children: [
      { 
        path: '', 
        name: 'Dashboard', 
        component: Dashboard, 
        meta: { requiresAuth: true } 
      },
      { 
        path: 'settings', 
        name: 'Settings', 
        component: Settings, 
        meta: { requiresAuth: true } 
      },
      { 
        path: 'projets/list', 
        name: 'ProjectList', 
        component: ProjectList, 
        meta: { requiresAuth: true, role: 'admin' } 
      },
      { 
        path: 'projets/detail/:id', 
        name: 'ProjectDetail', 
        component: ProjectDetail, 
        meta: { requiresAuth: true } 
      },
      { 
        path: 'users/list', 
        name: 'UserList', 
        component: UserList, 
        meta: { requiresAuth: true, role: 'admin' } 
      },
      { 
        path: 'ressources/list/:role', 
        name: 'ResourceList', 
        component: ResourceList, 
        props: true, 
        meta: { requiresAuth: true } 
      },
      { 
        path: 'ressources/detail/:id', 
        name: 'ResourceDetail', 
        component: ResourceDetail, 
        props: true, 
        meta: { requiresAuth: true } 
      },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'notfound',
    component: NotFound,
  },
];

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: "active",
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0 };
        }
    },
    routes: routes,
});

// Garde de route pour les middlewares
router.beforeEach((to, from, next) => {
    const isAuthenticated = store.state.token; // Récupère le token depuis Vuex
    const userRole = store.state.role; // Récupère le rôle de l'utilisateur depuis Vuex

    if (to.meta.requiresAuth && !isAuthenticated) {
        // Si la route nécessite une authentification mais l'utilisateur n'est pas connecté
        toast.warn("Vous devez vous connecter pour accéder à cette page.", {
            theme: "colored",
            dangerouslyHTMLString: true,
            position: toast.POSITION.BOTTOM_RIGHT,
        });
        next({ name: 'login' }); // Redirection vers la page de connexion
    } else if (to.meta.role && to.meta.role !== userRole) {
        // Si une route nécessite un rôle spécifique et que l'utilisateur n'a pas ce rôle
        next({ name: 'Dashboard' }); // Redirection vers le tableau de bord ou une autre page
    } else {
        next();
    }
});

export default router;