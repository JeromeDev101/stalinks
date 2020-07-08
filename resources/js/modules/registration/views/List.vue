<template>
    <div class="row">
        <div class="col-sm-12">
            <!-- <section class="content-header col-sm-12">
                <h1>Accounts</h1>
            </section> -->

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search Name and Email</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="" v-model="filterModel.status">
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Account type</label>
                                <select class="form-control" name="" v-model="filterModel.type">
                                    <option value="">Select Type</option>
                                    <option value="seller">Seller</option>
                                    <option value="buyer">Buyer</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Accounts</h3>
                    <button class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-registration">Register</button>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company Name</th>
                                <th>Type</th>
                                <th>Skype</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(account, index) in listAccount.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ account.name }}</td>
                                <td>{{ account.email }}</td>
                                <td>{{ account.phone }}</td>
                                <td>{{ account.company_name }}</td>
                                <td>{{ account.type }}</td>
                                <td>{{ account.skype }}</td>
                                <td>{{ account.status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdateAccount(account)" data-target="#modal-update-registration" title="Edit" class="btn btn-default">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Modal Update Registration -->
        <div class="modal fade" id="modal-update-registration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Account</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.username" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.name" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.email" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.phone" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" v-model="accountUpdate.password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" v-model="accountUpdate.c_password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Account Type</label>
                                    <select class="form-control" name="" v-model="accountUpdate.type">
                                        <option value="">Select Type</option>
                                        <option value="Seller">Seller</option>
                                        <option value="Buyer">Buyer</option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.company_name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Skype</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.skype">
                                    <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Type</label>
                                    <select class="form-control" name="" v-model="accountUpdate.id_payment_type">
                                        <option value="">Select Type</option>
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.id_payment_type" v-for="err in messageForms.errors.id_payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Email</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.payment_email">
                                    <span v-if="messageForms.errors.payment_email" v-for="err in messageForms.errors.payment_email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Account</label>
                                    <input type="text" class="form-control" v-model="accountUpdate.payment_account">
                                    <span v-if="messageForms.errors.payment_account" v-for="err in messageForms.errors.payment_account" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Commission</label>
                                    <select class="form-control" name="" v-model="accountUpdate.commission">
                                        <option value="">Select Commission</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="">Status</label>
                                <select class="form-control" name="" v-model="accountUpdate.status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="">Credit Authorization</label>
                                <select class="form-control" name="" v-model="accountUpdate.credit_auth">
                                    <option value=""></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Registration -->

        <!-- Modal Registration -->
        <div class="modal fade" id="modal-registration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registration</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" class="form-control" v-model="accountModel.username" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.username" v-for="err in messageForms.errors.username" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="accountModel.name" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" v-model="accountModel.email" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" v-model="accountModel.phone" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" v-model="accountModel.password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.password" v-for="err in messageForms.errors.password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Confirm Password</label>
                                    <input type="password" class="form-control" v-model="accountModel.c_password" name="" aria-describedby="helpId" placeholder="">
                                    <span v-if="messageForms.errors.c_password" v-for="err in messageForms.errors.c_password" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Account Type</label>
                                    <select class="form-control" name="" v-model="accountModel.type">
                                        <option value="">Select Type</option>
                                        <option value="Seller">Seller</option>
                                        <option value="Buyer">Buyer</option>
                                    </select>
                                    <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" class="form-control" v-model="accountModel.company_name">
                                    <span v-if="messageForms.errors.company_name" v-for="err in messageForms.errors.company_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Skype</label>
                                    <input type="text" class="form-control" v-model="accountModel.skype">
                                    <span v-if="messageForms.errors.skype" v-for="err in messageForms.errors.skype" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Type</label>
                                    <select class="form-control" name="" v-model="accountModel.id_payment_type">
                                        <option value="">Select Type</option>
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.id_payment_type" v-for="err in messageForms.errors.id_payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Email</label>
                                    <input type="text" class="form-control" v-model="accountModel.payment_email">
                                    <span v-if="messageForms.errors.payment_email" v-for="err in messageForms.errors.payment_email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Payment Account</label>
                                    <input type="text" class="form-control" v-model="accountModel.payment_account">
                                    <span v-if="messageForms.errors.payment_account" v-for="err in messageForms.errors.payment_account" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Commission</label>
                                    <select class="form-control" name="" v-model="accountModel.commission">
                                        <option value="">Select Commission</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.commission" v-for="err in messageForms.errors.commission" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Registration -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                accountModel: {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    commission: '',
                },


                filterModel: {
                    type: this.$route.query.type || '',
                    search: this.$route.query.search || '',
                    status: this.$route.query.status || ''
                },

                accountUpdate: {
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    commission: '',
                    status: '',
                    username:'',
                },

                isPopupLoading: false,
                isSearchLoading: false,

            }
        },

        async created() {
            this.getAccountList();
            this.getPaymentTypeList();
        },

        computed: {
            ...mapState({
                messageForms: state => state.storeAccount.messageForms,
                listAccount: state => state.storeAccount.listAccount,
                listPayment: state => state.storeAccount.listPayment,
            }),
        },

        methods: {
            async submitAdd(){
                let that = this;
                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddAccount', that.accountModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'saved_account') {
                    this.clearAccountModel();
                    this.getAccountList({
                        params: this.filterModel
                    });
                }
            },

            async submitUpdate(){
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateAccount', this.accountUpdate);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_account') {
                    for (var index in this.listAccount.data) {
                        if (this.listAccount.data[index].id === this.accountUpdate.id) {
                            this.listAccount.data[index] = this.accountUpdate;
                            break;
                        }
                    }
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageFormAccount');
            },

            doUpdateAccount(account){
                this.clearMessageform();
                this.accountUpdate = JSON.parse(JSON.stringify(account))
                this.accountUpdate.password = '';
                this.accountUpdate.c_password = '';
            },

            async getPaymentTypeList(params) {
                await this.$store.dispatch('actionGetListPayentType', params);
            },

            async getAccountList(params) {
                this.isLoadingTable = true;
                this.isSearchLoading = true;
                await this.$store.dispatch('actionGetAccount', {
                    params: {
                        type: this.filterModel.type,
                        status: this.filterModel.status,
                        search: this.filterModel.search,
                    }
                });
                this.isLoadingTable = false;
                this.isSearchLoading = false;
            },

            clearSearch() {
                this.filterModel = {
                    type: '',
                    status: '',
                    search: '',
                }

                this.getAccountList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async doSearch(){
                this.$router.push({
                    query: this.filterModel,
                });

                this.getAccountList({
                    params: {
                        status: this.filterModel.status,
                        search: this.filterModel.search,
                        type: this.filterModel.type
                    }
                });
            },

            clearAccountModel() {
                this.accountModel = {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    c_password: '',
                    type: '',
                    company_name: '',
                    skype: '',
                    id_payment_type: '',
                    payment_email: '',
                    payment_account: '',
                    commission: '',
                };
            },
        }
    }
</script>