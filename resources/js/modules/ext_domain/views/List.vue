<template>
    <div class="row">

        <!-- <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ listExt.total }}</h3>
                    <p>Total External Domains</p>
                </div>
                <div class="icon">
                    <i class="fa fa-external-link"></i>
                </div>
            </div>
        </div> -->

        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>
                <div class="box-body col-md-10 offset-md-1 relative">
                    <div class="row">

                        <div v-if="tableShow.country" class="col-md-2">
                            <div class="form-group">
                                <label style="color: #333">Email Required</label>
                                <select v-model="filterModel.required_email_temp" class="form-control pull-right">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div v-if="tableShow.country" class="col-md-2">
                            <div class="form-group">
                                <label style="color: #333">Country</label>
                                <!-- <select v-model="filterModel.country_id_temp" class="form-control pull-right">
                                    <option v-for="(option, index) in filterModel.countryList.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select> -->
                                <v-select multiple v-model="filterModel.country_id_temp" :options="listCountryAll.data" :reduce="name => name.name" label="name" :searchable="false" placeholder="All"/>
                            </div>
                        </div>

                        <div v-if="tableShow.domain" class="col-md-2">
                            <div class="from-group">
                                <label style="color: #333">Domains</label>
                                <input type="text" v-model="filterModel.domain_temp" class="form-control pull-right" placeholder="Search Domain">
                            </div>
                        </div>

                        <div v-if="tableShow.status" class="col-md-2">
                            <div class="form-group">
                                <label style="color: #333">Status</label>
                                <select v-model="filterModel.status_temp" class="form-control">
                                    <option v-for="(option, key) in listStatusText" v-bind:value="key">
                                        {{ option.text }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin || tableShow.employee">
                            <div class="form-group">
                                <label style="color: #333">Employee</label>
                                <!-- <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.employee_id" style="height: 37px;">
                                    <option value="0">Select Employee</option>
                                    <option v-for="user in listUser.data" :value="user.id">{{ user.username }}</option>
                                </select> -->
                                <v-select multiple v-model="filterModel.employee_id" :options="listSellerTeam.data" :reduce="username => username.username" label="username" :searchable="false" placeholder="All"/>
                            </div>
                        </div>

                        <div v-if="tableShow.status" class="col-md-2">
                            <div class="form-group pull-right">
                                <label style="color: #333">Action</label>
                                <br>
                                <div class="btn-group">
                                    <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                    <button @click="doCrawlExtList" type="submit" title="Crawl" class="btn btn-default"><i class="fa fa-fw fa-globe"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">

                    <h3 class="box-title">URL Prospect List</h3>

                    <button @click="doAddExt" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                    
                    <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                    
                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>

                    <div class="input-group input-group-sm float-right" style="min-width: 200px; max-width: 400px;">
                        <label style="color: #333;margin: 5%;">Selected Action</label>
                        <div class="btn-group">
                            <button @click="doSendEmail(null, $event)" data-toggle="modal" type="submit" title="Send Email" class="btn btn-default"><i class="fa fa-fw fa-envelope-o"></i></button>
                            <button type="submit" title="Get Ahrefs" @click="getAhrefs()" class="btn btn-default"><i class="fa fa-fw fa-area-chart"></i></button>
                            <button type="submit" title="Status" @click="doMultipleStatus" class="btn btn-default"><i class="fa fa-fw fa-tag"></i></button>
                            <button type="submit" title="Delete" @click="deleteAll" class="btn btn-default"><i class="fa fa-fw fa-trash"></i></button>
                        </div>
                    </div>

                    <div class="form-row">

                        <div class="col-md-4 my-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" v-on:change="checkDataExcel" enctype="multipart/form-data" ref="excel" name="file">
                                        <div class="input-group-btn">
                                            <button title="Upload CSV File" @click="submitUpload" :disabled="isEnableBtn" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i></button>
                                        </div>
                                    </div>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                    <span v-if="messageForms.action == 'uploaded'" class="text-success">{{ messageForms.message }}</span>
                                </div>
                            </div>

                            <!-- <div class="row my-3" v-show="showLang">
                                <div class="col-sm-12">
                                    <select class="form-control" name="language" ref="language" v-on:change="checkData">
                                        <option value="">Select language</option>
                                        <option v-for="(option, index) in filterModel.countryList.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row" v-show="showLang">
                                <div class="col-sm-12">
                                    <select class="form-control" name="status" ref="status" v-on:change="checkData">
                                        <option value="">Select Status</option>
                                        <option value="50">Contacted</option>
                                        <option value="60">Refused</option>
                                        <option value="70">InTouched</option>
                                        <option value="90">Unqualified</option>
                                        <option value="100">Qualified</option>
                                    </select>
                                </div>
                            </div> -->

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <small class="text-secondary">Reminder: The columns for the CSV file are Domain, Status, Country and Email. The columns should be separated using comma(,).</small>
                        </div>
                    </div>

                </div>

                <!-- <div class="box-body no-padding table-wrapper table-scrollable freeze-table" style="height: 600px; overflow-x: scroll;">
                    <table id="data-table" class="dataTable table table-hover table-bordered table-striped rlink-table" style="min-width: 1070px; max-width: 1626px;"> -->

                <div :class="{ 'box-body': true, 'no-padding': true, 'table-responsive': true }">

                    <span v-if="listExt.total > 10" class="pagination-custom-footer-text">
                        <b>Showing {{ listExt.from }} to {{ listExt.to }} of {{ listExt.total }} entries.</b>
                    </span>

                    <table id="data-table" class="dataTable table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>Action</th>
                                <th>
                                    <input class="custom-checkbox" type="checkbox" @click="selectAll" v-model="allSelected">
                                </th>
                                <th class="sorting" data-index="0" v-show="tableShow.id">#</th>
                                <th class="sorting" data-index="1" v-show="tableShow.employee">Employee</th>
                                <th class="sorting" data-index="2" v-show="tableShow.country">Country</th>
                                <th class="sorting" data-index="3" v-show="tableShow.domain">Domain</th>
                                <th class="sorting" data-index="4" v-show="tableShow.email">Emails</th>
                                <th class="sorting" data-index="5" v-show="tableShow.facebook">Facebook</th>
                                <th class="sorting" data-index="6" v-show="tableShow.phone">Phone</th>
                                <th class="sorting" data-index="7" v-show="tableShow.rank">Rank</th>
                                <th class="sorting" data-index="8" v-show="tableShow.status">Status</th>
                                <th class="sorting" data-index="9" v-show="tableShow.total_spent">Total Spent</th>
                                <th class="sorting" data-index="10" v-show="tableShow.ahrefs_rank">Ahreafs Rank</th>
                                <th class="sorting" data-index="11" v-show="tableShow.no_backlinks">No Backlinks</th>
                                <th class="sorting" data-index="12" v-show="tableShow.url_rating">UR</th>
                                <th class="sorting" data-index="13" v-show="tableShow.domain_rating">DR</th>
                                <th class="sorting" data-index="14" v-show="tableShow.ref_domains">Ref Domains</th>
                                <th class="sorting" data-index="15" v-show="tableShow.organic_keywords">Organic Keywords</th>
                                <th class="sorting" data-index="16" v-show="tableShow.organic_traffic">Organic Traffic</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(ext, index) in listExt.data" :key="index">
                            <td>
                                <div class="btn-group" v-if="checkSellerAccess(ext.users == null ? null:ext.users.id)">
                                    <button data-action="a1" :data-index="index" @click="doEditExt(ext)" data-toggle="modal" data-target="#modal-update" class="btn btn-default" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                    <button data-action="a2" :data-index="index" v-if="hasBacklink(ext.status)" @click="doShowBackLink(ext)" data-toggle="modal" data-target="#modal-backlink" type="submit" title="Back Link" class="btn btn-default"><i class="fa fa-fw fa-link"></i></button>
                                    <button data-action="a4" :data-index="index" @click="doSendEmail(ext, $event)" data-toggle="modal" type="submit" title="Send Email" class="btn btn-default"><i class="fa fa-fw fa-envelope-o"></i></button>
                                    <button v-if="ext.status == '30'" type="submit" title="Get Ahrefs" @click="getAhrefsById(ext.id, ext.status)" class="btn btn-default"><i class="fa fa-fw fa-area-chart"></i></button>
                                </div>
                                <!--                                <router-link class="btn btn-success" :to="{ path: `/profile/${user.id}` }"><i class="fa fa-fw fa-eye"></i> View</router-link>-->
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default">
                                        <input type="checkbox" class="custom-checkbox" v-on:change="checkSelected" :id="ext.id" :value="ext" v-model="checkIds">
                                    </button>
                                </div>
                            </td>
                            <td v-show="tableShow.id" title="Index" class="center-content">{{ index + 1 }}</td>
                            <td v-show="tableShow.employee">{{ ext.users == null ? 'N/A':ext.users.username }}</td>
                            <td v-show="tableShow.country" title="Country"  >{{ ext.country.name }}</td>
                            <td v-show="tableShow.domain" title="Domain" ><a :href="'http://' + ext.domain" target="_blank">{{ ext.domain }}</a></td>
                            <td v-show="tableShow.email" title="Emails" style="max-width: 200px;">
                                <div style="width: 100%; text-align: center;" v-if="isCrawling && !ext.email"><img src="/images/row-loading.gif" alt="crawling"></div>
                                <ol v-if="ext.email" class="pl-15"><li v-for="item in ext.email.split('|')">{{ item }}</li></ol>
                            </td>
                            <td v-show="tableShow.facebook" title="Facebook" style="max-width: 200px;" >
                                <div style="width: 100%; text-align: center;" v-if="isCrawling && !ext.facebook"><img src="/images/row-loading.gif" alt="crawling"></div>
                                <ol v-if="ext.facebook" class="pl-15"><li v-for="item in ext.facebook.split('|')"><a target="_blank" :href="item">{{ item }}<br/></a></li></ol>
                            </td>
                            <td v-show="tableShow.phone" title="Phone" style="max-width: 200px;" >
                                <div style="width: 100%; text-align: center;" v-if="isCrawling && !ext.phone"><img src="/images/row-loading.gif" alt="crawling"></div>
                                <ol v-if="ext.phone" class="pl-15"><li v-for="item in ext.phone.split('|')"><a target="_blank" :href="item">{{ item }}<br/></a></li></ol>
                            </td>
                            <td v-show="tableShow.rank" title="Rank">{{ ext.alexa_rank }}</td>
                            <td v-show="tableShow.status" title="Status"><span :class="['label', 'label-' + (listStatusText[ext.status] ? listStatusText[ext.status].label : 'warning')]">{{ listStatusText[ext.status] ? listStatusText[ext.status].text : 'undefined' }}</span></td>
                            <td v-show="tableShow.total_spent" title="Total Spent">{{ convertPrice(ext.total_spent) }}$</td>
                            <td v-show="tableShow.ahrefs_rank" title="Aherfs Rank">{{ ext.ahrefs_rank }}</td>
                            <td v-show="tableShow.no_backlinks" title="No Backlinks">{{ ext.no_backlinks }}</td>
                            <td v-show="tableShow.url_rating" title="URL Rating">{{ ext.url_rating }}</td>
                            <td v-show="tableShow.domain_rating" title="Domain Rating">{{ ext.domain_rating }}</td>
                            <td v-show="tableShow.ref_domains" title="Ref domains">{{ ext.ref_domains }}</td>
                            <td v-show="tableShow.organic_keywords" title="Organic keywords">{{ formatPrice(ext.organic_keywords) }}</td>
                            <td v-show="tableShow.organic_traffic" title="Organic traffic">{{ formatPrice(ext.organic_traffic) }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>

                </div>

                <!-- <div class="box-footer clearfix">
                    <component :is="pagination" :callMethod="goToPage"></component>
                </div> -->

            </div>
        </div>


        <!-- Modal Add -->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add URL Prospect</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                    <label style="color: #333">Domain</label>
                                    <input type="text" v-model="extModel.domain" class="form-control" value="" required placeholder="Enter domain">
                                    <span v-if="messageForms.errors.domain" v-for="err in messageForms.errors.domain" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Status</label>
                                    <select class="form-control" v-model="extModel.status">
                                        <option value="50">Contacted</option>
                                        <option value="60">Refused</option>
                                        <option value="70">InTouched</option>
                                        <option value="90">Unqualified</option>
                                        <option value="100">Qualified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Country</label>
                                    <div>
                                        <select v-model="extModel.country_id" class="form-control pull-right">
                                            <option value="0">-- Select country --</option>
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333">Email</label>
                                    <textarea type="text" v-model="extModel.email" class="form-control" value=""  placeholder="Enter Email"></textarea>
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.facebook}" class="form-group">
                                    <label style="color: #333">Facebook</label>
                                    <textarea type="text" v-model="extModel.facebook" class="form-control" value=""  placeholder="Enter Facebook"></textarea>
                                    <span v-if="messageForms.errors.facebook" v-for="err in messageForms.errors.facebook" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">
                                    <label style="color: #333">Phone</label>
                                    <textarea type="text" v-model="extModel.phone" class="form-control" value=""  placeholder="Enter Phone"></textarea>
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.alexa_rank}" class="form-group">
                                    <label style="color: #333">Alexa Rank</label>
                                    <input type="text" v-model="extModel.alexa_rank" class="form-control" value=""  placeholder="Enter Alexa Rank">
                                    <span v-if="messageForms.errors.alexa_rank" v-for="err in messageForms.errors.alexa_rank" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.ahrefs_rank}" class="form-group">
                                    <label style="color: #333">Ahrefs Traffic</label>
                                    <input type="number" v-model="extModel.ahrefs_rank" class="form-control" value=""  placeholder="Enter Ahrefs Rank">
                                    <span v-if="messageForms.errors.ahrefs_rank" v-for="err in messageForms.errors.ahrefs_rank" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.ref_domains}" class="form-group">
                                    <label style="color: #333">Ref Domains</label>
                                    <input type="number" v-model="extModel.ref_domains" class="form-control" value=""  placeholder="Enter Ref Domains">
                                    <span v-if="messageForms.errors.ref_domains" v-for="err in messageForms.errors.ref_domains" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.no_backlinks}" class="form-group">
                                    <label style="color: #333">No Backlinks</label>
                                    <input type="number" v-model="extModel.no_backlinks" class="form-control" value=""  placeholder="Enter No Backlinks">
                                    <span v-if="messageForms.errors.no_backlinks" v-for="err in messageForms.errors.no_backlinks" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.url_rating}" class="form-group">
                                    <label style="color: #333">URL Rating</label>
                                    <input type="number" v-model="extModel.url_rating" class="form-control" value=""  placeholder="Enter URL Rating">
                                    <span v-if="messageForms.errors.url_rating" v-for="err in messageForms.errors.url_rating" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain_rating}" class="form-group">
                                    <label style="color: #333">Domain Rating</label>
                                    <input type="number" v-model="extModel.domain_rating" class="form-control" value=""  placeholder="Enter Domain Rating">
                                    <span v-if="messageForms.errors.domain_rating" v-for="err in messageForms.errors.domain_rating" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_keywords}" class="form-group">
                                    <label style="color: #333">Organic Keywords</label>
                                    <input type="text" v-model="extModel.organic_keywords" class="form-control" value=""  placeholder="Enter Organic Keywords">
                                    <span v-if="messageForms.errors.organic_keywords" v-for="err in messageForms.errors.organic_keywords" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_traffic}" class="form-group">
                                    <label style="color: #333">Organic Traffic</label>
                                    <input type="text" v-model="extModel.organic_traffic" class="form-control" value=""  placeholder="Enter Organic Traffic">
                                    <span v-if="messageForms.errors.organic_traffic" v-for="err in messageForms.errors.organic_traffic" class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Skype</label>
                                    <textarea type="text" v-model="extModel.skype" class="form-control" value="" placeholder="Enter Skype"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Info</label>
                                    <textarea type="text" v-model="extModel.info" class="form-control" value="" placeholder="Enter Info"></textarea>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="number" v-model="extModel.price" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Include Article</label>
                                    <select class="form-control" v-model="extModel.inc_article">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div> -->

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Add-->


        <!-- Modal Backlink -->
        <div class="modal fade" id="modal-backlink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ listBackLink.total }} Back Links: <a target="_blank" :href="'http://' + extBackLink.domain">{{ extBackLink.domain }}</a></h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <table class="table table-hover table-bordered table-striped rlink-table">
                            <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>External Domain</th>
                                <th>Internal Domain</th>
                                <th>Link</th>
                                <th>Price</th>
                                <th>Anchor Text</th>
                                <th>Live Date</th>
                                <th>Status</th>
                                <th>User</th>
                            </tr>
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ backLink.ext_domain.domain }}</td>
                                <td>{{ backLink.int_domain.domain }}</td>
                                <td><a href="backLink.link">{{ backLink.link }}</a></td>
                                <td>{{ backLink.price }}</td>
                                <td>{{ backLink.anchor_text }}</td>
                                <td>{{ backLink.live_date }}</td>
                                <td>{{ backLink.status }}</td>
                                <td>{{ backLink.user.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                        <download-csv
                                :data = "listBackLink.data"
                                :fileds = "csvExport.data_filled"
                                :nameFile = "extBackLink.domain + '_' + csvExport.file_csv">
                        </download-csv>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Backlink -->


        <!-- Modal Update -->
        <div class="modal fade" id="modal-update" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update URL Prospect: <a :href="'http://' + extUpdate.domain">{{ extUpdate.domain }}</a></h4>
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
                               <ul>
                                   <li>Note: contact enter with format: "contact 01|contact 02|contact 03"</li>
                                   <li>Example: abc@gmail.com|xyz@yahoo.com</li>
                               </ul>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                    <label style="color: #333">Domain</label>
                                    <input type="text" v-model="extUpdate.domain" class="form-control" value="" required="required" placeholder="Enter domain">
                                    <span v-if="messageForms.errors.domain" v-for="err in messageForms.errors.domain" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.status}" class="form-group">
                                    <label style="color: #333">Status</label>
                                    <select type="text" v-model="extUpdate.status" @change="showAddURL()" class="form-control" value="" required="required">
                                    <option v-for="(option, key) in listStatusText" v-bind:value="key" :selected="(key === extUpdate.status ? 'selected' : '')">
                                        {{ option.text }}
                                    </option>
                                    </select>
                                    <span v-if="messageForms.errors.status" v-for="err in messageForms.errors.status" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Country</label>
                                    <div>
                                        <select v-model="extUpdate.country_id" class="form-control pull-right">
                                            <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.facebook}" class="form-group">
                                    <label style="color: #333">Facebook</label>
                                    <textarea type="text" v-model="extUpdate.facebook" class="form-control" value=""  placeholder="Enter Facebook"></textarea>
                                    <span v-if="messageForms.errors.facebook" v-for="err in messageForms.errors.facebook" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email}" class="form-group">
                                    <label style="color: #333">Email</label>
                                    <textarea type="text" v-model="extUpdate.email" class="form-control" value=""  placeholder="Enter Email"></textarea>
                                    <span v-if="messageForms.errors.email" v-for="err in messageForms.errors.email" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.phone}" class="form-group">
                                    <label style="color: #333">Phone</label>
                                    <textarea type="text" v-model="extUpdate.phone" class="form-control" value=""  placeholder="Enter Phone"></textarea>
                                    <span v-if="messageForms.errors.phone" v-for="err in messageForms.errors.phone" class="text-danger">{{ err }}</span>
                                </div>
                            </div>


                            <!-- <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.ahrefs_rank}" class="form-group">
                                    <label style="color: #333">Ahrefs Rank</label>
                                    <input type="number" v-model="extUpdate.ahrefs_rank" class="form-control" value=""  placeholder="Enter Ahrefs Rank">
                                    <span v-if="messageForms.errors.ahrefs_rank" v-for="err in messageForms.errors.ahrefs_rank" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.ref_domains}" class="form-group">
                                    <label style="color: #333">Ref Domains</label>
                                    <input type="number" v-model="extUpdate.ref_domains" class="form-control" value=""  placeholder="Enter Ref Domains">
                                    <span v-if="messageForms.errors.ref_domains" v-for="err in messageForms.errors.ref_domains" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.no_backlinks}" class="form-group">
                                    <label style="color: #333">No Backlinks</label>
                                    <input type="number" v-model="extUpdate.no_backlinks" class="form-control" value=""  placeholder="Enter No Backlinks">
                                    <span v-if="messageForms.errors.no_backlinks" v-for="err in messageForms.errors.no_backlinks" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.url_rating}" class="form-group">
                                    <label style="color: #333">URL Rating</label>
                                    <input type="number" v-model="extUpdate.url_rating" class="form-control" value=""  placeholder="Enter URL Rating">
                                    <span v-if="messageForms.errors.url_rating" v-for="err in messageForms.errors.url_rating" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain_rating}" class="form-group">
                                    <label style="color: #333">Domain Rating</label>
                                    <input type="number" v-model="extUpdate.domain_rating" class="form-control" value=""  placeholder="Enter Domain Rating">
                                    <span v-if="messageForms.errors.domain_rating" v-for="err in messageForms.errors.domain_rating" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_keywords}" class="form-group">
                                    <label style="color: #333">Organic Keywords</label>
                                    <input type="text" v-model="extUpdate.organic_keywords" class="form-control" value=""  placeholder="Enter Organic Keywords">
                                    <span v-if="messageForms.errors.organic_keywords" v-for="err in messageForms.errors.organic_keywords" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.organic_traffic}" class="form-group">
                                    <label style="color: #333">Organic Traffic</label>
                                    <input type="text" v-model="extUpdate.organic_traffic" class="form-control" value=""  placeholder="Enter Organic Traffic">
                                    <span v-if="messageForms.errors.organic_traffic" v-for="err in messageForms.errors.organic_traffic" class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Skype</label>
                                    <textarea type="text" v-model="extUpdate.skype" class="form-control" value="" placeholder="Enter Skype"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: #333">Info</label>
                                    <textarea type="text" v-model="extUpdate.info" class="form-control" value="" placeholder="Enter Info"></textarea>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="number" v-model="extUpdate.price" class="form-control" value="" placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #333">Include Article</label>
                                    <select class="form-control" v-model="extUpdate.inc_article">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div> -->

                        </form>

                        <div class="row mt-3" v-show="formAddUrl">
                            <div class="col-md-12">
                                <h4>Publisher List</h4>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.seller']}" class="form-group">
                                    <label style="color: #333">Seller</label>
                                    <select name="" class="form-control" v-model="publisherAdd.seller" :disabled="isEditable">
                                        <option value="">Select Seller</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors['pub.seller']" v-for="err in messageForms.errors['pub.seller']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.inc_article']}" class="form-group">
                                    <label style="color: #333">Inc Article</label>
                                    <select name="" id="" class="form-control" v-model="publisherAdd.inc_article" :disabled="isEditable">
                                        <option value="">Select Include Article</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <span v-if="messageForms.errors['pub.inc_article']" v-for="err in messageForms.errors['pub.inc_article']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.url']}" class="form-group">
                                    <label style="color: #333">URL</label>
                                    <input type="text" class="form-control" v-model="publisherAdd.url" :disabled="true">
                                    <span v-if="messageForms.errors['pub.url']" v-for="err in messageForms.errors['pub.url']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.language_id']}" class="form-group">
                                    <label style="color: #333">Language</label>
                                    <select name="" class="form-control" v-model="publisherAdd.language_id" :disabled="isEditable">
                                        <option value="">Select Language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors['pub.language_id']" v-for="err in messageForms.errors['pub.language_id']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.price']}" class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="number" class="form-control" v-model="publisherAdd.price" :disabled="isEditable">
                                    <span v-if="messageForms.errors['pub.price']" v-for="err in messageForms.errors['pub.price']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.casino_sites']}" class="form-group">
                                    <label style="color: #333">Accept Casino & Betting Sites</label>
                                    <select name="" class="form-control" v-model="publisherAdd.casino_sites" :disabled="isEditable">
                                        <option value="">Select Casino & Bettings Sites</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors['pub.casino_sites']" v-for="err in messageForms.errors['pub.casino_sites']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors['pub.topic']}" class="form-group">
                                    <label style="color: #333">Topic</label>
                                    <select name="" class="form-control" v-model="publisherAdd.topic" :disabled="isEditable">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors['pub.topic']" v-for="err in messageForms.errors['pub.topic']" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="overlay" v-if="isPopupLoading"></div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Update -->

        <!-- Modal Setting -->
        <div class="modal fade" id="modal-setting" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.id ? 'checked':''" v-model="tableShow.id">#</label>
                            </div>
                            
                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.employee ? 'checked':''" v-model="tableShow.employee">Employee</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.country ? 'checked':''" v-model="tableShow.country">Country</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox"  :checked="tableShow.domain ? 'checked':''" v-model="tableShow.domain">Domain</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.email ? 'checked':''" v-model="tableShow.email">Email</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.facebook ? 'checked':''" v-model="tableShow.facebook">Facebook</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.rank ? 'checked':''" v-model="tableShow.rank">Rank</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.status ? 'checked':''" v-model="tableShow.status">Status</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.total_spent ? 'checked':''" v-model="tableShow.total_spent">Total Spent</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.ahrefs_rank ? 'checked':''" v-model="tableShow.ahrefs_rank">Ahrefs Rank</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.no_backlinks ? 'checked':''" v-model="tableShow.no_backlinks">No Backlinks</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.url_rating ? 'checked':''" v-model="tableShow.url_rating">UR</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.domain_rating ? 'checked':''" v-model="tableShow.domain_rating">DR</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.ref_domains ? 'checked':''" v-model="tableShow.ref_domains">Ref Domains</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.organic_keywords ? 'checked':''" v-model="tableShow.organic_keywords">Organic Keywords</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.organic_traffic ? 'checked':''" v-model="tableShow.organic_traffic">Organic Traffic</label>
                            </div>

                            <div class="checkbox col-md-4">
                                <label><input type="checkbox" :checked="tableShow.phone ? 'checked':''" v-model="tableShow.phone">Phone</label>
                            </div>
                        </div>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" data-dismiss="modal" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Setting -->


        <!-- Modal Add Backlink -->
        <div v-if="openModalBackLink" class="modal fade"  ref="modalBacklink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add BackLink Domain</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageBacklinkForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageBacklinkForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageBacklinkForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.ext_domain_id}" class="form-group">
                                    <label style="color: #333">Ext Domain</label>
                                    <input type="text" :disabled="true" v-model="modelBaclink.ext_name" class="form-control" value="" required="required" >
                                    <span v-if="messageBacklinkForms.errors.ext_domain_id" v-for="err in messageBacklinkForms.errors.ext_domain_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Country</label>
                                    <div>
                                        <select @change="fillterIntByCountry($event)" class="form-control pull-right">
                                            <option value="0">-- Select Country --</option>
                                            <option  v-for="option in listCountriesInt.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.int_domain_id}" class="form-group">
                                    <label style="color: #333">Internal Domain</label>
                                    <div>
                                        <select :disabled="loadIntDomain" v-model="modelBaclink.int_domain_id" class="form-control pull-right">
                                            <option v-bind:value="0" >-- Select Internal Domain --</option>
                                            <option  v-for="option in listInt" v-bind:value="option.id">
                                                {{ option.domain }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageBacklinkForms.errors.int_domain_id" v-for="err in messageBacklinkForms.errors.int_domain_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                                <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.price}" class="form-group">
                                    <div>
                                        <label style="color: #333">Price</label>
                                        <input type="number" v-model="modelBaclink.price" class="form-control" value="" required="required" >
                                        <span v-if="messageBacklinkForms.errors.price" v-for="err in messageBacklinkForms.errors.price" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="closeModalBacklink">Close</button>
                        <button type="button" :disabled="checkSelectIntDomain" @click="submitAddBacklink" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Add -->

        <!-- Modal Send Email -->
        <div id="modal-email" class="modal fade" ref="modalEmail" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h4 class="modal-title">Send email to {{ mailInfo.receiver_text }}</h4> -->
                        <h4 class="modal-title">Send email</h4>
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <label style="color: #333">Language</label>
                                        <div>
                                            <select @change="doChangeEmailCountry" v-model="mailInfo.country.id" class="form-control pull-right">
                                                <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div  class="form-group">
                                            <label style="color: #333">Select template {{ mailInfo.country.name }}</label>
                                            <div>
                                                <select @change="doChangeEmailTemplate" v-model="mailInfo.tpl" class="form-control pull-right">
                                                    <option  v-for="option in listMailTemplate.data" v-bind:value="option.id">
                                                        {{ option.mail_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.mail_name}" class="form-group">
                                    <label style="color: #333">Email Name</label>
                                    <input type="text" v-model="modelMail.mail_name" class="form-control" value="" required="required" >
                                    <span v-if="messageForms.errors.mail_name" v-for="err in messageForms.errors.mail_name" class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->


                            <div class="col-md-12" style="margin-top: 15px;">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.title}" class="form-group">
                                    <label style="color: #333">Title</label>
                                    <input type="text" v-model="modelMail.title" class="form-control" value="" required="required" >
                                    <span v-if="messageForms.errors.title" v-for="err in messageForms.errors.title" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.content}" class="form-group">
                                    <label style="color: #333">Content</label>
                                    <textarea rows="10" type="text" v-model="modelMail.content" class="form-control" value="" required="required"></textarea>
                                    <span v-if="messageForms.errors.content" v-for="err in messageForms.errors.content" class="text-danger">{{ err }}</span>
                                </div>
                            </div>


                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" :disabled="!allowSending" @click="submitSendMail" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Send Email -->

        <!-- Modal Change multiple Status -->
        <div id="modal-multiple-status" class="modal fade" ref="modalMultipleStatus">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Multiple Status</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.status}" >
                                    <label style="color: #333">Status</label>
                                    <select type="text"  class="form-control" v-on:change="checkQualified" v-model="updateStatus.status" required="required">
                                        <option value="">Select Status</option>
                                        <option v-for="(option, key) in listStatusText" v-bind:value="key">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <span v-if="messageBacklinkForms.errors.status" v-for="err in messageBacklinkForms.errors.status" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="isQualified">
                                <div :class="{'form-group': true, 'has-error': messageBacklinkForms.errors.seller}" >
                                    <label style="color: #333">Seller</label>
                                    <select type="text" v-model="updateStatus.seller" class="form-control" required="required">
                                        <option value="">Select Seller</option>
                                        <option  v-for="option in listExtSeller" v-bind:value="option.id">
                                            {{ option.username }}
                                        </option>
                                    </select>
                                    <span v-if="messageBacklinkForms.errors.seller" v-for="err in messageBacklinkForms.errors.seller" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="doUpdateMultipleStatus(false, '')">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Change Multiple Status -->


        <!-- Modal Existing Domain -->
        <div class="modal fade" id="modal-existing-domain">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Existing Domain</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed">
                            <tr>
                                <th colspan="2">Total Existing Domain ({{existingDomain.total}})</th>
                            </tr>
                            <tr v-for="(ext, index) in existingDomain.data" :key="index">
                                <td>{{ ext }}</td>
                                <td class="text-danger">Not Uploaded</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Existing Domain -->

    </div>
