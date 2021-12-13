<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">

                <div class="card-header">

                    <h3 class="card-title text-primary">Auto Replies</h3>

                    <div class="card-tools">
                        <div class="input-group">
                            <input
                                v-model="filterModel.search"
                                type="text"
                                placeholder="Search Auto Replies"
                                class="form-control input-sm">

                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" title="Search" @click="getAutoReplies()">
                                    <i class="fa fa-search"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Clear"
                                    class="btn btn-default"

                                    @click="clearSearch()">
                                    <i class="fa fa-times-circle"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Add Auto Reply"
                                    class="btn btn-success"

                                    @click="modalOpener('Add')">

                                    <i class="fa fa-plus"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="card-body">

                    <!-- table buttons/headers/pagination -->
                    <div class="mailbox-controls">

                        <div class="btn-group" role="group" aria-label="Auto Reply buttons">
                            <button type="button" class="btn btn-default">
                                <input type="checkbox" @click="selectAllIds" v-model="isAllSelected">
                            </button>

                            <button type="button" title="Refresh" class="btn btn-default" @click="getAutoReplies()">
                                <i class="fas fa-sync"></i>
                            </button>

                            <button
                                type="button"
                                title="Trash"
                                class="btn btn-default"
                                :disabled="selectedIds.length === 0"

                                @click="deleteSelectedAutoReplies()">

                                <i class="fa fa-fw fa-trash"></i>
                            </button>

                            <button
                                type="button"
                                title="Toggle show active auto reply"
                                class="btn btn-default"

                                @click="toggleActiveAutoReply()">

                                <i :class="filterModel.show ? 'fas fa-toggle-on text-primary' : 'fas fa-toggle-off'"></i>
                            </button>
                        </div>

                        <div v-if="listAutoReply" class="float-right" style="text-align:center;">
                            <div v-if="listAutoReply.total" class="d-inline-block">
                                {{ listAutoReply.from + '-' + listAutoReply.to + " / " + listAutoReply.total }}
                            </div>

                            <div class="d-inline-block ml-2">
                                <pagination
                                    size="small"
                                    :limit="5"
                                    :data="listAutoReply"
                                    @pagination-change-page="getAutoReplies">

                                </pagination>
                            </div>
                        </div>

                    </div>

                    <!-- table -->
                    <div class="table-responsive">

                        <table class="table table-condensed table-hover" style="table-layout: fixed; width: 100%">
                            <tbody>
                                <tr v-if="listAutoReply.total === 0">
                                    <td class="text-muted text-center">No auto reply record</td>
                                </tr>

                                <tr v-else v-for="(auto, index) in listAutoReply.data" :key="index">
                                    <!-- select/switch -->
                                    <td style="width:10%">
                                        <input
                                            v-model="selectedIds"
                                            :id="auto.id"
                                            :value="auto"
                                            type="checkbox"
                                            style="width: 25px"
                                            class="checkbox-big">

                                        <div class="custom-control custom-switch d-inline-block">
                                            <input
                                                :checked="auto.active"
                                                :id="'switch' + auto.id"
                                                type="checkbox"
                                                class="custom-control-input"

                                                @change="toggleAutoReply($event, auto)">

                                            <label class="custom-control-label" :for="'switch' + auto.id"></label>
                                        </div>
                                    </td>

                                    <!-- auto reply subject -->
                                    <td style="width: 25%">
                                        <div class="auto-reply-subject">
                                            {{ auto.subject }}
                                        </div>
                                    </td>

                                    <!-- auto reply body -->
                                    <td style="width: 50%">
                                        <div class="auto-reply-body">
                                            {{ auto.body }}
                                        </div>
                                    </td>

                                    <!-- action buttons -->
                                    <td style="width: 15%; text-align: right">
                                        <div class="btn-group">
                                            <button
                                                title="Edit auto reply"
                                                class="btn btn-default mr-2"

                                                @click="updateAutoReply(auto); modalOpener('Update')">

                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Auto Reply Start -->
        <div class="modal fade" id="modal-auto-reply" ref="modalAutoReply" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ modalMode === 'Add' ? 'Add' : 'Update' }} Auto Reply</h4>
                    </div>

                    <div class="modal-body">
                        <form class="row" @submit.prevent="">
                            <div class="col-md-12">
                                <div :class="{'has-error': messageFormsAutoReply.errors.subject}" class="form-group">
                                    <label style="color: #333">Auto Reply Subject</label>

                                    <input
                                        v-model="modelSubject"
                                        required
                                        type="text"
                                        class="form-control">

                                    <span
                                        v-if="messageFormsAutoReply.errors.subject"
                                        v-for="errSubject in messageFormsAutoReply.errors.subject"
                                        class="text-danger">

                                        {{ errSubject }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'has-error': messageFormsAutoReply.errors.body}" class="form-group">
                                    <label style="color: #333">Auto Reply Body</label>

                                    <tiny-editor
                                        v-model="modelBody"
                                        ref="autoEditor"
                                        editor-id="autoEditor">

                                    </tiny-editor>

                                    <span
                                        v-if="messageFormsAutoReply.errors.body"
                                        v-for="errBody in messageFormsAutoReply.errors.body"
                                        class="text-danger">

                                {{ errBody }}
                            </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser(modalMode)">Close</button>
                        <button v-if="modalMode === 'Add'" type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                        <button v-else type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Auto Reply End -->

    </div>
