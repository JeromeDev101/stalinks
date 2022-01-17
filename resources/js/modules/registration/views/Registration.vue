<template>
    <div class="homepage-login">

        <div class="row justify-content-center">

            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">
                        <h4>Registration</h4>
                        <hr class="mb-4"/>

                        <div class="alert alert-success" v-if="isVerifiedEmail">
                            Please for validation check your email Inbox or Spam or Promotion to click on the link to complete the process.
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.type}">
                                    <label for="">Account Type <span class="text-danger">*</span></label>
                                    <select name="" class="form-control" v-model="RegisterModel.type" :disabled="isCompanySelected">
                                        <option value="">Select Account type</option>
                                        <option v-for="type in accountType" v-bind:value="type">
                                            {{ type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.username}">
                                    <label for="">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="RegisterModel.username" name="" aria-describedby="helpId" placeholder="Enter your username">
                                    <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}">
                                    <label for="">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="RegisterModel.email" name="" aria-describedby="helpId" placeholder="Enter your email">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.name}">
                                    <label for="">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="RegisterModel.name" name="" aria-describedby="helpId" placeholder="Enter your name">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.password}">
                                    <label for="">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" v-model="RegisterModel.password" name="" aria-describedby="helpId" placeholder="Enter your password">
                                    <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}">
                                    <label for="">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" v-model="RegisterModel.c_password" name="" aria-describedby="helpId" placeholder="Re-enter your password">
                                    <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-12" v-if="RegisterModel.type === 'Buyer'">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.affiliate_code}">
                                    <label class="mb-0">Affiliate Code</label>
                                    <br>
                                    <small class="font-italic text-secondary">
                                        <i class="fa fa-info-circle"></i>
                                        Enter code if you are registering as a buyer under an affiliate
                                    </small>
                                    <input
                                        v-model="RegisterModel.affiliate_code"
                                        type="text"
                                        class="form-control mt-2"
                                        placeholder="Enter code given by affiliate">

                                    <span
                                        v-if="messageForms.errors.affiliate_code"
                                        v-for="err in messageForms.errors.affiliate_code"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <p>
                                    <input type="checkbox" v-model="BtnAccept" @change="isEnableSubmit =  isEnableSubmit ? false:true">
                                    I've read and accept the <a href="#" data-toggle="modal" data-target="#modalTermsAndCondition">Terms and Condition</a>
                                </p>
                            </div>

                            <div class="col-md-12">
                                <span>Already have an account? </span>
                                <router-link :to="{ path: '/login' }">
                                    Login now.
                                </router-link>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button @click="submitForm" :disabled="isEnableSubmit" type="submit" class="btn btn-primary btn-flat pull-right">Submit <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import TermsAndConditions from "../../../components/terms/TermsAndConditions";

    export default {
        components: {TermsAndConditions},
        data() {
            return {
                accountType: ['Seller', 'Buyer', 'Writer', 'Affiliate'],
                RegisterModel: {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    type: '',
                    // company_type: 'Company',
                    country_id: '',
                    affiliate_code: '',
                },

                isPopupLoading: false,
                isVerifiedEmail: false,
                isCompany: true,
                isCompanySelected: false,
                isEnableSubmit: true,
                // countryList: [],
                BtnAccept: false
            }
        },

        created() {
            //
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
            }),
        },

        methods: {
            loginPage() {
                this.$router.push('login')
            },

            async submitForm() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionRegister', this.RegisterModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'registration_success') {

                    // Send Email Validation
                    axios.post('/api/registration-email-validation',{
                        email: this.RegisterModel.email,
                        title: 'Email Validation'
                    });

                    this.clearRegistrationModel();
                    this.isVerifiedEmail = true;
                    this.isEnableSubmit = true;
                    this.BtnAccept = false;

                }
            },

            clearRegistrationModel() {
                this.RegisterModel = {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    // company_type: 'Company',
                    type: '',
                    country_id: '',
                    affiliate_code: ''
                }
            },
        }
    }
</script>
