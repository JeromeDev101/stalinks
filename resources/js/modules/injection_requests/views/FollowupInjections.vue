<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.generate_list.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button
                                type="button"
                                class="btn btn-primary ml-5"
                                aria-expanded="false"
                                data-toggle="collapse"
                                data-target="#collapseExample"
                                aria-controls="collapseExample"
                            >
                                <i class="fa fa-plus"></i> {{ $t('message.generate_list.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL Publisher</label>
                                    <input
                                        v-model="filterModel.url_publisher"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.generate_list.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL Article</label>
                                    <input
                                        v-model="filterModel.url_article"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.generate_list.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>URL Advertiser</label>
                                    <input
                                        v-model="filterModel.url_advertiser"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.generate_list.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Link To</label>
                                    <input
                                        v-model="filterModel.link_to"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.generate_list.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Anchor Text</label>
                                    <input
                                        v-model="filterModel.anchor_text"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.generate_list.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>

                                    <v-select
                                        v-model="filterModel.status"
                                        multiple
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Status"
                                        :searchable="true"
                                        :options="linkInjectionStatus"
                                    />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date Requested</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            v-model="filterModel.date_requested"
                                            ref="picker"
                                            opens="left"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :ranges="generateDefaultDateRange()"
                                            :dateRange="filterModel.date_requested"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date Processed</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            v-model="filterModel.date_process"
                                            ref="picker"
                                            opens="left"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :ranges="generateDefaultDateRange()"
                                            :dateRange="filterModel.date_process"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date Completed</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            v-model="filterModel.live_date"
                                            ref="picker"
                                            opens="right"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :ranges="generateDefaultDateRange()"
                                            :dateRange="filterModel.live_date"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-3">
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
                            </div> -->
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearFilter()">
                                    {{ $t('message.generate_list.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch()">
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
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h3 class="card-title text-primary">
                                    Follow up Injection Requests
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <small class="font-weight-bold mr-2">LEGEND: </small>
                                        <span class="badge badge-pill badge-warning">Date Requested</span>
                                        <span class="badge badge-pill badge-info">Date Processed</span>
                                        <span class="badge badge-pill badge-primary">Date Completed</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="linkInjections.total" class="pagination-custom-footer-text m-0 mt-2 font-weight-bold">
                                    {{ $t('message.others.table_entries', {
                                        from: linkInjections.from,
                                        to: linkInjections.to,
                                        end: linkInjections.total
                                    }) }}
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl_link_injections" class="table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr class="label-primary">
                                        <th>Action</th>
                                        <th>ID#</th>
                                        <th>URL Publisher</th>
                                        <th>Injection Price</th>
                                        <th>URL Article</th>
                                        <th>URL Advertiser</th>
                                        <th>Link to</th>
                                        <th>Anchor Text</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(injection, index) in linkInjections.data" :key="index">
                                        <td>
                                            <div class="text-center">
                                                <button
                                                    v-if="injection.status == 'Checked' && user.permission_list.includes('update-buyer-follow-up-injection')"
                                                    title="Purchase Injection"
                                                    class="btn btn-default action-btns"
                                                    data-toggle="modal"
                                                    data-target="#modal-update-injection"
                                                    :disabled="injection.status != 'Checked'"

                                                    @click="doUpdate(injection, 'buy')">

                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>

                                                <button
                                                    v-if="user.permission_list.includes('update-buyer-follow-up-injection')"
                                                    title="Edit"
                                                    class="btn btn-default action-btns"
                                                    data-toggle="modal"
                                                    data-target="#modal-update-injection"

                                                    @click="doUpdate(injection, 'update')">

                                                    <i class="fa fa-fw fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ injection.id }}</td>
                                        <td>
                                            <div>
                                                <small class="font-italic badge badge-success">#{{ injection.publisher_id }}</small>
                                            </div>
                                            <div>
                                                {{ injection.publisher_url }}
                                            </div>
                                            <div v-if="user.isAdmin || [15, 8].includes(user.role_id)">
                                                <small class="font-italic">
                                                    <i class="fa fa-shopping-basket text-primary" aria-hidden="true"></i>
                                                    {{ injection.publisher
                                                        ? injection.publisher.user
                                                            ? injection.publisher.user.username
                                                                ? injection.publisher.user.username
                                                                : injection.publisher.user.name
                                                            : 'N/A'
                                                        : 'N/A'
                                                    }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span v-if="injection.buyer_injection_price == null" class="badge badge-info">
                                                <small>
                                                    INJECTION PRICE NOT SET
                                                </small>
                                            </span>

                                            <span v-else>
                                                $ {{ injection.buyer_injection_price }}
                                            </span>
                                        </td>
                                        <td>
                                            <div v-if="injection.url_article">
                                                <a :href="'//' + replaceCharacters(injection.url_article)" target="_blank">
                                                    {{ replaceCharacters(injection.url_article) }}
                                                </a>
                                            </div>

                                            <div v-else>
                                                <span class="badge badge-pill badge-secondary">
                                                    N/A
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="injection.url_advertiser">
                                                <a :href="'//' + replaceCharacters(injection.url_advertiser)" target="_blank">
                                                    {{ replaceCharacters(injection.url_advertiser) }}
                                                </a>
                                            </div>

                                            <div v-else>
                                                <span class="badge badge-pill badge-secondary">
                                                    N/A
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="injection.link">
                                                <a :href="'//' + replaceCharacters(injection.link)" target="_blank">
                                                    {{ replaceCharacters(injection.link) }}
                                                </a>
                                            </div>

                                            <div v-else>
                                                <span class="badge badge-pill badge-secondary">
                                                    N/A
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ injection.anchor_text }}</td>
                                        <td>
                                            <span v-if="injection.date_requested != null" class="badge badge-pill badge-warning">
                                                {{ injection.date_requested }}
                                            </span>
                                            <span v-if="injection.date_process != null" class="badge badge-pill badge-info">
                                                {{ injection.date_process }}
                                            </span>
                                            <span v-if="injection.live_date != null" class="badge badge-pill badge-primary">
                                                {{ injection.live_date }}
                                            </span>
                                        </td>
                                        <td>
                                            <span :class="statusBadges(injection.status)">
                                                {{ injection.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <pagination
                                class="mt-2"
                                :limit="8"
                                :data="linkInjections"

                                @pagination-change-page="getLinkInjections">

                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Update Publisher -->
        <div
            class="modal fade"
            role="dialog"
            tabindex="-1"
            id="modal-update-injection"
            aria-hidden="true"
            aria-labelledby="modelTitleId"
            data-backdrop="static"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ updateMode == 'buy' ? 'Purchase Link Injection' : 'Update Injection Request' }}</h5>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Date Requested</label>
                                        <input
                                            v-model="linkInjectionModel.date_requested"
                                            disabled
                                            type="date"
                                            class="form-control"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.efb_date_processed') }}</label>
                                        <input
                                            v-model="linkInjectionModel.date_process"
                                            type="date"
                                            class="form-control"
                                            :disabled="user.role_id == 5"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Date Completed</label>
                                        <input
                                            v-model="linkInjectionModel.live_date"
                                            disabled
                                            type="date"
                                            class="form-control"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_url_pub') }}</label>
                                    <input
                                        v-model="linkInjectionModel.publisher_url"
                                        type="text"
                                        required="required"
                                        class="form-control"
                                        :disabled="true"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_url_ad') }}</label>
                                    <input
                                        v-model="linkInjectionModel.url_advertiser"
                                        type="text"
                                        class="form-control"
                                        required="required"
                                        :disabled="linkInjectionModel.status == 'Live'"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Injection Price</label>
                                        <input
                                            v-model="linkInjectionModel.buyer_injection_price"
                                            value=""
                                            type="number"
                                            required="required"
                                            class="form-control"
                                            :disabled="true"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL Article</label>
                                    <input
                                        v-model="linkInjectionModel.url_article"
                                        type="text"
                                        class="form-control"
                                        required="required"
                                        :disabled="linkInjectionModel.status == 'Live'"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_anchor_text') }}</label>
                                    <input
                                        v-model="linkInjectionModel.anchor_text"
                                        type="text"
                                        class="form-control"
                                        required="required"
                                        :disabled="linkInjectionModel.status == 'Live'"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_link_to') }}</label>
                                    <input
                                        v-model="linkInjectionModel.link"
                                        type="text"
                                        class="form-control"
                                        required="required"
                                        :disabled="linkInjectionModel.status == 'Live'"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': linkInjectionErrors.status}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.filter_status') }}</label>
                                        <select
                                            v-model="linkInjectionModel.status"
                                            class="form-control pull-right"
                                            style="height: 37px;"
                                            :disabled="[5, 8].includes(user.role_id)"
                                        >
                                            <option v-for="(status, index) in generateInjectionPriceStatus(linkInjectionModel.status)" v-bind:value="status" :key="index">
                                                {{ status }}
                                            </option>
                                        </select>

                                        <div v-if="linkInjectionErrors.status">
                                            <span
                                                v-for="(statusErr, index) in linkInjectionErrors.status"
                                                class="text-danger"
                                                :key="index"
                                            >
                                                {{ statusErr }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_reason') }}</label>
                                        <select
                                            v-model="linkInjectionModel.reason"
                                            disabled
                                            style="height: 37px;"
                                            class="form-control pull-right"

                                            @change="checkReason()">
                                            <option v-for="(reason, index) in listReason" v-bind:value="reason" :key="index">{{ reason }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_note_issue') }}</label>
                                        <textarea v-model="linkInjectionModel.reason_detailed" disabled class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReason">
                                <div :class="{'form-group': true, 'has-error': linkInjectionErrors.file}">
                                    <label>{{ $t('message.follow.si_issue') }}</label>

                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" :value="linkInjectionModel.reason_file" disabled>

                                        <div class="input-group-append">
                                            <button
                                                :disabled="linkInjectionModel.reason_file === '' || linkInjectionModel.reason_file == null"
                                                class="btn btn-primary"
                                                :title="$t('message.follow.si_view_issue')"
                                                data-toggle="modal"
                                                data-target="#modal-view-issue-cancel-file-injection"

                                                @click="doShowIssueCancelFile(linkInjectionModel.reason_file)">

                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>

                                            <a
                                                download
                                                :href="linkInjectionModel.reason_file"
                                                :disabled="linkInjectionModel.reason_file === '' || linkInjectionModel.reason_file == null"
                                                title="Reason File"
                                                class="btn btn-primary">
                                                <em class="fa fa-download"></em>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>

                        <div v-if="updateMode == 'update'">
                            <button  type="button" class="btn btn-primary" @click="submitUpdate()">
                                {{ $t('message.publisher.update') }}
                            </button>
                        </div>

                        <div v-else>
                            <button type="button" class="btn btn-success" @click="purchaseLinkInjection()">
                                Purchase
                            </button>

                            <button type="button" class="btn btn-danger" @click="declineLinkInjection()">
                                Decline
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal update publisher -->

        <!-- Modal View Issue File -->
        <div class="modal fade" id="modal-view-issue-cancel-file-injection" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.follow.si_issue') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img class="img-fluid"
                                    :src="issueCancelFilePreview"
                                    alt="Reason File" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('message.follow.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Issue File -->
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import axios from "axios";
import {dateRange} from "../../../mixins/dateRange";

export default {
    props: [],
    mixins: [dateRange],
    data() {
        return {
            linkInjections: {
                data: []
            },

            filterModel: {
                page : this.$route.query.page || 1,
                status : this.$route.query.status || '',
                link_to: this.$route.query.link_to || '',
                paginate : this.$route.query.paginate || 10,
                anchor_text: this.$route.query.anchor_text || '',
                url_article: this.$route.query.url_article || '',
                url_publisher: this.$route.query.url_publisher || '',
                url_advertiser: this.$route.query.url_advertiser || '',
                live_date : {
                    startDate : '',
                    endDate: ''
                },
                date_process : {
                    startDate : '',
                    endDate: ''
                },
                date_requested : {
                    startDate : '',
                    endDate: ''
                },
            },

            linkInjectionModel: {
                id: '',
                link: '',
                status: '',
                url_article: '',
                anchor_text: '',
                date_requested: '',
                date_process: '',
                live_date: '',
                publisher_url: '',
                url_advertiser: '',
                injection_price: '',
                buyer_injection_price: '',
                reason: '',
                reason_detailed: '',
            },

            linkInjectionStatus: [
                'Pending',
                'Checked',
                'Processing',
                'Live in Process',
                'Issue',
                'Canceled',
                'Live'
            ],

            linkInjectionErrors: [],

            updateMode: '',

            showReason: false,
            showReasonText: false,
            reasonIssue: [
                'URL link to cannot be found',
                'One-click ads URL',
                'I am having technical issues',
                'I am in a vacation',
                'Other'
            ],
            reasonCancelled: [
                'URL not available',
                'URL sold',
                'Not selling backlink',
                'Other'
            ],
            listReason: [],
            issueCancelFilePreview: '',
        }
    },

    computed: {
        ...mapState({
            currentUser: state => state.storeAuth.currentUser,
            user : state => state.storeAuth.currentUser
        }),
    },

    async mounted () {
        this.getLinkInjections();
    },

    methods: {
        getLinkInjections (page = 1) {
            let self = this;
            let loader = this.$loading.show();
            let table = $("#tbl_link_injections");

            self.filterModel.page = page;

            table.DataTable().destroy();

            axios.get('/api/get-link-injections', {
                params: self.filterModel
            })
            .then((res) => {
                loader.hide();
                self.linkInjections = res.data;

                self.$nextTick(() => {
                    table.DataTable({
                        paging: false,
                        searching: false,
                        info: false,
                    });
                });
            })
            .catch((err) => {
                console.log(err);
            })
        },

        doUpdate (data, mode) {
            this.linkInjectionModel = {
                id: data.id,
                date_process: data.date_process,
                date_requested: data.date_requested,
                live_date: data.live_date,
                publisher_url: data.publisher_url,
                url_article: data.url_article,
                url_advertiser: data.url_advertiser,
                link: data.link,
                anchor_text: data.anchor_text,
                status: data.status,
                buyer_injection_price: data.buyer_injection_price,
                reason: data.reason,
                reason_detailed: data.reason_detailed,
                reason_file: data.reason_file,
            }

            this.updateMode = mode;

            this.checkStatus();

            // to display the Details of Issue/Cancelled field
            if(this.linkInjectionModel.reason == 'Other' && this.linkInjectionModel.reason != null ){
                this.showReasonText = true;
            } else {
                this.showReasonText = false;
            }
        },

        submitUpdate () {
            let self = this;
            let loader = this.$loading.show();
            let table = $("#tbl_link_injections");

            axios.post('/api/update-link-injections', self.linkInjectionModel)
            .then((res) => {
                loader.hide();
                $('#modal-update-injection').modal('hide');

                swal.fire(
                    self.$t('message.article.alert_updated'),
                    '',
                    'success'
                )

                self.getLinkInjections(this.$route.query.page);
            })
            .catch((err) => {
                console.log(err);
            })
        },

        purchaseLinkInjection () {
            let self = this;

            swal.fire({
                title : 'Are you sure that you want to purchase the link injection?',
                text : '',
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let loader = this.$loading.show();

                    axios.post('/api/purchase-link-injections', self.linkInjectionModel)
                    .then((res) => {
                        loader.hide();
                        $('#modal-update-injection').modal('hide');

                        this.$root.$refs.AppHeader.liveGetWallet()

                        swal.fire(
                            'Link injection purchase is now in process',
                            '',
                            'success'
                        )

                        self.getLinkInjections(this.$route.query.page);
                    })
                    .catch((err) => {
                        loader.hide();
                        swal.fire(
                            self.$t('message.draft.error'),
                            err.response.data.message,
                            'error'
                        )
                    })
                }
            });
        },

        declineLinkInjection () {
            let self = this;

            swal.fire({
                title : 'Are you sure that you want to decline the approved link injection price?',
                text : 'Declining will automatically cancel the request',
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let loader = this.$loading.show();

                    axios.post('/api/decline-link-injection', self.linkInjectionModel)
                    .then((res) => {
                        loader.hide();
                        $('#modal-update-injection').modal('hide');

                        this.$root.$refs.AppHeader.liveGetWallet()

                        swal.fire(
                            'Link injection declined',
                            '',
                            'success'
                        )

                        self.getLinkInjections(this.$route.query.page);
                    })
                    .catch((err) => {
                        loader.hide();
                        swal.fire(
                            self.$t('message.draft.error'),
                            err.response.data.message,
                            'error'
                        )
                    })
                }
            });
        },

        doSearch () {
            this.$router.push({
                query: this.filterModel,
            });

            this.getLinkInjections(1)
        },

        clearFilter () {
            this.filterModel = {
                status: '',
                link_to: '',
                url_article: '',
                anchor_text: '',
                url_publisher: '',
                url_advertiser: '',
                page : 1,
                live_date : {
                    startDate :null,
                    endDate:null
                },
                date_process : {
                    startDate :null,
                    endDate:null
                },
                date_requested : {
                    startDate :null,
                    endDate:null
                },
            }

            this.$router.replace({'query': null});

            this.getLinkInjections(1)
        },

        statusBadges (status) {
            let statusClasses = {
                'Pending': 'badge badge-warning',
                'Checked': 'badge badge-info',
                'Processing': 'badge badge-primary',
                'Live in Process': 'badge badge-primary',
                'Issue': 'badge badge-danger',
                'Canceled': 'badge badge-danger',
                'Live': 'badge badge-success',
            }

            return statusClasses[status];
        },

        generateInjectionPriceStatus(status) {
            if (status === 'Pending' || status === 'Checked') {
                return this.linkInjectionStatus.filter(i => i === status);
            } else {
                return this.linkInjectionStatus.filter(i => i !== 'Pending' && i !== 'Checked');
            }
        },

        checkStatus() {
            if(this.linkInjectionModel.status === 'Issue'){
                this.showReason = true;
                this.listReason = this.reasonIssue
            } else if(this.linkInjectionModel.status === 'Canceled'){
                this.showReason = true;
                this.listReason = this.reasonCancelled
            } else {
                this.showReason = false;
                this.showReasonText = false;
            }
        },

        checkReason() {
            if(this.updateModel.reason == 'Other'){
                this.showReasonText = true;
            } else {
                this.showReasonText = false;
            }
        },

        doShowIssueCancelFile(src) {
            this.issueCancelFilePreview = src;
        },

        replaceCharacters(str) {
            let char1 = str.replace("http://", "");
            let char2 = char1.replace("https://", "");
            let char3 = char2.replace("www.", "");

            return char3;
        },
    },
}
</script>

<style>
.action-btns {
    width: 50px;
    margin: 2px;
}
</style>

