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
                            class="flag-icon"
                            :class="pageLanguage === 'en'
                                ? 'flag-icon-us'
                                    : pageLanguage === 'ind'
                                    ? 'flag-icon-in'
                                        : 'flag-icon-' + pageLanguage">
                        </i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'en'}"

                            @click="pageLanguage = 'en'">

                            <i class="flag-icon flag-icon-us mr-2"></i>
                            English
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'jp'}"

                            @click="pageLanguage = 'jp'">
                            <i class="flag-icon flag-icon-jp mr-2"></i>
                            Japanese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'th'}"

                            @click="pageLanguage = 'th'">

                            <i class="flag-icon flag-icon-th mr-2"></i>
                            Thai
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'vn'}"

                            @click="pageLanguage = 'vn'">

                            <i class="flag-icon flag-icon-vn mr-2"></i>
                            Vietnamese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'ind'}"

                            @click="pageLanguage = 'ind'">

                            <i class="flag-icon flag-icon-in mr-2"></i>
                            Hindi
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'id'}"

                            @click="pageLanguage = 'id'">

                            <i class="flag-icon flag-icon-id mr-2"></i>
                            Indonesian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mx-5 my-5">
            <div v-if="!isCodeError" class="col-12 col-md-12 col-lg-8 col-xl-6">
                <!-- CONFIRMATION -->
                <div v-if="mode === 'sub'" class="card card-outline card-secondary align-items-center">
                    <div v-if="!isAlreadySubscribed" class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-mail-bulk fa-10x text-primary"></i>
                        </div>

                        <h2 class="text-center mt-5 text-primary">{{ $t('message.subscription.sub_confirm') }}</h2>
                        <p>
                            {{ $t('message.subscription.sub_confirm_note') }}
                        </p>

                        <button class="btn btn-primary btn-lg btn-block mt-5" @click="confirmSubscription('yes')">
                            {{ $t('message.subscription.sub_confirm_yes') }}
                        </button>
                    </div>

                    <!-- if user is already subscribed -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-check-circle fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">{{ $t('message.subscription.sub_confirm_thanks') }}</h3>
                        <p>{{ $t('message.subscription.sub_confirm_thanks_note') }}</p>

                        <button class="btn btn-success btn-lg btn-block mt-5" @click="redirectToLogin()">
                            {{ $t('message.subscription.sub_go_to_login') }}
                        </button>
                    </div>
                </div>

                <!-- CANCEL -->
                <div v-else class="card card-outline card-secondary align-items-center">
                    <div v-if="isAlreadySubscribed" class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="far fa-bell-slash fa-10x text-danger"></i>
                        </div>

                        <h2 class="text-center mt-5 text-danger">{{ $t('message.subscription.sub_unsub') }}</h2>
                        <p>
                            {{ $t('message.subscription.sub_unsub_note') }}
                        </p>

                        <button class="btn btn-danger btn-lg btn-block mt-5" @click="confirmSubscription('no')">
                            {{ $t('message.subscription.sub_unsub_button') }}
                        </button>
                    </div>

                    <!-- if user is already unsubscribed -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="far fa-bell-slash fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">{{ $t('message.subscription.sub_thanks') }}</h3>
                        <p>
                            {{ $t('message.subscription.sub_thanks_removed') }}
                        </p>

                        <button class="btn btn-success btn-lg btn-block mt-5" @click="redirectToLogin()">
                            {{ $t('message.subscription.sub_go_to_login') }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="col-12 col-md-12 col-lg-8 col-xl-6">
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-exclamation-triangle fa-10x text-danger"></i>
                        </div>

                        <h3 class="text-center mt-5 text-danger">{{ $t('message.subscription.sub_code_not_found') }}</h3>
                        <p>{{ $t('message.subscription.sub_code_not_found_note') }}</p>

                        <button class="btn btn-default btn-lg btn-block mt-5" @click="redirectToLogin()">
                            {{ $t('message.subscription.sub_go_to_login') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: [],

    data() {
        return {
            mode: null,
            code: null,
            isCodeError: false,
            isAlreadySubscribed: false,

            pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',
        }
    },

    watch : {
        pageLanguage(newvalue, oldValue) {
            this.$i18n.locale = newvalue;
        },
    },

    created() {
        this.checkRouteParams();
    },

    methods: {
        checkRouteParams () {
            if (this.$route.params.code) {
                this.code = this.$route.params.code;

                this.checkSubscriptionCode();
            }

            this.mode = this.$route.params.sub;

            this.hasUserAlreadySubscribed()
        },

        checkSubscriptionCode () {
            axios.post('/api/subscription/check-subscription-code', {
                code: this.code
            })
            .then((response) => {
                this.isCodeError = Object.keys(response.data).length === 0;
            });
        },

        confirmSubscription (mode) {
            let self = this;

            axios.post('/api/subscription/subscribe-user', {
                code: this.code,
                mode: mode
            })
            .then((response) => {

                if (mode === 'yes') {
                    swal.fire(
                        self.$t('message.subscription.sub_thanks'),
                        self.$t('message.subscription.sub_confirm_sub'),
                        'success',
                    )
                } else {
                    swal.fire(
                        self.$t('message.subscription.sub_thanks'),
                        self.$t('message.subscription.sub_confirm_unsub'),
                        'success',
                    )
                }

                this.hasUserAlreadySubscribed();
            })
            .catch((err) => {
                swal.fire(
                    'Error',
                    err.response.data.message,
                    'error',
                )

                console.log(err.response.data.message)
            });
        },

        hasUserAlreadySubscribed () {
            axios.post('/api/subscription/check-subscribed', {
                code: this.code
            })
            .then((response) => {
                this.isAlreadySubscribed = Object.keys(response.data).length !== 0;
            });
        },

        redirectToLogin () {
            window.location.assign('http://' + window.location.hostname + '/login');
        },
    },
}
</script>

<style scoped>

</style>
