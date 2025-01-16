<template>
    <div class="page-content">
        <Loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
            loader="spinner"
        />
      <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">Liste des {{ this.$route.params.role }}</h4>
              <div class="page-title-right">
                <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item"><a href="javascript: void(0);">Liste des Ressources</a></li>
                  <li class="breadcrumb-item active">Liste des {{ this.$route.params.role }}</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- End Page Title -->
  
        <div class="row" id="userList">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="row g-lg-2 g-4">
                  <div class="col-lg-3">
                    <div class="search-box">
                      <input
                        type="text"
                        class="form-control search"
                        placeholder="Rechercher des utilisateurs..."
                        v-model="searchQuery"
                        @input="filteredUsers"
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
                      Ajouter un utilisateur
                    </button>
                  </div>
                  <div class="col-lg-auto col-sm-9">
                    <select
                      class="form-control"
                      v-model="selectedRole"
                      @change="filteredUsers"
                    >
                      <option value="all">Tous</option>
                      <option v-for="(role, index) in roles" :key="role.id" :value="role.lib_role">{{ role.lib_role }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
  
            <div class="card">
              <div class="card-body">
                <div class="table-responsive table-card mb-1">
                  <table class="table align-middle table-nowrap" id="userTable">
                    <thead class="table-light">
                      <tr>
                        <th class="sort" data-sort="user_name">#</th>
                        <th class="sort" data-sort="user_name">Nom d'utilisateur</th>
                        <th class="sort" data-sort="role">Rôle</th>
                        <th class="sort" data-sort="status">Statut</th>
                        <th data-sort="action">Action</th>
                      </tr>
                    </thead>
                    <tbody class="list form-check-all">
                      <tr v-for="(user, index) in filteredUsers" :key="user.id">
                        <th scope="row">{{ index + 1 }}</th>
                        <td class="user_name">{{ user.prenom_user }} <strong>{{ user.nom_user }}</strong></td>
                        <td class="role">{{ user.role.lib_role }}</td>
                        <td class="status">
                          <span :class="getStatusClass(user.statut)">{{ user.statut }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <div>
                                    <router-link :to="{ name:'ResourceDetail', params: { id: user.id }}" class="btn btn-sm btn-soft-secondary">Details</router-link>
                                </div>
                            </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="noresult" v-if="filteredUsers.length === 0" style="display: block">
                    <div class="text-center">
                      <lord-icon
                        src="https://cdn.lordicon.com/msoeawqm.json"
                        trigger="loop"
                        colors="primary:#121331,secondary:#08a88a"
                        style="width: 75px; height: 75px"
                      ></lord-icon>
                      <h5 class="mt-2">Désolé ! Aucun résultat trouvé</h5>
                      <p class="text-muted mb-0">
                        Nous avons cherché dans plus de {{ this.filteredUsers.length }}+ utilisateurs, mais nous n'avons trouvé aucun résultat correspondant à votre recherche.
                      </p>
                    </div>
                  </div>
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
          </div>
        </div>
  
        <!-- Modal pour ajouter ou éditer un utilisateur -->
        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <Create @close="closeModal" @refresh="refreshUsers"/>
          </div>
        </div>
  
        <!-- Modal de Modification -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <Edit :user="user" @close="closeModal" @refresh="refreshUsers" />
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Create from "./Create.vue";
  import Edit from "./Edit.vue";
  export default {
    name: 'UserList',
    components: {
      Create,
      Edit,
    },
    data() {
      return {
        users: [],
        searchQuery: '',
        selectedRole: 'all',
        user: '',
        errors: {},
        currentPage: 1,
        perPage: 10,
        totalPages: 0,
        total: 0,
        roles: [],
        isLoading: false,
      };
    },
    // mounted() {
    //   this.fetchUsers();
    // },
    computed: {
      filteredUsers() {
        let filtered = this.users;
  
        // Filtrer par la recherche
        if (this.searchQuery) {
          filtered = filtered.filter(user =>
            user.nom_user.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        }
  
        return filtered;
      },
    },
    methods: {
        async fetchUsers(role) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/users/resources/' + role + '/?page=' + this.currentPage);
                this.users = response.data.data;
                this.totalPages = response.data.last_page;
                this.total = response.data.total;
            } catch (error) {
                console.error("Erreur lors de la récupération des utilisateurs :", error);
                this.users = [];
            } finally {
                this.isLoading = false;
            }
        },
        loadUsersFromRoute() {
            const role = this.$route.params.role;
            if (role) {
                this.fetchUsers(role);
            }
        },
        refreshUsers() {
            this.fetchUsers();
        },
        openEditModal(user) {
            this.user = user ;
        },
        changePage(page) {
            this.currentPage = page;
            this.fetchUsers();
        },
        getStatusClass(status) {
            switch (status) {
                case 'actif':
                    return 'text-uppercase badge bg-success-subtle text-success';
                case 'inactif':
                    return 'text-uppercase badge bg-danger-subtle text-danger';
            }
        },
    },
    created() {
        // Charger les utilisateurs au montage du composant
        this.loadUsersFromRoute();
    },
    watch: {
        // Surveiller les changements de route
        '$route'(to, from) {
            this.fetchUsers(to.params.role);
        },
    },

};
</script>
  