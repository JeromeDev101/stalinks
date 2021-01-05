<template>
    <div class="row">

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ Listlogs.total_mail }}</h3>
                    <p>Total Mail</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope-o"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ Listlogs.sent }}</h3>
                    <p>Total Mail Sent</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ Listlogs.failed }}</h3>
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
                    <h3 class="box-title">Filter</h3>
                </div>
                <div class="box-body m-3">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">User Email</label>
                                <select class="form-control" v-model="filterModel.user_email">
                                    <option value="">All</option>
                                    <option v-for="option in listUserEmail" v-bind:value="option.work_mail">
                                        {{ option.work_mail }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" v-model="filterModel.status">
                                    <option value="">All</option>
                                    <option value="250">Success</option>
                                    <option value="0">Waiting</option>
                                    <option value="552">Failed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">
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
                                <select class="form-control pull-right" style="height: 37px;">
                                </select>
                            </div>
                        </div>

                        <div class="box-body no-padding relative">
                            <table id="tbl_maillog" class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>User Email</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(log, index) in Listlogs.logs" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ log.user_mail }}</td>
                                        <td>{{ log.from }}</td>
                                        <td v-html="checkEmailTo(log.to)"></td>
                                        <td v-html="statusLabel(log.status)"></td>
                                        <td>{{ log.date }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                Listlogs: {},
                filterModel: {
                    user_email: this.$route.query.user_email || '',
                    status: this.$route.query.status || '',
                },
                listUserEmail: [],
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
            this.getMaillogs();
            this.getStatus();
            this.getListUserEmails();
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

            statusLabel(code) {
                let label = '<span class="label label-warning">Waiting</span>';
                if (code == 250) {
                    label = '<span class="label label-success">Success</span>';
                }

                if (code == 552) {
                    label = '<span class="label label-danger">Failed</span>';
                }

                return label;
            },

            clearSearch() {
                $('#tbl_maillog').DataTable().destroy();

                this.filterModel = {
                    user_email: '',
                    status: '',
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

            getMaillogs() {
                axios.get('/api/mail/mail-logs',{
                    params: {
                        user_email: this.filterModel.user_email,
                        status: this.filterModel.status,
                    }
                })
                .then((res) => {
                    this.Listlogs = res.data
                    $('#tbl_maillog').DataTable().destroy();
                })
                .then((res) => {
                    $('#tbl_maillog').DataTable({
                        paging: false,
                        searching: false,
                    });
                })

            },
            getStatus() {
                axios.get('/api/mail/status')
                    .then((res) => {
                        console.log("success check all message status")
                    })
            },
        },
    }
</script>
