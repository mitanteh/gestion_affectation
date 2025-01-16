<template>
    <div class="modal-content">
        <Loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="false"
            loader="spinner"
        />
        <div class="modal-header px-4 pt-4">
            <h5 class="modal-title">Modifier un Utilisateur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form @submit.prevent="saveUser" novalidate autocomplete="off">
            <div class="modal-body p-4">
                <div v-if="errors.general" class="alert alert-danger">{{ errors.general }}</div>

                <div class="row gy-4">
                    <!-- Nom -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="nom_user" class="form-label">Nom de l'utilisateur</label>
                        <input type="text" id="nom_user" 
                            :class="{ 'form-control border-danger': errors.nom_user, 'form-control': !errors.nom_user }" 
                            v-model="form.nom_user" 
                            placeholder="Entrez le nom" 
                            required />
                        <div v-if="errors.nom_user" class="text-danger">{{ errors.nom_user[0] }}</div>
                    </div>

                    <!-- Prénom -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="prenom_user" class="form-label">Prénom de l'utilisateur</label>
                        <input type="text" id="prenom_user" 
                            :class="{ 'form-control border-danger': errors.prenom_user, 'form-control': !errors.prenom_user }" 
                            v-model="form.prenom_user" 
                            placeholder="Entrez le prénom" 
                            required />
                        <div v-if="errors.prenom_user" class="text-danger">{{ errors.prenom_user[0] }}</div>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email de l'utilisateur</label>
                    <input type="email" id="email" 
                        :class="{ 'form-control border-danger': errors.email, 'form-control': !errors.email }" 
                        v-model="form.email" 
                        placeholder="Entrez l'email" 
                        required>
                    <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
                </div>

                <!-- Rôle -->
                <div class="mb-3">
                    <label for="role_id" class="form-label">Rôle de l'utilisateur</label>
                    <select id="role_id" 
                        :class="{ 'form-control border-danger': errors.role_id, 'form-control': !errors.role_id }" 
                        v-model="form.role_id" 
                        required>
                        <option value="" disabled>Selectionner le rôle</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.lib_role }}</option>
                    </select>
                    <div v-if="errors.role_id" class="text-danger">{{ errors.role_id[0] }}</div>
                </div>

                <!-- statut -->
                <div class="mb-3">
                    <label for="statut" class="form-label">statut de l'utilisateur</label>
                    <select id="statut" 
                        :class="{ 'form-control border-danger': errors.statut, 'form-control': !errors.statut }" 
                        v-model="form.statut"
                        required>
                        <option value="actif" :selected="form.statut === 'actif'">Actif</option>
                        <option value="inactif" :selected="form.statut === 'inactif'">Inactif</option>
                    </select>
                    <div v-if="errors.statut" class="text-danger">{{ errors.statut[0] }}</div>
                </div>

                <!-- Password -->
                <!-- <div v-if="!isEdit" class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password"  
                        :class="{ 'form-control border-danger': errors.password, 'form-control': !errors.password }" 
                        v-model="form.password" 
                        placeholder="Entrez le mot de passe" 
                        required />
                    <div v-if="errors.password" class="text-danger">{{ errors.password[0] }}</div>
                </div> -->
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success">Modifier Utilisateur</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    components: {
        Loading
    },
    props: {
        user: {
            type: Object,
            required: true, 
        }
    },
    data() {
        return {
            form: {},
            roles: [],
            errors: {},
            isLoading: false,
        };
    },
    watch: {
        // Surveiller les changements de la prop `user`
        user: {
            immediate: true, // Exécute cette fonction dès que le composant est monté
            handler(newUser) {
                // Met à jour le formulaire avec les nouvelles données utilisateur
                this.form = {
                    id: newUser.id,
                    nom_user: newUser.nom_user,
                    prenom_user: newUser.prenom_user,
                    email: newUser.email,
                    password: '', // Laisse vide pour ne pas afficher le mot de passe existant
                    role_id: newUser.role_id,
                    statut: newUser.statut,
                    role: newUser.role,
                };
            },
        },
    },
    methods: {
        saveUser() {
            this.isLoading = true;

            axios.put('/api/users/' + this.user.id, this.form)
                .then(response => {
                    toast.success(`Utilisateur modifié avec succès !`, {
                        "theme": "colored",
                        "type": "success",
                        "position": "bottom-right",
                        "dangerouslyHTMLString": true
                    });
                    this.$emit("close");
                    this.$emit("refresh");
                })
                .catch(error => {
                    toast.error(error.response.data.message, {
                        "theme": "colored",
                        "type": "error",
                        "position": "bottom-right",
                        "dangerouslyHTMLString": true
                    });
                    this.errors = {};
                    if (error.response && error.response.data) {
                        this.errors = error.response.data.errors || {};
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        fetchRoles() {
            axios.get('/api/roles')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des rôles:', error);
                });
        },
    },
    mounted() {
        this.fetchRoles(); // Charger les rôles
    },
};
</script>
