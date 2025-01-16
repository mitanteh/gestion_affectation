<template>
    <div class="page-content">
      <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">Liste des Projets</h4>
              <div class="page-title-right">
                <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item"><a href="javascript: void(0);">Liste des Projets</a></li>
                  <li class="breadcrumb-item active">Liste des Projets</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- End Page Title -->
  
        <div class="row" id="usersList">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row g-lg-2 g-4">
                  <div class="col-lg-3">
                    <div class="search-box">
                      <input
                        type="text"
                        class="form-control search"
                        placeholder="Search for projects..."
                        v-model="searchQuery"
                        @input="filteredProjets"
                      />
                      <i class="ri-search-line search-icon"></i>
                    </div>
                  </div>
                  <div class="col-lg-auto col-sm-3 ms-auto">
                    <button
                      type="button"
                      class="btn btn-primary w-100 add-btn"
                      data-bs-toggle="modal"
                      data-bs-target="#showModal"
                      @click="openCreateModal"
                    >
                      Ajouter un projet
                    </button>
                  </div>
                  <div class="col-lg-auto col-sm-9">
                    <select
                      class="form-control"
                      v-model="selectedStatus"
                      @change="filteredProjets"
                    >
                      <option value="all">Tous</option>
                      <option value="En cours">En cours</option>
                      <option value="Terminé">Terminé</option>
                      <option value="En retard">En retard</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
  
            <div class="card">
              <div class="card-body">
                <div class="table-responsive table-card mb-1">
                  <table class="table align-middle table-nowrap" id="customerTable">
                    <thead class="table-light">
                      <tr>
                        <th class="sort desc" data-sort="project_name">#</th> <!-- Changement ici -->
                        <th class="sort desc" data-sort="project_name">Nom du projet</th>
                        <th class="sort" data-sort="status">Statut</th>
                        <th class="sort" data-sort="progress">Avancement</th>
                        <th data-sort="action" width="10">Action</th>
                      </tr>
                    </thead>
                    <tbody class="list form-check-all">
                      <tr v-for="(project, index) in filteredProjets" :key="project.id">
                        <th scope="row">{{ index + 1 }}</th> <!-- Affichage du numéro incrémenté -->
                        <td class="project_name">{{ project.nom_projet }}</td>
                        <td class="status">
                          <span :class="getStatusClass(project.etat_projet)">{{ project.etat_projet }}</span>
                        </td>
                        <td>{{ project.taux_avancement }}%</td>
                        <td>
                          <div class="d-flex gap-2">
                            <div class="edit">
                              <router-link :to="{ name:'ProjectDetail', params: { id: project.id }}" class="btn btn-sm btn-soft-secondary">Details</router-link>
                            </div>
                            <div class="edit">
                              <button
                                class="btn btn-sm btn-soft-info"
                                @click="openEditModal(project)"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                              >
                                Modifier
                              </button>
                            </div>
                            <div class="remove">
                              <button
                                class="btn btn-sm btn-soft-danger remove-item-btn"
                                @click="deleteProject(project.id)"
                              >
                                Supprimer
                              </button>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="noresult" v-if="filteredProjets.length === 0" style="display: block">
                    <div class="text-center">
                      <lord-icon
                        src="https://cdn.lordicon.com/msoeawqm.json"
                        trigger="loop"
                        colors="primary:#121331,secondary:#08a88a"
                        style="width: 75px; height: 75px"
                      ></lord-icon>
                      <h5 class="mt-2">Désolé ! Aucun résultat trouvé</h5>
                      <p class="text-muted mb-0">
                        Nous avons cherché dans plus de {{ this.filteredProjets.length }}+ projets, mais nous n'avons trouvé aucun résultat correspondant à votre recherche.
                      </p>
                    </div>
                  </div>
                </div>
  
                <div class="row" id="pagination-element" style="display: flex;">
                    <div class="col-lg-12">
                        <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                            <div class="page-item" :class="{ 'disabled': currentPage <= 1 }">
                                <a href="javascript:void(0);" class="page-link" @click="changePage(currentPage - 1)" id="page-prev">Précedent</a>
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
          </div>
        </div>
  
        <!-- Modal pour ajouter ou éditer un projet -->
        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <Create @refresh="refreshProjets" />
          </div>
        </div>
  
        <!-- Modal de Modification -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <Edit :project="project" @refresh="refreshProjets"/>
            </div>
        </div>
      </div>
    </div>
</template>
  
<script>
import Create from "./Create.vue";
import Edit from "./Edit.vue";
import Swal from "sweetalert2";

export default {
    name: 'ProjectList',
    components: {
        Create,
        Edit,
    },
    data() {
        return {
            // projets: [],
            projets: [],
            searchQuery: '',
            selectedStatus: 'all',
            project: {
                id: null,
                nom_projet: '',
                description_projet: '',
                date_deb: '',
                date_fin: '',
                etat_projet: '',
                type_projet: '', // Projet, Chantier
            },
            errors: {},
            currentPage: 1,
            perPage: 10,
            totalPages: 0,
            total: 0,
        };
    },
    mounted() {
        this.fetchProjets();
    },
    computed: {
        filteredProjets() {
            let filtered = this.projets;

            // Filtrer par la recherche
            if (this.searchQuery) {
                filtered = filtered.filter(project =>
                    project.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }

            // Filtrer par statut
            if (this.selectedStatus !== 'all') {
                filtered = filtered.filter(project => project.status === this.selectedStatus);
            }

            return filtered; // Retourner la liste filtrée
        },
    },
    methods: {
        // Récupérer les projets depuis l'API
        fetchProjets() {
            axios.get('/api/projets/?page=' + this.currentPage)
                .then(response => {
                    this.projets = response.data.data;
                    this.totalPages = response.data.last_page;
                    this.total = response.data.total;
                })
                .catch(error => {
                    console.error('Erreur de récupération des projets:', error);
                    this.errors = 'Erreur lors de la récupération des projets. Veuillez réessayer.';
                });
        },
        refreshProjets() {
            this.fetchProjets();
        },
        // Créer un projet
        createProject() {
            this.$axios
                .post('/api/projets', this.project, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                })
                .then(response => {
                    this.projets.push(response.data);
                    this.closeModal();
                })
                .catch(error => {
                    console.error('Erreur lors de la création du projet:', error);

                    // Gestion des erreurs du backend
                    if (error.response && error.response.data) {
                        this.errors = error.response.data.message || 'Une erreur est survenue lors de la création du projet.';
                    } else {
                        this.errors = error;
                    }
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
            this.errors = ''; // Réinitialiser le message d'erreur
            $('#editModal').modal('hide');
            $('#showModal').modal('hide');
        },
        // Ouvrir la modal de modification et remplir les champs avec les données existantes
        openEditModal(project) {
            this.project = { ...project };  // Remplir les champs de la modal avec les données du projet sélectionné
            $('#editModal').modal('show');
        },
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
        changePage(page) {
            if (page > 0 && page <= this.totalPages) {
                this.currentPage = page;
                this.fetchProjets(); // Récupérer les pneus pour la page actuelle
            }
        },
        deleteProject(projectId) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/projets/${projectId}`)
                        .then(() => {
                            Swal.fire(
                                'Supprimé !',
                                'Le projet a été supprimé avec succès.',
                                'success'
                            );
                            this.fetchProjets(); // Actualiser la liste
                        })
                        .catch(error => {
                            console.error('Erreur lors de la suppression:', error);
                            Swal.fire(
                                'Erreur !',
                                'Une erreur est survenue lors de la suppression.',
                                'error'
                            );
                        });
                }
            });
        },
    },
};
</script>  