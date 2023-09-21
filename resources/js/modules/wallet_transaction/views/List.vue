<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.wallet_transaction.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.wallet_transaction.filter_buyer') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.buyer">
                                        <option value="">{{ $t('message.wallet_transaction.all') }}</option>
                                        <option v-for="option
                                     in
                                     listBuyerTransactions.data" v-bind:value="option.id">
                                            {{ option.username ? option.username : option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.wallet_transaction.filter_payment_type') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.payment_type">
                                        <option value="">{{ $t('message.wallet_transaction.all') }}</option>
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!--                        <div class="col-md-3">-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label>Date</label>-->
                            <!--                                <input type="date" class="form-control" v-model="filterModel.date"  name="" aria-describedby="helpId" placeholder="Type here">-->
                            <!--                            </div>-->
                            <!--                        </div>-->

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.wallet_transaction.filter_date') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch">
                                    {{ $t('message.wallet_transaction.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch">
                                    {{ $t('message.wallet_transaction.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.wallet_transaction.wt_title') }}</h3>
                        <div class="card-tools">
                            <button
                                v-if="user.isAdmin && user.permission_list.includes('create-billing-wallet-transaction')"
                                data-toggle="modal"
                                data-target="#modal-add-wallet"
                                class="btn btn-success float-right"

                                @click="clearMessageform">

                                <i class="fa fa-plus"></i>
                                {{ $t('message.wallet_transaction.wt_add_wallet') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="text-primary" v-show="[5, 14].includes(user.role_id)">
                            {{ $t('message.wallet_transaction.wt_total_deposit') }}
                            <b>${{listWallet.deposit}}</b>
                        </span>

                        <span class="text-primary">
                            {{ $t('message.wallet_transaction.wt_total_amount') }}
                            <b>${{ totalAmount }}</b>
                        </span>

                        <table id="tbl_wallet_transaction"
                               class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>{{ $t('message.wallet_transaction.wt_id_buyer') }}</th>
                                <th>{{ $t('message.wallet_transaction.filter_buyer') }}</th>
                                <th>{{ $t('message.wallet_transaction.wt_payment_via') }}</th>
                                <th>{{ $t('message.wallet_transaction.wt_amount_usd') }}</th>
                                <th>{{ $t('message.wallet_transaction.filter_date') }}</th>
                                <th>{{ $t('message.wallet_transaction.wt_proof_of_doc') }}</th>
                                <th>{{ $t('message.wallet_transaction.wt_confirmation') }}</th>
                                <th>{{ $t('message.wallet_transaction.wt_action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(wallet, index) in listWallet.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ wallet.user_id }}</td>
                                <td>{{ wallet.user.username}}</td>
                                <td>{{ wallet.payment_type.type }}</td>
                                <td>$ {{ wallet.amount_usd }}</td>
                                <td>{{ wallet.date }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            v-if="wallet.invoice && wallet.admin_confirmation != 'Refund processing'"
                                            :title="$t('message.wallet_transaction.wt_download_invoice')"
                                            @click="downloadInvoice(wallet.id)"
                                            class="btn btn-default">
                                            <i class="fa fa-fw fa-download"></i>
                                        </button>

                                        <button
                                            v-if="!wallet.invoice && wallet.admin_confirmation != 'Refund processing'"
                                            :title="$t('message.wallet_transaction.wt_proof_of_documents')"
                                            @click="doShow(wallet.proof_doc)"
                                            data-target="#modal-wallet-docs"
                                            data-toggle="modal"
                                            class="btn btn-default">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{ wallet.admin_confirmation }}</td>
                                <td>
                                    <div class="btn-group" v-if="user.isAdmin && user.permission_list.includes('update-billing-wallet-transaction')">
                                        <button
                                            :title="$t('message.wallet_transaction.edit')"
                                            @click="doUpdate(wallet)"
                                            data-target="#modal-wallet-transaction-update"
                                            data-toggle="modal"
                                            class="btn btn-default">

                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Show proof -->
        <div class="modal fade"
             id="modal-wallet-docs"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.wallet_transaction.wt_proof_of_documents') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid" :src="proof_doc" :atl="$t('message.wallet_transaction.pd_proof_billing')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.wallet_transaction.close') }}
                        </button>
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
                        <h5 class="modal-title">{{ $t('message.wallet_transaction.awt_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="walletStep === 0">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.user_id_buyer}">
                                    <label>{{ $t('message.wallet_transaction.awt_user_buyer') }}</label>

                                    <!-- <select name="" class="form-control" v-model="updateModel.user_id_buyer">
                                        <option value="">{{ $t('message.wallet_transaction.awt_select_buyer') }}</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ 'ID: ' + option.id + ' - Name: ' + option.name }}
                                        </option>
                                    </select> -->

                                    <v-select
                                            v-model="updateModel.user_id_buyer"
                                            label="username"
                                            :placeholder="$t('message.wallet_transaction.awt_user_buyer')"
                                            :options="listBuyer.data"
                                            :searchable="true"
                                            :reduce="buyer => buyer.id"
                                            >

                                            <template slot="selected-option" slot-scope="option">
                                                ID: {{ option.id }} - Username: {{ option.username ? option.username:option.name }}
                                            </template>

                                            <template slot="option" slot-scope="option">
                                                ID: {{ option.id }} - Username: {{ option.username ? option.username:option.name }}
                                            </template>
                                        </v-select>

                                    <span
                                        v-if="messageForms.errors.user_id_buyer"
                                        v-for="err in messageForms.errors.user_id_buyer"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                    <label>{{ $t('message.wallet_transaction.wt_amount_usd') }}</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name=""
                                        placeholder="0.00"
                                        v-model="updateModel.amount_usd">

                                    <span
                                        v-if="messageForms.errors.amount_usd"
                                        v-for="err in messageForms.errors.amount_usd"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label>{{ $t('message.wallet_transaction.wt_payment_via') }}</label>

                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.payment_type"
                                        v-for="err in messageForms.errors.payment_type"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="col-md-12" v-if="updateModel.payment_type != 1"> -->
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label>{{ $t('message.wallet_transaction.wt_proof_of_documents') }}</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        enctype="multipart/form-data"
                                        ref="proof"
                                        name="file">

                                    <small class="text-muted">
                                        {{ $t('message.wallet_transaction.awt_proof_note') }}
                                    </small>

                                    <br/>

                                    <span
                                        v-if="messageForms.errors.file"
                                        v-for="err in messageForms.errors.file"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.wallet_transaction.close') }}
                        </button>
                        <button type="button" @click="submitPay" class="btn btn-primary">
                            {{ $t('message.wallet_transaction.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Wallet -->

        <!-- Modal Update Wallet -->
        <div class="modal fade"
             id="modal-wallet-transaction-update"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.wallet_transaction.uw_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.user_id_buyer}">
                                    <label>{{ $t('message.wallet_transaction.awt_user_buyer') }}</label>
                                    <select name="" class="form-control" v-model="editModel.user_id_buyer">
                                        <option value="">{{ $t('message.wallet_transaction.awt_select_buyer') }}</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ 'ID: ' + option.id + ' - Name: ' + option.name }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="messageForms.errors.user_id_buyer"
                                        v-for="err in messageForms.errors.user_id_buyer"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                    <label>{{ $t('message.wallet_transaction.wt_amount_usd') }}</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name=""
                                        placeholder="0.00"
                                        v-model="editModel.amount_usd">

                                    <span
                                        v-if="messageForms.errors.amount_usd"
                                        v-for="err in messageForms.errors.amount_usd"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label>{{ $t('message.wallet_transaction.wt_payment_via') }}</label>

                                    <select name="" class="form-control" v-model="editModel.payment_type">
                                        <option v-for="option in listPayment.data" v-bind:value="option.id">
                                            {{ option.type }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.payment_type"
                                        v-for="err in messageForms.errors.payment_type"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <!-- <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                    <label></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="">
                                </div>
                            </div> -->

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label>{{ $t('message.wallet_transaction.wt_proof_of_documents') }}</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        enctype="multipart/form-data"
                                        ref="proof_edit"
                                        name="file">

                                    <small class="text-muted">
                                        {{ $t('message.wallet_transaction.awt_proof_note') }}
                                    </small>

                                    <br/>

                                    <span v-if="messageForms.errors.file"
                                          v-for="err in messageForms.errors.file"
                                          class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.admin_confirmation}">
                                    <label>{{ $t('message.wallet_transaction.wt_confirmation') }}</label>

                                    <select name="" class="form-control" v-model="editModel.admin_confirmation">
                                        <option value="">{{ $t('message.wallet_transaction.uw_select_confirmation') }}</option>
                                        <option value="Paid">{{ $t('message.wallet_transaction.uw_paid') }}</option>
                                        <option value="Not Paid">{{ $t('message.wallet_transaction.uw_not_paid') }}</option>
                                        <option value="Refund processing">{{ $t('message.wallet_transaction.uw_refund_processing') }}</option>
                                        <option value="Refunded">{{ $t('message.wallet_transaction.uw_refunded') }}</option>
                                        <option value="Refund Order">{{ $t('message.wallet_transaction.uw_refund_order') }}</option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.admin_confirmation"
                                        v-for="err in messageForms.errors.admin_confirmation"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.wallet_transaction.close') }}
                        </button>
                        <button type="button" @click="submitUpdatePay" class="btn btn-primary">
                            {{ $t('message.wallet_transaction.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Wallet -->
    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import {dateRange} from "../../../mixins/dateRange";

export default {
    mixins: [dateRange],
    data() {
        return {
            updateModel : {
                user_id_buyer : '',
                payment_type : '',
                amount_usd : '',
            },
            editModel : {
                id : '',
                user_id_buyer : '',
                payment_type : '',
                amount_usd : '',
                admin_confirmation : '',
            },
            isPopupLoading : false,
            proof_doc : '',
            filterModel : {
                buyer : this.$route.query.buyer || '',
                payment_type : this.$route.query.payment_type || '',
                date : {
                    startDate : null,
                    endDate : null
                },
            },
            searchLoading : false,
            totalAmount : 0,
            walletStep : 0
        }
    },

    async created() {
        this.getWalletTransactionList()
        this.getListBuyer();
        this.getListPaymentType();
    },

    computed : {
        ...mapState({
            listWallet : state => state.storeWalletTransaction.listWallet,
            listBuyer : state => state.storeWalletTransaction.listBuyer,
            listBuyerTransactions : state => state.storeWalletTransaction.listBuyerTransactions,
            messageForms : state => state.storeWalletTransaction.messageForms,
            listPayment : state => state.storeWalletTransaction.listPayment,
            user : state => state.storeAuth.currentUser,
        }),
    },

    methods : {
        initPaypalButtons() {
            let self = this;
            paypal.Buttons({
                style : {
                    shape : 'rect',
                    color : 'black',
                    layout : 'vertical',
                    label : 'pay',

                },

                createOrder : function (data, actions) {
                    return actions.order.create({
                        purchase_units : [{"amount" : {"currency_code" : "USD", "value" : 1}}]
                    });
                },

                onApprove : function (data, actions) {
                    let vm = this;
                    return actions.order.capture().then(function (details) {
                        try {
                            $("#modal-add-wallet").modal('hide');
                            vm.updateModel = {
                                user_id_buyer : '',
                                payment_type : '',
                                amount_usd : '',
                            };

                            swal.fire(
                                self.$t('message.wallet_transaction.alert_success'),
                                self.$t('message.wallet_transaction.alert_successfully_added'),
                                'success'
                            );

                            vm.getWalletTransactionList();
                        } catch (err) {
                            console.log(err);
                        }
                    });
                },

                onError : function (err) {
                    swal.fire(
                        self.$t('message.wallet_transaction.alert_error'),
                        self.$t('message.wallet_transaction.alert_error_payment'),
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
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 7},
                    {orderable : false, targets : '_all'}
                ],
            });

            this.getTotalAmount()
        },

        async getListBuyer(params) {
            await this.$store.dispatch('actionGetListBuyer', params);
            await this.$store.dispatch('actionGetListBuyerWithTransaction', params);
        },

        async getListPaymentType(params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },

        doSearch() {
            // change the format of date
            this.filterModel.date = this.formatFilterDates(this.filterModel.date)

            this.$router.push({
                query : this.filterModel,
            });

            this.getWalletTransactionList({
                params : {
                    buyer : this.filterModel.buyer,
                    payment_type : this.filterModel.payment_type,
                    date : this.filterModel.date,
                }
            });
        },

        clearSearch() {
            this.filterModel = {
                buyer : '',
                payment_type : '',
                date : {
                    startDate : null,
                    endDate : null
                },
            }

            this.getWalletTransactionList({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
        },

        doUpdate(wallet) {
            this.clearMessageform()
            let that = JSON.parse(JSON.stringify(wallet))
            this.editModel.id = that.id
            this.editModel.user_id_buyer = that.user_id
            this.editModel.payment_type = that.payment_via_id
            this.editModel.amount_usd = that.amount_usd
            this.editModel.admin_confirmation = that.admin_confirmation
        },

        doShow(doc) {
            this.proof_doc = doc;
        },

        downloadInvoice(id) {
            let self = this;
            axios({
                url : '/api/files/invoice/paypal/' + id,
                method : 'GET',
                responseType : 'blob',
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');

                fileLink.href = fileURL;
                fileLink.setAttribute('download',
                    'STAL-' + id + '.pdf');
                document.body.appendChild(fileLink);

                fileLink.click();
            }).catch((error) => {
                swal.fire(
                    self.$t('message.wallet_transaction.alert_error'),
                    self.$t('message.wallet_transaction.alert_file_not_found'),
                    'error'
                )
            });
        },

        async submitUpdatePay() {
            let self = this;
            let loader = this.$loading.show();

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

            if (this.messageForms.action == 'success') {
                this.$refs.proof_edit.value = '';
                this.getWalletTransactionList();

                loader.hide();
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }
        },

        async submitPay() {
            let self = this;
            this.formData = new FormData();
            this.formData.append('payment_type', this.updateModel.payment_type);
            this.formData.append('amount_usd', this.updateModel.amount_usd);
            this.formData.append('user_id_buyer', this.updateModel.user_id_buyer);
            this.formData.append('mode', 'manual');

            // if (this.updateModel.payment_type !== 1) {
                this.formData.append('file', this.$refs.proof.files[0]);
            // }

            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddWallet', this.formData)
            this.isPopupLoading = false;

            if (this.messageForms.action == 'success') {
                // if (this.updateModel.payment_type === 1) {
                //     this.walletStep = 1;
                //     this.initPaypalButtons();
                // } else {
                    $("#modal-add-wallet").modal('hide');
                    this.updateModel = {
                        user_id_buyer : '',
                        payment_type : '',
                        amount_usd : '',
                    }

                    this.$refs.proof.value = '';

                    swal.fire(
                        self.$t('message.wallet_transaction.alert_success'),
                        self.$t('message.wallet_transaction.alert_successfully_added'),
                        'success'
                    )

                    this.getWalletTransactionList();
                // }
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }

            this.$root.$refs.AppHeader.liveGetWallet()
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },

        getTotalAmount() {
            let wallet_transaction = this.listWallet.data
            let total_price = [];
            let total = 0;

            wallet_transaction.forEach(function (item) {
                if (typeof item.amount_usd !== 'undefined') {
                    total_price.push(parseFloat(item.amount_usd))
                }
            })

            if (total_price.length > 0) {
                total = total_price.reduce(this.calcSum)
            }
            this.totalAmount = total.toFixed(2);
        },

        calcSum(total, num) {
            return total + num
        },
    },

}
</script>
