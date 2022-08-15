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
                            {{ $t('message.buyer_survey.s_title') }} {{ survey.answers.set === 'a' ? $t('message.buyer_survey.a') : $t('message.buyer_survey.b') }}
                        </h3>

                        <div class="card-tools">
                        </div>
                    </div>

                    <div v-if="!isSetAAnswered && !isSetBAnswered" class="card-body">
                        <!-- SURVEY A -->
                        <div v-if="survey.answers.set === 'a'" class="row">
                            <!-- a1 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_a_q_1') }}</p>
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
                                            {{ $t('message.buyer_survey.yes') }}
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
                                            {{ $t('message.buyer_survey.no') }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.one === 'no'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.buyer_survey_a_q_1_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.one_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.one"
                                    v-for="err in surveyErrors.errors.one"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.one_other"
                                    v-for="err in surveyErrors.errors.one_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- a2 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.buyer_survey.buyer_survey_a_q_2') }}
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
                                            {{ $t('message.buyer_survey.yes') }}
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
                                            {{ $t('message.buyer_survey.no') }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.two === 'no'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.buyer_survey_a_q_2_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.two_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.two"
                                    v-for="err in surveyErrors.errors.two"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.two_other"
                                    v-for="err in surveyErrors.errors.two_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- a3 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_a_q_3') }}</p>
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
                                            {{ $t('message.buyer_survey.yes') }}
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
                                            {{ $t('message.buyer_survey.no') }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.three === 'no'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.buyer_survey_a_q_1_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.three_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.three"
                                    v-for="err in surveyErrors.errors.three"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.three_other"
                                    v-for="err in surveyErrors.errors.three_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- a4 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.buyer_survey.buyer_survey_a_q_4') }}
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
                                            {{ $t('message.buyer_survey.yes') }}
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
                                            {{ $t('message.buyer_survey.no') }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.four === 'yes'" class="form-group">
                                    <label>
                                        {{ $t('message.buyer_survey.buyer_survey_a_q_4_yes') }}
                                    </label>

                                    <textarea
                                        v-model="survey.answers.four_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.four"
                                    v-for="err in surveyErrors.errors.four"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.four_other"
                                    v-for="err in surveyErrors.errors.four_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- a5 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.buyer_survey.buyer_survey_a_q_5') }}
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
                                            {{ $t('message.buyer_survey.yes') }}
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
                                            {{ $t('message.buyer_survey.no') }}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="survey.answers.five === 'no'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.buyer_survey_a_q_5_no') }}</label>

                                    <textarea
                                        v-model="survey.answers.five_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.five"
                                    v-for="err in surveyErrors.errors.five"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.five_other"
                                    v-for="err in surveyErrors.errors.five_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- a comment -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>
                                        {{ $t('message.buyer_survey.buyer_survey_aside') }}
                                    </label>

                                    <textarea
                                        v-model="survey.answers.comment"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

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
                                    {{ $t('message.buyer_survey.buyer_survey_b_q_1') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_a') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_b') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_c') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_d') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_others') }}
                                        </label>
                                    </div>

                                </div>
                                <div v-if="survey.answers.one === 'other'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.other') }}</label>

                                    <textarea
                                        v-model="survey.answers.one_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.one"
                                    v-for="err in surveyErrors.errors.one"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.one_other"
                                    v-for="err in surveyErrors.errors.one_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b2 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_b_q_2') }}</p>
                                <div class="form-group">
                                    <div v-for="answer in surveyBTwoThreeOptions('two')" class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.two"
                                            type="radio"
                                            :name="answer.name"
                                            :id="answer.id"
                                            class="custom-control-input"
                                            :value="answer.value">

                                        <label :for="answer.id" class="custom-control-label font-weight-normal">
                                            {{ answer.label }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $t('message.buyer_survey.comment') }}</label>

                                    <textarea
                                        v-model="survey.answers.two_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.two"
                                    v-for="err in surveyErrors.errors.two"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.two_other"
                                    v-for="err in surveyErrors.errors.two_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b3 -->
                            <div class="col-12">
                                <p class="font-weight-bold">
                                    {{ $t('message.buyer_survey.buyer_survey_b_q_3') }}
                                </p>
                                <div class="form-group">
                                    <div v-for="answer in surveyBTwoThreeOptions('three')" class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.three"
                                            type="radio"
                                            :name="answer.name"
                                            :id="answer.id"
                                            class="custom-control-input"
                                            :value="answer.value">

                                        <label :for="answer.id" class="custom-control-label font-weight-normal">
                                            {{ answer.label }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $t('message.buyer_survey.comment') }}</label>

                                    <textarea
                                        v-model="survey.answers.three_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.three"
                                    v-for="err in surveyErrors.errors.three"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.three_other"
                                    v-for="err in surveyErrors.errors.three_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b4 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_b_q_4') }}</p>
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_4_a') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_4_b') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_4_c') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_1_others') }}
                                        </label>
                                    </div>

                                </div>
                                <div v-if="survey.answers.four === 'other'" class="form-group">
                                    <label>{{ $t('message.buyer_survey.other') }}</label>

                                    <textarea
                                        v-model="survey.answers.four_other"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.four"
                                    v-for="err in surveyErrors.errors.four"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.four_other"
                                    v-for="err in surveyErrors.errors.four_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b5 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_b_q_5') }}</p>
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_5_1') }}
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
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_5_10') }}
                                        </label>
                                    </div>
                                </div>

                                <span
                                    v-if="surveyErrors.errors.five"
                                    v-for="err in surveyErrors.errors.five"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.five_other"
                                    v-for="err in surveyErrors.errors.five_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b6 -->
                            <div class="col-12">
                                <p class="font-weight-bold">{{ $t('message.buyer_survey.buyer_survey_b_q_6') }}</p>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.six"
                                            type="radio"
                                            id="question-six-b-a"
                                            name="question-six-b"
                                            value="Facebook"
                                            class="custom-control-input">

                                        <label for="question-six-b-a" class="custom-control-label font-weight-normal">
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_6_fb') }}
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.six"
                                            type="radio"
                                            id="question-six-b-b"
                                            name="question-six-b"
                                            class="custom-control-input"
                                            value="LinkedIn">

                                        <label for="question-six-b-b" class="custom-control-label font-weight-normal">
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_6_li') }}
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            v-model="survey.answers.six"
                                            type="radio"
                                            value="Others"
                                            id="question-six-b-c"
                                            name="question-six-b"
                                            class="custom-control-input">

                                        <label for="question-six-b-c" class="custom-control-label font-weight-normal">
                                            {{ $t('message.buyer_survey.buyer_survey_b_q_6_others') }}
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

                                <span
                                    v-if="surveyErrors.errors.six"
                                    v-for="err in surveyErrors.errors.six"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <span
                                    v-if="surveyErrors.errors.six_other"
                                    v-for="err in surveyErrors.errors.six_other"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <hr/>
                            </div>

                            <!-- b comment -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label>
                                        {{ $t('message.buyer_survey.buyer_survey_aside') }}
                                    </label>

                                    <textarea
                                        v-model="survey.answers.comment"
                                        rows="3"
                                        class="form-control"
                                        :placeholder="$t('message.buyer_survey.enter')">

                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <button
                                type="button"
                                class="btn btn-primary"

                                @click="submitSurvey">
                                {{ $t('message.buyer_survey.submit') }}
                            </button>
                        </div>
                    </div>

                    <!-- if set is already answered -->
                    <div v-else class="card-body text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-check-circle fa-10x text-success"></i>
                        </div>

                        <h3 class="text-center mt-5 text-success">{{ $t('message.buyer_survey.thanks_1') }}</h3>
                        <p>{{ $t('message.buyer_survey.thanks_2') }}</p>
                    </div>
                </div>
            </div>

            <div v-else class="col-12 col-md-12 col-lg-8 col-xl-6">
                <div class="card card-outline card-secondary align-items-center">
                    <div class="card-body mx-5 text-center">
                        <div class="my-3 text-center">
                            <i class="fas fa-exclamation-triangle fa-10x text-danger"></i>
                        </div>

                        <h3 class="text-center mt-5 text-danger">{{ $t('message.buyer_survey.errors_1') }}</h3>
                        <p>{{ $t('message.buyer_survey.errors_2') }}</p>

                        <button class="btn btn-default btn-lg btn-block mt-5" @click="redirectToLogin()">
                            {{ $t('message.buyer_survey.login') }}
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
                    six : null,
                    one_other: '',
                    two_other: '',
                    three_other: '',
                    four_other: '',
                    five_other: '',
                    six_other: '',
                    comment: null,
                    set : 'a',
                    code: null,
                    type: 'buyer'
                }
            },

            pageLanguage : this.$i18n.locale ? this.$i18n.locale : 'en',

            surveyErrors: {
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
        surveyBTwoThreeOptions(number) {
            let self = this;

            return [
                {
                    id: 'question-' + number + '-b-a',
                    name: 'question-' + number + '-b-a',
                    value: '6-> Really Easy',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_6')
                },
                {
                    id: 'question-' + number + '-b-b',
                    name: 'question-' + number + '-b-b',
                    value: '5-> Easy',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_5')
                },
                {
                    id: 'question-' + number + '-b-c',
                    name: 'question-' + number + '-b-c',
                    value: '4-> Took a time to look but ok',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_4')
                },
                {
                    id: 'question-' + number + '-b-d',
                    name: 'question-' + number + '-b-d',
                    value: '3-> After a time and reading - understood',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_3')
                },
                {
                    id: 'question-' + number + '-b-e',
                    name: 'question-' + number + '-b-e',
                    value: '2-> Difficult',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_2')
                },
                {
                    id: 'question-' + number + '-b-f',
                    name: 'question-' + number + '-b-f',
                    value: '1-> Very Difficult',
                    label: self.$t('message.buyer_survey.buyer_survey_b_q_2_1')
                }
            ]
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
                        self.$t('message.buyer_survey.alert_success'),
                        self.$t('message.buyer_survey.alert_success_text'),
                        'success',
                    )

                    this.hasUserAnsweredSurveySet();
                })
                .catch((err) => {
                    swal.fire(
                        self.$t('message.buyer_survey.alert_error'),
                        err.response.data.message,
                        'error',
                    )

                    self.surveyErrors.errors = err.response.data.errors;
                    self.surveyErrors.message = err.response.data.message;

                    console.log(err.response.data)
                });
        },

        hasUserAnsweredSurveySet () {

            let path = this.survey.answers.code === null
                ? '/api/survey/check-survey-set'
                : '/api/survey/check-survey-code-set'

            axios.post(path, {
                set: this.survey.answers.set,
                code: this.survey.answers.code,
                type: 'buyer'
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
