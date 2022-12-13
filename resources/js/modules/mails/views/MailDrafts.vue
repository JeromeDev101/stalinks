<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">

                <div class="card-header">

                    <h3 class="card-title text-primary">{{ $t('message.draft.d_title') }}</h3>

                    <div class="card-tools">
                        <div class="input-group">
                            <input
                                v-model="filterModel.search"
                                type="text"
                                :placeholder="$t('message.draft.d_search')"
                                class="form-control input-sm">

                            <span class="input-group-btn">
                                <button
                                    type="button"
                                    class="btn btn-default"
                                    :title="$t('message.draft.search')"

                                    @click="getDrafts()">
                                    <i class="fa fa-search"></i>
                                </button>

                                <button
                                    type="button"
                                    :title="$t('message.draft.clear')"
                                    class="btn btn-default"

                                    @click="clearSearch()">
                                    <i class="fa fa-times-circle"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="card-body">

                    <!-- table buttons/headers/pagination -->
                    <div class="mailbox-controls">

                        <div class="btn-group" role="group" aria-label="Draft buttons">
                            <button type="button" class="btn btn-default">
                                <input type="checkbox" @click="selectAllIds" v-model="isAllSelected">
                            </button>

                            <button type="button" title="Refresh" class="btn btn-default" @click="getDrafts()">
                                <i class="fas fa-sync"></i>
                            </button>

                            <button
                                v-if="user.permission_list.includes('delete-mails-drafts')"
                                type="button"
                                title="Trash"
                                class="btn btn-default"
                                :disabled="selectedIds.length === 0"

                                @click="deleteSelectedDrafts()">

                                <i class="fa fa-fw fa-trash"></i>
                            </button>
                        </div>

                        <div v-if="drafts" class="float-right" style="text-align:center;">
                            <div v-if="drafts.total" class="d-inline-block">
                                {{ drafts.from + '-' + drafts.to + " / " + drafts.total }}
                            </div>

                            <div class="d-inline-block ml-2">
                                <pagination
                                    size="small"
                                    :limit="5"
                                    :data="drafts"
                                    @pagination-change-page="getDrafts">

                                </pagination>
                            </div>
                        </div>

                    </div>

                    <!-- table -->
                    <div class="table-responsive">
                        <table class="table table-condensed table-hover" style="table-layout: auto; width: 100%">
                            <tbody>
                                <tr v-if="drafts.total === 0">
                                    <td class="text-muted text-center">{{ $t('message.draft.d_no_drafts') }}</td>
                                </tr>

                                <tr
                                    v-else
                                    v-for="(draft, index) in drafts.data"
                                    :key="index"
                                    style="cursor:pointer"

                                    @click="openBalloon(draft)">

                                    <!-- select -->
                                    <td style="width:30px;">
                                        <input
                                            v-model="selectedIds"
                                            :id="draft.id"
                                            :value="draft"
                                            type="checkbox"
                                            style="width: 25px"
                                            class="checkbox-big"

                                            @click.stop="">
                                    </td>

                                    <!-- draft recipient -->
                                    <td>
                                        <div
                                            class="draft-recipient"
                                            :class="!draft.received ? 'text-muted font-italic' : ''">

                                            {{ draft.received ? draft.received : $t('message.draft.d_no_recipient') }}
                                        </div>
                                    </td>

                                    <!-- draft subject -->
                                    <td>
                                        <div :class="!draft.subject ? 'text-muted font-italic' : ''">
                                            {{ draft.subject ? draft.subject : $t('message.draft.d_no_subject') }}
                                        </div>
                                    </td>

                                    <!-- draft date -->
                                    <td class="text-right" style="width:80px;">
                                        <div>
                                            {{ draft.human_readable_date }}
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Balloon wrapper -->
        <div class="bln-container">

            <!-- Send Draft Balloon -->
            <Balloon ref="draftBalloon" :title="$t('message.draft.sd_title')">

                <!-- custom header buttons -->
                <template v-slot:header-buttons>
                    <i class="fa fa-times" @click="closeBalloon()"></i>
                </template>

                <!-- form -->
                <form class="row" action="" style="font-size: .875rem !important;">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withCcDraft"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="withCcDraft = !withCcDraft">
                                        {{ $t('message.inbox.with_cc') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withBccDraft"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="withBccDraft = !withBccDraft">
                                        {{ $t('message.inbox.cm_bcc') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="col-md-12 py-0 my-0">
                        <div class="form-group">

                            <small
                                v-if="draftContent.email.length"
                                class="text-primary small"
                                style="cursor: pointer"

                                @click="draftContent.email = []">
                                {{ $t('message.draft.sd_remove_all_emails') }}
                            </small>

                            <vue-tags-input
                                v-model="tag"
                                :placeholder="$t('message.inbox.cm_rec')"
                                :max-tags="10"
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="draftContent.email"

                                @tags-changed="newTags => draftContent.email = newTags"
                            />

                            <span
                                v-if="messageForms.errors.email"
                                v-for="errEmail in messageForms.errors.email"
                                class="text-danger">
                                {{ errEmail }}
                            </span>

                            <span v-if="checkEmailValidationError(messageForms.errors)" class="text-danger">
                                {{ $t('message.draft.sd_err_valid_emails') }}
                            </span>

                        </div>
                    </div>

                    <div class="col-md-12" v-show="withCcDraft">
                        <div class="form-group">
                            <small
                                v-if="draftContent.cc.length"
                                class="text-primary small"
                                style="cursor: pointer"

                                @click="draftContent.cc = []">
                                {{ $t('message.inbox.cm_remove_all_emails') }}
                            </small>

                            <vue-tags-input
                                v-model="draftCc"
                                placeholder="Cc"
                                :max-tags="10"
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="draftContent.cc"

                                @tags-changed="newTagsCc => draftContent.cc = newTagsCc"
                            />

                            <span
                                v-if="messageForms.errors.cc"
                                v-for="err in messageForms.errors.cc"
                                class="text-danger">
                                {{ err }}
                            </span>

                            <span v-if="checkCcBccValidationError(messageForms.errors, 'cc.')" class="text-danger">
                                {{ $t('message.inbox.cc_valid') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12" v-show="withBccDraft">
                        <div class="form-group">
                            <small
                                v-if="draftContent.bcc.length"
                                class="text-primary small"
                                style="cursor: pointer"

                                @click="draftContent.bcc = []">
                                {{ $t('message.inbox.cm_remove_all_emails') }}
                            </small>

                            <vue-tags-input
                                v-model="draftBcc"
                                placeholder="Bcc"
                                :max-tags="10"
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="draftContent.bcc"

                                @tags-changed="newTagsBcc => draftContent.bcc = newTagsBcc"
                            />

                            <span
                                v-if="messageForms.errors.bcc"
                                v-for="err in messageForms.errors.bcc"
                                class="text-danger">
                                {{ err }}
                            </span>

                            <span v-if="checkCcBccValidationError(messageForms.errors, 'bcc.')" class="text-danger">
                                {{ $t('message.inbox.bcc_valid') }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.title}"
                            class="form-group">
                            <input
                                v-model="draftContent.title"
                                type="text"
                                required="required"
                                :placeholder="$t('message.inbox.cm_subject')"
                                class="form-control form-control-sm">

                            <span
                                v-if="messageForms.errors.title"
                                v-for="errTitle in messageForms.errors.title"
                                class="text-danger">
                                {{ errTitle }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.content}"
                            class="form-group">

                            <tiny-editor
                                v-model="draftContent.content"
                                ref="draftEditor"
                                editor-id="draftEditor">

                            </tiny-editor>

                            <span
                                v-if="messageForms.errors.content"
                                v-for="errContent in messageForms.errors.content"
                                class="text-danger">

                                {{ errContent }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 col-xl-12 col-12">

                                <!-- send button -->
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"

                                    @click="sendDraft()">

                                    <i class="fa fa-paper-plane"></i>
                                    {{ $t('message.draft.send') }}
                                </button>

                                <!-- attachment button -->
                                <button
                                    type="button"
                                    :title="$t('message.inbox.cm_attachments')"
                                    class="btn btn-primary btn-sm float-right"

                                    @click="$refs.file_draft.click()">

                                    <i class="fa fa-paperclip"></i>
                                    {{
                                        attached_files.draft === 0
                                            ? $t('message.inbox.cm_attachments')
                                            : attached_files.draft + $t('message.inbox.cm_files')
                                    }}
                                </button>

                            </div>

                            <div class="col-md-6 col-xl-6 col-12">
                                <div class="form-group">
                                    <input
                                        multiple
                                        type="file"
                                        id="file_draft"
                                        ref="file_draft"
                                        class="form-control form-control-sm d-none"

                                        @change="getAttachments()">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </Balloon>

        </div>
    </div>
</template>

<script>
import axios from "axios";
import {mapActions, mapState} from "vuex";
import Balloon from '@/components/windows/Balloon';
import TinyEditor from "@/components/editor/TinyEditor";
import {createTags} from "@johmun/vue-tags-input";

export default {
    components : {TinyEditor, Balloon},

    data() {
        return {
            drafts: {
                data: []
            },

            draftContent: {
                id: '',
                cc: [],
                bcc: [],
                email: [],
                title: '',
                content: ''
            },

            listPageOptions: [5, 10, 25, 50, 100],

            filterModel: {
                search: '',
                page: this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 15,
            },

            isAllSelected: false,
            selectedIds: [],

            // for tag input component
            tag : '',
            separators : [
                ';',
                ',',
                '|',
                ' '
            ],

            // for attachments
            attached_files: {
                draft: 0
            },

            draftCc: '',
            draftBcc: '',
            withCcDraft: false,
            withBccDraft: false,
        }
    },

    created() {
        this.getDrafts()
    },

    mounted() {
        this.getUserDrafts();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            userDrafts : state => state.storeAuth.userDrafts,
            messageForms : state => state.storeMailgun.messageForms,
        })
    },

    methods: {
        ...mapActions({
            getUserDrafts : "getUserDrafts",
        }),

        // query methods
        getDrafts (page = 1) {
            let loader = this.$loading.show();

            this.filterModel.page = page;

            axios.post('/api/mail/get-drafts', this.filterModel)
            .then((response) => {
                this.drafts = response.data;

                this.getUserDrafts();

                loader.hide();
            })
            .catch((error) => {
                console.log(error)

                loader.hide();
            });
        },

        async sendDraft () {
            let self = this;
            let cc = '';
            let bcc = '';
            let appendEmail = '';
            let appendTitle = '';
            let appendContent = '';
            let attachments = [];
            let forwardAttachments = [];

            cc = JSON.stringify(this.draftContent.cc);
            bcc = JSON.stringify(this.draftContent.bcc);
            appendTitle = this.draftContent.title;
            appendContent = this.draftContent.content;
            appendEmail = JSON.stringify(this.draftContent.email);
            attachments = this.$refs.file_draft.files;

            // request body
            this.formData = new FormData();
            this.formData.append('cc', cc);
            this.formData.append('bcc', bcc);
            this.formData.append('email', appendEmail);
            this.formData.append('title', appendTitle);
            this.formData.append('work_mail', this.user.work_mail_orig);
            this.formData.append('content', appendContent);
            this.formData.append('forwardAttachment', 'undefined');
            this.formData.append('from', 'drafts');

            // attachments
            if (!attachments.length) {
                this.formData.append('attachment', 'undefined');
            } else {
                for (let i = 0; i < attachments.length; i++) {
                    this.formData.append('attachment[]', attachments[i]);
                }
            }

            // send email
            await this.$store.dispatch('actionSendMailgun', this.formData);

            if (this.messageForms.action === 'success') {

                await swal.fire(
                    self.$t('message.draft.sent'),
                    self.$t('message.draft.sd_success_sent'),
                    'success'
                )

                // delete removed images on editor
                this.$refs.draftEditor.deleteImages('Removed');

                // delete draft
                this.deleteDraft(this.draftContent.id);

                //clear model
                this.clearModel();

                // clear attachments and templates
                this.$refs.file_draft.value = ""

                // clear attachment count
                this.attached_files.draft = 0;

                // close balloon
                this.closeBalloon();

                // clear message forms
                this.clearMessageform()
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.$t('message.draft.sd_err_send'),
                    'error'
                )
            }
        },

        deleteDraft (id) {
            if (id) {
                axios.post('/api/mail/delete-draft', {
                    id: id
                }).then((res) => {
                    this.getDrafts();
                }).catch((err) => {
                    console.log(err)
                })
            }
        },

        deleteSelectedDrafts () {
            let self = this;
            let idArray = this.selectedIds.map(a => a.id);

            swal.fire({
                title : self.$t('message.draft.dd_title'),
                html : self.$t('message.draft.dd_confirm'),
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.draft.deletes'),
                cancelButtonText : self.$t('message.draft.keep')
            })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.post('/api/mail/delete-selected-drafts', {
                        ids: idArray
                    }).then((res) => {
                        swal.fire(
                            self.$t('message.draft.deleted'),
                            self.$t('message.draft.dd_deleted'),
                            'success'
                        )

                        this.getDrafts()
                    }).catch((err) => {
                        swal.fire(
                            self.$t('message.draft.error'),
                            err.response.data.message,
                            'error'
                        )
                    })
                }
            });
        },

        // action methods
        openBalloon (draft) {

            this.clearModel();
            this.clearMessageform();

            this.draftContent.id  = draft.id;
            this.draftContent.email = [];
            this.draftContent.title = draft.subject ? draft.subject : '';
            this.draftContent.cc = [];
            this.draftContent.bcc = [];

            // add recipient
            if (draft.received) {
                this.draftContent.email = createTags(draft.received.replace(/\s/g, '')
                    .split(/[|,]/g)
                    .filter(function (email) {
                        return email !== '';
                    }))
            }

            // cc and bcc
            if (draft.cc) {
                this.withCcDraft = true;

                this.draftContent.cc = createTags(draft.cc.replace(/\s/g, '')
                    .split(/[|,]/g)
                    .filter(function (email) {
                        return email !== '';
                    }))
            }

            if (draft.bcc) {
                this.withBccDraft = true;

                this.draftContent.bcc = createTags(draft.bcc.replace(/\s/g, '')
                    .split(/[|,]/g)
                    .filter(function (email) {
                        return email !== '';
                    }))
            }

            this.$refs.draftBalloon.open();

            if (draft.body) {
                this.draftContent.content = draft.body;
            }
        },

        closeBalloon () {
            this.$refs.draftBalloon.close();
            this.withCcDraft = false;
            this.withBccDraft = false;
        },

        selectAllIds () {
            this.selectedIds = [];

            if (!this.isAllSelected) {
                for (let index in this.drafts.data) {
                    this.selectedIds.push(this.drafts.data[index]);
                }
            }
        },

        clearSearch () {
            this.filterModel = {
                search: '',
                page: 0,
                paginate: 10,
            }

            this.getDrafts()
        },

        checkEmailValidationError (error) {
            return Object.keys(error).some(function (err) {
                return ~err.indexOf("email.")
            })
        },

        getAttachments () {
            let attachments = [];
            attachments = this.$refs.file_draft.files
            this.attached_files.draft = attachments.length
        },

        clearMessageform () {
            this.$store.dispatch('clearMessageform');
        },

        clearModel () {
            this.draftContent = {
                id: '',
                cc : [],
                bcc: [],
                email : [],
                title : '',
                content : ''
            }

            this.attached_files.draft = 0
        },

        checkCcBccValidationError(error, mod) {
            return Object.keys(error).some(function (err) {
                return ~err.indexOf(mod)
            })
        },
    },
}
</script>

<style scoped>
    .draft-recipient {
        width: 250px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .bln-container {
        bottom: 0;
        right: 0;
        width: 100%;
        z-index: 1040;
        position: fixed;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        pointer-events: none;
    }

    .checkbox-big {
        transform: scale(1.4);
    }
</style>
