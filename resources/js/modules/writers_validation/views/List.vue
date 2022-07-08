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


        <div v-if="!isExtWriter">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title text-primary">{{ $t('message.writer_validation.filter_title') }}</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_anchor_text') }}</label>
                                        <input type="text" class="form-control" v-model="filterModel.anchor_text">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_writer') }}</label>
                                        <select name="" class="form-control" v-model="filterModel.writer_id">
                                            <option value="">{{ $t('message.writer_validation.all') }}</option>
                                            <option v-for="option in ListExtWriters.data" v-bind:value="option.id">
                                                {{ option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_status') }}</label>
                                        <select name="" class="form-control" v-model="filterModel.status">
                                            <option value="">{{ $t('message.writer_validation.all') }}</option>
                                            <option v-for="option in statusFilter" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_topic') }}</label>
                                        <v-select
                                            class="style-chooser"
                                            v-model="filterModel.topic"
                                            multiple
                                            placeholder="All"
                                            :options="topicList"
                                            :searchable="false"/>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <button class="btn btn-default" @click="clearSearch">
                                        {{ $t('message.writer_validation.clear') }}
                                    </button>
                                    <button class="btn btn-default" @click="doSearch">
                                        {{ $t('message.writer_validation.search') }}
                                        <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">

                                </div>

                                <div class="col-6">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control float-right"
                                        style="min-width: 100px; width: 100px"

                                        @change="getListExtWriters">

                                        <option v-for="option in paginateOptions" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    id="tbl_writer_validation"
                                    class="table"
                                    style="font-size: 0.75rem; border-collapse: collapse">
                                    <thead>
                                        <tr class="label-primary">
                                            <th>{{ $t('message.writer_validation.filter_writer') }}</th>
                                            <th>{{ $t('message.writer_validation.wv_attempt') }}</th>
                                            <th>{{ $t('message.writer_validation.wv_action') }}</th>
                                            <th>{{ $t('message.writer_validation.filter_topic') }}</th>
                                            <th>{{ $t('message.writer_validation.wv_title') }}</th>
                                            <th>{{ $t('message.writer_validation.filter_anchor_text') }}</th>
                                            <th>{{ $t('message.writer_validation.wv_link_to') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="writer in ListExtWriters.data">
                                            <!-- if no exam -->
                                            <template v-if="writer.writer_exam.length === 0">
                                                <tr>
                                                    <td class="align-middle font-weight-bold">
                                                        (#{{ writer.id }}) {{ writer.username }}
                                                    </td>

                                                    <td class="align-middle">
                                                        <span class="badge badge-secondary p-3 text-uppercase">
                                                            1 - {{ $t('message.writer_validation.wv_not_setup') }}
                                                        </span>
                                                    </td>

                                                    <td class="align-middle">
                                                        <button
                                                            :title="$t('message.writer_validation.wv_create_exam')"
                                                            class="btn btn-success"

                                                            @click="doCreateExam(writer.id, writer.username, 1, writer.exam_duration)">

                                                            <i class="fas fa-plus-square"></i>
                                                        </button>
                                                    </td>

                                                    <td class="align-middle" colspan="4">
                                                        {{ $t('message.writer_validation.wv_exam_details_empty') }}
                                                    </td>

                                                    <td style="display: none !important;"></td>
                                                    <td style="display: none !important;"></td>
                                                </tr>
                                            </template>

                                            <!-- if writer exam is 1 > -->
                                            <template v-else>
                                                <tr v-for="(exam, index) in writer.writer_exam">
                                                    <td
                                                        class="align-middle font-weight-bold"
                                                        :rowspan="checkExamsHaveDisapproved(writer.writer_exam) ? 2 : writer.writer_exam.length"
                                                        :style="index > 0 ? 'display: none !important' : ''">

                                                        (#{{ writer.id }}) {{ writer.username }}
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">

                                                        <div class="d-flex">
                                                            <span
                                                                class="badge text-uppercase p-3"
                                                                :class="examStatusBadge(exam.status)">

                                                                {{ exam.attempt }} - {{ exam.status }}
                                                            </span>

                                                            <button
                                                                v-if="exam.status === 'Disapproved'"
                                                                title="Update fail reason"
                                                                class="btn btn-warning ml-2"

                                                                @click="updateExamFailReason(exam)">

                                                                <i class="fas fa-comment-dots"></i>
                                                            </button>
                                                        </div>
                                                    </td>

                                                    <td :style="exam.attempt === 2 ? 'border: none !important;' : ''">
                                                        <button
                                                            :title="$t('message.writer_validation.wv_view_exam_details')"
                                                            class="btn btn-primary"

                                                            @click="doUpdate(exam)">

                                                            <i class="fas fa-pen-square"></i>
                                                        </button>
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">

                                                        <template v-if="!exam.topic">
                                                            <span class="badge badge-secondary">
                                                                N/A
                                                            </span>
                                                        </template>

                                                        <template v-else>
                                                            <span v-for="topic in sortTopics(exam.topic)">
                                                                <span class="badge badge-primary mr-1">
                                                                    {{ topic }}
                                                                </span>
                                                            </span>
                                                        </template>
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">

                                                        <span :class="!exam.title ? 'badge badge-secondary' : ''">
                                                            {{ exam.title ? exam.title : 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">
                                                        {{ exam.anchor_text }}
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">
                                                        {{ exam.link_to }}
                                                    </td>
                                                </tr>

                                                <tr v-if="checkExamsHaveDisapproved(writer.writer_exam)">
                                                    <td
                                                        rowspan="2"
                                                        class="align-middle font-weight-bold"
                                                        style="display: none !important;">

                                                        (#{{ writer.id }}) {{ writer.username }}
                                                    </td>

                                                    <td class="align-middle" style="border: none !important;">
                                                        <span
                                                            v-if="writer.exam_duration > 0"
                                                            class="badge badge-secondary p-3 text-uppercase">
                                                            {{ writer.exam_duration }} {{ $t('message.writer_validation.wv_days_attempt') }}
                                                        </span>

                                                        <span v-else class="badge badge-warning p-3 text-uppercase">
                                                            {{ $t('message.writer_validation.wv_create_2nd_attempt_exam') }}
                                                        </span>
                                                    </td>

                                                    <td class="align-middle" style="border: none !important;">
                                                        <button
                                                            :disabled="writer.exam_duration > 0"
                                                            :title="$t('message.writer_validation.wv_create_exam')"
                                                            class="btn btn-success"

                                                            @click="doCreateExam(writer.id, writer.username, 2, writer.exam_duration)">

                                                            <i class="fas fa-plus-square"></i>
                                                        </button>
                                                    </td>

                                                    <td
                                                        colspan="4"
                                                        class="align-middle"
                                                        style="border: none !important;"
                                                        :class="writer.exam_duration !== 0 ? 'text-secondary' : ''">

                                                        {{ $t('message.writer_validation.wv_exam_details_empty') }}
                                                    </td>

                                                    <td style="display: none !important; border: none !important;"></td>
                                                    <td style="display: none !important; border: none !important;"></td>
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <pagination
                                :data="ListExtWriters"
                                :limit="8"

                                @pagination-change-page="getListExtWriters">

                            </pagination>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Create Exam Edit -->
            <div class="modal fade" id="modalEditWriterValidate" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $t('message.writer_validation.ce_title') }} {{ addExam.attempt === 1 ? '1st' : '2nd' }} {{ $t('message.writer_validation.ce_attempt_exam') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_writer') }}</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="addExam.writer_name">
                                        <input type="hidden" class="form-control" v-model="addExam.writer_id">
                                    </div>
                                </div>
<!--                                <div class="col-md-12">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>Title</label>-->
<!--                                        <input type="text" class="form-control" v-model="addExam.title">-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_topic') }}</label>
                                        <v-select
                                            v-model="addExam.topic"
                                            multiple
                                            :placeholder="$t('message.writer_validation.ce_select_topic')"
                                            :options="topicList"
                                            :searchable="false"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.ce_topic_notes') }}</label>
                                        <textarea v-model="addExam.topic_notes" class="form-control" rows="3">

                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_anchor_text') }}</label>
                                        <input type="text" class="form-control" v-model="addExam.anchor_text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.wv_link_to') }}</label>
                                        <input type="text" class="form-control" v-model="addExam.link_to">
                                    </div>
                                </div>
<!--                                <div class="col-md-12">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>Meta Description</label>-->
<!--                                        <textarea class="form-control" cols="30" rows="5" v-model="addExam.meta_description"></textarea>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                {{ $t('message.writer_validation.close') }}
                            </button>
                            <button type="button" class="btn btn-success" @click="submitExam()">
                                {{ $t('message.writer_validation.wv_create_exam') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal Edit -->

            <!-- Modal View Content -->
            <div class="modal fade" id="modalEditWriterValidateViewContent" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $t('message.writer_validation.vc_title') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_status') }}</label>
                                        <select name="" class="form-control" v-model="viewModel.status" disabled>
                                            <option v-for="option in status" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="viewModel.status === 'Disapproved'" class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-danger">Fail Reason</label>
                                        <textarea v-model="viewModel.fail_reason" class="form-control" rows="3" disabled>

                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_topic') }}</label>
                                        <v-select
                                            v-model="viewModel.topic"
                                            multiple
                                            :options="topicList"
                                            :searchable="false"
                                            :disabled=true
                                            :placeholder="$t('message.writer_validation.ce_select_topic')"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.ce_topic_notes') }}</label>
                                        <textarea v-model="viewModel.topic_notes" class="form-control" rows="3" disabled>

                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.wv_title') }}</label>
                                        <input type="text" class="form-control" v-model="viewModel.title" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.filter_anchor_text') }}</label>
                                        <input type="text" class="form-control" v-model="viewModel.anchor_text" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.wv_link_to') }}</label>
                                        <input type="text" class="form-control" v-model="viewModel.link_to" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.vc_meta_desc') }}</label>
                                        <textarea class="form-control" cols="30" rows="5" v-model="viewModel.meta_description" disabled></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('message.writer_validation.vc_content') }}</label>
                                        <tiny-editor
                                            v-model="data2"
                                            ref="composeEditExam"
                                            editor-id="composeEditExam">

                                        </tiny-editor>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                v-if="viewModel.status === 'For Checking'"
                                type="button"
                                class="btn btn-success"

                                @click="submitUpdate('approve')">

                                <i class="fas fa-user-check"></i>
                                {{ $t('message.writer_validation.approve') }}
                            </button>

                            <button
                                v-if="viewModel.status === 'For Checking'"
                                type="button"
                                class="btn btn-danger"

                                @click="submitUpdate('disapprove')">

                                <i class="fas fa-user-times"></i>
                                {{ $t('message.writer_validation.disapprove') }}
                            </button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                {{ $t('message.writer_validation.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal View Content -->

            <!-- Modal Fail Reason -->
            <div class="modal fade" id="modalFailReason" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="swal2-icon swal2-error swal2-icon-show" style="display: flex;">
                                <span class="swal2-x-mark">
                                    <span class="swal2-x-mark-line-left"></span>
                                    <span class="swal2-x-mark-line-right"></span>
                                </span>
                            </div>

                            <h2 class="disapprove-title">
                                {{ $t('message.writer_validation.alert_writer_exam_evaluation') }}
                            </h2>

                            <div class="swal2-content">
                                Mark exam as disapprove?

                                <p class="m-0">
                                    (Please provide a reason for the writer)
                                </p>

                                <textarea
                                    v-model="viewModel.fail_reason"
                                    rows="3"
                                    class="form-control mt-2">

                                </textarea>

                                <span
                                    v-if="errorMessages.errors.fail_reason"
                                    v-for="err in errorMessages.errors.fail_reason"
                                    class="text-danger">

                                    {{ err }}
                                </span>
                            </div>

                            <div class="swal2-actions">
                                <button
                                    type="button"
                                    class="swal2-confirm swal2-styled"
                                    style="display: inline-block; border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); background-color: #dc3545"
                                    aria-label=""

                                    @click="checkExam('disapprove')">

                                    {{ $t('message.writer_validation.disapprove') }}
                                </button>

                                <button
                                    type="button"
                                    class="swal2-cancel swal2-styled"
                                    style="display: inline-block;"
                                    aria-label=""
                                    data-dismiss="modal">
                                    {{ $t('message.writer_validation.close') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal Fail Reason -->

            <!-- Modal Update Fail Reason -->
            <div class="modal fade" id="modalUpdateFailReason" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Exam Failed Reason</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body text-center">
                            <div class="alert alert-info text-left">
                                <i class="fas fa-info-circle"></i>
                                An email and notification will be sent to the writer about the updated reason.
                            </div>

                            <textarea
                                v-model="examFailReasonModel.reason"
                                rows="3"
                                class="form-control mt-2">

                            </textarea>

                            <span
                                v-if="errorMessages.errors.reason"
                                v-for="err in errorMessages.errors.reason"
                                class="text-danger">

                                    {{ err }}
                                </span>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-primary"

                                @click="updateExamFailReasonSend">

                                {{ $t('message.tools.update') }}
                            </button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                {{ $t('message.writer_validation.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal Update Fail Reason -->
        </div>

        <!-- Viewing for External Writers -->
        <div v-if="isExtWriter">

            <div v-if="ExamUpdateFirst || ExamUpdateSecond" class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <p><i class="fas fa-book"></i> <b>{{ $t('message.writer_validation.ew_instructions') }}</b></p>
                        1. {{ $t('message.writer_validation.ew_600_words') }}.<br/>
                        2. {{ $t('message.writer_validation.ew_keywords') }}.<br/>
                        3. {{ $t('message.writer_validation.ew_anchor_text') }}.<br/>
                        4. {{ $t('message.writer_validation.ew_creative_title') }}.<br/>
                        5. {{ $t('message.writer_validation.ew_meta_desc') }}.<br/>

                        <br/>
                        {{ $t('message.writer_validation.ew_2_attempts') }}
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="alert alert-info">
                    <p class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        {{ $t('message.writer_validation.ew_please_wait') }}
                    </p>
                </div>
            </div>

            <!-- first attempt exam -->
            <div v-if="ExamUpdateFirst">
                <div class="row" v-if="ExamUpdateFirst.anchor_text !== '' && ExamUpdateFirst.link_to !== '' ">
                    <div class="col-sm-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title text-primary">{{ $t('message.writer_validation.fam_title') }}</h3>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body">

                                <div v-if="ExamUpdateFirst.status === 'Disapproved'" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.fam_first_attempt_failed') }}
                                                <br/> <br/>

                                                Reason(s):
                                                <br>
                                                <span class="font-italic">{{ ExamUpdateFirst.fail_reason }}</span>
                                                <br/> <br/>

                                                {{ $t('message.writer_validation.fam_remaining_days') }} {{
                                                    user.exam_duration > 0 ? user.exam_duration : 0
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="row">
                                    <div class="col-md-12" v-if="ExamUpdateFirst.status === 'For Checking'">
                                        <div class="alert alert-warning">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.fam_on_checking') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12" v-if="ExamUpdateFirst.status === 'Approved'">
                                        <div class="alert alert-success">
                                            <p class="mb-0"> <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.fam_passed') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.wv_title') }}</label>
                                            <input
                                                v-model="ExamUpdateFirst.title"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.filter_topic') }}</label>
                                            <v-select
                                                v-model="ExamUpdateFirst.topic"
                                                multiple
                                                :options="topicList"
                                                :searchable="false"
                                                :disabled=true
                                                :placeholder="$t('message.writer_validation.ce_select_topic')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.ce_topic_notes') }}</label>
                                            <textarea
                                                v-model="ExamUpdateFirst.topic_notes"
                                                disabled
                                                rows="3"
                                                class="form-control">

                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.filter_anchor_text') }}</label>
                                            <input
                                                v-model="ExamUpdateFirst.anchor_text"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.wv_link_to') }}</label>
                                            <input
                                                v-model="ExamUpdateFirst.link_to"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.vc_meta_desc') }}</label>
                                            <textarea
                                                v-model="ExamUpdateFirst.meta_description"
                                                cols="30"
                                                rows="5"
                                                class="form-control">

                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.vc_content') }}</label>
                                            <tiny-editor
                                                v-model="data"
                                                ref="composeEditExam2"
                                                editor-id="composeEditExam2">

                                            </tiny-editor>
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-3" v-if="ExamUpdateFirst.status !== 'For Checking' && ExamUpdateFirst.status !== 'Approved'">
                                        <button class="btn btn-primary btn-lg" @click="submitWriterExam(1)">
                                            {{ $t('message.writer_validation.fam_submit_exam') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- second attempt exam -->
            <div v-if="ExamUpdateFirst && ExamUpdateFirst.status === 'Disapproved'">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title text-primary">{{ $t('message.writer_validation.sae_title') }}</h3>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body">

                                <div v-if="!ExamUpdateSecond" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.sae_please_wait_second') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else-if="ExamUpdateSecond.status === 'Disapproved'" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.sae_failed') }}

                                                <br/> <br/>

                                                Reason(s):
                                                <br>
                                                <span class="font-italic">{{ ExamUpdateSecond.fail_reason_two }}</span>
                                                <br/> <br/>

                                                {{ $t('message.writer_validation.sae_deactivate') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="row">
                                    <div class="col-md-12" v-if="ExamUpdateSecond.status === 'For Checking'">
                                        <div class="alert alert-warning">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.fam_on_checking') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12" v-if="ExamUpdateSecond.status === 'Approved'">
                                        <div class="alert alert-success">
                                            <p class="mb-0"> <i class="fas fa-info-circle"></i>
                                                {{ $t('message.writer_validation.fam_passed') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.wv_title') }}</label>
                                            <input
                                                v-model="ExamUpdateSecond.title"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.filter_topic') }}</label>
                                            <v-select
                                                v-model="ExamUpdateSecond.topic"
                                                multiple
                                                :options="topicList"
                                                :searchable="false"
                                                :disabled=true
                                                :placeholder="$t('message.writer_validation.ce_select_topic')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.ce_topic_notes') }}</label>
                                            <textarea
                                                v-model="ExamUpdateSecond.topic_notes"
                                                disabled
                                                rows="3"
                                                class="form-control">

                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.filter_anchor_text') }}</label>
                                            <input
                                                v-model="ExamUpdateSecond.anchor_text"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.wv_link_to') }}</label>
                                            <input
                                                v-model="ExamUpdateSecond.link_to"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.vc_meta_desc') }}</label>
                                            <textarea
                                                v-model="ExamUpdateSecond.meta_description"
                                                class="form-control"
                                                cols="30"
                                                rows="5">

                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ $t('message.writer_validation.vc_content') }}</label>
                                            <tiny-editor
                                                v-model="dataSecond"
                                                ref="composeEditExam2Two"
                                                editor-id="composeEditExam2Two">

                                            </tiny-editor>
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-3" v-if="ExamUpdateSecond.status !== 'For Checking' && ExamUpdateSecond.status !== 'Approved'">
                                        <button class="btn btn-primary btn-lg" @click="submitWriterExam(2)">
                                            {{ $t('message.writer_validation.fam_submit_exam') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import 'tinymce/skins/lightgray/skin.min.css';
import TinyEditor from "../../../components/editor/TinyEditor";

export default {
    components: {
        TinyEditor,
    },
    data() {
        return {
            statusFilter: ['Not Yet Setup', 'Setup', 'For Checking', 'Approved', 'Disapproved'],
            status: ['Setup', 'For Checking', 'Approved', 'Disapproved'],
            data: '',
            dataSecond: '',
            data2: '',
            ListExtWriters: {
                data: [],
            },
            addExam: {
                topic: '',
                topic_notes: '',
                writer_name: '',
                writer_id: '',
                anchor_text: '',
                link_to: '',
                title: '',
                meta_description: '',
                attempt: '',
                duration: '',
            },
            filterModel: {
                topic: '',
                status: '',
                anchor_text: '',
                writer_id: '',
                paginate: 15,
            },

            ExamUpdateFirst: {
                topic: '',
                topic_notes: '',
                link_to: '',
                anchor_text: '',
                meta_description: '',
                title: '',
                content: '',
                status: '',
            },

            ExamUpdateSecond: {
                topic: '',
                topic_notes: '',
                link_to: '',
                anchor_text: '',
                meta_description: '',
                title: '',
                content: '',
                status: '',
            },

            isExtWriter: false,
            searchLoading: false,
            viewModel: {
                id: '',
                title: '',
                topic: '',
                topic_notes: '',
                anchor_text: '',
                meta_description: '',
                status: '',
                link_to: '',
                writer_id: '',
                attempt: '',
                fail_reason: '',
            },

            paginateOptions : [
                15,
                25,
                50,
                100,
                200,
                250,
                'All'
            ],

            topicList: [
                'Adult',
                'Art',
                'Beauty',
                'Charity',
                'Cooking',
                'Crypto',
                'Education',
                'Fashion',
                'Finance',
                'Gambling',
                'Games',
                'Health',
                'History',
                'Job',
                'Marketing',
                'Movies & Music',
                'News',
                'Pet',
                'Photograph',
                'Real Estate',
                'Religion',
                'Shopping',
                'Sports',
                'Tech',
                'Travel',
            ],

            errorMessages: {
                message: '',
                errors: [],
            },

            examFailReasonModel: {
                id: '',
                reason: '',
            }
        }
    },

    created() {
        this.checkUser();
    },

    mounted() {
        this.getListExtWriters();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

    },

    methods : {

        submitUpdate(mod) {
            let self = this;

            if (mod === 'approve') {
                swal.fire({
                    title : self.$t('message.writer_validation.alert_writer_exam_evaluation'),
                    text : self.$t('message.writer_validation.alert_mark') + mod + "?",
                    icon : mod === 'approve' ? 'success' : 'error',
                    showCancelButton : true,
                    confirmButtonText : self.$t('message.writer_validation.yes'),
                    cancelButtonText : self.$t('message.writer_validation.no')
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        self.checkExam(mod)
                    }
                });
            } else {
                this.errorMessages = {
                    message: '',
                    errors: [],
                }
                $("#modalFailReason").modal('show')
            }
        },

        checkExam (mod) {
            let self = this;
            let loader = self.$loading.show();

            this.viewModel.status = mod === 'approve' ? 'Approved' : 'Disapproved';

            axios.post('/api/check-exam', this.viewModel)
            .then((res) => {
                loader.hide();

                swal.fire(
                    self.$t('message.writer_validation.alert_success'),
                    self.$t('message.writer_validation.alert_updated_successfully'),
                    'success',
                )

                $("#modalEditWriterValidateViewContent").modal('hide')

                if (mod === 'disapprove') {
                    $("#modalFailReason").modal('hide')
                    this.viewModel.fail_reason = '';
                }

                self.$nextTick(() => {
                    self.doSearch();
                });
            })
            .catch((err) => {
                console.log(err)

                loader.hide();

                this.viewModel.status = 'For Checking'

                self.errorMessages = err.response.data

                swal.fire(
                    self.$t('message.article.alert_err'),
                    'Please indicate the reason why the writer failed the exam.',
                    'error'
                )
            })
        },

        updateExamFailReason (exam) {
            this.examFailReasonModel.id = exam.id;
            this.examFailReasonModel.reason = exam.attempt === 1 ? exam.fail_reason : exam.fail_reason_two;

            $("#modalUpdateFailReason").modal('show')
        },

        updateExamFailReasonSend () {
            let self = this;
            let loader = self.$loading.show();

            axios.post('/api/update-exam-fail-reason', this.examFailReasonModel)
                .then((res) => {
                    loader.hide();

                    swal.fire(
                        self.$t('message.writer_validation.alert_success'),
                        self.$t('message.writer_validation.alert_updated_successfully'),
                        'success',
                    )

                    $("#modalUpdateFailReason").modal('hide')

                    self.$nextTick(() => {
                        self.doSearch();
                    });
                })
                .catch((err) => {
                    console.log(err)

                    loader.hide();

                    self.errorMessages = err.response.data

                    swal.fire(
                        self.$t('message.article.alert_err'),
                        'Please indicate the reason why the writer failed the exam.',
                        'error'
                    )
                })
        },

        clearSearch() {
            this.filterModel = {
                topic: '',
                status: '',
                anchor_text: '',
                writer_id: '',
                paginate: 15,
            }

            this.getListExtWriters();

            this.$router.replace({'query': null});
        },

        doSearch() {
            this.$router.push({
                query: this.filterModel,
            });

            this.getListExtWriters();
        },

        checkUser() {
            // check if external writer
            if(this.user.role_id == 4 && this.user.isOurs == 1) {
                this.isExtWriter = true
                this.getExamDetails(1);
                this.getExamDetails(2);
            }
        },

        getExamDetails(attempt) {
            axios.get('/api/get-exam-details', {
                params: {
                    id: this.user.id,
                    attempt: attempt
                }
            }).then((res) => {
                let result = res.data.data

                if (attempt === 1) {
                    this.ExamUpdateFirst = result;

                    this.ExamUpdateFirst.topic = this.getTopics(result);

                    if (result) {
                        this.data = result.content === null ? '' : result.content
                    }
                } else {
                    this.ExamUpdateSecond = result;

                    this.ExamUpdateSecond.topic = this.getTopics(result);

                    if (result) {
                        this.dataSecond = result.content === null ? '' : result.content
                    }
                }

            })
        },

        submitWriterExam(attempt) {
            let self = this;
            let data = {};

            if (attempt === 1) {
                this.ExamUpdateFirst.content = this.data;
                this.ExamUpdateFirst.num_words = this.$refs.composeEditExam2.wordCount();

                data = this.ExamUpdateFirst;
            } else {
                this.ExamUpdateSecond.content = this.dataSecond;
                this.ExamUpdateSecond.num_words = this.$refs.composeEditExam2Two.wordCount();

                data = this.ExamUpdateSecond;
            }

            if(data.title === "" || data.title == null) {
                swal.fire(
                    self.$t('message.writer_validation.alert_note'),
                    self.$t('message.writer_validation.alert_title'),
                    'error',
                )
                return false;
            }

            if(data.meta_description === "" || data.meta_description == null) {
                swal.fire(
                    self.$t('message.writer_validation.alert_note'),
                    self.$t('message.writer_validation.alert_meta_desc'),
                    'error',
                )
                return false;
            }

            if(data.content === "" || data.content == null) {
                swal.fire(
                    self.$t('message.writer_validation.alert_note'),
                    self.$t('message.writer_validation.alert_content'),
                    'error',
                )
                return false;
            }

            if(data.num_words < 600) {
                swal.fire(
                    self.$t('message.writer_validation.alert_note'),
                    self.$t('message.writer_validation.alert_600'),
                    'error',
                )
                return false;
            }

            let loader = this.$loading.show();

            axios.post('/api/update-exam', data)
                .then((res) => {
                    loader.hide();

                    swal.fire(
                        self.$t('message.writer_validation.alert_success'),
                        self.$t('message.writer_validation.alert_submitted_successfully'),
                        'success',
                    )

                    if (attempt === 1) {
                        this.ExamUpdateFirst.status = 'For Checking';
                    } else {
                        this.ExamUpdateSecond.status = 'For Checking';
                    }

                }).catch((err) => {
                    loader.hide();
                    console.log(err)
                })
        },

        getListExtWriters(page = 1) {
            this.filterModel.page = page;

            axios.get('/api/ext-writers', {
                params: this.filterModel
            })
            .then((res) => {
                this.ListExtWriters = res.data

                // let table = $('#tbl_writer_validation');
                //
                // table.DataTable().destroy();
                //
                // this.$nextTick(() => {
                //     table.DataTable({
                //         paging : false,
                //         searching : false,
                //         order: [],
                //         columnDefs : [
                //             {orderable : true, targets : 0},
                //             {orderable : true, targets : 1},
                //             {orderable : true, targets : 2},
                //             {orderable : true, targets : 3},
                //             {orderable : true, targets : 4},
                //             {orderable : true, targets : 5},
                //         ],
                //     });
                // });
            })
        },

        doUpdate(exam) {

            let topic = '';

            if(exam.topic != null && exam.topic != '') {
                let _topic = exam.topic;
                if (_topic.indexOf(',') > -1) {
                    topic = _topic.split(',')
                } else {
                    topic = _topic;
                }
            }

            this.viewModel.writer_id = exam.writer_id;
            this.viewModel.id = exam.id;
            this.viewModel.title = exam.title;
            this.viewModel.anchor_text = exam.anchor_text;
            this.viewModel.status = exam.status;
            this.viewModel.link_to = exam.link_to;
            this.viewModel.meta_description = exam.meta_description;
            this.viewModel.attempt = exam.attempt;
            this.viewModel.topic = topic;
            this.viewModel.topic_notes = exam.topic_notes;
            this.viewModel.fail_reason = exam.attempt === 1 ? exam.fail_reason : exam.fail_reason_two;
            this.data2 = exam.content === null ? '' : exam.content;

            $("#modalEditWriterValidateViewContent").modal('show')
        },

        submitExam() {
            let self = this;
            // if 2nd attempt, duration must be <= 0
            if (this.addExam.attempt === 2) {
                if (this.addExam.duration > 0) {
                    swal.fire(
                        self.$t('message.writer_validation.alert_error'),
                        self.$t('message.writer_validation.alert_not_yet') + this.addExam.duration
                        + self.$t('message.writer_validation.alert_days'),
                        'error',
                    )

                    return false;
                }
            }

            let loader = this.$loading.show();

            axios.post('/api/add-exam', this.addExam)
                .then((res) => {
                    loader.hide();

                    this.getListExtWriters();

                    swal.fire(
                        self.$t('message.writer_validation.alert_success'),
                        self.$t('message.writer_validation.alert_created_successfully'),
                        'success',
                    )

                    $("#modalEditWriterValidate").modal('hide');
                })
                .catch((err) => {
                    loader.hide();

                    let errorMessages = Object.values(err.response.data.errors);
                    let html = "<span style='color: red; text-transform: uppercase'>" + err.response.data.message + "</span><br/><br/>";

                    for(let i = 0; i < errorMessages.length; i++) {
                        html += "<span>" + errorMessages[i] + "</span><br>";
                    }

                    swal.fire(
                        self.$t('message.writer_validation.alert_error'),
                        html,
                        'error',
                    )

                    console.log(err)
                })
        },

        doCreateExam(writer_id, writer_username, attempt, duration) {
            this.addExam.topic = '';
            this.addExam.topic_notes = '';
            this.addExam.writer_name = writer_username;
            this.addExam.writer_id = writer_id;
            this.addExam.title = '';
            this.addExam.anchor_text = '';
            this.addExam.link_to = '';
            this.addExam.meta_description = '';
            this.addExam.attempt = attempt;
            this.addExam.duration = duration;

            $("#modalEditWriterValidate").modal('show')
        },

        examStatusBadge (status) {
            let badge = '';

            if (status === 'Setup') {
                badge = 'badge-primary'
            } else if (status === 'For Checking') {
                badge = 'badge-info'
            } else if (status === 'Approved') {
                badge = 'badge-success'
            } else {
                badge = 'badge-danger'
            }

            return badge;
        },

        checkExamsHaveDisapproved (exams) {
            return exams.some(item => item.status === 'Disapproved') && exams.length === 1;
        },

        sortTopics (topics) {
            return topics.split(',');
        },

        getTopics (result) {
            let topic;

            if(result.topic != null && result.topic != '') {
                let _topic = result.topic;
                if (_topic.indexOf(',') > -1) {
                    topic = _topic.split(',')
                } else {
                    topic = _topic;
                }
            }

            return topic;
        },
    },
}
</script>

<style scoped>
    .table td {
        padding: .75rem;
        vertical-align: top;
        border-top: 4px solid #dee2e6;
    }

    .disapprove-title {
        position: relative;
        max-width: 100%;
        margin: 0 0 .4em;
        padding: 0;
        color: #595959;
        font-size: 1.875em;
        font-weight: 600;
        text-align: center;
        text-transform: none;
        word-wrap: break-word;
    }
</style>

<style>
    .style-chooser input {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
    }

    #modalFailReason .modal-header {
        border-bottom: 0 none;
    }

    #modalFailReason .modal-footer {
        border-top: 0 none;
    }
</style>
