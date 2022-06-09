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
            <!-- toggle surveys -->

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div v-for="(tog, index) in toggle" :key="index" class="custom-control custom-switch d-inline-block mx-2">
                            <input
                                v-model="toggle[index]['active']"
                                :checked="tog.active"
                                :id="'switch' + tog.id"
                                type="checkbox"
                                class="custom-control-input"

                                @change="">

                            <label class="custom-control-label" :for="'switch' + tog.id">{{ toggleText[index] }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="toggle.buyer.active" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ this.$t('message.survey_dashboard.t_buyer') }}</h4>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header d-flex align-items-center">
                                        <h3 class="card-title text-primary mr-3">{{ this.$t('message.survey_dashboard.bs_title_a') }}</h3>

<!--                                        <button-->
<!--                                            data-toggle="modal"-->
<!--                                            data-target="#modal-survey-a-questions"-->
<!--                                            type="button"-->
<!--                                            class="btn btn-primary btn-sm">-->

<!--                                            <i class="far fa-question-circle"></i>-->
<!--                                            View Questions-->
<!--                                        </button>-->

                                        <div class="card-tools ml-auto">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" id="collapseExample">
<!--                                        <apexchart-->
<!--                                            :series="setAData"-->
<!--                                            :options="setAChartOptions"-->
<!--                                            type="bar"-->
<!--                                            height="350">-->

