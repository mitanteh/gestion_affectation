<template>
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="/" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="../assets/images/logo-sm.png" alt="" height="26">
                </span>
                <span class="logo-lg">
                    <img src="../assets/images/logo-dark.png" alt="" height="26">
                </span>
            </a>
            <a href="/" class="logo logo-light">
                <span class="logo-sm">
                    <img src="../assets/images/logo-sm.png" alt="" height="24">
                </span>
                <span class="logo-lg">
                    <img src="../assets/images/logo-light.png" alt="" height="24">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div id="scrollbar">
            <div class="container-fluid">
                <div id="two-column-menu"></div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="/" class="nav-link menu-link">
                            <i class="bi bi-house-door"></i> <span data-key="t-dashboard">Dashboard</span>
                        </a>
                    </li>

                    <!-- Gestion des projets -->
                    <li class="nav-item">
                        <router-link :to="{name: ProjectList}" class="nav-link menu-link">
                            <i class="bi bi-clipboard-check"></i> <span data-key="t-projets">Projets</span>
                        </router-link>
                        <!-- <a class="nav-link menu-link" href="#sidebarProjets" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProjets">
                            <i class="bi bi-clipboard-check"></i> <span data-key="t-projets">Projets</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarProjets">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <router-link :to="{name: ProjectList}" class="nav-link" data-key="t-liste-projets">Liste des projets</router-link>
                                </li>
                                <li class="nav-item">
                                    <a href="/projets/ajouter" class="nav-link" data-key="t-ajouter-projet">Ajouter un projet</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/projets/affectations" class="nav-link" data-key="t-affectations">Affectations</a>
                                </li>
                            </ul>
                        </div> -->
                    </li>

                    <!-- Gestion des ressources -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarRessources" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> <span data-key="t-ressources">Ressources</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarRessources">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <router-link :to="{ name: 'ResourceList', params: { role: 'Manager' } }" class="nav-link" data-key="t-liste-ressources">Managers</router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link :to="{ name: 'ResourceList', params: { role: 'Chef de projet' } }" class="nav-link" data-key="t-liste-ressources">Chefs de projets</router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link :to="{ name: 'ResourceList', params: { role: 'Analyste de test' } }" class="nav-link" data-key="t-liste-ressources">Analystes de tests</router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link :to="{ name: 'ResourceList', params: { role: 'Ingenieur de test' } }" class="nav-link" data-key="t-liste-ressources">Ingénieurs de tests</router-link>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des utilisateurs -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarUtilisateurs" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUtilisateurs">
                            <i class="bi bi-person-bounding-box"></i> <span data-key="t-utilisateurs">Utilisateurs</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarUtilisateurs">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <router-link :to="{ name: 'UserList' }" class="nav-link" data-key="t-liste-utilisateurs">Liste des utilisateurs</router-link>
                                </li>
                                <li class="nav-item">
                                    <a href="/utilisateurs/ajouter" class="nav-link" data-key="t-ajouter-utilisateur">Ajouter un utilisateur</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Paramètres -->
                    <li class="nav-item">
                        <router-link :to="{ name: 'Settings' }" class="nav-link menu-link">
                            <i class="bi bi-gear"></i> <span data-key="t-parametres">Paramètres</span>
                        </router-link>
                    </li>

                    <!-- Déconnexion -->
                    <li class="nav-item">
                        <a href="/logout" class="nav-link menu-link">
                            <i class="bi bi-box-arrow-right"></i> <span data-key="t-deconnexion">Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sidebar-background"></div>
    </div>
</template>

<script>
export default {
    name: 'Sidebar',
    methods: {
        selectRole(role) {
        // Émettre l'événement avec le rôle sélectionné
        this.$emit('role-selected', role);
        }
    }
};
</script>