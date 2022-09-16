<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $t('message.admin_dashboard.ad_title') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <orders-graph
                :list-seller-team="listSellerTeam"
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isQc">

            </orders-graph>

            <seller-valid-graph
                :list-seller-team="listSellerTeam"
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isQc || isCs">

            </seller-valid-graph>

            <url-valid-graph
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isQc">

            </url-valid-graph>

            <url-valid-price-graph
                :list-seller-team="listSellerTeam"
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isCs">

            </url-valid-price-graph>

            <url-seller-statistics-graph
                :list-seller-team="listSellerTeam"
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isCs">

            </url-seller-statistics-graph>

            <prospect-qualified-vs-registered-graph
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isCs">

            </prospect-qualified-vs-registered-graph>

            <buyer-valid-graph
                :list-team-in-charge="listIncharge"
                :date-ranges="generateDefaultDateRange()"
                v-if="user.isAdmin || isQc || isCs">

            </buyer-valid-graph>

            <writer-valid-graph
                :list-team-in-charge="listIncharge"
                :date-ranges="dateRanges"
                v-if="user.isAdmin || isQc || isCs">

            </writer-valid-graph>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import LineChart from '@/components/chart/Line.js'
import axios from 'axios';
import OrdersGraph from "../../../components/graphs/OrdersGraph";
import SellerValidGraph from "../../../components/graphs/SellerValidGraph";
import UrlValidGraph from "../../../components/graphs/UrlValidGraph";
import UrlValidPriceGraph from "../../../components/graphs/UrlValidPriceGraph";
import UrlSellerStatisticsGraph from "../../../components/graphs/UrlSellerStatisticsGraph";
import ProspectQualifiedVsRegisteredGraph from "../../../components/graphs/ProspectQualifiedVsRegisteredGraph";
import BuyerValidGraph from "../../../components/graphs/BuyerValidGraph";
import WriterValidGraph from "../../../components/graphs/WriterValidGraph";
import {dateRange} from "../../../mixins/dateRange";

export default {

    name : 'Dashboard',
    mixins: [dateRange],
    components : {
        LineChart,
        OrdersGraph,
        SellerValidGraph,
        UrlValidGraph,
        UrlValidPriceGraph,
        UrlSellerStatisticsGraph,
        ProspectQualifiedVsRegisteredGraph,
        BuyerValidGraph,
        WriterValidGraph
    },
    data() {
        return {
            isRangeDate : false,
            isMonthly : true,
            filterModel : {
                date_type : 'Monthly',
                monthly : '',
                date1 : '',
                date2 : '',
            },
            datacollection : {},
            datacollection2 : {},
            urlValidData : [],
            urlValidPriceData : [],
            urlSellerStatisticsData : [],
            prospectQualifiedRegisteredData : [],
            options : {
                responsive : true,
                maintainAspectRatio : false,
                title : {
                    display : false,
                    text : 'Chart.js Line Chart'
                },
                tooltips : {
                    mode : 'index',
                    intersect : false,
                },
                hover : {
                    mode : 'nearest',
                    intersect : true
                },
                scales : {
                    xAxes : [
                        {
                            display : true,
                            scaleLabel : {
                                display : true,
                                labelString : 'Month'
                            }
                        }
                    ],
                    yAxes : [
                        {
                            display : true,
                            scaleLabel : {
                                display : true,
                                labelString : 'Value'
                            }
                        }
                    ]
                }
            },

            styles : {
                height : '500px',
            },
        };
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listSellerTeam : state => state.storeExtDomain.listSellerTeam,
            listIncharge : state => state.storeAccount.listIncharge,
        }),

        isQc() {
            let qcRoleIds = [
                5,
                8,
                9,
                10
            ];
            return qcRoleIds.includes(this.user.role_id);
        },

        isQcManager() {
            return this.user.role_id === 8
        },

        isCs() {
            return this.user.role_id === 6
        },

        dateRanges() {
            let today = new Date();

            let yesterday = new Date();
            yesterday.setDate(today.getDate() - 1);
            yesterday.setHours(0, 0, 0, 0);

            return {
                'Today' : [
                    today,
                    today
                ],
                'Yesterday' : [
                    yesterday,
                    yesterday
                ],
                'This month' : [
                    new Date(today.getFullYear(),
                        today.getMonth(), 1),
                    new Date(today.getFullYear(),
                        today.getMonth() + 1, 0)
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
                'This year' : [
                    new Date(today.getFullYear(), 0, 1),
                    new Date(today.getFullYear(), 11, 31)
                ],
                'Last year': [
                    new Date(today.getFullYear() - 1, 0, 1),
                    new Date(today.getFullYear() - 1, 11, 31)
                ],
            }
        }
    },

    mounted() {
        this.fillData('without')
        this.getListSellerTeam();
        this.getTeamInCharge();
    },

    methods : {
        async getTeamInCharge() {
            await this.$store.dispatch('actionGetTeamInCharge');
        },

        async getListSellerTeam(params) {
            await this.$store.dispatch('actionGetListSellerTeam', params);
        },

        changeSelection() {
            let date_type = this.filterModel.date_type

            if (date_type != 'Monthly') {
                this.isRangeDate = true;
                this.isMonthly = false;
            } else {
                this.isRangeDate = false;
                this.isMonthly = true;
            }
        },

        fillData(isFilter) {
            axios.get('/api/dashboard-admin', {
                params : {
                    date_type : this.filterModel.date_type,
                    monthly : this.filterModel.monthly,
                    date1 : this.filterModel.date1,
                    date2 : this.filterModel.date2,
                    is_filter : isFilter == 'with' ? true : false,
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
