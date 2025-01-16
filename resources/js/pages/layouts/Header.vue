<template>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">

                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <button type="button" class="btn btn-sm px-3 fs-15 user-name-text header-item d-none d-md-block" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <span class="bi bi-search me-2"></span> Rechercher ...
                    </button>
                </div>

                <div class="d-flex align-items-center">

                    <div class="dropdown ms-sm-3 header-item topbar-user topbar-head-dropdown dropdown-hover-end">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                        {{ getInitials(this.$store.state.user.nom_user, this.$store.state.user.prenom_user) }}
                                    </div>
                                </div>
                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ this.$store.state.user.prenom_user }} {{ this.$store.state.user.nom_user }}</span>
                                    <!-- <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">{{ this.$store.state.user.role.lib_role }}</span> -->
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <h6 class="dropdown-header">Bienvenue {{ this.$store.state.user.prenom_user }} !</h6>
                            <a class="dropdown-item" href="account.html"><i class="bi bi-person-circle text-muted fs-15 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" @click="logoutUser"><i class="bi bi-box-arrow-right text-muted fs-15 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState('user'), // Assurez-vous que userName est défini dans votre store
    },
    methods: {
        ...mapActions(['logout']),
        async logoutUser() {
            await this.logout(this.form);
        }, // Assurez-vous que logout est défini dans votre store
        getInitials(nom, prenom) {
            return `${nom?.charAt(0) || ''}${prenom?.charAt(0) || ''}`.toUpperCase();
        },
    }
};
</script>

<style scoped>
/* Ajoutez ici vos styles personnalisés si nécessaire */
</style>