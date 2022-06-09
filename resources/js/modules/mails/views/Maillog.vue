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
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ $t('message.mail_logs.reminder') }}
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
                                :title="$t('message.mail_logs.tn_total')">
                            </i>
                            {{ $t('message.mail_logs.t_total') }}
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box rounded bg-warning text-light">
                    <div class="inner">
                        <h3>
                            {{ listLogsTotals.sent }}
                        </h3>

                        <p>
                            <i
                                class="fa fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                :title="$t('message.mail_logs.tn_total_mail_sent')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_sent') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.sent) }}%)
                            </span>
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
                                :title="$t('message.mail_logs.tn_total_mail_today')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_today') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.sent_today) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
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
                                :title="$t('message.mail_logs.tn_total_mail_rejected')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_rejected') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.rejected) }}%)
                            </span>
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
                                :title="$t('message.mail_logs.tn_total_mail_failed')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_failed') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.failed) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
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
                                :title="$t('message.mail_logs.tn_total_mail_reported')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_reported') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.reported) }}%)
                            </span>
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
                                :title="$t('message.mail_logs.tn_total_mail_delivered')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_delivered') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.delivered) }}%)
                            </span>
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
                                :title="$t('message.mail_logs.tn_total_mail_opened')">
                            </i>
                            {{ $t('message.mail_logs.t_total_mail_opened') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.opened) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-envelope-open"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box rounded bg-gradient-cyan">
                    <div class="inner">
                        <h3>{{ listLogsTotals.inbox }}</h3>
                        <p>
                            <i
                                class="fa fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                :title="$t('message.mail_logs.tn_total_inbox')">
                            </i>
                            {{ $t('message.mail_logs.t_total_inbox') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.inbox) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box rounded bg-gradient-orange">
                    <div class="inner">
                        <h3>{{ listLogsTotals.drafts }}</h3>
                        <p>
                            <i
                                class="fa fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                :title="$t('message.mail_logs.tn_total_drafts')">
                            </i>
                            {{ $t('message.mail_logs.t_total_drafts') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.drafts) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fab fa-firstdraft"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box rounded bg-gradient-purple">
                    <div class="inner">
                        <h3>{{ listLogsTotals.prospect }}</h3>
                        <p>
                            <i
                                class="fa fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                :title="$t('message.mail_logs.tn_total_prospect')">
                            </i>
                            {{ $t('message.mail_logs.t_total_prospect') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.prospect) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box rounded bg-gradient-teal">
                    <div class="inner">
                        <h3>{{ listLogsTotals.registration }}</h3>
                        <p>
                            <i
                                class="fa fa-question-circle"
                                data-toggle="tooltip"
                                data-placement="top"
                                :title="$t('message.mail_logs.tn_total_registration')">
                            </i>
                            {{ $t('message.mail_logs.t_total_registration') }}

                            <span>
                                ({{ calculatePercentage(listLogsTotals.registration) }}%)
                            </span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.mail_logs.filter_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_sender') }}</label>
                                    <select class="form-control" v-model="filterModel.user_email">
                                        <option value="">{{ $t('message.mail_logs.all') }}</option>
                                        <option v-for="option in listUserEmail" v-bind:value="option.work_mail">
                                            {{ option.work_mail }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_recipient') }}</label>
                                    <input type="text" class="form-control" v-model="filterModel.recipient">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_subject') }}</label>
                                    <input type="text" class="form-control" v-model="filterModel.subject">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_status') }}</label>
                                    <select class="form-control" v-model="filterModel.status">
                                        <option value="">{{ $t('message.mail_logs.all') }}</option>
                                        <option value="0">{{ $t('message.mail_logs.filter_sent') }}</option>
                                        <option value="500">{{ $t('message.mail_logs.filter_rejected') }}</option>
                                        <option value="552">{{ $t('message.mail_logs.filter_failed') }}</option>
                                        <option value="250">{{ $t('message.mail_logs.filter_delivered') }}</option>
                                        <option value="260">{{ $t('message.mail_logs.filter_opened') }}</option>
                                        <option value="570">{{ $t('message.mail_logs.filter_reported') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_date') }}</label>
                                    <!--                                    <input type="date" class="form-control" v-model="filterModel.date">-->

                                    <date-range-picker
                                        v-model="filterModel.date"
                                        ref="picker"
                                        opens="right"
                                        style="width: 100%"
                                        :linkedCalendars="true"
                                        :dateRange="filterModel.date"
                                        :ranges="generateDefaultDateRange()"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.mail_logs.filter_from') }}</label>
                                    <select class="form-control" v-model="filterModel.from_page">
                                        <option value="">{{ $t('message.mail_logs.all') }}</option>
                                        <option value="none">{{ $t('message.mail_logs.filter_na') }}</option>
                                        <option value="inbox">{{ $t('message.mail_logs.filter_inbox') }}</option>
                                        <option value="drafts">{{ $t('message.mail_logs.filter_drafts') }}</option>
                                        <option value="prospect">{{ $t('message.mail_logs.filter_url_prospect') }}</option>
                                        <option value="registration">{{ $t('message.mail_logs.filter_registration_accounts') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearSearch">
                                    {{ $t('message.mail_logs.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch">
                                    {{ $t('message.mail_logs.search') }}
                                    <i v-show="false" class="fa fa-refresh fa-spin" ></i>
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
                        <h3 class="card-title text-primary">{{ $t('message.mail_logs.ml_title') }}</h3>
                        <div class="card-tools">
<!--                            <button class="btn btn-primary" @click="getStatus">-->
<!--                                <i class="fas fa-sync"></i>-->
<!--                                Update Status-->
<!--                            </button>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <span v-if="Listlogs.total > 0" class="pagination-custom-footer-text my-0 mx-2" style="margin-left: 0 !important;">
                            <b>
                                {{ $t('message.others.table_entries', { from: Listlogs.from, to: Listlogs.to, end: Listlogs.total }) }}
                            </b>
                        </span>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.mail_logs.ml_action') }}</th>
                                    <th>{{ $t('message.mail_logs.filter_sender') }}</th>
                                    <th>{{ $t('message.mail_logs.filter_subject') }}</th>
                                    <th>{{ $t('message.mail_logs.ml_to') }}</th>
                                    <th>{{ $t('message.mail_logs.ml_bcc') }}</th>
                                    <th>{{ $t('message.mail_logs.filter_status') }}</th>
                                    <th>{{ $t('message.mail_logs.filter_from') }}</th>
                                    <th>{{ $t('message.mail_logs.filter_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(log, index) in Listlogs.data" :key="index">
                                    <td>{{ log.id }}</td>
                                    <td>
                                        <button
                                            class="btn btn-default"
                                            title="Check Email Content"

                                            @click="viewMailLogContent(log)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>{{ log.user_mail }}</td>
                                    <td>{{ log.subject }}</td>
                                    <td v-html="checkEmailTo(log.to)"></td>
                                    <td></td>
                                    <td>
                                        <span class="badge" :class="statusClass(log.status)">{{ statusLabel(log.status) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge" :class="statusClassFrom(log.from)">
                                            {{ log.from ? log.from : 'N/A' }}
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

        <!-- Modal Email Content Start -->
        <div class="modal fade" id="modal-email-content" ref="modalEmailContent" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $t('message.mail_logs.ec_title') }}
                        </h4>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="badge" :class="statusClass(emailContent.status)">
                                    {{ statusLabel(emailContent.status) }}
                                </span>
                                <label class="font-weight-bold">{{ emailContent.subject }}</label>
                            </div>
                        </div>

                        <div v-if="viewAttachmentChecker(emailContent)" class="row">

                            <template v-if="!Array.isArray(emailContent.attachment)">
                                <div v-if="!Array.isArray(emailContent.attachment)" class="col-md-4">
                                    <div class="card">
                                        <div class="card-body" style="white-space: nowrap !important; overflow: hidden; text-overflow: ellipsis">
                                            <a
                                                target="_blank"
                                                class="mailbox-attachment-name"
                                                :download="emailContent.attachment.display_name"
                                                :href="emailContent.attachment.url">

                                                <i class="fa fa-paperclip"></i>
                                                {{ emailContent.attachment.display_name }}
                                            </a>

                                            <span class="mailbox-attachment-size">
                                                {{ bytesToSize(emailContent.attachment.size) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template v-if="Array.isArray(emailContent.attachment)">
                                <div v-for="att in emailContent.attachment" class="col-4">
                                    <div class="card">
                                        <div class="card-body" style="white-space: nowrap !important; overflow: hidden; text-overflow: ellipsis">
                                            <a
                                                target="_blank"
                                                class="mailbox-attachment-name"
                                                :title="att.display_name"
                                                :download="att.display_name"
                                                :href="att.url">

                                                <i class="fa fa-paperclip"></i>
                                                {{ att.display_name }}
                                            </a>

                                            <span class="mailbox-attachment-size">
                                                {{ bytesToSize(att.size) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <iframe
                                            v-if="emailContent.content"
                                            width="100%"
                                            ref="iframeMailLog"
                                            frameborder="0">

                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-default"
                            data-dismiss="modal"

                            @click="modalCloser()">
                            {{ $t('message.mail_logs.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Email Content End -->
    </div>

</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import {dateRange} from "../../../mixins/dateRange";

export default {
    mixins: [dateRange],
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
                    inbox: 0,
                    drafts: 0,
                    prospect: 0,
                    registration: 0,
                },

                filterModel: {
                    page: this.$route.query.page || 0,
                    paginate: this.$route.query.paginate || 50,
                    user_email: this.$route.query.user_email || '',
                    status: this.$route.query.status || '',
                    subject: this.$route.query.subject || '',
                    recipient: '',
                    date: {
                        startDate: '',
                        endDate: ''
                    },
                    from_page: this.$route.query.from_page || ''
                },
                listUserEmail: [],

                listPageOptions: [5, 10, 25, 50, 100],

                emailContent: {
                    status: '',
                    subject: '',
                    content: '',
                    attachment : {
                        url : '',
                        size : '',
                        name : '',
                    },
                }
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

            $(this.$refs.modalEmailContent).on("shown.bs.modal", this.calculateIframeHeight)
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

            viewMailLogContent (log) {
                let element = this.$refs.modalEmailContent
                $(element).modal('show')

                this.emailContent.subject = log.subject;
                this.emailContent.status = log.status

                // email content
                let body = JSON.parse(log.body);
                let body_html = JSON.parse(log.body_html);
                let stripped_html = JSON.parse(log.stripped_html);

                let content = body_html
                    ? body_html['body-html']
                    : stripped_html
                        ? stripped_html['stripped-html']
                        : body['body-plain']

                content = '<pre style="white-space: pre-line;"> <base target="_blank">' + content + '</pre>'

                this.emailContent.content = content;

                // email attachments
                if (log.attachment !== '' && log.attachment !== '[]') {
                    this.emailContent.attachment = JSON.parse(log.attachment);
                }
            },

            iFrameLoader (iframeBody) {
                const iFrameEl = this.$refs.iframeMailLog
                const doc = iFrameEl.contentWindow.document

                doc.open()
                doc.write(iframeBody)
                doc.close()

                iFrameEl.onload = () => {
                    iFrameEl.style.height = "0"
                    iFrameEl.style.height = (doc.body.scrollHeight + 25) + 'px';
                }
            },

            calculateIframeHeight () {
                // load iframe
                this.iFrameLoader(this.emailContent.content)
            },

            modalCloser () {
                this.emailContent = {
                    status: '',
                    subject: '',
                    content: '',
                    attachment : {
                        url : '',
                        size : '',
                        name : '',
                    },
                }
            },

            viewAttachmentChecker (email) {
                return email.attachment !== null && (email.attachment.url !== '' && email.attachment.url !== '[]');
            },

            bytesToSize(bytes) {
                let sizes = [
                    'Bytes',
                    'KB',
                    'MB',
                    'GB',
                    'TB'
                ];
                if (bytes == 0) return '0 Byte';
                let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            },

            statusClass(status) {
                return {
                    'bg-warning': status === 0,
                    'bg-maroon': status === 500,
                    'bg-gray-dark': status === 570,
                    'bg-danger': status === 552,
                    'bg-success': status === 250,
                    'bg-navy': status === 260
                }
            },

            statusClassFrom(from) {
                return {
                    'bg-gradient-cyan': from === 'inbox',
                    'bg-gradient-orange': from === 'drafts',
                    'bg-gradient-purple': from === 'prospect',
                    'bg-gradient-teal': from === 'registration',
                    'bg-light': from === null,
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
                    subject: '',
                    paginate: 50,
                    user_email: '',
                    recipient: '',
                    date: {
                        startDate: null,
                        endDate: null
                    },
                    from_page: '',
                }

                this.$router.replace({'query': null});

                this.getMaillogs();
                this.getMailLogsTotals();
            },

            doSearch() {
                // change the format of date
                this.filterModel.date = this.formatFilterDates(this.filterModel.date)

                this.$router.push({
                    query: this.filterModel,
                });

                this.getMaillogs();
                this.getMailLogsTotals();
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
                axios.get('/api/mail/mail-logs-totals', {
                    params: this.filterModel
                })
                .then((res) => {
                    this.listLogsTotals = res.data
                })
                .catch((err) => {
                    console.log(err)
                })
            },

            getStatus() {
                let self = this;
                let loader = this.$loading.show();
                axios.get('/api/mail/status')
                    .then((res) => {
                        console.log("success check all message status");
                        this.getMaillogs()
                        this.getMailLogsTotals()
                        loader.hide();

                        swal.fire(
                            self.$t('message.mail_logs.alert_updated'),
                            self.$t('message.mail_logs.alert_updated_successfully'),
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

            calculatePercentage (number) {
                if (this.listLogsTotals.total_mail === 0) {
                    return 0;
                } else {
                    return ((number / this.listLogsTotals.total_mail) * 100).toFixed(2);
                }
            }
        },
    }
</script>
