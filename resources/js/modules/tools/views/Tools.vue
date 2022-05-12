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
                                    <label>Tool</label>

                                    <input
                                        v-model="filterModel.name"
                                        type="text"
                                        placeholder="Search Tool Name"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL</label>

                                    <input
                                        v-model="filterModel.url"
                                        type="text"
                                        placeholder="Search URL"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Username</label>

                                    <input
                                        v-model="filterModel.username"
                                        type="text"
                                        placeholder="Search Username"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Details</label>

                                    <input
                                        v-model="filterModel.details"
                                        type="text"
                                        placeholder="Search Details"
                                        class="form-control pull-right">
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-4">
                                <button class="btn btn-default" @click="clearFilter">Clear</button>

                                <button class="btn btn-default" @click="filterToolList">Search
                                    <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i>
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
                        <h3 class="card-title">Tools</h3>
                        <div class="card-tools">
                            <button
                                class="btn btn-success btn-tool"
                                data-toggle="modal"
                                data-target="#modal-add-tool"

                                @click="modalOpener('add')">

                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="pagination-custom-footer-text m-0 pl-2">
                            <b>Showing {{ listTools.from }} to {{ listTools.to }} of {{ listTools.total }} entries.</b>
                        </span>

                        <div class="input-group input-group-sm float-right" style="width: 65px">
                            <select
                                v-model="filterModel.paginate"
                                style="height: 37px;"
                                class="form-control pull-right"

                                @change="getToolList">

                                <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                            </select>
                        </div>

                        <table id="tbl-tools" class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#ID</th>
                                <th>Tool</th>
                                <th>URL</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(tool, index) in listTools.data" :key="index">
                                <td class="center-content">{{ tool.id }}</td>
                                <td>{{ tool.name }}</td>
                                <td>
                                    <a v-if="tool.url" :href="'//' + tool.url" target="_blank">
                                        {{ tool.url }}
                                    </a>
                                    <span v-else>N/A</span>
                                </td>
                                <td>{{ tool.username }}</td>
                                <td>
                                    <span>
                                        <i :ref="'eyes' + index" class="fa fa-eye" @click="togglePassword(index)"></i>
                                    </span>

                                    <span :ref="'badge' + index" class="badge badge-secondary">hidden</span>

                                    <span :ref="'pass' + index" style="display: none">{{ tool.password }}</span>
                                </td>
                                <td>
                                    <div style="white-space: pre-line;">{{ tool.details }}</div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <button
                                            type="button"
                                            title="Edit Tool"
                                            class="btn btn-default"

                                            @click="updateTool(tool); modalOpener('update')">

                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button
                                            type="button"
                                            title="Delete Tool"
                                            class="btn btn-default"

                                            @click="deleteTool(tool.id)">

                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="listTools" @pagination-change-page="getToolList" :limit="8"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-add-tool" ref="modalTool" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">{{ modalMode === 'add' ? 'Add' : 'Update' }} Tool</h4>
                    </div>

                    <div class="modal-body relative">

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.name}">
                                    <label>Tool</label>
                                    <input
                                        v-model="modelName"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Tool Name">

                                    <span
                                        v-if="messageFormsTools.errors.name"
                                        v-for="err in messageFormsTools.errors.name"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.url}">
                                    <label>URL</label>
                                    <input
                                        v-model="modelUrl"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Url">

                                    <span
                                        v-if="messageFormsTools.errors.url"
                                        v-for="err in messageFormsTools.errors.url"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.username}">
                                    <label>Username</label>
                                    <input
                                        v-model="modelUsername"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Username">

                                    <span
                                        v-if="messageFormsTools.errors.username"
                                        v-for="err in messageFormsTools.errors.username"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>Password</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend" @click="showPassword()">
                                        <span class="input-group-text bg-info">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <input type="password" id="psswd" v-model="modelPassword" class="form-control" aria-label="Amount (to the nearest dollar)">
                                    <div class="input-group-append" @click="copyPassword()" id="copyBtn">
                                        <span class="input-group-text bg-success">
                                            <i class="fa fa-copy"></i>
                                        </span>
                                    </div>
                                </div>

                                <span
                                        v-if="messageFormsTools.errors.password"
                                        v-for="err in messageFormsTools.errors.password"
                                        class="text-danger">

                                            {{ err }}
                                        </span>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.details}">
                                    <label>Details</label>
                                    <textarea
                                        v-model="modelDetails"
                                        rows="5"
                                        class="form-control">

                                        </textarea>

                                    <span
                                        v-if="messageFormsTools.errors.details"
                                        v-for="err in messageFormsTools.errors.details"
                                        class="text-danger">

                                            {{ err }}
                                        </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                        <button
                            v-if="modalMode === 'add'"
                            type="button"
                            class="btn btn-primary"

                            @click="submitAddTool">

                            Save
                        </button>

                        <button
                            v-else
                            type="button"
                            class="btn btn-primary"

                            @click="submitUpdateTool">

                            Update
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--    <div class="row">
            <div class="col-sm-12">

                &lt;!&ndash; FILTERS &ndash;&gt;
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter</h3>
                    </div>

                    <div class="box-body my-2 mx-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tool</label>

                                    <input
                                        v-model="filterModel.name"
                                        type="text"
                                        placeholder="Search Tool Name"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL</label>

                                    <input
                                        v-model="filterModel.url"
                                        type="text"
                                        placeholder="Search URL"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Username</label>

                                    <input
                                        v-model="filterModel.username"
                                        type="text"
                                        placeholder="Search Username"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Details</label>

                                    <input
                                        v-model="filterModel.details"
                                        type="text"
                                        placeholder="Search Details"
                                        class="form-control pull-right">
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-4">
                                <button class="btn btn-default" @click="clearFilter">Clear</button>

                                <button class="btn btn-default" @click="filterToolList">Search
                                    <i class="fa fa-refresh fa-spin" v-if="isSearchLoading"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                &lt;!&ndash; TABLE &ndash;&gt;
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tools</h3>

                        <button
                            class="btn btn-success float-right"
                            data-toggle="modal"
                            data-target="#modal-add-tool"

                            @click="modalOpener('add')">

                            <i class="fa fa-plus"></i>
                        </button>

                        <div class="input-group input-group-sm float-right" style="width: 65px">
                            <select
                                v-model="filterModel.paginate"
                                style="height: 37px;"
                                class="form-control pull-right"

                                @change="getToolList">

                                <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="box-body no-padding relative">
                        <span class="pagination-custom-footer-text m-0 pl-2">
                            <b>Showing {{ listTools.from }} to {{ listTools.to }} of {{ listTools.total }} entries.</b>
                        </span>

                        <table id="tbl-tools" class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#ID</th>
                                <th>Tool</th>
                                <th>URL</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(tool, index) in listTools.data" :key="index">
                                    <td class="center-content">{{ tool.id }}</td>
                                    <td>{{ tool.name }}</td>
                                    <td>
                                        <a v-if="tool.url" :href="'//' + tool.url" target="_blank">
                                            {{ tool.url }}
                                        </a>
                                        <span v-else>N/A</span>
                                    </td>
                                    <td>{{ tool.username }}</td>
                                    <td>
                                        <span>
                                            <i :ref="'eyes' + index" class="fa fa-eye" @click="togglePassword(index)"></i>
                                        </span>

                                        <span :ref="'badge' + index" class="badge badge-secondary">hidden</span>

                                        <span :ref="'pass' + index" style="display: none">{{ tool.password }}</span>
                                    </td>
                                    <td>
                                        <div style="white-space: pre-line;">{{ tool.details }}</div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            &lt;!&ndash; edit tool &ndash;&gt;
                                            <button
                                                type="button"
                                                title="Edit Tool"
                                                class="btn btn-default"

                                                @click="updateTool(tool); modalOpener('update')">

                                                <i class="fa fa-edit"></i>
                                            </button>

                                            &lt;!&ndash; delete tool &ndash;&gt;
                                            <button
                                                type="button"
                                                title="Delete Tool"
                                                class="btn btn-default"

                                                @click="deleteTool(tool.id)">

                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :data="listTools" @pagination-change-page="getToolList" :limit="8"></pagination>
                    </div>
                </div>

            </div>

            &lt;!&ndash; Modal Add/Update Tool&ndash;&gt;
            <div class="modal fade" id="modal-add-tool" ref="modalTool" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">{{ modalMode === 'add' ? 'Add' : 'Update' }} Tool</h4>
                            <div class="modal-load overlay float-right">
                                <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                            </div>
                        </div>

                        <div class="modal-body relative">

                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.name}">
                                        <label>Tool</label>
                                        <input
                                            v-model="modelName"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Tool Name">

                                        <span
                                            v-if="messageFormsTools.errors.name"
                                            v-for="err in messageFormsTools.errors.name"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.url}">
                                        <label>URL</label>
                                        <input
                                            v-model="modelUrl"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Url">

                                        <span
                                            v-if="messageFormsTools.errors.url"
                                            v-for="err in messageFormsTools.errors.url"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.username}">
                                        <label>Username</label>
                                        <input
                                            v-model="modelUsername"
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Username">

                                        <span
                                            v-if="messageFormsTools.errors.username"
                                            v-for="err in messageFormsTools.errors.username"
                                            class="text-danger">

                                        {{ err }}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.password}">
                                        <label>Password</label>
                                        <input
                                            v-model="modelPassword"
                                            type="password"
                                            class="form-control"
                                            placeholder="Enter Password">

                                        <span
                                            v-if="messageFormsTools.errors.password"
                                            v-for="err in messageFormsTools.errors.password"
                                            class="text-danger">

                                            {{ err }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageFormsTools.errors.details}">
                                        <label>Details</label>
                                        <textarea
                                            v-model="modelDetails"
                                            rows="5"
                                            class="form-control">

                                        </textarea>

                                        <span
                                            v-if="messageFormsTools.errors.details"
                                            v-for="err in messageFormsTools.errors.details"
                                            class="text-danger">

                                            {{ err }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                            <button
                                v-if="modalMode === 'add'"
                                type="button"
                                class="btn btn-primary"

                                @click="submitAddTool">

                                Save
                            </button>

                            <button
                                v-else
                                type="button"
                                class="btn btn-primary"

                                @click="submitUpdateTool">

                                Update
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            &lt;!&ndash; Modal Add/Update Tool End &ndash;&gt;

        </div>-->
</template>

<script>
import {mapState} from 'vuex';

export default {
    props : [],

    data() {
        return {

            // misc
            modalMode : '',
            isPopupLoading : false,
            isSearchLoading : false,
            listPageOptions : [
                5,
                10,
                25,
                50,
                100,
                'All'
            ],

            // filter model
            filterModel : {
                url : this.$route.query.url || '',
                page : this.$route.query.page || 0,
                name : this.$route.query.name || '',
                details : this.$route.query.details || '',
                paginate : this.$route.query.paginate || 10,
                username : this.$route.query.username || '',
            },

            // tool model
            toolModel : {
                url : '',
                name : '',
                details : '',
                username : '',
                password : '',
            },

            updateToolModel : {
                id : '',
                url : '',
                name : '',
                details : '',
                username : '',
                password : '',
            }
        }
    },

    mounted() {
        this.getToolList()
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listTools : state => state.storeTools.listTools,
            messageFormsTools : state => state.storeTools.messageFormsTools,
        }),

        modelName : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.name : this.updateToolModel.name
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.name = val
                } else {
                    this.updateToolModel.name = val
                }
            }
        },

        modelUrl : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.url : this.updateToolModel.url
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.url = val
                } else {
                    this.updateToolModel.url = val
                }
            }
        },

        modelUsername : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.username : this.updateToolModel.username
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.username = val
                } else {
                    this.updateToolModel.username = val
                }
            }
        },

        modelPassword : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.password : this.updateToolModel.password
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.password = val
                } else {
                    this.updateToolModel.password = val
                }
            }
        },

        modelDetails : {
            get() {
                return this.modalMode === 'add' ? this.toolModel.details : this.updateToolModel.details
            },
            set(val) {
                if (this.modalMode === 'add') {
                    this.toolModel.details = val
                } else {
                    this.updateToolModel.details = val
                }
            }
        },
    },

    methods : {

        showPassword() {
            let passwordField = document.querySelector('#psswd')
            if (passwordField.getAttribute('type') === 'password') passwordField.setAttribute('type', 'text')
            else passwordField.setAttribute('type', 'password')
        },

        copyPassword() {

            var copyTextarea = document.querySelector('#psswd');
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                console.log('Copying text command was ' + msg);
            } catch (err) {
                console.log('Oops, unable to copy');
            }
           

            // swal.fire('Done', 'Copied Successfully', 'success');
        },

        // queries
        async getToolList(page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            await self.$store.dispatch('actionGetListTools', {params : self.filterModel});

            loader.hide();
        },

        async submitAddTool() {
            let self = this;
            let loader = self.$loading.show();

            self.isPopupLoading = true;
            await self.$store.dispatch('actionAddTool', self.toolModel);
            self.isPopupLoading = false;

            if (self.messageFormsTools.action === 'added') {
                self.clearModel('add');

                await self.getToolList(this.filterModel.page);

                self.closeModal()
                loader.hide();

                await swal.fire(
                    'Success!',
                    'Tool has been added.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    'Error!',
                    'There were some errors while adding the tool.',
                    'error'
                )
            }
        },

        async submitUpdateTool() {
            let self = this;
            let loader = self.$loading.show();

            self.isPopupLoading = true;
            await self.$store.dispatch('actionUpdateTool', self.updateToolModel);
            self.isPopupLoading = false;

            if (this.messageFormsTools.action === 'updated') {
                self.clearModel('update');

                await self.getToolList(this.filterModel.page);

                self.closeModal()
                loader.hide();

                await swal.fire(
                    'Success!',
                    'Tool has been updated.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    'Error!',
                    'There were some errors while updating the tool.',
                    'error'
                )
            }
        },

        deleteTool(id) {
            swal.fire({
                title : "Delete Tool",
                text : "Do you want to delete this tool?",
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : 'Yes, delete it!',
                cancelButtonText : 'No, keep it'
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('/api/tools/' + id)
                            .then(response => {

                                this.getToolList();

                                swal.fire(
                                    'Deleted!',
                                    'Tool deleted.',
                                    'success'
                                )
                            })
                    }
                });
        },

        // misc
        filterToolList() {
            this.$router.push({
                query : this.filterModel,
            });

            this.getToolList(1);
        },

        clearFilter() {
            this.clearModel('filter');
            this.$router.replace({'query' : null});
            this.getToolList();
        },

        updateTool(data) {
            this.updateToolModel.id = data.id;
            this.updateToolModel.url = data.url;
            this.updateToolModel.name = data.name;
            this.updateToolModel.details = data.details;
            this.updateToolModel.username = data.username;
            this.updateToolModel.password = data.password;
        },

        modalOpener(mode) {
            let self = this;

            self.clearMessageForm();
            self.modalMode = mode;

            let element = this.$refs.modalTool
            $(element).modal('show')
        },

        closeModal() {
            let element = this.$refs.modalTool
            $(element).modal('hide')
        },

        clearModel(mod) {
            if (mod === 'add') {
                this.toolModel = {
                    url : '',
                    name : '',
                    details : '',
                    username : '',
                    password : '',
                }
            } else if (mod === 'update') {
                this.updateToolModel = {
                    id : '',
                    url : '',
                    name : '',
                    details : '',
                    username : '',
                    password : '',
                }
            } else {
                this.filterModel = {
                    url : '',
                    page : 1,
                    name : '',
                    details : '',
                    username : '',
                    paginate : 10
                }
            }
        },

        togglePassword(index) {
            let eyes = this.$refs['eyes' + index][0];
            let pass = this.$refs['pass' + index][0];
            let badge = this.$refs['badge' + index][0];

            if (pass.style.display === 'none') {
                eyes.classList.value = 'fa fa-eye-slash'
                badge.style.display = 'none';
                pass.style.display = 'inline';
            } else {
                eyes.classList.value = 'fa fa-eye'
                badge.style.display = 'inline';
                pass.style.display = 'none';
            }
        },

        clearMessageForm() {
            this.$store.dispatch('clearMessageFormTools');
        },
    },
}
</script>

<style scoped>

</style>
