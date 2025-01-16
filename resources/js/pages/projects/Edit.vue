<template>
    <div class="modal-content">
        <Loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="false"
            loader="spinner"
        />
        <div class="modal-header px-4 pt-4">
            <h5 class="modal-title" id="exampleModalLabel">Modifier un Projet</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form @submit.prevent="updateProject" novalidate autocomplete="off">
            <div class="modal-body p-4" :class="{ 'disabled-form': isProjectCompleted }">
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

                <!-- État du Projet -->
                <div class="mb-3">
                    <label for="etat_projet" class="form-label">État du Projet</label>
                    <select :class="{ 'form-control border-danger': errors.etat_projet, 'form-control': !errors.etat_projet }" 
                        v-model="form.etat_projet" 
                        :disabled="isProjectCompleted"
                        required>
                        <option value="" disabled>Selectionner le statut</option>
                        <option value="A démarrer" :disabled="!canSetToStart">A démarrer</option>
                        <option value="En cours">En cours</option>
                        <option value="Glissement">Glissement</option>
                        <option value="Terminé">Terminé</option>
                        <option value="Annulé">Annulé</option>
                    </select>
                    <div v-if="errors.etat_projet" class="text-danger">{{ errors.etat_projet[0] }}</div>
                </div>

                <!-- Champs de glissement -->
                <div v-if="showSlippageFields" class="mb-3">
                    <div class="mb-3">
                        <label for="date_glissement" class="form-label">Date de Glissement</label>
                        <input type="date" id="date_glissement" 
                            class="form-control"
                            v-model="form.date_glissement" 
                            required />
                    </div>
                    
                    <div class="mb-3">
                        <label for="cause_glissement" class="form-label">Cause du Glissement</label>
                        <textarea id="cause_glissement" 
                            class="form-control" 
                            v-model="form.cause_glissement" 
                            required
                            placeholder="Expliquez la cause du glissement"></textarea>
                    </div>
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

                <!-- Taux d'avancement -->
                <div class="mb-3">
                    <label for="taux_avancement" class="form-label">Taux d'avancement (%)</label>
                    <div class="input-group">
                        <input type="number" 
                            id="taux_avancement" 
                            class="form-control" 
                            v-model="form.taux_avancement"
                            min="0"
                            max="100"
                            step="5"
                            @input="validateTauxAvancement"
                            required />
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar" 
                            role="progressbar" 
                            :style="{ width: form.taux_avancement + '%' }"
                            :aria-valuenow="form.taux_avancement" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                    <div v-if="errors.taux_avancement" class="text-danger">{{ errors.taux_avancement[0] }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success" id="add-btn" :disabled="isProjectCompleted">
                        Modifier Projet
                    </button>
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
        project: Object,
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
                date_glissement: '',
                cause_glissement: '',
                original_etat: '', // Pour stocker l'état initial
            },
            errors: {},
            isLoading: false,
        };
    },
    computed: {
        showSlippageFields() {
            return this.form.etat_projet === 'Glissement';
        },
        isProjectCompleted() {
            return this.project.etat_projet === 'Terminé';
        },
        canSetToStart() {
            // On ne peut mettre "A démarrer" que si c'était déjà l'état initial
            return this.form.original_etat === 'A démarrer';
        }
    },
    watch: {
        // Surveiller les changements de la prop `project`
        project: {
            immediate: true, // Exécute cette fonction dès que le composant est monté
            handler(newProject) {
                // Met à jour le formulaire avec les nouvelles données utilisateur
                this.form = {
                    id: newProject.id,
                    nom_projet: newProject.nom_projet,
                    description_projet: newProject.description_projet,
                    date_deb: this.formatDate(newProject.date_deb),
                    date_fin: this.formatDate(newProject.date_fin),
                    etat_projet: newProject.etat_projet,
                    type_projet: newProject.type_projet,
                    date_glissement: this.formatDate(newProject.date_glissement),
                    cause_glissement: newProject.cause_glissement || '',
                    original_etat: newProject.etat_projet,
                    taux_avancement: newProject.taux_avancement || 0,
                };
            },
        },
        'form.etat_projet': {
            handler(newEtat) {
                if (newEtat === 'Terminé') {
                    this.form.taux_avancement = 100;
                } else if (newEtat === 'A démarrer') {
                    this.form.taux_avancement = 0;
                }

                // Empêcher le retour à "A démarrer"
                if (newEtat === 'A démarrer' && this.form.original_etat !== 'A démarrer') {
                    this.form.etat_projet = this.form.original_etat;
                    toast.error("Impossible de revenir à l'état 'A démarrer'", {
                        theme: "colored",
                        position: "bottom-right"
                    });
                }

                // Réinitialiser les champs de glissement
                if (newEtat !== 'Glissement') {
                    this.form.date_glissement = '';
                    this.form.cause_glissement = '';
                }
            }
        }
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return '';
            return dateString.split('T')[0];
        },
        updateProject() {
            if (this.isProjectCompleted) {
                toast.error("Impossible de modifier un projet terminé", {
                    theme: "colored",
                    position: "bottom-right"
                });
                return;
            }

            // Validation des champs de glissement
            if (this.form.etat_projet === 'Glissement' && (!this.form.date_glissement || !this.form.cause_glissement)) {
                toast.error("Veuillez remplir les informations de glissement", {
                    theme: "colored",
                    position: "bottom-right"
                });
                return;
            }

            this.isLoading = true;
            axios.put('/api/projets/' + this.project.id, this.form)
                .then(response => { 
                    toast.success("Projet modifié avec succès !", {
                        "theme": "colored",
                        "position": "bottom-right"
                    });
                    document.getElementById('close-modal').click();
                    setTimeout(() => {
                        this.$emit("refresh");
                    }, 500);
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du projet:', error);

                    // Gestion des erreurs du backend
                    if (error.response && error.response.data) {
                        this.errors = error.response.data.message || 'Une erreur est survenue lors de la mise à jour du projet.';
                    } else {
                        this.errors = 'Erreur réseau, veuillez réessayer.';
                    }
                })
                .finally(() => {
                    this.isLoading = false;
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
        validateTauxAvancement() {
            let taux = Number(this.form.taux_avancement);
            
            // Vérifier les limites min/max
            if (taux < 0) taux = 0;
            if (taux > 100) taux = 100;
            
            // Mettre à jour la valeur
            this.form.taux_avancement = taux;

            // Ne pas permettre de revenir à 0% si le projet n'était pas initialement "A démarrer"
            if (taux === 0 && this.form.original_etat !== 'A démarrer') {
                this.form.taux_avancement = 1;
                toast.error("Impossible de revenir à 0% une fois le projet démarré", {
                    theme: "colored",
                    position: "bottom-right"
                });
            }
        },
    },
};
</script>

<style scoped>
.disabled-form {
    pointer-events: none;
    opacity: 0.6;
}

.progress-bar {
    background-color: #198754; /* Couleur verte Bootstrap */
    transition: width 0.3s ease;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}

select option:disabled {
    color: #999;
    font-style: italic;
}
</style>  