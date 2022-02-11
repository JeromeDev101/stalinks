<template>
    <div :class="{'homepage-login': survey.answers.code}">
        <div class="row justify-content-center mx-5 my-5">
            <div v-if="!survey.isCodeError" class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- SURVEY -->
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-header">
                        <h3 class="card-title text-primary my-3" style="font-size: 2.5vw">
                            Stalinks Seller Survey {{ survey.answers.set === 'a' ? 'A' : 'B' }}
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
                                    1. Do you think our system is efficient for you to sell your guest posts?
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','one')"
                                        class="custom-control custom-radio">

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
                                    <label>If no, please let us know why and how we can improve...</label>

                                    <textarea
                                        v-model="survey.answers.one_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- a2 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    2. Are you satisfied with our CS Team service?
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','two')"
                                        class="custom-control custom-radio">

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
                                    <label>If no, please let us know why and how we can improve...</label>

                                    <textarea
                                        v-model="survey.answers.two_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- a3 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    3. Do you have any problems using our payment methods?
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','three')"
                                        class="custom-control custom-radio">

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
                                    <label>If yes, please let us know why and how we can improve...</label>

                                    <textarea
                                        v-model="survey.answers.three_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- a4 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    4. Do you find our platform helpful to you in getting orders easily?
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','four')"
                                        class="custom-control custom-radio">

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
                                <div v-if="survey.answers.four === 'no'" class="form-group">
                                    <label>If no, please let us know why and how we can improve...</label>

                                    <textarea
                                        v-model="survey.answers.four_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- a5 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    5. Based on your most recent experience, please rate your satisfaction by using our service.
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','five')"
                                        class="custom-control custom-radio">

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

                                <hr/>
                            </div>

                            <!-- a6 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    6. Please rate if you would recommend us and our services to your connections.
                                </p>
                                <div class="form-group">
                                    <div
                                        v-for="answerSetAOne in sellerSurveyAnswers('a','six')"
                                        class="custom-control custom-radio">

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

                                <hr/>
                            </div>

                            <!-- a comment -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>
                                        Aside from the issues you have already mentioned above, are there any other
                                        comments you would like to share?
                                    </label>

                                    <textarea
                                        v-model="survey.answers.comment"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

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
                                Submit
                            </button>
                        </div>
                    </div>

                    <!-- if set is already answered -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-check-circle fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">Thank you for answering this survey!</h3>
                        <p>This would help us a lot to understand your needs even better!</p>
                    </div>
                </div>
            </div>

            <div v-else class="col-12 col-md-12 col-lg-8 col-xl-6">
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-exclamation-triangle fa-10x text-danger"></i>
                        </div>

                        <h3 class="text-center mt-5 text-danger">Survey Code Not Found</h3>
                        <p>Contact an administrator to get the correct survey link</p>

                        <button class="btn btn-default btn-lg btn-block mt-5" @click="redirectToLogin()">
                            Go to Login
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
                    type: 'seller'
                }
            },
        }
    },

    created() {
        this.checkRouteParams();
    },

    methods: {
        sellerSurveyAnswers(set, number) {
            let answers = {};

            if (set === 'a') {
                answers = {
                    one: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: 'Yes'
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: 'No'
                        }
                    ],

                    two: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: 'Yes'
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: 'No'
                        }
                    ],

                    three: [
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'No, I am okay with the current payment methods options',
                            label: 'No, I am okay with the current payment methods options'
                        },
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: 'Yes'
                        }
                    ],

                    four: [
                        {
                            id: 'question-' + number + 'a-yes',
                            name: 'question-' + number + 'a-yes',
                            value: 'yes',
                            label: 'Yes'
                        },
                        {
                            id: 'question-' + number + 'a-no',
                            name: 'question-' + number + 'a-no',
                            value: 'no',
                            label: 'No'
                        }
                    ],

                    five: [
                        {
                            id: 'question-' + number + 'a-a',
                            name: 'question-' + number + 'a-a',
                            value: 'Extremely dissatisfied',
                            label: 'Extremely dissatisfied'
                        },
                        {
                            id: 'question-' + number + 'a-b',
                            name: 'question-' + number + 'a-b',
                            value: 'Somewhat dissatisfied',
                            label: 'Somewhat dissatisfied'
                        },
                        {
                            id: 'question-' + number + 'a-c',
                            name: 'question-' + number + 'a-c',
                            value: 'Neither',
                            label: 'Neither'
                        },
                        {
                            id: 'question-' + number + 'a-d',
                            name: 'question-' + number + 'a-d',
                            value: 'Somewhat satisfied',
                            label: 'Somewhat satisfied'
                        },
                        {
                            id: 'question-' + number + 'a-e',
                            name: 'question-' + number + 'a-e',
                            value: 'Extremely satisfied',
                            label: 'Extremely satisfied'
                        }
                    ],

                    six: [
                        {
                            id: 'question-' + number + 'a-a',
                            name: 'question-' + number + 'a-a',
                            value: '1-4 I will not recommend to anyone',
                            label: '1-4 I will not recommend to anyone'
                        },
                        {
                            id: 'question-' + number + 'a-b',
                            name: 'question-' + number + 'a-b',
                            value: '5-7 I will consider it',
                            label: '5-7 I will consider it'
                        },
                        {
                            id: 'question-' + number + 'a-c',
                            name: 'question-' + number + 'a-c',
                            value: '7-10 I will definitely recommend to my connections to use your services',
                            label: '7-10 I will definitely recommend to my connections to use your services'
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

            let path = this.survey.answers.code === null ? '/api/survey' : '/api/survey-code'

            axios.post(path, this.survey.answers)
                .then((response) => {

                    swal.fire(
                        'Success',
                        'Thank you for your time! This means a lot for us to understand your needs even better!',
                        'success',
                    )

                    this.hasUserAnsweredSurveySet();
                })
                .catch((err) => {
                    swal.fire(
                        'Error',
                        err.response.data.message,
                        'error',
                    )

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
                type: 'seller'
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
