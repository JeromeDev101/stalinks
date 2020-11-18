<template>
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
                            <img v-bind:src="user.avatar ? user.avatar : defaultAvatar" alt="User Image">
                        </li>
                        <li>
                            <label>Username</label>
                            <h3>{{ user.username }}</h3>
                        </li>
                        <li>
                            <button class="btn btn-block btn-default btn-sm">Upload photo</button>
                        </li>
                    </ul>
                    
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="box box-primary table-user">
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
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>Company Name</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.company_name}" class="form-group">
                                            <input type="text" v-model="user.user_type.company_name" class="form-control" value="" required="required" placeholder="Enter Company Name">
                                            <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>Skype</b></td>
                                    <td v-if="user.user_type">
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.skype}" class="form-group">
                                            <input type="text" v-model="user.user_type.skype" class="form-control" value="" required="required" placeholder="Enter Skype">
                                            <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>New Password</b></td>
                                    <td>
                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.password}" class="form-group">
                                            <input type="password" class="form-control" v-model="new_password" placeholder="Type New Password">
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

        <div class="col-sm-12" v-if="currentUser.isOurs == 1">
            <div class="box box-primary table-user">
                <div class="box-header"  >
                    <h3 class="box-title">Billing</h3>
                </div>

                <div class="box-body no-padding" >
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <tbody>
                                <tr>
                                    <td><b>Paypal Account</b></td>
                                    <td style="width: 50px;padding-top:20px;">
                                        <input name="payment_default" v-bind:value="1" v-model="billing.payment_default" type="radio">
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" v-model="billing.paypal_account" class="form-control" value="" required="required" placeholder="Enter Paypal Account">
                                            <!-- <span v-if="messageForms.errors.paypal_account" v-for="err in messageForms.errors.paypal_account" class="text-danger">{{ err }}</span> -->
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Skrill Account</b></td>
                                    <td style="width: 50px;padding-top:20px;">
                                        <input name="payment_default" v-bind:value="2" v-model="billing.payment_default" type="radio">
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" v-model="billing.skrill_account" class="form-control" value="" required="required" placeholder="Enter Skrill Account">
                                            <!-- <span v-if="messageForms.errors.skrill_account" v-for="err in messageForms.errors.skrill_account" class="text-danger">{{ err }}</span> -->
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>BTC Account</b></td>
                                    <td style="width: 50px;padding-top:20px;">
                                        <input name="payment_default" v-bind:value="3" v-model="billing.payment_default" type="radio">
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" v-model="billing.btc_account" class="form-control" value="" required="required" placeholder="Enter BTC Account">
                                            <!-- <span v-if="messageForms.errors.btc_account" v-for="err in messageForms.errors.btc_account" class="text-danger">{{ err }}</span> -->
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-8">
                    <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                </div>


            </div>
        </div>


        <div class="col-sm-12" v-if="currentUser.isOurs == 1 && currentUser.role_id == 5">
            <div class="box box-primary table-user">
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
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in ListSubAccounts" :key="index">
                                <td>{{ account.username }}</td>
                                <td>{{ account.email }}</td>
                                <td>{{ account.status }}</td>
                                <td>
                                    <button class="btn btn-default" title="Edit" @click="doUpdateSubAccounts(account)" data-toggle="modal" data-target="#modal-edit-sub-account">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-default" title="Delete" @click="deleteSubAccount(account.id)"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                    <label for="">Username</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.username" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.name" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" v-model="updateModelSubAccount.email" :disabled="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" v-model="updateModelSubAccount.status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
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

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';

