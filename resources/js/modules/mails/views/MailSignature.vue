<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header d-flex">
                    <h3 class="box-title align-self-center">Mail Signature</h3>

                    <button
                        class="btn btn-success ml-auto"
                        data-toggle="modal"
                        data-backdrop="static"
                        data-target="#modal-add-signature"

                        @click="">

                        <i class="fa fa-plus"></i>
                    </button>

                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select
                            v-model="filterModel.paginate"
                            style="height: 37px;"
                            class="form-control pull-right"

                            @change="">

                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>
                </div>

                <div class="box-body no-padding relative">
                    <table class="table table-hover table-bordered table-striped">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Name</th>
                                <th>User</th>
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
                                        <select v-model="filterModel.user" class="form-control pull-right">
                                            <option value="">Search User</option>
                                            <option v-for="option in listUsers" :value="option.id">
                                                {{ option.username }}
                                            </option>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button
                                            title="Search"
                                            class="btn btn-default"

                                            @click="">

                                            <i class="fa fa-fw fa-search"></i>
                                        </button>

                                        <button
                                            title="Clear"
                                            class="btn btn-default ml-2"

                                            @click="">

                                            <i class="fa fa-fw fa-refresh"></i>
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
                </div>
            </div>
        </div>

        <!-- Modal Add Signature -->
        <div class="modal fade" id="modal-add-signature" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Email Signature</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                        </div>
                    </div>

                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.name}" class="form-group">
                                    <label style="color: #333">Signature Name</label>
                                    <input
                                        v-model="signatureModel.name"
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
                                <label style="color: #333">Signature Content</label>
                                <tinymce
                                    v-model="signatureModel.content"
                                    id="articleContent"
                                    :other_options="options">

                                </tinymce>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="" class="btn btn-primary">Save</button>
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
                name: this.$route.query.title || '',
                user: this.$route.query.user || '',
                page: this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 10,
            },

            signatureModel : {
                name: '',
                content: ''
            },

            isPopupLoading: false,

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
    },
}
</script>
