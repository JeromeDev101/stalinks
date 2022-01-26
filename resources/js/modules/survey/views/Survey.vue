<template>
    <div :class="{'homepage-login': survey.answers.code}">
        <div class="row justify-content-center mx-5 my-5">
            <div v-if="!survey.isCodeError" class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- SURVEY -->
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-header">
                        <h3 class="card-title text-primary my-3" style="font-size: 2.5vw">
                            Stalinks Survey {{ survey.answers.set === 'a' ? 'A' : 'B' }}
                        </h3>

                        <div class="card-tools">
                        </div>
                    </div>

                    <div v-if="!isSetAAnswered && !isSetBAnswered" class="card-body">
                        <!-- SURVEY A -->
                        <div v-if="survey.answers.set === 'a'" class="row">
                            <!-- a1 -->
                            <div class="col-12">
                                <p class="font-weight-bold">1. Do you find using our system efficient?</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            value="yes"
                                            type="radio"
                                            name="question-one-a"
                                            id="question-one-a-yes"
                                            class="custom-control-input">

                                        <label for="question-one-a-yes" class="custom-control-label font-weight-normal">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            value="no"
                                            type="radio"
                                            name="question-one-a"
                                            id="question-one-a-no"
                                            class="custom-control-input">

                                        <label for="question-one-a-no" class="custom-control-label font-weight-normal">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.one === 'no'" class="form-group">
                                    <label>If no, please let us know how we can improve it…</label>

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
                                    2. Does our system provide the URLs that you are looking for?
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.two"
                                            value="yes"
                                            type="radio"
                                            name="question-two-a"
                                            id="question-two-a-yes"
                                            class="custom-control-input">

                                        <label for="question-two-a-yes" class="custom-control-label font-weight-normal">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.two"
                                            value="no"
                                            type="radio"
                                            name="question-two-a"
                                            id="question-two-a-no"
                                            class="custom-control-input">

                                        <label for="question-two-a-no" class="custom-control-label font-weight-normal">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.two === 'no'" class="form-group">
                                    <label>If no, please let us know what more to add…</label>

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
                                <p class="font-weight-bold">3. Do you find our HELP page easy to follow?</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.three"
                                            value="yes"
                                            type="radio"
                                            name="question-three-a"
                                            id="question-three-a-yes"
                                            class="custom-control-input">

                                        <label for="question-three-a-yes" class="custom-control-label font-weight-normal">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.three"
                                            value="no"
                                            type="radio"
                                            name="question-three-a"
                                            id="question-three-a-no"
                                            class="custom-control-input">

                                        <label for="question-three-a-no" class="custom-control-label font-weight-normal">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.three === 'no'" class="form-group">
                                    <label>If no, please let us know how we can improve it...</label>

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
                                    4. Do you find our payment methods too complicated for you?
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            value="yes"
                                            type="radio"
                                            name="question-four-a"
                                            id="question-four-a-yes"
                                            class="custom-control-input">

                                        <label for="question-four-a-yes" class="custom-control-label font-weight-normal">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            value="no"
                                            type="radio"
                                            name="question-four-a"
                                            id="question-four-a-no"
                                            class="custom-control-input">

                                        <label for="question-four-a-no" class="custom-control-label font-weight-normal">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.four === 'yes'" class="form-group">
                                    <label>
                                        If yes, please let us know other methods you preferred to make the transaction
                                        easier for you
                                    </label>

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
                                    5. Are you satisfied with the service that we provide right now?
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.five"
                                            value="yes"
                                            type="radio"
                                            name="question-five-a"
                                            id="question-five-a-yes"
                                            class="custom-control-input">

                                        <label for="question-five-a-yes" class="custom-control-label font-weight-normal">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.five"
                                            value="no"
                                            type="radio"
                                            name="question-five-a"
                                            id="question-five-a-no"
                                            class="custom-control-input">

                                        <label for="question-five-a-no" class="custom-control-label font-weight-normal">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.five === 'no'" class="form-group">
                                    <label>If no, please let us know what can we improve</label>

                                    <textarea
                                        v-model="survey.answers.five_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
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
                            <!-- b1 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    1. In your opinion, what is the most important thing to have in desk management?
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            type="radio"
                                            id="question-one-b-a"
                                            name="question-one-b"
                                            value="Complete URLs info"
                                            class="custom-control-input">

                                        <label for="question-one-b-a" class="custom-control-label font-weight-normal">
                                            Complete URLs info
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            type="radio"
                                            id="question-one-b-b"
                                            name="question-one-b"
                                            value="Good user interface"
                                            class="custom-control-input">

                                        <label for="question-one-b-b" class="custom-control-label font-weight-normal">
                                            Good user interface
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            type="radio"
                                            id="question-one-b-c"
                                            name="question-one-b"
                                            value="Secured transaction"
                                            class="custom-control-input">

                                        <label for="question-one-b-c" class="custom-control-label font-weight-normal">
                                            Secured transaction
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            type="radio"
                                            value="Good price"
                                            id="question-one-b-d"
                                            name="question-one-b"
                                            class="custom-control-input">

                                        <label for="question-one-b-d" class="custom-control-label font-weight-normal">
                                            Good price
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.one"
                                            type="radio"
                                            value="other"
                                            id="question-one-b-e"
                                            name="question-one-b"
                                            class="custom-control-input">

                                        <label for="question-one-b-e" class="custom-control-label font-weight-normal">
                                            Others. Please specify...
                                        </label>
                                    </div>

                                </div>
                                <div v-if="survey.answers.one === 'other'" class="form-group">
                                    <label>Other</label>

                                    <textarea
                                        v-model="survey.answers.one_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- b2 -->
                            <div class="col-12">
                                <p class="font-weight-bold">2. From 1-10, how would you rate our tools?</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.two"
                                            type="radio"
                                            id="question-two-b-a"
                                            name="question-two-b"
                                            class="custom-control-input"
                                            value="1-> easy to understand">

                                        <label for="question-two-b-a" class="custom-control-label font-weight-normal">
                                            1-> easy to understand
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.two"
                                            type="radio"
                                            name="question-two-b"
                                            id="question-two-b-no"
                                            class="custom-control-input"
                                            value="10-> difficult to understand">

                                        <label for="question-two-b-no" class="custom-control-label font-weight-normal">
                                            10-> difficult to understand
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Comment</label>

                                    <textarea
                                        v-model="survey.answers.two_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- b3 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    3. From 1-10, how well do you understand Stalinks and what can we do to help you?
                                </p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.three"
                                            type="radio"
                                            name="question-three-b"
                                            id="question-three-b-a"
                                            class="custom-control-input"
                                            value="1-> completely clueless">

                                        <label for="question-three-b-a" class="custom-control-label font-weight-normal">
                                            1-> completely clueless
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.three"
                                            type="radio"
                                            id="question-three-b-b"
                                            name="question-three-b"
                                            class="custom-control-input"
                                            value="10-> understand completely">

                                        <label for="question-three-b-b" class="custom-control-label font-weight-normal">
                                            10-> understand completely
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Comment</label>

                                    <textarea
                                        v-model="survey.answers.three_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- b4 -->
                            <div class="col-12">
                                <p class="font-weight-bold">4. What would you like to see in Stalinks?</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            type="radio"
                                            id="question-four-b-a"
                                            name="question-four-b"
                                            value="More promotion"
                                            class="custom-control-input">

                                        <label for="question-four-b-a" class="custom-control-label font-weight-normal">
                                            More promotion
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            type="radio"
                                            id="question-four-b-b"
                                            name="question-four-b"
                                            class="custom-control-input"
                                            value="Detailed guide on how to use the tools">

                                        <label for="question-four-b-b" class="custom-control-label font-weight-normal">
                                            Detailed guide on how to use the tools
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            type="radio"
                                            id="question-four-b-c"
                                            name="question-four-b"
                                            class="custom-control-input"
                                            value="Personalized help choosing the right URLs">

                                        <label for="question-four-b-c" class="custom-control-label font-weight-normal">
                                            Personalized help choosing the right URLs
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.four"
                                            type="radio"
                                            value="other"
                                            id="question-four-b-d"
                                            name="question-four-b"
                                            class="custom-control-input">

                                        <label for="question-four-b-d" class="custom-control-label font-weight-normal">
                                            Others. Please specify
                                        </label>
                                    </div>

                                </div>
                                <div v-if="survey.answers.four === 'other'" class="form-group">
                                    <label>Other</label>

                                    <textarea
                                        v-model="survey.answers.four_other"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Enter ...">

                                    </textarea>
                                </div>

                                <hr/>
                            </div>

                            <!-- b5 -->
                            <div class="col-12">
                                <p class="font-weight-bold">5. How would you rate our team interaction with you?</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.five"
                                            type="radio"
                                            name="question-five-b"
                                            id="question-five-b-yes"
                                            value="1-> Disappointed"
                                            class="custom-control-input">

                                        <label for="question-five-b-yes" class="custom-control-label font-weight-normal">
                                            1-> Disappointed
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.five"
                                            type="radio"
                                            name="question-five-b"
                                            id="question-five-b-no"
                                            class="custom-control-input"
                                            value="10-> Very satisfied, would recommend">

                                        <label for="question-five-b-no" class="custom-control-label font-weight-normal">
                                            10-> Very satisfied, would recommend
                                        </label>
                                    </div>
                                </div>

                                <hr/>
                            </div>

                            <!-- b comment -->
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
                            </div>
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

            <div v-else class="col-5">
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
                    one_other: '',
                    two_other: '',
                    three_other: '',
                    four_other: '',
                    five_other: '',
                    comment: null,
                    set : 'a',
                    code: null
                }
            }
        }
    },

    created() {
        this.checkRouteParams();
    },

    methods: {
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
                code: this.survey.answers.code
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
