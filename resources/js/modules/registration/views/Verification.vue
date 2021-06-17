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
                                        <label for="">Account Type <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.type">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.username">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" :disabled="true" v-model="regModel.email">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input type="text" class="form-control" v-model="regModel.phone">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                        <label for="">Company Type <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="regModel.company_type" @change="checkCompanyType()">
                                            <option value="Company">Company</option>
                                            <option value="Freelancer">Freelancer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12" v-show="isCompany">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('company_name')}">
                                        <label for="">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter your Company Name" v-model="regModel.company_name">
                                        <span v-show="errorMessage.hasOwnProperty('company_name')" class="text-danger">Please provide Company Name</span>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Skype</label>
                                        <input type="text" class="form-control" placeholder="Enter your Skype" v-model="regModel.skype">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('country_id')}">
                                        <label for="">Country <span class="text-danger">*</span></label>
                                        <select name="" class="form-control" v-model="regModel.country_id">
                                            <option value="">Select Country</option>
                                                <option v-for="option in countryList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                        <span v-show="errorMessage.hasOwnProperty('country_id')" class="text-danger">Please provide Country</span>
                                    </div>
                                </div>
                            </div>

                            <h4 class="text-primary my-3"  v-show="regModel.type == 'Writer'">Writer pricing</h4> 
                            <hr/>

                            <div class="row"  v-show="regModel.type == 'Writer'">

                                <div class="col-sm-6">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('rate_type')}">
                                        <label>Pricing type <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="regModel.rate_type">
                                            <option value="ppw">Pay Per Words (PPW)</option>
                                            <option value="ppa">Pay Per Article (PPA)</option>
                                        </select>
                                        <span v-show="errorMessage.hasOwnProperty('rate_type')" class="text-danger">Please provide Writer Pirce</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': errorMessage.hasOwnProperty('writer_price')}">
                                        <label for="">Writer Price (USD)<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="regModel.writer_price">
                                        <span v-show="errorMessage.hasOwnProperty('writer_price')" class="text-danger">Please provide Writer Pirce</span>
                                    </div>
                                </div>
                            </div>


                            <h4 class="text-primary my-3">Payment Information <span class="text-danger">*</span></h4> 
                            <span v-show="errorMessage.hasOwnProperty('id_payment_type')" class="text-danger">Please provide atleast one Payment Information</span>
                            <div class="row">
                                
                                <table class="table">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Paypal Account</label>
                                                <input type="text" class="form-control" v-model="regModel.paypal_account">
                                                <span v-show="errorMessage.hasOwnProperty('paypal_account')" class="text-danger">Please provide Paypal Account</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="1" v-model="regModel.id_payment_type">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">BTC Account</label>
                                                <input type="text" class="form-control" v-model="regModel.btc_account">
                                                <span v-show="errorMessage.hasOwnProperty('btc_account')" class="text-danger">Please provide BTC Account</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="3" v-model="regModel.id_payment_type">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="">Skril Account</label>
                                                <input type="text" class="form-control" v-model="regModel.skrill_account">
                                                <span v-show="errorMessage.hasOwnProperty('skrill_account')" class="text-danger">Please provide Skrill Account</span>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;">
                                            <input type="radio" name="payment_default" v-bind:value="2" v-model="regModel.id_payment_type">
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
                                    <button class="btn btn-primary btn-flat" @click="submitRegister">Register <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Form -->
                        <div v-if="formVerified">
                            <h3 class="text-center my-5">Successfully Registered!</h3>

                            <button class="btn btn-default btn-block" @click="redirectToLogin">Go to Login</button>
                        </div>
                            
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        data() {
            return {
                // verifyModel: {
                //     password: '',
                //     c_password: '',
                // },
                verificationCode: '' ,
                isPopupLoading: false,
                formVerified: false,
                formSetPassword: true,
                regModel: {
                    type: '',
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_type: 'Company',
                    company_name: '',
                    skype: '',
                    country_id: '',
                    paypal_account: '',
                    btc_account: '',
                    skrill_account: '',
                    id_payment_type: '',
                    writer_price: '',
                    rate_type: '',
                },
                countryList: [],
                errorMessage: [],
                isCompany: true,
            }
        },

        beforeMount() {
            this.checkVerificationCode();
        },

        created() {
            this.getListCountry();
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),
        },

        methods: {
            checkCompanyType() {
                if(this.regModel.company_type == 'Company') {
                    this.isCompany = true;
                } else {
                    this.isCompany = false;
                }
            },

            getListCountry() {
                axios.get('/api/registration-country-list')
                    .then((res) => {
                        this.countryList = res.data.data;
                    })
            },

            getInfo() {
                axios.get('/api/registration-get-info',{
                    params: {
                        code: this.$route.params.code
                    }
                }).then((res) => {
                    this.regModel.type = res.data.type
                    this.regModel.username = res.data.username
                    this.regModel.name = res.data.name
                    this.regModel.email = res.data.email
                })
            },

            async checkVerificationCode() {
                await this.$store.dispatch('actionCheckVerificationCode', {
                    code: this.$route.params.code
                });

                // console.log(this.messageForms.action)

                if( !this.messageForms.action ){
                    this.redirectToLogin();
                } else {
                    this.getInfo();
                }
            },

            submitRegister() {
                axios.post('/api/registration-get-update-info', this.regModel)
                    .then((res) => {
                        console.log(res.data)
                        this.formVerified = true;
                        this.formSetPassword = false;
                    })
                    .catch(err => {
                        if(err.response.status == 422) {
                            this.errorMessage = err.response.data.errors;
                        }
                    })
            },

            // async submitRegister() {
            //     this.isPopupLoading = true
            //     await this.$store.dispatch('actionSetPassword', { 
            //         code: this.$route.params.code,
            //         password: this.verifyModel.password,
            //         c_password: this.verifyModel.c_password,
            //     });
            //     this.isPopupLoading = false

            //     if( this.messageForms.action === 'success'){
            //         this.formVerified = true;
            //         this.formSetPassword = false;
            //     }
            // },

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