<template>
    <div class="modal-content">
        <Loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="false"
            loader="spinner"
        />
        <div class="modal-header px-4 pt-4">
            <h5 class="modal-title" id="exampleModalLabel">Ajouter un Projet</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form @submit.prevent="createProject" novalidate autocomplete="off">
            <div class="modal-body p-4">
                <input type="hidden" id="id-field" v-model="form.id" />
                <!-- Nom du Projet -->
                <div class="mb-3">
                    <label for="nom_projet" class="form-label">Nom du Projet</label>
                    <input type="text" id="nom_projet" :class="{ 'form-control border-danger': errors.nom_projet, 'form-control': !errors.nom_projet }" 
                            v-model="form.nom_projet" placeholder="Entrez le nom du projet" required />
                    <div v-if="errors.nom_projet" class="text-danger">{{ errors.nom_projet[0] }}</div>
                </div>

                <!-- Description du Projet -->
                <div class="mb-3">
                    <label for="description_projet" class="form-label">Description du Projet</label>
                    <textarea id="description_projet" class="form-control" v-model="form.description_projet" placeholder="Décrivez le projet" required></textarea>
                </div>

                <!-- Date de début -->
                <div class="mb-3">
                    <label for="date_deb" class="form-label">Date de Début</label>
                    <input type="date" id="date_deb" :class="{ 'form-control border-danger': errors.date_deb, 'form-control': !errors.date_deb }" 
                            v-model="form.date_deb" placeholder="Entrez la date de début" required />
                    <div v-if="errors.date_deb" class="text-danger">{{ errors.date_deb[0] }}</div>
                </div>

                <!-- Date de fin -->
                <div class="mb-3">
                    <label for="date_fin" class="form-label">Date de Fin</label>
                    <input type="date" id="date_fin" :class="{ 'form-control border-danger': errors.date_fin, 'form-control': !errors.date_fin }" 
                            v-model="form.date_fin" placeholder="Entrez la date de fin" required />
                    <div v-if="errors.date_fin" class="text-danger">{{ errors.date_fin[0] }}</div>
                </div>

                <!-- Type de Projet -->
                <div class="mb-3">
                    <label for="type_projet" class="form-label">Type de Projet</label>
                    <select :class="{ 'form-control border-danger': errors.date_deb, 'form-control': !errors.date_deb }" 
                        v-model="form.type_projet" required>
                        <option value="" disabled>Selectionner le type de projet</option>
                        <option value="agile">Projet</option>
                        <option value="waterfall">Chantier</option>
                    </select>
                    <div v-if="errors.type_projet" class="text-danger">{{ errors.type_projet[0] }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success" id="add-btn">Ajouter Projet</button>
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
    data() {
        return {
            form: {
                id: null,
                nom_projet: '',
                description_projet: '',
                date_deb: '',
                date_fin: '',
                etat_projet: '',
                type_projet: '', // Projet, Chantier
            },
            errors: {},
            isLoading: false,
        };
    },
    methods: {
        // Créer un projet
        createProject() {
            this.isLoading = true;
            axios.post('/api/projets', this.form)
                .then(response => {
                    console.log(response.data);
                    this.closeModal();
                    toast.success("Projet créé avec succès !", {
                        "theme": "colored",
                        "type": "success",
                        "position": "bottom-right",
                        "dangerouslyHTMLString": true
                    });
                    setTimeout(() => {
                        this.$emit("refresh"); // Émet l'événement pour rafraîchir la liste des utilisateurs
                    }, 3500);
                })
                .catch(error => {
                    console.error('Erreur lors de la création du projet:', error);

                    if (error.response && error.response.data) {
                        this.errors = error.response.data.errors || {};
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                    this.form = {
                        id: null,
                        nom_projet: '',
                        description_projet: '',
                        date_deb: '',
                        date_fin: '',
                        etat_projet: '',
                        type_projet: '',
                    };
                });
        },
        // Fermer la modal et réinitialiser le formulaire
        closeModal() {
            this.project = {
                id: null,
                nom_projet: '',
                description_projet: '',
                date_deb: '',
                date_fin: '',
                etat_projet: 'en cours',
                type_projet: 'agile',
            };
            this.errors = {};
        },
    },
};
</script>  