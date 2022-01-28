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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Buyer Surveys</h4>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header d-flex align-items-center">
                                        <h3 class="card-title text-primary mr-3">Survey Set A</h3>

                                        <button
                                            data-toggle="modal"
                                            data-target="#modal-survey-a-questions"
                                            type="button"
                                            class="btn btn-primary btn-sm">

                                            <i class="far fa-question-circle"></i>
                                            View Questions
                                        </button>

                                        <div class="card-tools ml-auto">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" id="collapseExample">
                                        <apexchart
                                            :series="setAData"
                                            :options="setAChartOptions"
                                            type="bar"
                                            height="350">

                                        </apexchart>
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
                                    <div class="card-body" id="collapseExample2">
                                        <div class="row">
                                            <!-- set b 1 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <p class="mb-0 font-weight-bold">
                                                            Question 1: In your opinion, what is the most important
                                                            thing to have in desk management?
                                                        </p>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setBOneData"
                                                            :options="setBOneChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set b 2 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <p class="mb-0 font-weight-bold">
                                                            Question 2: From 1-6, how would you rate our tools?
                                                        </p>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setBTwoData"
                                                            :options="setBTwoChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set b 3 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <p class="mb-0 font-weight-bold">
                                                            Question 3: From 1-10, how well do you understand Stalinks
                                                            and what can we do to help you?
                                                        </p>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setBThreeData"
                                                            :options="setBThreeChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set b 4 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <p class="mb-0 font-weight-bold">
                                                            Question 4: What would you like to see in Stalinks?
                                                        </p>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setBFourData"
                                                            :options="setBFourChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set b 5 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <p class="mb-0 font-weight-bold">
                                                            Question 5: How would you rate our team interaction with you?
                                                        </p>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setBFiveData"
                                                            :options="setBFiveChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Survey -->
        <div
            aria-hidden="true"
            aria-labelledby="modelTitleId"
            data-backdrop="static"
            tabindex="-1"
            role="dialog"
            ref="surveyModal"
            id="modal-survey-a-questions"
            class="modal fade"
            style="z-index: 9999">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">Buyer Survey A Questions</h5>
                    </div>
                    <div class="modal-body">
                        <div v-for="(question, index) in buyerSetAQuestions">
                            <p class="mb-2 font-weight-bold">
                                {{ index + 1 + '. ' + question }}
                            </p>

                            <hr/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import set_a from "../data/set_a";
import set_b from "../data/set_b";

export default {
    name : "AdminSurvey",

    data() {
        return {
            surveys: {},

            setAChartOptions: {},
            setAData: [],

            setBOneChartOptions: {},
            setBOneData: [],

            setBTwoChartOptions: {},
            setBTwoData: [],

            setBThreeChartOptions: {},
            setBThreeData: [],

            setBFourChartOptions: {},
            setBFourData: [],

            setBFiveChartOptions: {},
            setBFiveData: [],

            buyerSetAQuestions: [
                'Do you find using our system efficient?',
                'Does our system provide the URLs that you are looking for?',
                'Do you find our HELP page easy to follow?',
                'Do you find our payment methods too complicated for you?',
                'Are you satisfied with the service that we provide right now?'
            ]
        }
    },

    mounted() {
        this.getSurveyList();
        this.setAChartOptions = set_a.graphSetting();
        this.setBOneChartOptions = set_b.graphSetting('one');
        this.setBTwoChartOptions = set_b.graphSetting('two');
        this.setBThreeChartOptions = set_b.graphSetting('three');
        this.setBFourChartOptions = set_b.graphSetting('four');
        this.setBFiveChartOptions = set_b.graphSetting('five');
    },

    methods: {
        async getSurveyList() {
            await axios.get('/api/surveys')
                .then((response) => {
                    this.surveys = response.data;
                    this.setAData = set_a.graphData(this.surveys);
                    this.setBOneData = set_b.graphData(this.surveys, 'one');
                    this.setBTwoData = set_b.graphData(this.surveys, 'two');
                    this.setBThreeData = set_b.graphData(this.surveys, 'three');
                    this.setBFourData = set_b.graphData(this.surveys, 'four');
                    this.setBFiveData = set_b.graphData(this.surveys, 'five');
                })
        }
    },
}
</script>

<style scoped>

</style>
