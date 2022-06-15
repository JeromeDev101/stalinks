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
            <div v-for="(count, key) in logTotals" class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div :class="'small-box bg-' + (actionMeta[key] ? actionMeta[key].label : 'green')">
                    <div class="inner">
                        <h3>{{ count }}</h3>
                        <p style="text-transform: capitalize">{{ $t('message.system_logs.total_text') }} {{ listAction[key] ? listAction[key] : key }}</p>
                    </div>
                    <div class="icon">
                        <i :class="'fas fa-' + (actionMeta[key] ? actionMeta[key].icon : 'circle')"></i>
                    </div>
                    <div class="overlay" v-if="isTotalsLoading">
                        <i class="fas fa-3x fa-spin fa-sync-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.system_logs.sl_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <v-select
                                    class="style-chooser"
                                    v-model="selectedRole"
                                    :options="rolesList"
                                    :reduce="roleTemp => roleTemp.id"
                                    label="name"
                                    :placeholder="$t('message.system_logs.sl_roles')"/>
                            </div>

                            <div class="col-md-3">
                                <v-select
                                    class="style-chooser"
                                    v-model="filterModel.user_id_temp"
                                    :options="listUser.data"
                                    :reduce="logUser => logUser.id"
                                    label="username"
                                    :placeholder="$t('message.system_logs.sl_users')"/>
                            </div>

                            <div class="col-md-3">
                                <v-select
                                    class="style-chooser"
                                    v-model="filterModel.table_temp"
                                    :options="listTable"
                                    :reduce="tableTemp => tableTemp.id"
                                    label="value"
                                    :placeholder="$t('message.system_logs.sl_tables')"/>
                            </div>

                            <div class="col-md-3">
                                <v-select
                                    class="style-chooser"
                                    v-model="filterModel.action_temp"
                                    :options="listAction"
                                    :reduce="actionTemp => actionTemp.id"
                                    label="value"
                                    :placeholder="$t('message.system_logs.sl_actions')"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <v-select
                                    class="style-chooser"
                                    v-model="filterModel.path_temp"
                                    :options="listPages"
                                    :reduce="pathTemp => pathTemp.path"
                                    label="name"
                                    :placeholder="$t('message.system_logs.sl_pages')"/>
                            </div>

                            <div class="col-md-3">
                                <date-range-picker
                                    v-model="filterModel.date"
                                    :ranges="generateDefaultDateRange()"
                                    ref="picker"
                                    opens="right"
                                    style="width: 100%"
                                    :linkedCalendars="true"
                                    :dateRange="filterModel.date"
                                    :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                />
                            </div>

                            <div class="col-md-3">
                                <button @click="doSearchList"
                                        type="submit"
                                        title="Filter"
                                        class="btn btn-default col-5 mr-2">
                                    {{ $t('message.system_logs.search') }}
                                </button>

                                <button @click="clearFilter"
                                        title="Clear Filter"
                                        class="btn btn-default col-5">
                                    {{ $t('message.system_logs.clear') }}
                                </button>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col-md-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            {{ $t('message.system_logs.sl_delete') }}
                                        </span>
                                    </div>

                                    <select class="form-control" v-model="deleteModel.month">
                                        <option value="">{{ $t('message.system_logs.sl_select_month') }}</option>
                                        <option v-for="value in months" :value="value.month">{{ value.month_name }}</option>
                                    </select>

                                    <div class="input-group-append">
                                        <button type="button" @click="deleteMonth" class="btn btn-danger">
                                            {{ $t('message.system_logs.delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="input-group input-group-sm float-right" style="width: 65px">
                                    <select @change="doSearchList"
                                            class="form-control"
                                            v-model="filterModel.per_page"
                                            style="height: 37px;">
                                        <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <span v-if="listLogs.total > 10" class="pagination-custom-footer-text m-0 mt-2">
                            <b>
                                {{ $t('message.others.table_entries', { from: listLogs.from, to: listLogs.to, end: listLogs.total }) }}
                            </b>
                        </span>

                        <div class="row">
                            <div class="col">
                                <table class="table table-hover table-bordered table-striped rlink-table">
                                    <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>{{ $t('message.system_logs.t_username') }}</th>
                                        <th>{{ $t('message.system_logs.t_table') }}</th>
                                        <th>{{ $t('message.system_logs.t_action') }}</th>
                                        <th>{{ $t('message.system_logs.t_page') }}</th>
                                        <th>{{ $t('message.system_logs.t_time') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in listLogs.data" :key="index">
                                        <td class="center-content">{{ index + 1 }}</td>
                                        <td>{{
                                                item.user.username
                                            }}
                                        </td>
                                        <td>
                                            {{ tableData(item.table) }}
                                        </td>
                                        <td>{{ tableAction(item.action) }}</td>
                                        <td>
                                            <a v-if="item.page !== null && isTablePage(item.page)" :href="item.page" target="_blank">
                                                {{ tablePage(item.page) }}
                                            </a>

                                            <span v-else>
                                                N/A
                                            </span>
                                        </td>
                                        <td>{{ item.created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="overlay" v-if="isLoadingTable">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <component :is="pagination" :callMethod="goToPage"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import _ from 'lodash';
import {Constants} from "../../../mixins/constants";
import {dateRange} from "../../../mixins/dateRange";

export default {
    name : 'LogsList',
    mixins: [Constants, dateRange],
    data() {
        return {
            filterModel : {
                user_id: this.$route.query.user_id || '',
                user_id_temp : Number.parseInt(this.$route.query.user_id_temp) || '',
                email : this.$route.query.email || '',
                email_temp : this.$route.query.email_temp || '',
                table : this.$route.query.table || '',
                table_temp : this.$route.query.table_temp || '',
                action : this.$route.query.action || '',
                action_temp : this.$route.query.action_temp || '',
                path : this.$route.query.path || '',
                path_temp : this.$route.query.path_temp || '',
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 10,
                date : {
                    startDate : null,
                    endDate : null,
                },
            },
            deleteModel : {
                month : ''
            },
            rolesList : [],
            selectedRole : '',
            listPageOptions : [
                10,
                25,
                50,
                100,
                200
            ],
            isLoadingTable : false,
            isPopupLoading : false,
            actionMeta : [],
            months : [],
            listUser: [],

            tablePages: Constants.LOG_PAGES,
            logTotals: {
                create: 0,
                delete: 0,
                update: 0,
                get_alexa: 0,
            },
            isTotalsLoading: true,
        };
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});

        if (!this.user.isAdmin) {
            window.location.href = '/';
            return;
        }

        this.initActionMeta();
        this.getTableList();
        this.getActionList();
        this.getLogsList({
            params : this.filterModel
        });
        this.getMonthFilter();
        this.getUsersList({
            params : {
                per_page : 'All'
            }
        });
        this.getRolesList();
        this.getAllUsers()
        await this.getLogsTotals();
    },

    watch : {
        selectedRole() {
            this.getAllUsers();
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listTable : state => state.storeLog.listTable,
            listAction : state => state.storeLog.listAction,
            messageForms : state => state.storeLog.messageForms,
            listLogs : state => state.storeLog.listLogs,
            counter : state => state.storeLog.counter
        }),

        listPages() {
            return Constants.LOG_PAGES;
        },

        pagination() {
            return {
                props : {
                    callMethod : ""
                },
                template : `
                    <div class="paging_simple_numbers">${this.listLogs.pagination}</div>`,
                methods : {
                    async goToPage(pageNum) {
                        this.callMethod(pageNum);
                    }
                }
            }
        },
    },

    methods : {
        clearFilter() {
            this.filterModel = {
                user_id: '',
                user_id_temp: '',
                email : '',
                email_temp : '',
                table : '',
                table_temp : '',
                action : '',
                action_temp : '',
                path : '',
                path_temp : '',
                page : 0,
                per_page : 10,
                date : {
                    startDate : null,
                    endDate : null,
                },
            }

            this.selectedRole = '';

            this.$router.replace({'query': null});

            this.getLogsList();
            this.getLogsTotals();
        },

        getAllUsers() {
            axios.get('/api/all-users', {
                params: {
                    role: this.selectedRole
                }
            })
            .then((res) => {
                this.listUser = res;
            })
        },

        tableData(itemTable) {
            return _.find(this.listTable, [
                'id',
                itemTable
            ]) ? _.find(this.listTable, [
                'id',
                itemTable
            ]).value : itemTable
        },

        tableAction(itemAction) {
            return _.find(this.listAction, [
                'id',
                itemAction
            ]) ? _.find(this.listAction, [
                'id',
                itemAction
            ]).value : itemAction
        },

        tablePage(itemPath) {
            return _.find(Constants.LOG_PAGES, [
                'path',
                itemPath
            ]) ? _.find(Constants.LOG_PAGES, [
                'path',
                itemPath
            ]).name : 'N/A'
        },

        isTablePage(itemPath) {
            return _.find(Constants.LOG_PAGES, [
                'path',
                itemPath
            ])
        },

        async initActionMeta() {
            this.actionMeta['create'] = {icon : 'plus', label : 'green'};
            this.actionMeta['update'] = {icon : 'pencil-alt', label : 'yellow'};
            this.actionMeta['delete'] = {icon : 'trash', label : 'red'};
            this.actionMeta['get_alexa'] = {icon : 'heart', label : 'aqua'};
        },

        async getTableList() {
            await this.$store.dispatch('actionGetTableListLog', {vue : this});
        },

        async getActionList() {
            await this.$store.dispatch('actionGetActionListLog', {vue : this});
        },

        async getLogsList(params) {
            let loader = this.$loading.show();
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetLogsList', {vue : this, params : params});
            this.isLoadingTable = false;
            loader.hide();
        },

        async getLogsTotals() {
            let that = this;
            that.isTotalsLoading = true;

            // change the format of date
            that.filterModel.date = that.formatFilterDates(this.filterModel.date)
            that.filterModel.user_id = that.filterModel.user_id_temp;
            that.filterModel.table = that.filterModel.table_temp;
            that.filterModel.email = that.filterModel.email_temp;
            that.filterModel.action = that.filterModel.action_temp;
            that.filterModel.path = that.filterModel.path_temp;

            axios.get('/api/logs/totals', {
                params: this.filterModel
            })
            .then((res) => {
                this.logTotals = res.data
                that.isTotalsLoading = false;
            })
            .catch((err) => {
                console.log(err)
                that.isTotalsLoading = false;
            })
        },

        async getUsersList(params) {
            await this.$store.dispatch('actionGetUser', {vue : this, params : params});
        },

        async goToPage(pageNum) {
            var that = this;
            this.filterModel.page = pageNum;
            this.$router.push({
                query : that.filterModel,
            });

            await this.getLogsList({
                params : that.filterModel
            });
        },

        async doSearchList() {
            let that = this;
            // change the format of date
            that.filterModel.date = that.formatFilterDates(this.filterModel.date)
            that.filterModel.user_id = that.filterModel.user_id_temp;
            that.filterModel.table = that.filterModel.table_temp;
            that.filterModel.email = that.filterModel.email_temp;
            that.filterModel.action = that.filterModel.action_temp;
            that.filterModel.path = that.filterModel.path_temp;

            this.goToPage(0);
            await this.getLogsTotals();
        },

        getMonthFilter() {
            axios.get('/api/logs/months')
                .then((response) => {
                    this.months = response.data;
                })
        },

        deleteMonth() {
            let self = this;
            swal.fire({
                icon : 'warning',
                title : self.$t('message.system_logs.alert_confirm'),
                text : self.$t('message.system_logs.alert_confirm_note'),
                showCancelButton : true,
                confirmButtonText : 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/logs/flush/' + this.deleteModel.month)
                        .then((response) => {
                            this.getLogsList();
                            this.getLogsTotals();

                            swal.fire({
                                icon : 'success',
                                title : self.$t('message.system_logs.alert_success'),
                                text : self.$t('message.system_logs.alert_deleted_successfully'),
                            });
                        })
                }
            })
        },

        getRolesList() {
            axios.get('/api/roles')
            .then((response) => {
                this.rolesList = response.data;
            });
        },
    }
}
</script>
