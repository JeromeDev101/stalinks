<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Buyer</label>
                                <select name="" class="form-control" v-model="filterModel.buyer">
                                    <option value="">All</option>
                                    <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Payment Type</label>
                                <select name="" class="form-control" v-model="filterModel.payment_type">
                                    <option value="">All</option>
                                    <option v-for="option in listPayment.data" v-bind:value="option.id">
                                        {{ option.type }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" class="form-control" v-model="filterModel.date"  name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" >Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Wallet Transaction</h3>
                    <span class="ml-5 text-primary" v-show="user.role_id == 5">Total deposit: <b>${{listWallet.deposit}}</b></span>
                    <button data-toggle="modal" @click="clearMessageform" data-target="#modal-add-wallet" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add Wallet</button>
                </div>

                <div class="box-body no-padding relative">
                    <table id="tbl_wallet_transaction" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Id Buyer</th>
                                <th>Buyer</th>
                                <th>Payment Via</th>
                                <th>Amount USD</th>
                                <th>Date</th>
                                <th>Proof of Doc</th>
                                <th>Confirmation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(wallet, index) in listWallet.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ wallet.user_id }}</td>
                                <td>{{ wallet.user.name}}</td>
                                <td>{{ wallet.payment_type.type }}</td>
                                <td>$ {{ wallet.amount_usd }}</td>
                                <td>{{ wallet.date }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="Proof of Documents" @click="doShow(wallet.proof_doc)" data-target="#modal-wallet-docs" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                                <td>{{ wallet.admin_confirmation }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="Edit" @click="doUpdate(wallet)" data-target="#modal-wallet-transaction-update" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-pencil"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Modal Show proof -->
        <div class="modal fade" id="modal-wallet-docs" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Proof of Documents</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid" :src="proof_doc" atl="Proof of Billing" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Show proof -->

        <!-- Modal Add Wallet -->
        <div class="modal fade" id="modal-add-wallet"
             tabindex="-1" role="dialog"
             aria-labelledby="modelTitleId" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Wallet Transaction</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="walletStep
                         === 0">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.user_id_buyer}">
                                    <label for="">User Buyer</label>
                                    <select name="" class="form-control" v-model="updateModel.user_id_buyer">
                                        <option value="">Select Buyer</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ 'ID: ' + option.id + ' - Name: ' + option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.user_id_buyer" v-for="err in messageForms.errors.user_id_buyer" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                    <label for="">Amount USD</label>
                                    <input type="number" class="form-control" name="" placeholder="0.00" v-model="updateModel.amount_usd">
                                    <span v-if="messageForms.errors.amount_usd" v-for="err in messageForms.errors.amount_usd" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label for="">Payment Via</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.payment_type" v-for="err in messageForms.errors.payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-12"
                                 v-if="updateModel.payment_type != 1">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row"
                            v-show="walletStep === 1">
                            <div class="col-sm-12">
                                <div id="smart-button-container">
                                    <div style="text-align: center;">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"
                         v-if="walletStep === 0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitPay" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Wallet -->

        <!-- Modal Update Wallet -->
        <div class="modal fade" id="modal-wallet-transaction-update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Wallet Transaction</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.user_id_buyer}">
                                    <label for="">User Buyer</label>
                                    <select name="" class="form-control" v-model="editModel.user_id_buyer">
                                        <option value="">Select Buyer</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ 'ID: ' + option.id + ' - Name: ' + option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.user_id_buyer" v-for="err in messageForms.errors.user_id_buyer" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                    <label for="">Amount USD</label>
                                    <input type="number" class="form-control" name="" placeholder="0.00" v-model="editModel.amount_usd">
                                    <span v-if="messageForms.errors.amount_usd" v-for="err in messageForms.errors.amount_usd" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label for="">Payment Via</label>
                                    <select name="" class="form-control" v-model="editModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.payment_type" v-for="err in messageForms.errors.payment_type" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof_edit" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.admin_confirmation}">
                                    <label for="">Confirmation</label>
                                    <select name="" class="form-control" v-model="editModel.admin_confirmation">
                                        <option value="">Select Confirmation</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Not Paid">Not Paid</option>
                                    </select>
                                    <span v-if="messageForms.errors.admin_confirmation" v-for="err in messageForms.errors.admin_confirmation" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdatePay" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Wallet -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                updateModel: {
                    user_id_buyer: '',
                    payment_type: '',
                    amount_usd: '',
                },
                editModel: {
                    id: '',
                    user_id_buyer: '',
                    payment_type: '',
                    amount_usd: '',
                    admin_confirmation: '',
                },
                isPopupLoading: false,
                proof_doc: '',
                filterModel: {
                    buyer: this.$route.query.buyer || '',
                    payment_type: this.$route.query.payment_type || '',
                    date: this.$route.query.date || '',
                },
                searchLoading: false,
                walletStep: 0
            }
        },

        async created() {
            this.getWalletTransactionList()
            this.getListBuyer();
            this.getListPaymentType();
        },

        computed: {
            ...mapState({
                listWallet: state => state.storeWalletTransaction.listWallet,
                listBuyer: state => state.storeWalletTransaction.listBuyer,
                messageForms: state => state.storeWalletTransaction.messageForms,
                listPayment: state => state.storeWalletTransaction.listPayment,
                user: state => state.storeAuth.currentUser,
            }),
        },

        methods: {
            initPaypalButtons() {
                paypal.Buttons({
                    style: {
                        shape: 'rect',
                        color: 'black',
                        layout: 'vertical',
                        label: 'pay',

                    },

                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{"amount":{"currency_code":"USD","value":1}}]
                        });
                    },

                    onApprove: function(data, actions) {
                        let vm = this;
                        return actions.order.capture().then(function(details) {
                            try {
                                $("#modal-add-wallet").modal('hide');
                                vm.updateModel = {
                                    user_id_buyer : '',
                                    payment_type : '',
                                    amount_usd : '',
                                };

                                swal.fire(
                                    'Success',
                                    'Successfully Added',
                                    'success'
                                );

                                vm.getWalletTransactionList();
                            } catch (err) {
                                console.log(err);
                            }
                        });
                    },

                    onError: function(err) {
                        swal.fire(
                            'Error',
                            'There was an error on processing your payment.',
                            'error'
                        )
                    }
                }).render('#paypal-button-container');
            },

            async getWalletTransactionList(params) {

                $('#tbl_wallet_transaction').DataTable().destroy();

                this.searchLoading = true;
                await this.$store.dispatch('actionGetWalletList', params);
                this.searchLoading = false;

                $('#tbl_wallet_transaction').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 7 },
                        { orderable: false, targets: '_all' }
                    ],
                });
            },

            async getListBuyer(params) {
                await this.$store.dispatch('actionGetListBuyer', params);
            },

            async getListPaymentType(params) {
                await this.$store.dispatch('actionGetListPaymentType', params);
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getWalletTransactionList({
                    params: {
                        buyer: this.filterModel.buyer,
                        payment_type: this.filterModel.payment_type,
                        date: this.filterModel.date,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    buyer: '',
                    payment_type: '',
                    date: '',
                }

                this.getWalletTransactionList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            doUpdate(wallet) {
                this.clearMessageform()
                let that = JSON.parse( JSON.stringify(wallet) )
                this.editModel.id = that.id
                this.editModel.user_id_buyer = that.user_id
                this.editModel.payment_type = that.payment_via_id
                this.editModel.amount_usd = that.amount_usd
                this.editModel.admin_confirmation = that.admin_confirmation
            },

            doShow(doc) {
                this.proof_doc = doc;
            },

            async submitUpdatePay() {
                this.formDataEdit = new FormData();
                this.formDataEdit.append('file', this.$refs.proof_edit.files[0]);
                this.formDataEdit.append('id', this.editModel.id);
                this.formDataEdit.append('payment_type', this.editModel.payment_type);
                this.formDataEdit.append('amount_usd', this.editModel.amount_usd);
                this.formDataEdit.append('user_id_buyer', this.editModel.user_id_buyer);
                this.formDataEdit.append('admin_confirmation', this.editModel.admin_confirmation);

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateWallet', this.formDataEdit)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    this.$refs.proof_edit.value = '';
                    this.getWalletTransactionList();
                }

            },

            async submitPay() {
                this.formData = new FormData();
                this.formData.append('payment_type', this.updateModel.payment_type);
                this.formData.append('amount_usd', this.updateModel.amount_usd);
                this.formData.append('user_id_buyer', this.updateModel.user_id_buyer);

                if (this.updateModel.payment_type !== 1) {
                    this.formData.append('file', this.$refs.proof.files[0]);
                }

                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddWallet', this.formData)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    if (this.updateModel.payment_type === 1) {
                        this.walletStep = 1;
                        this.initPaypalButtons();
                    } else {
                        $("#modal-add-wallet").modal('hide');
                        this.updateModel = {
                            user_id_buyer: '',
                            payment_type: '',
                            amount_usd: '',
                        }

                        this.$refs.proof.value = '';

                        swal.fire(
                            'Success',
                            'Successfully Added',
                            'success'
                        )

                        this.getWalletTransactionList();
                    }
                }

                this.$root.$refs.AppHeader.liveGetWallet()
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },

    }
</script>