<!--                                        </apexchart>-->

                                        <div class="row">
                                            <!-- set a 1 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetAQuestions[0].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'buyer',
                                                                    'one',
                                                                     buyerSetAQuestions[0].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setAOneData"
                                                            :options="setAOneChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set a 2 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetAQuestions[1].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'buyer',
                                                                    'two',
                                                                     buyerSetAQuestions[1].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setATwoData"
                                                            :options="setATwoChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set a 3 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetAQuestions[2].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'buyer',
                                                                    'three',
                                                                     buyerSetAQuestions[2].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setAThreeData"
                                                            :options="setAThreeChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set a 4 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetAQuestions[3].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'buyer',
                                                                    'four',
                                                                     buyerSetAQuestions[3].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setAFourData"
                                                            :options="setAFourChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- set a 5 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetAQuestions[4].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'buyer',
                                                                    'five',
                                                                     buyerSetAQuestions[4].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="setAFiveData"
                                                            :options="setAFiveChartOptions"
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

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title text-primary">{{ this.$t('message.survey_dashboard.bs_title_b') }}</h3>
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
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetBQuestions[0].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'b',
                                                                    'buyer',
                                                                    'one',
                                                                     buyerSetBQuestions[0].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
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
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetBQuestions[1].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'b',
                                                                    'buyer',
                                                                    'two',
                                                                     buyerSetBQuestions[1].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
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
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetBQuestions[2].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'b',
                                                                    'buyer',
                                                                    'three',
                                                                     buyerSetBQuestions[2].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
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
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetBQuestions[3].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'b',
                                                                    'buyer',
                                                                    'four',
                                                                     buyerSetBQuestions[3].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
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
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ buyerSetBQuestions[4].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'b',
                                                                    'buyer',
                                                                    'five',
                                                                     buyerSetBQuestions[4].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
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

            <div v-if="toggle.seller.active" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ this.$t('message.survey_dashboard.t_seller') }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title text-primary">{{ this.$t('message.survey_dashboard.ss_title_a') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body" id="sellerSurveySetACollapse">
                                        <div class="row">
                                            <!-- seller set a 1 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[0].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'one',
                                                                     sellerSetAQuestions[0].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetAOneData"
                                                            :options="sellerSetAOneChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- seller set a 2 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[1].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'two',
                                                                     sellerSetAQuestions[1].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetATwoData"
                                                            :options="sellerSetATwoChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- seller set a 3 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[2].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'three',
                                                                     sellerSetAQuestions[2].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetAThreeData"
                                                            :options="sellerSetAThreeChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- seller set a 4 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[3].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'four',
                                                                     sellerSetAQuestions[3].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetAFourData"
                                                            :options="sellerSetAFourChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- seller set a 5 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[4].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'five',
                                                                     sellerSetAQuestions[4].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetAFiveData"
                                                            :options="sellerSetAFiveChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- seller set a 6 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ sellerSetAQuestions[5].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'seller',
                                                                    'six',
                                                                     sellerSetAQuestions[5].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="sellerSetASixData"
                                                            :options="sellerSetASixChartOptions"
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

            <div v-if="toggle.writer.active" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ this.$t('message.survey_dashboard.t_writer') }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-outline card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title text-primary">{{ this.$t('message.survey_dashboard.ws_title_a') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body" id="writerSurveySetACollapse">
                                        <div class="row">
                                            <!-- writer set a 1 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ writerSetAQuestions[0].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'writer',
                                                                    'one',
                                                                     writerSetAQuestions[0].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="writerSetAOneData"
                                                            :options="writerSetAOneChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- writer set a 2 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ writerSetAQuestions[1].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'writer',
                                                                    'two',
                                                                     writerSetAQuestions[1].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="writerSetATwoData"
                                                            :options="writerSetATwoChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- writer set a 3 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ writerSetAQuestions[2].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'writer',
                                                                    'three',
                                                                     writerSetAQuestions[2].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="writerSetAThreeData"
                                                            :options="writerSetAThreeChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- writer set a 4 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ writerSetAQuestions[3].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'writer',
                                                                    'four',
                                                                     writerSetAQuestions[3].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="writerSetAFourData"
                                                            :options="writerSetAFourChartOptions"
                                                            type="donut">

                                                        </apexchart>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- writer set a 5 -->
                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="font-weight-bold">
                                                                {{ writerSetAQuestions[4].question }}
                                                            </span>

                                                            <button
                                                                class="btn btn-primary"
                                                                title="View Question Answers Full Details"

                                                                @click="viewSurveyQuestionFullDetails(
                                                                    'a',
                                                                    'writer',
                                                                    'five',
                                                                     writerSetAQuestions[4].question)">

                                                                <i class="fas fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <apexchart
                                                            :series="writerSetAFiveData"
                                                            :options="writerSetAFiveChartOptions"
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

        <!-- Modal Buyer Survey A Questions -->
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
                        <h5 class="modal-title text-primary">{{ this.$t('message.survey_dashboard.bq_title') }}</h5>
                    </div>

                    <div class="modal-body">
                        <div
                            v-for="(question, index) in buyerSetAQuestions"
                            class="row mb-2 pb-2"
                            :class="index !== 4 ? 'border-bottom' : ''">

                            <div class="col-10 align-self-center">
                                <p class="mb-0 font-weight-bold">
                                    {{ index + 1 + '. ' + question.question }}
                                </p>
                            </div>

                            <div class="col-2">
                                <button
                                    class="btn btn-primary"
                                    :title="'View Question #' + (index + 1) + ' Details'"

                                    @click="viewSurveyQuestionFullDetails('a', 'buyer', question.number, question.question)">

                                    <i class="fas fa-list-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ this.$t('message.survey_dashboard.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Buyer Survey A Questions -->
        <div
            aria-hidden="true"
            aria-labelledby="modelTitleId"
            data-backdrop="static"
            tabindex="-1"
            role="dialog"
            class="modal fade"
            ref="surveyDetailsModal"
            id="modal-survey-questions-details"
            style="z-index: 9999">

            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">{{ surveyQuestionFullDetails.modalTitle }}</h5>
                    </div>

                    <div class="modal-body">

                        <div class="row px-3">
                            <div class="card col-12">
                                <div class="card-body">
                                    <strong>{{ this.$t('message.survey_dashboard.bq_question') }}:</strong>
                                    {{ surveyQuestionFullDetails.question }}
                                </div>
                            </div>
                        </div>

                        <div class="row px-3 mb-2">
                            <div class="col-6 px-0 align-self-center">
                                <b v-if="surveyQuestionFullDetails.pagination !== 'All'">
                                    {{ $t('message.others.table_entries', { from: surveyQuestionFullDetailsData.data.from, to: surveyQuestionFullDetailsData.data.to, end: surveyQuestionFullDetailsData.data.total }) }}
                                </b>

                                <b v-else>
                                    {{ $t('message.others.table_all_entries', { total: surveyQuestionFullDetailsData.data.total }) }}
                                </b>
                            </div>

                            <div class="col-6 px-0">
                                <select
                                    v-model="surveyQuestionFullDetails.pagination"
                                    class="form-control float-right ml-2"
                                    style="min-width: 100px; width: 100px"

                                    @change="getSurveyFullDetails">

                                    <option v-for="option in surveyQuestionPaginateOptions" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>

                                <export-excel
                                    worksheet="My Worksheet"
                                    :name="surveyQuestionFullDetails.type
                                    + '_survey_answers_set_'
                                    + surveyQuestionFullDetails.set
                                    + '_'
                                    + surveyQuestionFullDetails.number"
                                    class="btn btn-primary float-right"
                                    :data=sortDataForExport(surveyQuestionFullDetailsData.data.data,surveyQuestionFullDetails.number)>

                                    <i class="fa fa-list"></i>
                                    {{ this.$t('message.survey_dashboard.export_text') }}

                                </export-excel>
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="table-responsive mb-2">
                                <table
                                    id="tbl_survey_question_details"
                                    class="table table-hover table-bordered table-striped rlink-table">
                                    <thead>
                                    <tr>
                                        <th>{{ this.$t('message.survey_dashboard.t_user') }}</th>
                                        <th>{{ this.$t('message.survey_dashboard.t_answer') }}</th>
                                        <th>{{ this.$t('message.survey_dashboard.t_additional') }}</th>
                                        <th>{{ this.$t('message.survey_dashboard.t_overall_comment') }}</th>
                                        <th>{{ this.$t('message.survey_dashboard.t_date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(survey, index) in surveyQuestionFullDetailsData.data.data" :key="index">
                                        <td>
                                            <span v-if="survey.user">
                                                {{ survey.user.username }}
                                            </span>

                                            <span v-else class="badge badge-danger">
                                                N/A
                                            </span>
                                        </td>

                                        <td>
                                            {{ survey[surveyQuestionFullDetails.number] }}
                                        </td>

                                        <td>
                                            <span v-if="survey[surveyQuestionFullDetails.number + '_other']">
                                                {{ survey[surveyQuestionFullDetails.number + '_other'] }}
                                            </span>

                                            <span v-else class="badge badge-danger">
                                                N/A
                                            </span>
                                        </td>

                                        <td>
                                            <span v-if="survey.comment">
                                                {{ survey.comment }}
                                            </span>

                                            <span v-else class="badge badge-danger">
                                                N/A
                                            </span>
                                        </td>

                                        <td>
                                            {{ survey.created_at }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="surveyQuestionFullDetailsData.data"
                                :limit="8"

                                @pagination-change-page="getSurveyFullDetails">

                            </pagination>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ this.$t('message.survey_dashboard.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import set_a from "../data/set_a";
import set_a_2 from "../data/set_a_2";
import set_b from "../data/set_b";
import seller_set_a from "../data/seller_set_a";
import writer_set_a from "../data/writer_set_a";
import {stringManipulation} from "../../../mixins/stringManipulation";
import axios from "axios";

export default {
    name : "AdminSurvey",
    mixins: [stringManipulation],
    data() {
        return {
            surveys: {},
            surveyQuestionFullDetailsData: {
                data: {}
            },

            toggle: {
                buyer: {
                    id: 'buyer',
                    active: true,
                    label: this.$t('message.survey_dashboard.t_buyer')
                },

                seller: {
                    id: 'seller',
                    active: true,
                    label: this.$t('message.survey_dashboard.t_seller')
                },

                writer: {
                    id: 'writer',
                    active: true,
                    label: this.$t('message.survey_dashboard.t_writer')
                }
            },

            surveyQuestionFullDetails: {
                page: 1,
                set: null,
                type: null,
                number: null,
                question: null,
                pagination: 15,
                modalTitle: 'Survey Question Details',
            },

            surveyQuestionPaginateOptions : [
                15,
                25,
                50,
                100,
                200,
                250,
                'All'
            ],

            setAChartOptions: {},
            setAData: [],

            // buyer surveys - set a

            setAOneChartOptions: {},
            setAOneData: [],

            setATwoChartOptions: {},
            setATwoData: [],

            setAThreeChartOptions: {},
            setAThreeData: [],

            setAFourChartOptions: {},
            setAFourData: [],

            setAFiveChartOptions: {},
            setAFiveData: [],

            // buyer surveys - set b

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

            // seller surveys

            sellerSetAOneChartOptions: {},
            sellerSetAOneData: [],

            sellerSetATwoChartOptions: {},
            sellerSetATwoData: [],

            sellerSetAThreeChartOptions: {},
            sellerSetAThreeData: [],

            sellerSetAFourChartOptions: {},
            sellerSetAFourData: [],

            sellerSetAFiveChartOptions: {},
            sellerSetAFiveData: [],

            sellerSetASixChartOptions: {},
            sellerSetASixData: [],

            // writer surveys

            writerSetAOneChartOptions: {},
            writerSetAOneData: [],

            writerSetATwoChartOptions: {},
            writerSetATwoData: [],

            writerSetAThreeChartOptions: {},
            writerSetAThreeData: [],

            writerSetAFourChartOptions: {},
            writerSetAFourData: [],

            writerSetAFiveChartOptions: {},
            writerSetAFiveData: [],

            writerSetASixChartOptions: {},
            writerSetASixData: [],
        }
    },

    computed: {
        toggleText () {
            return {
                buyer: this.$t('message.survey_dashboard.t_buyer'),
                seller: this.$t('message.survey_dashboard.t_seller'),
                writer: this.$t('message.survey_dashboard.t_writer')
            }
        },

        buyerSetAQuestions () {
            return [
                {
                    number: 'one',
                    question: this.$t('message.buyer_survey.buyer_survey_a_q_1')
                },
                {
                    number: 'two',
                    question: this.$t('message.buyer_survey.buyer_survey_a_q_2')
                },
                {
                    number: 'three',
                    question: this.$t('message.buyer_survey.buyer_survey_a_q_3')
                },
                {
                    number: 'four',
                    question: this.$t('message.buyer_survey.buyer_survey_a_q_4')
                },
                {
                    number: 'five',
                    question: this.$t('message.buyer_survey.buyer_survey_a_q_5')
                },
            ];
        },

        buyerSetBQuestions () {
            return [
                {
                    number: 'one',
                    question: this.$t('message.buyer_survey.buyer_survey_b_q_1')
                },
                {
                    number: 'two',
                    question: this.$t('message.buyer_survey.buyer_survey_b_q_2')
                },
                {
                    number: 'three',
                    question: this.$t('message.buyer_survey.buyer_survey_b_q_3')
                },
                {
                    number: 'four',
                    question: this.$t('message.buyer_survey.buyer_survey_b_q_4')
                },
                {
                    number: 'five',
                    question: this.$t('message.buyer_survey.buyer_survey_b_q_5')
                },
            ];
        },

        sellerSetAQuestions () {
            return [
                {
                    number: 'one',
                    question: this.$t('message.seller_survey.seller_survey_a_q_1')
                },
                {
                    number: 'two',
                    question: this.$t('message.seller_survey.seller_survey_a_q_2')
                },
                {
                    number: 'three',
                    question: this.$t('message.seller_survey.seller_survey_a_q_3')
                },
                {
                    number: 'four',
                    question: this.$t('message.seller_survey.seller_survey_a_q_4')
                },
                {
                    number: 'five',
                    question: this.$t('message.seller_survey.seller_survey_a_q_5')
                },
                {
                    number: 'six',
                    question: this.$t('message.seller_survey.seller_survey_a_q_6')
                },
            ];
        },

        writerSetAQuestions () {
            return [
                {
                    number: 'one',
                    question: this.$t('message.writer_survey.writer_survey_a_q_1')
                },
                {
                    number: 'two',
                    question: this.$t('message.writer_survey.writer_survey_a_q_2')
                },
                {
                    number: 'three',
                    question: this.$t('message.writer_survey.writer_survey_a_q_3')
                },
                {
                    number: 'four',
                    question: this.$t('message.writer_survey.writer_survey_a_q_4')
                },
                {
                    number: 'five',
                    question: this.$t('message.writer_survey.writer_survey_a_q_5')
                },
            ];
        },
    },

    mounted() {
        this.getSurveyList();
        this.setAChartOptions = set_a.graphSetting();

        // buyer surveys

        this.setAOneChartOptions = set_a_2.graphSetting('one');
        this.setATwoChartOptions = set_a_2.graphSetting('two');
        this.setAThreeChartOptions = set_a_2.graphSetting('three');
        this.setAFourChartOptions = set_a_2.graphSetting('four');
        this.setAFiveChartOptions = set_a_2.graphSetting('five');

        this.setBOneChartOptions = set_b.graphSetting('one');
        this.setBTwoChartOptions = set_b.graphSetting('two');
        this.setBThreeChartOptions = set_b.graphSetting('three');
        this.setBFourChartOptions = set_b.graphSetting('four');
        this.setBFiveChartOptions = set_b.graphSetting('five');

        // seller surveys

        this.sellerSetAOneChartOptions = seller_set_a.graphSetting('one')
        this.sellerSetATwoChartOptions = seller_set_a.graphSetting('two')
        this.sellerSetAThreeChartOptions = seller_set_a.graphSetting('three')
        this.sellerSetAFourChartOptions = seller_set_a.graphSetting('four')
        this.sellerSetAFiveChartOptions = seller_set_a.graphSetting('five')
        this.sellerSetASixChartOptions = seller_set_a.graphSetting('six')

        // writer surveys

        this.writerSetAOneChartOptions = writer_set_a.graphSetting('one')
        this.writerSetATwoChartOptions = writer_set_a.graphSetting('two')
        this.writerSetAThreeChartOptions = writer_set_a.graphSetting('three')
        this.writerSetAFourChartOptions = writer_set_a.graphSetting('four')
        this.writerSetAFiveChartOptions = writer_set_a.graphSetting('five')
    },

    methods: {
        async getSurveyList () {
            await axios.get('/api/surveys')
                .then((response) => {
                    this.surveys = response.data;
                    this.setAData = set_a.graphData(this.surveys);

                    // buyer surveys
                    this.setAOneData = set_a_2.graphData(this.surveys, 'one');
                    this.setATwoData = set_a_2.graphData(this.surveys, 'two');
                    this.setAThreeData = set_a_2.graphData(this.surveys, 'three');
                    this.setAFourData = set_a_2.graphData(this.surveys, 'four');
                    this.setAFiveData = set_a_2.graphData(this.surveys, 'five');

                    this.setBOneData = set_b.graphData(this.surveys, 'one');
                    this.setBTwoData = set_b.graphData(this.surveys, 'two');
                    this.setBThreeData = set_b.graphData(this.surveys, 'three');
                    this.setBFourData = set_b.graphData(this.surveys, 'four');
                    this.setBFiveData = set_b.graphData(this.surveys, 'five');

                    // seller surveys

                    this.sellerSetAOneData = seller_set_a.graphData(this.surveys, 'one');
                    this.sellerSetATwoData = seller_set_a.graphData(this.surveys, 'two');
                    this.sellerSetAThreeData = seller_set_a.graphData(this.surveys, 'three');
                    this.sellerSetAFourData = seller_set_a.graphData(this.surveys, 'four');
                    this.sellerSetAFiveData = seller_set_a.graphData(this.surveys, 'five');
                    this.sellerSetASixData = seller_set_a.graphData(this.surveys, 'six');

                    // writer surveys

                    this.writerSetAOneData = writer_set_a.graphData(this.surveys, 'one');
                    this.writerSetATwoData = writer_set_a.graphData(this.surveys, 'two');
                    this.writerSetAThreeData = writer_set_a.graphData(this.surveys, 'three');
                    this.writerSetAFourData = writer_set_a.graphData(this.surveys, 'four');
                    this.writerSetAFiveData = writer_set_a.graphData(this.surveys, 'five');
                })
        },

        viewSurveyQuestionFullDetails (set, type, number, question) {
            this.surveyQuestionFullDetails.set = set;
            this.surveyQuestionFullDetails.type = type;
            this.surveyQuestionFullDetails.number = number;
            this.surveyQuestionFullDetails.question = question;
            this.surveyQuestionFullDetails.modalTitle = this.capitalizeFirstLetter(type)
            + ' Survey ' + this.capitalizeFirstLetter(set) + ' Question ' + this.capitalizeFirstLetter(number)
            + ' Details'

            this.getSurveyFullDetails()
        },

        getSurveyFullDetails (page = 1) {
            let loader = this.$loading.show();
            this.surveyQuestionFullDetails.page = page;

            axios.get('/api/survey/survey-question-full-details', {
                params: this.surveyQuestionFullDetails
            })
            .then((res) => {
                loader.hide();

                this.surveyQuestionFullDetailsData.data = res.data

                let table = $('#tbl_survey_question_details');

                table.DataTable().destroy();

                this.$nextTick(() => {
                    table.DataTable({
                        paging : false,
                        searching : false,
                        order: [],
                        columnDefs : {orderable : true, targets : '_all'},
                    });
                });

                // open survey modal
                let element = this.$refs.surveyDetailsModal
                $(element).modal('show')
            })
        },

        sortDataForExport (data, number) {
            if (data) {
                return data.map(function(item) {
                    return {
                        user: item.user ? item.user.username : 'N/A',
                        answer: item[number],
                        additional: item[number + '_other'],
                        'overall comment': item.comment,
                        date: item.created_at
                    };
                });
            }
        }
    },
}
</script>

<style scoped>

</style>
