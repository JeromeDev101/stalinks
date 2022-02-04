<template>
    <div class="homepage-login">
        <div class="row justify-content-center mx-5 my-5">
            <div v-if="!isCodeError" class="col-12 col-md-12 col-lg-8 col-xl-6">
                <!-- CONFIRMATION -->
                <div v-if="mode === 'sub'" class="card card-outline card-secondary align-items-center">
                    <div v-if="!isAlreadySubscribed" class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-mail-bulk fa-10x text-primary"></i>
                        </div>

                        <h2 class="text-center mt-5 text-primary">Please confirm your subscription</h2>
                        <p>
                            If you clicked the subscribe button from our email by mistake, simply close this page.
                            You won't be subscribed if you don't click the confirmation button down below.
                        </p>

                        <button class="btn btn-primary btn-lg btn-block mt-5" @click="confirmSubscription('yes')">
                            Yes, subscribe me to the newsletter
                        </button>
                    </div>

                    <!-- if user is already subscribed -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-check-circle fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">Thank you for subscribing!</h3>
                        <p>You've been added to our list and will hear from us soon!</p>

                        <button class="btn btn-success btn-lg btn-block mt-5" @click="redirectToLogin()">
                            Go to Login
                        </button>
                    </div>
                </div>

                <!-- CANCEL -->
                <div v-else class="card card-outline card-secondary align-items-center">
                    <div v-if="isAlreadySubscribed" class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="far fa-bell-slash fa-10x text-danger"></i>
                        </div>

                        <h2 class="text-center mt-5 text-danger">Do you wish to unsubscribe to our newsletter?</h2>
                        <p>
                            If you want to stop receiving notification emails for new url uploads from us,
                            click the unsubscribe button below.
                        </p>

                        <button class="btn btn-danger btn-lg btn-block mt-5" @click="confirmSubscription('no')">
                            Unsubscribe
                        </button>
                    </div>

                    <!-- if user is already unsubscribed -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="far fa-bell-slash fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">Thank you!</h3>
                        <p>
                            You have been successfully removed from the subscriber list and won't receive any further
                            notification emails from us.
                        </p>

                        <button class="btn btn-success btn-lg btn-block mt-5" @click="redirectToLogin()">
                            Go to Login
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

                        <h3 class="text-center mt-5 text-danger">Subscription Code Not Found</h3>
                        <p>Contact an administrator to get the correct subscription link</p>

                        <button class="btn btn-default btn-lg btn-block mt-5" @click="redirectToLogin()">
                            Go to Login
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
        }
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
            axios.post('/api/subscription/subscribe-user', {
                code: this.code,
                mode: mode
            })
            .then((response) => {

                if (mode === 'yes') {
                    swal.fire(
                        'Thank you!',
                        'Your subscription has been confirmed!',
                        'success',
                    )
                } else {
                    swal.fire(
                        'Thank you!',
                        'You have been successfully unsubscribed to our newsletter.',
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
