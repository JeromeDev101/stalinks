<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.o_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_date_range') }}</label>

                            <div class="input-group">
                                <date-range-picker
                                    ref="order-picker"
                                    v-model="filterModel.orders.dateRange"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    :dateRange="filterModel.orders.dateRange"
                                    :ranges="dateRanges"
                                    :linkedCalendars="true"
                                    opens="right"
                                    style="width: 100%"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_team_in_charge') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.orderTeam" class="form-control">
                                    <option value="0">{{ $t('message.admin_dashboard.all') }}</option>
                                    <option v-for="user in listSellerTeam.data" v-if="user.id != 0" :value="user.id">
                                        {{ user.username }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ $t('message.admin_dashboard.action') }}</label>

                            <br>
                            <button class="btn btn-default col-md-6" @click="filterOrder">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearOrdersFilter">
                                {{ $t('message.admin_dashboard.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <h5>{{ $t('message.admin_dashboard.o_year') }}: {{ displayModel.orderYear }}</h5>
                        <h5>{{ $t('message.admin_dashboard.o_team_in_charge') }}: {{ teamInCharge }}</h5>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        :options="orderChartOptions"
                        :series="ordersData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import orders from "../../graph_settings/orders";
import axios from "axios";
import _ from "underscore";

export default {
    name : "OrdersGraph",

    props: [
        'dateRanges',
        'listSellerTeam'
    ],

    data() {
        return {
            filterModel: {
                orders: {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                },

                orderTeam: null
            },
            displayModel: {
                orderTeam: 0,
                orderYear: new Date().getFullYear()
            },
            ordersData: []
        };
    },

    mounted() {
        this.getOrdersData();
    },

    computed: {
        orderChartOptions() {
            return orders.ordersGraphSetting();
        },

        teamInCharge() {
            if (this.displayModel.orderTeam === 0) {
                return 'All';
            }

            return _.where(this.listSellerTeam.data, {
                id: this.displayModel.orderTeam
            })[0].username;
        },
    },

    methods: {
        getOrdersData(start = null, end = null,
                      teamInCharge = 0) {
            axios.get('/api/graphs/orders?&team_in_charge='+ teamInCharge +
                '&start_date=' + start + '&end_date=' + end)
                .then(response => {
                    let data = response.data;

                    this.ordersData =
                        orders.ordersGraphData(data);
                });
        },

        filterOrder() {
            this.displayModel.orderTeam =
                this.filterModel.orderTeam;

            if (this.filterModel.orders.dateRange.startDate !=
                null &&
                this.filterModel.orders.dateRange.endDate != null) {
                this.filterModel.orders.dateRange.startDate =
                    new
                    Date(this.filterModel.orders.dateRange.startDate).toJSON();
                this.filterModel.orders.dateRange.endDate =
                    new
                    Date(this.filterModel.orders.dateRange.endDate).toJSON();
            }

            this.getOrdersData(this.filterModel.orders.dateRange.startDate, this.filterModel.orders.dateRange.endDate, this.filterModel.orderTeam);
        },

        clearOrdersFilter() {
            this.filterModel.orders = {

                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.orderTeam = null;

            this.getOrdersData();
        },

    }
}
</script>

<style scoped>

</style>
