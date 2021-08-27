<template>
    <div class="row">
        <div class="col-sm-12">

            <!-- FILTERS -->
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
                                <label>Username</label>

                                <input
                                    v-model="filterModel.username"
                                    type="text"
                                    placeholder="Search Username"
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

            <!-- TABLE -->
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
                            <th>Username</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(tool, index) in listTools.data" :key="index">
                                <td class="center-content">{{ tool.id }}</td>
                                <td>{{ tool.name }}</td>
                                <td>{{ tool.username }}</td>
                                <td>
                                    <span>
                                        <i :ref="'eyes' + index" class="fa fa-eye" @click="togglePassword(index)"></i>
                                    </span>

                                    <span :ref="'badge' + index" class="badge badge-secondary">hidden</span>

                                    <span :ref="'pass' + index" style="display: none">{{ tool.password }}</span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <!-- edit tool -->
                                        <button
                                            type="button"
                                            title="Edit Tool"
                                            class="btn btn-default"

                                            @click="updateTool(tool); modalOpener('update')">

                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <!-- delete tool -->
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

        <!-- Modal Add/Update Tool-->
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
                            <div class="col-md-12">
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
        <!-- Modal Add/Update Tool End -->

    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    props: [],

    data() {
        return {

            // misc
            modalMode: '',
            isPopupLoading: false,
            isSearchLoading: false,
            listPageOptions: [5, 10, 25, 50, 100, 'All'],

            // filter model
            filterModel: {
                page: this.$route.query.page || 0,
                name: this.$route.query.name || '',
                paginate: this.$route.query.paginate || 10,
                username: this.$route.query.username || ''
            },

            // tool model
            toolModel: {
                name: '',
                username: '',
                password: '',
            },

            updateToolModel: {
                id: '',
                name: '',
                username: '',
                password: '',
            }
        }
    },

    mounted() {
        this.getToolList()
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listTools: state => state.storeTools.listTools,
            messageFormsTools: state => state.storeTools.messageFormsTools,
        }),

        modelName: {
            get () {
                return this.modalMode === 'add' ? this.toolModel.name : this.updateToolModel.name
            },
            set (val) {
                if (this.modalMode === 'add') {
                    this.toolModel.name = val
                } else {
                    this.updateToolModel.name = val
                }
            }
        },

        modelUsername: {
            get () {
                return this.modalMode === 'add' ? this.toolModel.username : this.updateToolModel.username
            },
            set (val) {
                if (this.modalMode === 'add') {
                    this.toolModel.username = val
                } else {
                    this.updateToolModel.username = val
                }
            }
        },

        modelPassword: {
            get () {
                return this.modalMode === 'add' ? this.toolModel.password : this.updateToolModel.password
            },
            set (val) {
                if (this.modalMode === 'add') {
                    this.toolModel.password = val
                } else {
                    this.updateToolModel.password = val
                }
            }
        },
    },

    methods: {

        // queries
        async getToolList(page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            await self.$store.dispatch('actionGetListTools', { params: self.filterModel });

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
                title: "Delete Tool",
                text: "Do you want to delete this tool?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
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
                query: this.filterModel,
            });

            this.getToolList(1);
        },

        clearFilter() {
            this.clearModel('filter');
            this.$router.replace({'query': null});
            this.getToolList();
        },

        updateTool(data) {
            this.updateToolModel.id = data.id;
            this.updateToolModel.name = data.name;
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
                    name: '',
                    username: '',
                    password: '',
                }
            } else if (mod === 'update') {
                this.updateToolModel = {
                    id: '',
                    name: '',
                    username: '',
                    password: '',
                }
            } else {
                this.filterModel = {
                    page: 1,
                    name: '',
                    username: '',
                    paginate: 10
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
