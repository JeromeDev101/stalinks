<template>
    <div class="homepage-login">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">

                        <!-- Set password Form -->
                        <div v-if="formSetPassword">
                            <h4>Set Password</h4> 
                            <hr class="mb-4"/>

                            <div class="row">
                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.password}">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control" v-model="verifyModel.password" name="" id="" aria-describedby="helpId" placeholder="Enter  password">
                                        <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}">
                                        <label for="">Confirm Password</label>
                                        <input type="password" class="form-control" v-model="verifyModel.c_password" name="" id="" aria-describedby="helpId" placeholder="Enter confirm password">
                                        <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary btn-flat" @click="submitRegister" >Register <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Form -->
                        <div v-if="formVerified">
                            <h3 class="text-center my-5">Successful Registered!</h3>

                            <button class="btn btn-default btn-block" @click="redirectToLogin">Login</button>
                        </div>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                verifyModel: {
                    password: '',
                    c_password: '',
                },
                verificationCode: '' ,
                isPopupLoading: false,
                formVerified: false,
                formSetPassword: true,
            }
        },

        beforeMount() {
            this.checkVerificationCode()
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),
        },

        methods: {
            async checkVerificationCode() {
                await this.$store.dispatch('actionCheckVerificationCode', {
                    code: this.$route.params.code
                });

                if( !this.messageForms.action ){
                    this.redirectToLogin();
                }
            },

            async submitRegister() {
                this.isPopupLoading = true
                await this.$store.dispatch('actionSetPassword', { 
                    code: this.$route.params.code,
                    password: this.verifyModel.password,
                    c_password: this.verifyModel.c_password,
                });
                this.isPopupLoading = false

                if( this.messageForms.action === 'success'){
                    this.formVerified = true;
                    this.formSetPassword = false;
                }
            },

            redirectToLogin() {
                window.location.assign('http://' + window.location.hostname + '/login');
            },

            clearModel() {
                this.verifyModel = {
                    password: '',
                    c_password: '',
                }
            },
        }
    }
</script>