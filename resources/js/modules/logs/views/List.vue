<template>
    <div class="row">
        <div  v-for="(count, key) in counter" class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div :class="'small-box bg-' + (actionMeta[key] ? actionMeta[key].label : 'green')">
                <div class="inner">
                    <h3>{{ count }}</h3>
                    <p style="text-transform: capitalize">Total {{ listAction[key] ? listAction[key] : key }}</p>
                </div>
                <div class="icon">
                    <i :class="'fa fa-' + (actionMeta[key] ? actionMeta[key].icon : 'circle')"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">System Logs</h3>
                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                        <tr class="label-primary">
                            <th>#</th>
                            <th>User Email</th>
                            <th>Table</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                        <td style="max-width: 30px;">
                            Filter
                        </td>

                        <td>
                            <div class="input-group input-group-sm">
                                <input type="text" v-model="filterModel.email_temp"  class="form-control pull-right" placeholder="Search Email">
                            </div>
                        </td>

                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <select v-model="filterModel.table_temp" class="form-control pull-right">
                                        <option value="">-- All Tables --</option>
                                        <option v-for="(option, key) in listTable" v-bind:value="key">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <select v-model="filterModel.action_temp" class="form-control pull-right">
                                        <option value="">-- All Actions --</option>
                                        <option v-for="(option, key) in listAction" v-bind:value="key">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                            </div>
                        </td>
                        <tr>

                        </tr>
                        <tr v-for="(item, index) in listLogs.data" :key="index">
                            <td class="center-content">{{ index + 1 }}</td>
                            <td>{{ item.user.email }}</td>
                            <td>{{ listTable[item.table] ? listTable[item.table] : ''}}</td>
                            <td>{{ listAction[item.action] ? listAction[item.action] : '' }}</td>
                            <td>{{ item.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <component :is="pagination" :callMethod="goToPage"></component>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'LogsList',
        data() {
            return {
                filterModel: {
                    email: this.$route.query.email || '',
                    email_temp: this.$route.query.email_temp || '',
                    table: this.$route.query.table || '',
                    table_temp: this.$route.query.table_temp || '',
                    action: this.$route.query.action || '',
                    action_temp: this.$route.query.action_temp || '',
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 5,
                },

                listPageOptions: [5, 10, 25, 50, 100],
                isLoadingTable: false,
                isPopupLoading: false,
                actionMeta: []
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });

            if (!this.user.isAdmin) {
                window.location.href = '/';
                return;
            }

            this.initActionMeta();
            this.getTableList();
            this.getActionList();
            this.getLogsList();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                listTable: state => state.storeLog.listTable,
                listAction: state => state.storeLog.listAction,
                messageForms: state => state.storeLog.messageForms,
                listLogs: state => state.storeLog.listLogs,
                counter: state => state.storeLog.counter,
            }),

            pagination() {
                return {
                    props: {
                        callMethod: ""
                    },
                    template: `<div class="paging_simple_numbers">${this.listLogs.pagination}</div>`,
                    methods: {
                        async goToPage(pageNum) {
                            this.callMethod(pageNum);
                        }
                    }
                }
            },
        },

        methods: {
            async initActionMeta() {
                this.actionMeta['create'] = { icon: 'plus', label: 'green' };
                this.actionMeta['update'] = { icon: 'pencil', label: 'yellow' };
                this.actionMeta['delete'] = { icon: 'trash', label: 'red' };
                this.actionMeta['get_alexa'] = { icon: 'heart', label: 'aqua' };
            },

            async getTableList() {
                await this.$store.dispatch('actionGetTableListLog', { vue: this });
            },

            async getActionList() {
                await this.$store.dispatch('actionGetActionListLog', { vue: this });
            },

            async getLogsList(params) {
                this.isLoadingTable = true;
                await this.$store.dispatch('actionGetLogsList', { vue: this, params: params });
                this.isLoadingTable = false;
            },

            async goToPage(pageNum) {
                var that = this;
                this.filterModel.page = pageNum;
                this.$router.push({
                    query: that.filterModel,
                });

                await this.getLogsList({
                    params: that.filterModel
                });
            },

            async doSearchList() {
                let that = this;
                that.filterModel.table = that.filterModel.table_temp;
                that.filterModel.email = that.filterModel.email_temp;
                that.filterModel.action = that.filterModel.action_temp;
                this.goToPage(0);
            },
        }
    }
</script>
