<template>
    <div>
        <div class="row">
            <div class="col-sm-3">
                <!-- USERS LIST -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Avatar</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="users-list avatar clearfix">
                            <li>
                                <img v-bind:src="user.avatar ? user.avatar : defaultAvatar" alt="User Image" class="img-fluid avatar-img">
                            </li>
                            <li>
                                <label>Username</label>
                                <h3>{{ user.username }}</h3>
                            </li>
                            <li>
                                <input type="file"
                                           class="form-control mb-2"
                                           enctype="multipart/form-data"
                                           ref="photo"
                                           accept="image/png, image/gif, image/jpeg"
                                           name="photo">
                                <button class="btn btn-block btn-default btn-sm" @click="submitUpload">Upload photo</button>
                            </li>
                            <li>
                                <p>
                                    By registering with us, you have read and accepted the
                                    <a
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#modalTermsAndCondition">
                                        Terms and Conditions
                                    </a>
                                </p>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Information</h3>
                        <h3 class="box-title"  v-if="currentUser.isOurs == 0">[Team]</h3>
                        <h3 class="box-title"  v-if="currentUser.isOurs == 1">[Registration Account]</h3>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <tbody>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                                            <input type="text" v-model="user.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Email</b></td>
                                    <td>
                                        <input type="text" class="form-control" v-model="user.email" :disabled="true">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Phone</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">
                                            <input type="text" v-model="user.phone" class="form-control" value="" required="required" placeholder="Enter Phone">
                                            <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 0">
                                    <td><b>Role</b></td>
                                    <td>{{ user.role ? user.role.name : null }}</td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>Type</b></td>
                                    <td>{{ user.user_type ? user.user_type.type: '' }}</td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1 && user.user_type.type == 'Writer'">
                                    <td><b>Pricing Type</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.rate_type}">
                                            <select class="form-control" v-model="user.user_type.rate_type">
                                                <option value="ppw">Pay Per Words (PPW)</option>
                                                <option value="ppa">Pay Per Article (PPA)</option>
                                            </select>
                                            <span v-if="messageForms.errors.rate_type" class="text-danger">
                                                The Pricing Type field is required.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1 && user.user_type.type == 'Writer'">
                                    <td><b>Writer Price (USD)</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.writer_price}" class="form-group">
                                            <input type="number" v-model="user.user_type.writer_price" class="form-control" value="" required="required" placeholder="Enter Price">
                                            <span
                                                v-if="messageForms.errors.writer_price"
                                                class="text-danger">
                                                The Writer Price field is required.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>Company Type</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.company_type}" class="form-group">
                                            <select class="form-control"  v-model="company_type" @click="checkCompanyType()">
                                                <option value="Company">Company</option>
                                                <option value="Freelancer">Freelancer</option>
                                            </select>
                                            <span v-if="messageForms.errors.company_type" v-for="err in messageForms.errors.company_type" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1" v-show="CompanyName">
                                    <td><b>Company Name</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'has-error': messageForms.errors.hasOwnProperty('user_type.company_name')}" class="form-group">
                                            <input type="text" v-model="user.user_type.company_name" class="form-control" value="" required="required" placeholder="Enter Company Name">
                                            <span
                                                v-if="messageForms.errors.hasOwnProperty('user_type.company_name')"
                                                class="text-danger">
                                                The company name field is required when company type is 'Company'
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1" v-show="CompanyName">
                                    <td><b>Company URL</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.company_url}" class="form-group">
                                            <input type="text" v-model="user.user_type.company_url" class="form-control" value="" required="required" placeholder="Enter Company URL">
                                            <span v-if="messageForms.errors.company_url" v-for="err in messageForms.errors.company_url" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Skype</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.skype}" class="form-group">
                                            <input type="text" v-model="user.skype" class="form-control" value="" required="required" placeholder="Enter Skype">
                                            <span
                                                v-if="messageForms.errors.skype"
                                                class="text-danger">
                                                The skype field is required when user is internal.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>Country</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                            <select name="" class="form-control" v-model="country_id">
                                                <option v-for="option in countryList" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                            <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>New Password</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                            <input type="password" class="form-control" v-model="new_password" placeholder="Type New Password">
                                            <span
                                                v-if="messageForms.errors.password"
                                                v-for="err in messageForms.errors.password"
                                                class="text-danger">

                                                {{ err }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Confirm Password</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.c_password}" class="form-group">
                                            <input type="password" class="form-control" v-model="c_password" placeholder="Type Confirm Password">
                                            <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 0">
                                    <td colspan="2">
                                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="currentUser.isOurs === 1">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Billing</h3>
                    </div>

                    <div class="box-body no-padding">

                        <div class="alert alert-info m-2">
                            <i class="fa fa-info-circle"></i>
                            Please provide at least one payment solution to be able to upload URL's.
                        </div>

                        <span
                            v-if="messageForms.errors.id_payment_type"
                            class="text-danger mx-2">
                        Please provide at least one payment type.
                    </span>

                        <div class="table-responsive">

                            <!-- payment for seller and writer -->
                            <table class="table no-margin" v-if="user.user_type.type === 'Seller' || user.user_type.type === 'Writer'">
                                <tbody>
                                    <tr v-for="(payment_method, index) in paymentMethodListSendPayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} Account</label>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="billing.payment_default">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="billing.payment_type[payment_method.id]">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end of payment for seller and writer -->

                            <!-- payment for buyer -->
                            <table class="table no-margin" v-if="user.user_type.type === 'Buyer'">
                                <tbody>
                                    <tr v-for="(payment_method, index) in paymentMethodListReceivePayment" :key="index">
                                        <td>
                                            <div class="form-group">
                                                <label>{{ payment_method.type }} Account</label>
                                            </div>
                                        </td>
                                        <td style="width: 50px;vertical-align:middle;" class="text-center">
                                            <input type="radio" class="btn-radio-custom" name="payment_default" v-bind:value="payment_method.id" v-model="billing.payment_default">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" v-model="billing.payment_type[payment_method.id]">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end of payment for buyer -->

                        </div>
                    </div>

                    <div class="ml-3">
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="currentUser.isOurs == 1 && currentUser.role_id == 5 && (currentUser.registration != null && currentUser.registration.is_sub_account == 0)">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header"  >
                        <h3 class="box-title">Add Sub Account</h3>
                    </div>

                    <div class="box-body no-padding" >
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <tbody>
                                <tr>
                                    <td><b>Username</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.username}" class="form-group">
                                            <input type="text" v-model="modelAddSubAccount.username" class="form-control" required="required" >
                                            <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Email</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                            <input type="text" v-model="modelAddSubAccount.email" class="form-control" required="required" >
                                            <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Password</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                            <input type="password" v-model="modelAddSubAccount.password" class="form-control" required="required">
                                            <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <button type="button" @click="addSubAccount" class="btn btn-primary">Add Sub Account</button>
                    </div>

                    <div class="col-md-12">
                        <table class="table table-condensed table-hover table-bordered mt-4">
                            <thead>
                            <tr class="label-primary">
                                <th >Username</th>
                                <th>Email</th>
                                <th>Credit Authorization</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(account, index) in ListSubAccounts" :key="index">
                                <td>{{ account.username }}</td>
                                <td>{{ account.email }}</td>
                                <td>{{ account.credit_auth }}</td>
                                <td>{{ account.status }}</td>
                                <td>
                                    <button class="btn btn-default" title="Edit" @click="doUpdateSubAccounts(account)" data-toggle="modal" data-target="#modal-edit-sub-account">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-default" title="Delete" @click="deleteSubAccount(account.id)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Sub Account -->
        <div class="modal fade" id="modal-edit-sub-account" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.username" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" v-model="updateModelSubAccount.password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageErrors.c_password != ''}" class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" v-model="updateModelSubAccount.c_password">
                                    <span v-show="messageErrors.c_password != ''" class="text-danger">{{ messageErrors.c_password }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.email" :disabled="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" v-model="updateModelSubAccount.status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Credit Authorization</label>
                                    <select class="form-control" v-model="updateModelSubAccount.credit_auth">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submitUpdateSubAccount">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Edit Sub Account -->

        <terms-and-conditions></terms-and-conditions>
    </div>
</template>

<style scoped>
    .avatar-img {
        height: 300px;
        width: 300px;
        object-fit: cover;
        border: 8px solid silver;
    }

    .btn-radio-custom {
        transform: scale(2);
    }
</style>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';
import TermsAndConditions from "../../../components/terms/TermsAndConditions";

export default {
    name: 'Profile',
    components: {TermsAndConditions},
    data() {
        return {
            modelAddSubAccount: {
                username: '',
                email: '',
                password: '',
            },
            defaultAvatar: config.avatar_url,
            extDomain:{
                status: 0,
                country_id: 0,
                country_id_of_user: [],
                status_of_user: [],
                employee_id: 0,
            },
            intDomain:{
                country_id: 0,
                country_id_of_user: [],
                employee_id: 0,
            },
            backLink:{
                country_id: 0,
                country_id_of_user: [],
                status: 0,
                status_of_backlink: [],
                employee_id: 0,
            },
            price:{
                country_id: 0,
                country_id_of_user: [],
                employee_id: 0,
            },
            optionStatus: [
                { text: 'Please select', value: 0 },
                { text: 'New', value: 'new' },
                { text: 'Crawl Failed', value: 'crawlfail' },
                { text: 'Contacts Null', value: 'contactnull' },
            ],
            publisher:{
                country_id: 0,
                country_id_of_user: [],
            },
            isBuyer: false,
            isSeller: false,
            billing: {
                btc_account: '',
                paypal_account: '',
                skrill_account: '',
                payment_default: '',
                payment_type: [],
            },
            new_password: '',
            c_password: '',
            ListSubAccounts: [],
            updateModelSubAccount: {
                name: '',
                username: '',
                email: '',
                status: '',
                password: '',
                c_password: '',
                credit_auth : ''
            },
            messageErrors:{
                c_password: '',
            },
            company_type: '',
            CompanyName: true,
            countryList: [],
            country_id: '',
            paymentMethodList: [],
        };
    },

    computed: {
        ...mapState({
            currentUser: state => state.storeAuth.currentUser,
            user: state => state.storeUser.userInfor,
            totalExt: state => state.storeExtDomain.totalExtDomain,
            totalInt: state => state.storeIntDomain.totalIntDomain,
            totalBackLink: state => state.storeBackLink.totalBackLink,
            totalPrice: state => state.storeBackLink.totalPrice,
            messageForms: state => state.storeUser.messageForms,
            listUser: state => state.storeUser.listUser,
            summaryPublish: state => state.storePublisher.summaryPublish,
        }),

        paymentMethodListSendPayment: function() {
            return this.paymentMethodList.filter(i => i.send_payment === 'yes')
        },

        paymentMethodListReceivePayment: function() {
            return this.paymentMethodList.filter(i => i.receive_payment === 'yes')
        },
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        let userId = this.$route.params.id || null;
        await this.$store.dispatch('actionGetUserInformation', {
            vue: this,
            id: userId,
        });

        this.checkAccountType();
        this.getPublisherSummaryCountry();
        this.bindPayment();
        this.getListPaymentMethod();

        // console.log(this.user)

        if(this.user.user_type) {
            this.company_type = this.user.user_type.is_freelance == '0' ? 'Company':'Freelancer';
            this.CompanyName = this.user.user_type.is_freelance == '0' ? true:false;
            this.country_id = this.user.user_type.country_id;
        }

        this.getListCountry();

        // this.$root.$refs.AppHeader.liveGetWallet()
    },

    mounted() {
        this.getListSubAccount();
    },

    methods: {

        getListPaymentMethod() {
            axios.get('/api/payment-list-registration')
                .then((res) => {
                    this.paymentMethodList = res.data.data
                })
        },

        submitUpload() {
            this.formData = new FormData();
            this.formData.append('photo', this.$refs.photo.files[0]);

            if( this.$refs.photo.value != '' ) {
                axios.post('/api/user-upload-avatar', this.formData)
                .then((res) => {
                    location.reload();
                })
            }

        },

        getListCountry() {
            axios.get('/api/registration-country-list')
                .then((res) => {
                    this.countryList = res.data.data;
                })
        },

        checkCompanyType() {
            if(this.company_type == 'Company') {
                this.CompanyName = true;
            } else {
                this.CompanyName = false;
            }
        },

        submitUpdateSubAccount() {
            axios.get('/api/update-sub-account', {
                params: {
                    id: this.updateModelSubAccount.id,
                    email: this.updateModelSubAccount.email,
                    name: this.updateModelSubAccount.name,
                    username: this.updateModelSubAccount.username,
                    status: this.updateModelSubAccount.status,
                    password: this.updateModelSubAccount.password,
                    c_password: this.updateModelSubAccount.c_password,
                    credit_auth: this.updateModelSubAccount.credit_auth
                }
            })
            .then((res) => {
                // console.log(res.data)
                this.getListSubAccount();
                $("#modal-edit-sub-account").modal('hide')

                swal.fire(
                    'Update!',
                    'Successfully Updated',
                    'success'
                )

                this.messageErrors = {
                    c_password: '',
                }
            })
            .catch(error => {
                if (error.response) {
                    this.messageErrors.c_password = error.response.data.errors.c_password[0]
                }
            })
        },

        deleteSubAccount(id) {
            swal.fire({
                title: "Are you sure ?",
                html: "Delete these account?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.get('/api/delete-sub-account',{ params: { id: id } })

                    swal.fire(
                        'Deleted!',
                        'Messages is move to Trash.',
                        'success'
                    )

                    this.getListSubAccount();
                }
            });
        },

        doUpdateSubAccounts(account) {
            this.messageErrors = {
                c_password: '',
            }

            this.updateModelSubAccount.id = account.id;
            this.updateModelSubAccount.name = account.name;
            this.updateModelSubAccount.username = account.username;
            this.updateModelSubAccount.status = account.status;
            this.updateModelSubAccount.email = account.email;
            this.updateModelSubAccount.credit_auth = account.credit_auth;
        },

        getListSubAccount() {
            axios.get('/api/sub-account')
                .then((res) => {
                    this.ListSubAccounts = res.data;
                })
        },

        bindPayment() {
            let that = this.user.user_type

            // console.log(this.user)
            if( this.currentUser.isOurs == 1 ) {
                // this.billing.paypal_account = that.paypal_account;
                // this.billing.skrill_account = that.skrill_account;
                // this.billing.btc_account = that.btc_account;
                this.billing.payment_default = this.user.id_payment_type;

                if(typeof this.user.user_payment_types != "undefined" && this.user.user_payment_types != null){
                    if(this.user.user_payment_types.length > 0) {
                        for(let index in this.user.user_payment_types) {
                            var payment_id = this.user.user_payment_types[index].payment_id;

                            this.billing.payment_type[payment_id] = this.user.user_payment_types[index].account
                        }
                    }
                }
            } 
        },

        async addSubAccount() {
            await this.$store.dispatch('actionAddSubAccount', this.modelAddSubAccount);

            if (this.messageForms.action === 'saved_account') {
                swal.fire(
                    'Success',
                    'Successfully created!',
                    'success'
                )

                this.modelAddSubAccount = {
                    username: '',
                    email: '',
                    password: '',
                }

                this.getListSubAccount();
            }

        },

        async getPublisherSummaryCountry() {
            let that = this;
            that.publisher.country_id_of_user = [],

            that.publisher.country_id = that.user.id;
            await this.$store.dispatch('getSummaryPublisher', {
                vue: this,
                params: this.publisher,
            });
        },

        async submitUpdate() {
            this.isPopupLoading = true;

            if( this.currentUser.isOurs == 1 ) {
                // this.user.user_type.paypal_account = this.billing.paypal_account;
                // this.user.user_type.skrill_account = this.billing.skrill_account;
                // this.user.user_type.btc_account = this.billing.btc_account;

                this.user.payment_type = this.billing.payment_type;
                this.user.id_payment_type = this.billing.payment_default;
                this.user.user_type.company_type = this.company_type;
                this.user.user_type.country_id = this.country_id;
            }

            this.user.password = this.new_password;
            this.user.c_password = this.c_password;

            await this.$store.dispatch('actionUpdateUser', this.user);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_user') {
                for (var index in this.listUser.data) {
                    if (this.listUser.data[index].id === this.user.id) {
                        this.listUser.data[index] = this.user;
                        break;
                    }
                }

                swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )

                this.new_password = '';
                this.c_password = '';
            } else {
                swal.fire(
                    'Failed!',
                    'Information not updated.',
                    'error'
                )
            }
        },

        checkAccountType() {
            let that = this.currentUser
            if( that.user_type ){
                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }
        },

        checkArray(array) {
            return Hepler.arrayNotEmpty(array);
        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }


}
</script>

<style>
    .avatar li{
        width: 100%;
    }
    .table-user {
        padding-bottom: 73px;
    }
</style>
