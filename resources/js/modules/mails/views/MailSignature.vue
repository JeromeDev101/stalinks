<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header d-flex">
                    <h3 class="box-title align-self-center">{{ $t('message.signature.ms_title') }}</h3>

                    <button
                        v-if="user.permission_list.includes('create-mails-signatures')"
                        class="btn btn-success ml-auto"
                        :title="$t('message.signature.ms_add')"

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
                                <th>#{{ $t('message.signature.ms_id') }}</th>
                                <th>{{ $t('message.signature.ms_name') }}</th>
                                <th>{{ $t('message.signature.ms_login') }}</th>
                                <th>{{ $t('message.signature.ms_action') }}</th>
                            </tr>

                            <tr>
                                <td style="max-width: 30px;"></td>

                                <td>
                                    <div class="form-group">
                                        <input
                                            v-model="filterModel.name"
                                            type="text"
                                            :placeholder="$t('message.signature.ms_search_name')"
                                            class="form-control pull-right">
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <select v-model="filterModel.work_mail" class="form-control pull-right">
                                            <option value="">{{ $t('message.signature.ms_search_login') }}</option>
                                            <option v-for="option in listUsers" :value="option.work_mail">
                                                {{ option.work_mail }}
                                            </option>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button
                                            :title="$t('message.signature.search')"
                                            class="btn btn-default"

                                            @click="getSignatureList">

                                            <i class="fa fa-fw fa-search"></i>
                                        </button>

                                        <button
                                            :title="$t('message.signature.clear')"
                                            class="btn btn-default ml-2"

                                            @click="clearFilter">

                                            <i class="fa fa-fw fa-sync"></i>
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
                                            v-if="user.permission_list.includes('update-mails-signatures')"
                                            title="Edit email signature"
                                            class="btn btn-default mr-2"

                                            @click="updateSignature(item); modalOpener('Update')">

                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>

                                        <button
                                            v-if="user.permission_list.includes('delete-mails-signatures')"
                                            data-toggle="modal"
                                            title="Delete"
                                            class="btn btn-default"

                                            @click="deleteSignature(item.id, item.content)">

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
        <div class="modal fade" id="modal-add-signature" ref="modalSignature" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            {{ $t('message.signature.ams_title') }}
                        </h4>

<!--                        <div class="modal-load overlay float-right">-->
<!--                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>-->

<!--                            <span-->
<!--                                v-if="messageForms.message !== '' && !isPopupLoading"-->
<!--                                :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">-->

<!--                                {{ messageForms.message }}-->
<!--                            </span>-->
<!--                        </div>-->
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-info">
                            <i class="fa fa-exclamation-circle"></i>
                            {{ $t('message.signature.ams_note') }}
                        </div>

                        <form class="row" action="" @submit.prevent="">
                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.name}" class="form-group">
                                    <label style="color: #333">{{ $t('message.signature.ams_name') }}</label>

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
                                    <label style="color: #333">{{ $t('message.signature.ams_login') }}</label>

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
                                <label style="color: #333">{{ $t('message.signature.ams_content') }}</label>

                                <br>
                                <span
                                    v-if="messageForms.errors.content"
                                    v-for="err in messageForms.errors.content"
                                    class="text-danger">

                                    {{ err }}
                                </span>

                                <tinymce
                                    v-model="modelContent"
                                    id="articleContent"
                                    :other_options="options"

                                    @editorChange="testEvent">

                                </tinymce>
                            </div>
                        </form>

<!--                        <div class="overlay" v-if="isPopupLoading"></div>-->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser(modalMode)">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalMode === 'Add'" type="button" @click="submitAdd" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import 'tinymce/skins/lightgray/skin.min.css';