export default {
    name: 'Profile',

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
            },
            new_password: '',
            c_password: '',
            ListSubAccounts: [],
            updateModelSubAccount: {
                name: '',
                username: '',
                email: '',
                status: '',
            },
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
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        // if (!this.currentUser.isAdmin) {
        //     window.location.href = '/';
        //     return;
        // }
        let userId = this.$route.params.id || null;
        await this.$store.dispatch('actionGetUserInformation', {
            vue: this,
            id: userId,
        });

        // this.filterExtDomain();
        // this.filterIntDomain();
        // this.filterBacklink();
        // this.filterPrice();
        this.checkAccountType();
        this.getPublisherSummaryCountry();
        this.bindPayment();

        // this.$root.$refs.AppHeader.liveGetWallet()
    },

    mounted() {
        this.getListSubAccount();
    },

    methods: {
        submitUpdateSubAccount() {
            axios.get('/api/update-sub-account', {
                params: {
                    id: this.updateModelSubAccount.id,
                    email: this.updateModelSubAccount.email,
                    name: this.updateModelSubAccount.name,
                    username: this.updateModelSubAccount.username,
                    status: this.updateModelSubAccount.status,
                }
            })
            .then((res) => {
                console.log(res.data)
                this.getListSubAccount();
                $("#modal-edit-sub-account").modal('hide')

                swal.fire(
                    'Update!',
                    'Successfully Updated',
                    'success'
                )
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
            this.updateModelSubAccount = account;
        },

        getListSubAccount() {
            axios.get('/api/sub-account')
                .then((res) => {
                    console.log(res.data)
                    this.ListSubAccounts = res.data;
                })
        },

        bindPayment() {
            let that = this.user.user_type
            if( this.currentUser.isOurs == 1 ) {
                this.billing.paypal_account = that.paypal_account;
                this.billing.skrill_account = that.skrill_account;
                this.billing.btc_account = that.btc_account;
                this.billing.payment_default = this.user.id_payment_type;
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
                this.user.user_type.paypal_account = this.billing.paypal_account;
                this.user.user_type.skrill_account = this.billing.skrill_account;
                this.user.user_type.btc_account = this.billing.btc_account;
                this.user.id_payment_type = this.billing.payment_default;
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
            }
        },

        // async filterExtDomain() {
        //     let that = this;
        //     that.extDomain.country_id_of_user = [],
        //     that.extDomain.status_of_user = [],
        //     this.user.countries_accessable.forEach(function(country) {
        //         that.extDomain.country_id_of_user.push(country.id)
        //         if (country.external_domains) {
        //             country.external_domains.forEach(function(ext) {
        //                 that.extDomain.status_of_user.push(ext.status)
        //             })
        //         }
        //     });
        //     that.extDomain.employee_id = that.user.id;
        //     await this.$store.dispatch('filterExtDomain', {
        //         vue: this,
        //         params: this.extDomain,
        //     });
        // },

        checkAccountType() {
            let that = this.currentUser
            if( that.user_type ){
                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }
        },

        // async filterIntDomain() {
        //     let that = this;
        //     that.intDomain.country_id_of_user = [],

        //     this.user.internal_domains_accessable.forEach(function(internal_domain) {
        //         that.intDomain.country_id_of_user.push(internal_domain.country.id)
        //     });
        //     that.intDomain.employee_id = that.user.id;
        //     await this.$store.dispatch('filterIntDomain', {
        //         vue: this,
        //         params: this.intDomain,
        //     });
        // },

        // async filterBacklink() {
        //     let that = this;
        //     that.backLink.country_id_of_user = [],
        //     that.backLink.status_of_backlink = [],

        //     this.user.backlinks.forEach(function(backlink) {
        //         that.backLink.status_of_backlink.push(backlink.status)
        //         that.backLink.country_id_of_user.push(backlink.int_domain.country.id)
        //     });
        //     that.backLink.employee_id = that.user.id;
        //     await this.$store.dispatch('filterBackLink', {
        //         vue: this,
        //         params: this.backLink,
        //     });
        // },

        // async filterPrice() {
        //     let that = this;
        //     that.price.country_id_of_user = [],

        //     this.user.backlinks.forEach(function(backlink) {
        //         that.price.country_id_of_user.push(backlink.int_domain.country.id)
        //     });
        //     that.price.employee_id = that.user.id;
        //     await this.$store.dispatch('filterPrice', {
        //         vue: this,
        //         params: this.price,
        //     });
        // },

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
