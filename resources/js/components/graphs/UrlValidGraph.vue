<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">URLs Validation</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
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
                                Filter
                            </button>
                            <button
                                class="btn btn-default" @click="clearUrlValidFilter">Clear
                            </button>
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
</template>

<script>
import url_valid from "../../graph_settings/url_valid";
import axios from "axios";

export default {
    name : "UrlValidGraph",

    props : [
        'dateRanges'
    ],

    data() {
        return {
            filterModel : {
                urlValid : {
                    dateRange : {
                        startDate : null,
                        endDate : null
                    },
                },
            },
            urlValidData : []
        };
    },

    mounted() {
        this.getUrlValidData();
    },

    computed : {
        urlValidChartOptions() {
            return url_valid.urlValidGraphSetting();
        },
    },

    methods : {
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

        clearUrlValidFilter() {
            this.filterModel.urlValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.getUrlValidData();
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
    }
}
</script>

<style scoped>

</style>
