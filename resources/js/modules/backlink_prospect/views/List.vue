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
                                    <label>Search Referring Domain</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.referring_domain"
                                           aria-describedby="helpId"
                                           placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>UR</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.ur = buttonState.ur === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.ur }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.ur">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>DR</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.dr = buttonState.dr === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.dr }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.dr">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Org Kw</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_kw = buttonState.org_kw === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.org_kw }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_kw">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Org Traffic</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_traffic = buttonState.org_traffic === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.org_traffic }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_traffic">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" v-model="filterModel.status">
                                        <option value="">Choose Status</option>
                                        <option v-for="option in status" v-bind:value="option" :key="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">Clear</button>
                                <button class="btn btn-default" @click="getBacklinkProspect()">Search <i v-if="false" class="fa fa-refresh fa-spin"></i>
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
                        <h3 class="card-title text-primary">Backlinks Prospect</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <table width="100%" class="mt-3">
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                               class="form-control"
                                               @change="checkData()"
                                               enctype="multipart/form-data"
                                               ref="excel"
                                               name="file">
                                        <div class="input-group-btn">
                                            <button
                                                title="Upload CSV File"
                                                class="btn btn-primary btn-flat"
                                                :disabled="btnUpload"
                                                @click="submitUpload">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            <button
                                                title="Download CSV Template"
                                                class="btn btn-primary btn-flat"

                                                @click="downloadTemplate">
                                                <i class="fa fa-download"></i>
                                            </button>

                                            <export-excel
                                                class="btn btn-primary btn-flat"
                                                :data=exportData
                                                worksheet="My Worksheet"
                                                name="backlink_prospect.xls">
                                                <i class="fa fa-list"></i>
                                                Export

                                            </export-excel>

                                        </div>
                                    </div>
                                </td>
                                <td width="100px">
                                    <div class="input-group input-group-sm float-right" style="width: 100px">
                                        <select name=""
                                                class="form-control float-right"
                                                @change="getBacklinkProspect"
                                                v-model="filterModel.paginate"
                                                style="height: 37px;">
                                            <option v-for="option in paginate" v-bind:value="option" :key="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input-group mt-3">
                                        <button class="btn btn-default mr-2"
                                                @click="selectAll">{{
                                                allSelected
                                                    ?
                                                    "Deselect"
                                                    : "Select"
                                                                   }} All
                                        </button>

                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                Selected Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" @click="deleteData();">Delete</a>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </table>

                        <span class="pagination-custom-footer-text">
                            <b v-if="filterModel.paginate !== 'All'">Showing {{ backinkProspectList.from }} to {{
                                    backinkProspectList.to
                                                                     }} of {{ backinkProspectList.total }} entries.</b>
                            <b v-else>Showing {{ backinkProspectList.total }} entries.</b>
                        </span>

                        <vue-virtual-table
                            width="100%"
                            :height="1000"
                            :bordered="true"
                            :item-height="60"
                            :config="tableConfig"
                            :data="backinkProspectList.data">

                            <template
                                slot-scope="scope"
                                slot="actionSelectRow">

                                <input
                                    type="checkbox"
                                    class="custom-checkbox"
                                    :value="scope.row.id"
                                    :id="scope.row.id"
                                    v-model="checkIds">

                                
                            </template>

                            <template
                                slot-scope="scope"
                                slot="actionButtons">
                                <div class="btn-group">
                                    <button
                                        data-toggle="modal"
                                        @click="doUpdate(scope.row)" data-target="#modal-update-backlink_prospect" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                </div>
                            </template>

                        </vue-virtual-table>

                        <pagination v-if="backinkProspectList.data"
                                    :data="backinkProspectList"
                                    @pagination-change-page="getBacklinkProspect"
                                    :limit="8"></pagination>

                    </div>
                </div>
            </div>
        </div>

        
        <!-- Modal Edit Backlink Prospect -->
        <div class="modal fade" id="modal-update-backlink_prospect" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Backlink Prospect</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Referring Domain</label>
                                    <input type="text" class="form-control" :disabled="true" v-model="updateModel.referring_domain">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Category</label>
                                    <select class="form-control" v-model="updateModel.category">
                                        <option value="">Choose Category</option>
                                        <option v-for="option in category" v-bind:value="option" :key="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select class="form-control" v-model="updateModel.status">
                                        <option value="">Choose Status</option>
                                        <option v-for="option in status" v-bind:value="option" :key="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="updateModel.note"></textarea>
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
import {csvTemplateMixin} from "../../../mixins/csvTemplateMixin";

