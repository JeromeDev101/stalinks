<template>
    <div class="homepage-login">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">

                        <!-- Set password Form -->
                        <div v-if="formSetPassword">
                            <h3>Complete Registration</h3> 
                            <hr class="mb-4"/>

                            <h4 class="text-primary">Account Information</h4> 
                            <hr/>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Account Type</label>
                                        <input type="text" class="form-control" :disabled="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Username</label>
                                        <input type="text" class="form-control" :disabled="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" :disabled="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="text" class="form-control" :disabled="true">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                        <label for="">Company Type</label>
                                        <select class="form-control">
                                            <option value="Company">Company</option>
                                            <option value="Freelancer">Freelancer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.company_name}">
                                        <label for="">Company Name</label>
                                        <input type="text" class="form-control" placeholder="Enter your Company Name">
                                        <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Skype</label>
                                        <input type="text" class="form-control" placeholder="Enter your Skype">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <h4 class="text-primary my-3">Account Information</h4> 
                            
                            <div class="row">
                                
                                <table class="table">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Paypal Account</label>
                                                <input type="text" class="form-control" >
                                                <span v-if="messageForms.errors.paypal_account" v-for="err in messageForms.errors.paypal_account" class="text-danger">{{ err }}</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="1" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">BTC Account</label>
                                                <input type="text" class="form-control" >
                                                <span v-if="messageForms.errors.btc_account" v-for="err in messageForms.errors.btc_account" class="text-danger">{{ err }}</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="3" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Skril Account</label>
                                                <input type="text" class="form-control">
                                                <span v-if="messageForms.errors.skrill_account" v-for="err in messageForms.errors.skrill_account" class="text-danger">{{ err }}</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="2" >
                                        </td>
                                    </tr>
                                </table>

                                <!-- <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.password}">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control" v-model="verifyModel.password" placeholder="Enter  password">
                                        <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}">
                                        <label for="">Confirm Password</label>
                                        <input type="password" class="form-control" v-model="verifyModel.c_password" placeholder="Enter confirm password">
                                        <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                    </div>
                                </div> -->

                                <div class="col-md-12 mt-4">
                                    <!-- <button class="btn btn-primary btn-flat" @click="submitRegister" >Register <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button> -->
                                    <button class="btn btn-primary btn-flat" >Register <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
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