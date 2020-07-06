<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search </label>
                                <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button class="btn btn-default">Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Wallet Transaction</h3>
                    <button data-toggle="modal" data-target="#modal-add-wallet" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add Wallet</button>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Id User Buyer</th>
                                <th>Payment Via</th>
                                <th>Amount USD</th>
                                <th>Date</th>
                                <th>Proof of Doc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="listWallet.data.length == 0">
                                <td colspan="7" class="text-center">No record</td>
                            </tr>
                            <tr v-for="(wallet, index) in listWallet.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ wallet.user_id }}</td>
                                <td>{{ wallet.payment_type.type }}</td>
                                <td>$ {{ wallet.amount_usd }}</td>
                                <td>{{ wallet.date }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button title="Proof of Documents" @click="doShow(wallet.proof_doc)" data-target="#modal-wallet-docs" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
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
        <div class="modal fade" id="modal-add-wallet" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Wallet Transaction</h5>
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
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
                },
                isPopupLoading: false,
                proof_doc: '',
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
            }),
        },

        methods: {
            async getWalletTransactionList(params) {
                await this.$store.dispatch('actionGetWalletList', params);
            },

            async getListBuyer(params) {
                await this.$store.dispatch('actionGetListBuyer', params);
            },

            async getListPaymentType(params) {
                await this.$store.dispatch('actionGetListPaymentType', params);
            },

            doUpdate(wallet) {
                this.clearMessageform()
                let that = JSON.parse( JSON.stringify(wallet) )
                this.editModel.id = that.id
                this.editModel.user_id_buyer = that.user_id
                this.editModel.payment_type = that.payment_via_id
                this.editModel.amount_usd = that.amount_usd
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
                this.formData.append('file', this.$refs.proof.files[0]);
                this.formData.append('payment_type', this.updateModel.payment_type);
                this.formData.append('amount_usd', this.updateModel.amount_usd);
                this.formData.append('user_id_buyer', this.updateModel.user_id_buyer);

                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddWallet', this.formData)
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){

                    this.updateModel = {
                        user_id_buyer: '',
                        payment_type: '',
                        amount_usd: '',
                    }

                    this.$refs.proof.value = '';
                    this.getWalletTransactionList();

                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },

    }
</script>