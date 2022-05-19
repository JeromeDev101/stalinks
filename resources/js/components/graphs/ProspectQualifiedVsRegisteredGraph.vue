<template>
    <div class="col-sm-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title text-primary">{{ $t('message.admin_dashboard.upqr_title') }}</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="color: #333">{{ $t('message.admin_dashboard.sv_scope') }}</label>

                            <div class="input-group">
                                <select v-model="filterModel.prospectQualifiedRegistered.scope" class="form-control">
                                    <option value="monthly">{{ $t('message.admin_dashboard.uss_monthly') }}</option>
                                    <option value="team">{{ $t('message.admin_dashboard.uss_team') }}</option>
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
                                    v-model="filterModel.prospectQualifiedRegistered.dateRange"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    :dateRange="filterModel.prospectQualifiedRegistered.dateRange"
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
                            <label>{{ $t('message.admin_dashboard.action') }}</label>

                            <br>
                            <button class="btn btn-default col-md-6" @click="filterProspectQualifiedRegistered">
                                {{ $t('message.admin_dashboard.filter') }}
                            </button>
                            <button class="btn btn-default" @click="clearProspectQualifiedRegistered">
                                {{ $t('message.admin_dashboard.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="small">
                    <apexchart
                        type="bar"
                        height="350"
                        :options="prospectQualifiedRegisteredOptions"
                        :series="prospectQualifiedRegisteredData">

                    </apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import prospect_qualified_register from "../../graph_settings/prospect_qualified_register";
import axios from "axios";

export default {
    name : "ProspectQualifiedVsRegisteredGraph",

    props : [
        'dateRanges'
    ],

    data() {
        return {
            filterModel : {
                prospectQualifiedRegistered : {
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    scope : 'monthly'
                }
            },
            prospectQualifiedRegisteredData : []
        }
    },

    mounted() {
        this.getProspectQualifiedRegistered();
    },

    computed : {
        prospectQualifiedRegisteredOptions() {
            return prospect_qualified_register.graphSetting();
        },
    },

    methods : {
        filterProspectQualifiedRegistered() {
            if
            (this.filterModel.prospectQualifiedRegistered.dateRange.startDate !=
                null &&
                this.filterModel.prospectQualifiedRegistered.dateRange.endDate !=
                null) {
                this.filterModel.prospectQualifiedRegistered.dateRange.startDate =
                    new
                    Date(this.filterModel.prospectQualifiedRegistered.dateRange.startDate).toJSON();
                this.filterModel.prospectQualifiedRegistered.dateRange.endDate =
                    new
                    Date(this.filterModel.prospectQualifiedRegistered.dateRange.endDate).toJSON();
            }

            this.getProspectQualifiedRegistered(this.filterModel.prospectQualifiedRegistered.dateRange.startDate, this.filterModel.prospectQualifiedRegistered.dateRange.endDate);
        },

        clearProspectQualifiedRegistered() {
            this.filterModel.prospectQualifiedRegistered = {
                dateRange : {
                    startDate : null,
                    endDate : null
                }
            };

            this.filterModel.prospectQualifiedRegistered.scope =
                'monthly';

            this.getProspectQualifiedRegistered();
        },

        getProspectQualifiedRegistered(start = null, end =
            null) {
            axios.get('/api/graphs/prospect-qualified-registered?start_date=' +
                start + '&end_date=' + end + '&scope=' +
                this.filterModel.prospectQualifiedRegistered.scope)
                .then(response => {
                    let data = response.data;

                    this.prospectQualifiedRegisteredData =
                        prospect_qualified_register.graphData(data);
                });
        },
    }
}
</script>

<style scoped>

</style>