</template>

<style>
    .custom-checkbox {
        /* Double-sized Checkboxes */
        -ms-transform: scale(2); /* IE */
        -moz-transform: scale(2); /* FF */
        -webkit-transform: scale(2); /* Safari and Chrome */
        -o-transform: scale(2); /* Opera */
        transform: scale(2);
        padding: 10px;
    }
</style>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import DownloadCsv from '@/components/export-csv/Csv.vue'
    import { async } from 'q';
    export default {
        name: 'ExtList',
        data() {
            return {
                csvExport: {
                    file_csv: 'baclink.csv',
                    data_filled: {
                        'Ext Domain': 'ext_domain.domain',
                        'Int Domain': 'int_domain.domain',
                        'Link': 'link',
                        'Price': 'price',
                        'Anchor Text': 'anchor_text',
                        'Live Date': 'live_date',
                        'Status': 'status',
                        'User': 'user.name'
                    },
                    json_meta: [
                        [{
                            'key': 'charset',
                            'value': 'utf-8'
                        }]
                    ]
                },
                dataTable: null,
                filterModel: {
                    id: this.$route.query.id || 0,
                    id_temp: this.$route.query.id_temp || 0,
                    country_id: this.$route.query.country_id || 0,
                    country_id_temp: this.$route.query.country_id || '',
                    countryList: { data: [], total: 0},
                    domain: this.$route.query.domain || '',
                    domain_temp: this.$route.query.domain_temp || '',
                    status: this.$route.query.status || -1,
                    status_temp: this.$route.query.status_temp || 0,
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 50,
                    employee_id: this.$route.query.employee_id || '',
                    required_email_temp: this.$route.query.required_email_temp || 0,
                    required_email: this.$route.query.required_email || 0,
                    sort_key: this.$route.query.sort_key || 'id',
                    sort_value: this.$route.query.sort_value || 'desc'
                },
                listPageOptions: [50, 150, 250, 350, 500, 1000, 2000],
                extModel: {
                    id: 0,
                    domain: '',
                    country_id: 0,
                    alexa_rank: 0,
                    ahrefs_rank: 0,
                    no_backlinks: 0,
                    url_rating: 0,
                    domain_rating: 0,
                    ref_domains: 0,
                    organic_keywords: '',
                    organic_traffic: '',
                    facebook: '',
                    email: '',
                    phone: '',
                    skype: '',
                    info: '',
                    // price: '',
                    status: '',
                    // inc_article: 'Yes',
                },
                mailInfo: {
                    tpl: 0,
                    ids: '',
                    receiver_text: '',
                    country: {
                        id: 0,
                        name: '',
                        code: ''
                    }
                },
                modelMail: {
                    title: '',
                    content: '',
                    mail_name: '',
                },
                extBackLink: {},
                extUpdate: {},
                publisherAdd: {
                    seller: '',
                    language_id: '',
                    inc_article: '',
                    topic: '',
                    casino_sites: '',
                    url: '',
                    price: '',
                },
                isUpdateMode: false,
                isCrawling: false,
                isLoadingTable: false,
                isPopupLoading: false,
                modalAddBackLink: false,
                modelBaclink: {
                    int_domain_id: 0,
                    ext_name: ''
                },
                loadIntDomain: false,
                allowSending: true,
                listSortKey: [],
                listSortValue: [],
                checkIds: [],
                showLang: false,
                isEnableBtn: true,
                updateStatus: {
                    seller: '',
                    status: '',
                },
                isQualified: false,
                formAddUrl: false,
                topic: [
                    'Beauty',
                    'Charity',
                    'Cooking',
                    'Education',
                    'Fashion',
                    'Finance',
                    'Games',
                    'Health',
                    'History',
                    'Job',
                    'Movies & Music',
                    'News',
                    'Pet',
                    'Photograph',
                    'Real State',
                    'Religion',
                    'Shopping',
                    'Sports',
                    'Tech',
                    'Unlisted',
                ],
                isEditable: false,
                existingDomain: {
                    total: 0,
                    data:[]
                },
                email_to: '',
                extDomain_id: '',
                allSelected: false,
             };
        },
        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            this.updateUserPermission();
            this.getUserList();
            this.getListCountriesInt();
            this.getExtList({
                params: this.filterModel
            });
            this.fillterIntByCountry();
            this.checkQualified();
            this.getListExtSeller();

            let seller = this.listSeller.data;
            if( seller.length === 0 ){
                this.getListSeller();
            }

            let countries = this.listCountryAll.data;
            if( countries.length === 0 ){
                this.getListCountries();
            }

            this.getListSellerTeam();
            this.getListLanguages();
        },
        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                tableShow: state => state.storeExtDomain.tableExtShowOptions,
                listExt: state => state.storeExtDomain.listExt,
                listStatusText: state => state.storeExtDomain.listStatusText,
                listUser: state => state.storeUser.listUser,
                messageForms: state => state.storeExtDomain.messageForms,
                messageBacklinkForms: state => state.storeBackLink.messageBacklinkForms,
                listInt: state => state.storeIntDomain.listInt,
                listBackLink: state => state.storeBackLink.listBackLink,
                filterBackLink: state => state.storeBackLink.fillter,
                listCountriesInt: state => state.storeExtDomain.listCountriesInt,
                listAhrefs: state => state.storeExtDomain.listAhrefs,
                listMailTemplate: state => state.storeExtDomain.listMailTemplate,
                listExtSeller: state => state.storeExtDomain.listExtSeller,
                listSeller: state => state.storePublisher.listSeller,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listSellerTeam: state => state.storeExtDomain.listSellerTeam,
                listLanguages: state => state.storePublisher.listLanguages,
            }),
            pagination() {
                return {
                    props: {
                        callMethod: ""
                    },
                    template: `<div class="paging_simple_numbers">${this.listExt.pagination}</div>`,
                    methods: {
                        async goToPage(pageNum) {
                            this.callMethod(pageNum);
                        }
                    }
                }
            },
            checkSelectIntDomain () {
                if (this.modelBaclink.int_domain_id == 0) {
                    return true
                }
                return false
            },
            allowSendMail() {
                if (this.allowSending = true) {
                    return true;
                }
                return false;
            },
            openModalBackLink() {
                if (this.modalAddBackLink = true) {
                    return true
                }
                return false
            },
        },
        mounted(){
            let that = this;
            $(this.$refs.modalBacklink).on("hidden.bs.modal", this.handleCloseBacklinkModal)
            $('.freeze-table').on('click', '.clone-column-table-wrap *[data-action]', function(e) {
                e.preventDefault();
                var action = $(this).attr('data-action');
                var index = $(this).attr('data-index');
                if (action == "a1") {
                    that.doEditExtIndex(index);
                } else if (action == "a2") {
                    that.doShowBackLinkIndex(index);
                } else if (action == "a3") {
                    that.addBackLinkIndex(index);
                } else if (action == "a4") {
                    that.doSendEmailIndex(index);
                }
            });
            $('.freeze-table').on('click', '.clone-head-table-wrap th', function(e) {
                e.preventDefault();
                var index = $(this).attr('data-index');
                $('#data-table th[data-index=' + index + ']').click();
                var m_class = $('#data-table th[data-index=' + index + ']').attr('class');
                $(this).attr('class', m_class);
            });
        },
        methods: {

            selectAll() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var ext in this.listExt.data) {
                        this.checkIds.push(this.listExt.data[ext]);
                    }
                }
            },

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            showAddURL() {
                this.formAddUrl = false;
                if ( this.extUpdate.status == 100 ){
                    this.formAddUrl = true;
                    this.extUpdate.url = this.extUpdate.domain;
                }
            },

            checkSellerAccess(seller_id) {
                if( this.user.role_id == 6 && this.user.isOurs == 0 ){
                    let check = false;
                    if( this.user.id == seller_id ){
                        check = true;
                    }
                    return check;
                }else{
                    return true;
                }
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            async getListSellerTeam(params) {
                await this.$store.dispatch('actionGetListSellerTeam', params);
            },

            async getListExtSeller() {
                await this.$store.dispatch('actionGetListExtSeller');
            },

            checkQualified() {
                let check = this.updateStatus.status
                if( check == 100 ){
                    this.isQualified = true;
                }else{
                    this.isQualified = false;
                }
            },
    
            // checkData() {
            //     this.isEnableBtn = true;
            //     if( this.$refs.language.value && this.$refs.status.value){
            //         this.isEnableBtn = false;
            //     }
            // },

            checkDataExcel() {
                this.isEnableBtn = true;
                if( this.$refs.excel.value ){
                    this.isEnableBtn = false;
                }
            },

            doMultipleStatus() {
                if( this.checkIds.length > 0 ){
                    let element = this.$refs.modalMultipleStatus
                    $(element).modal('show')
                }else{
                    swal.fire(
                        'No item',
                        'No selected item',
                        'error'
                    )
                }
            },

            async deleteAll() {
                if( this.checkIds.length > 0 ){
                    if( confirm("Are you sure you want to delete selected records?") ){
                        await this.$store.dispatch('actionDeleteExtDomain', {
                            params: {
                                id: this.checkIds,
                            }
                        });

                        this.getExtList();
                        this.checkIds = []
                        swal.fire(
                            'Saved!',
                            'Successfully Updated.',
                            'success'
                        )
                    }
                }else{
                    swal.fire(
                        'No item',
                        'No selected item',
                        'error'
                        )
                }
                    
            },

            async submitUpload() {

                this.isEnableBtn = true;
                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);
                // this.formData.append('language', this.$refs.language.value);
                // this.formData.append('status', this.$refs.status.value);

                await this.$store.dispatch('actionUploadCsvExtDomain', this.formData);

                if (this.messageForms.action === 'uploaded') {
                    this.getExtList();
                    this.$refs.excel.value = '';
                    // this.$refs.language.value = '';
                    // this.$refs.status.value = '';
                    this.isEnableBtn = true;
                    this.showLang = false;

                    // console.log(this.messageForms.errors.length)
                    let cnt_existing = this.messageForms.errors.length;
                    if (cnt_existing > 0){
                        for (let key in this.messageForms.errors ){
                            this.existingDomain.data.push(this.messageForms.errors[key].message)
                        }

                        this.existingDomain.total = cnt_existing;
                        $("#modal-existing-domain").modal('show')
                    }

                    console.log(this.existingDomain);
                }
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
                return val;
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            async updateUserPermission() {
                let that = this;
                await this.$store.dispatch('actionUpdateCurrentUserCountriesExt', { vue: this, userId: that.user.id });
                this.initFilter();
            },
            clearExtModel() {
                this.extModel = {
                    id: 0,
                    domain: '',
                    country_id: 0,
                    alexa_rank: 0,
                    ahrefs_rank: 0,
                    no_backlinks: 0,
                    url_rating: 0,
                    domain_rating: 0,
                    ref_domains: 0,
                    organic_keywords: '',
                    organic_traffic: '',
                    facebook: '',
                    email: '',
                    phone: '',
                    status: 0
                }
            },
            initFilter() {
                let that = this;
                this.user.countries_ext_accessable.forEach(function(country) {
                    that.filterModel.countryList.data.push({id: country.id, name: country.name});
                });
                this.listSortKey = {
                    'id': { text: '----' },
                    'alexa_rank': { text: 'Alexa Rank' },
                    'ahrefs_rank': { text: 'Ahrefs Rank' },
                    'no_backlinks': { text: 'No Backlink' },
                    'url_rating': { text: 'URL Rating' },
                    'domain_rating': { text: 'Domain Rating' },
                    'ref_domains': { text: 'Ref Domains' },
                    'organic_keywords': { text: 'Organic keywords' },
                    'organic_traffic': { text: 'Organic traffic' },
                    'total_spent': { text: 'Total Spent' },
                };
                this.listSortValue = {
                    'asc': { text: 'Ascending ' },
                    'desc': { text: 'Descending '}
                };
                this.filterModel.sort_value = 'desc';
            },
            async getUserList() {
                await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
                if (this.user.isAdmin) {
                    await this.$store.dispatch('actionGetUser', {
                        vue: this,
                        params: { params: { full_data: true } },
                        showMainLoading: false
                    });
                }
            },
           async getListCountriesInt() {
                await this.$store.dispatch('getListCountriesInt', { vue: this });
            },
            async getExtList(params) {
                this.isLoadingTable = true;
                if (this.dataTable != null) {
                    this.dataTable.destroy();
                }
                await this.$store.dispatch('getListExt', params);
                this.isLoadingTable = false;
                $('.freeze-table').freezeTable({ 'columnNum': 4, 'shadow': true, 'scrollable': true });
                this.dataTable = $('#data-table').DataTable({
                    paging: false,
                    searching: false,
                    ordering: true,
                    bDestroy: true
                });
            },
            async doSearchList() {
                let that = this;
                that.filterModel.country_id = that.filterModel.country_id_temp;
                that.filterModel.status = that.filterModel.status_temp;
                that.filterModel.domain = that.filterModel.domain_temp;
                that.filterModel.id = that.filterModel.id_temp;
                that.filterModel.required_email = that.filterModel.required_email_temp;
                that.filterModel.sort = that.filterModel.sort_key + ',' +  that.filterModel.sort_value;
                this.$router.push({
                    query: that.filterModel,
                });
                this.getExtList({
                    params: {
                        country_id: that.filterModel.country_id,
                        status: that.filterModel.status,
                        domain: that.filterModel.domain,
                        employee_id: that.filterModel.employee_id,
                        required_email: that.filterModel.required_email,
                        id: that.filterModel.id,
                        sort: that.filterModel.sort_key + ',' +  that.filterModel.sort_value,
                        per_page: that.filterModel.per_page,
                    }
                });
            },
            async goToPage(pageNum) {
                let that = this;
                this.$router.push({
                    query: that.filterModel,
                });
                await this.getExtList({
                    params: {
                        page: pageNum,
                        country_id: that.filterModel.country_id,
                        status: that.filterModel.status,
                        domain: that.filterModel.domain,
                        id: that.filterModel.id,
                        sort: that.filterModel.sort_key + ',' +  that.filterModel.sort_value,
                        per_page: that.filterModel.per_page,
                        employee_id: that.filterModel.employee_id,
                        required_email: that.filterModel.required_email
                    }
                });
            },
            async doCrawlExtList() {
                this.isCrawling = true;
                var arrayIds = [];
                if (this.listExt.data) {
                    for (let key in this.listExt.data) {
                        arrayIds.push(this.listExt.data[key].id);
                    }
                }
                if (arrayIds.length == 0) return;
                await this.$store.dispatch('crawlExtList', {
                    params: {
                        domain_ids: arrayIds.join(","),
                        queue: false
                    }
                });
                this.isCrawling = false;
            },
            async submitAdd() {
                let that = this;
                this.isPopupLoading = true;
                await this.$store.dispatch('addExt', that.extModel);
                this.isPopupLoading = false;
                if (this.messageForms.action === 'saved_ext') {
                    this.clearExtModel();
                    //this.listExt.data.pop();
                    //this.listExt.data.unshift(this.messageForms.obj);
                    this.doSearchList();
                    this.isEditable = true;
                }
            },
            convertPrice(price) {
                return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            },
            async submitUpdate() {
                this.isPopupLoading = true;
                await this.$store.dispatch('updateExt', {
                    ext: this.extUpdate,
                    pub: this.publisherAdd,
                });
                this.isPopupLoading = false;
                if (this.messageForms.action === 'updated_ext') {
                    for (var index in this.listExt.data) {
                        if (this.listExt.data[index].id === this.extUpdate.id) {
                            this.listExt.data[index] = this.extUpdate;
                            break;
                        }
                    }
                }
            },
            async doShowBackLink(extDomain) {
                this.extBackLink = extDomain;
                this.filterBackLink.ext= extDomain.id;
                this.filterBackLink.full_data = true;
                this.isPopupLoading = true;
                await this.$store.dispatch('actionGetBackLink', {
                    vue: this,
                    params: this.filterBackLink
                });
                this.isPopupLoading = false;
            },

            async doShowBackLinkIndex(index) {
                var extDomain = this.listExt.data[index];
                this.doShowBackLinkIndex(extDomain);
            },

            doAddExt() {
                this.$store.dispatch('clearMessageForm');
            },

            doEditExt(extDomain) {
                this.formAddUrl = true;

                this.$store.dispatch('clearMessageForm');
                this.extUpdate = JSON.parse(JSON.stringify(extDomain))

                if (this.extUpdate.status != 100){
                    this.formAddUrl = false;
                }

                this.getPublisherInfo(this.extUpdate.domain).then(res => {
                    var result = res.data
                    if (res.data.success == true){
                        this.publisherAdd.seller = result.data.user_id
                        this.publisherAdd.inc_article = result.data.inc_article
                        this.publisherAdd.url = result.data.url
                        this.publisherAdd.language_id = result.data.language_id
                        this.publisherAdd.topic = result.data.topic
                        this.publisherAdd.casino_sites = result.data.casino_sites
                        this.publisherAdd.price = result.data.price

                        this.isEditable = true;
                    } else {
                        this.publisherAdd.seller = ''
                        this.publisherAdd.inc_article = ''
                        this.publisherAdd.url = this.extUpdate.domain;
                        this.publisherAdd.language_id = ''
                        this.publisherAdd.topic = ''
                        this.publisherAdd.casino_sites = ''
                        this.publisherAdd.price = ''

                        this.isEditable = false;
                    }
                    
                });
                
            },

            async getPublisherInfo(domain) {
                try {
                    const response = await axios.get('api/get-publisher-info', {
                                            params: {
                                                url: domain,
                                            }
                                        });
                    return response;
                } catch (error) {
                    console.error(error);
                }
            },

            doEditExtIndex(index) {
                let extDomain = this.listExt.data[index];
                this.doEditExt(extDomain);
            },

            hasBacklink(status) {
                if (status == 70) {
                    return true
                }
                return false
            },
            async addBackLink(ext) {
                this.modalAddBackLink = true
                this.modelBaclink.ext_domain_id = ext.id
                this.modelBaclink.ext_name = ext.domain
                let element = this.$refs.modalBacklink
                $(element).modal('show')
            },
            async addBackLinkIndex(index) {
                let extDomain = this.listExt.data[index];
                this.addBackLink(extDomain);
            },
            async fillterIntByCountry (event) {
                if (typeof event == 'undefined') {
                    var countryID = 0
                } else {
                    var countryID = event.target.value
                }
                this.loadIntDomain =  true
                await this.$store.dispatch('getListInt', {
                    params: {
                        country_id: countryID,
                        full_page: true
                    }
                });
                this.loadIntDomain = false
            },
            async submitAddBacklink () {
                await this.$store.dispatch('actionSaveBacklink', {
                    params: this.modelBaclink
                })

                if (this.messageForms.action === 'saved_backlink') {
                    this.closeModalBacklink()
                }
            },
            handleCloseBacklinkModal () {
                this.modelBaclink = {
                    int_domain_id: 0,
                    ext_name: ''
                }
                this.$store.dispatch('clearMessageBacklinkForm')
            },
            closeModalBacklink() {
                let element = this.$refs.modalBacklink
                $(element).modal('hide')
            },
            closeModalEmail() {
                let element = this.$refs.modalEmail
                $(element).modal('hide')
            },
            async getAhrefs() {
                var listInvalid = this.checkIds.some(ext => ext.status != 30);
                if (listInvalid === true) {
                    alert('List invalid: status diff with GotContacts');
                    return;
                }
                var listIds = this.checkIds.map(ext => ext.id).join(',');
                this.isLoadingTable = true;
                await this.$store.dispatch('getListAhrefs', { params: { domain_ids: listIds } });
                this.isLoadingTable = false;
                var that = this;
                this.checkIds.forEach(item => {
                    if (that.listAhrefs.hasOwnProperty(item.id)) {
                        let itemAherf = that.listAhrefs[item.id];
                        item.ahrefs_rank = itemAherf.ahrefs_rank;
                        item.no_backlinks = itemAherf.no_backlinks;
                        item.url_rating = itemAherf.url_rating;
                        item.domain_rating = itemAherf.domain_rating;
                        item.organic_keywords = itemAherf.organic_keywords;
                        item.organic_traffic = itemAherf.organic_traffic;
                        item.ref_domains = itemAherf.ref_domains;
                        item.status = itemAherf.status;
                    }
                });
            },
            async getAhrefsById(extId, extStatus) {
                if (extStatus != 30) {
                    alert('List invalid: status diff with GotContacts');
                    return;
                }
                var listIds = extId;
                this.isLoadingTable = true;
                await this.$store.dispatch('getListAhrefs', { params: { domain_ids: listIds } });
                this.isLoadingTable = false;
                var that = this;
                this.listExt.data.forEach(item => {
                    if (that.listAhrefs.hasOwnProperty(item.id)) {
                        let itemAherf = that.listAhrefs[item.id];
                        item.ahrefs_rank = itemAherf.ahrefs_rank;
                        item.no_backlinks = itemAherf.no_backlinks;
                        item.url_rating = itemAherf.url_rating;
                        item.domain_rating = itemAherf.domain_rating;
                        item.organic_keywords = itemAherf.organic_keywords;
                        item.organic_traffic = itemAherf.organic_traffic;
                        item.ref_domains = itemAherf.ref_domains;
                        item.status = itemAherf.status;
                    }
                });
            },
            openModalEmailElem() {
                let element = this.$refs.modalEmail;
                $(element).modal('show');
                this.allowSending = true;
            },

            doSendEmail(ext, event) {
                this.$store.dispatch('clearMessageForm');

                if (ext == null) {
                    if (this.checkIds.length == 0) {
                         swal.fire('No Selected', 'Please select first', 'error');

                    } else {
                        this.openModalEmailElem();
                        var emails = [];
                        for (var index in this.checkIds) {
                            if (this.checkIds[index].email != "") {
                                emails.push(this.checkIds[index].email)
                            }
                        }
                        this.email_to = emails.join('|')
                    }  
                    // console.log(this.checkIds)
                    // return false;
                }

                if (ext != null) {
                    if (ext.status == 50) {
                        swal.fire('Invalid', 'This is Already Contacted', 'error')
                    }
                    else if (ext.email == "") {
                        swal.fire('No email', 'Please check if with email', 'error' )
                    } 
                    else {
                        this.openModalEmailElem();
                        this.email_to = ext.email;
                        this.extDomain_id = ext.id;
                    }
                }
                    
            },

            getStatus() {
                axios.get('/api/mail/status')
                    .then((res) => {
                        console.log(res.data)
                    })
            },


            async submitSendMail() {
                this.allowSending = false;
                this.isPopupLoading = true;

                await this.$store.dispatch('sendMailWithMailgun',{
                    cc: '',
                    email: this.email_to,
                    title: this.modelMail.title,
                    content: this.modelMail.content,
                    attachment: 'undefined',
                })

                this.modelMail = {
                    title: '',
                    content: '',
                    mail_name: '',
                }

                // this.getStatus();
                let result = true;
                if( this.extDomain_id == '' ) {
                    result = false;
                    this.updateStatus.status = 50;
                }

                this.doUpdateMultipleStatus(result, this.extDomain_id);
                $("#modal-email").modal('hide')

                this.isPopupLoading = false;
            },


            async doUpdateMultipleStatus(is_sending, id) {
                await this.$store.dispatch('actionUpdateMultipleStatus', {
                    id: is_sending ? id:this.checkIds,
                    seller: this.updateStatus.seller,
                    status: is_sending ? 50:this.updateStatus.status,
                });

                if( this.messageForms.action == 'updated' ){
                    let element = this.$refs.modalMultipleStatus
                    $(element).modal('hide')
                    
                    // this.getExtList();

                    if(is_sending) {
                        let obj = this.listExt.data.findIndex(o => o.id === id);
                        this.listExt.data[obj].status = 50;
                    } else {
                        for(var index in this.checkIds) {
                            var id = this.checkIds[index].id
                            var obj = this.listExt.data.findIndex(o => o.id === id);
                            this.listExt.data[obj].status = this.updateStatus.status;
                        }
                    }

                    this.checkIds = []
                    this.updateStatus.seller = '';
                    this.updateStatus.status = '';
                    let message = is_sending ? 'Email send' : 'Successfully Updated'
                    swal.fire(
                        'Done',
                        message,
                        'success'
                    )
                }
            },


            // async doSendEmail(ext, event) {
            //     console.log(event);
            //     this.$store.dispatch('clearMessageForm');
            //     var ids = '';

            //     if(ext != null) {
            //         if (ext === -1) {
            //             ids = this.listExt.data.map(item => item.id).join(",");
            //             var ctemp = -1;
            //             if (this.listExt.data.some(item => {
            //                 if (ctemp === -1) ctemp = item.country;
            //                 return item.country.id !== ctemp.id;
            //             })) {
            //                 alert("can't not handle with multiple countries");
            //                 return;
            //             }
            //             if (this.listExt.data.some(item => {
            //                 return (item.status != 30 && item.status != 40);
            //             })) {
            //                 alert("can't not handle with external domain not have contacts or was contacted");
            //                 return;
            //             }
            //             if (this.listExt.data.some(item => {
            //                 return (item.email === '' || item.email.split('|').length > 1);
            //             })) {
            //                 alert("can't not handle with external domain not have email or multiple emails");
            //                 return;
            //             }
            //             this.mailInfo.ids = ids;
            //             this.mailInfo.receiver_text = " all list";
            //             this.mailInfo.country = ctemp;
            //             this.fetchTemplateMail(this.mailInfo.country.id);
            //             this.openModalEmailElem();
            //             return;
            //         }
            //         if (ext.status != 30 && ext.status != 40) {
            //             alert("can't not handle with external domain not have contacts or was contacted");
            //             return;
            //         }
            //         if (ext.email === '' || ext.email.split('|').length > 1) {
            //             alert("can't not handle with external domain not have email or multiple emails");
            //             return;
            //         }

            //         this.mailInfo.ids = ext.id;
            //         this.mailInfo.receiver_text = ext.domain;
            //         this.mailInfo.country = ext.country;
            //         this.fetchTemplateMail(this.mailInfo.country.id);
            //         this.openModalEmailElem();
            //     }

                
            //     if(ext == null) {
            //         var ext_id = [];
            //         var ext_domain = [];
            //         var ext_country = [];

            //         var i =0;
            //         this.checkIds.forEach(function(entry) {
            //             if (entry.status != 30 && entry.status != 40) {
            //                 alert("can't not handle with external domain not have contacts or was contacted");
            //                 return;
            //             }
            //             if (entry.email === '' || entry.email.split('|').length > 1) {
            //                 alert("can't not handle with external domain not have email or multiple emails");
            //                 return;
            //             }

            //             ext_id[i] = entry.id;
            //             ext_domain[i] = entry.domain;
            //             ext_country[i] = entry.country;

            //             i++;
            //         });

            //         ext_id = ext_id.join(", ");
            //         ext_domain = ext_domain.join(", ");

            //         this.mailInfo.ids = ext_id;
            //         this.mailInfo.receiver_text = ext_domain;
            //         this.mailInfo.country =ext_country[0];
            //         this.fetchTemplateMail(this.mailInfo.country.id);
            //         this.openModalEmailElem();
            //     }
            // },


            async doSendEmailIndex(index) {
                let extDomain = this.listExt.data[index];
                this.doSendEmail(extDomain);
            },
            async fetchTemplateMail(countryId) {
                this.isPopupLoading = true;
                await this.$store.dispatch('getListMails', { params: { country_id: countryId, full_page: 1} });
                this.isPopupLoading = false;
            },
            async doChangeEmailTemplate() {
                let that = this;
                this.modelMail = this.listMailTemplate.data.filter(item => item.id === that.mailInfo.tpl)[0];
            },
            async doChangeEmailCountry() {
                let that = this;
                this.mailInfo.country = this.listLanguages.data.filter(item => item.id === that.mailInfo.country.id)[0];
                this.fetchTemplateMail(this.mailInfo.country.id);
            },

            // async submitSendMail() {
            //     this.allowSending = false;
            //     this.isPopupLoading = true;
            //     await this.$store.dispatch('sendMail', { ids: this.mailInfo.ids, mail_name: this.modelMail.mail_name, title: this.modelMail.title, content: this.modelMail.content });
            //     this.isPopupLoading = false;
            // }
        },
        components: {
            DownloadCsv
        }
    }
</script>