<template>
  <div class="page-content">
    <div class="container-fluid">
      <!-- Titre de la page -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Gestion des Modules</h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Modules</a></li>
                <li class="breadcrumb-item active">Liste des Modules</li>
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
                    placeholder="Rechercher un module..."
                    v-model="searchQuery"
                    @input="filterItems"
                  />
                </div>

                <!-- Filtre par statut -->
                <div class="col-lg-3">
                  <select
                    class="form-control"
                    v-model="selectedStatus"
                    @change="filterItems"
                  >
                    <option value="all">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                  </select>
                </div>

                <!-- Bouton pour ajouter un module -->
                <div class="col-lg-auto ms-auto">
                  <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addItemModal"
                    @click="openAddModal"
                  >
                    Ajouter un module
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Tableau des modules -->
          <div class="card">
            <div class="card-body">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nom du module</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in filteredItems" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.status }}</td>
                    <td>
                      <button
                        class="btn btn-sm btn-info"
                        @click="openEditModal(item)"
                        data-bs-toggle="modal"
                        data-bs-target="#editItemModal"
                      >
                        Modifier
                      </button>
                      <button
                        class="btn btn-sm btn-danger"
                        @click="deleteItem(item.id)"
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
      <AddItemModal @refresh="fetchItems" />
      <EditItemModal :item="selectedItem" @refresh="fetchItems" />
    </div>
  </div>
</template>

<script>
import AddItemModal from "./AddItemModal.vue";
import EditItemModal from "./EditItemModal.vue";

export default {
  name: "ModulesManagement",
  components: { AddItemModal, EditItemModal },
  data() {
    return {
      items: [],
      searchQuery: "",
      selectedStatus: "all",
      selectedItem: null,
      currentPage: 1,
      totalPages: 1,
    };
  },
  computed: {
    filteredItems() {
      let filtered = this.items;

      if (this.searchQuery) {
        filtered = filtered.filter((item) =>
          item.name.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }

      if (this.selectedStatus !== "all") {
        filtered = filtered.filter((item) => item.status === this.selectedStatus);
      }

      return filtered;
    },
  },
  methods: {
    fetchItems() {
      // Exemple d'appel API pour récupérer les items
      axios.get(`/api/modules?page=${this.currentPage}`).then((response) => {
        this.items = response.data.data;
        this.totalPages = response.data.totalPages;
      });
    },
    changePage(page) {
      this.currentPage = page;
      this.fetchItems();
    },
    openAddModal() {
      this.selectedItem = null;
    },
    openEditModal(item) {
      this.selectedItem = item;
    },
    deleteItem(id) {
      if (confirm("Voulez-vous vraiment supprimer cet élément ?")) {
        axios.delete(`/api/modules/${id}`).then(() => {
          this.fetchItems();
        });
      }
    },
  },
  mounted() {
    this.fetchItems();
  },
};
</script>