<template>
    <div class="page-content">
        <div class="container-fluid">
            <!-- Entête avec informations principales -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ projet?.nom_projet }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><router-link to="/projets/list">Liste des Projets</router-link></li>
                                <li class="breadcrumb-item active">Détail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards d'informations -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-calendar-todo-line fs-24 align-middle text-primary me-1"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="text-muted mb-0">Date début</p>
                                            <h5 class="mb-0">{{ formatDate(projet?.date_deb) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex mt-4 mt-sm-0">
                                        <div class="flex-shrink-0">
                                            <i class="ri-calendar-check-line fs-24 align-middle text-primary me-1"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="text-muted mb-0">Date fin</p>
                                            <h5 class="mb-0">{{ formatDate(projet?.date_fin) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-team-line fs-24 align-middle text-primary me-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0">Total Membres</p>
                                    <h5 class="mb-0">{{ projetUsers.total || 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="row">
                <!-- Sidebar avec les détails -->
                <div class="col-xxl-4 order-2 order-xxl-1">
                    <!-- Card des détails -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Détails du projet</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Statut</h6>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span :class="getStatusBadgeClass(projet?.etat_projet)">
                                            {{ projet?.etat_projet }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Avancement</h6>
                                <div class="progress animated-progress custom-progress mb-1">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                        :style="{ width: `${projet?.taux_avancement || 0}%` }" 
                                        :aria-valuenow="projet?.taux_avancement || 0" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="text-muted mb-0">{{ projet?.taux_avancement || 0 }}% complété</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Description</h6>
                                <p class="text-muted">{{ projet?.description_projet || 'Aucune description' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card des compétences -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Compétences requises</h5>
                            <div class="flex-shrink-0">
                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addCompetenceModal"
                                >
                                    <i class="ri-add-line align-middle me-1"></i> Ajouter
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <span 
                                    v-for="comp in projetCompetences" 
                                    :key="comp.id"
                                    class="badge bg-primary-subtle text-primary fs-12 d-inline-flex align-items-center"
                                    v-if="projetCompetences.length"
                                >
                                    {{ comp.lib_comp }}
                                    <i 
                                        class="ri-close-line ms-1 cursor-pointer"
                                        @click="confirmRemoveCompetence(comp)"
                                    ></i>
                                </span>
                                <span v-if="!projetCompetences.length" class="text-muted">
                                    Aucune compétence requise
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 order-1 order-xxl-2">
                    <!-- Liste des membres -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Membres du projet</h5>
                            <div class="flex-shrink-0">
                                <button 
                                    type="button" 
                                    class="btn btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addMemberModal"
                                >
                                    <i class="ri-add-line align-middle me-1"></i> Ajouter un membre
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-nowrap table-centered align-middle">
                                    <thead class="bg-light text-muted">
                                        <tr>
                                            <th scope="col">Membre</th>
                                            <th scope="col">Rôle</th>
                                            <!-- <th scope="col">Date d'ajout</th> -->
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in projetUsers.data" :key="user.id">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            {{ getInitials(user.nom_user, user.prenom_user) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-13 mb-0">{{ user.nom_user }}</h5>
                                                        <p class="fs-12 mb-0 text-muted">{{ user.prenom_user }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span :class="getRoleBadgeClass(user.role?.lib_role)">
                                                    {{ user.role?.lib_role }}
                                                </span>
                                            </td>
                                            <!-- <td>{{ formatDate(user.pivot?.created_at) }}</td> -->
                                            <td>
                                                <button 
                                                    class="btn btn-sm btn-soft-danger"
                                                    @click="confirmRemoveUser(user)"
                                                >
                                                    <i class="ri-delete-bin-line align-bottom"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!projetUsers.data?.length">
                                            <td colspan="4" class="text-center py-4">
                                                <div class="avatar-md mx-auto mb-4">
                                                    <div class="avatar-title rounded-circle bg-light text-primary">
                                                        <i class="ri-user-unfollow-line fs-24"></i>
                                                    </div>
                                                </div>
                                                <h5 class="text-muted">Aucun membre dans ce projet</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div v-if="projetUsers.data?.length" class="row align-items-center mt-4 g-3 text-center text-sm-start">
                                <div class="col-sm">
                                    <div class="text-muted">Affichage de <span class="fw-semibold">{{ startIndex }}</span> à <span class="fw-semibold">{{ endIndex }}</span> sur <span class="fw-semibold">{{ projetUsers.total }}</span> résultats</div>
                                </div>
                                <div class="col-sm-auto">
                                    <ul class="pagination pagination-separated pagination-md justify-content-center justify-content-sm-start mb-0">
                                        <li :class="['page-item', { disabled: projetUsers.current_page === 1 }]">
                                            <a 
                                                class="page-link" 
                                                href="#"
                                                @click.prevent="changeProjetUsersPage(projetUsers.current_page - 1)"
                                            >
                                                Précédent
                                            </a>
                                        </li>
                                        <li 
                                            v-for="page in getPageRange(projetUsers.current_page, projetUsers.last_page)" 
                                            :key="page"
                                            :class="['page-item', { active: page === projetUsers.current_page }]"
                                        >
                                            <a 
                                                class="page-link" 
                                                href="#"
                                                @click.prevent="changeProjetUsersPage(page)"
                                            >
                                                {{ page }}
                                            </a>
                                        </li>
                                        <li :class="['page-item', { disabled: projetUsers.current_page === projetUsers.last_page }]">
                                            <a 
                                                class="page-link" 
                                                href="#"
                                                @click.prevent="changeProjetUsersPage(projetUsers.current_page + 1)"
                                            >
                                                Suivant
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- liste des tâches -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Tâches du projet</h5>
                            <div class="flex-shrink-0">
                                <button 
                                    type="button" 
                                    class="btn btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addTaskModal"
                                >
                                    <i class="ri-add-line align-middle me-1"></i> Nouvelle tâche
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Colonne "À faire" -->
                                <div class="col-xxl-3">
                                    <div class="tasks-column-header">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-checkbox-blank-circle-line me-2"></i>
                                            <h6 class="mb-0">À faire</h6>
                                            <span class="badge bg-secondary-subtle text-secondary ms-2">
                                                {{ getTasksByStatus('A faire').length }}
                                            </span>
                                        </div>
                                    </div>
                                    <draggable 
                                        class="tasks-list"
                                        :list="getTasksByStatus('A faire')"
                                        group="tasks"
                                        @change="handleDragChange($event, 'A faire')"
                                        item-key="id"
                                        :animation="200"
                                        ghost-class="ghost-card"
                                        drag-class="drag-card"
                                    >
                                        <template #item="{ element: task }">
                                            <div class="card border-secondary border-opacity-50 mb-2" @click="showTaskDetails(task)">
                                                <div class="card-body">
                                                    <span class="float-end badge" :class="getPriorityBadgeClass(task.priorite)">
                                                        {{ task.priorite }}
                                                    </span>
                                                    <h5 class="mb-3">{{ task.titre }}</h5>
                                                    <div class="d-flex align-items-end">
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-medium mb-1">{{ task.user?.nom_user }} {{ task.user?.prenom_user }}</h6>
                                                            <p class="text-muted mb-0">{{ formatDate(task.date_limite) }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown" @click.stop>
                                                                <button class="btn btn-ghost-secondary btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" @click="editTask(task)">
                                                                        <i class="ri-pencil-line me-2"></i>Modifier
                                                                    </a></li>
                                                                    <li><a class="dropdown-item" @click="confirmDeleteTask(task)">
                                                                        <i class="ri-delete-bin-line me-2"></i>Supprimer
                                                                    </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>

                                <!-- Colonne "En cours" -->
                                <div class="col-xxl-3">
                                    <div class="tasks-column-header">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-loader-4-line me-2"></i>
                                            <h6 class="mb-0">En cours</h6>
                                            <span class="badge bg-warning-subtle text-warning ms-2">
                                                {{ getTasksByStatus('En cours').length }}
                                            </span>
                                        </div>
                                    </div>
                                    <draggable 
                                        class="tasks-list"
                                        :list="getTasksByStatus('En cours')"
                                        group="tasks"
                                        @change="handleDragChange($event, 'En cours')"
                                        item-key="id"
                                        :animation="200"
                                        ghost-class="ghost-card"
                                        drag-class="drag-card"
                                    >
                                        <template #item="{ element: task }">
                                            <div class="card border-warning border-opacity-50 mb-2" @click="showTaskDetails(task)">
                                                <div class="card-body">
                                                    <span class="float-end badge" :class="getPriorityBadgeClass(task.priorite)">
                                                        {{ task.priorite }}
                                                    </span>
                                                    <h5 class="mb-3">{{ task.titre }}</h5>
                                                    <div class="d-flex align-items-end">
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-medium mb-1">{{ task.user?.nom_user }} {{ task.user?.prenom_user }}</h6>
                                                            <p class="text-muted mb-0">{{ formatDate(task.date_limite) }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown" @click.stop>
                                                                <button class="btn btn-ghost-secondary btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" @click="editTask(task)">
                                                                        <i class="ri-pencil-line me-2"></i>Modifier
                                                                    </a></li>
                                                                    <li><a class="dropdown-item" @click="confirmDeleteTask(task)">
                                                                        <i class="ri-delete-bin-line me-2"></i>Supprimer
                                                                    </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>

                                <!-- Colonne "En pause" -->
                                <div class="col-xxl-3">
                                    <div class="tasks-column-header">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-pause-circle-line me-2"></i>
                                            <h6 class="mb-0">En pause</h6>
                                            <span class="badge bg-info-subtle text-info ms-2">
                                                {{ getTasksByStatus('En pause').length }}
                                            </span>
                                        </div>
                                    </div>
                                    <draggable 
                                        class="tasks-list"
                                        :list="getTasksByStatus('En pause')"
                                        group="tasks"
                                        @change="handleDragChange($event, 'En pause')"
                                        item-key="id"
                                        :animation="200"
                                        ghost-class="ghost-card"
                                        drag-class="drag-card"
                                    >
                                        <template #item="{ element: task }">
                                            <div class="card border-info border-opacity-50 mb-2" @click="showTaskDetails(task)">
                                                <div class="card-body">
                                                    <span class="float-end badge" :class="getPriorityBadgeClass(task.priorite)">
                                                        {{ task.priorite }}
                                                    </span>
                                                    <h5 class="mb-3">{{ task.titre }}</h5>
                                                    <div class="d-flex align-items-end">
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-medium mb-1">{{ task.user?.nom_user }} {{ task.user?.prenom_user }}</h6>
                                                            <p class="text-muted mb-0">{{ formatDate(task.date_limite) }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown" @click.stop>
                                                                <button class="btn btn-ghost-secondary btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" @click="editTask(task)">
                                                                        <i class="ri-pencil-line me-2"></i>Modifier
                                                                    </a></li>
                                                                    <li><a class="dropdown-item" @click="confirmDeleteTask(task)">
                                                                        <i class="ri-delete-bin-line me-2"></i>Supprimer
                                                                    </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>

                                <!-- Colonne "Terminé" -->
                                <div class="col-xxl-3">
                                    <div class="tasks-column-header">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-checkbox-circle-line me-2"></i>
                                            <h6 class="mb-0">Terminé</h6>
                                            <span class="badge bg-success-subtle text-success ms-2">
                                                {{ getTasksByStatus('Terminé').length }}
                                            </span>
                                        </div>
                                    </div>
                                    <draggable 
                                        class="tasks-list"
                                        :list="getTasksByStatus('Terminé')"
                                        group="tasks"
                                        @change="handleDragChange($event, 'Terminé')"
                                        item-key="id"
                                        :animation="200"
                                        ghost-class="ghost-card"
                                        drag-class="drag-card"
                                        :options="{ disabled: true }"
                                    >
                                        <template #item="{ element: task }">
                                            <div class="card border-success border-opacity-50 mb-2" @click="showTaskDetails(task)">
                                                <div class="card-body">
                                                    <span class="float-end badge" :class="getPriorityBadgeClass(task.priorite)">
                                                        {{ task.priorite }}
                                                    </span>
                                                    <h5 class="mb-3">{{ task.titre }}</h5>
                                                    <div class="d-flex align-items-end">
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-medium mb-1">{{ task.user?.nom_user }} {{ task.user?.prenom_user }}</h6>
                                                            <p class="text-muted mb-0">{{ formatDate(task.date_limite) }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown" @click.stop v-if="task.statut !== 'Terminé'">
                                                                <button class="btn btn-ghost-secondary btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                                                    <i class="ri-more-2-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item" @click="editTask(task)">
                                                                        <i class="ri-pencil-line me-2"></i>Modifier
                                                                    </a></li>
                                                                    <li><a class="dropdown-item" @click="confirmDeleteTask(task)">
                                                                        <i class="ri-delete-bin-line me-2"></i>Supprimer
                                                                    </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'ajout de membre -->
        <div class="modal fade" id="addMemberModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un membre</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addUser">
                            <div class="mb-3">
                                <label class="form-label">Sélectionner un membre</label>
                                <select v-model="selectedUser" class="form-select">
                                    <option value="" disabled selected>Choisir un membre...</option>
                                    <option 
                                        v-for="user in eligibleUsers" 
                                        :key="user.id" 
                                        :value="user.id"
                                    >
                                        {{ user.nom_user }} {{ user.prenom_user }} - {{ user.role?.lib_role }}
                                    </option>
                                </select>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Annuler</button>
                                <button 
                                    type="submit" 
                                    class="btn btn-primary" 
                                >
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'ajout de compétence -->
        <div id="addCompetenceModal" class="modal fade" tabindex="-1" aria-labelledby="addCompetenceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-4 pb-0">
                        <h5 class="modal-title" id="addCompetenceModalLabel">Ajouter une compétence</h5>
                        <button type="button" class="btn-close" id="close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form @submit.prevent="addCompetence" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="competence-select" class="form-label">Sélectionner une compétence</label>
                                <select v-model="selectedCompetence" class="form-select" id="competence-select" required>
                                    <option value="">Choisir...</option>
                                    <option 
                                        v-for="comp in availableCompetences" 
                                        :key="comp.id" 
                                        :value="comp.id"
                                    >
                                        {{ comp.lib_comp }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner une compétence
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'ajout/édition de tâche -->
        <div class="modal fade" id="addTaskModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ editingTask ? 'Modifier la tâche' : 'Nouvelle tâche' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveTask">
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    v-model="taskForm.titre" 
                                    required
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea 
                                    class="form-control" 
                                    v-model="taskForm.description" 
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Assigné à</label>
                                <select v-model="taskForm.user_id" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option 
                                        v-for="user in projetUsers.data" 
                                        :key="user.id" 
                                        :value="user.id"
                                    >
                                        {{ user.nom_user }} {{ user.prenom_user }}
                                    </option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date limite</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            v-model="taskForm.date_limite"
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Priorité</label>
                                        <select v-model="taskForm.priorite" class="form-select" required>
                                            <option value="Basse">Basse</option>
                                            <option value="Moyenne">Moyenne</option>
                                            <option value="Haute">Haute</option>
                                            <option value="Urgente">Urgente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-soft-danger" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">
                                    {{ editingTask ? 'Modifier' : 'Créer' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de détail des tâches -->
        <div class="modal fade" id="taskDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la tâche</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" v-if="selectedTask">
                        <div class="mb-4">
                            <h6 class="task-title fs-15 mb-3">{{ selectedTask.titre }}</h6>
                            <p class="text-muted">{{ selectedTask.description }}</p>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                {{ getInitials(selectedTask.user?.nom_user, selectedTask.user?.prenom_user) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1">Assigné à</h6>
                                        <p class="text-muted mb-0">{{ selectedTask.user?.nom_user }} {{ selectedTask.user?.prenom_user }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1">Date limite</h6>
                                <p class="text-muted mb-0">{{ formatDate(selectedTask.date_limite) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h6 class="mb-1">Priorité</h6>
                                <span :class="getPriorityBadgeClass(selectedTask.priorite)">
                                    {{ selectedTask.priorite }}
                                </span>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1">Statut</h6>
                                <span :class="getStatusBadgeClass(selectedTask.statut)">
                                    {{ selectedTask.statut }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="editTask(selectedTask)">
                            <i class="ri-pencil-line align-bottom me-1"></i> Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';

export default {
    components: {
        draggable
    },
    data() {
        return {
            projetId: this.$route.params.id,
            projet: null,
            selectedUser: '',
            projetUsers: {
                data: [],
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0
            },
            eligibleUsers: [],
            modal: null,
            competences: [],
            projetCompetences: [],
            selectedCompetence: '',
            tasks: [],
            editingTask: null,
            taskForm: {
                titre: '',
                description: '',
                user_id: '',
                date_limite: '',
                priorite: 'Moyenne',
                statut: 'A faire'
            },
            currentFilter: 'Toutes',
            searchQuery: '',
            filterOptions: [
                { label: 'Toutes', value: 'Toutes' },
                { label: 'À faire', value: 'A faire' },
                { label: 'En cours', value: 'En cours' },
                { label: 'En pause', value: 'En pause' },
                { label: 'Terminé', value: 'Terminé' }
            ],
            selectedTask: null,
            columns: [
                {
                    status: 'A faire',
                    title: 'À faire',
                    icon: 'ri-checkbox-blank-circle-line',
                    class: 'todo-column',
                    badgeClass: 'bg-secondary'
                },
                {
                    status: 'En cours',
                    title: 'En cours',
                    icon: 'ri-loader-4-line',
                    class: 'in-progress-column',
                    badgeClass: 'bg-warning'
                },
                {
                    status: 'En pause',
                    title: 'En pause',
                    icon: 'ri-pause-circle-line',
                    class: 'paused-column',
                    badgeClass: 'bg-info'
                },
                {
                    status: 'Terminé',
                    title: 'Terminé',
                    icon: 'ri-checkbox-circle-line',
                    class: 'done-column',
                    badgeClass: 'bg-success'
                }
            ]
        }
    },

    computed: {
        startIndex() {
            return (this.projetUsers.current_page - 1) * this.projetUsers.per_page + 1;
        },
        endIndex() {
            return Math.min(
                this.projetUsers.current_page * this.projetUsers.per_page,
                this.projetUsers.total
            );
        },
        availableCompetences() {
            return this.competences.filter(comp => 
                !this.projetCompetences?.find(pc => pc.id === comp.id)
            );
        },
        filteredTasks() {
            if (this.currentFilter === 'Toutes') return this.tasks;
            return this.tasks.filter(task => task.statut === this.currentFilter);
        },
        filteredAndSearchedTasks() {
            let tasks = this.filteredTasks;
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                tasks = tasks.filter(task => 
                    task.titre.toLowerCase().includes(query) ||
                    task.description.toLowerCase().includes(query)
                );
            }
            return tasks;
        }
    },

    methods: {
        getInitials(nom, prenom) {
            return `${nom?.charAt(0) || ''}${prenom?.charAt(0) || ''}`.toUpperCase();
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

        getStatusBadgeClass(statut) {
            const classes = {
                'En cours': 'badge bg-warning',
                'Terminé': 'badge bg-success',
                'En attente': 'badge bg-info',
                'Annulé': 'badge bg-danger'
            };
            return classes[statut] || 'badge bg-secondary';
        },

        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        },

        getPageRange(current, last) {
            const range = [];
            const maxVisible = 5;
            const start = Math.max(1, current - Math.floor(maxVisible / 2));
            const end = Math.min(last, start + maxVisible - 1);

            for (let i = start; i <= end; i++) {
                range.push(i);
            }
            return range;
        },

        async fetchProjet() {
            try {
                const response = await axios.get(`/api/projets/${this.projetId}`);
                this.projet = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération du projet:", error);
                toast.error("Erreur lors de la récupération du projet", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },

        async fetchEligibleUsers() {
            try {
                const response = await axios.get(`/api/projets/${this.projetId}/eligible-users`);
                this.eligibleUsers = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération des utilisateurs éligibles:", error);
                toast.error("Erreur lors de la récupération des utilisateurs éligibles", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },

        async fetchProjetUsers(page = 1) {
            try {
                const response = await axios.get(`/api/projets/${this.projetId}/users?page=${page}`);
                this.projetUsers = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération des membres du projet:", error);
                toast.error("Erreur lors de la récupération des membres", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
            }
        },

        async changeProjetUsersPage(page) {
            if (page >= 1 && page <= this.projetUsers.last_page) {
                await this.fetchProjetUsers(page);
            }
        },

        async addUser() {
            if (!this.selectedUser) return;

            try {
                await axios.post(`/api/projets/${this.projetId}/users/${this.selectedUser}`);
                
                toast.success("Membre ajouté avec succès", {
                    theme: "colored",
                    type: "success",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });

                // Actualiser toutes les données
                await this.fetchProjetUsers();

                // Fermer le modal
                const modal = document.getElementById('addMemberModal');
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
                
                // Réinitialiser la sélection
                this.selectedUser = '';
            } catch (error) {
                console.error("Erreur lors de l'ajout du membre:", error);
                toast.error(error.response?.data?.message || "Erreur lors de l'ajout du membre", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });
            }
        },

        confirmRemoveUser(user) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment retirer ${user.nom_user} ${user.prenom_user} du projet ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeUser(user.id);
                }
            });
        },

        async removeUser(userId) {
            try {
                await axios.delete(`/api/projets/${this.projetId}/users/${userId}`);
                
                // Actualiser toutes les données
                await this.fetchProjetUsers();

                toast.success("Membre retiré avec succès", {
                    theme: "colored",
                    type: "success",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });

            } catch (error) {
                console.error("Erreur lors du retrait du membre:", error);
                toast.error("Erreur lors du retrait du membre", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });
            }
        },

        async fetchCompetences() {
            try {
                const response = await axios.get('/api/competences');
                this.competences = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération des compétences:", error);
                toast.error("Erreur lors de la récupération des compétences", {
                    position: toast.POSITION.BOTTOM_RIGHT,
                    theme: "colored"
                });
            }
        },

        async fetchProjetCompetences() {
            try {
                const response = await axios.get(`/api/projets/${this.$route.params.id}/competences`);
                this.projetCompetences = response.data;
                console.log("compétences du projet:", this.projetCompetences);
                
            } catch (error) {
                console.error("Erreur lors de la récupération des compétences du projet:", error);
                toast.error("Erreur lors de la récupération des compétences du projet");
            }
        },

        async addCompetence() {
            if (!this.selectedCompetence) return;

            try {
                await axios.post(`/api/projets/${this.projetId}/competences/${this.selectedCompetence}`);
                
                // Actualiser toutes les données
                await this.fetchProjetCompetences();

                toast.success("Compétence ajoutée avec succès !", {
                    theme: "colored",
                    type: "success",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });

                // Fermer le modal
                const modal = document.getElementById('addCompetenceModal');
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }

                // Réinitialiser la sélection
                this.selectedCompetence = "";
            } catch (error) {
                console.error("Erreur lors de l'ajout de la compétence:", error);
                toast.error("Erreur lors de l'ajout de la compétence", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });
            }
        },

        confirmRemoveCompetence(competence) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment retirer la compétence "${competence.lib_comp}" du projet ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeCompetence(competence.id);
                }
            });
        },

        async removeCompetence(competenceId) {
            try {
                await axios.delete(`/api/projets/${this.projetId}/competences/${competenceId}`);
                
                // Actualiser toutes les données
                await this.fetchProjetCompetences();

                toast.success("Compétence retirée avec succès !", {
                    theme: "colored",
                    type: "success",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });
            } catch (error) {
                console.error("Erreur lors du retrait de la compétence:", error);
                toast.error("Erreur lors du retrait de la compétence", {
                    theme: "colored",
                    type: "error",
                    position: "bottom-right",
                    dangerouslyHTMLString: true
                });
            }
        },

        async loadAllData() {
            try {
                await Promise.all([
                    this.fetchProjet(),
                    this.fetchProjetUsers(),
                    this.fetchEligibleUsers(),
                    this.fetchCompetences(),
                    this.fetchProjetCompetences()
                ]);
            } catch (error) {
                console.error("Erreur lors du chargement des données:", error);
                toast.error("Erreur lors du chargement des données", {
                    type: "error",
                    position: 'bottom-right',
                    theme: "colored",
                    dangerouslyHTMLString: true
                });
            }
        },

        async fetchTasks() {
            try {
                const response = await axios.get(`/api/projets/${this.projetId}/tasks`);
                this.tasks = response.data;
            } catch (error) {
                console.error("Erreur lors de la récupération des tâches:", error);
                toast.error("Erreur lors de la récupération des tâches");
            }
        },

        editTask(task) {
            if (task.statut === 'Terminé') {
                toast.info("Les tâches terminées ne peuvent pas être modifiées", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                return;
            }
            this.editingTask = task;
            this.taskForm = { ...task };
            const modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
            modal.show();
        },

        async saveTask() {
            try {
                if (this.editingTask) {
                    await axios.put(`/api/tasks/${this.editingTask.id}`, this.taskForm);
                    toast.success("Tâche modifiée avec succès");
                } else {
                    await axios.post(`/api/projets/${this.projetId}/tasks`, this.taskForm);
                    toast.success("Tâche créée avec succès");
                }

                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                if (modal) {
                    modal.hide();
                }

                // Réinitialiser le formulaire
                this.resetTaskForm();

                // Actualiser les tâches
                await this.fetchTasks();
            } catch (error) {
                console.error("Erreur lors de la sauvegarde de la tâche:", error);
                toast.error("Erreur lors de la sauvegarde de la tâche");
            }
        },

        async updateTaskStatus(task, newStatus = null) {
            try {
                const statusToUpdate = newStatus || task.statut;
                await axios.patch(`/api/tasks/${task.id}/status`, {
                    statut: statusToUpdate
                });
                
                await this.fetchTasks(); // Recharger les tâches
                toast.success("Statut mis à jour avec succès");
            } catch (error) {
                console.error("Erreur lors de la mise à jour du statut:", error);
                toast.error("Erreur lors de la mise à jour du statut");
                // Annuler le changement en cas d'erreur
                if (!newStatus) {
                    task.statut = task.statut === 'Terminé' ? 'A faire' : 'Terminé';
                }
            }
        },

        confirmDeleteTask(task) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment supprimer la tâche "${task.titre}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteTask(task.id);
                }
            });
        },

        async deleteTask(taskId) {
            try {
                await axios.delete(`/api/tasks/${taskId}`);
                toast.success("Tâche supprimée avec succès");
                await this.fetchTasks();
            } catch (error) {
                console.error("Erreur lors de la suppression de la tâche:", error);
                toast.error("Erreur lors de la suppression de la tâche");
            }
        },

        resetTaskForm() {
            this.taskForm = {
                titre: '',
                description: '',
                user_id: '',
                date_limite: '',
                priorite: 'Moyenne',
                statut: 'A faire'
            };
            this.editingTask = null;
        },

        getPriorityBadgeClass(priorite) {
            const classes = {
                'Basse': 'badge bg-success-subtle text-success',
                'Moyenne': 'badge bg-warning-subtle text-warning',
                'Haute': 'badge bg-danger-subtle text-danger',
                'Urgente': 'badge bg-dark-subtle text-dark'
            };
            return classes[priorite] || 'badge bg-secondary';
        },

        async toggleTaskStatus(task) {
            const newStatus = task.statut === 'Terminé' ? 'A faire' : 'Terminé';
            task.statut = newStatus;
            await this.updateTaskStatus(task);
        },

        getTaskCountByStatus(status) {
            if (status === 'Toutes') return this.tasks.length;
            return this.tasks.filter(task => task.statut === status).length;
        },

        getBorderColor(priorite) {
            const colors = {
                'Basse': 'var(--vz-success)',
                'Moyenne': 'var(--vz-warning)',
                'Haute': 'var(--vz-danger)',
                'Urgente': 'var(--vz-dark)'
            };
            return { borderColor: colors[priorite] || 'var(--vz-secondary)' };
        },

        getStatusButtonClass(status) {
            const classes = {
                'A faire': 'btn-secondary',
                'En cours': 'btn-warning',
                'En pause': 'btn-info',
                'Terminé': 'btn-success'
            };
            return classes[status] || 'btn-secondary';
        },

        getStatusIcon(status) {
            const icons = {
                'A faire': 'ri-time-line',
                'En cours': 'ri-time-line',
                'En pause': 'ri-pause-line',
                'Terminé': 'ri-check-line'
            };
            return icons[status] || 'ri-time-line';
        },

        handleDragChange(event, column) {
            // Handle drag and drop events
        },

        getTasksByStatus(status) {
            return this.tasks.filter(task => task.statut === status);
        },

        async handleDragChange(evt, newStatus) {
            if (evt.added) {
                const task = evt.added.element;
                
                // Empêcher le déplacement si la tâche est terminée
                if (task.statut === 'Terminé' && newStatus !== 'Terminé') {
                    toast.info("Les tâches terminées ne peuvent pas être modifiées", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                    this.fetchTasks(); // Recharger les tâches pour annuler le déplacement
                    return;
                }

                try {
                    await axios.patch(`/api/tasks/${task.id}/status`, {
                        statut: newStatus
                    });
                    
                    // Mettre à jour le statut localement
                    const taskIndex = this.tasks.findIndex(t => t.id === task.id);
                    if (taskIndex !== -1) {
                        this.tasks[taskIndex].statut = newStatus;
                    }
                } catch (error) {
                    console.error('Erreur lors de la mise à jour du statut:', error);
                    // Recharger les tâches en cas d'erreur
                    this.fetchTasks();
                }
            }
        },

        getTaskCardClass(task) {
            const priorityClasses = {
                'Basse': 'task-priority-low',
                'Moyenne': 'task-priority-medium',
                'Haute': 'task-priority-high',
                'Urgente': 'task-priority-urgent'
            };
            return [
                priorityClasses[task.priorite],
                { 'task-completed': task.statut === 'Terminé' }
            ];
        },

        showTaskDetails(task) {
            this.selectedTask = task;
            const modal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
            modal.show();
        },

        getPriorityIcon(priorite) {
            const icons = {
                'Basse': 'ri-arrow-down-line',
                'Moyenne': 'ri-arrow-right-line',
                'Haute': 'ri-arrow-up-line',
                'Urgente': 'ri-alarm-warning-line'
            };
            return icons[priorite] || 'ri-question-line';
        }
    },

    mounted() {
        this.fetchProjet();
        this.fetchProjetUsers();
        this.fetchEligibleUsers();
        this.fetchCompetences();        // Ajout de ces deux appels
        this.fetchProjetCompetences();  // dans le mounted
        this.fetchTasks();
    }
}
</script>

<style scoped>
.tasks-wrapper {
    overflow-x: auto;
    padding: 1rem;
}

.tasks-board {
    display: grid;
    grid-template-columns: repeat(4, minmax(280px, 1fr));
    gap: 2rem;
    min-height: 400px;
}

.tasks-column {
    background-color: var(--vz-light);
    border-radius: 0.5rem;
    padding: 1.25rem;
    max-height: calc(100vh - 350px);
    overflow-y: auto;
    scrollbar-width: thin;
}

.tasks-column::-webkit-scrollbar {
    width: 4px;
}

.tasks-column::-webkit-scrollbar-track {
    background: transparent;
}

.tasks-column::-webkit-scrollbar-thumb {
    background: rgba(var(--vz-primary-rgb), 0.2);
    border-radius: 4px;
}

.tasks-column::-webkit-scrollbar-thumb:hover {
    background: rgba(var(--vz-primary-rgb), 0.3);
}

.column-header {
    color: var(--vz-body-color);
    font-size: 0.9375rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid;
}

.todo-column .column-header {
    border-color: var(--vz-secondary);
}

.in-progress-column .column-header {
    border-color: var(--vz-warning);
}

.paused-column .column-header {
    border-color: var(--vz-info);
}

.done-column .column-header {
    border-color: var(--vz-success);
}

.task-card {
    background: var(--vz-card-bg);
    border: 1px solid var(--vz-border-color);
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.task-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.task-id {
    font-size: 0.75rem;
    color: var(--vz-text-muted);
    margin-bottom: 0.25rem;
}

.task-title {
    font-size: 0.9375rem;
    font-weight: 500;
    color: var(--vz-body-color);
    margin-bottom: 0.75rem;
}

.task-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
    color: var(--vz-text-muted);
}

.task-meta-left {
    display: flex;
    gap: 1rem;
}

.task-assignee, .task-date {
    display: flex;
    align-items: center;
}

/* Styles pour les statuts */
.task-status-todo {
    border-left: 3px solid var(--vz-secondary);
}

.task-status-progress {
    border-left: 3px solid var(--vz-warning);
}

.task-status-paused {
    border-left: 3px solid var(--vz-info);
}

.task-status-done {
    border-left: 3px solid var(--vz-success);
}

/* Style pour le drag & drop */
.sortable-ghost {
    opacity: 0.6;
    background: var(--vz-light) !important;
    border: 2px dashed var(--vz-primary) !important;
}

.sortable-drag {
    transform: rotate(2deg) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.task-item {
    background: var(--vz-card-bg);
    border: 1px solid var(--vz-border-color);
    border-radius: 0.5rem;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

/* Styles pour les bordures selon la colonne */
.tasks-column:nth-child(1) .task-item {
    border: 1px solid var(--vz-secondary);
    border-left: 4px solid var(--vz-secondary);
}

.tasks-column:nth-child(2) .task-item {
    border: 1px solid var(--vz-warning);
    border-left: 4px solid var(--vz-warning);
}

.tasks-column:nth-child(3) .task-item {
    border: 1px solid var(--vz-info);
    border-left: 4px solid var(--vz-info);
}

.tasks-column:nth-child(4) .task-item {
    border: 1px solid var(--vz-success);
    border-left: 4px solid var(--vz-success);
}

.task-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.task-item-content {
    position: relative;
}

/* Améliorer le style des badges et du texte */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 500;
}

.avatar-xs {
    width: 24px;
    height: 24px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.75rem;
}

.task-card {
    border: 1px solid var(--vz-border-color);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

/* Styles pour les bordures selon la colonne */
.tasks-column:nth-child(1) .task-card {
    border-left: 3px solid var(--vz-secondary);
}

.tasks-column:nth-child(2) .task-card {
    border-left: 3px solid var(--vz-warning);
}

.tasks-column:nth-child(3) .task-card {
    border-left: 3px solid var(--vz-info);
}

.tasks-column:nth-child(4) .task-card {
    border-left: 3px solid var(--vz-success);
}

.task-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.fs-14 {
    font-size: 14px !important;
}

.fs-13 {
    font-size: 13px !important;
}

.avatar-xs {
    width: 24px;
    height: 24px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.75rem;
}

.btn-ghost-secondary {
    color: var(--vz-secondary);
    background-color: transparent;
}

.btn-ghost-secondary:hover {
    color: var(--vz-secondary);
    background-color: var(--vz-secondary-subtle);
}

/* Style pour le drag & drop */
.sortable-ghost {
    opacity: 0.6;
    background: var(--vz-light) !important;
    border: 2px dashed var(--vz-primary) !important;
}

.sortable-drag {
    transform: rotate(2deg) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.ghost-card {
    opacity: 0.5;
    background: var(--vz-light) !important;
    border: 2px dashed var(--vz-primary) !important;
    box-shadow: none !important;
}

.drag-card {
    transform: rotate(2deg) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.tasks-list {
    min-height: 50px;
    padding: 0.5rem 0;
}

/* Améliorer la zone de drop */
.tasks-column {
    background-color: var(--vz-light);
    border-radius: 0.5rem;
    padding: 1.25rem;
    max-height: calc(100vh - 350px);
    overflow-y: auto;
    transition: background-color 0.2s ease;
}

.tasks-column.drag-over {
    background-color: var(--vz-light-rgb);
}
</style>