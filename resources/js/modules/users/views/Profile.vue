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
                                <tr v-if="currentUser.isOurs == 1">
                                    <td><b>New Password</b></td>
                                    <td>
                                        <input type="password" class="form-control" v-model="new_password">
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

    </div>
</template>

<script>
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';

export default {
    name: 'Profile',

    data() {
        return {
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

        this.$root.$refs.AppHeader.liveGetWallet()
    },

    methods: {
        bindPayment() {
            let that = this.user.user_type
            if( this.currentUser.isOurs == 1 ) {
                this.billing.paypal_account = that.paypal_account;
                this.billing.skrill_account = that.skrill_account;
                this.billing.btc_account = that.btc_account;
                this.billing.payment_default = this.user.id_payment_type;
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

            this.user.new_password = this.new_password;
            
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
