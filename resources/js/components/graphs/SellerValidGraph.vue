<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.sv_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_scope') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.sellerValid.scope" class="form-control">
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
                         v-if="this.filterModel.sellerValid.scope === 'global'">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_team_in_charge') }}</label>

                            <div class="input-group">
                                <select class="form-control" v-model="filterModel.sellerValid.team_in_charge">
                                    <option value="0">{{ $t('message.admin_dashboard.all') }}</option>
                                    <option :value="user.id" v-for="user in listSellerTeam.data" v-if="user.id != 0">
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
                            <button class="btn btn-default col-md-6" @click="filterSellerValid">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearSellerValidFilter">
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
                        <h6 class="mb-2">{{ $t('message.admin_dashboard.sv_valid_with_url') }}: {{ with_url }}</h6>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        :options="sellerValidOption"
                        :series="sellerValidData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import seller_valid from "../../graph_settings/seller_valid";
import axios from "axios";
import __ from "lodash";
import _ from "underscore";

export default {
    name : "SellerValidGraph",

    props:[
        'dateRanges',
        'listSellerTeam'
    ],

    data() {
        return {
            filterModel:{
                sellerValid : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    scope: 'global',
                    team_in_charge: 0
                },
                show_numbers: []
            },
            sellerValidData: [],
            sellerValidOption: {},
            rawData : null
        };
    },

    computed : {
        total() {
            return __.sum(_.pluck(this.rawData, 'total_registration'));
        },

        valid() {
            return __.sum(_.pluck(this.rawData, 'total_valid'));
        },

        with_url() {
            return __.sum(_.pluck(this.rawData, 'valid_with_url'));
        },

        seriesList () {
            return [{
                index : 0,
                label : this.$t('message.admin_dashboard.sv_registration')
            },{
                index : 1,
                label : this.$t('message.admin_dashboard.sv_valid')
            },{
                index : 2,
                label : this.$t('message.admin_dashboard.sv_valid_with_url')
            },{
                index : 3,
                label : '% ' + this.$t('message.admin_dashboard.sv_valid')
            },{
                index : 4,
                label : '% ' + this.$t('message.admin_dashboard.sv_url')
            }];
        }
    },

    mounted() {
        this.getSellerValidChartOptions();
        this.getSellerValidData();
    },

    methods: {
        getSellerValidChartOptions() {
            this.sellerValidOption = seller_valid.sellerValidGraphSetting();
        },

        filterNumbers() {
            this.filterModel.show_numbers = this.filterModel.show_numbers.length === 0 ? [
                0,
                1,
                2,
                3,
                4
            ] : this.filterModel.show_numbers;

            this.sellerValidOption = {
                dataLabels : {
                    enabledOnSeries : this.filterModel.show_numbers
                }
            };
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

            this.filterNumbers();
            this.getSellerValidData(this.filterModel.sellerValid.dateRange.startDate, this.filterModel.sellerValid.dateRange.endDate);
        },


        clearSellerValidFilter() {
            this.filterModel.sellerValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.show_numbers = [];
            this.filterNumbers();

            this.filterModel.sellerValid.scope = 'global';
            this.filterModel.sellerValid.team_in_charge =
                '0';

            this.getSellerValidData();
        },

        getSellerValidData(start = null, end = null) {
            axios.get('/api/graphs/seller-valid?start_date=' + start + '&end_date=' + end + '&scope=' + this.filterModel.sellerValid.scope+'&team_in_charge='+this.filterModel.sellerValid.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.rawData = data;

                    this.sellerValidData =
                        seller_valid.sellerValidGraphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