</template>

<script>
import axios from "axios";
import {mapActions, mapState} from "vuex";
import TinyEditor from "@/components/editor/TinyEditor";
import {createTags} from "@johmun/vue-tags-input";

export default {
    components : {TinyEditor},

    data() {
        return {
            autoReplies: {
                data: []
            },

            autoReplyContent: {
                body: '',
                subject: '',
            },

            updateAutoReplyContent: {
                id: '',
                body: '',
                subject: '',
            },

            updateAutoReplyContentTemp: {
                id: '',
                body: '',
                subject: '',
            },

            listPageOptions: [5, 10, 25, 50, 100],

            filterModel: {
                show: false,
                search: '',
                page: this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 15,
            },

            isAllSelected: false,
            selectedIds: [],

            // for modal
            modalMode: '',
        }
    },

    created() {
        this.getAutoReplies()
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listAutoReply: state => state.storeMailgun.listAutoReply,
            messageFormsAutoReply : state => state.storeMailgun.messageFormsAutoReply,
        }),

        modelSubject: {
            get () {
                return this.modalMode === 'Add' ? this.autoReplyContent.subject : this.updateAutoReplyContent.subject
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.autoReplyContent.subject = val
                } else {
                    this.updateAutoReplyContent.subject = val
                }
            }
        },

        modelBody: {
            get () {
                return this.modalMode === 'Add' ? this.autoReplyContent.body : this.updateAutoReplyContent.body
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.autoReplyContent.body = val
                } else {
                    this.updateAutoReplyContent.body = val
                }
            }
        },
    },

    methods: {
        ...mapActions({
            getAutoReplyState : "getAutoReplyState",
        }),

        // query methods
        async getAutoReplies (page = 1) {
            let loader = this.$loading.show();

            this.filterModel.page = page;

            await this.$store.dispatch('actionGetAutoReply', this.filterModel);

            await loader.hide();
        },

        async submitAdd () {
            let loader = this.$loading.show();
            await this.$store.dispatch('actionAddAutoReply', this.autoReplyContent);

            if (this.messageFormsAutoReply.action === 'saved_auto_reply') {

                // delete removed images before saving
                this.$refs.autoEditor.deleteImages('Update');

                await this.getAutoReplies()

                await this.clearModel('Add');
                await this.closeModal();

                loader.hide();

                await swal.fire(
                    'Added!',
                    'Auto reply successfully added.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    'Error!',
                    this.messageFormsAutoReply.message,
                    'error'
                )
            }
        },

        async submitUpdate () {
            let loader = this.$loading.show();
            await this.$store.dispatch('actionUpdateAutoReply', this.updateAutoReplyContent);

            if (this.messageFormsAutoReply.action === 'updated_auto_reply') {

                // delete removed images before saving
                this.$refs.autoEditor.deleteImages('Update');

                await this.getAutoReplies()

                await this.clearModel('Update');
                await this.closeModal();

                loader.hide();

                await swal.fire(
                    'Updated!',
                    'Auto reply successfully updated.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    'Error!',
                    this.messageFormsAutoReply.message,
                    'error'
                )
            }
        },

        deleteSelectedAutoReplies () {
            let idArray = this.selectedIds.map(a => a.id);

            swal.fire({
                title : "Delete auto reply",
                html : "Are you sure that you want to delete the selected auto reply/replies?",
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : 'Yes, delete it!',
                cancelButtonText : 'No, keep it'
            })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.post('/api/mail/delete-auto-reply', {
                        ids: idArray
                    }).then((res) => {

                        this.isAllSelected = false;
                        this.selectedIds = [];

                        swal.fire(
                            'Deleted!',
                            'Selected auto reply/replies were successfully deleted.',
                            'success'
                        )

                        this.getAutoReplies();
                    }).catch((err) => {
                        console.log(err)

                        swal.fire(
                            'Error!',
                            'Something went wrong while deleting the auto reply/replies.',
                            'error'
                        )
                    })
                }
            });
        },

        toggleAutoReply (e, data) {
            let checked = e.target.checked;

            axios.post('/api/mail/toggle-auto-reply', {
                id: data.id,
                checked: checked
            }).then((res) => {

                let status = checked ? 'on.' : 'off.';

                swal.fire(
                    'Success!',
                    'Successfully turned auto reply to ' + status,
                    'success'
                )

                this.getAutoReplies()
                this.getAutoReplyState();
            }).catch((err) => {
                console.log(err)

                swal.fire(
                    'Error!',
                    'Something went wrong while toggling auto reply.',
                    'error'
                )

                this.getAutoReplyState();
            })
        },

        toggleActiveAutoReply () {
            this.filterModel.show = !this.filterModel.show;

            this.getAutoReplies();
        },

        // action methods
        updateAutoReply (data) {
            this.clearMessageForm()

            this.updateAutoReplyContent.id = data.id
            this.updateAutoReplyContent.body = data.body
            this.updateAutoReplyContent.subject = data.subject

            // add temp data

            this.updateAutoReplyContentTemp.id = data.id
            this.updateAutoReplyContentTemp.body = data.body
            this.updateAutoReplyContentTemp.subject = data.subject
        },

        modalCloser (mode) {
            if (mode === 'Add') {

                if (this.autoReplyContent.subject || this.autoReplyContent.body) {
                    swal.fire({
                        title : "Are you sure?",
                        text : "Changes will not be saved",
                        icon : "question",
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        cancelButtonColor: 'red',
                        confirmButtonColor: 'green',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {

                            // remove all images inserted on editor
                            this.$refs.autoEditor.deleteImages('All');

                            this.closeModal()
                            this.clearModel(mode)

                        }
                    });
                } else {
                    this.closeModal()
                    this.clearModel(mode)
                }

            } else {
                if ((this.updateAutoReplyContent.subject !== this.updateAutoReplyContentTemp.subject)
                    || (this.updateAutoReplyContent.body !== this.updateAutoReplyContentTemp.body)
                ) {

                    swal.fire({
                        title : "Are you sure?",
                        text : "Changes will not be saved",
                        icon : "question",
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        cancelButtonColor: 'red',
                        confirmButtonColor: 'green',
                    })
                        .then((result) => {
                            if (result.isConfirmed) {

                                // delete removed images before saving
                                this.$refs.autoEditor.deleteImages('Update', this.updateAutoReplyContentTemp.body);

                                this.closeModal()
                                this.clearModel(mode)

                            }
                        });
                } else {
                    this.closeModal()
                    this.clearModel(mode)
                }
            }
        },

        closeModal () {
            let element = this.$refs.modalAutoReply
            $(element).modal('hide')
        },

        modalOpener (mode) {
            this.clearMessageForm()

            this.modalMode = mode

            let element = this.$refs.modalAutoReply
            $(element).modal('show')

            // when opening modal, always clear the images on the editor
            this.$refs.autoEditor.clearAddImages();
        },

        selectAllIds () {
            this.selectedIds = [];

            if (!this.isAllSelected) {
                for (let index in this.listAutoReply.data) {
                    this.selectedIds.push(this.listAutoReply.data[index]);
                }
            }
        },

        clearSearch () {
            this.filterModel = {
                show: false,
                search: '',
                page: 0,
                paginate: 10,
            }

            this.getAutoReplies()
        },

        clearMessageForm () {
            this.$store.dispatch('clearMessageFormAutoReply');
        },

        clearModel (mode) {
            if (mode === 'Add') {
                this.autoReplyContent = {
                    body: '',
                    subject: '',
                }
            } else {
                this.updateAutoReplyContent = {
                    id: '',
                    body: '',
                    subject: '',
                }
            }
        },
    },
}
</script>

<style scoped>

.auto-reply-subject {
    overflow: hidden;
    font-weight: bold;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.auto-reply-body {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.checkbox-big {
    transform: scale(1.4);
}
</style>
