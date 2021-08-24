<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Reminder: </strong> Some statuses like OPENED, DELIVERED, REJECTED and REPORTED are only updated since July 2021.
                Mailgun only retains our email status for only 5 days.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-aqua">
                <div class="inner">
                    <h3>{{ listLogsTotals.total_mail }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Total mails">
                        </i>
                        Total Mail
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope-o"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-warning text-light">
                <div class="inner">
                    <h3>{{ listLogsTotals.sent }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Total emails sent from our system">
                        </i>
                        Total Mail Sent
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-paper-plane"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-olive">
                <div class="inner">
                    <h3>{{ listLogsTotals.sent_today }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Total emails sent from our system today">
                        </i>
                        Total Mail Today
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-red">
                <div class="inner">
                    <h3>{{ listLogsTotals.rejected }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Mailgun rejected the request to send/forward the email">
                        </i>
                        Total Mail Rejected
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-times-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-danger text-light">
                <div class="inner">
                    <h3>{{ listLogsTotals.failed }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Mailgun could not deliver the email to the recipient email server">
                        </i>
                        Total Mail Failed
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-warning"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-dark text-light">
                <div class="inner">
                    <h3>{{ listLogsTotals.reported }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="The email recipient clicked on the spam complaint button within their email client">
                        </i>
                        Total Mail Reported
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-success text-light">
                <div class="inner">
                    <h3>{{ listLogsTotals.delivered }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Mailgun sent the email and it was accepted by the recipient email server.">
                        </i>
                        Total Mail Delivered
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box rounded bg-navy">
                <div class="inner">
                    <h3>{{ listLogsTotals.opened }}</h3>
                    <p>
                        <i
                            class="fa fa-question-circle"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="The email recipient opened the email">
                        </i>
                        Total Mail Opened
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope-open"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="box">
                <div class="box-header" style="padding-bottom: 10px !important;">
                    <h3 class="box-title">Filter</h3>
                </div>
                <div class="box-body mx-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>User Email</label>
                                <select class="form-control" v-model="filterModel.user_email">
                                    <option value="">All</option>
                                    <option v-for="option in listUserEmail" v-bind:value="option.work_mail">
                                        {{ option.work_mail }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recipient (to)</label>
                                <input type="text" class="form-control" v-model="filterModel.recipient">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" v-model="filterModel.status">
                                    <option value="">All</option>
                                    <option value="0">Sent</option>
                                    <option value="500">Rejected</option>
                                    <option value="552">Failed</option>
                                    <option value="250">Delivered</option>
                                    <option value="260">Opened</option>
                                    <option value="570">Reported</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-show="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Mail Logs</h3>

                            <div class="input-group input-group-sm float-right" style="width: 65px">
                                <select
                                    v-model="filterModel.paginate"
                                    class="form-control pull-right"
                                    style="height: 37px;"

                                    @change="getMaillogs">

                                    <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                </select>
                            </div>

                            <button class="btn btn-primary float-right" @click="getStatus()">Update Status</button>
                        </div>

                        <div class="box-body no-padding relative">
                            <span v-if="Listlogs.total > 0" class="pagination-custom-footer-text my-0 mx-2">
                                <b>Showing {{ Listlogs.from }} to {{ Listlogs.to }} of {{ Listlogs.total }} entries.</b>
                            </span>

                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>User Email</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Bcc</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(log, index) in Listlogs.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ log.user_mail }}</td>
                                        <td>{{ log.from }}</td>
                                        <td v-html="checkEmailTo(log.to)"></td>
                                        <td></td>
                                        <td>
                                            <span class="label" :class="statusClass(log.status)">
                                                {{ statusLabel(log.status) }}
                                            </span>
                                        </td>
                                        <td>{{ log.date }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <pagination
                                :limit="8"
                                :data="Listlogs"
                                @pagination-change-page="getMaillogs">

                            </pagination>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {

        data() {
            return {
                Listlogs: {
                    data: []
                },

                listLogsTotals: {
                    total_mail: 0,
                    sent: 0,
                    sent_today: 0,
                    failed: 0,
                    delivered: 0,
                    rejected: 0,
                    reported: 0,
                    opened: 0,
                },

                filterModel: {
                    page: this.$route.query.page || 0,
                    paginate: this.$route.query.paginate || 50,
                    user_email: this.$route.query.user_email || '',
                    status: this.$route.query.status || '',
                    recipient: '',
                },
                listUserEmail: [],

                listPageOptions: [5, 10, 25, 50, 100],
            };
        },

        async created() {
            //
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
            }),
        },

        mounted() {
            // this.getMaillogs();
            this.getListUserEmails();
            this.getMaillogs();
            this.getMailLogsTotals()
        },

        methods: {
            checkEmailTo(email) {
                let display = '';
                if(email.indexOf('|') > -1) {
                    var emails = '';
                    var email = email.split('|');
                    for(var i = 0; i < email.length; i++) {
                        var num = i + 1;
                        emails += num + '.)' + email[i] + '<br>';
                    }
                    display = emails;
                } else {
                    display = email;
                }
                return display;
            },

            getListUserEmails() {
                axios.get('/api/mail/user-email-list')
                    .then((res) => {
                        this.listUserEmail = res.data;
                    })
            },

            statusClass(status) {
                return {
                    'label-warning': status === 0,
                    'bg-red': status === 500,
                    'bg-dark': status === 570,
                    'label-danger': status === 552,
                    'label-success': status === 250,
                    'bg-navy': status === 260
                }
            },

            statusLabel(code) {
                let label = '';

                if (code === 0) {
                    label = 'Sent'
                } else if(code === 250){
                    label = 'Delivered'
                } else if(code === 500){
                    label = 'Rejected'
                } else if(code === 552){
                    label = 'Failed'
                } else if(code === 260){
                    label = 'Opened'
                } else {
                    label = 'Reported'
                }

                return label;
            },

            clearSearch() {
                // $('#tbl_maillog').DataTable().destroy();

                this.filterModel = {
                    page: 1,
                    status: '',
                    paginate: 50,
                    user_email: '',
                    recipient: '',
                }

                this.$router.replace({'query': null});

                this.getMaillogs();
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getMaillogs();
            },

            getMaillogs(page = 1) {
                let loader = this.$loading.show();
                this.filterModel.page = page
                axios.get('/api/mail/mail-logs',{
                    params: this.filterModel
                })
                .then((res) => {
                    this.Listlogs = res.data
                    loader.hide();
                })
                .catch((err) => {
                    console.log(err)
                    loader.hide();
                })
            },

            getMailLogsTotals() {
                axios.get('/api/mail/mail-logs-totals')
                .then((res) => {
                    this.listLogsTotals = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            getStatus() {
                let loader = this.$loading.show();
                axios.get('/api/mail/status')
                    .then((res) => {
                        console.log("success check all message status");
                        this.getMaillogs()
                        this.getMailLogsTotals()
                        loader.hide();

                        swal.fire(
                            'Updated!',
                            'Mail logs status successfully updated.',
                            'success'
                        )
                    })
                    .catch((err) => {
                        console.log("Update status failed");
                        this.getMaillogs()
                        this.getMailLogsTotals()
                        loader.hide();
                    })
            },
        },
    }
</script>
