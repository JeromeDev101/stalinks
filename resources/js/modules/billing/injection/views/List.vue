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
                        <h3 class="card-title text-primary">{{ $t('message.writer_billing.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search ID Injection</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.search_injection"
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.writer_billing.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seller</label>
                                    <select name="" class="form-control" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.writer_billing.all') }}</option>
                                        <option v-for="option in listSeller" v-bind:value="option.id" :key="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select name="" class="form-control" v-model="filterModel.status">
                                        <option value="">{{ $t('message.writer_billing.all') }}</option>
                                        <option value="not paid">{{ $t('message.writer_billing.wb_not_paid')}}</option>
                                        <option value="paid">{{ $t('message.writer_billing.wb_paid')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.writer_billing.filter_date_completed') }}</label>
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
                                    <label>{{ $t('message.writer_billing.filter_date_created') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_created"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_created"
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
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.writer_billing.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.writer_billing.search') }}
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
                        <h3 class="card-title text-primary">Injection Billing</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3" v-if="user.isOurs != 1 && user.permission_list.includes('update-billing-writer-billing')">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle"
                                        :disabled="isDisabled"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    {{ $t('message.writer_billing.wb_selected_action') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" @click="doUpdatePay" href="#">
                                        {{ $t('message.writer_billing.wb_pay') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl_injection_billing"
                                   class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th v-if="user.isOurs != 1">{{ $t('message.writer_billing.wb_select') }}</th>
                                    <th>{{ $t('message.writer_billing.filter_date_completed') }}</th>
                                    <th>{{ $t('message.writer_billing.filter_date_created') }}</th>
                                    <th>ID Injection</th>
                                    <th>Seller</th>
                                    <th>Buyer Price</th>
                                    <th>Seller Price</th>
                                    <th>Commission</th>
                                    <th>Payment Status</th>
                                    <th>Proof Documents</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(injection, index) in listInjectionBilling.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td v-if="user.isOurs != 1">
                                            <div class="btn-group">
                                                <button class="btn btn-default">
                                                    <input type="checkbox"
                                                           :disabled="injection.payment_status == 'paid'"
                                                           v-model="checkIds"
                                                           v-on:change="checkSelected"
                                                           :id="injection.publisher_id"
                                                           :value="injection">
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ injection.date_process }}</td>
                                        <td>{{ injection.date_requested }}</td>
                                        <td>{{ injection.id }}</td>
                                        <td>{{ injection.publisher.user.username }}</td>
                                        <td>$ {{ injection.buyer_injection_price }}</td>
                                        <td>$ {{ injection.injection_price }}</td>
                                        <td>$ {{ parseInt(injection.buyer_injection_price) - parseInt(injection.injection_price) }}</td>
                                        <td>{{ injection.payment_status != 'paid' ? 'Not Paid':'Paid' }}</td>
                                        <td>
                                            <div v-if="injection.payment_status == 'paid'">
                                                <button
                                                    v-if="injection.billing.proof_doc_path == null || !isFilePdf(injection.billing.proof_doc_path)"
                                                    :title="$t('message.writer_billing.wb_view_proof_billing')"
                                                    :disabled="injection.billing.proof_doc_path == null"
                                                    data-target="#modal-view-docs-inj"
                                                    data-toggle="modal"
                                                    class="btn btn-default"

                                                    @click="doShow(injection.billing.proof_doc_path)">
                                                    <i class="fa fa-fw fa-eye"></i>
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
        </div>

        <!-- Modal Proof Doc -->
        <div class="modal fade"
             id="modal-view-docs-inj"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.writer_billing.pd_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid" :src="proof_doc" :atl="$t('message.writer_billing.pd_proof_billing')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.writer_billing.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Proof Doc -->

        <!-- Modal Payment -->
        <div class="modal fade"
             id="modal-payment-inj"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.seller_billing.p_title') }}</h5>

                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

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

                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            INJECTION ID: 
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
                                <div :class="{'form-group': true}">
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

    </div>
</template>

<script>

import {mapState} from 'vuex';
import _ from "lodash";
import axios from "axios";
import {dateRange} from "../../../../mixins/dateRange";

export default {
    mixins: [dateRange],
    data() {
        return {
            isDisabled: true,
            filterModel : {
                seller : this.$route.query.seller || '',
                search_injection : this.$route.query.search_injection || '',
                status : this.$route.query.status || '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_created : {
                    startDate : null,
                    endDate : null
                }
            },
            step : 0,
            isPopupLoading : false,
            isSearching : false,
            searchLoading : false,
            isDisabledPay : true,
            listInjectionBilling: [],
            listSeller: [],
            checkIds : [],
            proof_doc : '',
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
        }
    },

    async created() {
        this.getInjectionBillingList();
        this.getListSellerInjection();
    },

    computed : {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        }),
    },

    methods : {
        getInjectionBillingList(params){
            axios.get('/api/injection-billing', params)
                .then( (res) => {
                    this.listInjectionBilling = res;
                })
        },

        getListSellerInjection() {
            $("#tbl_injection_billing").DataTable().destroy();

            axios.get('/api/injection-billing-seller')
                .then( (res) => {
                    this.listSeller = res.data
                })

            $("#tbl_injection_billing").DataTable({
                paging : false,
                searching : false,
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 2},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : false, targets : '_all'}
                ],
            });
        },

        doUpdatePay() {
            // let self = this;

            axios.get('api/injection-billing-seller-info', {
                    params: {
                        ids : this.checkIds
                    }
                })
                .then((res) => {
                    let response = res.data

                    if(response.success) {

                        let data = response.data[0]
                        let account = 'Not yet setup';
                        let total = 0;
                        let _ids = '';

                        this.info.seller = data.username;
                        this.info.payment_types = data.user_payment_types;
                        this.info.payment_type = data.payment_type.type;
                        this.info.payment_type_id = data.payment_type.id;

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

                            if (this.checkIds[index].injection_price != null) {
                                total += parseInt(this.checkIds[index].injection_price);

                                _ids += this.checkIds[index].id + ' ';
                            }
                        }

                        if (account == 'Not yet setup') {
                            this.isDisabledPay = true;
                        }

                        this.info.account = account;
                        this.info.amount = total;
                        this.info.ids = _ids;

                        $("#modal-payment-inj").modal('show');

                    } else {
                        swal.fire(
                            self.$t('message.seller_billing.alert_invalid'),
                            self.$t('message.seller_billing.alert_multiple_different_seller'),
                            'error'
                        )
                    }
                })

        },

        doPay() {
            let ids = this.checkIds
            let _file = this.$refs.proof.files[0];

            if(_file?.name) {

                this.formData = new FormData();
                this.formData.append('ids', JSON.stringify(ids));
                this.formData.append('payment_id', this.info.payment_type_id);
                this.formData.append('file', _file);

                axios.post('/api/pay-seller-billing-injection',this.formData)
                .then((res) => {
                    console.log(res)
                    $("#modal-payment-inj").modal('hide');

                    this.checkIds = [];

                    this.getInjectionBillingList();

                    swal.fire(
                        self.$t('message.seller_billing.alert_success'),
                        self.$t('message.seller_billing.alert_paid_successfully'),
                        'success'
                    )
                })
            }
        },

        doShow(src) {
            this.proof_doc = src;
        },

        isFilePdf(path) {
            var arr = path.split('.');

            return arr[1] === 'pdf';
        },

        doSearch() {
            // change the format of date
            this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)
            this.filterModel.date_created = this.formatFilterDates(this.filterModel.date_created)

            this.$router.push({
                query : this.filterModel,
            });

            this.getInjectionBillingList({
                params: {
                    search_injection : this.filterModel.search_injection,
                    seller : this.filterModel.writer,
                    date_completed : this.filterModel.date_completed,
                    date_created : this.filterModel.date_created,
                    status : this.filterModel.status,
                }
            })
        },

        checkSelected() {
            this.isDisabled = true;
            if (this.checkIds.length > 0) {
                this.isDisabled = false;
            }
        },

        clearSearch() {
            this.filterModel = {
                seller : '',
                search_injection : '',
                status : '',
                date_completed : {
                    startDate : null,
                    endDate : null
                },
                date_created : {
                    startDate : null,
                    endDate : null
                }
            }

            this.$router.replace({'query' : null});

            this.getInjectionBillingList({
                params: this.filterModel
            })
        },

        doUpdate() {

        }
    }
}

</script>
