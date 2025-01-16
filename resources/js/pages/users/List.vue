<template>
    <div class="page-content">
      <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
          <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">Liste des Utilisateurs</h4>
              <div class="page-title-right">
                <ol class="breadcrumb m-0">
                  <li class="breadcrumb-item"><a href="javascript: void(0);">Liste des Utilisateurs</a></li>
                  <li class="breadcrumb-item active">Liste des Utilisateurs</li>
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
                            <div class="edit">
                              <button
                                class="btn btn-sm btn-ghost-info btn-icon edit-item-btn"
                                @click="openEditModal(user)"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                              >
                                <i class="ph-pencil-line"></i>
                              </button>
                            </div>
                            <div class="remove">
                              <button
                                class="btn btn-sm btn-ghost-danger btn-icon remove-item-btn"
                                @click="deleteUser(user.id)"
                              >
                                <i class="ph-trash"></i>
                              </button>
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
            <Create @refresh="refreshUsers"/>
          </div>
        </div>
  
        <!-- Modal de Modification -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <Edit :user="user" @refresh="refreshUsers" />
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
      };
    },
    mounted() {
      this.fetchUsers();
      this.fetchRoles();
    },
    computed: {
      filteredUsers() {
        let filtered = this.users;
  
        // Filtrer par la recherche
        if (this.searchQuery) {
          filtered = filtered.filter(user =>
            user.nom_user.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        }
  
        // Filtrer par rôle
        if (this.selectedRole !== 'all') {
          filtered = filtered.filter(user => user.role.lib_role === this.selectedRole);
        }
  
        return filtered;
      },
    },
    methods: {
      fetchUsers() {
        axios.get('/api/users/?page=' + this.currentPage)
          .then(response => {
            this.users = response.data.data;
            this.totalPages = response.data.last_page;
            this.total = response.data.total;
          })
          .catch(error => {
            console.error('Erreur de récupération des utilisateurs:', error);
            this.errors = error;
          });
      },
      // Récupérer les rôles depuis la base de données
      fetchRoles() {
            axios.get('/api/roles').then(response => {
                this.roles = response.data;
            }).catch(error => {
                console.error('Erreur lors de la récupération des rôles:', error);
            });
        },
        submitForm() {
            this.errors = ''; // Réinitialiser les messages d'erreur avant chaque soumission
                
            if (this.user.id) {
                this.updateUser();
            } else {
                this.createUser();
            }
        },
        createUser() {
            this.$axios.post('/api/users', this.user)
                .then(response => {
                    this.users.push(response.data);
                    this.closeModal();
                })
                .catch(error => {
                    console.error('Erreur lors de la création de l\'utilisateur:', error);
                    this.errors = error.response ? error.response.data.message : 'Une erreur est survenue lors de la création de l\'utilisateur.';
                });
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
        deleteUser(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
            axios.delete(`/api/users/${id}`)
                .then(response => {
                    this.fetchUsers();
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression de l\'utilisateur:', error);
                    this.errors = 'Erreur lors de la suppression de l\'utilisateur. Veuillez réessayer.';
                });
            }
        }
    },
};
</script>
  