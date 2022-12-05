<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.module_page.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button
                                type="button"
                                class="btn btn-primary ml-5"
                                data-toggle="collapse"
                                data-target="#collapseExample"
                                aria-expanded="false"
                                aria-controls="collapseExample">

                                <i class="fa fa-plus"></i>
                                {{ $t('message.module_page.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Module Group</label>
                                    <v-select
                                        v-model="filterModel.group"
                                        :options="filterValues.group"
                                        class="style-chooser"
                                        placeholder="Select page group"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Module Page</label>

                                    <v-select
                                        v-model="filterModel.page_name"
                                        :options="filterValues.page"
                                        class="style-chooser"
                                        placeholder="Select page"/>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearFilter()">
                                    {{ $t('message.module_page.clear') }}
                                </button>
                                <button class="btn btn-default" @click="filterModules()">
                                    {{ $t('message.module_page.search') }}
                                    <i v-if="false" class="fa fa-refresh fa-spin"></i>
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
                        <h3 class="card-title text-primary">{{ $t('message.module_page.m_title') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="modules.total" class="pagination-custom-footer-text m-0 mt-2">
                                    <b v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: modules.from, to: modules.to, end: modules.total }) }}
                                    </b>

                                    <b v-else>
                                        {{ $t('message.others.table_all_entries', { total: modules.total }) }}
                                    </b>
                                </span>
                            </div>
                            <div class="col-6">
                                <button
                                    v-if="user.permission_list.includes('create-management-module')"
                                    title="Add Module"
                                    class="btn btn-success float-right"

                                    @click="modalOpener('Add')">

                                    <i class="fas fa-plus"></i>
                                </button>

                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control"
                                        style="height: 37px;"

                                        @change="getModules()">

                                        <option v-for="value in pageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-if="modules.data.length">
                            <vue-virtual-table
                                width="100%"
                                :min-width="450"
                                :height="600"
                                :bordered="true"
                                :item-height="60"
                                :hover-highlight="true"
                                :config="tableConfig"
                                :enable-export="true"
                                :data="modules.data">

                                <template slot-scope="scope" slot="action-buttons">
                                    <button
                                        v-if="user.permission_list.includes('update-management-module')"
                                        title="Edit"
                                        class="btn btn-primary m-1"

                                        @click="fillModuleData(scope.row); modalOpener('Update')">

                                        <i class="fas fa-pen-square"></i>
                                    </button>

                                    <button
                                        v-if="user.permission_list.includes('delete-management-module')"
                                        title="Delete"
                                        class="btn btn-danger"

                                        @click="deleteModule(scope.row.id)">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                </template>

                            </vue-virtual-table>

                            <pagination
                                :limit="8"
                                :data="modules"

                                @pagination-change-page="getModules">

                            </pagination>
                        </div>

                        <div v-else class="col-md-12 text-center card">
                            <div class="card-body">
                                <i class="fas fa-exclamation-circle"></i>
                                No results found
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Module -->
        <div class="modal fade" id="modal-module" ref="modalModule" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            Module
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Module Group</label>

                                    <v-select
                                        v-model="modelGroup"
                                        :options="selectionGroup"
                                        class="style-chooser"
                                        placeholder="Select page group"/>

                                    <span
                                        v-if="errorMessages.errors.group"
                                        v-for="err in errorMessages.errors.group"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Module Page</label>

                                    <v-select
                                        v-model="modelPage"
                                        :options="selectionPage"
                                        class="style-chooser"
                                        placeholder="Select page"/>

                                    <div v-if="modalMode === 'Add' && (addModulePageInfo && !addModuleModel.page)">
                                        <small v-if="addModulePageInfo" class="font-italic text-primary">
                                            <i class="fa fa-exclamation-circle"></i>
                                            {{ addModulePageInfo }}
                                        </small>
                                    </div>

                                    <div v-else>
                                        <small v-if="updateModulePageInfo" class="font-italic text-primary">
                                            <i class="fa fa-exclamation-circle"></i>
                                            {{ updateModulePageInfo }}
                                        </small>
                                    </div>

                                    <span
                                        v-if="errorMessages.errors.page"
                                        v-for="err in errorMessages.errors.page"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Module Description</label>

                                    <textarea
                                        v-model="modelDescription"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter description">

                                    </textarea>

                                    <span
                                        v-if="errorMessages.errors.description"
                                        v-for="err in errorMessages.errors.description"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Module Permissions</label>

                                    <div class="mt-3 card">
                                        <div class="card-header">
                                            <div class="custom-control custom-switch">
                                                <input
                                                    v-model="isAllPermissionsSelected"
                                                    type="checkbox"
                                                    id="allPermissionsToggle"
                                                    class="custom-control-input"

                                                    @change="toggleSelectPermissions()">

                                                <label class="custom-control-label" for="allPermissionsToggle">
                                                    {{ isAllPermissionsSelected ? 'Deselect' : 'Select' }} All Permissions
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div
                                                v-for="(permission, index) in generatePermissionsNamesPerModule(modelGroup, modelPage, 'obj')"
                                                class="form-check form-check-inline"
                                                :key="index">

                                                <input
                                                    v-model="modelPermissions"
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    :value="permission.value"
                                                    :disabled="!modelPage || !modelGroup">

                                                <label class="form-check-label">{{ permission.text }}</label>
                                            </div>

                                            <div v-if="!modelGroup || !modelPage" class="mt-2">
                                                <small class="text-secondary font-italic">
                                                    Please select module group and page.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalMode === 'Add'" type="button" @click="addModule" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updateModule" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import VueVirtualTable from 'vue-virtual-table';
import {Constants} from "../../../mixins/constants";
import {stringManipulation} from "../../../mixins/stringManipulation";

export default {
    mixins: [Constants, stringManipulation],
    components : {
        VueVirtualTable,
    },
    data() {
        return {
            modules: {
                data: [],
            },

            filterValues: {
                group: [],
                page: [],
            },

            addModuleModel: {
                group: '',
                page: '',
                description: '',
                permissions: [],
            },

            updateModuleModel: {
                id: '',
                group: '',
                page: '',
                description: '',
                permissions: [],
            },

            addModulePageInfo: '',
            updateModulePageInfo: '',

            errorMessages: {
                message: '',
                errors: [],
            },

            filterModel: {
                page : this.$route.query.page || 0,
                group: this.$route.query.group || '',
                paginate: this.$route.query.paginate || 10,
                page_name: this.$route.query.page_name || '',
            },

            modalMode: 'Add',
            pageOptions: [10, 25, 50, 100, 'All'],

            isAllPermissionsSelected: false,
        }
    },

    created() {
        this.getModules(this.$route.query.page);
        this.getModuleFilterValues();
    },

    watch: {
        'addModuleModel.group': function () {
            if (this.addModuleModel.group !== null
                && this.addModuleModel.group !== ''
                && this.addModuleModel.page !== null
                && this.addModuleModel.page !== '') {

                let isNotInGroup = !Constants.PAGE_GROUPS[this.addModuleModel.group].includes(this.addModuleModel.page);

                this.addModulePageInfo = isNotInGroup
                    ? 'The selected page does not belong in the selected group. Page is removed.'
                    : '';

                this.addModuleModel.page = isNotInGroup ? '' : this.addModuleModel.page;
            }
        },

        'updateModuleModel.group': function () {
            if (this.updateModuleModel.group !== null
                && this.updateModuleModel.group !== ''
                && this.updateModuleModel.page !== null
                && this.updateModuleModel.page !== '') {

                let isNotInGroup = !Constants.PAGE_GROUPS[this.updateModuleModel.group].includes(this.updateModuleModel.page);

                this.updateModulePageInfo = isNotInGroup
                    ? 'The selected page does not belong in the selected group. Page is removed.'
                    : '';

                this.updateModuleModel.page = isNotInGroup ? '' : this.updateModuleModel.page;
            }
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

        tableConfig() {
            return [
                {
                    prop : 'id',
                    name : 'Module ID',
                    sortable: true,
                },
                {
                    prop : 'group',
                    name : 'Module Group',
                },
                {
                    prop : 'page',
                    name : 'Module Page',
                },
                {
                    prop : '_action',
                    name : this.$t('message.publisher.t_action'),
                    actionName : 'action-buttons',
                    width : '150',
                },
            ];
        },

        selectionGroup () {
            return Object.keys(Constants.PAGE_GROUPS);
        },

        selectionPage () {
            return this.modelGroup ? Constants.PAGE_GROUPS[this.modelGroup] : [];
        },

        modelGroup: {
            get () {
                return this.modalMode === 'Add' ? this.addModuleModel.group : this.updateModuleModel.group
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addModuleModel.group = val
                } else {
                    this.updateModuleModel.group = val
                }
            }
        },

        modelPage: {
            get () {
                return this.modalMode === 'Add' ? this.addModuleModel.page : this.updateModuleModel.page
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addModuleModel.page = val
                } else {
                    this.updateModuleModel.page = val
                }
            }
        },

        modelDescription: {
            get () {
                return this.modalMode === 'Add' ? this.addModuleModel.description : this.updateModuleModel.description
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addModuleModel.description = val
                } else {
                    this.updateModuleModel.description = val
                }
            }
        },

        modelPermissions: {
            get () {
                return this.modalMode === 'Add' ? this.addModuleModel.permissions : this.updateModuleModel.permissions
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addModuleModel.permissions = val
                } else {
                    this.updateModuleModel.permissions = val
                }
            }
        },
    },

    methods : {
        getModules (page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            this.$router.push({
                query : {
                    page: self.filterModel.page
                }
            });

            axios.get('/api/module', {
                params: self.filterModel
            })
            .then((res) => {
                self.modules = res.data

                loader.hide();
            })
            .catch((err) => {
                console.log(err)
                loader.hide();
            })
        },

        getModuleFilterValues () {
            axios.get('/api/module/get-filter-values')
            .then((res) => {
                this.filterValues.group = Object.values(res.data.group).length ? Object.values(res.data.group) : [];
                this.filterValues.page = Object.values(res.data.page).length ? Object.values(res.data.page) : [];
            })
            .catch((err) => {
                console.log(err)
            })
        },

        filterModules () {
            this.$router.push({
                query : this.filterModel,
            });

            this.getModules(1);
            this.getModuleFilterValues();
        },

        clearFilter () {
            this.clearModels('filter');
            this.$router.replace({'query' : null});
            this.getModules(1);
        },

        addModule () {
            let self = this;
            let loader = this.$loading.show();

            axios.post('/api/module', self.addModuleModel)
            .then((response) => {
                swal.fire(
                    self.$t('message.article.alert_success'),
                    'Module successfully added.',
                    'success'
                )

                self.modalCloser();
                self.clearModels('add');
                self.getModules(self.filterModel.page);
                self.getModuleFilterValues();
                loader.hide();
            }).catch((err) => {
                self.errorMessages = err.response.data

                swal.fire(
                    self.$t('message.article.alert_err'),
                    err.response.data.message,
                    'error'
                )

                loader.hide();
            });
        },

        updateModule () {
            let self = this;
            let loader = self.$loading.show();

            axios.put('/api/module', self.updateModuleModel)
            .then((response) => {
                swal.fire(
                    self.$t('message.article.alert_success'),
                    'Module successfully updated.',
                    'success'
                )

                self.modalCloser();
                self.clearModels('update');
                self.getModules(self.filterModel.page);
                self.getModuleFilterValues();
                loader.hide();
            }).catch((err) => {
                self.errorMessages = err.response.data

                swal.fire(
                    self.$t('message.article.alert_err'),
                    err.response.data.message,
                    'error'
                )

                loader.hide();
            });
        },

        deleteModule (id) {
            let self = this;

            swal.fire({
                title : 'Delete Module',
                text : 'Are you sure that you want to delete this module?',
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/module/' + id)
                        .then(response => {

                            let page = self.modules.data.length <= 1 ? 1 : self.filterModel.page;

                            self.getModules(page);
                            self.getModuleFilterValues();

                            swal.fire(
                                self.$t('message.tools.alert_deleted'),
                                'Module successfully deleted.',
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

        clearModels (mod) {
            switch(mod) {
                case 'add':
                    this.addModuleModel = {
                        group: '',
                        page: '',
                        description: '',
                        permissions: []
                    }

                    break;
                case 'update':
                    this.updateModuleModel = {
                        id: '',
                        group: '',
                        page: '',
                        description: '',
                        permissions: []
                    }

                    break;
                case 'filter':
                    this.filterModel = {
                        page: 1,
                        group: '',
                        page_name: '',
                        paginate: this.filterModel.paginate
                    }

                    break;
                default:
                    this.errorMessages = {
                        message: '',
                        errors: [],
                    }
            }
        },

        clearPermissions (mode) {
            if (mode === 'update') {
                this.updateModuleModel.permissions = [];
            } else {
                this.addModuleModel.permissions = [];
            }
        },

        fillModuleData (data) {
            this.updateModuleModel.id = data.id;
            this.updateModuleModel.group = data.group;
            this.updateModuleModel.page = data.page;
            this.updateModuleModel.description = data.description;
            this.updateModuleModel.permissions = data.permissions.map(({module_id, id, ...keepAttrs}) => keepAttrs);
        },

        modalOpener (mode) {
            this.modalMode = mode

            let element = this.$refs.modalModule
            $(element).modal('show')

            this.isAllPermissionsSelected = false;
        },

        modalCloser () {
            let element = this.$refs.modalModule
            $(element).modal('hide')

            this.clearModels('error');
            this.clearPermissions('add');
            this.clearPermissions('update');
        },

        generatePermissionsNamesPerModule (module, page, mode) {
            let moduleTemp = this.convertStringToKebabCase(module);
            let pageTemp = this.convertStringToKebabCase(page);
            let permission = moduleTemp + '-' + pageTemp;

            return mode === 'obj' ? [
                {
                    value: {
                        name: 'view-' + permission,
                        display_name: this.toTitleCase('View ' + module + ' ' + page)
                    },
                    text: 'View'
                },
                {
                    value: {
                        name: 'create-' + permission,
                        display_name: this.toTitleCase('Create ' + module + ' ' + page)
                    },
                    text: 'Create'
                },
                {
                    value: {
                        name: 'update-' + permission,
                        display_name: this.toTitleCase('Update ' + module + ' ' + page)
                    },
                    text: 'Update'
                },
                {
                    value: {
                        name: 'delete-' + permission,
                        display_name: this.toTitleCase('Delete ' + module + ' ' + page)
                    },
                    text: 'Delete'
                },
                {
                    value: {
                        name: 'upload-' + permission,
                        display_name: this.toTitleCase('Upload ' + module + ' ' + page)
                    },
                    text: 'Upload'
                },
                {
                    value: {
                        name: 'export-' + permission,
                        display_name: this.toTitleCase('Export ' + module + ' ' + page)
                    },
                    text: 'Export'
                },
            ] : [

                {
                    name: 'view-' + permission,
                    display_name: this.toTitleCase('View ' + module + ' ' + page)
                },
                {
                    name: 'create-' + permission,
                    display_name: this.toTitleCase('Create ' + module + ' ' + page)
                },
                {
                    name: 'update-' + permission,
                    display_name: this.toTitleCase('Update ' + module + ' ' + page)
                },
                {
                    name: 'delete-' + permission,
                    display_name: this.toTitleCase('Delete ' + module + ' ' + page)
                },
                {
                    name: 'upload-' + permission,
                    display_name: this.toTitleCase('Upload ' + module + ' ' + page)
                },
                {
                    name: 'export-' + permission,
                    display_name: this.toTitleCase('Export ' + module + ' ' + page)
                },
            ]
        },

        toggleSelectPermissions () {
            this.modelPermissions = this.isAllPermissionsSelected
                ? this.generatePermissionsNamesPerModule(this.modelGroup, this.modelPage, 'toggles')
                : [];
        },
    }
}
</script>
