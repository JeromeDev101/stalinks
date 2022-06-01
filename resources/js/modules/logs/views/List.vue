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
            <div v-for="(count, key) in counter" class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div :class="'small-box bg-' + (actionMeta[key] ? actionMeta[key].label : 'green')">
                    <div class="inner">
                        <h3>{{ count }}</h3>
                        <p style="text-transform: capitalize">{{ $t('message.system_logs.total_text') }} {{ listAction[key] ? listAction[key] : key }}</p>
                    </div>
                    <div class="icon">
                        <i :class="'fas fa-' + (actionMeta[key] ? actionMeta[key].icon : 'circle')"></i>
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
                        <div class="row mb-4">
                            <div class="col">
                                <v-select class="col-sm-12"
                                          v-model="selectedRole"
                                          :options="rolesList"
                                          :reduce="roleTemp => roleTemp.id"
                                          label="name"
                                          :placeholder="$t('message.system_logs.sl_roles')"/>
                            </div>

                            <div class="col">
                                <v-select class="col-sm-12"
                                          v-model="filterModel.email_temp"
                                          :options="listUser.data"
                                          :reduce="logUser => logUser.email"
                                          label="username"
                                          :placeholder="$t('message.system_logs.sl_users')"/>
                            </div>

                            <div class="col">
                                <v-select class="col-sm-12"
                                          v-model="filterModel.table_temp"
                                          :options="listTable"
                                          :reduce="tableTemp => tableTemp.id"
                                          label="value"
                                          :placeholder="$t('message.system_logs.sl_tables')"/>
                            </div>

                            <div class="col">
                                <v-select class="col-sm-12"
                                          v-model="filterModel.action_temp"
                                          :options="listAction"
                                          :reduce="actionTemp => actionTemp.id"
                                          label="value"
                                          :placeholder="$t('message.system_logs.sl_actions')"/>
                            </div>

                            <div class="col">
                                <v-select class="col-sm-12"
                                          v-model="filterModel.path_temp"
                                          :options="listPages"
                                          :reduce="pathTemp => pathTemp.path"
                                          label="name"
                                          :placeholder="$t('message.system_logs.sl_pages')"/>
                            </div>

                            <div class="col">
                                <div class="row">
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
                        </div>

                        <div class="row mb-4">
                            <div class="col-1">
                                <label class="float-right">{{ $t('message.system_logs.sl_delete') }}</label>
                            </div>

                            <div class="col-2">
                                <select class="form-control" v-model="deleteModel.month">
                                    <option value="">{{ $t('message.system_logs.sl_select_month') }}</option>
                                    <option v-for="value in months" :value="value.month">{{ value.month_name }}</option>
                                </select>
                            </div>

                            <div class="col-4">
                                <button type="button" @click="deleteMonth" class="btn btn-danger col col-2">
                                    {{ $t('message.system_logs.delete') }}
                                </button>
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

export default {
    name : 'LogsList',
    mixins: [Constants],
    data() {
        return {
            filterModel : {
                email : this.$route.query.email || '',
                email_temp : this.$route.query.email_temp || '',
                table : this.$route.query.table || '',
                table_temp : this.$route.query.table_temp || '',
                action : this.$route.query.action || '',
                action_temp : this.$route.query.action_temp || '',
                path : this.$route.query.path || '',
                path_temp : this.$route.query.path_temp || '',
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 5
            },
            deleteModel : {
                month : ''
            },
            rolesList : [],
            selectedRole : '',
            listPageOptions : [
                5,
                10,
                25,
                50,
                100
            ],
            isLoadingTable : false,
            isPopupLoading : false,
            actionMeta : [],
            months : [],
            listUser: [],

            tablePages: Constants.LOG_PAGES
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
        this.getLogsList();
        this.getMonthFilter();
        this.getUsersList({
            params : {
                per_page : 'All'
            }
        });
        this.getRolesList();
        this.getAllUsers()
    },

    watch : {
        selectedRole() {
            this.getUsersList({
                params : {
                    per_page : 'All',
                    role : this.selectedRole
                }
            });
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
                email : '',
                email_temp : '',
                table : '',
                table_temp : '',
                action : '',
                action_temp : '',
                path : '',
                path_temp : '',
                page : 0,
                per_page : 5
            }

            this.getLogsList();
        },

        getAllUsers() {
            axios.get('/api/admin/all-users')
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
            that.filterModel.table = that.filterModel.table_temp;
            that.filterModel.email = that.filterModel.email_temp;
            that.filterModel.action = that.filterModel.action_temp;
            that.filterModel.path = that.filterModel.path_temp;
            this.goToPage(0);
        },

        getMonthFilter() {
            axios.get('/api/admin/logs/months')
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
                    axios.delete('/api/admin/logs/flush/' + this.deleteModel.month)
                        .then((response) => {
                            this.getLogsList();

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
