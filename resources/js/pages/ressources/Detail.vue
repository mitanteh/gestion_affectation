<template>
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Fiche ressource</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);" @click="$router.go(-1)">
                                        Liste des {{ user.lib_role }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">{{ user.prenom_user }} {{ user.nom_user }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex border-bottom border-bottom-dashed pb-3 mb-3">
                                <div class="flex-grow-1">
                                    <h5>{{ user.prenom_user }} {{ user.nom_user }}</h5>
                                    <p class="text-muted mb-0">{{ user.lib_role }}</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td>Email ID</td>
                                            <td class="fw-medium">{{ user.email }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Backup</h5>
                        </div>
                        <div class="card-body">
                            <div v-if="currentBackup" class="mt-2">
                                <p class="mb-1">Backup actuel :</p>
                                <div class="d-flex align-items-center">
                                    
                                </div>
                                <div class="d-flex pb-3">
                                    <div class="flex-grow-1">
                                        <h5>{{ currentBackup.prenom_user }} {{ currentBackup.nom_user }}</h5> 
                                    </div>
                                    <button 
                                        class="btn btn-sm btn-soft-danger"
                                        @click="confirmRemoveBackup"
                                    >
                                        <i class="ri-delete-bin-line align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                            <div v-else>
                                <form @submit.prevent="assignBackup">
                                    <div class="row g-2 mt-2 mb-3">
                                        <div class="col">
                                            <select v-model="selectedBackup" class="form-select">
                                                <option value="" selected disabled>Choisir un backup...</option>
                                                <option v-for="backup in availableBackups" :key="backup.id" :value="backup.id">
                                                    {{ backup.prenom_user }} {{ backup.nom_user }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary" :disabled="!selectedBackup">
                                                <i class="mdi mdi-content-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Compétences</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="addSkill">
                                <div class="row g-2 mt-2 mb-3">
                                    <div class="col">
                                        <select v-model="selectedSkill" class="form-select">
                                            <option value="" selected disabled>Choisir une compétence...</option>
                                            <option v-for="skill in availableSkills" :key="skill.id" :value="skill.id">
                                                {{ skill.lib_comp }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary" :disabled="!selectedSkill">
                                            <i class="mdi mdi-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="hstack gap-2 align-items-start flex-wrap">
                                <span v-for="skill in userSkills" :key="skill.id" :class="`badge ${skill.class}`">
                                    {{ skill.text }}
                                    <i class="ri-close-line ms-1 cursor-pointer" @click="confirmRemoveSkill(skill)"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-9">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Liste des projets</h5>
                            <button 
                                type="button" 
                                class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addProjectModal"
                                :disabled="userProjects.length >= 3"
                            >
                                <i class="ri-add-line align-middle me-1"></i> Assigner un projet
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card mb-1">
                                <table class="table align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nom du projet</th>
                                            <th>Statut</th>
                                            <th>Avancement</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(project, index) in projets" :key="project.id">
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td>{{ project.nom_projet }}</td>
                                            <td>
                                                <span :class="getStatusClass(project.etat_projet)">{{ project.etat_projet }}</span>
                                            </td>
                                            <td>{{ project.taux_avancement }}%</td>
                                            <td>
                                                <button 
                                                    class="btn btn-sm btn-soft-danger"
                                                    @click="confirmRemoveProject(project)"
                                                >
                                                    <i class="ri-delete-bin-line align-bottom"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!projets.length">
                                            <td colspan="5" class="text-center py-4">
                                                <div class="avatar-md mx-auto mb-4">
                                                    <div class="avatar-title rounded-circle bg-light text-primary">
                                                        <i class="ri-folder-user-line fs-24"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">Aucun projet assigné</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" id="pagination-element" style="display: flex;">
                                <div class="col-lg-12">
                                    <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    <div class="page-item" :class="{ 'disabled': currentPage <= 1 }">
                                        <a href="javascript:void(0);" class="page-link" @click="changePage(currentPage - 1)" id="page-prev">Précédent</a>
                                    </div>
                                    <span id="page-num" class="pagination">
                                        <div v-for="page in totalPages" :key="page" class="page-item" :class="{ 'active': page === currentPage }">
                                        <a class="page-link clickPageNumber" href="javascript:void(0);" @click="changePage(page)">{{ page }}</a>
                                        </div>
                                    </span>
                                    <div class="page-item" :class="{ 'disabled': currentPage >= totalPages }">
                                        <a href="javascript:void(0);" class="page-link" @click="changePage(currentPage + 1)" id="page-next">Suivant</a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Liste des tâches</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card mb-1">
                                <table class="table align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nom de la tâche</th>
                                            <th>Projet</th>
                                            <th>Statut</th>
                                            <th>Priorité</th>
                                            <th>Date limite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(task, index) in tasks" :key="task.id">
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td>
                                                <a href="#" @click.prevent="showTaskDetails(task)">{{ task.titre }}</a>
                                            </td>
                                            <td>{{ task.projet.nom_projet }}</td>
                                            <td>
                                                <select 
                                                    v-model="task.statut" 
                                                    class="form-select form-select-sm" 
                                                    style="width: 130px;"
                                                    @change="updateTaskStatus(task)"
                                                    :disabled="task.statut === 'Terminé'"
                                                >
                                                    <option value="A démarrer">A démarrer</option>
                                                    <option value="En cours">En cours</option>
                                                    <option value="Terminé">Terminé</option>
                                                    <option value="En retard">En retard</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span :class="getPriorityClass(task.priorite)">{{ task.priorite }}</span>
                                            </td>
                                            <td>{{ formatDate(task.date_limite) }}</td>
                                        </tr>
                                        <tr v-if="!tasks.length">
                                            <td colspan="7" class="text-center py-4">
                                                <div class="avatar-md mx-auto mb-4">
                                                    <div class="avatar-title rounded-circle bg-light text-primary">
                                                        <i class="ri-task-line fs-24"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">Aucune tâche assignée</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row" v-if="tasks.length">
                                <div class="col-lg-12">
                                    <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                        <div class="page-item" :class="{ 'disabled': taskCurrentPage <= 1 }">
                                            <a href="javascript:void(0);" class="page-link" @click="changeTaskPage(taskCurrentPage - 1)">Précédent</a>
                                        </div>
                                        <span class="pagination">
                                            <div v-for="page in taskTotalPages" :key="page" class="page-item" :class="{ 'active': page === taskCurrentPage }">
                                                <a class="page-link" href="javascript:void(0);" @click="changeTaskPage(page)">{{ page }}</a>
                                            </div>
                                        </span>
                                        <div class="page-item" :class="{ 'disabled': taskCurrentPage >= taskTotalPages }">
                                            <a href="javascript:void(0);" class="page-link" @click="changeTaskPage(taskCurrentPage + 1)">Suivant</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal d'assignation de projet -->
                    <div class="modal fade" id="addProjectModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Assigner un projet</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent="assignProject">
                                        <div class="mb-3">
                                            <label class="form-label">Sélectionner un projet</label>
                                            <select v-model="selectedProject" class="form-select">
                                                <option value="" disabled selected>Choisir un projet...</option>
                                                <option 
                                                    v-for="project in availableProjects" 
                                                    :key="project.id" 
                                                    :value="project.id"
                                                >
                                                    {{ project.nom_projet }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Assigner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ajouter le modal de détail de tâche -->
                    <div class="modal fade" id="taskDetailModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Détail de la tâche</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" v-if="selectedTask">
                                    <div class="mb-3">
                                        <h5>{{ selectedTask.titre }}</h5>
                                        <p class="text-muted">{{ selectedTask.description }}</p>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Projet</label>
                                            <p class="form-control-static">{{ selectedTask.projet.nom_projet }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Date limite</label>
                                            <p class="form-control-static">{{ formatDate(selectedTask.date_limite) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Priorité</label>
                                            <p class="form-control-static">
                                                <span :class="getPriorityClass(selectedTask.priorite)">
                                                    {{ selectedTask.priorite }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Statut</label>
                                            <select 
                                                v-model="selectedTask.statut" 
                                                class="form-select"
                                                @change="updateTaskStatus(selectedTask)"
                                                :disabled="selectedTask.statut === 'Terminé'"
                                            >
                                                <option value="A démarrer">A démarrer</option>
                                                <option value="En cours">En cours</option>
                                                <option value="Terminé">Terminé</option>
                                                <option value="En retard">En retard</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Swal from 'sweetalert2';

export default {
    name: "RessourceDetail",
    data() {
        return {
            userId: this.$route.params.id,
            userSkills: [],
            availableSkills: [],
            selectedSkill: null,
            user: {},
            projets: [],
            currentPage: 1,
            totalPages: 0,
            badgeClasses: [
                "badge-gradient-primary",
                "badge-gradient-secondary",
                "badge-gradient-success",
                "badge-gradient-danger",
                "badge-gradient-warning",
                "badge-gradient-info",
                "badge-gradient-dark",
            ],
            userProjects: [],
            availableProjects: [],
            selectedProject: '',
            tasks: [],
            taskCurrentPage: 1,
            taskTotalPages: 0,
            selectedTask: null,
            selectedBackup: null,
            availableBackups: [],
            currentBackup: null,
        };
    },
    mounted() {
        this.fetchUser();
        this.fetchUserSkills();
        this.fetchAvailableSkills();
        this.fetchUserProjects();
        this.fetchAvailableProjects();
        this.fetchUserTasks();
        this.fetchAvailableBackups();
        this.fetchCurrentBackup();
    },
    methods: {
        async fetchUser() {
            try {
                const response = await axios.get(`/api/users/${this.userId}`);
                this.user = response.data;
            } catch (error) {
                toast.error("Erreur lors de la récupération de l'utilisateur", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async fetchUserSkills() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/competences`);
                this.userSkills = response.data.map((skill) => ({
                    text: skill.lib_comp,
                    id: skill.id,
                    class: this.randomBadgeClass(),
                }));
            } catch (error) {
                toast.error("Erreur lors de la récupération des compétences", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async fetchAvailableSkills() {
            try {
                const response = await axios.get("/api/competences");
                this.availableSkills = response.data.filter(
                    (skill) => !this.userSkills.some((userSkill) => userSkill.id === skill.id)
                );
            } catch (error) {
                toast.error("Erreur lors de la récupération des compétences disponibles", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async addSkill() {
            if (!this.selectedSkill) return;
            try {
                await axios.post(`/api/users/${this.userId}/competences/${this.selectedSkill}`);
                toast.success("Compétence ajoutée avec succès !", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.fetchUserSkills();
                this.fetchAvailableSkills();
                this.selectedSkill = null;
            } catch (error) {
                toast.error("Erreur lors de l'ajout de la compétence", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        randomBadgeClass() {
            return this.badgeClasses[Math.floor(Math.random() * this.badgeClasses.length)];
        },
        getStatusClass(status) {
            switch (status) {
                case "A démarrer":
                    return "badge bg-info-subtle text-info";
                case "En cours":
                    return "badge bg-warning-subtle text-warning";
                case "Terminé":
                    return "badge bg-success-subtle text-success";
                case "En retard":
                    return "badge bg-danger-subtle text-danger";
                default:
                    return "badge bg-secondary-subtle text-secondary";
            }
        },
        async fetchUserProjects() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/projects?page=${this.currentPage}`);
                this.projets = response.data.data;
                this.totalPages = response.data.last_page;
            } catch (error) {
                console.error("Erreur lors de la récupération des membres du projet:", error);
                toast.error("Erreur lors de la récupération des membres", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.fetchProjects();
            }
        },
        async fetchAvailableProjects() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/available-projects`);
                this.availableProjects = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération des projets disponibles:", error);
                toast.error("Erreur lors de la récupération des projets disponibles", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async assignProject() {
            if (!this.selectedProject) return;

            if (this.userProjects.length >= 3) {
                toast.error("L'utilisateur ne peut pas être assigné à plus de 3 projets", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                return;
            }

            try {
                await axios.post(`/api/users/${this.userId}/projects/${this.selectedProject}`);
                
                toast.success("Projet assigné avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                
                // Actualiser les données
                await this.fetchUserProjects();
                await this.fetchAvailableProjects();

                // Fermer le modal
                const modal = document.getElementById('addProjectModal');
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }

                // Réinitialiser la sélection
                this.selectedProject = '';
            } catch (error) {
                console.error("Erreur lors de l'assignation du projet:", error);
                toast.error(error.response?.data?.message || "Erreur lors de l'assignation du projet", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        confirmRemoveProject(project) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment retirer le projet "${project.nom_projet}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeProject(project.id);
                }
            });
        },
        async removeProject(projectId) {
            try {
                await axios.delete(`/api/users/${this.userId}/projects/${projectId}`);
                
                toast.success("Projet retiré avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                
                // Actualiser les données
                await this.fetchUserProjects();
                await this.fetchAvailableProjects();
            } catch (error) {
                console.error("Erreur lors du retrait du projet:", error);
                toast.error("Erreur lors du retrait du projet", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        },
        getStatusBadgeClass(status) {
            const classes = {
                'En cours': 'badge bg-warning',
                'Terminé': 'badge bg-success',
                'En attente': 'badge bg-info',
                'Annulé': 'badge bg-danger'
            };
            return classes[status] || 'badge bg-secondary';
        },
        confirmRemoveSkill(skill) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment supprimer la compétence "${skill.text}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeSkill(skill.id);
                }
            });
        },
        async removeSkill(skillId) {
            try {
                await axios.delete(`/api/users/${this.userId}/competences/${skillId}`);
                
                toast.success("Compétence supprimée avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                
                await this.fetchUserSkills();
                await this.fetchAvailableSkills();
            } catch (error) {
                toast.error("Erreur lors de la suppression de la compétence", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async fetchUserTasks() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/tasks?page=${this.taskCurrentPage}`);
                this.tasks = response.data.data;
                this.taskTotalPages = response.data.last_page;
            } catch (error) {
                toast.error("Erreur lors de la récupération des tâches", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        changeTaskPage(page) {
            if (page >= 1 && page <= this.taskTotalPages) {
                this.taskCurrentPage = page;
                this.fetchUserTasks();
            }
        },
        getPriorityClass(priority) {
            const classes = {
                'Haute': 'badge bg-danger-subtle text-danger',
                'Moyenne': 'badge bg-warning-subtle text-warning',
                'Basse': 'badge bg-success-subtle text-success'
            };
            return classes[priority] || 'badge bg-secondary-subtle text-secondary';
        },
        showTaskDetails(task) {
            this.selectedTask = task;
            const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            modal.show();
        },
        async updateTaskStatus(task) {
            // On stocke l'ancien statut
            const previousStatus = task.statut;

            try {
                await axios.patch(`/api/tasks/${task.id}/status`, {
                    statut: task.statut
                });
                
                toast.success("Statut mis à jour avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });

                // Si le nouveau statut est "Terminé", on rafraîchit les données
                if (task.statut === 'Terminé') {
                    await this.fetchUserTasks();
                }
            } catch (error) {
                // En cas d'erreur, on restaure l'ancien statut
                task.statut = previousStatus;
                toast.error("Erreur lors de la mise à jour du statut", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async fetchAvailableBackups() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/potential-backups`);
                this.availableBackups = response.data;
            } catch (error) {
                toast.error("Erreur lors de la récupération des backups disponibles", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async fetchCurrentBackup() {
            try {
                const response = await axios.get(`/api/users/${this.userId}/assigned-backup`);
                this.currentBackup = response.data;
            } catch (error) {
                toast.error("Erreur lors de la récupération du backup actuel", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        async assignBackup() {
            if (!this.selectedBackup) return;
            
            try {
                await axios.post(`/api/users/${this.userId}/backup/${this.selectedBackup}`);
                await this.fetchBackupData();
                this.selectedBackup = null;
                
                toast.success("Backup assigné avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            } catch (error) {
                toast.error("Erreur lors de l'assignation du backup", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
        confirmRemoveBackup() {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Voulez-vous vraiment supprimer ce backup ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeBackup();
                }
            });
        },
        async removeBackup() {
            try {
                await axios.delete(`/api/users/${this.userId}/remove-backup`);
                await this.fetchBackupData();
                
                toast.success("Backup supprimé avec succès", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            } catch (error) {
                toast.error("Erreur lors de la suppression du backup", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },
    },
};
</script>
