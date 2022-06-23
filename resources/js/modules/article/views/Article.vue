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

            <!-- alert for external writers that doesn't yet passed the exam -->
            <div class="col-md-12" v-if="user.isOurs == 1 && user.role.id == 4 && user.user_type.is_exam_passed != 1">
                <div class="alert alert-warning">
                    <p class="mb-0">
                        <b>{{ $t('message.article.alert_title_1') }}</b>
                        {{ $t('message.article.alert_title_2') }}
                    </p>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.article.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.article.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">

                            <div class="col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_id') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.search_article"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.article.filter_type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_id_bl') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.search_backlink"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.article.filter_type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_lang') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.language_id">
                                        <option value="">{{ $t('message.article.filter_sel_lang') }}</option>
                                        <option value="none">{{ $t('message.article.filter_none') }}</option>
                                        <option v-for="option in listLanguages.data"
                                                :value="option.id"
                                                :key="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" v-if="user.isOurs === 0">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_writer') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.writer">
                                        <option value="">{{ $t('message.article.all') }}</option>
                                        <option v-for="option in validWriters" v-bind:value="option.id">
                                            {{ option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_status') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.status">
                                        <option value="">{{ $t('message.article.all') }}</option>
                                        <option value="Queue">{{ $t('message.article.filter_queue') }}</option>
                                        <option value="In Writing">{{ $t('message.article.filter_in_wr') }}</option>
                                        <option value="Done">{{ $t('message.article.filter_done') }}</option>
                                        <option value="Canceled">{{ $t('message.article.filter_cancel') }}</option>
                                        <option value="Issue">{{ $t('message.article.filter_issue') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_topic') }}</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.topic">
                                        <option value="">All</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select
                                        multiple
                                        v-model="filterModel.topic"
                                        :options="topic"
                                        :searchable="false"
                                        :placeholder="$t('message.article.all')"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_ac_cb') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.casino_sites">
                                        <option value="">{{ $t('message.article.all') }}</option>
                                        <option value="yes">{{ $t('message.article.yes') }}</option>
                                        <option value="no">{{ $t('message.article.no') }}</option>
                                    </select>
                                </div>
                            </div>



                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.article.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.article.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i>
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
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.article.ar_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body">
<!--                        <table class="mb-3 bg-info">-->
<!--                            <tr>-->
<!--                                <td class="p-3">-->
<!--                                    Queue-->
<!--                                    <b>{{' ('+ statusSummary.total_queue +')' }}</b>-->
<!--                                </td>-->
<!--                                <td class="p-3">-->
<!--                                    In Writing-->
<!--                                    <b>{{' ('+ statusSummary.total_in_writing +')' }}</b>-->
<!--                                </td>-->
<!--                                <td class="p-3">-->
<!--                                    Done-->
<!--                                    <b>{{' ('+ statusSummary.total_done +')' }}</b>-->
<!--                                </td>-->
<!--                                <td class="p-3">-->
<!--                                    Canceled-->
<!--                                    <b>{{' ('+ statusSummary.total_cancelled +')' }}</b>-->
<!--                                </td>-->
<!--                                <td class="p-3">-->
<!--                                    Issue-->
<!--                                    <b>{{' ('+ statusSummary.total_issue +')' }}</b>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        </table>-->

                        <div class="d-flex flex-row flex-nowrap overflow-auto bg-info mb-4 text-center rounded">
                            <div class="col p-3">
                                {{ $t('message.article.filter_queue') }}
                                <b>({{ statusSummary.total_queue ? statusSummary.total_queue : '0' }})</b>
                            </div>

                            <div class="col p-3">
                                {{ $t('message.article.filter_in_wr') }}
                                <b>({{ statusSummary.total_in_writing ? statusSummary.total_in_writing : '0' }})</b>
                            </div>

                            <div class="col p-3">
                                {{ $t('message.article.filter_done') }}
                                <b>({{ statusSummary.total_done ? statusSummary.total_done : '0' }})</b>
                            </div>

                            <div class="col p-3">
                                {{ $t('message.article.filter_cancel') }}
                                <b>({{ statusSummary.total_cancelled ? statusSummary.total_cancelled : '0' }})</b>
                            </div>

                            <div class="col p-3">
                                {{ $t('message.article.filter_issue') }}
                                <b>({{ statusSummary.total_issue ? statusSummary.total_issue : '0' }})</b>
                            </div>
                        </div>

                        <div
                            v-if="isProcessing && (user.isOurs == 1 && user.role.id == 4 && user.user_type.is_exam_passed == 1)"
                            class="alert alert-info alert-dismissible fade show mt-3"
                            role="alert">

                            <strong>{{ $t('message.article.ar_reminder_1') }}</strong>
                            {{ $t('message.article.ar_reminder_2') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="input-group input-group-sm float-right" style="width: 100px">
                            <select name=""
                                    class="form-control float-right"
                                    @change="doSearch"
                                    v-model="filterModel.paginate"
                                    style="height: 37px;">
                                <option v-for="option in paginate" v-bind:value="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <button v-if="isTeam"
                                data-toggle="modal"
                                @click="clearModels(); clearEditorImages()" data-target="#modal-add-article" class="btn btn-success float-right mr-2 mb-2"><i
                            class="fa fa-plus"></i> {{ $t('message.article.ar_create') }}
                        </button>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b>{{ $t('message.others.table_entries', { from: listArticles.data.from, to: listArticles.data.to, end: listArticles.data.total }) }}</b>
                        </span>

                        <vue-virtual-table
                            v-if="!tableLoading"
                            width="100%"
                            :height="600"
                            :bordered="true"
                            :item-height="60"
                            :config="tableConfig"
                            :data="listArticles.data.data">
                            <template
                                slot-scope="scope"
                                slot="actionButton">
                                <div class="btn-group">
                                    <button
                                        v-if="user.role_id === 4"
                                        :disabled="!isStatusOnQueue(scope.row) || isProcessing"
                                        :id="'accept-article-' + scope.row.id"
                                        class="btn btn-default"
                                        :title="$t('message.article.ar_accept_article')"

                                        @click="acceptArticle(scope.row)">

                                        <i class="fas fa-vote-yea"></i>
                                    </button>

                                    <button
                                        v-if="(user.isOurs === 0 || user.isOurs === 1) && !isStatusOnQueue(scope.row)"
                                        :id="'article-' + scope.row.id"
                                        :disabled="isProcessing"
                                        class="btn btn-default"
                                        data-toggle="modal"
                                        data-target="#modal-content-edit"

                                        @click="doUpdate(scope.row.backlink, scope.row)">

                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="languageData">
                                {{ scope.row.language == null ?
                                'N/A':
                                scope.row.language.name }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="topicData">
                                {{ scope.row.topic == null ?
                                'N/A' :
                                scope.row.topic }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="casinoSiteData">
                                {{ scope.row.casino_sites ==
                            null ?
                                'N/A' :
                                scope.row.casino_sites }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="dateStartData">
                                {{ scope.row.date_start == null ?
                                '-':scope.row.date_start }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="dateCompleteData">
                                {{ scope.row.date_complete ==
                            null ?
                                '-':scope.row.date_complete}}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="statusData">

                                {{ scope.row.backlink_status == 'Issue' || scope.row.backlink_status == 'Canceled'
                                ? scope.row.backlink_status
                                : scope.row.status_writer == null
                                    ? 'Queue'
                                    : scope.row.status_writer }}

                                <span
                                    v-if="scope.row.id_writer !== null
                                    && scope.row.status_writer === 'In Writing'
                                    && scope.row.reminded_via !== null
                                    && scope.row.reminded_via !== '24'
                                    && scope.row.content === null"
                                    class="badge badge-warning">
                                    {{ $t('message.article.ar_reminder_sent') }}

                                    <span>
                                       - {{ scope.row.reminded_via }}h
                                    </span>
                                </span>
                            </template>

                        </vue-virtual-table>
                        <pagination :data="listArticles" @pagination-change-page="getListArticles" :limit="8"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="modal fade" id="modal-content-edit" tabindex="-1" ref="modalComposeArticle" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $t('message.article.ec_title') }} | {{ $t('message.article.ec_article_id') }}
                            <b class="text-primary">{{ contentModel.id }}</b>
                        </h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>

                        <button
                            v-if="contentModel.backlink_status !== 'Canceled'
                            && (contentModel.status === 'In Writing' || contentModel.status === 'Issue')
                            && contentModel.status !== null
                            && contentModel.writer !== null
                            && (user.isOurs === 1 && user.role_id === 4) || (user.isOurs === 0 && (user.role_id === 8 || user.role_id === 4)) || user.isAdmin"
                            class="btn btn-danger"

                            @click="cancelWritingArticle(contentModel)">

                            <i class="fas fa-times-circle"></i>
                            {{ $t('message.article.ec_cancel_wr') }}
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_in_title') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.title"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_at') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.anchor_text"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_url_pub') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.url_publisher"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_link') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.link"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_seller') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.seller"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_buyer') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.buyer"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <!-- <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label>Writer Price</label>
                                    <input type="number" v-model="contentModel.price" class="form-control" name="" aria-describedby="helpId" placeholder="0.00">
                                </div>
                            </div> -->

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_writer') }}</label>
                                    <input
                                        type="text"
                                        v-model="contentModel.writer"
                                        :disabled="true"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs == '0' || user.role_id == 4">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_status_writer') }}</label>
                                    <select name="" class="form-control" v-model="contentModel.status">
                                        <option value="">{{ $t('message.article.ec_select_status') }}</option>
                                        <option v-for="option in writer_status" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12" v-if="contentModel.backlink_status == 'Issue' && contentModel.status == 'Issue'">
                                <div class="card border border-danger">
                                    <div class="card-header">
                                        <i class="fa fa-exclamation-circle text-danger"></i>
                                        <span class="font-weight-bold">{{ $t('message.article.ec_issue_det') }}</span>
                                        <br>
                                        <small class="text-primary">{{ $t('message.article.ec_note') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12 mb-2">
                                            <label>{{ $t('message.article.ec_reason') }}</label>
                                            <input v-model="issueModel.reason" type="text" class="form-control" disabled>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <label>{{ $t('message.article.ec_details') }}</label>
                                            <textarea v-model="issueModel.details" class="form-control" cols="10" disabled>

                                            </textarea>
                                        </div>

                                        <div class="col-12">
                                            <label>{{ $t('message.article.ec_file_prev') }}</label>
                                            <button
                                                :disabled="issueModel.file === '' || issueModel.file === null"
                                                data-toggle="modal"
                                                data-target="#modal-view-article-issue-cancel-file"
                                                class="btn btn-default">

                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_note_2') }}</label>
                                    <textarea class="form-control" cols="30" rows="3" v-model="contentModel.note"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_mk') }}</label>
                                    <textarea class="form-control" cols="30" rows="3" v-model="contentModel.meta_keyword"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_md') }}</label>
                                    <textarea class="form-control" cols="30" rows="3" v-model="contentModel.meta_description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <!-- <tinymce id="articleContent" v-model="data" :other_options="options"></tinymce> -->

                                <tiny-editor
                                    v-model="data"
                                    ref="composeEditorArticle"
                                    editor-id="composeEditorArticle">

                                </tiny-editor>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
<!--                        <span class="text-primary mr-auto">Press 'Ctrl + Shift + F' for full screen</span>-->
                        <button @click="clearQuery" type="button" class="btn btn-default">
                            {{ $t('message.article.close') }}
                        </button>
                        <button
                            v-show="contentModel.backlink_status != 'Canceled'"
                            type="button"
                            class="btn btn-primary"

                            @click="submitSave">

                            {{ $t('message.article.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Content -->

        <!-- Modal Add Article -->
        <div class="modal fade" id="modal-add-article" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.article.ca_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.backlink}">
                                    <label>{{ $t('message.article.ca_sel_bl') }}</label>
                                    <select name="" class="form-control" v-on:change="displayInfo" v-model="viewModel.backlink">
                                        <option value="">{{ $t('message.article.ca_sel_bl_2') }}</option>
                                        <option v-for="option in listBacklinks.data" v-bind:value="option">
                                            {{ $t('message.article.ca_id') }} {{ option.id }} {{ $t('message.article.ca_URL') }} {{ option.publisher.url }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.backlink" v-for="err in messageForms.errors.backlink" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_in_title') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.title" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_at') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.anchor_text" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ec_link') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.link" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.ca_status') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.status" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.article.filter_lang') }}</label>
                                    <select disabled="true" class="form-control" name="" v-model="addModel.language_id">
                                        <option v-for="option in listLanguages.data"
                                                :value="option.id"
                                                :key="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.writer}">
                                    <label>{{ $t('message.article.filter_writer') }}</label>
                                    <select name="" class="form-control" v-model="addModel.writer">
                                        <option v-for="option in validWritersWithLanguage" v-bind:value="option.id">
                                            {{ $t('message.article.ca_id') }}
                                            {{ option.id }}
                                            {{ $t('message.article.ca_name') }}
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.writer" v-for="err in messageForms.errors.writer" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.article.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="addArticle">
                            {{ $t('message.article.ca_add_ar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Article -->

        <!-- Modal View Issue File -->
        <div
            tabindex="-1"
            role="dialog"
            class="modal fade"
            id="modal-view-article-issue-cancel-file"
            aria-hidden="true"
            aria-labelledby="modelTitleId">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.article.ca_file') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid"
                                     :src="issueModel.file"
                                     alt="Issue file">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.article.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Issue File -->
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import 'tinymce/skins/lightgray/skin.min.css';
    import VueVirtualTable from 'vue-virtual-table';
    import axios from 'axios';
    import TinyEditor from "../../../components/editor/TinyEditor";

    export default {
        components: {
            VueVirtualTable,
            TinyEditor,
        },
        data() {
            return {
                paginate: ['50','150','250','350','All'],
                data: '',
                options: {
                    height: 500,
                    // target_list: false,
                    // relative_urls : false,
                    // remove_script_host : false,
                    // document_base_url : 'http://www.example.com/path1/'
                    allow_script_urls: false,
                },

                writer_status: ['In Writing', 'Issue', 'Done'],
                viewModel: {
                    backlink: '',
                },
                addModel: {
                    backlink: '',
                    title: '',
                    anchor_text: '',
                    link_to: '',
                    status: '',
                    writer: '',
                    language_id: '',
                },
                isPopupLoading: false,

                contentModel: {
                    id: '',
                    title: '',
                    anchor_text: '',
                    price: '',
                    status: '',
                    url_publisher: '',
                    link: '',
                    seller: '',
                    buyer: '',
                    writer: '',
                    id_writer: '',
                    meta_description: '',
                    note: '',
                    meta_keyword: '',
                },

                contentModelTemp: {
                    title: '',
                    status: '',
                    note: '',
                    meta_keyword: '',
                    meta_description: '',
                    data: ''
                },

                issueModel: {
                    reason: '',
                    details: '',
                    file: '',
                },

                isSaved: false,

                filterModel: {
                    paginate: this.$route.query.paginate || '50',
                    search_article: this.$route.query.search_article || '',
                    search_backlink: this.$route.query.search_backlink || '',
                    language_id: this.$route.query.language_id || '',
                    status: this.$route.query.status || '',
                    casino_sites: this.$route.query.casino_sites || '',
                    topic: this.$route.query.topic || '',
                    writer: this.$route.query.writer || '',
                },

                searchLoading: false,
                tableLoading: false,
                isTeam: false,
                id_article: this.$route.query.id || '',
                topic: [
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
                    'Unlisted',
                ],
                isSearching: false,
                statusSummary: {
                    total_queue: 0,
                    total_in_writing: 0,
                    total_done: 0,
                    total_cancelled: 0,
                    total_issue: 0
                },

                externalWriters: [],
            }
        },

        async created() {
            this.getListArticles();
            this.getListBacklinks();
            this.getListWriter();
            this.getListCountries();
            this.checkTeam();
            this.getListLanguages();
            this.getValidExternalWriters();
        },

        computed: {
            ...mapState({
                listArticles: state => state.storeArticles.listArticles,
                listBacklinks: state => state.storeArticles.listBacklinks,
                listWriter: state => state.storeArticles.listWriter,
                messageForms: state => state.storeArticles.messageForms,
                listCountries: state => state.storeArticles.listCountries,
                user: state => state.storeAuth.currentUser,
                listLanguages : state => state.storePublisher.listLanguages,
            }),

            isProcessing() {
                // return this.user.isOurs === 0
                //     ? false
                //     : this.user.registration.account_validation !== 'valid';

                let result = true;

                if(this.user.isOurs === 0) {
                    result = false;
                } else if(this.user.isOurs === 1 && this.user.role.id === 6) {
                    result = false;
                } else if(this.user.isOurs === 1 && this.user.role.id === 4 && this.user.user_type.is_exam_passed == 1) {
                    result = false;
                }

                return result;
            },

            tableConfig() {
                let self = this;
                return [
                    {
                        prop : '_index',
                        name : '#',
                        width : '50',
                        isHidden: false
                    },
                    {
                        prop : 'id',
                        name : self.$t('message.article.ar_id'),
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : 'id_backlink',
                        name : this.$t('message.article.ar_id_bl'),
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.filter_lang'),
                        actionName : 'languageData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : 'writer',
                        name : this.$t('message.article.filter_writer'),
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.filter_topic'),
                        actionName : 'topicData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.filter_ac_cb'),
                        actionName : 'casinoSiteData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.ar_date_start'),
                        actionName : 'dateStartData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.ar_date_complete'),
                        actionName : 'dateCompleteData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.filter_status'),
                        actionName : 'statusData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.article.ar_action'),
                        actionName : 'actionButton',
                        width: 100,
                        isHidden: false
                    }
                ];
            },

            validWriters () {
                return this.listWriter.data.concat(this.externalWriters)
            },

            validWritersWithLanguage () {
                let self = this;

                return self.addModel.language_id
                    ? self.listWriter.data.concat(this.externalWriters.filter(function (item) {
                        return JSON.parse(item.language_id).includes(self.addModel.language_id)
                    }))
                    : self.listWriter.data.concat(this.externalWriters)
            },
        },

        methods: {

            acceptArticle (data) {
                let self = this;

                if (!this.isStatusOnQueue(data) || this.isProcessing) {
                    let error = !this.isStatusOnQueue(data)
                        ? self.$t('message.article.alert_article_queue')
                        : this.isProcessing
                            ? self.$t('message.article.alert_val_exam')
                            : self.$t('message.article.alert_invalid_att')

                    swal.fire(
                        self.$t('message.article.alert_invalid'),
                        error,
                        'error'
                    )
                } else {
                    swal.fire({
                        title : self.$t('message.article.alert_accept'),
                        text : self.$t('message.article.alert_accept_note'),
                        icon : "question",
                        showCancelButton : true,
                        confirmButtonText : self.$t('message.article.yes'),
                        cancelButtonText : self.$t('message.article.no')
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.submitAcceptArticle(data);
                        }
                    });
                }
            },

            submitAcceptArticle (data) {
                let self = this;
                let loader = this.$loading.show();

                axios.post('/api/accept-decline-article', {
                    writer_id: this.user.id,
                    article_id: data.id,
                    mode: 'accept'
                }).then((response) => {
                    swal.fire(
                        self.$t('message.article.alert_success'),
                        self.$t('message.article.alert_accepted'),
                        'success'
                    )

                    this.getListArticles();

                    loader.hide();
                }).catch((error) => {
                    swal.fire(
                        self.$t('message.article.alert_err'),
                        self.$t('message.article.alert_err_accept'),
                        'error'
                    )

                    loader.hide();
                });
            },

            cancelWritingArticle (data) {
                let self = this;

                swal.fire({
                    title : self.$t('message.article.alert_cancel'),
                    text : self.$t('message.article.alert_cancel_note'),
                    icon : "question",
                    showCancelButton : true,
                    confirmButtonText : self.$t('message.article.yes'),
                    cancelButtonText : self.$t('message.article.no')
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.submitCancelArticle(data);
                    }
                });
            },

            submitCancelArticle (data) {
                let self = this;
                let loader = this.$loading.show();

                axios.post('/api/accept-decline-article', {
                    writer_id: data.id_writer,
                    article_id: data.id,
                    mode: 'cancel'
                }).then((response) => {
                    swal.fire(
                        self.$t('message.article.alert_success'),
                        self.$t('message.article.alert_cancelled'),
                        'success'
                    )

                    $("#modal-content-edit").modal('hide')

                    this.getListArticles();

                    loader.hide();
                }).catch((error) => {
                    swal.fire(
                        self.$t('message.article.alert_err'),
                        self.$t('message.article.alert_err_cancel'),
                        'error'
                    )

                    loader.hide();
                });
            },

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            displayTotal() {
                let _status = this.listArticles.summary

                for (var index in _status) {
                    this.statusSummary.num_processing += parseInt(_status[index].num_processing);
                    this.statusSummary.writing += parseInt(_status[index].writing);
                    this.statusSummary.num_done += parseInt(_status[index].num_done);
                    this.statusSummary.num_issue += parseInt(_status[index].num_issue);
                    this.statusSummary.num_canceled += parseInt(_status[index].num_canceled);
                }
            },

            displaySummaryTotal () {
                this.statusSummary = this.listArticles.summary;
            },

            modalCloser() {
                let self = this;

                if (this.compareData() && !this.isSaved) {
                    swal.fire({
                        title : self.$t('message.article.alert_confirm'),
                        text : self.$t('message.article.alert_changes'),
                        icon : "warning",
                        showCancelButton : true,
                        confirmButtonText : self.$t('message.article.yes'),
                        cancelButtonText : self.$t('message.article.no')
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.$refs.composeEditorArticle.deleteImages('Update', this.contentModelTemp.data);

                            this.closeModal()
                            this.clearModel()
                        }
                    });

                } else {
                    this.clearModel()
                    this.closeModal()
                }

            },

            closeModal() {
                let element = this.$refs.modalComposeArticle
                $(element).modal('hide')
            },

            clearModel() {
                this.data = '';
            },

            async getListArticles(page = 1){
                let loader = this.$loading.show();
                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetListArticle', {
                    params: {
                        page: page,
                        paginate: this.filterModel.paginate,
                        search_backlink: this.filterModel.search_backlink,
                        search_article: this.filterModel.search_article,
                        language_id: this.filterModel.language_id,
                        status: this.filterModel.status,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        writer: this.filterModel.writer,
                    }
                });
                this.searchLoading = false;
                this.isSearching = false;

                // this.displayTotal();
                this.viewArticle();
                this.displaySummaryTotal();
                loader.hide();
            },

            async getListBacklinks(params){
                await this.$store.dispatch('actionGetListBacklinks', params);
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter', params);
            },

            getValidExternalWriters () {
                axios.get('/api/valid-external-writers')
                .then((res) => {
                    this.externalWriters = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            checkTeam() {
                if( this.user.isOurs == 0 ){
                    this.isTeam = true;
                }
            },

            async getArticleInfo() {
                const res = await axios.get('/api/article-list', {
                    params: {
                        search_article: this.id_article
                    }
                }).then(response => response.data);

                return res;
            },

            viewArticle() {
                // let id = this.id_article;
                // let articles = this.listArticles.data;
                // let article = '';

                // console.log(this.getArticleInfo())
                if( this.id_article ) {
                    this.getArticleInfo().then((res) => {
                        let article = res.data.data[0];

                        if( article ){
                            this.doUpdate(article.backlink, article);
                            $("#modal-content-edit").modal('show')
                        }
                    })
                }


                // articles.forEach(function(item, index){
                //     if( item.id == id){
                //         article = item;
                //     }
                // })

                // if( article ){
                //     this.doUpdate(article.backlinks, article);
                //     $("#modal-content-edit").modal('show')
                // }
            },

            clearQuery() {

                this.modalCloser();

                let id = this.id_article;
                if( id != '' ){
                    this.$router.replace({'query': null});
                }
            },

            compareData() {
                return (
                    (this.contentModel.title !== this.contentModelTemp.title)
                    || (this.contentModel.status !== this.contentModelTemp.status)
                    || (this.contentModel.note !== this.contentModelTemp.note)
                    || (this.contentModel.meta_keyword !== this.contentModelTemp.meta_keyword)
                    || (this.contentModel.meta_description !== this.contentModelTemp.meta_description)
                    || (this.data !== this.contentModelTemp.data)
                )
            },

            doUpdate(backlink, article){

                this.clearIssue();

                this.clearMessageform()
                this.data = article.content == null ? '':article.content;
                this.contentModel.price = article.price == null ? '':article.price.price;
                this.contentModel.id = article.id;
                this.contentModel.title = backlink == null ? '':backlink.title;
                this.contentModel.anchor_text = backlink == null ? '':backlink.anchor_text;
                this.contentModel.status = article.status_writer == null ? '':article.status_writer;
                this.contentModel.link = backlink == null ? '':backlink.link;
                this.contentModel.url_publisher = backlink == null ? '':backlink.publisher.url;
                this.contentModel.seller = backlink == null ? '':backlink.publisher.user.name;
                this.contentModel.buyer = backlink == null ? '':backlink.user.name;
                this.contentModel.writer = article.writer !== null ? article.writer : null;
                this.contentModel.id_writer = article.id_writer;
                this.contentModel.backlink_status = article.backlink_status;
                this.contentModel.meta_description = article.meta_description;
                this.contentModel.meta_keyword = article.meta_keyword;
                this.contentModel.note = article.note;

                // add article data to temp
                this.contentModelTemp.data = this.data;
                this.contentModelTemp.title = this.contentModel.title;
                this.contentModelTemp.status = this.contentModel.status;
                this.contentModelTemp.note = this.contentModel.note;
                this.contentModelTemp.meta_keyword = this.contentModel.meta_keyword;
                this.contentModelTemp.meta_description = this.contentModel.meta_description;

                // if issue

                this.issueModel.reason = backlink.reason
                this.issueModel.details = backlink.reason_detailed
                this.issueModel.file = backlink.reason_file

                this.isSaved = false;

                // when opening another update modal, always clear the images on the editor
                this.$refs.composeEditorArticle.clearAddImages();

                $('#modal-content-edit').modal('show');
            },

            clearIssue() {
                this.issueModel = {
                    reason: '',
                    details: '',
                    file: '',
                }
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListArticles({
                    params: {
                        paginate: this.filterModel.paginate,
                        search_backlink: this.filterModel.search_backlink,
                        search_article: this.filterModel.search_article,
                        language_id: this.filterModel.language_id,
                        status: this.filterModel.status,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        writer: this.filterModel.writer,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    paginate: '50',
                    search_article: '',
                    search_backlink: '',
                    language_id: '',
                    status: '',
                    casino_sites: '',
                    topic: '',
                    writer: '',
                }

                this.getListArticles({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async submitSave() {
                let self = this;
                this.isPopupLoading = true;
                this.contentModel.num_words = this.$refs.composeEditorArticle.wordCount();
                await this.$store.dispatch('actionSaveContent', {
                    data: this.data,
                    content: this.contentModel
                });
                this.isPopupLoading = false;

                // delete removed images before saving
                this.$refs.composeEditorArticle.deleteImages('Update');
                this.isSaved = true;

                this.getListArticles();

                await swal.fire(
                    self.$t('message.article.alert_updated'),
                    self.$t('message.article.alert_article_updated'),
                    'success'
                )
            },

            displayInfo() {
                let backlink = this.viewModel.backlink;
                this.addModel.backlink = backlink ? backlink.id:'';
                this.addModel.status = backlink.status;
                this.addModel.anchor_text = backlink.anchor_text;
                this.addModel.title = backlink.title;
                this.addModel.language_id = backlink.publisher.language_id;
                this.addModel.link_to = backlink ? backlink.link:'';
            },

            async addArticle() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddArticle', this.addModel);
                this.isPopupLoading = false;

                if( this.messageForms.action == 'success' ){
                    this.clearModels();
                    this.getListArticles();
                }
            },

            clearModels() {
                this.addModel = {
                    backlink: '',
                    title: '',
                    anchor_text: '',
                    link_to: '',
                    status: '',
                    writer: '',
                    language_id: '',
                }

                this.viewModel = {
                    backlink: '',
                }
            },

            isStatusOnQueue (data) {
                return (data.backlink_status === 'Issue' || data.backlink_status === 'Canceled')
                    ? false
                    : data.status_writer === null;
            },

            clearEditorImages () {
                // when opening another update modal, always clear the images on the editor
                // this.$refs.composeEditorArticle.clearAddImages();
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        },
    }
</script>
