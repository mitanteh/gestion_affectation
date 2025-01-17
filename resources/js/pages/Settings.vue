<template>
    <div>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-12 col-lg-6 order-first">
                        <div class="row row-cols-xxl-4 row-cols-1">
                            <div class="col">
                                <!-- card -->
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h5 class="card-title flex-grow-1 mb-0">Compétences</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Formulaire d'ajout de compétence -->
                                        <form @submit.prevent="addSkill">
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input
                                                            type="text"
                                                            v-model="newSkill"
                                                            class="form-control border-light bg-light"
                                                            placeholder="Ajouter une nouvelle compétence..."
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="mdi mdi-send"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Liste des compétences -->
                                        <div
                                            class="p-3 border-bottom border-bottom-dashed"
                                            v-for="(skill, index) in skills"
                                            :key="index"
                                        >
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ skill.text }}</h6>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <button class="btn btn-icon btn-sm btn-soft-danger">
                                                        <i class="ph-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col-xxl-6">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h5 class="card-title flex-grow-1 mb-0">Informations Personnelles</h5>
                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="updatePersonalInfo">
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="userName">Nom:</label>
                                                    <input
                                                        type="text"
                                                        v-model="userName"
                                                        class="form-control"
                                                        id="userName"
                                                        placeholder="Entrez votre nom"
                                                    />
                                                </div>
                                                <div class="col">
                                                    <label for="userPrenom">Prénom:</label>
                                                    <input
                                                        type="text"
                                                        v-model="userPrenom"
                                                        class="form-control"
                                                        id="userPrenom"
                                                        placeholder="Entrez votre prénom"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="userEmail">Email:</label>
                                                    <input
                                                        type="email"
                                                        v-model="userEmail"
                                                        class="form-control"
                                                        id="userEmail"
                                                        placeholder="Entrez votre email"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="userPhone">Téléphone:</label>
                                                    <input
                                                        type="tel"
                                                        v-model="userPhone"
                                                        class="form-control"
                                                        id="userPhone"
                                                        placeholder="Entrez votre numéro de téléphone"
                                                    />
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <!-- Section Mise à jour du mot de passe -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h5 class="card-title flex-grow-1 mb-0">Mise à jour du mot de passe</h5>
                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="updatePassword">
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="currentPassword">Mot de passe actuel:</label>
                                                    <input
                                                        type="password"
                                                        v-model="currentPassword"
                                                        class="form-control"
                                                        id="currentPassword"
                                                        placeholder="Entrez votre mot de passe actuel"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="newPassword">Nouveau mot de passe:</label>
                                                    <input
                                                        type="password"
                                                        v-model="newPassword"
                                                        class="form-control"
                                                        id="newPassword"
                                                        placeholder="Entrez un nouveau mot de passe"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="confirmPassword">Confirmer le mot de passe:</label>
                                                    <input
                                                        type="password"
                                                        v-model="confirmPassword"
                                                        class="form-control"
                                                        id="confirmPassword"
                                                        placeholder="Confirmez votre nouveau mot de passe"
                                                    />
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            <div class="col">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h5 class="card-title flex-grow-1 mb-0">Congés</h5>
                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submitVacation">
                                            <div class="row g-2 mb-3">
                                                <div class="col">
                                                    <label for="vacationStart">Début des congés:</label>
                                                    <input
                                                        type="date"
                                                        v-model="vacationStart"
                                                        class="form-control"
                                                        id="vacationStart"
                                                    />
                                                </div>
                                                <div class="col">
                                                    <label for="vacationEnd">Fin des congés:</label>
                                                    <input
                                                        type="date"
                                                        v-model="vacationEnd"
                                                        class="form-control"
                                                        id="vacationEnd"
                                                    />
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Soumettre</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div> <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
    name: 'Settings',
    data() {
        return {
            userId: 1, // Id de l'utilisateur
            newSkill: "", // Nouvelle compétence saisie par l'utilisateur
            skills: [], // Liste des compétences
            vacationStart: '', // Date de début des congés
            vacationEnd: '', // Date de fin des congés
            backupVacations: [], // Liste des congés du backup
            userName: '', // Nom de l'utilisateur
            userPrenom: '', // Prénom de l'utilisateur
            userEmail: '', // Email de l'utilisateur
            userPhone: '', // Téléphone de l'utilisateur
            currentPassword: '', // Mot de passe actuel
            newPassword: '', // Nouveau mot de passe
            confirmPassword: '', // Confirmation du nouveau mot de passe
        };
    },
    methods: {
        async addSkill() {
            if (this.newSkill.trim() === "") return;

            try {
                // Préparer les données pour l'API
                const formData = {
                    user_id: this.userId,
                    lib_comp: this.newSkill.trim(),
                };

                // Appel API pour enregistrer la compétence
                await axios.post("/api/competences", formData);

                toast.success("Compétence créée avec succès !", {
                        "theme": "colored",
                        "type": "success",
                        "position": "bottom-right",
                        "dangerouslyHTMLString": true
                    });

                // Récupérer les compétences mises à jour depuis l'API
                this.getSkills();

                // Réinitialisation du champ de saisie
                this.newSkill = "";
            } catch (error) {
                console.error("Erreur lors de l'enregistrement de la compétence :", error);
            }
        },

        async getSkills() {
            try {
                const response = await axios.get('/api/competences');
                this.skills = response.data.map(skill => ({
                    text: skill.lib_comp,
                }));
            } catch (error) {
                console.error("Erreur lors du chargement des compétences :", error);
            }
        },

        async submitVacation() {
            if (this.vacationStart === '' || this.vacationEnd === '') {
                toast.error("Veuillez sélectionner les dates de début et de fin des congés.", {
                    "theme": "colored",
                    "type": "error",
                    "position": "bottom-right",
                    "dangerouslyHTMLString": true
                });
                return;
            }

            const userVacationRange = {
                start: new Date(this.vacationStart),
                end: new Date(this.vacationEnd)
            };

            const conflict = this.backupVacations.some(backupVacation => {
                const backupStart = new Date(backupVacation.start);
                const backupEnd = new Date(backupVacation.end);
                return (userVacationRange.start <= backupEnd && userVacationRange.end >= backupStart);
            });

            if (conflict) {
                toast.error("Les dates sélectionnées chevauchent celles de votre backup.", {
                    "theme": "colored",
                    "type": "error",
                    "position": "bottom-right",
                    "dangerouslyHTMLString": true
                });
            } else {
                toast.success("Congés enregistrés avec succès !", {
                    "theme": "colored",
                    "type": "success",
                    "position": "bottom-right",
                    "dangerouslyHTMLString": true
                });
                // Logic to save the vacation dates
            }
        },
        async updatePersonalInfo() {
            try {
                const formData = {
                    user_id: this.userId,
                    nom_user: this.userName,
                    prenom_user: this.userPrenom,
                    email: this.userEmail,
                    phone: this.userPhone,
                };

                await axios.put(`/api/users/${this.userId}`, formData);

                toast.success("Informations personnelles mises à jour avec succès !", {
                    "theme": "colored",
                    "type": "success",
                    "position": "bottom-right",
                    "dangerouslyHTMLString": true
                });
            } catch (error) {
                console.error("Erreur lors de la mise à jour des informations personnelles :", error);
            }
        },
        async updatePassword() {
            if (this.newPassword !== this.confirmPassword) {
                toast.error("Les nouveaux mots de passe ne correspondent pas.", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                });
                return;
            }

            try {
                const formData = {
                    current_password: this.currentPassword,
                    new_password: this.newPassword,
                };

                await axios.put(`/api/users/${this.userId}/update-password`, formData);

                toast.success("Mot de passe mis à jour avec succès !", {
                    theme: "colored",
                    type: "success",
                    position: "bottom-right",
                });

                // Réinitialiser les champs
                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
            } catch (error) {
                console.error("Erreur lors de la mise à jour du mot de passe :", error);
                toast.error("Erreur lors de la mise à jour du mot de passe.", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                });
            }
        },
    },
    mounted() {
        // Charger les compétences à l'initialisation
        this.getSkills();
    },
};
</script>