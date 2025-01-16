<template>
	<div>
        <Loader :loading="loading" />
        <section class="auth-page-wrapper position-relative bg-light min-vh-100 d-flex align-items-center justify-content-between">
            <div class="w-100">
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card mx-lg-3">
                                <div class="card-header border-0 d-flex justify-content-center">
                                    <div class="d-block pt-3 h-auto">
                                        <!-- <img src="/public/assets/images/logo/logo.png" alt="" height="40" class="card-logo-dark mx-0"> -->
                                    </div>
                                </div>
                                <div class="card border-0 mb-0">
                                    <div class="card-header border-0">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h2 class="lh-base fw-bold py-0">Bienvenue</h2>
                                                <p class="text-muted fs-15">Saisissez votre adresse email et votre mot de passe pour vous connecter à votre espace</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="p-2">
                                            <form @submit.prevent="loginUser">
                
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" v-model="form.email" class="form-control" id="email" placeholder="Entrer votre email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Mot de passe</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" v-model="form.password" class="form-control pe-5 password-input" placeholder="Entrer votre mot de passe" id="password-input" required>
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <div class="float-end">
                                                        <router-link to="/reset/password" class="text-muted">Mot de passe oublié?</router-link>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button class="btn btn-primary w-100" type="submit">Connectez-vous</button>
                                                </div>
                                                <div v-if="errorMessage" class="text-danger mt-2">{{ errorMessage }}</div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </div>
        </section>
	</div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'Login',
    components: {
    //   Loader,
    },
	data () {
		return {
			form: {
                email: "",
                password: "",
            },
            errors: {},
            loading: false,
		}
    },
    methods: {
        ...mapActions(['login']),
        async loginUser() {
            this.loading = true;
            try {
                await this.login(this.form);
                setTimeout(() => {
                    this.$router.push({ name: 'Dashboard' });  // Rediriger vers le tableau de bord après la connexion
                }, 3500)
            } catch (error) {
               console.log(error);
               
            } finally {
                this.loading = false; // Arrêter l'indicateur de chargement une fois les données récupérées
            };
        },
    }
}
</script>