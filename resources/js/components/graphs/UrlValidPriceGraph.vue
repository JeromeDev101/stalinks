<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">URL Valid Price
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
                         v-if="filterModel.urlValidPrice.scope === 'global'">
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
                                            listSellerTeam.data" v-if="user.id !== 0">{{ user.username }}</option>
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
                                @click="filterUrlValidPrice">
                                Filter</button>
                            <button
                                class="btn btn-default" @click="clearUrlValidPriceFilter">Clear</button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart type="bar" height="350"
                               ref="chart"
                               :options="urlValidOption"
                               :series="urlValidPriceData"></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import url_valid_price from "../../graph_settings/url_valid_price";
import axios from "axios";

export default {
    name : "UrlValidPriceGraph",

    props : [
        'dateRanges',
        'listSellerTeam'
    ],

    data() {
        return {
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
            seriesList: [{
                index : 0,
                label : 'Upload'
            },{
                index : 1,
                label : 'Valid'
            },{
                index : 2,
                label : 'Quality Price'
            },{
                index : 3,
                label : 'GP'
            },{
                index : 4,
                label : 'UV'
            }],
            urlValidPriceData : [],
            urlValidOption: {}
        };
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
                this.filterModel.urlValidPrice.dateRange.startDate =
                    new
                    Date(this.filterModel.urlValidPrice.dateRange.startDate).toJSON();
                this.filterModel.urlValidPrice.dateRange.endDate =
                    new
                    Date(this.filterModel.urlValidPrice.dateRange.endDate).toJSON();
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

                    this.urlValidPriceData =
                        url_valid_price.urlValidPriceGraphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
