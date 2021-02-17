<template>
    <div class="homepage-login">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">

                        <!-- Set password Form -->
                        <div>
                            <h3>Reset Password</h3>
                            <hr class="mb-4"/>
                            <div
                                class="alert alert-danger alert-dismissible" v-if="!tokenValid">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i
                                    class="icon fa fa-ban"></i> Invalid Token</h4>
                                Your reset password
                                token is either
                                invalid or expired,
                                try resetting your
                                password again or
                                contact your
                                administrator.
                            </div>
                            <div class="row" v-else>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            for="">New
                                                   Password
                                            <span class="text-danger">*</span></label>
                                        <input
                                            type="password" v-model="data.password" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            for="">Confirm Password
                                            <span class="text-danger">*</span></label>
                                        <input
                                            type="password"
                                            v-model="data.password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-12"
                                     v-if="resetError">
                                    <div
                                        class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i
                                            class="icon fa fa-ban"></i>Oops...</h4>
                                        Something went wrong
                                    </div>
                                </div>

                                <div class="col-md-12"
                                     v-if="resetSuccess">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i
                                            class="icon fa fa-check"></i>Reset password</h4>
                                        You have
                                        successfully
                                        reset your
                                        password, click
                                        <a href="/login">
                                            here</a> to login!
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button
                                        class="btn btn-primary btn-flat" :disabled="loading" @click="resetPassword">Reset Password <i class="fa fa-refresh fa-spin" v-if="loading" ></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                loading: false,
                tokenValid: true,
                resetSuccess: false,
                resetError: false,
                data : {
                    password : null,
                    password_confirmation : null,
                    token: null,
                    email: null
                }
            }
        },

        mounted() {
            this.loading = true;
            this.data.token = this.$route.query.t;
            this.data.email = this.$route.query.e;

            this.checkToken();
        },

        computed: {
        },

        methods: {
            checkToken() {
                axios.post('/api/validate-reset-password-token', this.data)
                    .then((response) => {
                        this.loading = false;
                    })
                    .catch((error) => {
                        this.tokenValid = false;
                    });
            },

            resetPassword() {
                axios.post('/api/reset-password', this.data)
                    .then((response) => {
                        this.resetSuccess = true;
                        this.data.password = null;
                        this.data.password_confirmation =
                            null;
                    })
                    .catch((error) => {
                        this.resetError = true;
                    });
            }
        }
    }
</script>
