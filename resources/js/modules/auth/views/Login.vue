<template>
    <div class="homepage-login">
        <div class="col-md-4 col-md-offset-4 homepage-login__login">
            <h2 class="homepage-login__title">Rlink Management Tool</h2>
            <form class="homepage-login__login-form">
                <div class="form-group">
                    <label for="user-email">Email</label>
                    <input v-on:keyup.enter="submitLogin(credentials)" v-bind:class="{ 'is-invalid': error && objectNotEmpty(error.email)}" v-model="credentials.email" autofocus="autofocus" type="email" id="user-email" class="form-control">
                    <span v-if="error && objectNotEmpty(error.email)" class="text-danger">{{ error.email[0] }}</span>
                </div>
                <div class="form-group">
                    <label for="user-password">Password</label>
                    <input v-on:keyup.enter="submitLogin(credentials)" v-bind:class="{ 'is-invalid': error && objectNotEmpty(error.password)}" v-model="credentials.password" type="password" id="user-password" class="form-control">
                    <span v-if="error && objectNotEmpty(error.password)"  class="text-danger">{{ error.password[0] }}</span>
                    <span v-if="error.message"  class="text-danger">{{ error.message }}</span>
                </div>
                <div class="form-group homepage-login__button-login"><button type="button" @click="submitLogin(credentials)" class="btn btn-primary btn-flat pull-right"><span><b>Login</b></span></button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
    name: 'Login',
    data() {
        return {
            credentials: {
                'email': '',
                'password': '',
            },
        };
    },

    computed: {
        ...mapState({
            isLoading: state => state.storeAuth.loading,
            error: state => state.storeAuth.error,
            token: state => state.storeAuth.token,
        }),

        ...mapGetters({
            isLoggedIn: 'isLoggedIn',
        }),
    },

    beforeMount() {
        if (this.isLoggedIn) {
            return this.$router.push({
                path: '/',
            });
        }
    },

    methods: {
        async submitLogin() {
            await this.$store.dispatch('login', {
                vue: this,
                params: this.credentials,
            });
        },

        objectNotEmpty(object) {
            return !_.isEmpty(object);
        },
    },
}
</script>