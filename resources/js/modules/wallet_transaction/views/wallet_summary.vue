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
                        <h3 class="card-title text-primary">{{ $t('message.wallet_summary.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.wallet_summary.filter_buyer') }}</label>
                                    <v-select
                                        v-model="filterModel.buyer"
                                        multiple
                                        label="username"
                                        class="style-chooser"
                                        :searchable="true"
                                        :reduce="buyer => buyer.id"
                                        :options="summaryData"
                                        :placeholder="$t('message.url_prospect.all')"/>
                                </div>
                            </div>

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
                                            opens="right"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

<!--                            <div class="col-md-3">-->
<!--                                <div class="form-group">-->
<!--                                    <label>{{ $t('message.wallet_summary.filter_month') }}</label>-->
<!--                                    <select class="form-control" v-model="filterModel.month">-->
<!--                                        <option value="">{{ $t('message.wallet_summary.all') }}</option>-->
<!--                                        <option v-for="month in months" v-bind:value="month.value">-->
<!--                                            {{ month.label }}-->
<!--                                        </option>-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="col-md-3">-->
<!--                                <div class="form-group">-->
<!--                                    <label>{{ $t('message.wallet_summary.filter_year') }}</label>-->
<!--                                    <date-picker-->
<!--                                        v-model="dateTemp"-->
<!--                                        format="yyyy"-->
<!--                                        minimum-view="year"-->
<!--                                        maximum-view="year"-->
<!--                                        :placeholder="$t('message.wallet_summary.all')"-->
<!--                                        clear-button-icon="fas fa-times"-->
<!--                                        :typeable=true-->
<!--                                        :clear-button=true-->
<!--                                        :full-month-name=true-->
<!--                                        :bootstrap-styling=true-->

<!--                                        @input="formatDate">-->

