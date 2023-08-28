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
                                    Injection Requests
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
                                        <span class="badge badge-pill badge-success">Date Completed</span>
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
                                        <th>Dates</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(injection, index) in linkInjections.data" :key="index">
                                        <td>
                                            <div class="text-center">
                                                <button
                                                    v-if="user.permission_list.includes('update-seller-injection-requests')"
                                                    title="Edit"
                                                    class="btn btn-default action-btns"
                                                    data-toggle="modal"
                                                    data-target="#modal-update-injection"

                                                    @click="doUpdate(injection)">

                                                    <i class="fa fa-fw fa-edit"></i>
                                                </button>

                                                <button
                                                    v-if="injection.status != 'Canceled' && (user.isAdmin || [15, 8].includes(user.role_id))"
                                                    title="Update seller"
                                                    class="btn btn-default action-btns"

                                                    @click="updateSeller(injection)">

                                                    <i class="fa fa-user" aria-hidden="true"></i>
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
                                            <span v-if="injection.injection_price == null" class="badge badge-info">
                                                <small>
                                                    INJECTION PRICE NOT SET
                                                </small>
                                            </span>

                                            <span v-else>
                                                $ {{ injection.injection_price }}
                                            </span>
                                        </td>
                                        <td>{{ injection.url_article }}</td>
                                        <td>{{ injection.url_advertiser }}</td>
                                        <td>{{ injection.link }}</td>
                                        <td>{{ injection.anchor_text }}</td>
                                        <td>
                                            <span v-if="injection.date_requested != null" class="badge badge-pill badge-warning">
                                                {{ injection.date_requested }}
                                            </span>
                                            <span v-if="injection.date_process != null" class="badge badge-pill badge-info">
                                                {{ injection.date_process }}
                                            </span>
                                            <span v-if="injection.live_date != null" class="badge badge-pill badge-success">
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

        <!-- Modal Update Link Injection -->
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
                        <h5 class="modal-title">{{ linkInjectionModel.status == 'Pending' ? 'Approve' : 'Update' }} Injection Request</h5>
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
                                        disabled
                                        type="text"
                                        required="required"
                                        class="form-control"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_url_ad') }}</label>
                                    <input
                                        v-model="linkInjectionModel.url_advertiser"
                                        disabled
                                        type="text"
                                        class="form-control"
                                        required="required"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Injection Price</label>

                                        <small class="text-info">
                                            <i class="fa fa-exclamation-circle"></i>
                                            Input injection price offer
                                        </small>
                                        <input
                                            v-model="linkInjectionModel.injection_price"
                                            value=""
                                            type="number"
                                            required="required"
                                            class="form-control"
                                            :disabled="isInjectionPriceDisabled(linkInjectionModel.status)"
                                        >

                                        <div v-if="linkInjectionErrors.injection_price">
                                            <span
                                                v-for="(statusErr, index) in linkInjectionErrors.injection_price"
                                                class="text-danger"
                                                :key="index"
                                            >
                                                {{ statusErr }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL Article</label>
                                    <input
                                        v-model="linkInjectionModel.url_article"
                                        disabled
                                        type="text"
                                        class="form-control"
                                        required="required"
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
                                        :disabled="!user.isAdmin && user.role_id !== 8"
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
                                        :disabled="!user.isAdmin && user.role_id !== 8"
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
                                            :disabled="(linkInjectionModel.status == 'Pending' && (user.role_id != 8 || ![6, 15].includes(user.role_id)) && !user.isAdmin) || linkInjectionModel.status == 'Live' || linkInjectionModel.status == 'Canceled'"

                                            @change="checkStatus()"
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
                                        <select  class="form-control pull-right" v-model="linkInjectionModel.reason" style="height: 37px;" @change="checkReason()">
                                            <option v-for="(reason, index) in listReason" v-bind:value="reason" :key="index">{{ reason }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_note_issue') }}</label>
                                        <textarea class="form-control" v-model="linkInjectionModel.reason_detailed"></textarea>
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

                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="issue_file_injection" name="file">
                                    <small class="text-muted">{{ $t('message.follow.si_note') }}</small><br/>
                                    <div v-if="linkInjectionErrors.file">
                                        <span v-for="(err, index) in linkInjectionErrors.file" class="text-danger" :key="index">
                                            {{ err }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>

                        <button v-if="(linkInjectionModel.status != 'Pending' || (user.isAdmin || user.role_id == 8))" type="button" class="btn btn-primary" @click="submitUpdate()">
                            {{ $t('message.publisher.update') }}
                        </button>

                        <button v-if="linkInjectionModel.status == 'Pending'" type="button" class="btn btn-success" @click="approveInjection()">
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal update Link Injection -->

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

        <!-- Modal Update Seller -->
        <div class="modal fade" id="modal-update-seller" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Seller for Link Injection Request #{{ updateSellerModel.link_injection_id }}</h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="otherSellers.length === 0">
                            <div class="alert alert-danger mb-0" role="alert">
                                There are no other sellers associated with the url.
                            </div>
                        </div>

                        <div v-else class="row">
                            <div class="col-12 mb-3">
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    Updating the seller will cancel the current order and create a new one.
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Seller</label>

                                    <v-select
                                        v-model="updateSellerModel.publisher_id"
                                        placeholder="Select Seller"
                                        label="name"
                                        :options="otherSellers"
                                        :reduce="otherSellers => otherSellers.id"
                                        :searchable="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('message.follow.close') }}</button>

                        <button
                            v-if="linkInjectionModel.status == 'Pending' || (user.isAdmin || [15, 8].includes(user.role_id))"
                            type="button"
                            class="btn btn-primary"
                            :disabled="otherSellers.length === 0"

                            @click="submitUpdateSeller()">

                            {{ $t('message.publisher.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Seller -->
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
            updateFormula : {},
            injectionBuyer: {},

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

            otherSellers: [],
            updateSellerModel: {
                link_injection_id: '',
                publisher_id: '',
            }
        }
    },

    computed: {
        ...mapState({
            currentUser: state => state.storeAuth.currentUser,
            user : state => state.storeAuth.currentUser,
            formula : state => state.storeSystem.formula,
        }),
    },

    async mounted () {
        this.getFormula();
        this.getLinkInjections();
    },

    methods: {
        async getFormula() {
            await this.$store.dispatch('actionGetFormula');
            this.updateFormula = this.formula.data[0];
        },

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

        doUpdate (data) {
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
                injection_price: data.injection_price,
                status: data.status,
                reason: data.reason,
                reason_detailed: data.reason_detailed,
                reason_file: data.reason_file,
            }

            this.checkStatus();

            // to display the Details of Issue/Cancelled field
            if(this.linkInjectionModel.reason == 'Other' && this.linkInjectionModel.reason != null ){
                this.showReasonText = true;
            } else {
                this.showReasonText = false;
            }

            this.injectionBuyer = data.buyer.user_type;
        },

        submitUpdate () {
            let self = this;
            let loader = this.$loading.show();

            // make form data for file
            let form_data = new FormData();

            for ( let key in self.linkInjectionModel ) {
                // if value is null or undefined, make empty string
                let val = (self.linkInjectionModel[key] == null || self.linkInjectionModel[key] == undefined)
                    ? ''
                    : self.linkInjectionModel[key];

                form_data.append(key, val);
            }

            // append file on form data
            form_data.append('file', this.$refs.issue_file_injection.files[0]);

            axios.post('/api/update-link-injections', form_data)
            .then((res) => {
                loader.hide();
                $('#modal-update-injection').modal('hide');

                swal.fire(
                    self.$t('message.article.alert_updated'),
                    '',
                    'success'
                )

                this.$refs.issue_file_injection.value = '';

                self.getLinkInjections(this.$route.query.page);
            })
            .catch((err) => {
                loader.hide();

                swal.fire(
                    self.$t('message.draft.error'),
                    err.response.data.message,
                    'error'
                )

                self.linkInjectionErrors = err.response.data.errors
            })
        },

        approveInjection () {
            let self = this;
            let loader = this.$loading.show();
            self.linkInjectionModel.buyer_injection_price = self.computeBuyerInjectionPrice(self.linkInjectionModel.injection_price);

            axios.post('/api/approve-link-injections', self.linkInjectionModel)
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
                loader.hide();
                swal.fire(
                    self.$t('message.draft.error'),
                    err.response.data.message,
                    'error'
                )

                self.linkInjectionErrors = err.response.data.errors
            })
        },

        updateSeller (data) {
            let self = this;
            let loader = this.$loading.show();

            self.updateSellerModel.link_injection_id = data.id;

            axios.post('/api/check-publisher-sellers', {
                publisher_id: data.publisher_id
            })
            .then((res) => {
                loader.hide();

                self.otherSellers = Object.entries(res.data.data).map(([id, name]) => ({ id, name }));

                $('#modal-update-seller').modal('show');
            })
            .catch((err) => {
                loader.hide();
            })
        },

        submitUpdateSeller () {
            let self = this;

            swal.fire({
                title : 'Are you sure that you want to update the seller for this link injection request?',
                text : 'A notification will be sent to the buyer and seller',
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let loader = this.$loading.show();

                    axios.post('/api/update-injection-seller', self.updateSellerModel)
                    .then((res) => {
                        loader.hide();
                        $('#modal-update-seller').modal('hide');

                        swal.fire(
                            'New link injection for new seller requested',
                            '',
                            'success'
                        )

                        self.getLinkInjections(this.$route.query.page);
                    })
                    .catch((err) => {
                        loader.hide();
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

        isInjectionPriceDisabled (status) {
            return !['Pending', 'Checked'].includes(status)
        },

        computeBuyerInjectionPrice (price) {
            let self = this;

            let injection_price = price;
            let percent = parseFloat(this.formula.data[0].percentage);
            let buyer_type_basic = parseFloat(this.formula.data[0].basic);
            let buyer_type_medium = parseFloat(this.formula.data[0].medium);
            let buyer_type_premium = parseFloat(this.formula.data[0].premium);

            if (self.injectionBuyer) {
                let type = self.injectionBuyer.type
                let buyer_type = self.injectionBuyer.buyer_type
                let commission = (self.injectionBuyer.commission).toLowerCase()

                if(buyer_type == 'Basic') {
                    percent = buyer_type_basic;
                } else if (buyer_type == 'Medium') {
                    percent = buyer_type_medium;
                } else {
                    percent = buyer_type_premium;
                }

                if (price != '' && price != null) {
                    if (type == 'Buyer') {
                        if (commission == 'no') {
                            injection_price = price
                        }

                        if (commission == 'yes') {
                            let percentage = this.percentage(percent, price)
                            injection_price = parseFloat(percentage) + parseFloat(price)
                        }
                    }
                }
            }

            injection_price = parseFloat(injection_price).toFixed(0);

            return injection_price;
        },

        percentage(percent, total) {
            return ((percent / 100) * total).toFixed(2)
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
    },
}
</script>

<style>
    .action-btns {
        width: 50px;
        margin: 2px;
    }
</style>