export default {
    components : {
        VueVirtualTable,
    },
    mixins: [csvTemplateMixin],
    data() {
        return {
            buttonState : {
                ur : 'Above',
                dr : 'Above',
                org_kw : 'Above',
                org_traffic : 'Above'
            },

            filterModel: {
                referring_domain: '',
                ur: '',
                dr: '',
                org_kw: '',
                org_traffic: '',
                status: '',
                paginate: 50,
            },
            category: [
                'News',
                'E-commerce',
                'Blogs',
                'Forums',
                'Free Submission',
            ],
            status: [
                'New',
                'Contacted',
                'Contacted Via Form',
                'In-touched',
                'Qualified',
                'Refused',
                'Unqualified',
                'No Answer',
                'Hosting Expired',
                'Free Submission',
            ],
            paginate : [
                50,
                150,
                250,
                350,
                'All'
            ],

            btnUpload : true,
            allSelected: false,
            backinkProspectList: [],
            checkIds : [],
            updateModel: {
                id: '',
                referring_domain: '',
                category: '',
                status: '',
                note: '',
            },
        }
    },

    async created() {
        this.getBacklinkProspect();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
        }),

        exportData() {
            let obj = [];
            let _prop = {};

            for(let index in this.backinkProspectList.data) {
                _prop = {
                    "domain": this.backinkProspectList.data[index].referring_domain,
                    "category": this.backinkProspectList.data[index].category,
                    "status": this.backinkProspectList.data[index].status,
                    "note": this.backinkProspectList.data[index].note
                }

                obj.push(_prop)
            }


            return obj;
        },

        tableConfig() {
            return [
                {
                    prop : '_index',
                    name : '#',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : ' ',
                    actionName : 'actionSelectRow',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : 'referring_domain',
                    name : 'Referring Domain',
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'ur',
                    name : 'UR',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'dr',
                    name : 'DR',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'backlinks',
                    name : 'Backlinks',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'org_kw',
                    name : 'Org Kw',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'org_traffic',
                    name : 'Org Traffic',
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'ref_domain_ahref',
                    name : 'Ref Domains',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'category',
                    name : 'Category',
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'status',
                    name : 'Status',
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'note',
                    name : 'Note',
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
            axios.post('/api/backlink-prospect-update', this.updateModel)
                .then((res) => {
                    if (res.data.success === true) {
                        swal.fire(
                            'Uploaded!',
                            'Successfully Uploaded.',
                            'success'
                        )

                        this.getBacklinkProspect();
                        $('#modal-update-backlink_prospect').modal({
                            show: false
                        });
                    }
                })
        },

        submitUpload() {
            this.formData = new FormData();
            this.formData.append('file', this.$refs.excel.files[0]);

            axios.post('/api/backlink-prospect-upload-csv', this.formData)
                .then((res) => {

                    if (res.data.success === true) {
                        swal.fire(
                            'Uploaded!',
                            'Successfully Uploaded.',
                            'success'
                        )

                        this.$refs.excel.value = '';
                        this.btnUpload = true;
                        this.getBacklinkProspect()

                    } else {

                    }
                    console.log(res)
                });

        },

        getBacklinkProspect(page = 1) {
            axios.get('/api/backlink-prospect', {
                params : {
                    referring_domain : this.filterModel.referring_domain,
                    ur : this.filterModel.ur,
                    dr : this.filterModel.dr,
                    status : this.filterModel.status,
                    paginate : this.filterModel.paginate,
                    org_kw : this.filterModel.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    ur_direction : this.buttonState.ur,
                    dr_direction : this.buttonState.dr,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic_direction : this.buttonState.org_traffic,
                    page : page,
                }
            }).then((res) => {
                this.backinkProspectList = res.data
            })
        },

        doUpdate(backlink_prospect) {

            this.updateModel = {
                id: backlink_prospect.id,
                referring_domain: backlink_prospect.referring_domain,
                category: backlink_prospect.category,
                status: backlink_prospect.status,
                note: backlink_prospect.note
            }

            $('#modal-update-backlink_prospect').modal({
                show: true
            });
        },

        checkData() {
            this.btnUpload = true;
            if (this.$refs.excel.value) {
                this.btnUpload = false;
            }
        },

        clearSearch() {
            this.filterModel = {
                paginate : 50,
                referring_domain : '',
                status : '',
                ur : '',
                dr : '',
                org_kw : '',
                org_traffic : '',
            }

            this.$router.replace({'query' : null});
            this.getBacklinkProspect();
        },

        selectAll() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (var index in this.backinkProspectList.data) {
                    this.checkIds.push(this.backinkProspectList.data[index].id);
                }
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
        },

        deleteData() {
            axios.post('/api/backlink-prospect-delete', {
                ids : this.checkIds
            }).then((res) => {
                swal.fire(
                    'Deleted',
                    'Successfully Deleted.',
                    'success'
                )

                this.allSelected = true;
                this.checkIds = [];
                this.getBacklinkProspect();
            })
        },

        downloadTemplate() {
            let headers = [];

            let rows = ['Referring Domain'];

            headers.push(rows);

            this.downloadCsvTemplate(headers, 'backlink_prospect_list_data');
        }
    }
}
</script>
