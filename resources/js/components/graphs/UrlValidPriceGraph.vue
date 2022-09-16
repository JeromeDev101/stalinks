<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.uvp_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_scope') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.urlValidPrice.scope" class="form-control">
                                    <option value="global">{{ $t('message.admin_dashboard.sv_global') }}</option>
                                    <option value="team">{{ $t('message.admin_dashboard.o_team_in_charge') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_date_range') }}</label>

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
                         v-if="filterModel.urlValidPrice.scope === 'global'">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_team_in_charge') }}</label>
                            <div class="input-group">
                                <select class="form-control" v-model="filterModel.urlValidPrice.team_in_charge">
                                    <option value="0">{{ $t('message.admin_dashboard.all') }}</option>
                                    <option :value="user.id" v-for="user in listSellerTeam.data" v-if="user.id !== 0">
                                        {{ user.username }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_show_number') }}</label>

                            <v-select
                                v-model="filterModel.show_numbers"
                                multiple
                                :placeholder="$t('message.admin_dashboard.all')"
                                :searchable="true"
                                :options="seriesList"
                                :reduce="series => series.index"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>{{ $t('message.admin_dashboard.action') }}</label>

                            <br>
                            <button class="btn btn-default col-md-6" @click="filterUrlValidPrice">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearUrlValidPriceFilter">
                                {{ $t('message.admin_dashboard.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-1">
                        <h6 class="mb-2">{{ $t('message.admin_dashboard.sv_total') }}: {{ total }}</h6>
                    </div>
                    <div class="col-sm-1">
                        <h6 class="mb-2">{{ $t('message.admin_dashboard.sv_valid') }}: {{ valid }}</h6>
                    </div>
                    <div class="col-sm-2">
                        <h6 class="mb-2">{{ $t('message.admin_dashboard.uvp_good_price') }}: {{ good_price }}</h6>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        ref="chart"
                        :options="urlValidOption"
                        :series="urlValidPriceData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import url_valid_price from "../../graph_settings/url_valid_price";
import axios from "axios";
import _ from 'underscore';
import __ from 'lodash';
import {dateRange} from "../../mixins/dateRange";

export default {
    name : "UrlValidPriceGraph",
    mixins: [dateRange],

    props : [
        'dateRanges',
        'listSellerTeam'
    ],

    data() {
        return {
            rawData : null,
            filterModel : {
                urlValidPrice : {
                    scope: 'global',
                    team_in_charge: 0,
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                },
                show_numbers: []
            },
            urlValidPriceData : [],
            urlValidOption: {}
        };
    },

    computed : {
        total() {
            return __.sum(_.pluck(this.rawData, 'upload'));
        },

        valid() {
            return __.sum(_.pluck(this.rawData, 'valid'));
        },

        good_price() {
            return __.sum(_.pluck(this.rawData, 'quality_price'));
        },

        seriesList () {
            return [{
                index : 0,
                label : this.$t('message.admin_dashboard.uvp_upload')
            },{
                index : 1,
                label : this.$t('message.admin_dashboard.sv_valid')
            },{
                index : 2,
                label : this.$t('message.admin_dashboard.uvp_quality_price')
            },{
                index : 3,
                label : 'GP'
            },{
                index : 4,
                label : 'UV'
            }]
        }
    },

    mounted() {
        this.getUrlValidPriceChartOptions();
        this.getUrlValidPriceData();
    },

    methods : {
        getUrlValidPriceChartOptions() {
            this.urlValidOption = url_valid_price.urlValidPriceGraphSetting();
        },

        filterNumbers() {
            this.filterModel.show_numbers = this.filterModel.show_numbers.length === 0 ? [
                0,
                1,
                2,
                3,
                4
            ] : this.filterModel.show_numbers;

            this.urlValidOption = {
                dataLabels : {
                    enabledOnSeries : this.filterModel.show_numbers
                }
            };
        },

        filterUrlValidPrice() {
            if
            (this.filterModel.urlValidPrice.dateRange.startDate !=
                null &&
                this.filterModel.urlValidPrice.dateRange.endDate != null) {
                // this.filterModel.urlValidPrice.dateRange.startDate =
                //     new
                //     Date(this.filterModel.urlValidPrice.dateRange.startDate).toJSON();
                // this.filterModel.urlValidPrice.dateRange.endDate =
                //     new
                //     Date(this.filterModel.urlValidPrice.dateRange.endDate).toJSON();

                this.filterModel.urlValidPrice.dateRange = this.formatFilterDates(this.filterModel.urlValidPrice.dateRange)
            }

            this.filterNumbers();
            this.getUrlValidPriceData(this.filterModel.urlValidPrice.dateRange.startDate, this.filterModel.urlValidPrice.dateRange.endDate);


        },

        clearUrlValidPriceFilter() {
            this.filterModel.urlValidPrice = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.show_numbers = [];
            this.filterNumbers();

            this.filterModel.urlValidPrice.scope = 'global';

            this.getUrlValidPriceData();
        },

        getUrlValidPriceData(start = null, end = null) {
            axios.get('/api/graphs/url-valid-price?start_date=' +
                start + '&end_date=' + end + '&scope=' +
                this.filterModel.urlValidPrice.scope +
                '&team_in_charge=' +
                this.filterModel.urlValidPrice.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.rawData = data;
                    this.urlValidPriceData =
                        url_valid_price.urlValidPriceGraphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
