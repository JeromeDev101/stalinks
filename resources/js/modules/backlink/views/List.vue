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
                        <h3 class="card-title text-primary">Filter</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> Show Filter
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search ID Backlink</label>
                                    <input v-model="fillter.backlink_id" type="text" class="form-control" placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                                <div class="form-group">
                                    <label for="">Seller</label>
                                    <select class="form-control" v-model="fillter.seller">
                                        <option value="">All</option>
                                        <option v-for="seller in listSeller.data" v-bind:value="seller.id">{{ seller.username }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">
                                <div class="form-group">
                                    <label for="">Buyer</label>
                                    <select class="form-control" v-model="fillter.buyer">
                                        <option value="">All</option>
                                        <option v-for="option in listBuyerBought" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search URL Publisher</label>
                                    <input v-model="fillter.querySearch" type="text" name="search" class="form-control" placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search URL Advertiser</label>
                                    <input v-model="fillter.url_advertiser" type="text" name="search" class="form-control" placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <v-select multiple
                                              v-model="fillter.status" :options="statusBaclink" :searchable="false" placeholder="All"/>
                                    <!--                                <select class="form-control" v-model="fillter.status">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option v-for="status in statusBaclink" v-bind:value="status">{{ status }}</option>-->
                                    <!--                                </select>-->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Process Date
                                    </label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="fillter.process_date"
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
                                    <label for="">Date Completed
                                    </label>
                                    <div class="input-group">
                                        <date-range-picker
                                            ref="picker"
                                            v-model="fillter.date_completed"
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
                                    <label for="">User Buyer</label>
                                    <select class="form-control" v-model="fillter.sub_buyer_id">
                                        <option value="">All</option>
                                        <option v-for="buyer in listSubAccounts" v-bind:value="buyer.user_id">{{ buyer.username == null || buyer.username == '' ? buyer.name : buyer.username }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                                <button @click="getBackLinkList()" type="submit" name="submit" class="btn btn-default" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                <div class="card card-outline card-secondary">

                    <div class="card-header py-2">

                        <table width="100%" style="font-size: 0.85rem">
                            <tr>
                                <td>
                                    <table class="bg-info">
                                        <tr>
                                            <td v-for="stat in statusSummary" class="p-3">
                                                {{ stat.status }}
                                                <b>{{' ('+ stat.total +')' }}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="card-header">
                        <h3 class="card-title text-primary">Follow up Backlinks</h3>
                        <div class="card-tools"></div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select class="form-control float-right"
                                            @change="getBackLinkList"
                                            v-model="fillter.paginate"
                                            style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>

                                <div v-if="Object.keys(listBackLink).length !== 0" class="float-right mr-3">
                                    <download-csv
                                        :data="listBackLink.data"
                                        :fileds="data_filed"
                                        :nameFile="file_csv">
                                    </download-csv>
                                </div>

                                <button
                                    data-toggle="modal"
                                    data-target="#modal-setting-followup-backlinks"
                                    class="btn btn-default float-right mr-3">

                                    <i class="fa fa-cog"></i>
                                </button>

                            </div>
                        </div>

                        <h5 class="d-inline float-right">Amount: $ {{ totalAmount }}</h5>

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
                                    <th v-show="tblFollowupBacklinksOpt.id_backlink">ID Bck</th>
                                    <th v-show="tblFollowupBacklinksOpt.seller" v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin">Seller</th>
                                    <th v-show="tblFollowupBacklinksOpt.buyer">User Buyer</th>
                                    <th v-show="tblFollowupBacklinksOpt.url_publisher">URL Publisher</th>
                                    <th v-show="tblFollowupBacklinksOpt.url_advertiser" v-if="user.isAdmin || user.role_id == 5 || user.role_id == 8">URL Advertiser</th>
                                    <th v-show="tblFollowupBacklinksOpt.link_from">Link From</th>
                                    <th v-show="tblFollowupBacklinksOpt.link_to">Link To</th>
                                    <th v-show="tblFollowupBacklinksOpt.price" v-if="user.isAdmin">Price</th>
                                    <th v-show="tblFollowupBacklinksOpt.prices">Prices</th>
                                    <th v-show="tblFollowupBacklinksOpt.code_comb" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">Code Comb</th>
                                    <th v-show="tblFollowupBacklinksOpt.code_price" v-if="user.isAdmin">Code Price</th>
                                    <th v-show="tblFollowupBacklinksOpt.price_basis" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">Price Basis</th>
                                    <th v-show="tblFollowupBacklinksOpt.anchor_text">Anchor Text</th>
                                    <th v-show="tblFollowupBacklinksOpt.date_for_process">Date for Proccess</th>
                                    <th v-show="tblFollowupBacklinksOpt.date_completed">Date Completed</th>
                                    <th v-show="tblFollowupBacklinksOpt.status">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody v-show="!searchLoading">
                                    <tr v-for="(backLink, index) in listBackLink.data">
                                        <td class="center-content">{{ index + 1 }}</td>
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
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-default" @click="editBackLink(backLink)" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                            </div>
                                            <div v-if="user.isAdmin" class="btn-group">
                                                <button class="btn btn-default" @click="deleteBackLink(backLink.id, backLink.publisher.user.username, backLink.user.username)" title="Delete"><i class="fa fa-fw fa-trash"></i></button>
                                            </div>
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
                        <h4 class="modal-title">Setting Default</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.id_backlink ? 'checked':''" v-model="tblFollowupBacklinksOpt.id_backlink">ID backlinks</label>
                            </div>
                            <div v-if="(user.isOurs == 0 && !user.isAdmin) || user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.seller ? 'checked':''" v-model="tblFollowupBacklinksOpt.seller">Seller</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.buyer ? 'checked':''" v-model="tblFollowupBacklinksOpt.buyer">Buyer</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.url_publisher ? 'checked':''" v-model="tblFollowupBacklinksOpt.url_publisher">URL Publisher</label>
                            </div>
                            <div v-if="user.isAdmin || user.role_id == 5 || user.role_id == 8" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.url_advertiser ? 'checked':''" v-model="tblFollowupBacklinksOpt.url_advertiser">URL Advertiser</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.link_from ? 'checked':''" v-model="tblFollowupBacklinksOpt.link_from">Link From</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.link_to ? 'checked':''" v-model="tblFollowupBacklinksOpt.link_to">Link To</label>
                            </div>
                            <div v-if="user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.price ? 'checked':''" v-model="tblFollowupBacklinksOpt.price">Price</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.prices ? 'checked':''" v-model="tblFollowupBacklinksOpt.prices">Prices</label>
                            </div>
                            <div v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.code_comb ? 'checked':''" v-model="tblFollowupBacklinksOpt.code_comb">Code combination</label>
                            </div>
                            <div v-if="user.isAdmin" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.code_price ? 'checked':''" v-model="tblFollowupBacklinksOpt.code_price">Code Price</label>
                            </div>
                            <div v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)" class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.price_basis ? 'checked':''" v-model="tblFollowupBacklinksOpt.price_basis">Price Basis</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.anchor_text ? 'checked':''" v-model="tblFollowupBacklinksOpt.anchor_text">Anchor Text</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.date_for_process ? 'checked':''" v-model="tblFollowupBacklinksOpt.date_for_process">Date for Process</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.date_completed ? 'checked':''" v-model="tblFollowupBacklinksOpt.date_completed">Date Completed</label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tblFollowupBacklinksOpt.status ? 'checked':''" v-model="tblFollowupBacklinksOpt.status">Status</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!--   Modal Edit Followup Backlink -->
        <div v-if="openModalBackLink" class="modal fade"  ref="modalEditBacklink" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Follow up Backlinks Information</h4>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">

                            <div class="col-md-6" v-show="user.role_id != 5">
                                <div class="form-group">
                                    <label>Seller name</label>
                                    <input type="text" v-model="modelBaclink.username" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Date Processed</label>
                                        <input type="date" :disabled="isBuyer || isPostingWriter || user.role_id == 8" v-model="modelBaclink.date_process" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label>URL Publisher</label>
                                    <input type="text" v-model="modelBaclink.ext_domain.domain" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL Advertiser</label>
                                    <input type="text" v-model="modelBaclink.url_advertiser"  :disabled="isPostingWriter || user.role_id == 8" class="form-control" required="required" >
                                </div>
                            </div>

                            <div class="col-md-6" v-show="user.isAdmin">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label>Price</label>
                                        <input type="number" v-model="modelBaclink.price" :disabled="isBuyer || isPostingWriter || modelBaclink.status == 'Live' || user.role_id == 8" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="priceErr in messageBacklinkForms.errors.price" class="text-danger">{{ priceErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label>Prices</label>
                                        <input type="number" v-model="modelBaclink.prices" :disabled="true" class="form-control" value="" required="required" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.title}" class="form-group">
                                    <div>
                                        <label>Title</label>

                                        <input type="text" v-model="modelBaclink.title" class="form-control"  :disabled="user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.title" v-for="titleErr in messageBacklinkForms.errors.title" class="text-danger">{{ titleErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.anchor_text}" class="form-group">
                                    <div>
                                        <label>Anchor text</label>
                                        <input type="text" v-model="modelBaclink.anchor_text" :disabled="user.role_id == 8" class="form-control" required="required" >
                                        <span v-if="messageBacklinkForms.errors.anchor_text" v-for="anchorTextErr in messageBacklinkForms.errors.anchor_text" class="text-danger">{{ anchorTextErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link_from}" class="form-group">
                                    <div>
                                        <label>Link From</label>
                                        <input type="text" v-model="modelBaclink.link_from" class="form-control" :disabled="true">
                                        <span v-if="messageBacklinkForms.errors.link_from" v-for="linkFromErr in messageBacklinkForms.errors.link_from" class="text-danger">{{ linkFromErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.link}" class="form-group">
                                    <div>
                                        <label>Link To</label>

                                        <input type="text" v-model="modelBaclink.link" class="form-control" :disabled="isPostingWriter || user.role_id == 8" required="required" >
                                        <span v-if="messageBacklinkForms.errors.link" v-for="linkToErr in messageBacklinkForms.errors.link" class="text-danger">{{ linkToErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.live_date}" class="form-group">
                                    <div>
                                        <label>Date Completed</label>
                                        <input type="date" v-model="modelBaclink.live_date" class="form-control" :disabled="isBuyer || user.role_id == 8">
                                        <span v-if="messageBacklinkForms.errors.live_date" v-for="dateCompletedErr in messageBacklinkForms.errors.live_date" class="text-danger">{{ dateCompletedErr }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" class="form-group">
                                    <div>
                                        <label>Status</label>
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
                                        <label>Article ID</label>
                                        <input type="text" class="form-control" v-model="modelBaclink.id_article" :disabled="true ">
                                        <!-- <input type="text" class="form-control" :disabled="isBuyer || isPostingWriter"> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="modelBaclink.status === 'To Be Validated'">
                                <div class="row">
                                    <button class="btn btn-danger col mr-2" @click.prevent="uninterest">Un-Interested
                                    </button>
                                    <button v-if="user.isAdmin || user.registration.can_validate_backlink === 1 || user.registration.is_sub_account === 0"
                                            class="btn btn-success col" @click.prevent="buy">Buy
                                    </button>
                                </div>
                            </div>


                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">Close</button>
                        <button type="button" :disabled="checkSelectIntDomain" @click="submitEditBacklink" class="btn btn-primary">Save</button>
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

    export default {
        name: 'BackLinkList',
        mixins: [buyerAccess],
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
                swal.fire({
                    title: "Are you sure ?",
                    html: "<b>ID Backlink: </b>" + id + " <br> <b>Seller: </b>" + seller + " <br> <b>Buyer: </b>" + buyer + "<br><br> Articles is also included to delete, Do you want to continue? <br>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
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
                            'Deleted!',
                            'Backlinks is already deleted.',
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
                this.modelBaclink.credit_left = parseInt(this.listBuy.credit);

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateBuy', this.modelBaclink);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated') {
                    swal.fire(
                        'Bought Successfully!',
                        'Backlink bought successfully.',
                        'success'
                    );

                    this.closeModalBacklink()

                    // $(this.$refs.modalEditBacklink).hide()

                    this.getBackLinkList();
                    this.$root.$refs.AppHeader.liveGetWallet()
                }
            },

            uninterest() {
                axios.post('/api/buy-uninterested', {
                    backlink_id : this.modelBaclink.id,
                    publisher_id : this.modelBaclink.publisher_id
                }).then((response) => {
                    swal.fire(
                        'Success!',
                        'Successfully removed interest on backlink.',
                        'success'
                    )

                    this.closeModalBacklink()

                    this.getBackLinkList();
                }).catch((error) => {
                    swal.fire(
                        'Error!',
                        'There was an error with your request.',
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
