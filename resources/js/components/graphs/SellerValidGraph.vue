<template>
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
                         v-if="this.filterModel.sellerValid.scope === 'global'">
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
                            <label
                                style="color: #333">Show Numbers
                            </label>
                            <v-select
                                v-model="filterModel.show_numbers"
                                multiple
                                placeholder="All"
                                :searchable="true"
                                :options="seriesList"
                                :reduce="series => series.index"/>
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
                               :options="sellerValidOption"
                               :series="sellerValidData"></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import seller_valid from "../../graph_settings/seller_valid";
import axios from "axios";

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
            seriesList: [{
                index : 0,
                label : 'Registration'
            },{
                index : 1,
                label : 'Valid'
            },{
                index : 2,
                label : 'Valid with URLs'
            },{
                index : 3,
                label : '% Valid'
            },{
                index : 4,
                label : '% URL'
            }],
            sellerValidData: [],
            sellerValidOption: {}
        };
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

                    this.sellerValidData =
                        seller_valid.sellerValidGraphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
