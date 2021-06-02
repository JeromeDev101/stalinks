<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header d-flex">
                    <h3 class="box-title align-self-center">Mail Signature</h3>

                    <button
                        title="Add email signature"
                        class="btn btn-success ml-auto"

                        @click="modalOpener('Add')">

                        <i class="fa fa-plus"></i>
                    </button>

                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select
                            v-model="filterModel.paginate"
                            style="height: 37px;"
                            class="form-control pull-right"

                            @change="getSignatureList">

                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>
                </div>

                <div class="box-body no-padding relative">

                    <span v-if="listEmailSignature.total > 0" class="pagination-custom-footer-text m-0">
                        <b>Showing {{ listEmailSignature.from }} to {{ listEmailSignature.to }} of {{ listEmailSignature.total }} entries.</b>
                    </span>

                    <table class="table table-hover table-bordered table-striped">
                        <tbody>
                            <tr class="label-primary">
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Login Mail</th>
                                <th>Actions</th>
                            </tr>

                            <tr>
                                <td style="max-width: 30px;"></td>

                                <td>
                                    <div class="form-group">
                                        <input
                                            v-model="filterModel.name"
                                            type="text"
                                            placeholder="Search Name"
                                            class="form-control pull-right">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <select v-model="filterModel.work_mail" class="form-control pull-right">
                                            <option value="">Search Login Mail</option>
                                            <option v-for="option in listUsers" :value="option.work_mail">
                                                {{ option.work_mail }}
                                            </option>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button
                                            title="Search"
                                            class="btn btn-default"

                                            @click="getSignatureList">

                                            <i class="fa fa-fw fa-search"></i>
                                        </button>

                                        <button
                                            title="Clear"
                                            class="btn btn-default ml-2"

                                            @click="clearFilter">

                                            <i class="fa fa-fw fa-refresh"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-for="(item, index) in listEmailSignature.data" :key="index">
                                <td class="center-content">{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.work_mail !== null ? item.work_mail : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            title="Edit email signature"
                                            class="btn btn-default mr-2"

                                            @click="updateSignature(item); modalOpener('Update')">

                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>

                                        <button
                                            data-toggle="modal"
                                            title="Delete"
                                            class="btn btn-default"

                                            @click="deleteSignature(item.id)">

                                            <i class="fa fa-fw fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination
                        :limit="8"
                        :data="listEmailSignature"
                        @pagination-change-page="getSignatureList">

                    </pagination>

                    <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Signature -->
        <div class="modal fade" id="modal-add-signature" ref="modalSignature" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ modalMode === 'Add' ? 'Add' : 'Update' }} Email Signature</h4>

                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span
                                v-if="messageForms.message !== '' && !isPopupLoading"
                                :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">

                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>

                    <div class="modal-body relative">

                        <div class="alert alert-info">
                            <i class="fa fa-exclamation-circle"></i>
                            Setting table border to <strong>none</strong> will still show grey borders for guidelines.
                        </div>

                        <form class="row" action="">
                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.name}" class="form-group">
                                    <label style="color: #333">Signature Name</label>

                                    <input
                                        v-model="modelName"
                                        required
                                        type="text"
                                        class="form-control">

                                    <span
                                        v-if="messageForms.errors.name"
                                        v-for="err in messageForms.errors.name"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.work_mail}" class="form-group">
                                    <label style="color: #333">Login Mail</label>

                                    <select class="form-control" v-model="modelWorkMail">
                                        <option v-for="option in listUsers" v-bind:value="option.work_mail">
                                            {{ option.work_mail }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.work_mail"
                                        v-for="err in messageForms.errors.work_mail"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label style="color: #333">Signature Content</label>

                                <br>
                                <span
                                    v-if="messageForms.errors.content"
                                    v-for="err in messageForms.errors.content"
                                    class="text-danger">

                                    {{ err }}
                                </span>

<!--                                <tinymce-->
<!--                                    v-model="modelContent"-->
<!--                                    id="articleContent"-->
<!--                                    :other_options="options">-->

<!--                                </tinymce>-->

                                <ckeditor v-model="modelContent" @imageRemove="handleImageRemove"></ckeditor>
                            </div>
                        </form>

                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button v-if="modalMode === 'Add'" type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                        <button v-else type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: none" ref="editorPreview" v-html="htmlContent"></div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import 'tinymce/skins/lightgray/skin.min.css';
import Ckeditor from "../../../components/editor/Ckeditor";

export default {
    components: {
        Ckeditor
    },
    data() {
        return {
            listUsers: [],

            listPageOptions: [5, 10, 25, 50, 100],

            filterModel: {
                page: this.$route.query.page || 0,
                name: this.$route.query.title || '',
                paginate: this.$route.query.paginate || 10,
                work_mail: this.$route.query.work_mail || '',
            },

            signatureModel : {
                name: '',
                content: '',
                work_mail: '',
                html_content : ''
            },

            updateSignatureModel: {
                id: '',
                name: '',
                content: '',
                work_mail: '',
                html_content : ''
            },

            modalMode: '',
            isPopupLoading: false,
            isLoadingTable: false,

            htmlContent: '',

            options: {
                height: 450,
                branding: false,
                image_title: true,
                automatic_uploads: true,
                allow_script_urls: false,
                file_picker_types: 'image',
                images_upload_handler: function (blobInfo, success, failure) {
                    let xhr, formData;
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    let auth = localStorage.hasOwnProperty('vuex')
                        ? 'Bearer ' + JSON.parse(localStorage.getItem("vuex")).storeAuth.token.access_token
                        : '';

                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '/api/mail/post-signature-image');

                    // manually set header
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('Authorization', auth);

                    xhr.onload = function() {
                        let json;

                        if (xhr.status !== 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }
            },
        }
    },

    created() {
        this.getListUsers()
        this.getSignatureList()
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listEmailSignature: state => state.storeMailgun.listEmailSignature,
            messageForms: state => state.storeMailgun.messageForms,
        }),

        modelName: {
            get () {
                return this.modalMode === 'Add' ? this.signatureModel.name : this.updateSignatureModel.name
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.signatureModel.name = val
                } else {
                    this.updateSignatureModel.name = val
                }
            }
        },

        modelWorkMail: {
            get () {
                return this.modalMode === 'Add' ? this.signatureModel.work_mail : this.updateSignatureModel.work_mail
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.signatureModel.work_mail = val
                } else {
                    this.updateSignatureModel.work_mail = val
                }
            }
        },

        modelContent: {
            get () {
                return this.modalMode === 'Add' ? this.signatureModel.content : this.updateSignatureModel.content
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.signatureModel.content = val
                } else {
                    this.updateSignatureModel.content = val
                }

                this.htmlContent = val
            }
        }
    },

    watch: {
        htmlContent: function () {
            this.finalizeHtml()
        }
    },

    methods: {
        getListUsers(){
            axios.get('/api/mail/get-user-list')
            .then(response => {
                this.listUsers = response.data
            })
        },

        async getSignatureList(page = 1) {
            this.isLoadingTable = true;
            this.filterModel.page = page
            await this.$store.dispatch('actionGetEmailSignatureList', {
                params: this.filterModel
            });
            this.isLoadingTable = false;
        },

        async submitAdd() {
            let self = this;
            this.isPopupLoading = true;

            this.signatureModel.html_content = this.$refs.editorPreview.innerHTML

            await this.$store.dispatch('actionAddEmailSignature', self.signatureModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_signature') {
                this.clearModel();

                await this.getSignatureList()
            }
        },

        updateSignature(data) {
            this.updateSignatureModel.id = data.id;
            this.updateSignatureModel.name = data.name;
            this.updateSignatureModel.content = data.content;
            this.updateSignatureModel.work_mail = data.work_mail;
        },

        async deleteSignature(id) {
            swal.fire({
                title: "Delete Email Signature",
                text: "Do you want to delete this email signature?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/mail/delete-signature/' + id)
                    .then(response => {
                        this.getSignatureList()

                        swal.fire(
                            'Deleted!',
                            'Email signature deleted.',
                            'success'
                        )
                    })
                }
            });
        },

        async submitUpdate() {
            this.isPopupLoading = true;

            this.updateSignatureModel.html_content = this.$refs.editorPreview.innerHTML

            await this.$store.dispatch('actionUpdateEmailSignature', this.updateSignatureModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_signature') {
                await this.getSignatureList()
            }
        },

        handleImageRemove(images) {
            console.log(images)
        },

        finalizeHtml() {
            this.$nextTick(() => {
                let tables = this.$refs.editorPreview.getElementsByClassName('table')

                let tableElements = this.$refs.editorPreview.getElementsByTagName('table')

                for(let i=0; i < tableElements.length; i++)
                {
                    if (tables[i].style['width'] != null && tables[i].style['width'] !== '') {
                        tableElements[i].style['width'] = tables[i].style['width'];
                    }

                    if(tableElements[i].style['width'] !== '' && tableElements[i].style['width'] != null) {
                        tables[i].style['width'] = ''
                    }
                }

                let images = this.$refs.editorPreview.getElementsByClassName('image')

                let imageElements = this.$refs.editorPreview.getElementsByTagName('img')

                for(let i=0; i < imageElements.length; i++)
                {
                    if (images[i].style['width'] != null && images[i].style['width'] !== '') {
                        imageElements[i].style['width'] = images[i].style['width'];
                    }

                    if (imageElements[i].style['width'] !== '' && imageElements[i].style['width'] != null) {
                        images[i].style['width'] = ''
                    }
                }

                console.log(this.$refs.editorPreview.innerHTML)
            });
        },

        modalOpener(mode){
            this.clearMessageForm()

            this.modalMode = mode

            if (this.modalMode === 'Add') {
                this.checkWorkMail()
            }

            this.finalizeHtml()

            let element = this.$refs.modalSignature
            $(element).modal('show')
        },

        checkWorkMail() {
            this.signatureModel.work_mail = this.user.work_mail
                ? (this.listUsers.some(el => el.work_mail === this.user.work_mail))
                    ? this.user.work_mail
                    : ''
                : '';
        },

        clearFilter() {
            this.filterModel.page = 0;
            this.filterModel.name = '';
            this.filterModel.paginate = 10;
            this.filterModel.work_mail = '';

            this.getSignatureList()
        },

        clearModel() {
            this.signatureModel = {
                name: '',
                content: '',
                work_mail: '',
                html_content: ''
            };
        },

        clearMessageForm() {
            this.$store.dispatch('clearMessageform');
        },
    },
}
</script>
