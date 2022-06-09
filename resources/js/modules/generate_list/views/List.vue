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
                        <h3 class="card-title text-primary">{{ $t('message.generate_list.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.generate_list.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.generate_list.filter_search_url') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.url"
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.generate_list.type')">
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
                                               :placeholder="$t('message.generate_list.type')"
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
                                               :placeholder="$t('message.generate_list.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.dr">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.generate_list.filter_org_kw') }}</label>
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
                                               :placeholder="$t('message.generate_list.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_kw">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.generate_list.filter_org_traffic') }}</label>
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
                                               :placeholder="$t('message.generate_list.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_traffic">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.generate_list.filter_code') }}</label>

                                    <v-select
                                        v-model="filterModel.code"
                                        multiple
                                        :placeholder="$t('message.generate_list.all')"
                                        :options="listCode"
                                        :searchable="false"
                                    />
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">
                                    {{ $t('message.generate_list.clear') }}
                                </button>
                                <button class="btn btn-default" @click="getGenerateList()">
                                    {{ $t('message.generate_list.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.generate_list.gl_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <table width="100%" class="mt-3 mb-3">
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
                                                :title="$t('message.generate_list.gl_upload_csv')"
                                                class="btn btn-primary btn-flat"
                                                :disabled="btnUpload"
                                                @click="submitUpload">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            <button
                                                :title="$t('message.generate_list.gl_download_csv')"
                                                class="btn btn-primary btn-flat"

                                                @click="downloadTemplate">
                                                <i class="fa fa-download"></i>
                                            </button>

                                            <export-excel
                                                class="btn btn-primary btn-flat"
                                                :data=generateList.data
                                                worksheet="My Worksheet"
                                                name="generate_list.xls">
                                                <i class="fa fa-list"></i>
                                                {{ $t('message.generate_list.gl_export') }}

                                            </export-excel>

                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-success"
                                            data-toggle="modal"
                                            data-target="#modalAddUrlGenerateList">
                                        {{ $t('message.generate_list.gl_add_url') }}
                                    </button>
                                </td>
                                <td width="100px">
                                    <div class="input-group input-group-sm float-right" style="width: 100px">
                                        <select name=""
                                                class="form-control float-right"
                                                @change="getGenerateList"
                                                v-model="filterModel.paginate"
                                                style="height: 37px;">
                                            <option v-for="option in paginate" v-bind:value="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="input-group mt-3">
                                        <button class="btn btn-default mr-2" @click="selectAll">
                                            {{ allSelected ? $t('message.generate_list.gl_deselect') : $t('message.generate_list.gl_select') }} {{ $t('message.generate_list.all') }}
                                        </button>

                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                {{ $t('message.generate_list.gl_selected_action') }}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" @click="deleteData();">{{ $t('message.generate_list.gl_delete') }}</a>
                                                <a class="dropdown-item" @click="getAhref()">{{ $t('message.generate_list.gl_get_ahref') }}</a>
                                                <a class="dropdown-item" @click="computePrice()">{{ $t('message.generate_list.gl_compute_price') }}</a>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </table>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b v-if="filterModel.paginate !== 'All'">
                                {{ $t('message.others.table_entries', { from: generateList.from, to: generateList.to, end: generateList.total }) }}
                            </b>
                            <b v-else>
                                {{ $t('message.others.table_all_entries', { total: generateList.total }) }}
                            </b>
                        </span>

                        <vue-virtual-table
                            width="100%"
                            :height="1000"
                            :bordered="true"
                            :item-height="60"
                            :config="tableConfig"
                            :data="generateList.data">
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
                                slot="value1Data">
                                {{ scope.row.dr - scope.row.ur }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="value2Data">
                                {{
                                    (scope.row.backlinks == 0 || scope.row.ref_domain == 0) ? 0 : (scope.row.backlinks / scope.row.ref_domain).toFixed(2)
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="orgTrafficData">
                                {{
                                    formatPrice(scope.row.org_traffic)
                                }}
                            </template>
                        </vue-virtual-table>

                        <pagination
                            v-if="generateList.data"
                            :limit="8"
                            :data="generateList"

                            @pagination-change-page="getGenerateList">

                        </pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add URL -->
        <div class="modal fade" id="modalAddUrlGenerateList" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.generate_list.au_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.generate_list.au_url') }}</label>
                                    <input type="text" class="form-control" v-model="addModel.url" placeholder="" required>
                                    <small class="form-text text-danger" v-show="errorMessage.url">
                                        {{ $t('message.generate_list.au_url_error') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="errorMessage.url = false">
                            {{ $t('message.generate_list.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitAdd">
                            {{ $t('message.generate_list.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Add URL -->
    </div>
</template>

<style>
.color-coding-a {
    background: #f2a035;
    color: #fff;
}

.color-coding-b {
    background: #ffda6b;
    color: #000;
}

.color-coding-c {
    background: #2dc22b;
    color: #fff;
}

.color-coding-d {
    background: #4878e0;
    color: #fff;
}

.color-coding-e {
    background: #ffcccc;
    color: #b30000;
}
</style>

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
            filterModel : {
                paginate : 50,
                url : '',
                ur : '',
                dr : '',
                org_kw : '',
                org_traffic : '',
                code : '',
            },
            paginate : [
                50,
                150,
                250,
                350,
                'All'
            ],
            buttonState : {
                ur : 'Above',
                dr : 'Above',
                org_kw : 'Above',
                org_traffic : 'Above'
            },
            checkIds : [],
            generateList : [],
            btnUpload : true,
            listCode : [
                '4A',
                '3A',
                '2A',
                '1A',
                '0A'
            ],
            allSelected : false,
            addModel : {
                url : '',
            },
            errorMessage : {
                url : false
            },
        }
    },

    async created() {
        this.getGenerateList();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,

        }),

        tableConfig() {
            let self = this;
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
                    prop : 'url',
                    name : self.$t('message.generate_list.t_url'),
                    // width: 200,
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
                    prop : '_action',
                    name : self.$t('message.generate_list.t_value1'),
                    actionName : 'value1Data',
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'code_1',
                    name : self.$t('message.generate_list.t_code1'),
                    width : 50,
                    sortable : true,
                    isHidden : false,
                    eClass : this.tableConfigColorClasses('code1_numeral_value'),
                },
                {
                    prop : 'backlinks',
                    name : self.$t('message.generate_list.t_backlinks'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'ref_domain',
                    name : self.$t('message.generate_list.t_ref_domains'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : self.$t('message.generate_list.t_value2'),
                    actionName : 'value2Data',
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'code_2',
                    name : self.$t('message.generate_list.t_code2'),
                    width : 50,
                    sortable : true,
                    isHidden : false,
                    eClass : this.tableConfigColorClasses('code2_numeral_value'),
                },
                {
                    prop : 'org_kw',
                    name : self.$t('message.generate_list.filter_org_kw'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'code_3',
                    name : self.$t('message.generate_list.t_code3'),
                    width : 50,
                    sortable : true,
                    isHidden : false,
                    eClass : this.tableConfigColorClasses('code3_numeral_value'),
                },
                {
                    prop : '_action',
                    name : self.$t('message.generate_list.filter_org_traffic'),
                    actionName : 'orgTrafficData',
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'code_4',
                    name : self.$t('message.generate_list.t_code4'),
                    width : 50,
                    sortable : true,
                    isHidden : false,
                    eClass : this.tableConfigColorClasses('code4_numeral_value'),
                },
                {
                    prop : 'code_comb',
                    name : self.$t('message.generate_list.t_code_comb'),
                    width : 80,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'price',
                    name : self.$t('message.generate_list.t_price'),
                    prefix : '$',
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
            ];
        }
    },

    methods : {
        tableConfigColorClasses(code) {
            return {
                'color-coding-a' : "${" + code + "} == 1",
                'color-coding-b' : "${" + code + "} == 2",
                'color-coding-c' : "${" + code + "} == 3",
                'color-coding-d' : "${" + code + "} == 4",
                'color-coding-e' : "${" + code + "} == 5",
            }
        },

        submitAdd() {
            let self = this;
            if (this.addModel.url == '') {
                this.errorMessage.url = true
                return false;
            }

            axios.post('/api/generate-list-add-url', this.addModel)
                .then((res) => {
                    if (res.data.success === true) {
                        swal.fire(
                            self.$t('message.generate_list.alert_saved'),
                            self.$t('message.generate_list.alert_updated_successfully'),
                            'success'
                        )
                    } else {
                        swal.fire(
                            self.$t('message.generate_list.alert_error'),
                            self.$t('message.generate_list.alert_url_exists'),
                            'error'
                        )
                    }

                    this.errorMessage.url = false
                    this.addModel.url = ''
                    this.getGenerateList()
                })
        },

        getGenerateList(page = 1) {
            let loader = this.$loading.show();

            axios.get('/api/generate-list', {
                params : {
                    url : this.filterModel.url,
                    ur : this.filterModel.ur,
                    dr : this.filterModel.dr,
                    paginate : this.filterModel.paginate,
                    org_kw : this.filterModel.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    code : this.filterModel.code,
                    ur_direction : this.buttonState.ur,
                    dr_direction : this.buttonState.dr,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic_direction : this.buttonState.org_traffic,
                    page : page,
                }
            }).then((res) => {
                this.generateList = res.data
                loader.hide();
            })
        },

        submitUpload() {
            let self = this;
            this.formData = new FormData();
            this.formData.append('file', this.$refs.excel.files[0]);

            axios.post('/api/generate-list-upload-csv', this.formData)
                .then((res) => {

                    if (res.data.success === true) {
                        swal.fire(
                            self.$t('message.generate_list.alert_uploaded'),
                            self.$t('message.generate_list.alert_uploaded_successfully'),
                            'success'
                        )

                        this.$refs.excel.value = '';
                        this.btnUpload = true;
                        this.getGenerateList()

                    } else {

                    }
                    console.log(res)
                });

        },

        deleteData() {
            let self = this;
            axios.post('/api/generate-list-delete', {
                ids : this.checkIds
            }).then((res) => {
                swal.fire(
                    self.$t('message.generate_list.alert_deleted'),
                    self.$t('message.generate_list.alert_deleted_successfully'),
                    'success'
                )

                this.allSelected = true;
                this.checkIds = [];
                this.getGenerateList();
            })
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
                url : '',
                ur : '',
                dr : '',
                org_kw : '',
                org_traffic : '',
                code : '',
            }

            this.$router.replace({'query' : null});
            this.getGenerateList();
        },

        selectAll : function () {
            this.checkIds = [];
            if (!this.allSelected) {
                for (var index in this.generateList.data) {
                    this.checkIds.push(this.generateList.data[index].id);
                }
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
        },

        getAhref() {
            let self = this;
            swal.fire({
                title : self.$t('message.generate_list.alert_getting_ahrefs'),
                text : self.$t('message.generate_list.alert_please_wait'),
                timerProgressBar : true,
                showConfirmButton : false,
                allowOutsideClick : false,
                onBeforeOpen : () => {
                    swal.showLoading()
                },
            });

            axios.post('/api/generate-list-ahref', {
                ids : this.checkIds
            }).then((res) => {

                swal.fire(
                    self.$t('message.generate_list.alert_success'),
                    self.$t('message.generate_list.alert_updated_ahref'),
                    'success'
                )

                this.allSelected = true;
                this.checkIds = [];
                this.getGenerateList();
            })
        },

        computePrice() {
            let self = this;
            swal.fire({
                title : self.$t('message.generate_list.alert_computing_price'),
                text : self.$t('message.generate_list.alert_please_wait'),
                timerProgressBar : true,
                showConfirmButton : false,
                allowOutsideClick : false,
                onBeforeOpen : () => {
                    swal.showLoading()
                },
            });

            axios.post('/api/generate-list-compute-price', {
                ids : this.checkIds
            }).then((res) => {

                swal.fire(
                    self.$t('message.generate_list.alert_success'),
                    self.$t('message.generate_list.alert_computed_successfully'),
                    'success'
                )

                this.allSelected = true;
                this.checkIds = [];
                this.getGenerateList();
            })
        },

        formatPrice(value) {
            return (value / 1).toFixed(0);
        },

        downloadTemplate() {
            let headers = [];

            let rows = ['URL'];

            headers.push(rows);

            this.downloadCsvTemplate(headers, 'generate_list_csv_template');
        }
    }
}
</script>
