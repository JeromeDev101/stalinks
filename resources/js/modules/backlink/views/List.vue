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
                        <h3 class="card-title text-primary">{{ $t('message.follow_backlinks.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.follow_backlinks.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_search_backlinks') }}</label>
                                    <input
                                        v-model="fillter.backlink_id"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.follow_backlinks.type')">
                                </div>
                            </div>

                            <div class="col-md-3" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_seller') }}</label>
                                    <select class="form-control" v-model="fillter.seller">
                                        <option value="">{{ $t('message.follow_backlinks.all') }}</option>
                                        <option v-for="seller in listSeller.data" v-bind:value="seller.id">{{ seller.username }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_buyer') }}</label>
                                    <select class="form-control" v-model="fillter.buyer">
                                        <option value="">{{ $t('message.follow_backlinks.all') }}</option>
                                        <option v-for="option in listBuyerBought" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_search_url_pub') }}</label>
                                    <input
                                        v-model="fillter.querySearch"
                                        type="text"
                                        name="search"
                                        class="form-control"
                                        :placeholder="$t('message.follow_backlinks.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_search_url_ad') }}</label>
                                    <input v-model="fillter.url_advertiser" type="text" name="search" class="form-control" :placeholder="$t('message.follow_backlinks.type')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_status') }}</label>
                                    <v-select multiple
                                              v-model="fillter.status" :options="statusBaclink" :searchable="false" :placeholder="$t('message.follow_backlinks.all')"/>
                                    <!--                                <select class="form-control" v-model="fillter.status">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>-->
                                    <!--                                </select>-->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_process_date') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="fillter.process_date"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="fillter.process_date"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_date_completed') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="fillter.date_completed"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                            :dateRange="fillter.date_completed"
                                            :linkedCalendars="true"
                                            opens="left"
                                            style="width: 100%"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" v-if="listSubAccounts.length > 0">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.filter_user_buyer') }}</label>
                                    <select class="form-control" v-model="fillter.sub_buyer_id">
                                        <option value="">{{ $t('message.follow_backlinks.all') }}</option>
                                        <option v-for="buyer in listSubAccounts" v-bind:value="buyer.user_id">
                                            {{ buyer.username == null || buyer.username == '' ? buyer.name : buyer.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.follow_backlinks.clear') }}
                                </button>
                                <button @click="getBackLinkList()" type="submit" name="submit" class="btn btn-default" :disabled="isSearching">
                                    {{ $t('message.follow_backlinks.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.follow_backlinks.fub_title') }}</h3>
                        <div class="card-tools"></div>
                    </div>

                    <div class="card-body">

                        <div class="d-flex flex-row flex-nowrap overflow-auto bg-info mb-4 text-center rounded">
                            <div v-for="stat in statusSummary" class="col p-3">
                                {{ stat.status }}
                                <b>{{' ('+ stat.total +')' }}</b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-2">
                                <div class="input-group" v-if="user.isAdmin || user.registration.can_validate_backlink === 1 || user.registration.is_sub_account === 0">
                                    <button class="btn btn-default mr-2" @click="selectAll">
                                        {{
                                            allSelected
                                            ? $t('message.follow_backlinks.fub_deselect')
                                            : $t('message.follow_backlinks.fub_select')
                                        }}
                                        {{ $t('message.follow_backlinks.all') }}
                                    </button>

                                    <div class="dropdown mr-2">
                                        <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ $t('message.follow_backlinks.fub_selected_action') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item " @click="buyValidated">{{ $t('message.follow_backlinks.fub_buy') }}</a>
                                            <a class="dropdown-item " @click="UnInterestedValidated" >{{ $t('message.follow_backlinks.fub_uninterested') }}</a>
<!--                                            <a class="dropdown-item" @click="doMultipleDelete" href="#" v-if="user.isAdmin">{{ $t('message.follow_backlinks.delete') }}</a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 d-flex" :class="currentWindowWidth > 800 ? 'justify-content-end' : ''">
                                <button
                                    data-toggle="modal"
                                    data-target="#modal-setting-followup-backlinks"
                                    class="btn btn-default mr-2">
                                    <i class="fa fa-cog"></i>
                                </button>

                                <div v-if="Object.keys(listBackLink).length !== 0" class="mr-2">
                                    <download-csv
                                        style="margin-bottom: 0 !important;"
                                        :data="listBackLink.data"
                                        :fileds="data_filed"
                                        :nameFile="file_csv">
                                    </download-csv>
                                </div>

                                <select
                                    class="form-control w-25"
                                    v-model="fillter.paginate"
                                    style="height: 37px; min-width: 100px"

                                    @change="getBackLinkList">
                                    <option v-for="option in paginate" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <h5 class="d-inline float-right">{{ $t('message.follow_backlinks.fub_amount') }} $ {{ totalAmount }}</h5>

                        <span class="pagination-custom-footer-text">
                            <b v-if="fillter.paginate !== 'All'">
                                Showing {{ listBackLink.from }} to {{ listBackLink.to }} of {{ listBackLink.total }} entries.
                            </b>

                            <b v-else>
                                Showing all {{ listBackLink.total }} entries.
                            </b>
                        </span>

                        <div class="table-responsive">
                            <table id="tbl_backlink" class="table table-striped table-bordered" style="font-size: 0.75rem">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th v-if="user.isAdmin || user.registration.can_validate_backlink === 1 || user.registration.is_sub_account === 0"></th>
                                    <th v-show="tblFollowupBacklinksOpt.id_backlink">{{ $t('message.follow_backlinks.t_id_bck') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.seller" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">{{ $t('message.follow_backlinks.filter_seller') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.buyer">{{ $t('message.follow_backlinks.filter_user_buyer') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.url_publisher">{{ $t('message.follow_backlinks.t_url_pub') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.url_advertiser" v-if="user.isAdmin || user.role_id == 5 || user.role_id == 8">{{ $t('message.follow_backlinks.t_url_ad') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.link_from">{{ $t('message.follow_backlinks.t_link_from') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.link_to">{{ $t('message.follow_backlinks.t_link_to') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.price" v-if="user.isAdmin">{{ $t('message.follow_backlinks.t_price') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.prices">{{ $t('message.follow_backlinks.t_prices') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.code_comb" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">{{ $t('message.follow_backlinks.t_code_comb') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.code_price" v-if="user.isAdmin">{{ $t('message.follow_backlinks.t_code_price') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.price_basis" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">{{ $t('message.follow_backlinks.t_price_basis') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.anchor_text">{{ $t('message.follow_backlinks.t_anchor_text') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.date_for_process">{{ $t('message.follow_backlinks.t_date_process') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.date_completed">{{ $t('message.follow_backlinks.filter_date_completed') }}</th>
                                    <th v-show="tblFollowupBacklinksOpt.status">{{ $t('message.follow_backlinks.filter_status') }}</th>
                                    <th>{{ $t('message.follow_backlinks.t_action') }}</th>
                                </tr>
                                </thead>
                                <tbody v-show="!searchLoading">
                                    <tr v-for="(backLink, index) in listBackLink.data">
                                        <td class="center-content">{{ index + 1 }}</td>
                                        <td class="text-center" v-if="user.isAdmin || user.registration.can_validate_backlink === 1 || user.registration.is_sub_account === 0">
                                            <input type="checkbox"
                                            class="custom-checkbox"
                                            @change="checkSelected"
                                            :id="backLink.id"
                                            :value="backLink.id"
                                            :disabled="backLink.status !== 'To Be Validated' && !user.isAdmin"
                                            v-model="checkIds">
                                        </td>
                                        <td v-show="tblFollowupBacklinksOpt.id_backlink">{{ backLink.id }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.seller" v-if="(user.isOurs ==
                                     0 && !user.isAdmin) ||
                                     user.isAdmin">{{
                                                backLink.publisher == null ? 'N/A' : (backLink.publisher.user == null ? 'N/A' : (backLink.publisher.user.username == null ? backLink.publisher.user.name : backLink.publisher.user.username)) }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.buyer">{{backLink.user.username == null ? backLink.user.name : backLink.user.username}}</td>
                                        <td v-show="tblFollowupBacklinksOpt.url_publisher">
                                            <!--                                    {{ backLink.publisher == null ? 'N/A' : replaceCharacters(backLink.publisher.url) }}-->
                                            <span v-if="backLink.publisher == null">
                                            N/A
                                        </span>
                                            <span v-else>
                                            <a :href="'//' + replaceCharacters(backLink.publisher.url)" target="_blank">
                                                {{ replaceCharacters(backLink.publisher.url) }}
                                            </a>
                                        </span>
                                        </td>
                                        <td v-show="tblFollowupBacklinksOpt.url_advertiser" v-if="user.isAdmin || user.role_id == 5 || user.role_id == 8">
                                            <!--                                    {{ backLink.url_advertiser }}-->
                                            <span v-if="backLink.url_advertiser == null">
                                            N/A
                                        </span>
                                            <span v-else>
                                            <a :href="'//' + replaceCharacters(backLink.url_advertiser)" target="_blank">
                                                {{ backLink.url_advertiser }}
                                            </a>
                                        </span>
                                        </td>
                                        <td v-show="tblFollowupBacklinksOpt.link_from">
                                            <div class="dont-break-out">
                                                {{ backLink.link_from }}
                                            </div>
                                        </td>
                                        <td v-show="tblFollowupBacklinksOpt.link_to">
                                            <div class="dont-break-out">
                                                <a href="backLink.link">{{ backLink.link }}</a>
                                            </div>
                                        </td>
                                        <td v-show="tblFollowupBacklinksOpt.price" v-if="user.isAdmin">{{ backLink.price == null || backLink.price == '' ? 0:'$ '+ formatPrice(backLink.price) }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.prices">{{ backLink.prices == null || backLink.prices == '' ? 0:'$ ' + formatPrice(backLink.prices) }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.code_comb" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">{{ backLink.publisher == null ? 'N/A':backLink.publisher.code_comb }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.code_price" v-if="user.isAdmin">{{ backLink.publisher == null ? 'N/A':'$ '+backLink.publisher.code_price }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.price_basis" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">{{ backLink.publisher == null ? 'N/A':backLink.publisher.price_basis }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.anchor_text">{{ backLink.anchor_text }}</td>
                                        <!-- date_process before  -->
                                        <td v-show="tblFollowupBacklinksOpt.date_for_process">{{ backLink.created_at }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.date_completed">{{ backLink.live_date }}</td>
                                        <td v-show="tblFollowupBacklinksOpt.status">{{ backLink.status }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-default" @click="editBackLink(backLink)" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                            </div>
<!--                                            <div v-if="user.isAdmin" class="btn-group">-->
<!--                                                <button class="btn btn-default" @click="deleteBackLink(backLink.id, backLink.publisher.user.username, backLink.user.username)" title="Delete"><i class="fa fa-fw fa-trash"></i></button>-->
<!--                                            </div>-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal settings default Followup Backlink -->
        <div class="modal fade" id="modal-setting-followup-backlinks" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.follow_backlinks.sd_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.id_backlink ? 'checked':''" v-model="tblFollowupBacklinksOpt.id_backlink">{{ $t('message.follow_backlinks.sd_id_backlinks') }}</label>
                            </div>
                            <div v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.seller ? 'checked':''" v-model="tblFollowupBacklinksOpt.seller">{{ $t('message.follow_backlinks.filter_seller') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.buyer ? 'checked':''" v-model="tblFollowupBacklinksOpt.buyer">{{ $t('message.follow_backlinks.filter_buyer') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.url_publisher ? 'checked':''" v-model="tblFollowupBacklinksOpt.url_publisher">{{ $t('message.follow_backlinks.t_url_pub') }}</label>
                            </div>
                            <div v-if="user.isAdmin || user.role_id == 5 || user.role_id == 8" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.url_advertiser ? 'checked':''" v-model="tblFollowupBacklinksOpt.url_advertiser">{{ $t('message.follow_backlinks.t_url_ad') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.link_from ? 'checked':''" v-model="tblFollowupBacklinksOpt.link_from">{{ $t('message.follow_backlinks.t_link_from') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.link_to ? 'checked':''" v-model="tblFollowupBacklinksOpt.link_to">{{ $t('message.follow_backlinks.t_link_to') }}</label>
                            </div>
                            <div v-if="user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.price ? 'checked':''" v-model="tblFollowupBacklinksOpt.price">{{ $t('message.follow_backlinks.t_price') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.prices ? 'checked':''" v-model="tblFollowupBacklinksOpt.prices">{{ $t('message.follow_backlinks.t_prices') }}</label>
                            </div>
                            <div v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.code_comb ? 'checked':''" v-model="tblFollowupBacklinksOpt.code_comb">{{ $t('message.follow_backlinks.sd_code_comb') }}</label>
                            </div>
                            <div v-if="user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.code_price ? 'checked':''" v-model="tblFollowupBacklinksOpt.code_price">{{ $t('message.follow_backlinks.t_code_price') }}</label>
                            </div>
                            <div v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.price_basis ? 'checked':''" v-model="tblFollowupBacklinksOpt.price_basis">{{ $t('message.follow_backlinks.t_price_basis') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.anchor_text ? 'checked':''" v-model="tblFollowupBacklinksOpt.anchor_text">{{ $t('message.follow_backlinks.t_anchor_text') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.date_for_process ? 'checked':''" v-model="tblFollowupBacklinksOpt.date_for_process">{{ $t('message.follow_backlinks.t_date_process') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.date_completed ? 'checked':''" v-model="tblFollowupBacklinksOpt.date_completed">{{ $t('message.follow_backlinks.filter_date_completed') }}</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.status ? 'checked':''" v-model="tblFollowupBacklinksOpt.status">{{ $t('message.follow_backlinks.filter_status') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.follow_backlinks.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">
                            {{ $t('message.follow_backlinks.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--   Modal Edit Followup Backlink -->
        <div v-if="openModalBackLink" class="modal fade"  ref="modalEditBacklink" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.follow_backlinks.efb_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-6" v-show="user.role_id != 5">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.efb_seller_name') }}</label>
                                    <input type="text" v-model="modelBaclink.username" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.efb_date_processed') }}</label>
                                        <input type="date" :disabled="isBuyer || isPostingWriter || user.role_id == 8" v-model="modelBaclink.date_process" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_url_pub') }}</label>
                                    <input type="text" v-model="modelBaclink.ext_domain.domain" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.follow_backlinks.t_url_ad') }}</label>
                                    <input type="text" v-model="modelBaclink.url_advertiser"  :disabled="isPostingWriter || user.role_id == 8" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6" v-show="user.isAdmin">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.t_price') }}</label>
                                        <input type="number" v-model="modelBaclink.price" :disabled="isBuyer || isPostingWriter || modelBaclink.status == 'Live' || user.role_id == 8" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="priceErr in messageBacklinkForms.errors.price" class="text-danger">{{ priceErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.t_prices') }}</label>
                                        <input type="number" v-model="modelBaclink.prices" :disabled="true" class="form-control" value="" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.title}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.efb_ip_title') }}</label>

                                        <input type="text" v-model="modelBaclink.title" class="form-control"  :disabled="user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.title" v-for="titleErr in messageBacklinkForms.errors.title" class="text-danger">{{ titleErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.anchor_text}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.t_anchor_text') }}</label>
                                        <input type="text" v-model="modelBaclink.anchor_text" :disabled="user.role_id == 8" class="form-control" required="required" >
                                        <span v-if="messageBacklinkForms.errors.anchor_text" v-for="anchorTextErr in messageBacklinkForms.errors.anchor_text" class="text-danger">{{ anchorTextErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link_from}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.t_link_from') }}</label>
                                        <input type="text" v-model="modelBaclink.link_from" class="form-control" :disabled="true">
                                        <span v-if="messageBacklinkForms.errors.link_from" v-for="linkFromErr in messageBacklinkForms.errors.link_from" class="text-danger">{{ linkFromErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.t_link_to') }}</label>

                                        <input type="text" v-model="modelBaclink.link" class="form-control" :disabled="isPostingWriter || user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.link" v-for="linkToErr in messageBacklinkForms.errors.link" class="text-danger">{{ linkToErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.live_date}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.filter_date_completed') }}</label>
                                        <input type="date" v-model="modelBaclink.live_date" class="form-control" :disabled="isBuyer || user.role_id == 8">
                                        <span v-if="messageBacklinkForms.errors.live_date" v-for="dateCompletedErr in messageBacklinkForms.errors.live_date" class="text-danger">{{ dateCompletedErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.filter_status') }}</label>
                                        <select  class="form-control pull-right" v-model="modelBaclink.status" style="height: 37px;" :disabled="isBuyer || user.role_id == 8">
                                            <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>
                                        </select>
                                        <span v-if="messageBacklinkForms.errors.status" v-for="statusErr in messageBacklinkForms.errors.status" class="text-danger">{{ statusErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="withArticle">
                                <div class="form-group">
                                    <div>
                                        <label>{{ $t('message.follow_backlinks.efb_article_id') }}</label>
                                        <input type="text" class="form-control" v-model="modelBaclink.id_article" :disabled="true ">
                                        <!-- <input type="text" class="form-control" :disabled="isBuyer || isPostingWriter"> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="modelBaclink.status === 'To Be Validated'">
                                <div class="row">
                                    <button class="btn btn-danger col mr-2" @click.prevent="uninterest">
                                        {{ $t('message.follow_backlinks.efb_uninterested') }}
                                    </button>
                                    <button
                                        v-if="user.isAdmin || user.registration.can_validate_backlink === 1 || user.registration.is_sub_account === 0"
                                        class="btn btn-success col"

                                        @click.prevent="buy">

                                        {{ $t('message.follow_backlinks.buy') }}
                                    </button>
                                </div>
                            </div>


                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">
                            {{ $t('message.follow_backlinks.close') }}
                        </button>
                        <button type="button" :disabled="checkSelectIntDomain" @click="submitEditBacklink" class="btn btn-primary">
                            {{ $t('message.follow_backlinks.save') }}
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->
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
    import Hepler from '@/library/Helper';
    import { mapState, mapSetter } from 'vuex';
    import { Domain } from 'domain';
    import DownloadCsv from '@/components/export-csv/Csv.vue'
    import _ from 'lodash'
    import axios from 'axios';
    import {buyerAccess} from "../../../mixins/buyerAccess";
    import {dateRange} from "../../../mixins/dateRange";

    export default {
        name: 'BackLinkList',
        mixins: [buyerAccess, dateRange],
        data() {
            return {
                paginate: [50,150,250,350,500,'All'],
                file_csv: 'baclink.xls',
                statusBaclink: ['To Be Validated', 'Pending', 'Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live', 'Live in Process',  'Issue', 'Canceled'],
                data_filed: {
                    'URL Publisher': 'publisher.url',
                    'URL Advertiser': 'url_advertiser',
                    'Link From': 'link_from',
                    'Link To': 'link',
                    'Price': 'prices',
                    'Anchor Text': 'anchor_text',
                    'Date Completed': 'live_date',
                    'Status': 'status'
                },
                json_meta: [
                    [{
                    'key': 'charset',
                    'value': 'utf-8'
                    }]
                ],
                page: this.$route.query.page || 1,
                modalAddBackLink: false,
                modelBaclink: {
                    ext_domain: {
                        domain: ''
                    },
                    int_domain: {
                        domain: ''
                    },
                    user: {
                        name: ''
                    }
                },
                loadIntDomain: false,
                isPopupLoading: false,
                isBuyer: false,
                isPostingWriter: false,
                searchLoading: false,
                withArticle: true,
                totalAmount: 0,
                isSearching: false,
                listSubAccounts: [],
                updateFormula: {},
                statusSummary: [],
                checkIds: [],
                allSelected: false,
                isDisabled: true,

                currentWindowWidth: window.innerWidth
            }
        },
        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            await this.getBackLinkList();
            this.checkAccountType();
            this.getSellerList();
            this.getBuyerList();
            this.getBuyerBoughtList();
            this.getSubAccount();
            this.getFormula();
            this.getSummaryStatus();

            window.addEventListener("resize", this.resizeEventHandler);
        },

        destroyed() {
            window.removeEventListener("resize", this.resizeEventHandler);
        },

        computed: {

            ...mapState({
                listBackLink: state => state.storeBackLink.listBackLink,
                tblFollowupBacklinksOpt: state => state.storeBackLink.tblFollowupBacklinksOpt,
                fillter: state => state.storeBackLink.fillter,
                user: state => state.storeAuth.currentUser,
                messageBacklinkForms: state => state.storeBackLink.messageBacklinkForms,
                listSeller: state => state.storeAccount.listAccount,
                listBuyer: state => state.storeFollowupSales.listBuyer,
                listBuyerBought: state => state.storeBackLink.listBuyerBought,
                listBuy : state => state.storeBuy.listBuy,
                messageForms : state => state.storeBuy.messageForms,
                formula: state => state.storeSystem.formula,
            }),

            openModalBackLink() {
            if (this.modalAddBackLink = true) {
                return true
            }

            return false
            },

            checkSelectIntDomain () {
            if (this.modelBaclink.int_domain_id == 0) {
                return true
            }

            return false
            },
        },

        mounted() {
            $(this.$refs.modalEditBacklink).on("hidden.bs.modal", this.handleCloseBacklinkModal)
        },

        methods: {

            resizeEventHandler(e) {
                this.currentWindowWidth = window.innerWidth;
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            UnInterestedValidated() {
                let self = this;

                let ctr = 0;
                for(var index in this.checkIds) {
                    if(this.listBackLink.data[index].status !== 'To Be Validated') {
                        ctr++;
                    }
                }

                // check if selected is none
                if(this.checkIds.length === 0) {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_invalid'),
                        self.$t('message.follow_backlinks.alert_selection_empty'),
                        'warning'
                    )
                    return false;
                }

                // check if status is not 'To Be Validated'
                if(ctr > 0) {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_invalid'),
                        self.$t('message.follow_backlinks.alert_selection_note'),
                        'warning'
                    )
                    return false;
                }

                axios.post('/api/buy-uninterested-multiple', {
                    ids: this.checkIds
                }).then((response) => {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_success'),
                        self.$t('message.follow_backlinks.alert_remove_success'),
                        'success'
                    )

                    this.getBackLinkList();

                    this.checkIds = [];
                }).catch((error) => {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_error'),
                        self.$t('message.follow_backlinks.alert_error_request'),
                        'error'
                    )
                });
            },

            buyValidated() {
                let self = this;
                let ctr = 0;
                for(var index in this.checkIds) {
                    if(this.listBackLink.data[index].status !== 'To Be Validated') {
                        ctr++;
                    }
                }

                // check if selected is none
                if(this.checkIds.length === 0) {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_invalid'),
                        self.$t('message.follow_backlinks.alert_selection_empty'),
                        'warning'
                    )
                    return false;
                }

                // check if status is not 'To Be Validated'
                if(ctr > 0) {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_invalid'),
                        self.$t('message.follow_backlinks.alert_selection_note'),
                        'warning'
                    )
                    return false;
                }

                // loop buy
                for(var index in this.checkIds) {
                    this.buyMultiple(this.listBackLink.data[index])
                }


                swal.fire(
                    self.$t('message.follow_backlinks.alert_bought'),
                    self.$t('message.follow_backlinks.alert_bought_successfully'),
                    'success'
                );

                this.getBackLinkList();
                this.checkIds = [];
            },

            async buyMultiple(data) {

                data.credit_left = parseInt(this.listBuy.credit);

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateBuy', data);
                this.isPopupLoading = false;

            },

            doMultipleDelete() {
                let self = this;

                swal.fire({
                    title: self.$t('message.follow_backlinks.alert_confirm'),
                    html: self.$t('message.follow_backlinks.alert_delete') + " <br><br> <span class='text-danger'>" + self.$t('message.follow_backlinks.alert_delete_note') + "</span> <br>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: self.$t('message.follow_backlinks.delete_sentence'),
                    cancelButtonText: self.$t('message.follow_backlinks.keep')
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        axios.post('/api/delete-backlinks-multiple', {
                            ids:this.checkIds
                        })
                        .then(response => response)
                        .catch(error => error);

                        this.getBackLinkList();

                        swal.fire(
                            self.$t('message.follow_backlinks.alert_deleted'),
                            self.$t('message.follow_backlinks.alert_selected_deleted'),
                            'success'
                        )
                    }
                });
            },

            selectAll() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var index in this.listBackLink.data) {
                        if(this.listBackLink.data[index].status === 'To Be Validated' && !this.user.isAdmin) {
                            this.checkIds.push(this.listBackLink.data[index].id);
                        }

                        if(this.user.isAdmin) {
                            this.checkIds.push(this.listBackLink.data[index].id);
                        }
                    }
                    this.isDisabled = false;
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                    this.isDisabled = true;
                }
            },

            async getFormula() {
                await this.$store.dispatch('actionGetFormula');
                this.updateFormula = this.formula.data[0];
            },

            percentage(percent, total) {
                return ((percent/ 100) * total).toFixed(2)
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
                return val;
            },

            computePriceStalinks(price, article) {
                let selling_price = price
                let percent = parseFloat(this.formula.data[0].percentage);
                let additional = parseFloat(this.formula.data[0].additional);

                let commission = 'yes';

                if( price != '' && price != null ){ // check if price has a value

                        if( article == 'Yes' ){ //check if with article

                            // if( commission == 'no' ){
                            //     selling_price = price
                            // }

                            if( commission == 'yes' ){
                                let percentage = this.percentage(percent, price)
                                selling_price = parseFloat(percentage) + parseFloat(price)
                            }
                        }

                        if( article == 'No' ){ //check if without article

                            // if( commission == 'no' ){
                            //     selling_price = parseFloat(price) + additional
                            // }

                            if( commission == 'yes' ){
                                let percentage = this.percentage(percent, price)
                                selling_price = parseFloat(percentage) + parseFloat(price) + additional
                            }

                        }

                }

                selling_price = parseFloat(selling_price).toFixed(0);

                return selling_price;
            },

            getSummaryStatus() {
                axios.get('/api/backlinks-summary-status')
                .then((res)=> {
                    let _res = res.data
                    for(let index in this.statusBaclink) {
                        let _result = _res.find( ({ status }) => status === this.statusBaclink[index] );
                        let data = {
                            "status": this.statusBaclink[index],
                            "total": (typeof(_result) != "undefined") ? _result.total:0
                        }

                        this.statusSummary.push(data)
                    }
                })
            },

            getSubAccount() {
                axios.get('/api/sub-account',{
                    params: {
                        team_in_charge: this.user.id
                    }
                })
                .then((res)=> {
                    this.listSubAccounts = res.data
                })
            },

            getBackLinkList: _.debounce(async function(page) {
                $("#tbl_backlink").DataTable().destroy();

                // change the format of date
                this.fillter.process_date = this.formatFilterDates(this.fillter.process_date)
                this.fillter.date_completed = this.formatFilterDates(this.fillter.date_completed)

                if (page) {
                    this.page = page
                }
                this.$router.replace({
                        query: {
                        page: this.page,
                    },
                }).catch(err => {})

                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetBackLink', {
                    vue: this,
                    page: this.page,
                    params: this.fillter,
                });

                // let columnDefs = [
                //     { orderable: true, targets: 0 },
                //     { orderable: true, targets: 1 },
                //     { orderable: true, targets: 2 },
                //     { orderable: true, targets: 3 },
                //     { orderable: true, targets: 4 },
                //     { orderable: true, targets: 5 },
                //     { orderable: true, targets: 6, width: "200px" },
                //     { orderable: true, targets: 7 },
                //     { orderable: true, targets: 8 },
                //     { orderable: true, targets: 9 },
                //     { orderable: true, targets: 10 },
                //     { orderable: true, targets: 11 },
                //     { orderable: true, targets: 12 },
                //     { orderable: true, targets: 13 },
                //     { orderable: true, targets: 14 },
                //     { orderable: false, targets: '_all' }
                // ];

                // if (this.user.isOurs == 1){
                //     columnDefs = [
                //         { orderable: true, targets: 0 },
                //         { orderable: true, targets: 1 },
                //         { orderable: true, targets: 2 },
                //         { orderable: true, targets: 3 },
                //         { orderable: true, targets: 4, width: "200px" },
                //         { orderable: true, targets: 5, width: "200px" },
                //         { orderable: true, targets: 6 },
                //         { orderable: true, targets: 7 },
                //         { orderable: true, targets: 8 },
                //         { orderable: true, targets: 9 },
                //         { orderable: true, targets: 10 },
                //         { orderable: false, targets: '_all' }
                //     ];
                // }

                $("#tbl_backlink").DataTable({
                    paging: false,
                    searching: false,
                    // columnDefs: columnDefs,
                });

                this.searchLoading = false;
                this.isSearching = false;

                this.getTotalAmount()

            }, 200),

            deleteBackLink(id, seller, buyer) {
                let self = this;

                swal.fire({
                    title: "Are you sure ?",
                    html: "<b>" + self.$t('message.follow_backlinks.sd_id_backlinks') + ": </b>" + id + " <br> <b>"
                        + self.$t('message.follow_backlinks.filter_seller') + ": </b>" + seller
                        + " <br> <b>" + self.$t('message.follow_backlinks.filter_buyer') + ": </b>" + buyer
                        + "<br><br>" + self.$t('message.follow_backlinks.alert_delete_note') + "<br>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: self.$t('message.follow_backlinks.delete_sentence'),
                    cancelButtonText: self.$t('message.follow_backlinks.keep')
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        axios.post('/api/delete-backlinks', {
                            id:id
                        })
                        .then(response => response)
                        .catch(error => error);

                        this.getBackLinkList();

                        swal.fire(
                            self.$t('message.follow_backlinks.alert_deleted'),
                            self.$t('message.follow_backlinks.alert_deleted_successfully'),
                            'success'
                        )
                    }
                });

            },

            async getSellerList(params) {
                await this.$store.dispatch('actionGetSeller');
            },

            async getBuyerList(params) {
                await this.$store.dispatch('actionGetListBuyer');
            },

            async getBuyerBoughtList(params) {
                await this.$store.dispatch('actionGetListBuyerBought');
            },

            async clearSearch() {
                await this.$store.dispatch('actionResetFillterBacklink');
                this.fillter.status = ''
                this.fillter.paginate = '50'

                this.fillter.process_date = {
                    startDate: null,
                    endDate: null
                }

                this.fillter.date_completed = {
                    startDate: null,
                    endDate: null
                }

                this.getBackLinkList();
            },

            checkArray(array) {
                return Hepler.arrayNotEmpty(array);
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let incomes = this.listBackLink.data;

                this.totalAmount = _.sumBy(incomes, function
                    (o) {
                    return parseFloat(o.prices);
                }).toFixed(0);
            },
            //
            // calcSum(total, num) {
            //     return total + num
            // },

            checkAccountType() {
                let that = this.user

                if( that.user_type ){
                    if( that.user_type.type == 'Buyer' ){
                        this.isBuyer = true;
                    }
                }

                if( that.role.id == 4 ){
                    this.isPostingWriter = true;
                }
            },

            editBackLink(baclink) {
                this.modalAddBackLink = true
                let that = Object.assign({}, baclink)

                this.withArticle = that.publisher == null ? false : that.publisher.inc_article == "No" ? true : false;
                this.modelBaclink.id = that.id
                this.modelBaclink.publisher_id = that.publisher == null ? null : that.publisher.id
                this.modelBaclink.ext_domain.domain = that.publisher == null ? 'N/A' : that.publisher.url
                this.modelBaclink.int_domain.domain = that.int_domain == null ? '':that.int_domain.domain
                this.modelBaclink.username = that.publisher == null ? 'N/A'
                    : (that.publisher.user == null
                        ? 'N/A'
                        : (that.publisher.user.username == null
                            ? that.publisher.user.name
                            : that.publisher.user.username))
                this.modelBaclink.anchor_text = that.anchor_text
                this.modelBaclink.price = that.price
                this.modelBaclink.prices = that.prices
                this.modelBaclink.link = that.link
                this.modelBaclink.link_from = that.link_from
                this.modelBaclink.live_date = that.live_date
                this.modelBaclink.title = that.title
                this.modelBaclink.status = that.status
                this.modelBaclink.user_id = that.user_id
                this.modelBaclink.date_process = that.date_process
                this.modelBaclink.url_advertiser = that.url_advertiser

                this.modelBaclink.seller = that.publisher == null ? 'N/A'
                    : (that.publisher.user == null
                        ? 'N/A'
                        : (that.publisher.user.username == null
                            ? that.publisher.user.name
                            : that.publisher.user.username))
                this.modelBaclink.id_article = that.article == null ? '':that.article.id

                $(this.$refs.modalEditBacklink).modal('show')
            },

            closeModalBacklink() {
                this.modalAddBackLink = false
                let element = this.$refs.modalEditBacklink
                $(element).modal('hide')
            },

            async submitEditBacklink () {
                await this.$store.dispatch('actionSaveBacklink', {
                    params: this.modelBaclink
                })

                if (this.messageBacklinkForms.action === 'saved_backlink') {
                    this.closeModalBacklink()
                    this.getBackLinkList()
                }
            },

            handleCloseBacklinkModal () {
                this.modalAddBackLink =  false
                this.$store.dispatch('clearMessageBacklinkForm')
            },

            convertPrice(price) {
                return parseFloat(price).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            },

            async buy() {
                let self = this;
                this.modelBaclink.credit_left = parseInt(this.listBuy.credit);

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateBuy', this.modelBaclink);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated') {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_bought'),
                        self.$t('message.follow_backlinks.alert_bought_successfully'),
                        'success'
                    );

                    this.closeModalBacklink()

                    // $(this.$refs.modalEditBacklink).hide()

                    this.getBackLinkList();
                    this.$root.$refs.AppHeader.liveGetWallet()
                }
            },

            uninterest() {
                let self = this;
                axios.post('/api/buy-uninterested', {
                    backlink_id : this.modelBaclink.id,
                    publisher_id : this.modelBaclink.publisher_id
                }).then((response) => {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_success'),
                        self.$t('message.follow_backlinks.alert_remove_success'),
                        'success'
                    )

                    this.closeModalBacklink()

                    this.getBackLinkList();
                }).catch((error) => {
                    swal.fire(
                        self.$t('message.follow_backlinks.alert_error'),
                        self.$t('message.follow_backlinks.alert_error_request'),
                        'error'
                    )
                });
            },
        },

        components: {
            DownloadCsv
        }
}
</script>
