<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.wv_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_scope') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.writerValid.scope" class="form-control">
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
                                    v-model="filterModel.writerValid.dateRange"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    :dateRange="filterModel.writerValid.dateRange"
                                    :ranges="dateRanges"
                                    :linkedCalendars="true"
                                    opens="right"
                                    style="width: 100%"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"
                         v-if="this.filterModel.writerValid.scope === 'global'">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.o_team_in_charge') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.writerValid.team_in_charge" class="form-control">
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
                            <button class="btn btn-default col-md-6" @click="filterWriterValid">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearWriterValidFilter">
                                {{ $t('message.admin_dashboard.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        :options="writerValidChartOptions"
                        :series="writerValidData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import writer_valid from "../../graph_settings/writer_valid";
import axios from "axios";

export default {
    name : "WriterValidGraph",

    props:[
        'dateRanges',
        'listTeamInCharge'
    ],

    data() {
        return {
            filterModel:{
                writerValid : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    scope: 'global',
                    team_in_charge: 0
                },
            },
            writerValidData: []
        };
    },

    mounted() {
        this.getWriterValidData();
    },

    computed : {
        writerValidChartOptions() {
            return writer_valid.graphSetting();
        },
    },

    methods: {
        filterWriterValid() {
            if (this.filterModel.writerValid.dateRange.startDate != null && this.filterModel.writerValid.dateRange.endDate != null) {
                this.filterModel.writerValid.dateRange.startDate =
                    new
                    Date(this.filterModel.writerValid.dateRange.startDate).toJSON();
                this.filterModel.writerValid.dateRange.endDate =
                    new
                    Date(this.filterModel.writerValid.dateRange.endDate).toJSON();
            }

            this.getWriterValidData(this.filterModel.writerValid.dateRange.startDate, this.filterModel.writerValid.dateRange.endDate);
        },

        clearWriterValidFilter() {
            this.filterModel.writerValid = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.writerValid.scope = 'global';
            this.filterModel.writerValid.team_in_charge =
                '0';

            this.getWriterValidData();
        },

        getWriterValidData(start = null, end = null) {
            axios.get('/api/graphs/writer-valid?start_date=' + start + '&end_date=' + end + '&scope=' + this.filterModel.writerValid.scope + '&team_in_charge=' + this.filterModel.writerValid.team_in_charge)
                .then(response => {
                    let data = response.data;

                    this.writerValidData =
                        writer_valid.graphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
