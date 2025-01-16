<template>
    <div class="page-content">
      <div class="container-fluid">
        <!-- Titre de la page -->
        <div class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">Gestion des Congés</h4>
              <div class="page-title-right">
                <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item"><a href="javascript:void(0);">Congés</a></li>
                  <li class="breadcrumb-item active">Liste des Congés</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Contenu principal -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row g-3">
                  <!-- Recherche -->
                  <div class="col-lg-4">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Rechercher un congé..."
                      v-model="searchQuery"
                      @input="filterLeaves"
                    />
                  </div>
  
                  <!-- Filtre par statut -->
                  <div class="col-lg-3">
                    <select
                      class="form-control"
                      v-model="selectedStatus"
                      @change="filterLeaves"
                    >
                      <option value="all">Tous les statuts</option>
                      <option value="approved">Approuvé</option>
                      <option value="pending">En attente</option>
                      <option value="rejected">Rejeté</option>
                    </select>
                  </div>
  
                  <!-- Bouton pour ajouter un congé -->
                  <div class="col-lg-auto ms-auto">
                    <button
                      type="button"
                      class="btn btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#addLeaveModal"
                      @click="openAddModal"
                    >
                      Ajouter un congé
                    </button>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Tableau des congés -->
            <div class="card">
              <div class="card-body">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Employé</th>
                      <th>Date de début</th>
                      <th>Date de fin</th>
                      <th>Statut</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(leave, index) in filteredLeaves" :key="leave.id">
                      <td>{{ index + 1 }}</td>
                      <td>{{ leave.employee }}</td>
                      <td>{{ leave.startDate }}</td>
                      <td>{{ leave.endDate }}</td>
                      <td>{{ leave.status }}</td>
                      <td>
                        <button
                          class="btn btn-sm btn-info"
                          @click="openEditModal(leave)"
                          data-bs-toggle="modal"
                          data-bs-target="#editLeaveModal"
                        >
                          Modifier
                        </button>
                        <button
                          class="btn btn-sm btn-danger"
                          @click="deleteLeave(leave.id)"
                        >
                          Supprimer
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
  
                <!-- Pagination -->
                <div class="d-flex justify-content-between">
                  <button
                    class="btn btn-secondary"
                    :disabled="currentPage === 1"
                    @click="changePage(currentPage - 1)"
                  >
                    Précédent
                  </button>
  
                  <span>Page {{ currentPage }} sur {{ totalPages }}</span>
  
                  <button
                    class="btn btn-secondary"
                    :disabled="currentPage === totalPages"
                    @click="changePage(currentPage + 1)"
                  >
                    Suivant
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Modals -->
        <AddLeaveModal @refresh="fetchLeaves" />
        <EditLeaveModal :leave="selectedLeave" @refresh="fetchLeaves" />
      </div>
    </div>
  </template>
  
  <script>
  import AddLeaveModal from "./AddLeaveModal.vue";
  import EditLeaveModal from "./EditLeaveModal.vue";
  
  export default {
    name: "LeaveManagement",
    components: { AddLeaveModal, EditLeaveModal },
    data() {
      return {
        leaves: [],
        searchQuery: "",
        selectedStatus: "all",
        selectedLeave: null,
        currentPage: 1,
        totalPages: 1,
      };
    },
    computed: {
      filteredLeaves() {
        let filtered = this.leaves;
  
        if (this.searchQuery) {
          filtered = filtered.filter((leave) =>
            leave.employee.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        }
  
        if (this.selectedStatus !== "all") {
          filtered = filtered.filter((leave) => leave.status === this.selectedStatus);
        }
  
        return filtered;
      },
    },
    methods: {
      fetchLeaves() {
        // Exemple d'appel API pour récupérer les congés
        axios.get(`/api/leaves?page=${this.currentPage}`).then((response) => {
          this.leaves = response.data.data;
          this.totalPages = response.data.totalPages;
        });
      },
      changePage(page) {
        this.currentPage = page;
        this.fetchLeaves();
      },
      openAddModal() {
        this.selectedLeave = null;
      },
      openEditModal(leave) {
        this.selectedLeave = leave;
      },
      deleteLeave(id) {
        if (confirm("Voulez-vous vraiment supprimer ce congé ?")) {
          axios.delete(`/api/leaves/${id}`).then(() => {
            this.fetchLeaves();
          });
        }
      },
    },
    mounted() {
      this.fetchLeaves();
    },
  };
  </script>