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
                        <h3 class="card-title text-primary">{{ $t('message.seller_billing.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.seller_billing.filter_search_id_backlink') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="filterModel.search"
                                           name=""
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.seller_billing.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.seller_billing.filter_status_billing') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.status_billing">
                                        <option value="">{{ $t('message.seller_billing.all') }}</option>
                                        <option value="Not Yet">{{ $t('message.seller_billing.not_yet') }}</option>
                                        <option value="Done">{{ $t('message.seller_billing.done') }}</option>
                                        <option value="Voided">{{ $t('message.seller_billing.voided') }}</option>
                                        <option value="Refunded">Refunded</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.seller_billing.filter_seller') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.seller_billing.all') }}</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.seller_billing.filter_date_completed') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_completed"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_completed"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.seller_billing.filter_date_payment') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_of_payment"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_of_payment"
                                            :linkedCalendars="true"
                                            opens="right"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.seller_billing.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.seller_billing.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.seller_billing.sb_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="d-inline pull-right">{{ $t('message.seller_billing.sb_amount') }} $ {{ totalAmount }}</h5>

                        <div class="row" v-if="user.isOurs != 1 && user.permission_list.includes('update-billing-seller-billing')">
                            <div class="col-md-2 my-3">

                                <div class="input-group">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle"
                                                :disabled="isDisabled"
                                                type="button"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            {{ $t('message.seller_billing.sb_selected_action') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" @click="doUpdatePayment" href="#">
                                                {{ $t('message.seller_billing.sb_pay') }}
                                            </a>

                                            <a class="dropdown-item" @click="updatePayment" href="#">
                                                {{ $t('message.seller_billing.sb_void') }}
                                            </a>

                                            <a class="dropdown-item" @click="doRefund" href="#">
                                                Refund
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <table id="tbl_seller_billing"
                               class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th v-if="user.isOurs != 1">{{ $t('message.seller_billing.sb_select')}} </th>
                                <th>{{ $t('message.seller_billing.sb_id_backlink') }}</th>
                                <th>{{ $t('message.seller_billing.filter_seller') }}</th>
                                <th>{{ $t('message.seller_billing.sb_seller_price') }}</th>
                                <th>{{ $t('message.seller_billing.filter_date_payment') }}</th>
                                <th>{{ $t('message.seller_billing.filter_date_completed') }}</th>
                                <th>{{ $t('message.seller_billing.filter_status_billing') }}</th>
                                <th>{{ $t('message.seller_billing.sb_status_payment') }}</th>
                                <th>{{ $t('message.seller_billing.sb_proof') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(seller, index) in listSellerBilling.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td v-if="user.isOurs != 1">
                                    <div class="btn-group">
                                        <label class="btn btn-default">
                                            <input type="checkbox"
                                                   :disabled="seller.proof_doc_path != null"
                                                   v-on:change="checkSelected"
                                                   :id="seller.id"
                                                   :value="seller"
                                                   v-model="checkIds">
                                        </label>
                                    </div>
                                </td>
                                <td>{{ seller.id }}</td>
                                <td>{{
                                        seller.publisher == null ? 'Record Deleted' : seller.publisher.user == null ? $t('message.seller_billing.sb_record_deleted') : seller.publisher.user.username
                                    }}
                                </td>
                                <td>{{
                                        seller.price == null || seller.price == '' ? $t('message.seller_billing.sb_record_deleted') : '$ ' + formatPrice(seller.price)
                                    }}
                                </td>
                                <td>
                                    {{ seller.date_billing == null || seller.date_billing === '' ? $t('message.seller_billing.sb_pending') : seller.date_billing }}
                                </td>
                                <td>{{ seller.live_date }}</td>
                                <td>{{
                                        seller.admin_confirmation == null ? seller.payment_status == 'Voided' ? 'Not Paid' : 'Not Yet' : 'Done'
                                    }}
                                </td>
                                <td>{{
                                        seller.admin_confirmation == null ? seller.payment_status == 'Voided' ? seller.payment_status : 'Not Paid' : 'Paid'
                                    }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            v-if="seller.proof_doc_path == null || !isFilePdf(seller.proof_doc_path)"
                                            @click="doShow(seller.proof_doc_path)"
                                            :disabled="seller.proof_doc_path == null"
                                            :title="$t('message.seller_billing.sb_view_proof')"
                                            data-target="#modal-view-docs"
                                            data-toggle="modal"
                                            class="btn btn-default">

                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>

                                        <button
                                            v-else
                                            :title="$t('message.seller_billing.sb_download_proof')"
                                            @click="downloadProof(wallet.id)"
                                            class="btn btn-default">

                                            <i class="fa fa-fw fa-download"></i>
                                        </button>

                                        <button
                                            v-if="user.permission_list.includes('upload-billing-seller-billing')"
                                            data-target="#modal-re-upload-doc"
                                            data-toggle="modal"
                                            class="btn btn-default px-3 ml-2"
                                            :title="$t('message.seller_billing.sb_re_upload_proof')"
                                            :disabled="seller.proof_doc_path == null"

                                            @click="doShowReupload(seller.proof_doc_path, seller.billing_id)">

                                            <i class="fas fa-file-upload"></i>
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

        <!-- Modal View Docs -->
        <div class="modal fade"
             id="modal-view-docs"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.seller_billing.pd_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid"
                                     :src="proof_doc"
                                     :alt="$t('message.seller_billing.pd_proof_billing')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.seller_billing.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Docs -->

        <!-- Modal Payment -->
        <div class="modal fade"
             id="modal-payment"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.seller_billing.p_title') }}</h5>

                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span
                            v-if="messageForms.message != '' && !isPopupLoading"
                            :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="step == 0">
                            <div class="col-md-12 mb-4">
                                <table class="table">
                                    <tr>
                                        <td style="border-top: 0px;">
                                            {{ $t('message.seller_billing.filter_seller') }}:
                                            <b>{{ info.seller }}</b>
                                        </td>
                                        <td style="border-top: 0px;">
                                            {{ $t('message.seller_billing.p_amount_to_pay') }}
                                            <b>$ {{ info.amount }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            {{ $t('message.seller_billing.p_payment_type') }}
                                            <b :class="{ 'text-danger' : info.payment_type == 'Not yet setup' }">
                                                {{ info.payment_type }}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            {{ $t('message.seller_billing.p_account') }}
                                            <b :class="{ 'text-danger' : info.account == 'Not yet setup' }">
                                                {{ info.account }}
                                            </b>

                                            <div class="row my-3">
                                                <div class="image-container rounded mx-auto d-block">
                                                    <img
                                                        :src="'/storage/' + info.img_path"
                                                        class="img-thumbnail"
                                                        alt="..."
                                                        style="width: 100%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            STALINKS ID
                                            <b>{{ info.ids }}</b>
                                        </td>
                                    </tr>
                                    <tr v-show="info.bank_name != null && info.bank_name != ''">
                                        <td colspan="2">
                                            Bank Name:
                                            <b>{{ info.bank_name }}</b>
                                        </td>
                                    </tr>
                                    <tr v-show="info.account_name != null && info.account_name != ''">
                                        <td colspan="2">
                                            Account Name:
                                            <b>{{ info.account_name }}</b>
                                        </td>
                                    </tr>
                                    <tr v-show="info.account_iban != null && info.account_iban != ''">
                                        <td colspan="2">
                                            Account IBAN:
                                            <b>{{ info.account_iban }}</b>
                                        </td>
                                    </tr>
                                    <tr v-show="info.swift_code != null && info.swift_code != ''">
                                        <td colspan="2">
                                            Swift Code:
                                            <b>{{ info.swift_code }}</b>
                                        </td>
                                    </tr>
                                    <tr v-show="info.beneficiary_add != null && info.beneficiary_add != ''">
                                        <td colspan="2">
                                            Beneficiary Address:
                                            <b>{{ info.beneficiary_add }}</b>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label>{{ $t('message.seller_billing.pd_title') }}</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        enctype="multipart/form-data"
                                        ref="proof"
                                        name="file">

                                    <small class="text-muted">
                                        {{ $t('message.seller_billing.p_note') }}
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

                        <div class="row" v-else-if="step == 1">
                            <div class="col-sm-12">
                                <div id="smart-button-container">
                                    <div style="text-align: center;">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="step == 0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.seller_billing.close') }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="isDisabledPay"

                            @click="doPay">
                            {{ $t('message.seller_billing.pay') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Payment -->

        <!-- Modal Re upload Doc -->
        <div
            class="modal fade"
            id="modal-re-upload-doc"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.seller_billing.rud_title') }}</h5>
                    </div>

                    <div class="modal-body">

                        <div class="card">
                            <div class="card-header">
                                <strong>{{ $t('message.seller_billing.rud_uploaded') }}</strong>
                            </div>

                            <div class="cad-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <img
                                            class="img-fluid"
                                            :src="proof_doc_re_upload"
                                            :alt="$t('message.seller_billing.pd_proof_billing')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <strong>{{ $t('message.seller_billing.rud_new') }}</strong>
                            </div>

                            <div class="card-body">
                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                        <input
                                            type="file"
                                            class="form-control"
                                            enctype="multipart/form-data"
                                            ref="proof_reupload"
                                            name="file">

                                        <small class="text-muted">
                                            {{ $t('message.seller_billing.p_note') }}
                                        </small> <br/>

                                        <span
                                            v-if="messageForms.errors.file"
                                            v-for="err in messageForms.errors.file"
                                            class="text-danger">
                                            {{ err }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.seller_billing.close') }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="isDisabledReupload"

                            @click="doReupload">

                            {{ $t('message.seller_billing.rud_re_upload') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Re upload Doc -->
    </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import _ from 'lodash';
import axios from "axios";
import {dateRange} from "../../../../mixins/dateRange";

export default {
    mixins: [dateRange],
    data() {
        return {
            checkIds : [],
            isDisabled : true,
            updateModel : {
                payment_type : ''
            },
            proof_doc : '',
            proof_doc_re_upload : '',
            isPopupLoading : false,
            filterModel : {
                search : this.$route.query.search || '',
                status_billing : this.$route.query.status_billing || '',
                seller : this.$route.query.seller || '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_of_payment : {
                    startDate : null,
                    endDate : null
                }
            },
            searchLoading : false,
            totalAmount : 0,
            info : {
                seller : '',
                payment_type : '',
                payment_type_id : '',
                account : '',
                amount : '',
                ids : '',
                payment_types: null,
                bank_name: '',
                account_name: '',
                account_iban: '',
                swift_code: '',
                beneficiary_add: '',
                img_path: '',
            },
            isDisabledPay : true,
            isDisabledReupload: false,
            isSearching : false,
            step : 0,

            // reupload

            reUploadModel: {
                billing_id: null,
            }
        }
    },

    async created() {
        this.getSellerBilling();
        this.getPaymentTypeList();
        this.getListSeller();
    },

    computed : {
        ...mapState({
            listSellerBilling : state => state.storeBillingSeller.listSellerBilling,
            messageForms : state => state.storeBillingSeller.messageForms,
            listPayment : state => state.storeBillingSeller.listPayment,
            listSeller : state => state.storePublisher.listSeller,
            sellerInfo : state => state.storeBillingSeller.sellerInfo,
            user: state => state.storeAuth.currentUser,
        }),
    },

    methods : {
        initPaypalButtons() {
            let vm = this;
            paypal.Buttons({
                style : {
                    shape : 'rect',
                    color : 'black',
                    layout : 'vertical',
                    label : 'pay',

                },

                createOrder : function (data, actions) {
                    return axios.post('/api/paypal/order/create', {
                        email : vm.info.account,
                        amount : vm.info.amount,
                        entity : 'seller'
                    })
                        .then(response => {
                            return response.data.result;
                        })
                        .then(data => {
                            return data.id;
                        });
                },

                onApprove : function (data, actions) {
                    return axios.post('/api/paypal/order/' +
                        data.orderID
                        + '/capture')
                        .then(response => {
                            this.doPay();
                        });

                    // return vm.$store.dispatch('captureOrder')
                    //     .then(response => {
                    //         $("#modal-add-wallet").modal('hide');
                    //         vm.updateModel = {
                    //             user_id_buyer : '',
                    //             payment_type : '',
                    //             amount_usd : '',
                    //         };
                    //
                    //         swal.fire(
                    //             'Success',
                    //             'Successfully Added',
                    //             'success'
                    //         );
                    //
                    //         vm.getWalletTransactionList();
                    //     });
                },

                onError : function (err) {
                    swal.fire(
                        vm.$t('message.seller_billing.alert_error'),
                        vm.$t('message.seller_billing.alert_payment_error'),
                        'error'
                    )
                }
            }).render('#paypal-button-container');
        },

        async getSellerBilling(params) {
            this.searchLoading = true;
            this.isSearching = true;
            await this.$store.dispatch('actionGetSellerBilling', params);
            this.searchLoading = false;
            this.isSearching = false;

            $('#tbl_seller_billing').DataTable({
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 2},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : true, targets : 7},
                    {
                        orderable : false,
                        targets : '_all'
                    }
                ],
            });

            this.getTotalAmount()
        },

        doRefund() {

        },

        async doUpdatePayment() {
            let self = this;
            await this.$store.dispatch('actionGetSellerInfo', {ids : this.checkIds});

            if (this.sellerInfo.success) {
                let data = this.sellerInfo.data[0]
                let account = 'Not yet setup';
                let total = 0;
                let _ids = '';

                this.info.seller = data.username;
                this.info.payment_types = data.user_payment_types;
                this.info.payment_type = data.payment_type.type;

                if (this.info.payment_types) {
                    this.isDisabledPay = false;

                    let paymentType = _.find(this.info.payment_types, {'payment_id' : data.id_payment_type} );

                    if (paymentType) {
                        account = paymentType.account;
                        this.info.img_path = paymentType.img_path;
                        this.info.bank_name = paymentType.bank_name == null ? '':JSON.parse(paymentType.bank_name)[data.id_payment_type];
                        this.info.account_name = paymentType.account_name == null ? '':JSON.parse(paymentType.account_name)[data.id_payment_type];
                        this.info.account_iban = paymentType.account_iban == null ? '':JSON.parse(paymentType.account_iban)[data.id_payment_type];
                        this.info.swift_code = paymentType.swift_code == null ? '':JSON.parse(paymentType.swift_code)[data.id_payment_type];
                        this.info.beneficiary_add = paymentType.beneficiary_add == null ? '':JSON.parse(paymentType.beneficiary_add)[data.id_payment_type];
                    }
                }

                for (var index in this.checkIds) {
                    // if( this.checkIds[index].publisher != null ){
                    //     if( this.checkIds[index].publisher.price != null ){
                    //         total += parseInt(this.checkIds[index].publisher.price);
                    //     }
                    // }

                    if (this.checkIds[index].price != null) {
                        total += parseInt(this.checkIds[index].price);

                        _ids += this.checkIds[index].id + ' ';
                    }


                }

                if (account == 'Not yet setup') {
                    this.isDisabledPay = true;
                }

                this.info.account = account;
                this.info.amount = total;
                this.info.ids = _ids;

                $("#modal-payment").modal('show');

            } else {
                swal.fire(
                    self.$t('message.seller_billing.alert_invalid'),
                    self.$t('message.seller_billing.alert_multiple_different_seller'),
                    'error'
                )
            }

            this.clearMessageform();
        },

        doShow(src) {
            this.proof_doc = src;
        },

        doShowReupload(src, billing_id) {
            this.proof_doc_re_upload = src;
            this.reUploadModel.billing_id = billing_id
        },

        async getListSeller(params) {
            await this.$store.dispatch('actionGetListSeller', params);
        },

        getTotalAmount() {
            let seller_billing = this.listSellerBilling.data
            let total_price = [];
            let total = 0;

            seller_billing.forEach(function (item, index) {
                if (typeof item.price !== 'undefined') {
                    total_price.push(parseFloat(item.price))
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

        formatPrice(price) {
            price = parseFloat(price).toFixed(0);
            return price;
        },

        doSearch() {

            $('#tbl_seller_billing').DataTable().destroy();

            // change the format of date
            this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)
            this.filterModel.date_of_payment = this.formatFilterDates(this.filterModel.date_of_payment)

            this.$router.push({
                query : this.filterModel,
            });

            this.getSellerBilling({
                params : {
                    search : this.filterModel.search,
                    status_billing : this.filterModel.status_billing,
                    seller : this.filterModel.seller,
                    date_completed : this.filterModel.date_completed,
                    date_of_payment : this.filterModel.date_of_payment
                }
            });
        },

        checkSelected() {
            this.isDisabled = true;
            if (this.checkIds.length > 0) {
                this.isDisabled = false;
            }
        },

        clearSearch() {
            $('#tbl_seller_billing').DataTable().destroy();

            this.filterModel = {
                search : '',
                status_billing : '',
                seller : '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_of_payment : {
                    startDate : null,
                    endDate : null
                }
            }

            this.getSellerBilling({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});

        },

        nextPage() {
            this.step = 1;

            setTimeout(this.initPaypalButtons, 300);
        },

        async doPay() {
            let self = this;
            $('#tbl_seller_billing').DataTable().destroy();
            let loader = this.$loading.show();
            this.isDisabledPay = true;

            let ids = this.checkIds
            this.formData = new FormData();
            this.formData.append('payment_type', this.updateModel.payment_type);
            this.formData.append('ids', JSON.stringify(ids));
            this.formData.append('payment_id', this.info.payment_type_id);
            this.formData.append('file', this.$refs.proof.files[0]);

            this.isPopupLoading = true;
            await this.$store.dispatch('actionPay', this.formData)
            this.isPopupLoading = false;

            if (this.messageForms.action == 'success') {
                $("#modal-payment").modal('hide')
                this.getSellerBilling();
                this.$refs.proof.value = '';
                this.updateModel.payment_type = '';

                this.checkIds = [];

                loader.hide();

                swal.fire(
                    self.$t('message.seller_billing.alert_success'),
                    self.$t('message.seller_billing.alert_paid_successfully'),
                    'success'
                )

            } else {
                loader.hide();

                await swal.fire(
                    self.messageForms.message,
                    '',
                    'error'
                )
            }

            this.isDisabledPay = false;
            loader.hide();

            this.$root.$refs.AppHeader.liveGetWallet()
        },

        doReupload () {
            let self = this;
            swal.fire({
                title : self.$t('message.seller_billing.alert_re_upload'),
                html : self.$t('message.seller_billing.alert_re_upload_note'),
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.seller_billing.yes_update'),
                cancelButtonText : self.$t('message.seller_billing.keep')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    this.submitReupload()
                }
            });
        },

        async submitReupload () {
            let self = this;
            let loader = this.$loading.show();

            $('#tbl_seller_billing').DataTable().destroy();

            this.isDisabledReupload = true;

            this.formData = new FormData();
            this.formData.append('billing_id', this.reUploadModel.billing_id);
            this.formData.append('file', this.$refs.proof_reupload.files[0]);

            await this.$store.dispatch('actionReupload', this.formData)

            if (this.messageForms.action === 'success') {
                $("#modal-re-upload-doc").modal('hide')

                await this.getSellerBilling();

                this.$refs.proof_reupload.value = '';
                this.reUploadModel.billing_id = null;
                this.isDisabledReupload = false;

                loader.hide();

                await swal.fire(
                    self.$t('message.seller_billing.alert_success'),
                    self.$t('message.seller_billing.alert_re_upload_success'),
                    'success'
                )
            } else {
                loader.hide();
                this.isDisabledReupload = false;

                await swal.fire(
                    self.messageForms.message,
                    '',
                    'error'
                )
            }
        },

        async getPaymentTypeList(params) {
            await this.$store.dispatch('actionGetListPayentType', params);
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },

        isFilePdf(path) {
            var arr = path.split('.');

            return arr[1] === 'pdf';
        },

        downloadProof(id) {
            axios({
                url : '/api/files/proof/paypal/seller/' +
                    id,
                method : 'GET',
                responseType : 'blob',
            }).then((response) => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');

                fileLink.href = fileURL;
                fileLink.setAttribute('download',
                    'STAL-SELLER-' + id + '.pdf');
                document.body.appendChild(fileLink);

                fileLink.click();
            });
        },

        updatePayment() {
            let self = this;
            let ids = this.checkIds;
            $('#tbl_seller_billing').DataTable().destroy();

            axios({
                url : '/api/seller-billing/update/multiple',
                method : 'PUT',
                data : {ids : ids}
            }).then((response) => {
                swal.fire(
                    self.$t('message.seller_billing.alert_success'),
                    self.$t('message.seller_billing.alert_payment_voided'),
                    'success'
                )

                this.getSellerBilling();
                this.checkIds = [];
            }).catch((error) => {
                swal.fire(
                    error.response.data.message,
                    '',
                    'error'
                )
            })
        }
    },
}
</script>
