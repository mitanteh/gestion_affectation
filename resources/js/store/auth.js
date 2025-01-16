import axios from 'axios';
import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const store = createStore({
    state: {
        user: null,
        role: null,
        token: null,
        loginTimestamp: null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
            state.role = user.role.lib_role;
        },
        setToken(state, token) {
            state.token = token;
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        },
        setLoginTimestamp(state, timestamp) {
            state.loginTimestamp = timestamp;
        },
        clearAuthData(state) {
            state.user = null;
            state.role = null;
            state.token = null;
            state.loginTimestamp = null;
            axios.defaults.headers.common['Authorization'] = null;
        },
    },
    actions: {
        async login({ commit }, authData) {
            try {
                const response = await axios.post('/api/login', authData);
                commit('setUser', response.data.user);
                commit('setToken', response.data.token);
                commit('setLoginTimestamp', Date.now());
                toast.success("Authentification réussie !", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            } catch (error) {
                console.error("Login failed:", error);
                toast.error("Erreur lors de l'authentification", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async logout({ commit }) {
            try {
                await axios.post('/api/logout');
                toast.success("Déconnexion réussie !", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            } catch (error) {
                console.error("Logout failed:", error); // Handle logout error
            } finally {
                commit('clearAuthData'); // Always clear local data on logout
            }
        },
        checkSessionExpiration({ state, dispatch }) {
            if (state.loginTimestamp) {
                const now = Date.now();
                const elapsedTime = now - state.loginTimestamp;
                const hoursElapsed = elapsedTime / (1000 * 60 * 60);
                if (hoursElapsed >= 24) {
                dispatch('logout');
                    toast.info('Votre session a expiré. Veuillez vous reconnecter.');
                }
            }
        },
    },
    getters: {
        isAuthenticated(state) {
            return state.user !== null;
        },
        getUser(state) {
            return state.user;
        },
    },
    plugins: [
        createPersistedState({
            // storage: {
            //     getItem: (key) => Cookies.get(key),
            //     setItem: (key, value) => Cookies.set(key, value, { expires: 7 }), // Keep session for 7 days (adjust as needed)
            //     removeItem: (key) => Cookies.remove(key),
            // },
        }),
    ],
});

export default store;