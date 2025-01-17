<template>
    <div>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xxl-12 col-lg-6 order-first">
                        <div class="row row-cols-xxl-4 row-cols-1">
                            <div class="col">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-secondary opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Total Projets</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="0">{{ totalProjects }}</span></h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-secondary-subtle text-secondary rounded fs-3">
                                                    <i class="ph-folder"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-success opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Projets Terminés</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="0">{{ totalTasksFinish }}</span></h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                                    <i class="ph-sketch-logo"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-info opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Total Tâches en Cours</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="0">{{ totalTasksInProgress }}</span></h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                                    <i class="  ph-film-script-thin"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-warning opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Total Ressources</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="0">{{ totalUsers }}</span></h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                                    <i class="ph-users"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div>
                </div>

                <div class="row widget-responsive-fullscreen">
                    <div class="col-xxl-3">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Meilleure Ressource</h4>
                            </div><!-- end card header -->

                            <div class="card-body text-center">
                                <div v-if="bestResource" class="best-resource align-items-center">
                                    <div class="avatar-xl mb-3 mx-auto">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary fs-3">
                                            {{ getInitials(bestResource.nom_user, bestResource.prenom_user) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h5>{{ bestResource.prenom_user }} {{ bestResource.nom_user }}</h5>
                                        <span class="mb-3" :class="getRoleBadgeClass(bestResource.role?.lib_role)">
                                            {{ bestResource.role?.lib_role }}
                                        </span>
                                        <p><strong>Tâches terminées :</strong> {{ bestResource.completed_tasks_count }}</p>
                                    </div>
                                </div>
                                <div v-else>
                                    <p>Aucune ressource trouvée.</p>
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                    <div class="col-xxl-9">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Derniers Projets</h4>
                                <div class="ms-auto">
                                    <router-link :to="{ name: 'ProjectList' }" class="btn btn-sm btn-primary">Voir tous</router-link>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="sort desc" data-sort="project_name">#</th>
                                                <th class="sort desc" data-sort="project_name">Nom du projet</th>
                                                <th class="sort" data-sort="status">Statut</th>
                                                <th class="sort" data-sort="progress">Avancement</th>
                                                <th data-sort="action" width="10">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <tr v-for="(project, index) in lastProjects" :key="project.id">
                                                <th scope="row">{{ index + 1 }}</th>
                                                <td class="project_name">{{ project.nom_projet }}</td>
                                                <td class="status">
                                                    <span :class="getStatusClass(project.etat_projet)">{{ project.etat_projet }}</span>
                                                </td>
                                                <td>{{ project.taux_avancement }}%</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="edit">
                                                            <router-link :to="{ name:'ProjectDetail', params: { id: project.id }}" class="btn btn-sm btn-soft-secondary">Détails</router-link>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="noresult" v-if="lastProjects.length === 0" style="display: block">
                                        <div class="text-center">
                                            <lord-icon
                                                src="https://cdn.lordicon.com/msoeawqm.json"
                                                trigger="loop"
                                                colors="primary:#121331,secondary:#08a88a"
                                                style="width: 75px; height: 75px"
                                            ></lord-icon>
                                            <h5 class="mt-2">Désolé ! Aucun résultat trouvé</h5>
                                            <p class="text-muted mb-0">
                                                Nous avons cherché dans plus de {{ this.lastProjects.length }}+ projets, mais nous n'avons trouvé aucun résultat correspondant à votre recherche.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            totalProjects: 0,
            totalTasksInProgress: 0,
            totalUsers: 0,
            totalTasksFinish: 0,
            thisMonthRevenue: '$10,532',
            lastMonthRevenue: '$9,532',
            lastProjects: [], // Liste des 5 derniers projets
            bestResource: null // Ajoutez cette ligne
        };
    },
    methods: {
        
        getStatusClass(status) {
            switch (status) {
                case 'A démarrer':
                    return 'badge bg-info-subtle text-info'; // Example class for "In Progress" (you can change this to your preferred class)
                case 'En cours':
                    return 'badge bg-warning-subtle text-warning'; // Example class for "In Progress" (you can change this to your preferred class)
                case 'Terminé':
                    return 'badge bg-success-subtle text-success'; // Example class for "Completed"
                case 'En retard':
                    return 'badge bg-danger-subtle text-danger'; // Example class for "Late"
                default:
                    return ''; // Return em pty if status is unknown
            }
        },
        getRoleBadgeClass(role) {
            const classes = {
                'ADMIN': 'badge bg-danger-subtle text-danger',
                'MANAGER': 'badge bg-info-subtle text-info',
                'CHEF DE PROJET': 'badge bg-success-subtle text-success',
                'DEVELOPPEUR SENIOR': 'badge bg-primary-subtle text-primary',
                'DEVELOPPEUR': 'badge bg-warning-subtle text-warning',
                'INGENIEUR TEST': 'badge bg-secondary-subtle text-secondary'
            };
            return classes[role?.toUpperCase()] || 'badge bg-light text-dark';
        },
        async fetchDashboardData() {
            try {
                // Remplacez l'URL par celle de votre API
                const response = await axios.get('/api/dashboard-data');
                const data = response.data;

                // Mettez à jour les données du tableau de bord
                this.totalProjects = data.totalProjects;
                this.totalTasksInProgress = data.totalTasksInProgress;
                this.totalUsers = data.totalUsers;
                this.totalTasksFinish = data.totalTasksFinish;
                this.lastProjects = data.lastProjects; // Assurez-vous que votre API renvoie les 5 derniers projets
                this.bestResource = data.bestResource; // Assurez-vous que votre API renvoie la meilleure ressource
            } catch (error) {
                console.error('Erreur lors de la récupération des données du tableau de bord:', error);
            }
        },
        getInitials(nom, prenom) {
            return `${nom?.charAt(0) || ''}${prenom?.charAt(0) || ''}`.toUpperCase();
        },
    },
    mounted() {
        // Appel pour récupérer les données du tableau de bord
        this.fetchDashboardData();
    }
};
</script>