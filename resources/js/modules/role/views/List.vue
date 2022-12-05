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
                        <h3 class="card-title text-primary">{{ $t('message.role.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.role.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.role.filter_search_role') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.name"
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.role.type')">
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">
                                    {{ $t('message.role.clear') }}
                                </button>
                                <button class="btn btn-default" @click="getRoleList()">
                                    {{ $t('message.role.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.role.r_title') }}</h3>
                        <div class="card-tools">
                            <button
                                v-if="user.permission_list.includes('create-management-role')"
                                class="btn btn-success"
                                data-toggle="modal"
                                data-target="#modal-add-role"

                                @click="clearToggles(); addModel.permissions = [];">

                                {{ $t('message.role.r_add_role') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="input-group input-group-sm float-right mb-2" style="width: 100px">
                            <select name=""
                                    class="form-control float-right"
                                    @change="getRoleList"
                                    v-model="filterModel.paginate"
                                    style="height: 37px;">
                                <option v-for="option in paginate" v-bind:value="option" :key="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b v-if="filterModel.paginate !== 'All'">
                                {{ $t('message.others.table_entries', { from: roleList.from, to: roleList.to, end: roleList.total }) }}
                            </b>
                            <b v-else>
                                {{ $t('message.others.table_all_entries', { total: roleList.total }) }}
                            </b>
                        </span>

                        <vue-virtual-table
                            width="100%"
                            :height="1000"
                            :bordered="true"
                            :item-height="60"
                            :config="tableConfig"
                            :data="roleList.data">

                            <template
                                slot-scope="scope"
                                slot="actionButtons">
                                <div class="btn-group">
                                    <button
                                        v-if="user.permission_list.includes('update-management-role')"
                                        class="btn btn-default"
                                        data-toggle="modal"
                                        data-target="#modal-update-role-list"
                                        :title="$t('message.role.ar_edit_role')"

                                        @click="doUpdate(scope.row)">

                                        <i class="fa fa-fw fa-edit"></i>
                                    </button>

                                    <button
                                        v-if="user.permission_list.includes('update-management-role')"
                                        class="btn btn-default"
                                        :title="$t('message.system_logs.delete')"

                                        @click="doDelete(scope.row.id)">

                                        <i class="fa fa-fw fa-trash"></i>
                                    </button>
                                </div>
                            </template>

                        </vue-virtual-table>

                        <pagination v-if="roleList.data"
                                    :data="roleList"
                                    @pagination-change-page="getRoleList"
                                    :limit="8"></pagination>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-add-role" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.role.r_add_role') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.role.ar_name') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.role.r_desc') }}</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="addModel.description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Role Permissions</label>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Module Group</th>
                                                        <th>Module Page</th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllViewPermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allViewPermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('view-', 'add', isAllViewPermissionsSelected)">

                                                                <label class="custom-control-label" for="allViewPermissionsToggleAdd">
                                                                    View
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllCreatePermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allCreatePermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('create-', 'add', isAllCreatePermissionsSelected)">

                                                                <label class="custom-control-label" for="allCreatePermissionsToggleAdd">
                                                                    Create
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllUpdatePermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allUpdatePermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('update-', 'add', isAllUpdatePermissionsSelected)">

                                                                <label class="custom-control-label" for="allUpdatePermissionsToggleAdd">
                                                                    Update
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllDeletePermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allDeletePermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('delete-', 'add', isAllDeletePermissionsSelected)">

                                                                <label class="custom-control-label" for="allDeletePermissionsToggleAdd">
                                                                    Delete
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllUploadPermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allUploadPermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('upload-', 'add', isAllUploadPermissionsSelected)">

                                                                <label class="custom-control-label" for="allUploadPermissionsToggleAdd">
                                                                    Upload
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="custom-control custom-switch">
                                                                <input
                                                                    v-model="isAllExportPermissionsSelected"
                                                                    type="checkbox"
                                                                    id="allExportPermissionsToggleAdd"
                                                                    class="custom-control-input"

                                                                    @change="toggleSelectAllPermissions('export-', 'add', isAllExportPermissionsSelected)">

                                                                <label class="custom-control-label" for="allExportPermissionsToggleAdd">
                                                                    Export
                                                                </label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <tr v-for="module in modules.data">
                                                        <td>
                                                            {{ module.id }}
                                                        </td>

                                                        <td>
                                                            {{ module.group }}
                                                        </td>

                                                        <td>
                                                            {{ module.page }}
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('view-', module)"
                                                                    :disabled="getPermissionId('view-', module) == null"
                                                                    :id="generatePermissionName('view-', module.group, module.page)">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('create-', module)"
                                                                    :disabled="getPermissionId('create-', module) == null"
                                                                    :id="generatePermissionName('create-', module.group, module.page)">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('update-', module)"
                                                                    :disabled="getPermissionId('update-', module) == null"
                                                                    :id="generatePermissionName('update-', module.group, module.page)">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('delete-', module)"
                                                                    :disabled="getPermissionId('delete-', module) == null"
                                                                    :id="generatePermissionName('delete-', module.group, module.page)">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('upload-', module)"
                                                                    :disabled="getPermissionId('upload-', module) == null"
                                                                    :id="generatePermissionName('upload-', module.group, module.page)">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    v-model="addModel.permissions"
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    :value="getPermissionId('export-', module)"
                                                                    :disabled="getPermissionId('export-', module) == null"
                                                                    :id="generatePermissionName('export-', module.group, module.page)">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="addModel.permissions = [];">
                            {{ $t('message.role.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitAdd">
                            {{ $t('message.role.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Edit Role -->
        <div class="modal fade" id="modal-update-role-list" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.role.ar_edit_role') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.role.ar_name') }}</label>
                                    <input type="text" class="form-control" v-model="updateModel.name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.role.r_desc') }}</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="updateModel.description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Role Permissions</label>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Module Group</th>
                                                            <th>Module Page</th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllViewPermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allViewPermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('view-', 'update', isAllViewPermissionsSelected)">

                                                                    <label class="custom-control-label" for="allViewPermissionsToggle">
                                                                        View
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllCreatePermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allCreatePermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('create-', 'update', isAllCreatePermissionsSelected)">

                                                                    <label class="custom-control-label" for="allCreatePermissionsToggle">
                                                                        Create
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllUpdatePermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allUpdatePermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('update-', 'update', isAllUpdatePermissionsSelected)">

                                                                    <label class="custom-control-label" for="allUpdatePermissionsToggle">
                                                                        Update
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllDeletePermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allDeletePermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('delete-', 'update', isAllDeletePermissionsSelected)">

                                                                    <label class="custom-control-label" for="allDeletePermissionsToggle">
                                                                        Delete
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllUploadPermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allUploadPermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('upload-', 'update', isAllUploadPermissionsSelected)">

                                                                    <label class="custom-control-label" for="allUploadPermissionsToggle">
                                                                        Upload
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <div class="custom-control custom-switch">
                                                                    <input
                                                                        v-model="isAllExportPermissionsSelected"
                                                                        type="checkbox"
                                                                        id="allExportPermissionsToggle"
                                                                        class="custom-control-input"

                                                                        @change="toggleSelectAllPermissions('export-', 'update', isAllExportPermissionsSelected)">

                                                                    <label class="custom-control-label" for="allExportPermissionsToggle">
                                                                        Export
                                                                    </label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr v-for="module in modules.data">
                                                            <td>
                                                                {{ module.id }}
                                                            </td>

                                                            <td>
                                                                {{ module.group }}
                                                            </td>

                                                            <td>
                                                                {{ module.page }}
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('view-', module)"
                                                                        :disabled="getPermissionId('view-', module) == null"
                                                                        :id="generatePermissionName('view-', module.group, module.page)">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('create-', module)"
                                                                        :disabled="getPermissionId('create-', module) == null"
                                                                        :id="generatePermissionName('create-', module.group, module.page)">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check form-check-inline">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('update-', module)"
                                                                        :disabled="getPermissionId('update-', module) == null"
                                                                        :id="generatePermissionName('update-', module.group, module.page)">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check form-check-inline">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('delete-', module)"
                                                                        :disabled="getPermissionId('delete-', module) == null"
                                                                        :id="generatePermissionName('delete-', module.group, module.page)">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check form-check-inline">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('upload-', module)"
                                                                        :disabled="getPermissionId('upload-', module) == null"
                                                                        :id="generatePermissionName('upload-', module.group, module.page)">
                                                                </div>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="form-check form-check-inline">
                                                                    <input
                                                                        v-model="updateModel.permissions"
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        :value="getPermissionId('export-', module)"
                                                                        :disabled="getPermissionId('export-', module) == null"
                                                                        :id="generatePermissionName('export-', module.group, module.page)">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ $t('message.role.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitUpdate">
                            {{ $t('message.role.update') }}
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
import {stringManipulation} from "../../../mixins/stringManipulation";

export default {
    mixins: [stringManipulation],
    components : {
        VueVirtualTable,
    },
    data() {
        return {
            filterModel: {
                name: '',
                paginate: 50,
            },
            paginate : [
                50,
                150,
                250,
                350,
                'All'
            ],

            modules: [],
            roleList: [],
            checkIds : [],
            updateModel: {
                id: '',
                name: '',
                description: '',
                permissions: [],
            },
            addModel: {
                name: '',
                description: '',
                permissions: [],
            },

            isAllViewPermissionsSelected: false,
            isAllCreatePermissionsSelected: false,
            isAllUpdatePermissionsSelected: false,
            isAllDeletePermissionsSelected: false,
            isAllUploadPermissionsSelected: false,
            isAllExportPermissionsSelected: false,
        }
    },

    async created() {
        this.getRoleList();
        this.getModules();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

        tableConfig() {
            let self = this;
            return [
                {
                    prop : 'id',
                    name : 'ID#',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : 'name',
                    name : self.$t('message.role.r_role_name'),
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'description',
                    name : self.$t('message.role.r_desc'),
                    sortable : true,
                    isHidden : false
                },

                {
                    prop : '_action',
                    name : self.$t('message.teams.t_action'),
                    actionName : 'actionButtons',
                    width : '150',
                    isHidden: false
                },
            ];
        }
    },

    methods : {

        submitUpdate() {
            let self = this;
            let loader = self.$loading.show();

            axios.post('/api/roles-update', this.updateModel)
                .then((res) => {
                    if (res.data.success === true) {
                        loader.hide();

                        swal.fire(
                            self.$t('message.role.alert_updated'),
                            self.$t('message.role.alert_updated_successfully'),
                            'success'
                        )

                        this.getRoleList();
                        $('#modal-update-role-list').modal({
                            show: false
                        });
                    }
                })
                .catch((err) => {
                    swal.fire(
                        self.$t('message.article.alert_err'),
                        err.response.data.message,
                        'error'
                    )

                    loader.hide();
                });
        },

        submitAdd() {
            let self = this;
            if(this.addModel.name == '' || this.addModel.description == '') {
                swal.fire(
                    self.$t('message.role.alert_invalid'),
                    self.$t('message.role.alert_complete_fields'),
                    'error'
                )

                return false;
            }

            axios.post('/api/roles-add', this.addModel)
            .then((res) => {
                if (res.data.success === true) {
                    swal.fire(
                        self.$t('message.role.alert_saved'),
                        self.$t('message.role.alert_saved_successfully'),
                        'success'
                    )

                    this.getRoleList();
                    $('#modal-add-role').modal({
                        show: false
                    });

                    this.addModel = {
                        name: '',
                        description: ''
                    }
                }
            })
            .catch(err => {
                swal.fire(
                    self.$t('message.draft.error'),
                    err.response.data.message,
                    'error'
                )
            })
        },

        getRoleList(page = 1) {
            axios.get('/api/roles-list', {
                params : {
                    name : this.filterModel.name,
                    page : page,
                }
            }).then((res) => {
                this.roleList = res.data
            })
        },

        getModules () {
            let self = this;
            let loader = self.$loading.show();

            axios.get('/api/module', {
                params: {
                    paginate: 'All'
                }
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

        doUpdate(role) {
            this.updateModel = {
                id: role.id,
                name: role.name,
                description: role.description,
                permissions: role.permissions.map(a => a.id)
            }

            this.clearToggles();

            $('#modal-update-role-list').modal({
                show: true
            });
        },

        clearSearch() {
            this.filterModel = {
                paginate : 50,
                name : '',
            }

            this.$router.replace({'query' : null});
            this.getRoleList();
        },

        doDelete(id) {
            let self = this;

            swal.fire({
                title : 'Delete Role',
                text : 'Are you sure that you want to delete this role?',
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let loader = self.$loading.show();

                    axios.post('/api/roles-delete', {id})
                        .then((res) => {
                            if (res.data.success === true) {
                                loader.hide();

                                swal.fire(
                                    self.$t('message.role.alert_deleted'),
                                    self.$t('message.role.alert_deleted_successfully'),
                                    'success'
                                )

                                this.getRoleList();

                                $('#modal-update-role-list').modal({
                                    show: false
                                });
                            }
                        })
                        .catch(err => {
                            loader.hide();

                            swal.fire(
                                self.$t('message.draft.error'),
                                err.response.data.message,
                                'error'
                            )
                        })
                }
            });
        },

        getPermissionId (mode, module) {
            let permissionName = this.generatePermissionName(mode, module.group, module.page);

            if (Array.isArray(module.permissions)) {
                if (module.permissions.length) {
                    let obj = module.permissions.find(o => o.name === permissionName)

                    return obj ? obj.id : null;
                }
            } else {
                return null;
            }
        },

        generatePermissionName (mode, module, page) {
            let moduleTemp = this.convertStringToKebabCase(module);
            let pageTemp = this.convertStringToKebabCase(page);
            let permission = moduleTemp + '-' + pageTemp;

            return mode + permission;
        },

        toggleSelectAllPermissions (mode, func, toggle) {
            let permissionIds = [];

            this.modules.data.forEach((item) => {
                let permissionName = this.generatePermissionName(mode, item.group, item.page);

                if (Array.isArray(item.permissions)) {
                    let obj = item.permissions.find(o => o.name === permissionName)

                    if (obj) {
                        permissionIds.push(obj.id)
                    }
                }
            })

            if (func === 'update') {
                if (toggle) {
                    this.updateModel.permissions.push(...permissionIds);
                } else {
                    this.updateModel.permissions = this.updateModel.permissions.filter( ( el ) => !permissionIds.includes( el ) );
                }
            } else {
                if (toggle) {
                    this.addModel.permissions.push(...permissionIds);
                } else {
                    this.addModel.permissions = this.addModel.permissions.filter( ( el ) => !permissionIds.includes( el ) );
                }
            }
        },

        clearToggles () {
            this.isAllViewPermissionsSelected = false;
            this.isAllCreatePermissionsSelected = false;
            this.isAllUpdatePermissionsSelected = false;
            this.isAllDeletePermissionsSelected = false;
            this.isAllUploadPermissionsSelected = false;
            this.isAllExportPermissionsSelected = false;
        }
    }
}
</script>
