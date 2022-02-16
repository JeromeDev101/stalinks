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
                <div class="alert alert-info">
                    <p><b>Sorry!</b> you can't still write an article unless you passed the exam. Please complete the Exam. Go to <b>Writer's Validation</b> menu</p>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Filter</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> Show Filter
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">

                            <div class="col-md-12 col-lg-3">
                                <div class="form-group">
                                    <label for="">Search ID article</label>
                                    <input type="text" class="form-control" v-model="filterModel.search_article" name="" aria-describedby="helpId" placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search ID backlink</label>
                                    <input type="text" class="form-control" v-model="filterModel.search_backlink" name="" aria-describedby="helpId" placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <select class="form-control" name="" v-model="filterModel.language_id">
                                        <option value="">Select Language</option>
                                        <option value="none">None</option>
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
                                    <label>Writer</label>
                                    <select name="" class="form-control" v-model="filterModel.writer">
                                        <option value="">All</option>
                                        <option v-for="option in listWriter.data" v-bind:value="option.id">
                                            {{ option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="" class="form-control" v-model="filterModel.status">
                                        <option value="">All</option>
                                        <option value="Queue">Queue</option>
                                        <option value="In Writing">In Writing</option>
                                        <option value="Done">Done</option>
                                        <option value="Canceled">Canceled</option>
                                        <option value="Issue">Issue</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Topic</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.topic">
                                        <option value="">All</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select multiple v-model="filterModel.topic" :options="topic" :searchable="false" placeholder="All"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Accept Casino & Betting Sites</label>
                                    <select name="" class="form-control" v-model="filterModel.casino_sites">
                                        <option value="">All</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>



                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
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
                        <h3 class="card-title text-primary">Articles</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="mb-3 bg-info">
                            <tr>
                                <td class="p-3">
                                    Queue
                                    <b>{{' ('+ statusSummary.num_processing +')' }}</b>
                                </td>
                                <td class="p-3">
                                    In Writing
                                    <b>{{' ('+ statusSummary.writing +')' }}</b>
                                </td>
                                <td class="p-3">
                                    Done
                                    <b>{{' ('+ statusSummary.num_done +')' }}</b>
                                </td>
                                <td class="p-3">
                                    Canceled
                                    <b>{{' ('+ statusSummary.num_canceled +')' }}</b>
                                </td>
                                <td class="p-3">
                                    Issue
                                    <b>{{' ('+ statusSummary.num_issue +')' }}</b>
                                </td>
                            </tr>
                        </table>

                        <div v-if="isProcessing && (user.isOurs == 1 && user.role.id == 4 && user.user_type.is_exam_passed == 1)" class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                            <strong>Reminder: </strong> Your account is currently on process. Please contact the
                                                        administrator to process you account status.
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
                                @click="clearModels(); clearEditorImages()" data-target="#modal-add-article" class="btn btn-success float-right"><i
                            class="fa fa-plus"></i> Create Article
                        </button>

                        <span class="pagination-custom-footer-text">
                        <b>Showing {{ listArticles.data.from }} to {{ listArticles.data.to }} of {{ listArticles.data.total }} entries.</b>
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
                                <div class="btn-group" >
                                    <button
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
                        <h5 class="modal-title">Edit Content | Article ID: <b class="text-primary">{{ contentModel.id }}</b></h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" v-model="contentModel.title" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" v-model="contentModel.anchor_text" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">URL Publisher</label>
                                    <input type="text" v-model="contentModel.url_publisher" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Link To</label>
                                    <input type="text" v-model="contentModel.link" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Seller</label>
                                    <input type="text" v-model="contentModel.seller" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Buyer</label>
                                    <input type="text" v-model="contentModel.buyer" :disabled="true" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>

                            <!-- <div class="col-sm-6" v-if="user.isOurs != '1'">
                                <div class="form-group">
                                    <label for="">Writer Price</label>
                                    <input type="number" v-model="contentModel.price" class="form-control" name="" aria-describedby="helpId" placeholder="0.00">
                                </div>
                            </div> -->

                            <div class="col-sm-12" v-if="user.isOurs == '0' || user.role_id == 4">
                                <div class="form-group">
                                    <label for="">Status Writer</label>
                                    <select name="" class="form-control" v-model="contentModel.status">
                                        <option value="">Select Status</option>
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
                                        <span class="font-weight-bold">Issue Details</span>
                                        <br>
                                        <small class="text-primary">Note: If articles are already revised, set the writer status to 'Done'</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-12 mb-2">
                                            <label>Reason:</label>
                                            <input v-model="issueModel.reason" type="text" class="form-control" disabled>
                                        </div>

                                        <div class="col-12 mb-2">
                                            <label>Details:</label>
                                            <textarea v-model="issueModel.details" class="form-control" cols="10" disabled>

                                            </textarea>
                                        </div>

                                        <div class="col-12">
                                            <label>File preview:</label>
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
                                    <label for="">Note</label>
                                    <textarea class="form-control" cols="30" rows="3" v-model="contentModel.note"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Meta Keywords</label>
                                    <textarea class="form-control" cols="30" rows="3" v-model="contentModel.meta_keyword"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Meta Description</label>
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
                        <span class="text-primary mr-auto">Press 'Ctrl + Shift + F' for full screen</span>
                        <button @click="clearQuery" type="button" class="btn btn-default">Close</button>
                        <button
                            v-show="contentModel.backlink_status != 'Canceled'"
                            type="button"
                            class="btn btn-primary"

                            @click="submitSave">

                            Save
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
                        <h5 class="modal-title">Create Article</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.backlink}">
                                    <label for="">Select Backlink</label>
                                    <select name="" class="form-control" v-on:change="displayInfo" v-model="viewModel.backlink">
                                        <option value="">Select Backlinks</option>
                                        <option v-for="option in listBacklinks.data" v-bind:value="option">
                                            ID: {{ option.id }} URL: {{ option.publisher.url }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.backlink" v-for="err in messageForms.errors.backlink" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" v-model="addModel.title" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" class="form-control" v-model="addModel.anchor_text" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Link To</label>
                                    <input type="text" class="form-control" v-model="addModel.link" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <input type="text" class="form-control" v-model="addModel.status" :disabled="true">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.writer}">
                                    <label for="">Writer</label>
                                    <select name="" class="form-control" v-model="addModel.writer">
                                        <option v-for="option in listWriter.data" v-bind:value="option.id">
                                            ID: {{ option.id }} Name: {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.writer" v-for="err in messageForms.errors.writer" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addArticle">Add Article</button>
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
                        <h5 class="modal-title">File</h5>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                    num_canceled: 0,
                    num_done: 0,
                    num_issue: 0,
                    num_processing: 0,
                    writing: 0
                }
            }
        },

        async created() {
            this.getListArticles();
            this.getListBacklinks();
            this.getListWriter();
            this.getListCountries();
            this.checkTeam();
            this.getListWriter();
            this.getListLanguages();
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
                return [
                    {
                        prop : '_index',
                        name : '#',
                        width : '50',
                        isHidden: false
                    },
                    {
                        prop : 'id',
                        name : 'ID Article',
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : 'id_backlink',
                        name : 'ID Backlinks',
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Language',
                        actionName : 'languageData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : 'writer',
                        name : 'Writer',
                        sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Topic',
                        actionName : 'topicData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name :
                            'Accept Casino & Betting Sites',
                        actionName : 'casinoSiteData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Date Start',
                        actionName : 'dateStartData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Date Complete',
                        actionName : 'dateCompleteData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Status',
                        actionName : 'statusData',
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Action',
                        actionName : 'actionButton',
                        width: 100,
                        isHidden: false
                    }
                ];
            }
        },

        methods: {

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

            modalCloser() {

                if (this.compareData() && !this.isSaved) {
                    swal.fire({
                        title : "Are you sure?",
                        text : "Changes will not be saved.",
                        icon : "warning",
                        showCancelButton : true,
                        confirmButtonText : 'Yes',
                        cancelButtonText : 'No'
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

            async getListWriter(params) {
                await this.$store.dispatch('actionGetListWriter', params);
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

                this.displayTotal();
                this.viewArticle();
                loader.hide();
            },

            async getListBacklinks(params){
                await this.$store.dispatch('actionGetListBacklinks', params);
            },

            async getListWriter(params){
                await this.$store.dispatch('actionGetListWriter', params);
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
                    'Updated!',
                    'Article is updated.',
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
                    title: '',
                    anchor_text: '',
                    link: '',
                    status: '',
                }

                this.viewModel = {
                    backlink: '',
                }
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
