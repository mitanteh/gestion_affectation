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
                                            <div class="row g-2 mt-2 mb-3">
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
            userId: 1, // Id de l'utilisateur (à remplacer par une récupération dynamique si nécessaire)
            newSkill: "", // Nouvelle compétence saisie par l'utilisateur
            skills: [], // Liste des compétences
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
    },
    mounted() {
        // Charger les compétences à l'initialisation
        this.getSkills();
    },
};
</script>
