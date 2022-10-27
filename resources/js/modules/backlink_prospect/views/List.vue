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
                        <h3 class="card-title text-primary">{{ $t('message.backlink_prospect.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i>
                                {{ $t('message.backlink_prospect.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_search_ref_domain') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.referring_domain"
                                           aria-describedby="helpId"
                                           :placeholder="$t('message.backlink_prospect.type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_ur') }}</label>
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
                                               :placeholder="$t('message.backlink_prospect.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.ur">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_dr') }}</label>
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
                                               :placeholder="$t('message.backlink_prospect.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.dr">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_org_kw') }}</label>
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
                                               :placeholder="$t('message.backlink_prospect.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_kw">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_org_traffic') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_traffic = buttonState.org_traffic === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.org_traffic }}
                                            </button>
                                        </div>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('message.backlink_prospect.type')"
                                            aria-label=""
                                            aria-describedby="basic-addon1"
                                            v-model="filterModel.org_traffic">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status 3rd Party</label>
                                    <select class="form-control" v-model="filterModel.status">
                                        <option value="">{{ $t('message.backlink_prospect.choose_status') }}</option>
                                        <option v-for="option in status1" v-bind:value="option.value" :key="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status StaLinks URL Prospect</label>
                                    <select class="form-control" v-model="filterModel.status2">
                                        <option value="">{{ $t('message.backlink_prospect.choose_status') }}</option>
                                        <option v-for="option in status2" v-bind:value="option.value" :key="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.backlink_prospect.filter_date') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            opens="left"
                                            v-model="filterModel.date_upload"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_upload"
                                            :linkedCalendars="true"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch()">
                                    {{ $t('message.backlink_prospect.clear') }}
                                </button>
                                <button class="btn btn-default" @click="getBacklinkProspect()">
                                    {{ $t('message.backlink_prospect.search') }}
                                    <i v-if="false" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3rd party totals -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">3rd Party Status Totals</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="Object.keys(backlinkProspectTotals).length !== 0" class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box rounded bg-aqua">
                                    <div class="inner">
                                        <h3>{{ backlinkProspectTotals.Total }}</h3>
                                        <p>{{ $t('message.url_prospect.s_total') }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                </div>
                            </div>

                            <div v-for="total in status1Totals" class="col-lg-3 col-6">
                                <div class="small-box rounded" :class="total.badge">
                                    <div class="inner">
                                        <h3>{{ total.total }}</h3>
                                        <p>
                                            {{ total.text }}

                                            <span>
                                                ({{ total.percentage }}%)
                                            </span>
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i :class="total.icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- StaLinks URL prospect status totals -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">StaLinks URL Prospect Status Totals</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="Object.keys(backlinkProspectTotalsStatus2).length !== 0" class="row">
                            <div v-for="total in status2Totals" class="col-lg-3 col-6">
                                <div class="small-box rounded" :class="total.badge">
                                    <div class="inner">
                                        <h3>{{ total.total }}</h3>
                                        <p>
                                            {{ total.text }}

                                            <span>
                                                ({{ total.percentage }}%)
                                            </span>
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i :class="total.icon"></i>
                                    </div>
                                </div>
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
                        <h3 class="card-title text-primary">{{ $t('message.backlink_prospect.bp_title') }}</h3>
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
                                                :title="$t('message.backlink_prospect.bp_upload_csv')"
                                                class="btn btn-primary btn-flat"
                                                :disabled="btnUpload"
                                                @click="submitUpload">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            <button
                                                :title="$t('message.backlink_prospect.bp_download_csv')"
                                                class="btn btn-primary btn-flat"

                                                @click="downloadTemplate">
                                                <i class="fa fa-download"></i>
                                            </button>

                                            <export-excel
                                                class="btn btn-primary btn-flat"
                                                :data=backinkProspectList.data
                                                :fields="export_fields"
                                                worksheet="My Worksheet"
                                                name="backlink_prospect.xls">
                                                <i class="fa fa-list"></i>
                                                {{ $t('message.backlink_prospect.bp_export') }}

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
                                    <div class="input-group mt-3 mb-3">
                                        <button
                                            class="btn btn-default mr-2"
                                            @click="selectAll">
                                            {{ allSelected ? $t('message.backlink_prospect.bp_deselect') : $t('message.backlink_prospect.bp_select') }}
                                            {{ $t('message.backlink_prospect.all') }}
                                        </button>

                                        <div class="dropdown">
                                            <button
                                                class="btn btn-default dropdown-toggle"
                                                type="button"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ $t('message.backlink_prospect.bp_selected_action') }}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" @click="editData();">
                                                    {{ $t('message.backlink_prospect.edit') }}
                                                </a>
                                                <a class="dropdown-item" @click="deleteData();" v-show="user.isAdmin || user.role_id == 8">
                                                    {{ $t('message.backlink_prospect.delete') }}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        </table>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b v-if="filterModel.paginate !== 'All'">
                                {{ $t('message.others.table_entries', { from: backinkProspectList.from, to: backinkProspectList.to, end: backinkProspectList.total }) }}
                            </b>

                            <b v-else>
                                {{ $t('message.others.table_all_entries', { total: backinkProspectList.total }) }}
                            </b>
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
                                slot="actionStatusProspect">
                                {{ scope.row.prospect != null ? listStatusText[scope.row.prospect.status].text:'' }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="actionButtons">
                                <div class="btn-group">
                                    <button
                                        data-toggle="modal"
                                        data-target="#modal-update-backlink_prospect"
                                        :title="$t('message.backlink_prospect.edit')"
                                        class="btn btn-default"

                                        @click="doUpdate(scope.row)">

                                        <i class="fa fa-fw fa-edit"></i>
                                    </button>

                                    <button
                                        class="btn btn-default"
                                        title="Move to URL Prospect"
                                        @click="moveToUrlProspect(scope.row)"
                                        v-show="scope.row.status === 'To be contacted'"
                                        :disabled="scope.row.is_moved === 1">
                                        <i class="fa fa-fw fa-share"></i>
                                    </button>

                                </div>
                            </template>

                        </vue-virtual-table>

                        <pagination
                            v-if="backinkProspectList.data"
                            :limit="8"
                            :data="backinkProspectList"

                            @pagination-change-page="getBacklinkProspect">

                        </pagination>

                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Edit Backlink Prospect -->
        <div class="modal fade" id="modal-update-backlink_prospect" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.backlink_prospect.ebp_title') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.t_ref_domain') }}</label>
                                    <input type="text" class="form-control" :disabled="true" v-model="updateModel.referring_domain">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status StaLinks URL Prospect</label>
                                    <input v-model="updateModel.status2" disabled type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status 3rd Party</label>
                                    <select class="form-control" v-model="updateModel.status">
                                        <option value="">{{ $t('message.backlink_prospect.choose_status') }}</option>
                                        <option v-for="option in status1" v-bind:value="option.value" :key="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.t_category') }}</label>
                                    <select class="form-control" v-model="updateModel.category">
                                        <option value="">{{ $t('message.backlink_prospect.choose_category') }}</option>
                                        <option v-for="option in category" v-bind:value="option" :key="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.t_note') }}</label>
                                    <textarea class="form-control" cols="30" rows="5" v-model="updateModel.note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ $t('message.backlink_prospect.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="submitUpdate">
                            {{ $t('message.backlink_prospect.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Edit Backlink Prospect -->

        <!-- Modal Edit Multiple -->
        <div class="modal fade" id="modalEditMultipleProspect" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.backlink_prospect.em_title') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.t_category') }}</label>
                                    <select class="form-control" v-model="editModel.category">
                                        <option value="">{{ $t('message.backlink_prospect.choose_category') }}</option>
                                        <option v-for="option in category" v-bind:value="option" :key="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ $t('message.backlink_prospect.filter_status') }}</label>
                                    <select class="form-control" v-model="editModel.status">
                                        <option value="">{{ $t('message.backlink_prospect.choose_status') }}</option>
                                        <option v-for="option in status1" v-bind:value="option.value" :key="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ $t('message.backlink_prospect.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="saveMultiple">
                            {{ $t('message.backlink_prospect.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Edit Multiple -->

    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import VueVirtualTable from 'vue-virtual-table';
import {csvTemplateMixin} from "../../../mixins/csvTemplateMixin";
import {dateRange} from "../../../mixins/dateRange";

export default {
    components : {
        VueVirtualTable,
    },
    mixins: [csvTemplateMixin, dateRange],
    data() {
        return {
            buttonState : {
                ur : 'Above',
                dr : 'Above',
                org_kw : 'Above',
                org_traffic : 'Above'
            },

            editModel: {
                status: '',
                category: '',
            },

            filterModel: {
                referring_domain: '',
                ur: '',
                dr: '',
                org_kw: '',
                org_traffic: '',
                status: '',
                status2: '',
                date_upload: {
                    startDate : null,
                    endDate : null
                },
                paginate: 50,
            },
            category: [
                'News',
                'E-commerce',
                'Blogs',
                'Forums',
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
            backinkProspectListExport: [],
            checkIds : [],
            updateModel: {
                id: '',
                referring_domain: '',
                category: '',
                status: '',
                note: '',
                status2: '',
            },

            total_summary: [],
            backlinkProspectTotals: [],
            backlinkProspectTotalsStatus2: [],

            export_fields: {
                'domain': 'referring_domain',
                'category': 'category',
                'status': 'status',
                'note': 'note',
            },
        }
    },

    async created() {
        this.getBacklinkProspect();
        // this.excelExportData();
        this.getExtListTotals();
        this.getExtListTotalsStatus2();
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listStatusText : state => state.storeExtDomain.listStatusText,
        }),

        exportData() {
            let obj = [];
            let _prop = {};

            for(let index in this.backinkProspectListExport.data) {
                _prop = {
                    "domain": this.backinkProspectListExport.data[index].referring_domain,
                    "category": this.backinkProspectListExport.data[index].category,
                    "status": this.backinkProspectListExport.data[index].status,
                    "note": this.backinkProspectListExport.data[index].note
                }

                obj.push(_prop)
            }


            return obj;
        },

        tableConfig() {
            let self = this;

            return [
                {
                    prop : 'id',
                    name : 'ID',
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
                    name : self.$t('message.backlink_prospect.t_ref_domain'),
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'ur',
                    name : self.$t('message.backlink_prospect.filter_ur'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'dr',
                    name : self.$t('message.backlink_prospect.filter_dr'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'backlinks',
                    name : self.$t('message.backlink_prospect.t_backlinks'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'org_kw',
                    name : self.$t('message.backlink_prospect.filter_org_kw'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'org_traffic',
                    name : self.$t('message.backlink_prospect.filter_org_traffic'),
                    width : 100,
                    isHidden : false
                },
                {
                    prop : 'ref_domain_ahref',
                    name : self.$t('message.backlink_prospect.t_ref_domains'),
                    width : 100,
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'category',
                    name : self.$t('message.backlink_prospect.t_category'),
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'status',
                    name : 'Status 3rd Party',
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : 'Status StaLinks URL Prospect',
                    sortable : true,
                    actionName : 'actionStatusProspect',
                    isHidden : false
                },
                {
                    prop : 'note',
                    name : self.$t('message.backlink_prospect.t_note'),
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : 'created_at',
                    name : self.$t('message.backlink_prospect.t_date_uploaded'),
                    sortable : true,
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : self.$t('message.backlink_prospect.t_action'),
                    actionName : 'actionButtons',
                    width : '150',
                    isHidden: false
                },
            ];
        },

        status1() {
            let self = this;

            return [
                {
                    text: self.$t('message.backlink_prospect.s_new'),
                    value: 'New'
                },
                {
                    text: self.$t('message.backlink_prospect.s_qualified'),
                    value: 'Qualified'
                },
                {
                    text: self.$t('message.backlink_prospect.s_unqualified'),
                    value: 'Unqualified'
                },
                {
                    text: self.$t('message.backlink_prospect.s_hosting_expired'),
                    value: 'Hosting Expired'
                },
                {
                    text: self.$t('message.backlink_prospect.s_free_sub'),
                    value: 'Free Submission'
                },
                {
                    text: self.$t('message.backlink_prospect.s_to_be_contacted'),
                    value: 'To be contacted'
                },
                {
                    text: self.$t('message.backlink_prospect.s_contacted'),
                    value: 'Contacted'
                },
                {
                    text: self.$t('message.backlink_prospect.s_contacted_via_form'),
                    value: 'Contacted Via Form'
                },
                {
                    text: 'In-touched',
                    value: 'In-touched'
                },
                {
                    text: 'Refused',
                    value: 'Refused'
                },
                {
                    text: 'No Answer',
                    value: 'No Answer'
                },
            ];
        },

        status2() {
            let self = this;

            return [
                {
                    text: 'New',
                    value: 0,
                },
                {
                    text: 'Contact Null',
                    value: 20,
                },
                {
                    text: self.$t('message.backlink_prospect.s_contacted'),
                    value: 50,
                },
                {
                    text: self.$t('message.backlink_prospect.s_contacted_via_form'),
                    value: 120,
                },
                {
                    text: self.$t('message.backlink_prospect.s_in_touched'),
                    value: 70,
                },
                {
                    text: self.$t('message.backlink_prospect.s_qualified'),
                    value: 100,
                },
                {
                    text: self.$t('message.backlink_prospect.s_refused'),
                    value: 60,
                },
                {
                    text: self.$t('message.backlink_prospect.s_no_answer'),
                    value: 55,
                },
            ];
        },

        status1Totals () {
            let self = this;

            return [
                {
                    text: self.$t('message.url_prospect.s_new'),
                    total: self.backlinkProspectTotals['New'],
                    badge: 'bg-primary',
                    icon: 'fas fa-asterisk',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['New'])
                },

                {
                    text: self.$t('message.url_prospect.s_qualified'),
                    total: self.backlinkProspectTotals['Qualified'],
                    badge: 'bg-olive',
                    icon: 'fas fa-certificate',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Qualified'])
                },

                {
                    text: self.$t('message.url_prospect.s_unqualified'),
                    total: self.backlinkProspectTotals['Unqualified'],
                    badge: 'bg-dark',
                    icon: 'fas fa-ban',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Unqualified'])
                },

                {
                    text: 'Hosting Expired',
                    total: self.backlinkProspectTotals['Hosting'],
                    badge: 'bg-danger',
                    icon: 'fas fa-hourglass-end',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Hosting'])
                },

                {
                    text: 'Free Submission',
                    total: self.backlinkProspectTotals['Free'],
                    badge: 'bg-gradient-teal',
                    icon: 'fas fa-box-open',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Free'])
                },

                {
                    text: 'To Be Contacted',
                    total: self.backlinkProspectTotals['ToBeContacted'],
                    badge: 'bg-warning',
                    icon: 'fas fa-phone',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['ToBeContacted'])
                },

                {
                    text: self.$t('message.url_prospect.s_contacted'),
                    total: self.backlinkProspectTotals['Contacted'],
                    badge: 'bg-success',
                    icon: 'fas fa-check-circle',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Contacted'])
                },

                {
                    text: self.$t('message.url_prospect.s_contacted_via_form'),
                    total: self.backlinkProspectTotals['ContactedViaForm'],
                    badge: 'bg-secondary',
                    icon: 'fab fa-wpforms',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['ContactedViaForm'])
                },

                {
                    text: self.$t('message.url_prospect.s_in_touched'),
                    total: self.backlinkProspectTotals['Intouched'],
                    badge: 'bg-purple',
                    icon: 'fas fa-comments',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Intouched'])
                },

                {
                    text: self.$t('message.url_prospect.s_refused'),
                    total: self.backlinkProspectTotals['Refused'],
                    badge: 'bg-maroon',
                    icon: 'fas fa-handshake-alt-slash',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['Refused'])
                },

                {
                    text: self.$t('message.url_prospect.s_no_answer'),
                    total: self.backlinkProspectTotals['NoAnswer'],
                    badge: 'bg-orange',
                    icon: 'fas fa-phone-slash',
                    percentage: self.calculatePercentage(self.backlinkProspectTotals['NoAnswer'])
                },
            ];
        },

        status2Totals () {
            let self = this;

            return [
                {
                    text: self.$t('message.url_prospect.s_new'),
                    total: self.backlinkProspectTotalsStatus2['New'],
                    badge: 'bg-primary',
                    icon: 'fas fa-asterisk',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['New'])
                },

                {
                    text: self.$t('message.url_prospect.s_contact_null'),
                    total: self.backlinkProspectTotalsStatus2['ContactNull'],
                    badge: 'bg-warning',
                    icon: 'fas fa-comment-slash',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['ContactNull'])
                },

                {
                    text: self.$t('message.url_prospect.s_contacted'),
                    total: self.backlinkProspectTotalsStatus2['Contacted'],
                    badge: 'bg-success',
                    icon: 'fas fa-check-circle',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['Contacted'])
                },

                {
                    text: self.$t('message.url_prospect.s_contacted_via_form'),
                    total: self.backlinkProspectTotalsStatus2['ContactedViaForm'],
                    badge: 'bg-secondary',
                    icon: 'fab fa-wpforms',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['ContactedViaForm'])
                },

                {
                    text: self.$t('message.url_prospect.s_in_touched'),
                    total: self.backlinkProspectTotalsStatus2['Intouched'],
                    badge: 'bg-purple',
                    icon: 'fas fa-comments',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['Intouched'])
                },

                {
                    text: self.$t('message.url_prospect.s_qualified'),
                    total: self.backlinkProspectTotalsStatus2['Qualified'],
                    badge: 'bg-olive',
                    icon: 'fas fa-certificate',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['Qualified'])
                },

                {
                    text: self.$t('message.url_prospect.s_refused'),
                    total: self.backlinkProspectTotalsStatus2['Refused'],
                    badge: 'bg-maroon',
                    icon: 'fas fa-handshake-alt-slash',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['Refused'])
                },

                {
                    text: self.$t('message.url_prospect.s_no_answer'),
                    total: self.backlinkProspectTotalsStatus2['NoAnswer'],
                    badge: 'bg-orange',
                    icon: 'fas fa-phone-slash',
                    percentage: self.calculatePercentage(self.backlinkProspectTotalsStatus2['NoAnswer'])
                },
            ];
        }
    },

    methods : {

        excelExportData() {
            this.filterModel.date_upload = this.formatFilterDates(this.filterModel.date_upload);

            axios.get('/api/backlink-prospect', {
                params : {
                    referring_domain : this.filterModel.referring_domain,
                    ur : this.filterModel.ur,
                    dr : this.filterModel.dr,
                    status : this.filterModel.status,
                    status2 : this.filterModel.status2,
                    paginate : 'All',
                    org_kw : this.filterModel.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    ur_direction : this.buttonState.ur,
                    dr_direction : this.buttonState.dr,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic_direction : this.buttonState.org_traffic,
                    date_upload : this.filterModel.date_upload,
                    page : 1,
                }
            }).then((res) => {
                this.backinkProspectListExport = res.data
                this.summaryTotal();

            })
        },

        summaryTotal() {
            let self = this;
            let _obj = [];
            let total = [];
            let _prop = {};
            let total_val = 0;
            let icons = [
                'fa fa-bell',
                'fa fa-check',
                'fa fa-thumbs-down',
                'fa fa-exclamation',
                'fa fa-envelope',
                'fa fa-user',
                'fas fa-envelope-open-text',
                'fas fa-newspaper',
                'fas fa-comments',
                'fas fa-handshake-alt-slash',
                'fas fa-phone-slash'
            ];
            let bg_colors = [
                'small-box bg-info',
                'small-box bg-green',
                'small-box bg-danger',
                'small-box bg-warning',
                'small-box bg-olive',
                'small-box bg-gradient-purple',
                'small-box bg-navy',
                'small-box bg-secondary',
                'small-box bg-purple',
                'small-box bg-maroon',
                'small-box bg-orange',
            ];
            for(let i in this.status1) {
                _prop = {
                    "status": this.status1[i].text,
                    "total": this.computeTotal(this.status1[i].value),
                    "icon": icons[i],
                    "bg_color": bg_colors[i]
                }

                total.push(this.computeTotal(this.status1[i].value))
                _obj.push(_prop)
            }

            // total_val = total.reduce((partialSum, a) => partialSum + a, 0);
            //
            // _obj = Object.assign({8:{
            //     "status": "Total URLs",
            //     "total": total_val,
            //     "icon": "fa fa-link",
            //     "bg_color": "small-box bg-gradient-teal"
            // }}, _obj)

            // total urls

            let temp_total = {
                "status": self.$t('message.backlink_prospect.s_total_urls'),
                "total": self.backinkProspectListExport.total,
                "icon": "fa fa-link",
                "bg_color": "small-box bg-gradient-teal"
            }

            _obj.unshift(temp_total);

            this.total_summary = _obj
        },

        computeTotal(stat) {
            let total = 0;
            let arr = [];
            for(let i in this.backinkProspectListExport.data) {
                if(stat === this.backinkProspectListExport.data[i].status) {
                    arr.push(this.backinkProspectListExport.data[i].status)
                }
            }

            return arr.length > 0 ? arr.length:total;
        },

        moveToUrlProspect(backlink_prospect) {
            let self = this;

            axios.post('/api/backlink-prospect-move', {
                id : backlink_prospect.id,
                referring_domain: backlink_prospect.referring_domain
            }).then((res) => {
                console.log(res.data)

                if(res.data) {
                    swal.fire(
                        self.$t('message.backlink_prospect.alert_success'),
                        self.$t('message.backlink_prospect.alert_moved_successfully'),
                        'success'
                    )
                } else {
                    swal.fire(
                        self.$t('message.backlink_prospect.alert_success'),
                        self.$t('message.backlink_prospect.alert_domain_exists'),
                        'success'
                    )
                }


                this.getBacklinkProspect();
            })
        },

        submitUpdate() {
            let self = this;

            axios.post('/api/backlink-prospect-update', this.updateModel)
                .then((res) => {
                    if (res.data.success === true) {
                        swal.fire(
                            self.$t('message.backlink_prospect.alert_uploaded'),
                            self.$t('message.backlink_prospect.alert_uploaded_successfully'),
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
            let self = this;

            this.formData = new FormData();
            this.formData.append('file', this.$refs.excel.files[0]);

            axios.post('/api/backlink-prospect-upload-csv', this.formData)
                .then((res) => {

                    if (res.data.success === true) {
                        swal.fire(
                            self.$t('message.backlink_prospect.alert_uploaded'),
                            self.$t('message.backlink_prospect.alert_uploaded_successfully'),
                            'success'
                        )

                        this.$refs.excel.value = '';
                        this.btnUpload = true;
                        this.getBacklinkProspect()

                    } else {
                        swal.fire(
                            self.$t('message.publisher.alert_failed'),
                            self.$t('message.publisher.alert_upload_fail'),
                            'error'
                        )
                    }
                });

        },

        getBacklinkProspect(page = 1) {
            let loader = this.$loading.show();
            this.filterModel.date_upload = this.formatFilterDates(this.filterModel.date_upload);

            axios.get('/api/backlink-prospect', {
                params : {
                    referring_domain : this.filterModel.referring_domain,
                    ur : this.filterModel.ur,
                    dr : this.filterModel.dr,
                    status : this.filterModel.status,
                    status2 : this.filterModel.status2,
                    paginate : this.filterModel.paginate,
                    org_kw : this.filterModel.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    date_upload : this.filterModel.date_upload,
                    ur_direction : this.buttonState.ur,
                    dr_direction : this.buttonState.dr,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic_direction : this.buttonState.org_traffic,
                    page : page,
                }
            }).then((res) => {
                this.backinkProspectList = res.data
                loader.hide();
            })
        },

        editData() {
            let self = this;

            if( this.checkIds.length > 0 ) {
                $('#modalEditMultipleProspect').modal({
                    show: true
                });
            } else {
                swal.fire(
                    self.$t('message.backlink_prospect.alert_invalid'),
                    self.$t('message.backlink_prospect.alert_please_select'),
                    'error'
                )
            }
        },

        saveMultiple() {
            let self = this;

            axios.post('/api/backlink-prospect-edit', {
                ids : this.checkIds,
                category: this.editModel.category,
                status: this.editModel.status
            }).then((res) => {
                swal.fire(
                    self.$t('message.backlink_prospect.alert_success'),
                    self.$t('message.backlink_prospect.alert_updated_successfully'),
                    'success'
                )

                this.allSelected = true;
                this.checkIds = [];
                this.editModel = {
                    category: '',
                    status: '',
                }

                $('#modalEditMultipleProspect').modal({
                    show: false
                });

                this.getBacklinkProspect();
            })
        },

        doUpdate(backlink_prospect) {

            this.updateModel = {
                id: backlink_prospect.id,
                referring_domain: backlink_prospect.referring_domain,
                category: backlink_prospect.category,
                status: backlink_prospect.status,
                note: backlink_prospect.note,
                status2: backlink_prospect.status2
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
                status2 : '',
                ur : '',
                dr : '',
                org_kw : '',
                org_traffic : '',
                date_upload : {
                    startDate : null,
                    endDate : null
                },
            }

            this.$router.replace({'query' : null});
            this.getBacklinkProspect();
            // this.excelExportData();
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
            let self = this;

            if( this.checkIds.length > 0 ) {
                axios.post('/api/backlink-prospect-delete', {
                    ids : this.checkIds
                }).then((res) => {
                    swal.fire(
                        self.$t('message.backlink_prospect.alert_deleted'),
                        self.$t('message.backlink_prospect.alert_deleted_successfully'),
                        'success'
                    )

                    this.allSelected = true;
                    this.checkIds = [];
                    this.getBacklinkProspect();
                })
            } else {
                swal.fire(
                    self.$t('message.backlink_prospect.alert_invalid'),
                    self.$t('message.backlink_prospect.alert_please_select'),
                    'error'
                )
            }

        },

        downloadTemplate() {
            let headers = [];

            let rows = ['Referring Domain'];

            headers.push(rows);

            this.downloadCsvTemplate(headers, 'backlink_prospect_list_data');
        },

        getExtListTotals () {
            axios.get('/api/backlink-prospect-totals')
            .then((res) => {
                this.backlinkProspectTotals = res.data
            })
            .catch((err) => {
                console.log(err)
            })
        },

        getExtListTotalsStatus2 () {
            axios.get('/api/backlink-prospect-totals-2')
                .then((res) => {
                    this.backlinkProspectTotalsStatus2 = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
        },

        calculatePercentage (number) {
            if (this.backlinkProspectTotals.Total === 0) {
                return 0;
            } else {
                return ((number / this.backlinkProspectTotals.Total) * 100).toFixed(2);
            }
        },
    }
}
</script>
