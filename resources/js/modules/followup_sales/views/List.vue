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
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.follow.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.follow.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_search_url') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.search"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.follow.type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_search_backlink') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.backlink_id"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.follow.type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_status') }}</label>
                                    <v-select
                                        multiple
                                        v-model="filterModel.status"
                                        :options="statusBacklinkQc"
                                        :searchable="false"
                                        :placeholder="$t('message.follow.all')"/>
                                    <!--                                <select name="" class="form-control" v-model="filterModel.status">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option v-for="status in statusBacklinkQc" v-bind:value="status">{{ status }}</option>-->
                                    <!--                                </select>-->
                                </div>
                            </div>

                            <div class="col-md-2" v-if="user.isOurs != 1">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_seller') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.follow.all') }}</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2" v-if="user.isOurs != 1">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_buyer') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.buyer">
                                        <option value="">{{ $t('message.follow.all') }}</option>
                                        <option v-for="option in listBuyer.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_country') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.country_id">
                                        <option value="">{{ $t('message.follow.all') }}</option>
                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2" v-if="user.isOurs !== 1 || user.role_id !== 4">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_article') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.article">
                                        <option value="">{{ $t('message.follow.all') }}</option>
                                        <option value="With">{{ $t('message.follow.with') }}</option>
                                        <option value="Without">{{ $t('message.follow.without') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_in_charge') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.in_charge">
                                        <option value="">{{ $t('message.follow.all') }}</option>
                                        <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username}}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_process_date') }}
                                    </label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.process_date"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.process_date"
                                            :linkedCalendars="true"
                                            opens="right"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.filter_date_completed') }}
                                    </label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="filterModel.date_completed"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="filterModel.date_completed"
                                            :linkedCalendars="true"
                                            opens="right"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div v-if="user.isOurs === 0" class="col-md-2">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.dbs_filter') }}</label>
                                    <select class="form-control" v-model="filterModel.deleted_by_seller">
                                        <option value="">{{ $t('message.publisher.all') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.follow.clear') }}
                                </button>

                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.follow.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i>
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
                        <h3 class="card-title text-primary">{{ $t('message.follow.fus_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="d-inline pull-right">{{ $t('message.follow.fus_amount') }} $ {{ totalAmount }}</h5>

                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="input-group input-group-sm float-right" style="width: 100px">
                                        <select name=""
                                                class="form-control float-right"
                                                @change="getListSales"
                                                v-model="filterModel.paginate"
                                                style="height: 37px;">
                                            <option v-for="option in paginate" v-bind:value="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                    <button data-toggle="modal"
                                            data-target="#modal-setting"
                                            class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                                </td>
                            </tr>
                        </table>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b>{{ $t('message.others.table_entries', { from: listSales.from, to: listSales.to, end: listSales.total }) }}</b>
                        </span>

                        <div class="table-responsive">
                            <table id="tbl-followupsales"
                                   class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>{{ $t('message.follow.fus_action') }}</th>
                                    <th>#</th>
                                    <th v-show="tblOptions.pub_id" v-if="user.isOurs !== 1 || user.role_id !== 4">
                                        {{ $t('message.follow.fus_url_pub_id') }}
                                    </th>
                                    <th v-show="tblOptions.blink_id">{{ $t('message.follow.fus_blink') }}</th>
                                    <th v-show="tblOptions.arc_id">{{ $t('message.follow.fus_artc') }}</th>
                                    <th v-show="tblOptions.country">{{ $t('message.follow.filter_country') }}</th>
                                    <th v-show="tblOptions.in_charge">{{ $t('message.follow.filter_in_charge') }}</th>
                                    <th v-show="tblOptions.seller" v-if="user.isOurs != 1">{{ $t('message.follow.filter_seller') }}</th>
                                    <th v-show="tblOptions.buyer" v-if="user.isOurs != 1">{{ $t('message.follow.filter_buyer') }}</th>
                                    <th v-show="tblOptions.url">{{ $t('message.follow.fus_url_pub') }}</th>
                                    <th v-show="tblOptions.price" v-if="user.isOurs !== 1 || user.role_id !== 4">
                                        {{ $t('message.follow.fus_price') }}
                                    </th>
                                    <th v-show="tblOptions.link_from">{{ $t('message.follow.fus_link_from') }}</th>
                                    <th v-show="tblOptions.link_to">{{ $t('message.follow.fus_link_to') }}</th>
                                    <th v-show="tblOptions.anchor_text">{{ $t('message.follow.fus_at') }}</th>
                                    <th v-show="tblOptions.date_process">{{ $t('message.follow.fus_date_process') }}</th>
                                    <th v-show="tblOptions.date_complete">{{ $t('message.follow.filter_date_completed') }}</th>
                                    <th v-show="tblOptions.status">{{ $t('message.follow.filter_status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(sales, index) in listSales.data" :key="index">
                                    <td>
                                        <div class="btn-group">
                                            <button
                                                data-toggle="modal"
                                                data-target="#modal-update-sales"
                                                :title="$t('message.follow.edit')"
                                                class="btn btn-default mr-2"

                                                @click="doUpdate(sales)">

                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button
                                                v-if="user.isAdmin || (user.role_id === 6 && user.isOurs === 1) || (user.role_id === 8 && user.isOurs === 1)"
                                                :disabled="sales.status !== 'Pending'"
                                                data-toggle="modal"
                                                data-target="#modal-approve-pending"
                                                class="btn btn-default"
                                                :title="$t('message.follow.fus_confirm_pending')"

                                                @click="confirmPendingSellerOrder(sales)">

                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>{{ index + 1}}</td>
                                    <td
                                        v-show="tblOptions.pub_id" v-if="user.isOurs !== 1 || user.role_id !== 4">{{ sales.publisher == null ? 'N/A' : sales.publisher.id }}
                                    </td>
                                    <td v-show="tblOptions.blink_id">{{ sales.id }}</td>
                                    <td v-show="tblOptions.arc_id">
                                        <div class="d-flex align-items-center">

                                            <span v-if="sales.article_id == null" class="badge badge-danger">
                                                N/A
                                            </span>

                                            <span
                                                v-else
                                                title="Go to Article"
                                                style="cursor: pointer; color: #1c85ff"

                                                @click="redirectToArticle(sales.article_id)">

                                                {{ sales.article_id }}
                                            </span>

                                            <button
                                                v-if="sales.article_id == null
                                                    && sales.status === 'Processing'
                                                    && (user.isAdmin || user.role_id === 8)"
                                                title="Generate Article"
                                                class="btn btn-primary ml-2"

                                                @click="generateArticle(sales)">

                                                <i class="fas fa-pen-fancy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        v-show="tblOptions.country">{{ sales.publisher == null ? 'N/A' : (sales.publisher.country == null ? 'N/A' : sales.publisher.country.name) }}</td>
                                    <td v-show="tblOptions.in_charge">{{ sales.in_charge == null ? 'N/A':sales.in_charge }}</td>
                                    <td
                                        v-show="tblOptions.seller" v-if="user.isOurs != 1">{{ sales.publisher == null ? 'N/A' : (sales.publisher.user == null ? 'N/A' : (sales.publisher.user.username == null ? sales.publisher.user.name : sales.publisher.user.username)) }}</td>
                                    <td
                                        v-show="tblOptions.buyer" v-if="user.isOurs != 1">{{ sales.user == null ? 'N/A' : (sales.user.username == null ? sales.user.name : sales.user.username) }}</td>
                                    <td v-show="tblOptions.url">
                                        <!--                                    {{ sales.publisher == null ? 'N/A' : replaceCharacters(sales.publisher.url) }}-->
                                        <span v-if="sales.publisher == null">
                                            N/A
                                        </span>
                                        <span v-else>
                                            <a :href="'//' + replaceCharacters(sales.publisher.url)" target="_blank">
                                                {{ replaceCharacters(sales.publisher.url) }}
                                            </a>

                                            <span v-if="sales.publisher.deleted_at" class="badge badge-danger">
                                                {{ $t('message.follow.dbs_table') }}
                                            </span>
                                        </span>
                                    </td>
                                    <td v-show="tblOptions.price" v-if="user.isOurs !== 1 || user.role_id !== 4">{{ sales.price == null ? '':'$ ' + sales.price }}</td>
                                    <td v-show="tblOptions.link_from">
                                        <div class="dont-break-out">
                                            <a :href="sales.link_from" target="_blank">{{ sales.link_from }}</a>
                                        </div>
                                    </td>
                                    <td v-show="tblOptions.link_to">
                                        <div class="dont-break-out">
                                            <a :href="sales.link" target="_blank">{{ sales.link }}</a>
                                        </div>
                                    </td>
                                    <td v-show="tblOptions.anchor_text">{{ sales.anchor_text }}</td>
                                    <td v-show="tblOptions.date_process">{{ sales.date_process }}</td>
                                    <td v-show="tblOptions.date_complete">{{ sales.live_date }}</td>
                                    <td v-show="tblOptions.status">{{ sales.status }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Update Sales -->
        <div class="modal fade" id="modal-update-sales" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $t('message.follow.si_title') }}
                            <b class="text-primary">{{ updateModel.id }}</b>
                        </h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_date_process') }}</label>
                                        <input type="date" class="form-control" :disabled="true" v-model="updateModel.date_process">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.follow.si_buyer_name') }}</label>
                                    <input type="text" :disabled="true" v-model="updateModel.user.name" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.follow.fus_url_pub') }}</label>
                                    <input type="text" v-model="updateModel.url_publisher" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.follow.si_url_ad') }}</label>
                                    <input type="text" :disabled="true" v-model="updateModel.url_advertiser" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.fus_price') }}</label>
                                        <input type="number" class="form-control" v-model="updateModel.price" :disabled="true" value="" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.fus_at') }}</label>
                                        <input type="text" class="form-control" :disabled="!user.isAdmin && user.role_id !== 8" v-model="updateModel.anchor_text" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_ip_title') }}</label>
                                        <input type="text" class="form-control" v-model="updateModel.title" required="required" :disabled="isLive">
                                        <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.fus_link_to') }}</label>
                                        <input type="text" class="form-control" :disabled="true" v-model="updateModel.link" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.link_from}" class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.fus_link_from') }}</label>
                                        <input type="text" class="form-control" v-model="updateModel.link_from" required="required" :disabled="isLive">
                                        <span v-if="messageForms.errors.link_from" v-for="err in messageForms.errors.link_from" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6" v-if="updateModel.article_id != ''">
                                <div class="form-group">
                                    <label>{{ $t('message.follow.si_status_writer') }}</label>
                                    <select name="" class="form-control" v-model="updateModel.status_writer" :disabled="isLive || user.role_id == 6" >
                                        <option value="">{{ $t('message.follow.si_select_status') }}</option>
                                        <option v-for="option in writer_status" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_status_sales') }}</label>
                                        <select
                                            v-model="updateModel.status"
                                            style="height: 37px;"
                                            class="form-control pull-right"
                                            :disabled="(isLive && user.role_id != 8) || isCancelledIssue || (updateModel.status == 'Pending' && (user.role_id != 8 || user.role_id != 6) && user.isOurs != 0 && !user.isAdmin)"

                                            @change="checkStatus()">

                                            <option
                                                v-bind:value="status"
                                                v-for="status in statusBacklink"
                                                v-show="user.role_id != 8 && !user.isAdmin && user.isOurs == 1">
                                                {{ status }}
                                            </option>

                                            <option
                                                v-bind:value="status"
                                                v-for="status in statusBacklinkQc"
                                                v-show="(user.role_id == 8 || user.role_id == 6 || user.isAdmin) && user.isOurs == 0">
                                                {{ status }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6" v-if="updateModel.article_id != ''">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_art_id') }}</label>
                                        <input type="text" class="form-control" v-model="updateModel.article_id" :disabled="true">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.filter_date_completed') }}</label>
                                        <input type="date" class="form-control" v-model="updateModel.live_date" :disabled="isLive || user.role_id === 6">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_reason') }}</label>
                                        <select  class="form-control pull-right" v-model="updateModel.reason" style="height: 37px;" @change="checkReason()">
                                            <option v-for="reason in listReason" v-bind:value="reason">{{ reason }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReason">
                                <div class="form-group">
                                    <div>
                                        <label style="color: #333">{{ $t('message.follow.si_note_issue') }}</label>
                                        <textarea class="form-control" v-model="updateModel.reason_detailed"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-show="showReason">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label>{{ $t('message.follow.si_issue') }}</label>

                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" :value="updateModel.reason_file" disabled>

                                        <div class="input-group-append">
                                            <button
                                                :disabled="updateModel.reason_file === '' || updateModel.reason_file == null"
                                                class="btn btn-primary"
                                                :title="$t('message.follow.si_view_issue')"
                                                data-toggle="modal"
                                                data-target="#modal-view-issue-cancel-file"

                                                @click="doShowIssueCancelFile(updateModel.reason_file)">

                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>

                                            <a
                                                download
                                                :href="updateModel.reason_file"
                                                :disabled="updateModel.reason_file === '' || updateModel.reason_file == null"
                                                title="Reason File"
                                                class="btn btn-primary">
                                                <em class="fa fa-download"></em>
                                            </a>
                                        </div>
                                    </div>

                                    <input type="file" class="form-control" enctype="multipart/form-data" ref="issue_file" name="file">
                                    <small class="text-muted">{{ $t('message.follow.si_note') }}</small><br/>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.follow.close') }}
                        </button>

                        <button :disabled="isDisabledUpdate" type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.follow.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update Sales -->

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.follow.sd_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.pub_id ? 'checked':''" v-if="user.isOurs !== 1 || user.role_id !== 4" v-model="tblOptions.pub_id">{{ $t('message.follow.sd_url_id') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.blink_id ? 'checked':''" v-model="tblOptions.blink_id">{{ $t('message.follow.sd_backlink_id') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.arc_id ? 'checked':''" v-model="tblOptions.arc_id">{{ $t('message.follow.si_art_id') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.country ? 'checked':''" v-model="tblOptions.country">{{ $t('message.follow.filter_country') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.in_charge ? 'checked':''" v-model="tblOptions.in_charge">{{ $t('message.follow.filter_in_charge') }}</label>
                            </div>
                            <div class="checkbox col-md-4" v-if="user.isOurs != 1">
                                <label><input type="checkbox" :checked="tblOptions.seller ? 'checked':''" v-model="tblOptions.seller">{{ $t('message.follow.filter_seller') }}</label>
                            </div>
                            <div class="checkbox col-md-4" v-if="user.isOurs != 1">
                                <label><input type="checkbox" :checked="tblOptions.buyer ? 'checked':''" v-model="tblOptions.buyer">{{ $t('message.follow.filter_buyer') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.url ? 'checked':''" v-model="tblOptions.url">{{ $t('message.follow.fus_url_pub') }}</label>
                            </div>
                            <div class="checkbox col-md-4" v-if="user.isOurs !== 1 || user.role_id !== 4">
                                <label><input type="checkbox" :checked="tblOptions.price ? 'checked':''" v-model="tblOptions.price">{{ $t('message.follow.fus_price') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.link_from ? 'checked':''" v-model="tblOptions.link_from">{{ $t('message.follow.fus_link_from') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.link_to ? 'checked':''" v-model="tblOptions.link_to">{{ $t('message.follow.fus_link_to') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.anchor_text ? 'checked':''" v-model="tblOptions.anchor_text">{{ $t('message.follow.fus_at') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.date_process ? 'checked':''" v-model="tblOptions.date_process">{{ $t('message.follow.si_date_process') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.date_complete ? 'checked':''" v-model="tblOptions.date_complete">{{ $t('message.follow.filter_date_completed') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblOptions.status ? 'checked':''" v-model="tblOptions.status">{{ $t('message.follow.filter_status') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.follow.close') }}
                        </button>

                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.follow.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->

        <!-- Modal View Issue File -->
        <div class="modal fade" id="modal-view-issue-cancel-file" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                     alt="Proof of Billing" >
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

        <!-- Modal Approve Pending Start -->
        <div class="modal fade" id="modal-approve-pending" ref="modalApprovePending" style="display: none;" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.follow.sc_title') }}</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <span>
                                {{ $t('message.follow.sc_note') }}
                            </span>
                        </div>

                        <!-- details -->
                        <div v-if="sellerConfirmationData.hasOwnProperty('id')" class="card">
                            <div class="card-header">
                                <strong>{{ $t('message.follow.sc_details') }}</strong>
                            </div>
                            <div class="card-body">

                                <table style="table-layout: fixed; width: 100%">
                                    <thead>
                                        <tr>
                                            <td style="width: 75%"></td>
                                            <td class="text-center font-weight-bold">
                                                {{ $t('message.follow.sc_correct') }}
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                {{ $t('message.follow.sc_incorrect') }}
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="mb-1">
                                                {{ $t('message.follow.sc_blink_id') }}
                                                <strong>
                                                    {{ sellerConfirmationData.id }}
                                                </strong>
                                            </p>
                                            <p class="mb-1">
                                                {{ $t('message.follow.sc_url') }}
                                                <strong>
                                                    {{ replaceCharacters(sellerConfirmationData.publisher.url) }}
                                                </strong>
                                            </p>
                                            <p class="mb-1">
                                                {{ $t('message.follow.sc_price') }}
                                                <strong>
                                                    {{ sellerConfirmationData.price == null ? '' : '$' + sellerConfirmationData.price }}
                                                </strong>
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-radio">
                                                <input
                                                    v-model="sellerConfirmationValues.data"
                                                    type="radio"
                                                    :value="true"
                                                    id="dataRadio1"
                                                    name="dataRadio"
                                                    class="custom-control-input text-success">

                                                <label class="custom-control-label" for="dataRadio1">
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-radio">
                                                <input
                                                    v-model="sellerConfirmationValues.data"
                                                    type="radio"
                                                    :value="false"
                                                    id="dataRadio2"
                                                    name="dataRadio"
                                                    class="custom-control-input text-danger">

                                                <label class="custom-control-label" for="dataRadio2">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- need to clarify -->
                        <div v-if="sellerConfirmationData.hasOwnProperty('id')" class="card">
                            <div class="card-header">
                                <strong>{{ $t('message.follow.sc_need_clarify') }}</strong>
                            </div>
                            <div class="card-body">
                                <table style="table-layout: fixed; width: 100%">
                                    <thead>
                                        <tr>
                                            <td style="width: 75%"></td>
                                            <td class="text-center font-weight-bold">{{ $t('message.follow.sc_correct') }}</td>
                                            <td class="text-center font-weight-bold">{{ $t('message.follow.sc_incorrect') }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border-bottom: 1px solid lightgray;">
                                            <td style="padding: 15px 0 15px 0">
                                                <p class="mb-1">
                                                    {{ $t('message.follow.sc_inc_article') }}
                                                    <strong>
                                                        {{ sellerConfirmationData.publisher.inc_article }}
                                                    </strong>
                                                </p>

                                                <div class="mb-3">
                                                    <p class="mb-1">
                                                        <span class="badge badge-danger">{{ $t('message.follow.no') }}</span>
                                                        {{ $t('message.follow.sc_inc_no') }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <span class="badge badge-success">{{ $t('message.follow.yes') }}</span>
                                                        {{ $t('message.follow.sc_inc_yes') }}
                                                    </p>
                                                </div>

                                                <!-- update list publisher data inc article -->
                                                <div v-if="sellerConfirmationData.publisher.inc_article === 'yes'
                                                    || sellerConfirmationData.publisher.inc_article === 'Yes'">
                                                    <p class="mb-1">
                                                        Upon confirmation, the system will NOT generate an
                                                        article for this order.
                                                    </p>
                                                    <p
                                                        class="mb-1 text-primary"
                                                        style="cursor: pointer !important;"

                                                        @click="updatePublisherIncArticle(sellerConfirmationData)">
                                                        Click here to update list publisher data include article to 'No'
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.inc_article"
                                                        type="radio"
                                                        :value="true"
                                                        id="incArticleRadio1"
                                                        name="incArticleRadio"
                                                        class="custom-control-input text-success">

                                                    <label class="custom-control-label" for="incArticleRadio1">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.inc_article"
                                                        type="radio"
                                                        :value="false"
                                                        id="incArticleRadio2"
                                                        name="incArticleRadio"
                                                        class="custom-control-input text-danger">

                                                    <label class="custom-control-label" for="incArticleRadio2">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr style="border-bottom: 1px solid lightgray;">
                                            <td style="padding: 15px 0 15px 0">
                                                <p class="mb-1">
                                                    {{ $t('message.follow.sc_do_follow') }}
                                                    <strong>
                                                        {{ $t('message.follow.yes') }}
                                                    </strong>
                                                </p>

                                                <div class="mb-1">
                                                    <p class="mb-1">
                                                        <span class="badge badge-danger">{{ $t('message.follow.no') }}</span>
                                                        {{ $t('message.follow.sc_accept_no_follow') }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <span class="badge badge-success">{{ $t('message.follow.yes') }}</span>
                                                        {{ $t('message.follow.sc_accept_do_follow') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.do_follow"
                                                        type="radio"
                                                        :value="true"
                                                        id="doFollowRadio1"
                                                        name="doFollowRadio"
                                                        class="custom-control-input text-success">

                                                    <label class="custom-control-label" for="doFollowRadio1">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.do_follow"
                                                        type="radio"
                                                        :value="false"
                                                        id="doFollowRadio2"
                                                        name="doFollowRadio"
                                                        class="custom-control-input text-danger">

                                                    <label class="custom-control-label" for="doFollowRadio2">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="padding: 15px 0 15px 0">
                                                <p class="mb-1">
                                                    {{ $t('message.follow.sc_permanent_article') }}
                                                    <strong>
                                                        {{ $t('message.follow.yes') }}
                                                    </strong>
                                                </p>

                                                <div class="mb-1">
                                                    <p class="mb-1">
                                                        <span class="badge badge-danger">{{ $t('message.follow.no') }}</span>
                                                        {{ $t('message.follow.sc_no_perm_article') }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <span class="badge badge-success">{{ $t('message.follow.yes') }}</span>
                                                        {{ $t('message.follow.sc_yes_perm_article') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.permanent_article"
                                                        type="radio"
                                                        :value="true"
                                                        id="permArticleRadio1"
                                                        name="permArticleRadio"
                                                        class="custom-control-input text-success">

                                                    <label class="custom-control-label" for="permArticleRadio1">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center" style="padding: 15px 0 15px 0">
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        v-model="sellerConfirmationValues.permanent_article"
                                                        type="radio"
                                                        :value="false"
                                                        id="permArticleRadio2"
                                                        name="permArticleRadio"
                                                        class="custom-control-input text-danger">

                                                    <label class="custom-control-label" for="permArticleRadio2">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            v-if="isShowConfirmButtonOrders"
                            type="button"
                            class="btn btn-success"

                            @click="processPendingSellerOrder(sellerConfirmationData.id, 'approve')">

                            <i class="fa fa-check"></i>
                            {{ $t('message.follow.confirm') }}
                        </button>

                        <button
                            v-if="isShowCancelButtonOrders"
                            type="button"
                            class="btn btn-danger"

                            @click="processPendingSellerOrder(sellerConfirmationData.id, 'cancel')">

                            <i class="fa fa-times"></i>
                            {{ $t('message.follow.cancel') }}
                        </button>

                        <button
                            data-dismiss="modal"
                            type="button"
                            class="btn btn-default pull-left"

                            @click="resetConfirmationValues()">
                            {{ $t('message.follow.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Approve Pending End -->
    </div>
</template>

<style scoped>
.dont-break-out {
    /* These are technically the same, but use both */
    overflow-wrap: break-word;
    word-wrap: break-word;

    -ms-word-break: break-all;
    /* This is the dangerous one in WebKit, as it breaks things wherever */
    word-break: break-all;
    /* Instead use this non-standard one: */
    word-break: break-word;

    /* Adds a hyphen where the word breaks, if supported (No Blink) */
    -ms-hyphens: auto;
    -moz-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;
}
</style>

<script>
    import { mapState } from 'vuex';
    import axios from "axios";
    import {dateRange} from "../../../mixins/dateRange";

    export default {
        mixins: [dateRange],
        data() {
            return {
                paginate: [50,150,250,350,'All'],
                statusBacklink: [
                    'Processing',
                    'Content In Writing',
                    'Content Done',
                    'Content sent',
                    'Live in Process',
                    'Issue',
                    'Canceled'
                ],
                statusBacklinkQc: [
                    'Pending',
                    'Processing',
                    'Content In Writing',
                    'Content Done',
                    'Content sent',
                    'Live in Process',
                    'Issue',
                    'Canceled',
                    'Live',
                    'Nofollow',
                    '404',
                    'Deleted',
                    'Refund'
                ],
                writer_status: ['In Writing', 'Done'],
                updateModel: {
                    id: '',
                    url_publisher: '',
                    url_advertiser: '',
                    anchor_text: '',
                    link: '',
                    live_date: '',
                    status: '',
                    article_id: '',
                    date_process: '',
                    status_writer: '',
                    user: {
                        name: ''
                    },
                    link_from: '',
                    url_from: '',
                    reason: '',
                    reason_detailed: '',
                },
                isPopupLoading: false,
                filterModel: {
                    backlink_id: this.$route.query.backlink_id || '',
                    search: this.$route.query.search || '',
                    status: this.$route.query.status || '',
                    seller: this.$route.query.seller || '',
                    buyer: this.$route.query.buyer || '',
                    paginate: this.$route.query.paginate || '50',
                    article: this.$route.query.article || '',
                    in_charge: this.$route.query.in_charge || '',
                    country_id: this.$route.query.country_id || '',
                    process_date : {
                        startDate : '',
                        endDate: ''
                    },
                    date_completed: {
                        startDate: '',
                        endDate: ''
                    },
                    deleted_by_seller: this.$route.query.deleted_by_seller || '',
                },
                searchLoading: false,
                isLive: false,
                totalAmount: 0,
                isSearching: false,
                showReason: false,
                showReasonText: false,
                reasonIssue: [
                    'URL link to cannot be found',
                    'One-click ads URL',
                    'I am having technical issue',
                    'I am in vacation',
                    'Articles are no good',
                    'Other'
                ],
                reasonCancelled: [
                    'URL not available',
                    'URL sold',
                    'Not selling backlink',
                    'Other'
                ],
                listReason: '',

                issueCancelFilePreview: '',

                // for seller confirmation

                sellerConfirmationData: {},
                sellerConfirmationValues: {
                    data: null,
                    do_follow: null,
                    inc_article: null,
                    permanent_article: null
                },
                isDisabledUpdate: false,
            }
        },

        async created() {
            this.getListSales();
            this.getListSeller();
            this.getListBuyer();

            let countries = this.listCountryAll.data;
            if( countries.length === 0 ){
                this.getListCountries();
            }

            let in_charge = this.listIncharge.data;
            if( in_charge.length === 0 ){
                this.getTeamInCharge();
            }
        },

        computed: {
            ...mapState({
                tblOptions: state => state.storeFollowupSales.tblFollowupSalesOpt,
                listSales: state => state.storeFollowupSales.listSales,
                listBuyer: state => state.storeFollowupSales.listBuyer,
                listSeller: state => state.storeFollowupSales.listSeller,
                messageForms: state => state.storeFollowupSales.messageForms,
                user: state => state.storeAuth.currentUser,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listIncharge: state => state.storeAccount.listIncharge,
            }),

            isCancelledIssue() {
                let disabled = ['Canceled', 'Issue']

                return disabled.includes(this.updateModel.status) && this.user.isOurs === 1;
            },

            isShowConfirmButtonOrders() {
                let obj = this.sellerConfirmationValues
                let confirm = true;

                for (let key in obj) {
                    if (obj[key] === null || obj[key] === "") {
                        confirm = false;
                    }
                }

                if (confirm) {
                    for (let key in obj) {
                        if (obj[key] === false) {
                            confirm = false;
                        }
                    }
                }

                return confirm;
            },

            isShowCancelButtonOrders() {
                let obj = this.sellerConfirmationValues
                let cancel = true;

                for (let key in obj) {
                    if (obj[key] === null || obj[key] === "") {
                        cancel = false;
                    }
                }

                if (cancel) {
                    for (let key in obj) {
                        if (obj[key] === false) {
                            cancel = true;
                        }
                    }
                }

                return (cancel && !this.isShowConfirmButtonOrders);
            }
        },

        methods: {

            checkReason() {
                if(this.updateModel.reason == 'Other'){
                    this.showReasonText = true;
                } else {
                    this.showReasonText = false;
                }
            },

            checkStatus() {
                this.updateModel.reason = ''
                if(this.updateModel.status === 'Issue'){
                    this.showReason = true;
                    this.listReason = this.reasonIssue
                } else if(this.updateModel.status === 'Canceled'){
                    this.showReason = true;
                    this.listReason = this.reasonCancelled
                } else {
                    this.showReason = false;
                    this.showReasonText = false;
                }
            },

            async getTeamInCharge(){
                await this.$store.dispatch('actionGetTeamInCharge');
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async getListSales(params){

                // change the format of date
                this.filterModel.process_date = this.formatFilterDates(this.filterModel.process_date)
                this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)

                $('#tbl-followupsales').DataTable().destroy();

                this.searchLoading = true;
                this.isSearching = true;
                if(this.filterModel.paginate == 'All')
                {
                  await this.$store.dispatch('actionGetListSales', {
                        params: {
                            backlink_id: this.filterModel.backlink_id,
                            search: this.filterModel.search,
                            status: this.filterModel.status,
                            seller: this.filterModel.seller,
                            buyer: this.filterModel.buyer,
                            paginate: 1000000,
                            article: this.filterModel.article,
                            in_charge: this.filterModel.in_charge,
                            country_id: this.filterModel.country_id,
                            date_completed: this.filterModel.date_completed,
                            process_date: this.filterModel.process_date,
                            deleted_by_seller: this.filterModel.deleted_by_seller
                        }
                    });

                }else
                {
                    await this.$store.dispatch('actionGetListSales', {
                        params: {
                            backlink_id: this.filterModel.backlink_id,
                            search: this.filterModel.search,
                            status: this.filterModel.status,
                            seller: this.filterModel.seller,
                            buyer: this.filterModel.buyer,
                            paginate: this.filterModel.paginate,
                            article: this.filterModel.article,
                            in_charge: this.filterModel.in_charge,
                            country_id: this.filterModel.country_id,
                            date_completed: this.filterModel.date_completed,
                            process_date: this.filterModel.process_date,
                            deleted_by_seller: this.filterModel.deleted_by_seller
                        }

                    });
                }

                let columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10, width: "200px" },
                        { orderable: true, targets: 11, width: "200px" },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: false, targets: '_all' }
                    ];

                if( this.user.isOurs == 1 ){
                    columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8, width: "200px"},
                        { orderable: true, targets: 9, width: "200px" },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                $('#tbl-followupsales').DataTable({
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    columnDefs: columnDefs,
                    scrollX: '100%'
                });

                this.searchLoading = false;
                this.isSearching = false;

                this.getTotalAmount();
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let sales = this.listSales.data
                let total_price = [];
                let total = 0;
                sales.forEach(function(item, index){
                    if (item.publisher !==
                        null && typeof item.publisher.price
                        !==
                        'undefined') {
                        total_price.push( parseFloat(item.publisher.price))
                    }
                })

                if( total_price.length > 0 ){
                    total = total_price.reduce(this.calcSum)
                }
                this.totalAmount = total.toFixed(2);
            },

            calcSum(total, num) {
                return total + num
            },

            async getListBuyer(params) {
                await this.$store.dispatch('actionGetListBuyer', params);
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            redirectToArticle(id) {
                // this.$router.push({
                //     mode: 'history',
                //     name: 'articles',
                //     query: {
                //         id: id,
                //     },
                // });

                let routeData = this.$router.resolve({name: 'articles', query: {id: id}});
                window.open(routeData.href, '_blank');
            },

            doSearch() {
                $('#tbl-followupsales').DataTable().destroy();

                // change the format of date
                this.filterModel.process_date = this.formatFilterDates(this.filterModel.process_date)
                this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)

                this.$router.push({
                    query: this.filterModel,
                });

                this.getListSales({
                    params: {
                        backlink_id: this.filterModel.backlink_id,
                        search: this.filterModel.search,
                        status: this.filterModel.status,
                        seller: this.filterModel.seller,
                        buyer: this.filterModel.buyer,
                        paginate: this.filterModel.paginate,
                        article: this.filterModel.article,
                        country_id: this.filterModel.country_id,
                        in_charge: this.filterModel.in_charge,
                        process_date: this.filterModel.process_date,
                        date_completed: this.filterModel.date_completed,
                        deleted_by_seller: this.filterModel.deleted_by_seller
                    }
                });

                this.getTotalAmount();
            },

            clearSearch() {
                $('#tbl-followupsales').DataTable().destroy();

                this.filterModel = {
                    backlink_id: '',
                    search: '',
                    status: '',
                    seller: '',
                    buyer: '',
                    paginate: '50',
                    article: '',
                    in_charge: '',
                    country_id: '',
                    process_date : {
                        startDate : null,
                        endDate: null
                    },
                    date_completed: {
                        startDate: null,
                        endDate: null
                    },
                    deleted_by_seller: '',
                }

                this.getListSales({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            doShowIssueCancelFile(src) {
                this.issueCancelFilePreview = src;
            },

            doUpdate(sales) {
                this.clearMessageform()
                let that = JSON.parse( JSON.stringify(sales) )

                this.updateModel = that
                this.updateModel.url_publisher = that.publisher == null ? that.ext_domain.domain:that.publisher.url
                this.updateModel.article_id = that.article == null ? '':that.article.id
                this.updateModel.status_writer = that.article == null ? '':that.article.status_writer;

                // to display the Details of Issue/Cancelled field
                if(this.updateModel.reason == 'Other' && this.updateModel.reason != null ){
                    this.showReasonText = true;
                } else {
                    this.showReasonText = false;
                }

                // to display the Reason field
                if(this.updateModel.status === 'Issue'){
                    this.showReason = true;
                    this.listReason = this.reasonIssue
                } else if(this.updateModel.status === 'Canceled'){
                    this.showReason = true;
                    this.listReason = this.reasonCancelled
                } else {
                    this.showReason = false;
                    this.showReasonText = false;
                }


                this.isLive = false;
                if( that.status == 'Live' && !this.user.isAdmin){
                    this.isLive = true;
                }
            },

            async submitUpdate(params) {
                let self = this;
                let loader = this.$loading.show();
                self.isDisabledUpdate = true;

                // make form data for file
                let form_data = new FormData();

                for ( let key in this.updateModel ) {
                    // if value is null or undefined, make empty string
                    let val = (this.updateModel[key] == null || this.updateModel[key] == undefined)
                        ? ''
                        : this.updateModel[key];

                    form_data.append(key, val);
                }

                // append file on form data
                form_data.append('file', this.$refs.issue_file.files[0]);

                $('#tbl-followupsales').DataTable().destroy();
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateSales', form_data)
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated') {
                    $("#modal-view-issue-cancel-file").modal('hide')
                    $("#modal-update-sales").modal('hide')
                    this.getListSales()
                    this.$refs.issue_file.value = '';

                    loader.hide();

                    swal.fire(
                        self.$t('message.follow.alert_success'),
                        self.$t('message.follow.alert_updated_successfully'),
                        'success'
                    )
                }

                self.isDisabledUpdate = false;
                loader.hide();
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },

            confirmPendingSellerOrder(sales) {
                this.sellerConfirmationData = sales;

                this.resetConfirmationValues();
            },

            resetConfirmationValues() {
                this.sellerConfirmationValues = {
                    data: null,
                    do_follow: null,
                    inc_article: null,
                    permanent_article: null
                }
            },

            processPendingSellerOrder(id, process) {
                let self = this;
                let loader = this.$loading.show();

                axios.post('/api/process-pending-order', {
                    id: id,
                    process: process
                }).then((res) => {

                    this.resetConfirmationValues();

                    let orderModal = this.$refs.modalApprovePending
                    $(orderModal).modal('hide')

                    if (process === 'approve') {
                        swal.fire(
                            self.$t('message.follow.alert_success'),
                            self.$t('message.follow.alert_pending_order_confirmed'),
                            'success'
                        )
                    } else {
                        swal.fire(
                            self.$t('message.follow.alert_cancelled'),
                            self.$t('message.follow.alert_pending_order_cancelled'),
                            'error'
                        )
                    }

                    this.getListSales();

                    loader.hide();
                }).catch((err) => {
                    console.log(err)

                    swal.fire(
                        self.$t('message.follow.alert_error'),
                        self.$t('message.follow.alert_pending_order_error'),
                        'error'
                    )

                    loader.hide();
                })
            },

            generateArticle (sales) {
                let self = this;

                swal.fire({
                    title: 'Generate Article for backlink ID# ' + sales.id,
                    text: 'Confirming will generate an article and update list publisher data - inc article to "no"',
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: self.$t('message.publisher.yes'),
                    cancelButtonText: self.$t('message.publisher.no')
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let loader = self.$loading.show();

                        axios.post('/api/generate-article', sales)
                            .then((res) => {
                                loader.hide();

                                swal.fire(
                                    self.$t('message.tools.alert_success'),
                                    'Article successfully generated',
                                    'success'
                                )

                                this.getListSales();
                            })
                            .catch((err) => {
                                loader.hide();

                                swal.fire(
                                    self.$t('message.tools.alert_error'),
                                    'There were some errors while generating an article.',
                                    'error'
                                )
                            });
                    } else {

                    }
                });
            },

            updatePublisherIncArticle (sales) {
                let self = this;

                swal.fire({
                    title: 'Update list publisher data: include article for backlink ID# ' + sales.id,
                    text: 'Confirming will update list publisher ID# '
                        + sales.publisher_id
                        + ' data - include article to "no"',
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: self.$t('message.publisher.yes'),
                    cancelButtonText: self.$t('message.publisher.no')
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            let loader = self.$loading.show();

                            axios.post('/api/update-publisher-inc-article', {
                                id: sales.id,
                                publisher_id: sales.publisher_id
                            })
                            .then((res) => {
                                loader.hide();

                                swal.fire(
                                    self.$t('message.tools.alert_success'),
                                    'List publisher data: include article successfully updated.',
                                    'success'
                                )

                                this.sellerConfirmationData.publisher.inc_article = 'no';
                                this.getListSales();
                            })
                            .catch((err) => {
                                loader.hide();

                                swal.fire(
                                    self.$t('message.tools.alert_error'),
                                    'There were some errors while updating the list publisher data.',
                                    'error'
                                )
                            });
                        } else {

                        }
                    });
            }
        }
    }
</script>
