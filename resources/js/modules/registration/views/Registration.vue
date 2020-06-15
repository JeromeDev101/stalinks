<template>
    <div class="homepage-login">

        <div class="row justify-content-center">

            <div class="col-md-5">
                <div class="card mt-5 pb-3">
                    <div class="card-body mx-5">
                        <h4>Registration</h4> 
                        <hr class="mb-4"/>

                        <div class="alert alert-success" v-if="isVerifiedEmail">
                            Please check your email to verify and complete the process.
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.name}">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="RegisterModel.name" name="" id="" aria-describedby="helpId" placeholder="Enter your name">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" v-model="RegisterModel.email" name="" id="" aria-describedby="helpId" placeholder="Enter your email">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" v-model="RegisterModel.phone" name="" id="" aria-describedby="helpId" placeholder="Enter your Phone">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.company_name}">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" name="" v-model="RegisterModel.company_name" id="" aria-describedby="helpId" placeholder="Enter your Company Name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.type}">
                                    <label for="">Account Type</label>
                                    <select name="" id="" class="form-control" v-model="RegisterModel.type">
                                        <option value="">Select Account type</option>
                                        <option value="Seller">Seller</option>
                                        <option value="Buyer">Buyer</option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button @click="submitForm" type="submit" class="btn btn-primary btn-flat pull-right">Submit <i class="fa fa-refresh fa-spin" v-if="isPopupLoading" ></i></button>
                                <button @click="loginPage" class="btn btn-default btn-flat">Login</button>
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

    export default {
        data() {
            return {
                RegisterModel: {
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    type: '',
                },

                isPopupLoading: false,
                isVerifiedEmail: false,
            }
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
                    this.clearRegistrationModel();
                    this.isVerifiedEmail = true;
                }
            },

            clearRegistrationModel() {
                this.RegisterModel = {
                    name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    type: '',
                }
            },
        }
    }
</script>