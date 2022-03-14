<template>
    <div class="homepage-login">
        <!-- language -->
        <div class="row p-2">
            <div class="col-12 d-flex justify-content-end">
                <div>
                    <button
                        type="button"
                        class="btn btn-default btn-sm dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <i
                            class="flag-icon mr-2"
                            :class="pageLanguage === 'en' ? 'flag-icon-us' : 'flag-icon-' + pageLanguage">
                        </i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        <a href="#" class="dropdown-item"
                           :class="{'active' : pageLanguage === 'en'}"
                           @click="pageLanguage = 'en'">
                            <i class="flag-icon flag-icon-us mr-2"></i> English
                        </a>
                        <a href="#" class="dropdown-item"
                           :class="{'active' : pageLanguage === 'jp'}"
                           @click="pageLanguage = 'jp'">
                            <i class="flag-icon flag-icon-jp mr-2"></i> Japan
                        </a>
                        <a href="#" class="dropdown-item"
                           :class="{'active' : pageLanguage === 'th'}"
                           @click="pageLanguage = 'th'">
                            <i class="flag-icon flag-icon-th mr-2"></i> Thailand
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4 homepage-login__login custom-card">
            <h2 class="homepage-login__title">
                <img style="height: auto; width: 385px;" src="../../../../images/stalinks2.png" alt="User Image">
            </h2>
            <form class="homepage-login__login-form" @submit.prevent="submitLogin(credentials)">
                <div class="form-group">
                    <label for="user-email">{{ $t('message.login.l1') }}</label>
                    <input v-bind:class="{ 'is-invalid': error && objectNotEmpty(error.email)}" v-model="credentials.email" autofocus="autofocus" type="email" id="user-email" class="form-control" autocomplete="off">
                    <span v-if="error && objectNotEmpty(error.email)" class="text-danger">{{ error.email[0] }}</span>
                </div>
                <div class="form-group mb-5">
                    <label for="user-password">{{ $t('message.login.l2') }}</label>
                    <input v-bind:class="{ 'is-invalid': error && objectNotEmpty(error.password)}" v-model="credentials.password" type="password" id="user-password" class="form-control">
                    <router-link :to="{ path: '/forgot-password' }">
                        <span>{{ $t('message.login.forgot') }}</span>
                    </router-link>
                    <span v-if="error && objectNotEmpty(error.password)"  class="text-danger">{{ error.password[0] }}</span>
                    <span v-if="error.message"  class="text-danger">{{ error.message }}</span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-flat float-right"><span><b>{{ $t('message.login.b2') }}</b></span></button>
                </div>
                <div class="form-group">
                    <button type="button" @click="registrationPage" class="btn btn-default btn-flat">{{ $t('message.login.b1') }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
    .custom-card {
        border-radius: 8px;
    }
</style>

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

            pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
        };
    },

    watch : {
        pageLanguage(newvalue, oldValue) {
            this.$i18n.locale = newvalue;
        },
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
        registrationPage() {
            this.$router.push('registration');
        },
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