<!--                                    </date-picker>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">
                                    {{ $t('message.wallet_summary.clear') }}
                                </button>
                                <button class="btn btn-default" @click="getWalletSummary()">
                                    {{ $t('message.wallet_summary.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.wallet_summary.ws_title') }}</h3>
                    </div>

                    <div class="d-flex justify-content-center mx-3 mt-3">
                        <div class="col">
                            <h6>Deposit: $ {{ total_deposit.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Orders: $ {{ total_orders.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Orders Cancelled: $ {{ total_orders_cancelled.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Valid Orders: $ {{ total_valid_orders.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Purchase: $ {{ total_purchase.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Refund Orders: $ {{ total_refund_orders.toFixed(2) }}</h6>
                        </div>

                        <div class="col">
                            <h6>Wallet: $ {{ total_wallet.toFixed(2) }}</h6>
                        </div>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl_wallet_transaction" class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>ID Buyer</th>
                                    <th>{{ $t('message.wallet_summary.filter_buyer') }}</th>
                                    <th>{{ $t('message.wallet_summary.ws_deposit') }}</th>
                                    <th>{{ $t('message.wallet_summary.ws_orders') }} <span class="fa fa-exclamation-circle text-primary" title="All Orders - (Orders Cancelled + Valid Orders + Refund Orders)"></span></th>
                                    <th>{{ $t('message.wallet_summary.ws_orders_cancelled') }}</th>
                                    <th>{{ $t('message.wallet_summary.ws_valid_orders') }} <span class="fa fa-exclamation-circle text-primary" title="Valid Orders are Live, Processing, Content In Writing/Done/Sent, Live in Process, Issue and Pending"></span></th>
                                    <th>{{ $t('message.wallet_summary.ws_credit_left') }} <span class="fa fa-exclamation-circle text-primary" title="Credit Left - (Deposit - Valid Orders + Refund Orders)"></span></th>
                                    <th>{{ $t('message.wallet_summary.ws_purchase') }} <span class="fa fa-exclamation-circle text-primary" title="Purchase is only LIVE status"></span></th>
                                    <th>Refund Orders</th>
                                    <th>Refund Request</th>
                                    <th>{{ $t('message.wallet_summary.ws_wallet') }} <span class="fa fa-exclamation-circle text-primary" title="Wallet - (Deposit - Purchase + Refund Orders)"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(summary, index) in summaryData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ summary.id }}</td>
                                    <td>
                                        {{ summary.username == null ? summary.name : summary.username }}
                                        <span v-if="summary.status == 'inactive'" class="badge badge-danger">Inactive</span>
                                    </td>
                                    <td>{{ summary.deposit == null ? 0 : '$ ' + summary.deposit  }}</td>
                                    <td>{{ '$ ' + (summary.orders).toFixed(0) }}</td>
                                    <td>{{ '$ ' + (summary.order_cancel).toFixed(0) }}</td>
                                    <td>{{ '$ ' + (summary.valid_orders).toFixed(0) }}</td>
                                    <td>{{ '$ ' + (summary.credit_left).toFixed(0) }}</td>
                                    <td>{{ '$ ' + (summary.order_live).toFixed(0) }}</td>
                                    <td>{{ '$ ' + (summary.order_refund).toFixed(0) }}</td>
                                    <td>{{ summary.refund_request == null ? '$ 0' : '$ ' + summary.refund_request }}</td>
                                    <td>{{ '$ ' + (summary.wallet).toFixed(0) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import {dateRange} from "../../../mixins/dateRange";

    export default {
        mixins: [dateRange],
        data() {
            return {
                summaryData: [],
                months: [
                    {
                        value: 1,
                        label: 'January'
                    },
                    {
                        value: 2,
                        label: 'February'
                    },
                    {
                        value: 3,
                        label: 'March'
                    },
                    {
                        value: 4,
                        label: 'April'
                    },
                    {
                        value: 5,
                        label: 'May'
                    },
                    {
                        value: 6,
                        label: 'June'
                    },
                    {
                        value: 7,
                        label: 'July'
                    },
                    {
                        value: 8,
                        label: 'August'
                    },
                    {
                        value: 9,
                        label: 'September'
                    },
                    {
                        value: 10,
                        label: 'October'
                    },
                    {
                        value: 11,
                        label: 'November'
                    },
                    {
                        value: 12,
                        label: 'December'
                    },
                ],
                dateTemp: '',
                total_deposit: 0,
                total_orders: 0,
                total_orders_cancelled: 0,
                total_valid_orders: 0,
                total_purchase: 0,
                total_refund_orders: 0,
                total_wallet: 0,
                filterModel: {
                    buyer: this.$route.query.buyer || '',
                    month: this.$route.query.month || '',
                    year: this.$route.query.year || '',
                    date : {
                        startDate : null,
                        endDate : null
                    },
                },
            }
        },

        async created() {
            this.getWalletSummary();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
            }),
        },

        methods: {
            getWalletSummary() {
                // change the format of date
                this.filterModel.date = this.formatFilterDates(this.filterModel.date)

                if (this.filterModel.month && !this.filterModel.year) {
                    this.dateTemp = new Date();
                    this.formatDate()
                }

                let loader = this.$loading.show();

                axios.get('/api/wallet-summary',{
                    params: this.filterModel
                }).then((res) => {
                    this.summaryData = res.data.data
                    this.total_deposit = res.data.total_deposit
                    this.total_orders = res.data.total_orders
                    this.total_orders_cancelled = res.data.total_orders_cancelled
                    this.total_valid_orders = res.data.total_valid_orders
                    this.total_purchase = res.data.total_purchase
                    this.total_refund_orders = res.data.total_refund_orders
                    this.total_wallet = res.data.total_wallet
                    loader.hide();
                })
            },

            clearSearch() {
                this.filterModel = {
                    buyer: '',
                    year: '',
                    month: '',
                    date : {
                        startDate : null,
                        endDate : null
                    },
                }

                this.dateTemp = '';

                this.$router.replace({'query': null});
                this.getWalletSummary();
            },

            formatDate() {
                this.filterModel.year = new Date(this.dateTemp).getFullYear();
            }
        },

    }
</script>
