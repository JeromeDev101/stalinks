<template>
    <div class="col-lg-12">
        <div class="box box-primary" style="padding-bottom:0.5em;">
            <div class="box-header">
                <h3
                    class="box-title text-primary">Total
                                                   Buyer
                                                   vs
                                                   Valid
                                                   Buyers
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
                                        v-model="filterModel.buyerValid.scope"
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
                            <label
                                style="color: #333">Team In-Charge
                            </label>
                            <div class="input-group">
                                <select name=""
                                        class="form-control"
                                        id=""
                                        v-model="filterModel.buyerValid.team_in_charge">
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
                                @click="filterBuyerValid">
                                Filter</button>
                            <button
                                class="btn btn-default" @click="clearBuyerValidFilter">Clear</button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart type="bar" height="350"
                               :options="sellerValidChartOptions"
                               :series="buyerValidData"></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import buyer_valid from "../../graph_settings/buyer_valid";
import axios from "axios";

export default {
    name : "BuyerValidGraph",

    props:[
        'dateRanges',
        'listSellerTeam'
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
                this.filterModel.buyerValid.dateRange.startDate =
                    new
                    Date(this.filterModel.buyerValid.dateRange.startDate).toJSON();
                this.filterModel.buyerValid.dateRange.endDate =
                    new
                    Date(this.filterModel.buyerValid.dateRange.endDate).toJSON();
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
