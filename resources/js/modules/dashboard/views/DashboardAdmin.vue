<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard Admin</h1>
            <hr>
            <br>
        </section>

<!--        ORDERS DATA GRAPH-->
        <div class="col-lg-12" >
            <div class="box box-primary" style="padding-bottom:0.5em;">
                <div class="box-header">
                    <h3
                        class="box-title text-primary">Orders
                    </h3>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Date
                                                        Range
                                </label>
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
                                <label
                                    style="color: #333">Team In-Charge
                                </label>
                                <div class="input-group">
                                    <select name=""
                                            class="form-control"
                                            id=""
                                            v-model="filterModel.orderTeam">
                                        <option
                                            value="0">All
                                        </option>
                                        <option
                                            :value="user.id"
                                            v-for="user
                                            in
                                            listSellerTeam.data" v-if="user.id != 0">{{ user.username }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Action</label>
                                <br>
                                <button
                                    class="btn btn-default col-md-6"
                                    @click="filterOrder">
                                    Filter</button>
                                <button
                                    class="btn btn-default" @click="clearOrdersFilter">Clear</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <h5>Year: {{
                                displayModel.orderYear
                                }}</h5>
                            <h5>Team In-Charge: {{
                                teamInCharge
                                }}</h5>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart type="bar" height="350"
                               :options="orderChartOptions"
                               :series="ordersData"></apexchart>
                </div>
            </div>
        </div>
<!--        ORDERS DATA GRAPH END-->

<!--        SELLER VALID GRAPH-->
        <div class="col-lg-12">
            <div class="box box-primary" style="padding-bottom:0.5em;">
                <div class="box-header">
                    <h3
                        class="box-title text-primary">Total
                                                       Seller
                                                       vs
                                                       Valid
                                                       Sellers
                    </h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Scope
                                </label>
                                <div class="input-group">
                                    <select name=""
                                            class="form-control"
                                            v-model="filterModel.sellerValid.scope"
                                    >
                                        <option
                                            value="global">Global</option>
                                        <option
                                            value="team">
                                            Team
                                            In-Charge</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Date
                                                        Range
                                </label>
                                <div class="input-group">
                                    <date-range-picker
                                        ref="picker"
                                        v-model="filterModel.sellerValid.dateRange"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        :dateRange="filterModel.sellerValid.dateRange"
                                        :ranges="dateRanges"
                                        :linkedCalendars="true"
                                        opens="right"
                                        style="width: 100%"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2"
                             v-if="this.filterModel.sellerValid.scope == 'global'">
                            <div class="form-group">
                                <label
                                    style="color: #333">Team In-Charge
                                </label>
                                <div class="input-group">
                                    <select name=""
                                            class="form-control"
                                            id=""
                                            v-model="filterModel.sellerValid.team_in_charge">
                                        <option
                                            value="0">All
                                        </option>
                                        <option
                                            :value="user.id"
                                            v-for="user
                                            in
                                            listSellerTeam.data" v-if="user.id != 0">{{ user.username }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Action</label>
                                <br>
                                <button
                                    class="btn btn-default col-md-6"
                                    @click="filterSellerValid">
                                    Filter</button>
                                <button
                                    class="btn btn-default" @click="clearSellerValidFilter">Clear</button>
                            </div>
                        </div>
                    </div>

                    <div class="small">
                        <apexchart type="bar" height="350"
                                   :options="sellerValidChartOptions"
                                   :series="sellerValidData"></apexchart>
                    </div>
                </div>
            </div>
        </div>
<!--        SELLER VALID GRAPH END-->

