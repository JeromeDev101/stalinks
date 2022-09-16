<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.bv_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_scope') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.buyerValid.scope" class="form-control">
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
                                    v-model="filterModel.buyerValid.dateRange"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    :dateRange="filterModel.buyerValid.dateRange"
                                    :ranges="dateRanges"
                                    :linkedCalendars="true"
                                    opens="right"
                                    style="width: 100%"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"
                         v-if="this.filterModel.buyerValid.scope === 'global'">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_team_in_charge') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.buyerValid.team_in_charge" class="form-control">
                                    <option value="0">{{ $t('message.admin_dashboard.all') }}</option>
                                    <option :value="user.id" v-for="user in listTeamInCharge.data" v-if="user.id != 0">
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
                            <button class="btn btn-default col-md-6" @click="filterBuyerValid">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearBuyerValidFilter">
                                {{ $t('message.admin_dashboard.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        :options="sellerValidChartOptions"
                        :series="buyerValidData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import buyer_valid from "../../graph_settings/buyer_valid";
import axios from "axios";
import {dateRange} from "../../mixins/dateRange";

export default {
    name : "BuyerValidGraph",
    mixins: [dateRange],

    props:[
        'dateRanges',
        'listTeamInCharge'
    ],

    data() {
        return {
            filterModel:{
                buyerValid : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    scope: 'global',
                    team_in_charge: 0
                },
            },
            buyerValidData: []
        };
    },

    mounted() {
        this.getSellerValidData();
    },

    computed : {
        sellerValidChartOptions() {
            return buyer_valid.graphSetting();
        },
    },

    methods: {
        filterBuyerValid() {
            if (this.filterModel.buyerValid.dateRange.startDate != null && this.filterModel.buyerValid.dateRange.endDate != null) {
                // this.filterModel.buyerValid.dateRange.startDate =
                //     new
                //     Date(this.filterModel.buyerValid.dateRange.startDate).toJSON();
                // this.filterModel.buyerValid.dateRange.endDate =
                //     new
                //     Date(this.filterModel.buyerValid.dateRange.endDate).toJSON();

                this.filterModel.buyerValid.dateRange = this.formatFilterDates(this.filterModel.buyerValid.dateRange)
            }

            this.getSellerValidData(this.filterModel.buyerValid.dateRange.startDate, this.filterModel.buyerValid.dateRange.endDate);
        },


        clearBuyerValidFilter() {
            this.filterModel.buyerValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.buyerValid.scope = 'global';
            this.filterModel.buyerValid.team_in_charge =
                '0';

            this.getSellerValidData();
        },

        getSellerValidData(start = null, end = null) {
            axios.get('/api/graphs/buyer-valid?start_date=' + start + '&end_date=' + end + '&scope=' + this.filterModel.buyerValid.scope+'&team_in_charge='+this.filterModel.buyerValid.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.buyerValidData =
                        buyer_valid.graphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
