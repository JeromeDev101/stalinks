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
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="btn-group">
                                        <button title="Edit" data-target="#modal-wallet-transaction-update" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-pencil"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        
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
                                <div class="form-group">
                                    <label for="">User Buyer</label>
                                    <select name="" class="form-control" v-model="updateModel.user_id_buyer">
                                        <option value="">Select Buyer</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ 'ID: ' + option.id + ' - Name: ' + option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Amount USD</label>
                                    <input type="number" class="form-control" name="" placeholder="0.00" v-model="updateModel.amount_usd">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Payment Via</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Proof of Documents</label>
                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small>
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

         <!-- Modal Update Wallet Transaction -->
        <div class="modal fade" id="modal-wallet-transaction-update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Wallet Transaction</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Wallet Transaction -->

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
                isPopupLoading: false,
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
        },

    }
</script>