<!--        URL VALID GRAPH-->
        <div class="col-lg-12">
            <div class="box box-primary" style="padding-bottom:0.5em;">
                <div class="box-header">
                    <h3
                        class="box-title text-primary">URLs Validation
                    </h3>
                </div>

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Date
                                                        Range
                                </label>
                                <div class="input-group">
                                    <date-range-picker
                                        ref="picker"
                                        v-model="filterModel.urlValid.dateRange"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        :dateRange="filterModel.urlValid.dateRange"
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
                                <label for="">Action</label>
                                <br>
                                <button
                                    class="btn btn-default col-md-6"
                                    @click="filterUrlValid">
                                    Filter</button>
                                <button
                                    class="btn btn-default" @click="clearUrlValidFilter">Clear</button>
                            </div>
                        </div>
                    </div>

                    <div class="small">
                        <apexchart type="bar" height="350"
                                   :options="urlValidChartOptions"
                                   :series="urlValidData"></apexchart>
                    </div>
                </div>
            </div>
        </div>
<!--        URL VALID GRAPH END-->

<!--        URL VALID PRICE GRAPH-->
        <div class="col-lg-12">
            <div class="box box-primary" style="padding-bottom:0.5em;">
                <div class="box-header">
                    <h3
                        class="box-title text-primary">URL Valid Price
                    </h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Scope
                                </label>
                                <div class="input-group">
                                    <select name=""
                                            class="form-control"
                                            v-model="filterModel.urlValidPrice.scope"
                                    >
                                        <option
                                            value="global">Global</option>
                                        <option
                                            value="team">
                                            Team
                                            In-Charge</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label
                                    style="color: #333">Date
                                                        Range
                                </label>
                                <div class="input-group">
                                    <date-range-picker
                                        ref="picker"
                                        v-model="filterModel.urlValidPrice.dateRange"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        :dateRange="filterModel.urlValidPrice.dateRange"
                                        :ranges="dateRanges"
                                        :linkedCalendars="true"
                                        opens="right"
                                        style="width: 100%"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2"
                             v-if="this.filterModel.urlValidPrice.scope == 'global'">
                            <div class="form-group">
                                <label
                                    style="color: #333">Team In-Charge
                                </label>
                                <div class="input-group">
                                    <select name=""
                                            class="form-control"
                                            id=""
                                            v-model="filterModel.urlValidPrice.team_in_charge">
                                        <option
                                            value="0">All
                                        </option>
                                        <option
                                            :value="user.id"
                                            v-for="user
                                            in
                                            listSellerTeam.data" v-if="user.id != 0">{{ user.username }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Action</label>
                                <br>
                                <button
                                    class="btn btn-default col-md-6"
                                    @click="filterUrlValidPrice">
                                    Filter</button>
                                <button
                                    class="btn btn-default" @click="clearUrlValidPriceFilter">Clear</button>
                            </div>
                        </div>
                    </div>

                    <div class="small">
                        <apexchart type="bar" height="350"
                                   :options="urlValidPriceChartOptions"
                                   :series="urlValidPriceData"></apexchart>
                    </div>
                </div>
            </div>
        </div>
<!--        URL VALID PRICE GRAPH END-->

        <div class="col-lg-12" >
            <div class="box box-primary" style="padding-bottom:0.5em;">
                <div class="box-header">
                    <h3 class="box-title text-primary">URL Statistics</h3>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <!-- <label for="">Date</label> -->
                                <div class="input-group">
                                    <select name="" class="form-control" v-model="filterModel.date_type" @change="changeSelection">
                                        <option value="Monthly">Monthly</option>
                                        <option value="Range Date">Range Date</option>
                                    </select>
                                    <select class="form-control" v-model="filterModel.monthly" v-show="isMonthly">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                    <input type="date" class="form-control" v-model="filterModel.date1" v-show="isRangeDate">
                                    <input type="date" class="form-control" v-model="filterModel.date2" v-show="isRangeDate">

                                    <button class="btn btn-default" @click="fillData('with')">Filter</button>
                                    <button class="btn btn-default">Clear</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="small">
                        <line-chart :chart-data="datacollection" :options="options" :styles="styles"></line-chart>
                    </div>
                    <hr>
                    <h3 class="box-title text-primary">Seller Sites Statistics</h3>

                    <div class="small">
                        <line-chart :chart-data="datacollection2" :options="options" :styles="styles"></line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import LineChart from '@/components/chart/Line.js'
