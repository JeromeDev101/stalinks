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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Search Role</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.name"
                                           aria-describedby="helpId"
                                           placeholder="Type here">
                                </div>
                            </div>
            
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">Clear</button>
                                <button class="btn btn-default" @click="getRoleList()">Search <i v-if="false" class="fa fa-refresh fa-spin"></i>
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
                        <h3 class="card-title text-primary">Roles</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modal-add-role">Add Role</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="input-group input-group-sm float-right" style="width: 100px">
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
                        
                        <span class="pagination-custom-footer-text">
                            <b v-if="filterModel.paginate !== 'All'">Showing {{ roleList.from }} to {{
                                    roleList.to
                                                                     }} of {{ roleList.total }} entries.</b>
                            <b v-else>Showing {{ roleList.total }} entries.</b>
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
                                        data-toggle="modal"
                                        @click="doUpdate(scope.row)" data-target="#modal-update-role-list" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    <button class="btn btn-default" title="Delete" @click="doDelete(scope.row.id)">
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="addModel.name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="addModel.description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submitAdd">Save</button>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Modal Edit Role -->
        <div class="modal fade" id="modal-update-role-list" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="updateModel.name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="updateModel.description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submitUpdate">Update</button>
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

export default {
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

            roleList: [],
            checkIds : [],
            updateModel: {
                id: '',
                name: '',
                description: '',
            },
            addModel: {
                name: '',
                description: '',
            },
        }
    },

    async created() {
        this.getRoleList();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

        tableConfig() {
            return [
                {
                    prop : '_index',
                    name : '#',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : 'name',
                    name : 'Role Name',
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'description',
                    name : 'Description',
                    sortable : true,
                    isHidden : false
                },
                
                {
                    prop : '_action',
                    name : 'Action',
                    actionName : 'actionButtons',
                    width : '150',
                    isHidden: false
                },
            ];
        }
    },

    methods : {

        submitUpdate() {
            axios.post('/api/roles-update', this.updateModel)
                .then((res) => {
                    if (res.data.success === true) {
                        swal.fire(
                            'Uploaded!',
                            'Successfully Updated.',
                            'success'
                        )

                        this.getRoleList();
                        $('#modal-update-role-list').modal({
                            show: false
                        });
                    }
                })
        },

        submitAdd() {
            if(this.addModel.name == '' || this.addModel.description == '') {
                swal.fire(
                    'Invalid!',
                    'Please complete all the fields.',
                    'error'
                )

                return false;
            }

            axios.post('/api/roles-add', this.addModel)
            .then((res) => {
                if (res.data.success === true) {
                    swal.fire(
                        'Uploaded!',
                        'Successfully Saved.',
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

        doUpdate(role) {

            this.updateModel = {
                id: role.id,
                name: role.name,
                description: role.description
            }

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
            axios.post('/api/roles-delete', {id})
                .then((res) => {
                    if (res.data.success === true) {
                        swal.fire(
                            'Uploaded!',
                            'Successfully Deleted.',
                            'success'
                        )

                        this.getRoleList();
                        $('#modal-update-role-list').modal({
                            show: false
                        });
                    }
                })
        },
    }
}
</script>
