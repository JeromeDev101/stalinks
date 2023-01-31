<template>
    <div :class="{'homepage-login': survey.answers.code}">
        <!-- language -->
        <div v-if="survey.answers.code" class="row p-2">
            <div class="col-12 d-flex justify-content-end">
                <div>
                    <button
                        type="button"
                        class="btn btn-default btn-sm dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <i
                            class="flag-icon"
                            :class="pageLanguage === 'en'
                                ? 'flag-icon-us'
                                    : pageLanguage === 'ind'
                                    ? 'flag-icon-in'
                                        : 'flag-icon-' + pageLanguage">
                        </i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'en'}"

                            @click="pageLanguage = 'en'">

                            <i class="flag-icon flag-icon-us mr-2"></i>
                            English
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'jp'}"

                            @click="pageLanguage = 'jp'">
                            <i class="flag-icon flag-icon-jp mr-2"></i>
                            Japanese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'th'}"

                            @click="pageLanguage = 'th'">

                            <i class="flag-icon flag-icon-th mr-2"></i>
                            Thai
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'vn'}"

                            @click="pageLanguage = 'vn'">

                            <i class="flag-icon flag-icon-vn mr-2"></i>
                            Vietnamese
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'ind'}"

                            @click="pageLanguage = 'ind'">

                            <i class="flag-icon flag-icon-in mr-2"></i>
                            Hindi
                        </a>
                        <a
                            href="#"
                            class="dropdown-item"
                            :class="{'active' : pageLanguage === 'id'}"

                            @click="pageLanguage = 'id'">

                            <i class="flag-icon flag-icon-id mr-2"></i>
                            Indonesian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mx-5 my-5">
            <div v-if="!survey.isCodeError" class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- SURVEY -->
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-header">
                        <h3 class="card-title text-primary my-3" style="font-size: 2.5vw">
                            {{ $t('message.writer_survey.s_title') }} {{ survey.answers.set === 'a' ? $t('message.writer_survey.a') : $t('message.writer_survey.b') }}
                        </h3>

                        <div class="card-tools">
                        </div>
                    </div>

                    <div v-if="!isSetAAnswered && !isSetBAnswered" class="card-body">
                        <!-- SURVEY A -->
                        <div v-if="survey.answers.set === 'a'" class="row">
                            <!-- a1 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_1') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','one')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.one"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.one === 'no'" class="form-group">
                                    <label>{{ $t('message.writer_survey.improve_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.one_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.one">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.one"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                
                                <template v-if="writerSurveyErrors.errors.one_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.one_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a2 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_2') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','two')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.two"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.two === 'no'" class="form-group">
                                    <label>{{ $t('message.writer_survey.improve_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.two_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.two">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.two"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                

                                <template v-if="writerSurveyErrors.errors.two_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.two_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a3 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_3') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','three')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.three"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.three === 'yes'" class="form-group">
                                    <label>{{ $t('message.writer_survey.improve_yes') }}</label>

                                    <textarea
                                        v-model="survey.answers.three_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.three">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.three"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                

                                <template v-if="writerSurveyErrors.errors.three_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.three_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a4 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_4') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','four')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.four"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>

                                <div v-if="survey.answers.four === 'Dissatisfied'" class="form-group">
                                    <label>{{ $t('message.seller_survey.improve_dissatisfied') }}</label>

                                    <textarea
                                        v-model="survey.answers.four_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.four">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.four"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                

                                <template v-if="writerSurveyErrors.errors.four_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.four_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a5 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_5') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','five')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.five"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>

                                <div v-if="survey.answers.five === 'no'" class="form-group">
                                    <label>{{ $t('message.writer_survey.improve_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.five_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.five">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.five"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                
                                <template v-if="writerSurveyErrors.errors.five_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.five_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a6 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.writer_survey.writer_survey_a_q_6') }}
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in writerSurveyAnswers('a','six')"
                                        class="custom-control custom-radio"
                                        :key="answerSetAOne.id">

                                        <input
                                            v-model="survey.answers.six"
                                            :value="answerSetAOne.value"
                                            type="radio"
                                            :name="answerSetAOne.name"
                                            :id="answerSetAOne.id"
                                            class="custom-control-input">

                                        <label :for="answerSetAOne.id" class="custom-control-label font-weight-normal">
                                            {{ answerSetAOne.label }}
                                        </label>
                                    </div>
                                </div>

                                <div v-if="survey.answers.six === 'Others'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.other') }}</label>

                                    <textarea
                                        v-model="survey.answers.six_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.six">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.six"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>

                                <template v-if="writerSurveyErrors.errors.six_other">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.six_other"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>
                                <hr/>
                            </div>

                            <!-- a comment -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>
                                        {{ $t('message.seller_survey.seller_survey_aside') }}
                                    </label>

                                    <textarea
                                        v-model="survey.answers.comment"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.writer_survey.enter')">

                                    </textarea>
                                </div>

                                <template v-if="writerSurveyErrors.errors.comment">
                                    <span
                                        v-for="err in writerSurveyErrors.errors.comment"
                                        class="text-danger"
                                        :key="err">

                                        {{ err }}
                                    </span>
                                </template>

                                <hr/>
                            </div>
                        </div>

                        <!-- SURVEY B -->
                        <div v-else class="row">

                        </div>

                        <div class="row justify-content-center">
                            <button
                                type="button"
                                class="btn btn-primary"

                                @click="submitSurvey">
                                {{ $t('message.writer_survey.submit') }}
                            </button>
                        </div>
                    </div>

                    <!-- if set is already answered -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-check-circle fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">{{ $t('message.writer_survey.thanks_1') }}</h3>
                        <p>{{ $t('message.writer_survey.thanks_2') }}</p>
                    </div>
                </div>
            </div>

            <div v-else class="col-12 col-md-12 col-lg-8 col-xl-6">
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-exclamation-triangle fa-10x text-danger"></i>
                        </div>

                        <h3 class="text-center mt-5 text-danger">{{ $t('message.writer_survey.errors_1') }}</h3>
                        <p>{{ $t('message.writer_survey.errors_2') }}</p>

                        <button class="btn btn-default btn-lg btn-block mt-5" @click="redirectToLogin()">
                            {{ $t('message.writer_survey.login') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: [],

    data() {
        return {
            isSetAAnswered: false,
            isSetBAnswered: false,

            // model
            survey : {
                show : false,
                isCodeError: false,
                answers : {
                    one : null,
                    two : null,
                    three : null,
                    four: null,
                    five : null,
                    six: null,
                    one_other: '',
                    two_other: '',
                    three_other: '',
                    four_other: '',
                    five_other: '',
                    six_other: '',
                    comment: null,
                    set : 'a',
                    code: null,
                    type: 'writer'
                }
            },

            pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',

            writerSurveyErrors: {
                message: '',
                errors: {}
            }
        }
    },

    watch : {
        pageLanguage(newvalue, oldValue) {
            this.$i18n.locale = newvalue;
        },
    },

    created() {
        this.checkRouteParams();
    },

    methods: {
        writerSurveyAnswers(set, number) {
            let self = this;
            let answers = {};

            if (set === 'a') {
                answers = {
                    one: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: self.$t('message.writer_survey.yes')
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: self.$t('message.writer_survey.no')
                        }
                    ],

                    two: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: self.$t('message.writer_survey.yes')
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: self.$t('message.writer_survey.no')
                        }
                    ],

                    three: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: self.$t('message.writer_survey.yes')
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: self.$t('message.writer_survey.no')
                        }
                    ],

                    four: [
                        {
                            id: 'question-' + number + 'a-a',
                            name: 'question-' + number + 'a-a',
                            value: 'Satisfied',
                            label: self.$t('message.seller_survey.seller_survey_a_q_5_a')
                        },
                        {
                            id: 'question-' + number + 'a-b',
                            name: 'question-' + number + 'a-b',
                            value: 'Dissatisfied',
                            label: self.$t('message.seller_survey.seller_survey_a_q_5_b')
                        },

                        // {
                        //     id: 'question-' + number + 'a-a',
                        //     name: 'question-' + number + 'a-a',
                        //     value: 'Extremely dissatisfied',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_4_a')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-b',
                        //     name: 'question-' + number + 'a-b',
                        //     value: 'Somewhat dissatisfied',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_4_b')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-c',
                        //     name: 'question-' + number + 'a-c',
                        //     value: 'Neither',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_4_c')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-d',
                        //     name: 'question-' + number + 'a-d',
                        //     value: 'Somewhat satisfied',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_4_d')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-e',
                        //     name: 'question-' + number + 'a-e',
                        //     value: 'Extremely satisfied',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_4_e')
                        // }
                    ],

                    five: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: self.$t('message.writer_survey.yes')
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: self.$t('message.writer_survey.no')
                        }

                        // {
                        //     id: 'question-' + number + 'a-a',
                        //     name: 'question-' + number + 'a-a',
                        //     value: '1-4 I will not recommend to anyone',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_5_a')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-b',
                        //     name: 'question-' + number + 'a-b',
                        //     value: '5-7 I will consider it',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_5_b')
                        // },
                        // {
                        //     id: 'question-' + number + 'a-c',
                        //     name: 'question-' + number + 'a-c',
                        //     value: '7-10 I will definitely recommend to my connections to use your services',
                        //     label: self.$t('message.writer_survey.writer_survey_a_q_5_c')
                        // }
                    ],

                    six: [
                        {
                            id: 'question-' + number + 'a-a',
                            name: 'question-' + number + 'a-a',
                            value: 'Facebook',
                            label: self.$t('message.writer_survey.writer_survey_a_q_6_fb')
                        },
                        {
                            id: 'question-' + number + 'a-b',
                            name: 'question-' + number + 'a-b',
                            value: 'LinkedIn',
                            label: self.$t('message.writer_survey.writer_survey_a_q_6_li')
                        },
                        {
                            id: 'question-' + number + 'a-c',
                            name: 'question-' + number + 'a-c',
                            value: 'Others',
                            label: self.$t('message.writer_survey.writer_survey_a_q_6_others')
                        }
                    ],
                }
            }

            return answers[number];
        },

        checkRouteParams () {
            this.survey.answers.set = this.$route.params.set;

            if (this.$route.params.code) {
                this.survey.answers.code = this.$route.params.code;

                this.checkSurveyCode();
            }

            this.hasUserAnsweredSurveySet();
        },

        submitSurvey () {
            let self = this;

            let path = this.survey.answers.code === null ? '/api/survey' : '/api/survey-code'

            axios.post(path, this.survey.answers)
                .then((response) => {

                    swal.fire(
                        self.$t('message.writer_survey.alert_success'),
                        self.$t('message.writer_survey.alert_success_text'),
                        'success',
                    )

                    this.hasUserAnsweredSurveySet();
                })
                .catch((err) => {
                    swal.fire(
                        self.$t('message.writer_survey.alert_error'),
                        err.response.data.message,
                        'error',
                    )

                    self.writerSurveyErrors = err.response.data;

                    console.log(err.response.data.message)
                });
        },

        hasUserAnsweredSurveySet () {

            let path = this.survey.answers.code === null
                ? '/api/survey/check-survey-set'
                : '/api/survey/check-survey-code-set'

            axios.post(path, {
                set: this.survey.answers.set,
                code: this.survey.answers.code,
                type: 'writer'
            })
                .then((response) => {
                    if (this.survey.answers.set === 'a') {
                        this.isSetAAnswered = Object.keys(response.data).length !== 0;
                    } else {
                        this.isSetBAnswered = Object.keys(response.data).length !== 0;
                    }
                });
        },

        checkSurveyCode () {
            axios.post('/api/survey/check-survey-code', {
                code: this.survey.answers.code
            })
                .then((response) => {
                    this.survey.isCodeError = Object.keys(response.data).length === 0;
                });
        },

        redirectToLogin () {
            window.location.assign('http://' + window.location.hostname + '/login');
        },
    },
}
</script>

<style scoped>

</style>
