<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Survey Set A</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="collapseExample">
                        <apexchart type="bar" height="350"
                                   :options="setAChartOptions"
                                   :series="setAData"></apexchart>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Survey Set B</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import set_a from "../data/set_a";

export default {
    name : "AdminSurvey",

    data() {
        return {
            setAChartOptions: {},
            setAData: [],
            surveys: {}
        }
    },

    mounted() {
        this.getSurveyList();
        this.setAChartOptions = set_a.graphSetting();
    },

    methods: {
        async getSurveyList() {
            await axios.get('/api/admin/surveys')
                .then((response) => {
                    this.surveys = response.data;
                    this.setAData = set_a.graphData(this.surveys);
                })
        }
    },
}
</script>

<style scoped>

</style>
