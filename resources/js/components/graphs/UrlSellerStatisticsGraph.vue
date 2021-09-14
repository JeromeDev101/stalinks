<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">URL Seller Statistics
                </h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label
                                style="color: #333">Scope
                            </label>
                            <div class="input-group">
                                <select name=""
                                        class="form-control"
                                        v-model="filterModel.urlSellerStatistics.scope"
                                >
                                    <option
                                        value="daily">
                                        Daily</option>
                                    <option
                                        value="weekly">
                                        Weekly</option>
                                    <option
                                        value="monthly">
                                        Monthly</option>
                                    <option
                                        value="team">
                                        Team</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--                        <div class="col-md-2">-->
                    <!--                            <div class="form-group">-->
                    <!--                                <label-->
                    <!--                                    style="color: #333">Date-->
                    <!--                                                        Range-->
                    <!--                                </label>-->
                    <!--                                <div class="input-group">-->
                    <!--                                    <date-range-picker-->
                    <!--                                        ref="picker"-->
                    <!--                                        v-model="filterModel.urlSellerStatistics.dateRange"-->
                    <!--                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"-->
                    <!--                                        :dateRange="filterModel.urlSellerStatistics.dateRange"-->
                    <!--                                        :ranges="dateRanges"-->
                    <!--                                        :linkedCalendars="true"-->
                    <!--                                        opens="right"-->
                    <!--                                        style="width: 100%"-->
                    <!--                                    />-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                        </div>-->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Date Range</label>
                            <div class="input-group mb-3" style="flex-wrap: unset !important;">
                                <div class="input-group-prepend">
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"

                                        @click="filterModel.urlSellerStatistics.dateMode = filterModel.urlSellerStatistics.dateMode === 'Created' ? 'Status' : 'Created'">
                                        {{ filterModel.urlSellerStatistics.dateMode }}
                                    </button>
                                </div>

                                <date-range-picker
                                    ref="picker"
                                    v-model="filterModel.urlSellerStatistics.dateRange"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    :dateRange="filterModel.urlSellerStatistics.dateRange"
                                    :ranges="dateRanges"
                                    :linkedCalendars="true"
                                    opens="right"
                                    style="width: 100%"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"
                         v-if="this.filterModel.urlSellerStatistics.scope !== 'team'">
                        <div class="form-group">
                            <label
                                style="color: #333">Team In-Charge
                            </label>
                            <div class="input-group">
                                <select name=""
                                        class="form-control"
                                        id=""
                                        v-model="filterModel.urlSellerStatistics.team_in_charge">
                                    <option
                                        value="0">All
                                    </option>
                                    <option
                                        :value="user.id"
                                        v-for="user
                                            in
                                            listSellerTeam.data" v-if="user.id !== 0">{{ user.username }}</option>
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
                                @click="filterUrlSellerStatistics">
                                Filter</button>
                            <button
                                class="btn btn-default" @click="clearUrlSellerStatisticsFilter">Clear</button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart type="line" height="350"
                               :options="urlSellerStatisticsChartOptions"
                               :series="urlSellerStatisticsData"></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import url_seller_statistics from "../../graph_settings/url_prospect_status";

export default {
    name : "UrlSellerStatisticsGraph",

    props : [
        'dateRanges',
        'listSellerTeam'
    ],

    data() {
        return {
            filterModel : {
                urlSellerStatistics : {
                    dateRange : {
                        startDate : null,
                        endDate : null
                    },
                    scope : 'monthly',
                    team_in_charge : 0,
                    dateMode : 'Created'
                },
            },
            urlSellerStatisticsData : []
        };
    },

    mounted() {
        this.getUrlSellerStatisticsData();
    },

    computed : {
        urlSellerStatisticsChartOptions() {
            return url_seller_statistics.graphSetting();
        },
    },

    methods : {
        filterUrlSellerStatistics() {
            if
            (this.filterModel.urlSellerStatistics.dateRange.startDate !=
                null &&
                this.filterModel.urlSellerStatistics.dateRange.endDate !=
                null) {
                this.filterModel.urlSellerStatistics.dateRange.startDate =
                    new
                    Date(this.filterModel.urlSellerStatistics.dateRange.startDate).toJSON();
                this.filterModel.urlSellerStatistics.dateRange.endDate =
                    new
                    Date(this.filterModel.urlSellerStatistics.dateRange.endDate).toJSON();
            }

            this.getUrlSellerStatisticsData(this.filterModel.urlSellerStatistics.dateRange.startDate, this.filterModel.urlSellerStatistics.dateRange.endDate);
        },

        clearUrlSellerStatisticsFilter() {
            this.filterModel.urlSellerStatistics = {
                dateRange : {
                    startDate : null,
                    endDate : null
                },
                team_in_charge: 0,
                scope: 'monthly',
                dateMode: 'Created'
            };

            // this.filterModel.urlSellerStatistics.scope =
            //     'monthly';

            this.getUrlSellerStatisticsData();
        },

        getUrlSellerStatisticsData(start = null, end =
            null) {
            axios.get('/api/graphs/url-seller-statistics?start_date=' +
                start + '&end_date=' + end + '&scope=' +
                this.filterModel.urlSellerStatistics.scope +
                '&team_in_charge=' +
                this.filterModel.urlSellerStatistics.team_in_charge +
                '&mode=' + this.filterModel.urlSellerStatistics.dateMode)
                .then(response => {
                    let data = response.data;

                    this.urlSellerStatisticsData =
                        url_seller_statistics.graphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