import axios from 'axios';
import orders from '../../../graph_settings/orders';
import seller_valid
    from "../../../graph_settings/seller_valid";
import url_valid from "../../../graph_settings/url_valid";
import url_valid_price
    from "../../../graph_settings/url_valid_price";
import _ from 'underscore';

export default {

    name: 'Dashboard',
    components: {
        LineChart
    },
    data() {
        return {
            isRangeDate: false,
            isMonthly: true,
            filterModel: {
                date_type: 'Monthly',
                monthly: '',
                date1: '',
                date2: '',
                orderTeam: 0,
                sellerValid : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    scope: 'global',
                    team_in_charge: 0
                },
                orders: {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                },
                urlValid : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                },
                urlValidPrice : {
                    scope: 'global',
                    team_in_charge: 0,
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                }
            },
            displayModel: {
                orderTeam: 0,
                orderYear: new Date().getFullYear()
            },
            datacollection: {},
            datacollection2: {},
            ordersData : [],
            sellerValidData: [],
            urlValidData : [],
            urlValidPriceData : [],
            options: {
                responsive: true,
                maintainAspectRatio: false,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				}
            },

            styles: {
                height: '500px',
            },
        };
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listSellerTeam: state => state.storeExtDomain.listSellerTeam,
        }),

        orderChartOptions() {
            return orders.ordersGraphSetting();
        },

        sellerValidChartOptions() {
            return seller_valid.sellerValidGraphSetting();
        },

        urlValidChartOptions() {
            return url_valid.urlValidGraphSetting();
        },

        urlValidPriceChartOptions() {
            return url_valid_price.urlValidPriceGraphSetting();
        },

        teamInCharge() {
            if (this.displayModel.orderTeam == 0) {
                return 'All';
            }

            return _.where(this.listSellerTeam.data, {
                id: this.displayModel.orderTeam
            })[0].username;
        },

        dateRanges() {
            let today = new Date();

            let yesterday = new Date();
            yesterday.setDate(today.getDate() - 1);
            yesterday.setHours(0, 0, 0, 0);

            return {
                'Today' : [today, today],
                'Yesterday' : [yesterday, yesterday],
                'This month' : [
                    new Date(today.getFullYear(),
                        today.getMonth(), 1),
                    new Date(today.getFullYear(),
                        today.getMonth()+1, 0)
                ],
                'Last month' : [
                    new Date(today.getFullYear(), today.getMonth() - 1, 1),
                    new Date(today.getFullYear(), today.getMonth(), 0)
                ],
                'Last 6 months' : [
                    new Date(today.getFullYear(),
                        today.getMonth() - 6, 1),
                    new Date(today.getFullYear(), today.getMonth(), 0)
                ],
                'This year' : [new Date(today.getFullYear(), 0, 1), new Date(today.getFullYear(), 11, 31)],
            }
        }
    },

    async created() {
        //
    },

    mounted() {
        this.fillData('without')
        this.getOrdersData();
        this.getSellerValidData();
        this.getUrlValidData();
        this.getUrlValidPriceData();
        this.getListSellerTeam();
    },

    methods: {
        clearUrlValidPriceFilter() {
            this.filterModel.urlValidPrice = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.urlValidPrice.scope = 'global';

            this.getUrlValidPriceData();
        },

        filterUrlValidPrice() {
            if
            (this.filterModel.urlValidPrice.dateRange.startDate !=
                null &&
                this.filterModel.urlValidPrice.dateRange.endDate != null) {
                this.filterModel.urlValidPrice.dateRange.startDate =
                    new
                    Date(this.filterModel.urlValidPrice.dateRange.startDate).toJSON();
                this.filterModel.urlValidPrice.dateRange.endDate =
                    new
                    Date(this.filterModel.urlValidPrice.dateRange.endDate).toJSON();
            }

            this.getUrlValidPriceData(this.filterModel.urlValidPrice.dateRange.startDate, this.filterModel.urlValidPrice.dateRange.endDate);
        },

        getUrlValidPriceData(start = null, end = null) {
            axios.get('/api/graphs/url-valid-price?start_date=' +
                start + '&end_date=' + end + '&scope=' +
                this.filterModel.urlValidPrice.scope +
                '&team_in_charge=' +
                this.filterModel.urlValidPrice.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.urlValidPriceData =
                        url_valid_price.urlValidPriceGraphData(data);
                });
        },

        clearUrlValidFilter() {
            this.filterModel.urlValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.getUrlValidData();
        },

        filterUrlValid() {
            if (this.filterModel.urlValid.dateRange.startDate !=
                null &&
                this.filterModel.urlValid.dateRange.endDate != null) {
                this.filterModel.urlValid.dateRange.startDate =
                    new
                    Date(this.filterModel.urlValid.dateRange.startDate).toJSON();
                this.filterModel.urlValid.dateRange.endDate =
                    new
                    Date(this.filterModel.urlValid.dateRange.endDate).toJSON();
            }

            this.getUrlValidData(this.filterModel.urlValid.dateRange.startDate, this.filterModel.urlValid.dateRange.endDate);
        },

        getUrlValidData(start = null, end = null) {
            axios.get('/api/graphs/url-valid?start_date=' +
                start + '&end_date=' + end)
                .then(response => {
                    let data = response.data;

                    this.urlValidData =
                        url_valid.urlValidGraphData(data);
                });
        },

        clearSellerValidFilter() {
            this.filterModel.sellerValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.sellerValid.scope = 'global';
            this.filterModel.sellerValid.team_in_charge =
                '0';

            this.getSellerValidData();
        },

        clearOrdersFilter() {
            this.filterModel.orders = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.getOrdersData();
        },

        filterSellerValid() {
            if (this.filterModel.sellerValid.dateRange.startDate != null && this.filterModel.sellerValid.dateRange.endDate != null) {
                this.filterModel.sellerValid.dateRange.startDate =
                    new
                    Date(this.filterModel.sellerValid.dateRange.startDate).toJSON();
                this.filterModel.sellerValid.dateRange.endDate =
                    new
                    Date(this.filterModel.sellerValid.dateRange.endDate).toJSON();
            }

            this.getSellerValidData(this.filterModel.sellerValid.dateRange.startDate, this.filterModel.sellerValid.dateRange.endDate);
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

        async getListSellerTeam(params) {
            await this.$store.dispatch('actionGetListSellerTeam', params);
        },

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

        getSellerValidData(start = null, end = null) {
            axios.get('/api/graphs/seller-valid?start_date=' + start + '&end_date=' + end + '&scope=' + this.filterModel.sellerValid.scope+'&team_in_charge='+this.filterModel.sellerValid.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.sellerValidData =
                        seller_valid.sellerValidGraphData(data);
                });
        },

        changeSelection() {
            let date_type = this.filterModel.date_type

            if( date_type != 'Monthly' ) {
                this.isRangeDate = true;
                this.isMonthly = false;
            } else {
                this.isRangeDate = false;
                this.isMonthly = true;
            }
        },

        fillData(isFilter) {
            axios.get('/api/dashboard-admin',{
                params: {
                    date_type: this.filterModel.date_type,
                    monthly: this.filterModel.monthly,
                    date1: this.filterModel.date1,
                    date2: this.filterModel.date2,
                    is_filter: isFilter == 'with' ? true: false,
                }
            })
            .then((res) => {
                this.datacollection = res.data.url_statistics;
                this.datacollection2 = res.data.seller_statistics;
            })
        },
    }
}
</script>
