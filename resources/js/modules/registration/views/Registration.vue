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
                            
                            <!-- <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" v-model="RegisterModel.phone" name="" aria-describedby="helpId" placeholder="Enter your Phone">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}">
                                    <label for="">Country</label>
                                    <select name="" class="form-control" v-model="RegisterModel.country_id">
                                        <option value="">Select Country</option>
                                        <option v-for="option in countryList" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                    </select>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                    <label for="">Company Type</label>
                                    <select class="form-control" v-model="RegisterModel.company_type" @change="checkType()">
                                        <option value="Company">Company</option>
                                        <option value="Freelancer">Freelancer</option>
                                    </select>
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="isCompany">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.company_name}">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" name="" v-model="RegisterModel.company_name" aria-describedby="helpId" placeholder="Enter your Company Name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->

                            <div class="col-md-12 mt-4">
                                <button @click="submitForm" type="submit" class="btn btn-primary btn-flat pull-right">Submit <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                <!-- <button @click="loginPage" class="btn btn-default btn-flat">Login</button> -->
                            </div>

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
                accountType: ['Seller', 'Buyer', 'Writer'],
                RegisterModel: {
                    username: '',
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    type: '',
                    // company_type: 'Company',
                    country_id: '',
                },

                isPopupLoading: false,
                isVerifiedEmail: false,
                isCompany: true,
                isCompanySelected: false,
                // countryList: [],
            }
        },

        created() {
            // this.getListCountry();
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
                // messageFormsMailgun: state => state.storeMailgun.messageForms,
            }),
        },

        methods: {
            // getListCountry() {
            //     axios.get('/api/registration-country-list')
            //         .then((res) => {
            //             this.countryList = res.data.data;
            //         })
            // },

            // checkType() {
            //     if (this.RegisterModel.company_type == 'Company') {
            //         this.isCompany = true;
            //         this.isCompanySelected = false;
            //         this.RegisterModel.type = ''
            //     } else {
            //         this.isCompany = false;
            //         this.isCompanySelected = true;
            //         this.RegisterModel.type = 'Freelance'
            //     }
            // },

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
                }
            },
        }
    }
</script>