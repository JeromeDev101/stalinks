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
                            <h3 class="card-title text-primary">Filter</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Anchor Text</label>
                                        <input type="text" class="form-control" v-model="filterModel.anchor_text">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Writer</label>
                                        <select name="" class="form-control" v-model="filterModel.writer_id">
                                            <option value="">All</option>
                                            <option v-for="option in ListExtWriters.data" v-bind:value="option.id">
                                                {{ option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="" class="form-control" v-model="filterModel.status">
                                            <option value="">All</option>
                                            <option v-for="option in statusFilter" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Topic</label>
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
                                    <button class="btn btn-default" @click="clearSearch">Clear</button>
                                    <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
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
                                            <th>Writer</th>
                                            <th>Attempt / Status</th>
                                            <th>Action</th>
                                            <th>Topic</th>
                                            <th>Title</th>
                                            <th>Anchor Text</th>
                                            <th>Link To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="writer in ListExtWriters.data">
                                            <!-- if no exam -->
                                            <template v-if="writer.writer_exam.length === 0">
                                                <tr>
                                                    <td class="align-middle font-weight-bold">
                                                        {{ writer.username }}
                                                    </td>

                                                    <td class="align-middle">
                                                        <span class="badge badge-secondary p-3 text-uppercase">
                                                            1 - Not Yet Setup
                                                        </span>
                                                    </td>

                                                    <td class="align-middle">
                                                        <button
                                                            title="Create Exam"
                                                            class="btn btn-success"

                                                            @click="doCreateExam(writer.id, writer.username, 1, writer.exam_duration)">

                                                            <i class="fas fa-plus-square"></i>
                                                        </button>
                                                    </td>

                                                    <td class="align-middle" colspan="4">
                                                        Exam details empty - please setup an exam for the writer
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

                                                        {{ writer.username }}
                                                    </td>

                                                    <td
                                                        class="align-middle"
                                                        :style="exam.attempt === 2 ? 'border: none !important;' : ''">

                                                        <span class="badge p-3 text-uppercase" :class="examStatusBadge(exam.status)">
                                                            {{ exam.attempt }} - {{ exam.status }}
                                                        </span>
                                                    </td>

                                                    <td :style="exam.attempt === 2 ? 'border: none !important;' : ''">
                                                        <button
                                                            title="View Exam Details"
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

                                                        {{ writer.username }}
                                                    </td>

                                                    <td class="align-middle" style="border: none !important;">
                                                        <span
                                                            v-if="writer.exam_duration > 0"
                                                            class="badge badge-secondary p-3 text-uppercase">
                                                            {{ writer.exam_duration }} day(s) until second attempt exam
                                                        </span>

                                                        <span v-else class="badge badge-warning p-3 text-uppercase">
                                                            Create 2nd attempt exam now
                                                        </span>
                                                    </td>

                                                    <td class="align-middle" style="border: none !important;">
                                                        <button
                                                            :disabled="writer.exam_duration > 0"
                                                            title="Create Exam"
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

                                                        Exam details empty - please setup an exam for the writer
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
                            <h5 class="modal-title">Create {{ addExam.attempt === 1 ? '1st' : '2nd' }} Attempt Exam</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Writer</label>
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
                                        <label>Topic</label>
                                        <v-select
                                            v-model="addExam.topic"
                                            multiple
                                            placeholder="Select Topic"
                                            :options="topicList"
                                            :searchable="false"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Anchor Text</label>
                                        <input type="text" class="form-control" v-model="addExam.anchor_text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Link To</label>
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" @click="submitExam()">Create Exam</button>
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
                            <h5 class="modal-title">Writer Examination Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="" class="form-control" v-model="viewModel.status" disabled>
                                            <option v-for="option in status" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Topic</label>
                                        <v-select
                                            v-model="viewModel.topic"
                                            multiple
                                            :options="topicList"
                                            :searchable="false"
                                            :disabled=true
                                            placeholder="Select Topic"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" v-model="viewModel.title" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Anchor Text</label>
                                        <input type="text" class="form-control" v-model="viewModel.anchor_text" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Link To</label>
                                        <input type="text" class="form-control" v-model="viewModel.link_to" :disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <textarea class="form-control" cols="30" rows="5" v-model="viewModel.meta_description" disabled></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Content</label>
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

                                <i class="fas fa-user-check"></i> Approve
                            </button>

                            <button
                                v-if="viewModel.status === 'For Checking'"
                                type="button"
                                class="btn btn-danger"

                                @click="submitUpdate('disapprove')">

                                <i class="fas fa-user-times"></i> Disapprove
                            </button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal View Content -->

        </div>

        <!-- Viewing for External Writers -->
        <div v-if="isExtWriter">

            <div v-if="ExamUpdateFirst || ExamUpdateSecond" class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <p><i class="fas fa-book"></i> <b>Instructions:</b></p>
                        1. Write an article with at least <b>600 words</b>.<br/>
                        2. Identify the keywords you have used in the article by using a <b>bold</b> character.<br/>
                        3. Use the <b>anchor text</b> as <b>natural</b> as possible within your article and
                        <b>hyperlink</b> it to the assigned website.<br/>
                        4.	Create a creative title based on the topic(s) provided.<br/>
                        5.	Write a meta description with 110-160 characters (The spaces are counted as character).<br/>

                        <br/>
                        You will be given <b>2 attempts</b> for the writer's exam. If you have failed on the first attempt.
                        no worries! we will give you a second attempt to take the exam after <b>3 weeks.</b>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="alert alert-info">
                    <p class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Please wait for the writer's exam to be created. We will notify you about this shortly. Thank you!
                    </p>
                </div>
            </div>

            <!-- first attempt exam -->
            <div v-if="ExamUpdateFirst">
                <div class="row" v-if="ExamUpdateFirst.anchor_text !== '' && ExamUpdateFirst.link_to !== '' ">
                    <div class="col-sm-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title text-primary">Examination - 1st Attempt</h3>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body">

                                <div v-if="ExamUpdateFirst.status === 'Disapproved'" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                First attempt examination result: Failed <br/> <br/>

                                                Remaining day(s) until second attempt: {{
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
                                                Your exam is now currently on checking. We will notify you shortly for
                                                the result. Thank you for your cooperation!
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12" v-if="ExamUpdateFirst.status === 'Approved'">
                                        <div class="alert alert-success">
                                            <p class="mb-0"> <i class="fas fa-info-circle"></i>
                                                <b>Congratulations!</b> you have passed the writer examination.
                                                You can now go to the articles page and start writing articles for
                                                our clients. Please re-login to update your account.
                                                Thanks you for your cooperation!
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input
                                                v-model="ExamUpdateFirst.title"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Topic</label>
                                            <v-select
                                                v-model="ExamUpdateFirst.topic"
                                                multiple
                                                :options="topicList"
                                                :searchable="false"
                                                :disabled=true
                                                placeholder="Select Topic"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Anchor Text</label>
                                            <input
                                                v-model="ExamUpdateFirst.anchor_text"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Link To</label>
                                            <input
                                                v-model="ExamUpdateFirst.link_to"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Meta Description</label>
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
                                            <label>Content</label>
                                            <tiny-editor
                                                v-model="data"
                                                ref="composeEditExam2"
                                                editor-id="composeEditExam2">

                                            </tiny-editor>
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-3" v-if="ExamUpdateFirst.status !== 'For Checking' && ExamUpdateFirst.status !== 'Approved'">
                                        <button class="btn btn-primary btn-lg" @click="submitWriterExam(1)">Submit Exam</button>
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
                                <h3 class="card-title text-primary">Examination - 2nd Attempt</h3>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body">

                                <div v-if="!ExamUpdateSecond" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                Please wait for the 2nd attempt exam to be created.
                                                We will notify you about this shortly. Thank you!
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else-if="ExamUpdateSecond.status === 'Disapproved'" class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                After careful evaluation of your second attempt exam. We regret to inform
                                                you that you did not pass the writer's examination provided by our team.

                                                <br/> <br/>

                                                We will now deactivate your writer's account. We wish you good luck
                                                on your future endeavors. Thank you!
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="row">
                                    <div class="col-md-12" v-if="ExamUpdateSecond.status === 'For Checking'">
                                        <div class="alert alert-warning">
                                            <p class="mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                Your exam is now currently on checking. We will notify you shortly for
                                                the result. Thank you for your cooperation!
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12" v-if="ExamUpdateSecond.status === 'Approved'">
                                        <div class="alert alert-success">
                                            <p class="mb-0"> <i class="fas fa-info-circle"></i>
                                                <b>Congratulations!</b> you have passed the writer examination.
                                                You can now go to the articles page and start writing articles for
                                                our clients. Please re-login to update your account.
                                                Thanks you for your cooperation!
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input
                                                v-model="ExamUpdateSecond.title"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Topic</label>
                                            <v-select
                                                v-model="ExamUpdateSecond.topic"
                                                multiple
                                                :options="topicList"
                                                :searchable="false"
                                                :disabled=true
                                                placeholder="Select Topic"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Anchor Text</label>
                                            <input
                                                v-model="ExamUpdateSecond.anchor_text"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Link To</label>
                                            <input
                                                v-model="ExamUpdateSecond.link_to"
                                                type="text"
                                                class="form-control"
                                                :disabled="true">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Meta Description</label>
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
                                            <label>Content</label>
                                            <tiny-editor
                                                v-model="dataSecond"
                                                ref="composeEditExam2Two"
                                                editor-id="composeEditExam2Two">

                                            </tiny-editor>
                                        </div>
                                    </div>

                                    <div class="col-md-12 my-3" v-if="ExamUpdateSecond.status !== 'For Checking' && ExamUpdateSecond.status !== 'Approved'">
                                        <button class="btn btn-primary btn-lg" @click="submitWriterExam(2)">Submit Exam</button>
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
                link_to: '',
                anchor_text: '',
                meta_description: '',
                title: '',
                content: '',
                status: '',
            },

            ExamUpdateSecond: {
                topic: '',
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
                anchor_text: '',
                meta_description: '',
                status: '',
                link_to: '',
                writer_id: '',
                attempt: '',
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
            swal.fire({
                title : "Writer Exam Evaluation",
                text : "Mark exam as " + mod + "?",
                icon : mod === 'approve' ? 'success' : 'error',
                showCancelButton : true,
                confirmButtonText : 'Yes',
                cancelButtonText : 'No'
            })
            .then((result) => {
                if (result.isConfirmed) {

                    let loader = this.$loading.show();

                    this.viewModel.status = mod === 'approve' ? 'Approved' : 'Disapproved';

                    axios.post('/api/check-exam', this.viewModel)
                        .then((res) => {

                            loader.hide();

                            swal.fire(
                                'Success',
                                'Successfully Updated.',
                                'success',
                            )

                            $("#modalEditWriterValidateViewContent").modal('hide')

                            this.$nextTick(() => {
                                this.doSearch();
                            });
                        })
                }
            });
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
                    'Note',
                    'Please provide a title',
                    'error',
                )
                return false;
            }

            if(data.meta_description === "" || data.meta_description == null) {
                swal.fire(
                    'Note',
                    'Please provide a meta description',
                    'error',
                )
                return false;
            }

            if(data.content === "" || data.content == null) {
                swal.fire(
                    'Note',
                    'Please write Content to finish the Exam',
                    'error',
                )
                return false;
            }

            if(data.num_words < 600) {
                swal.fire(
                    'Note',
                    'Below 600 words are not',
                    'error',
                )
                return false;
            }

            let loader = this.$loading.show();

            axios.post('/api/update-exam', data)
                .then((res) => {
                    loader.hide();

                    swal.fire(
                        'Success',
                        'Successfully Submitted.',
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
            this.data2 = exam.content === null ? '' : exam.content;

            $("#modalEditWriterValidateViewContent").modal('show')
        },

        submitExam() {
            if(this.addExam.anchor_text == "" || this.addExam.link_to == "" || this.addExam.topic == "") {
                swal.fire(
                    'Error',
                    'Please Fill up all fields.',
                    'error',
                )

                return false;
            }

            // if 2nd attempt, duration must be <= 0
            if (this.addExam.attempt === 2) {
                if (this.addExam.duration > 0) {
                    swal.fire(
                        'Error',
                        'Writer is not yet eligible to take 2nd attempt exam. Try again after ' + this.addExam.duration
                        + ' day(s)',
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
                        'Success',
                        'Exam Successfully Created',
                        'success',
                    )

                    $("#modalEditWriterValidate").modal('hide');
                }).catch((err) => {
                    loader.hide();
                    console.log(err)
                })
        },

        doCreateExam(writer_id, writer_username, attempt, duration) {
            this.addExam.topic = '';
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
</style>

<style>
    .style-chooser input {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
    }
</style>
