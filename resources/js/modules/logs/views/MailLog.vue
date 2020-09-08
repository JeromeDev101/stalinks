<template>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ listLogs.total }}</h3>
                    <p>Total Mail</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope-o"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ counter.success }}</h3>
                    <p>Total Mail Sent</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ listLogs.total - counter.success }}</h3>
                    <p>Total Mail Failed</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Mail Logs</h3>
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
                            <th>From</th>
                            <th>To</th>
                            <th>Ext Domain</th>
                            <th>Status</th>
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
                                    <input type="text" v-model="filterModel.from_temp"  class="form-control pull-right" placeholder="Search Email">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" v-model="filterModel.to_temp"  class="form-control pull-right" placeholder="Search Email">
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <input type="text" v-model="filterModel.ext_temp"  class="form-control pull-right" placeholder="Search Domain">
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <select v-model="filterModel.status_temp" class="form-control pull-right">
                                        <option value="">-- All Status --</option>
                                        <option v-for="(option, key) in listStatusFilter" v-bind:value="key">
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
                        <tr v-for="(item, index) in listLogs.data" :key="index" title="Click to view content email" v-on:click="showContentMail(item.id)"
                            style="cursor: pointer" data-toggle="modal" data-target="#modal-detail" >
                            <td class="center-content">{{ index + 1 }}</td>
                            <td>{{ item.user.email }}</td>
                            <td>{{ item.from }}</td>
                            <td>{{ item.to }}</td>
                            <td><a :href="'http://' + item.ext_domain">{{ item.ext_domain }}</a></td>
                            <td><span :class="['label', 'label-' + (listStatus[item.status] ? listStatus[item.status].label : 'default')]">{{ listStatus[item.status] ? listStatus[item.status].text : 'undefined' }}</span></td>
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

        <!--    Modal Detail-->
        <div class="modal fade" id="modal-detail" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Mail Log Detail</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-12">

                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Users</b> <span class="pull-right">{{ mailLogDetail.user.email }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>From</b> <span class="pull-right">{{ mailLogDetail.from }}</span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>To</b> <span class="pull-right">{{ mailLogDetail.to }}</span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>Ext Domain</b> <span class="pull-right"><a :href="'http://' + mailLogDetail.ext_domain">{{ mailLogDetail.ext_domain }}</a></span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>Status</b><span class="pull-right"><span :class="['label', 'label-' + (listStatus[mailLogDetail.status] ? listStatus[mailLogDetail.status].label : 'default')]">{{ listStatus[mailLogDetail.status] ? listStatus[mailLogDetail.status].text : 'undefined' }}</span></span>
                                    </li>

                                    <li class="list-group-item">
                                        <b>Time</b> <span class="pull-right">{{ mailLogDetail.created_at }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                    <label style="color: #333">Title</label>
                                    <input type="text" v-model="mailLogDetail.title" class="form-control" value="" required="required" :readonly="true">
                                    <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                    <label style="color: #333">Content</label>
                                    <textarea rows="10" type="text" v-model="mailLogDetail.content" class="form-control" value="" :readonly="true" required="required" placeholder="Enter Content"></textarea>
                                    <span v-if="messageForms.errors.content" v-for="err in messageForms.errors.content" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
                    from: this.$route.query.from || '',
                    from_temp: this.$route.query.from_temp || '',
                    to: this.$route.query.to || '',
                    to_temp: this.$route.query.to_temp || '',
                    ext: this.$route.query.ext || '',
                    ext_temp: this.$route.query.to_temp || '',
                    status: this.$route.query.status || '',
                    status_temp: this.$route.query.status_temp || '',
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 5,
                },

                listPageOptions: [5, 10, 25, 50, 100],
                isLoadingTable: false,
                isPopupLoading: false,
                listStatusFilter: [],
                mailLogDetail: {
                    title: '',
                    content: '',
                    from: '',
                    to: '',
                    user: {
                        id: 0,
                        email: ''
                    }
                }
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            this.initStatusMeta();
            this.getLogsList();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                messageForms: state => state.storeLog.messageForms,
                listLogs: state => state.storeLog.listLogsMail,
                counter: state => state.storeLog.counterMail,
                listStatus: state => state.storeLog.listStatusMail,
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
            async initStatusMeta() {
                 this.listStatusFilter.push("Failed");
                 this.listStatusFilter.push("Success");
                 this.listStatusFilter.push("Sending");
            },

            async getLogsList(params) {
                if(!params) {
                    params = this.filterModel
                }
                this.isLoadingTable = true;
                await this.$store.dispatch('actionGetLogsListMail', { vue: this, params: params });
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
                that.filterModel.to = that.filterModel.to_temp;
                that.filterModel.email = that.filterModel.email_temp;
                that.filterModel.from = that.filterModel.from_temp;
                that.filterModel.status = that.filterModel.status_temp;
                that.filterModel.ext = that.filterModel.ext_temp;
                this.goToPage(0);
            },

            showContentMail(mailLogId) {
                var mailLog = this.listLogs.data.filter(item => item.id === mailLogId)[0];
                this.mailLogDetail = JSON.parse(JSON.stringify(mailLog));
            }
        }
    }
</script>