export default {
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
            },

            updateSignatureModel: {
                id: '',
                name: '',
                content: '',
                work_mail: '',
            },

            updateSignatureTemp: {
                name: '',
                content: '',
                work_mail: '',
            },

            addImages: [],
            updateImages: [],

            modalMode: '',
            isPopupLoading: false,
            isLoadingTable: false,

            options: {
                height: 450,
                branding: false,
                image_title: true,
                convert_urls: false,
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

    watch: {
        'signatureModel.content': function(value) {
            this.getAddSignatureImages(value)
        },

        'updateSignatureModel.content': function(value) {
            this.getUpdateSignatureImages(value)
        }
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
            }
        }
    },

    methods: {
        getImages(string) {
            const imgRex = /<img.*?src="(.*?)"[^>]+>/g;
            const images = [];
            let img;
            while ((img = imgRex.exec(string))) {
                images.push(img[1]);
            }

            return images;
        },

        getAddSignatureImages(string) {
            let self = this
            let images = self.getImages(string)

            images.forEach(function (img) {
                if (!self.addImages.includes(img)) {
                    self.addImages.push(img)
                }
            })
        },

        getUpdateSignatureImages(string) {
            let self = this
            let images = self.getImages(string)

            images.forEach(function (img) {
                if (!self.updateImages.includes(img)) {
                    self.updateImages.push(img)
                }
            })
        },

        getRemovedImages(mode, images) {
            if (mode === 'Add') {
                return this.addImages.filter(img => !images.includes(img))
            } else {
                return this.updateImages.filter(img => !images.includes(img))
            }
        },

        async deleteRemovedImages(images) {
            if (images.length !== 0) {
                await axios.post('/api/mail/delete-signature-image', {
                    images: images
                })
            }
        },

        testEvent(event) {
            // console.log(event);
        },

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
            self.isPopupLoading = true;
            await this.$store.dispatch('actionAddEmailSignature', self.signatureModel);
            self.isPopupLoading = false;

            if (self.messageForms.action === 'saved_signature') {
                // remove deleted images on editor
                let removedImages = self.getRemovedImages('Add', self.getImages(self.signatureModel.content))
                await self.deleteRemovedImages(removedImages);

                await self.clearModel();
                await self.getSignatureList()
                this.addImages = [];

                await swal.fire(
                    self.$t('message.signature.alert_added'),
                    self.$t('message.signature.alert_added_note'),
                    'success'
                )
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }
        },

        updateSignature(data) {
            this.updateSignatureModel.id = data.id;
            this.updateSignatureModel.name = data.name;
            this.updateSignatureModel.content = data.content;
            this.updateSignatureModel.work_mail = data.work_mail;

            // add data to temp
            this.updateSignatureTemp.name = data.name
            this.updateSignatureTemp.content = data.content
            this.updateSignatureTemp.work_mail = data.work_mail

            this.updateImages = [];

            this.getUpdateSignatureImages(data.content)
        },

        compareUpdateData() {
            return (
                (this.updateSignatureModel.name !== this.updateSignatureTemp.name)
                || (this.updateSignatureModel.content !== this.updateSignatureTemp.content)
                || (this.updateSignatureModel.work_mail !== this.updateSignatureTemp.work_mail)
            )
        },

        async deleteSignature(id, content) {
            let self = this;

            swal.fire({
                title: self.$t('message.signature.alert_delete'),
                text: self.$t('message.signature.alert_confirm_delete'),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: self.$t('message.signature.deletes'),
                cancelButtonText: self.$t('message.signature.keep')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/mail/delete-signature/' + id)
                    .then(response => {

                        this.deleteRemovedImages(this.getImages(content))

                        this.getSignatureList()

                        swal.fire(
                            self.$t('message.signature.alert_deleted'),
                            self.$t('message.signature.alert_deleted_note'),
                            'success'
                        )
                    })
                    .catch(err => {
                        swal.fire(
                            self.$t('message.draft.error'),
                            err.response.data.message,
                            'error'
                        )
                    })
                }
            });
        },

        async submitUpdate() {
            let self = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateEmailSignature', this.updateSignatureModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_signature') {
                let removedImages = this.getRemovedImages('Update', this.getImages(this.updateSignatureModel.content))

                await this.deleteRemovedImages(removedImages);

                this.updateImages = [];

                await this.getSignatureList()
                this.updateSignatureTemp.name = this.updateSignatureModel.name
                this.updateSignatureTemp.content = this.updateSignatureModel.content
                this.updateSignatureTemp.work_mail = this.updateSignatureModel.work_mail

                await swal.fire(
                    self.$t('message.signature.alert_updated'),
                    self.$t('message.signature.alert_updated_note'),
                    'success'
                )
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }
        },

        handleImageRemove(images) {
            console.log(images)
        },

        modalCloser(mode) {
            let self = this;

            if (mode === 'Add') {

                if (this.signatureModel.content || this.signatureModel.name) {
                    swal.fire({
                        title: self.$t('message.signature.alert_confirm'),
                        text: self.$t('message.signature.alert_confirm_note'),
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: self.$t('message.signature.yes'),
                        cancelButtonText: self.$t('message.signature.no')
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            // remove all images inserted in editor
                            this.deleteRemovedImages(this.addImages)
                            this.closeModal()
                            this.clearModel()
                            this.addImages = [];
                        }
                    });
                } else {
                    this.deleteRemovedImages(this.addImages)
                    this.closeModal()
                    this.clearModel()
                    this.addImages = [];
                }

            } else {

                if (this.compareUpdateData()) {
                    swal.fire({
                        title: self.$t('message.signature.alert_confirm'),
                        text: self.$t('message.signature.alert_confirm_note'),
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: self.$t('message.signature.yes'),
                        cancelButtonText: self.$t('message.signature.no')
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.closeModal()

                            // get added images
                            let removedImages = this.getRemovedImages('Update', this.getImages(this.updateSignatureTemp.content))

                            // remove images
                            this.deleteRemovedImages(removedImages)

                            this.updateImages = [];
                        }
                    });
                } else {
                    this.closeModal()
                }

            }
        },

        closeModal() {
            let element = this.$refs.modalSignature
            $(element).modal('hide')
        },

        modalOpener(mode) {
            this.clearMessageForm()

            this.modalMode = mode

            if (this.modalMode === 'Add') {
                this.checkWorkMail()
            }

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
            };
        },

        clearMessageForm() {
            this.$store.dispatch('clearMessageform');
        },
    },
}
</script>
