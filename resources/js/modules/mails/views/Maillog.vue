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
                            <table class="table table-hover table-bordered table-striped rlink-table">
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
                                        <td>{{ log.to }}</td>
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
        },

        methods: {
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

            getMaillogs() {
                axios.get('/api/mail/mail-logs')
                    .then((res) => {
                        this.Listlogs = res.data
                        console.log(res.data)
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
