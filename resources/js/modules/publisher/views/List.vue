<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                    <button class="btn btn-primary ml-5" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-plus"></i> Show Filter
                    </button>
                </div>

                <div class="box-body m-3 collapse" id="collapseExample">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Continent</label>
                                <select class="form-control" v-model="filterModel.continent_id">
                                    <option value="">All</option>
                                    <option v-for="option in listContinent.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

<!--                        <div class="col-md-3">-->
<!--                            <div class="form-group">-->
<!--                                <label for="">Country</label>-->
<!--                                <select class="form-control" v-model="filterModel.country_id">-->
<!--                                    <option value="">All</option>-->
<!--                                    <option v-for="option in listCountryContinent.data" v-bind:value="option.id">-->
<!--                                        {{ option.name }}-->
<!--                                    </option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Language</label>
                                <select class="form-control" v-model="filterModel.language_id">
                                    <option value="">All</option>
                                    <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">In charge</label>
                                <select class="form-control" v-model="filterModel.in_charge">
                                    <option value="">All</option>
                                    <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" v-if="user.isAdmin || user.isOurs == 0">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select class="form-control" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="option in listSeller.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Valid</label>
                                <v-select multiple v-model="filterModel.valid" :options="['valid','invalid','unchecked']" :searchable="false" placeholder="All"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Include Article</label>
                                <select class="form-control" v-model="filterModel.inc_article">
                                    <option value="">All</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Got Ahref</label>
                                <select class="form-control" v-model="filterModel.got_ahref">
                                    <option value="">All</option>
                                    <option value="With">With</option>
                                    <option value="Without">Without</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" class="form-control" v-model="filterModel.date">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search URL</label>
                                <input type="text" class="form-control" v-model="filterModel.search" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Accept Casino & Betting Sites</label>
                                <select class="form-control" v-model="filterModel.casino_sites">
                                    <option value="">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Topic</label>
                                <!-- <select class="form-control" v-model="filterModel.topic">
                                    <option value="">All</option>
                                    <option v-for="option in topic" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select> -->
                                <v-select multiple v-model="filterModel.topic" :options="topic" :searchable="false" placeholder="All"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Keyword Anchor</label>
                                <select class="form-control" v-model="filterModel.kw_anchor">
                                    <option value="">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Price Basis</label>
                                <select name="" class="form-control" v-model="filterModel.price_basis">
                                    <option value="">All</option>
                                    <option value="Good">Good</option>
                                    <option value="Average">Average</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3"
                             v-show="user.isAdmin || user.role_id == 8">
                            <div class="form-group">
                                <label for="">QC Validation</label>
                                <select name="" class="form-control" v-model="filterModel.qc_validation">
                                    <option value="">All</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3"
                             v-show="user.isAdmin || user.role_id == 8">
                            <div class="form-group">
                                <label for="">Show
                                              Duplicates</label>
                                <select name=""
                                        class="form-control" v-model="filterModel.show_duplicates">
                                    <option value="no">
                                        No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" v-show="user.isAdmin || user.role_id === 8 || user.role_id === 6">
                            <div class="form-group">
                                <label>Account Validation</label>
                                <select class="form-control" v-model="filterModel.account_validation">
                                    <option value="">All</option>
                                    <option value="valid">Valid</option>
                                    <option value="invalid">Invalid</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Publisher URL List</h3>

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select class="form-control float-right" @change="getPublisherList" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>
                    <button data-toggle="modal" @click="clearMessageform; checkSeller(); checkAccountValidity()" data-target="#modal-add-url" class="btn btn-success float-right"><i class="fa fa-plus"></i> Add URL</button>
                    <button v-if="user.isAdmin ||
                    user.role_id == 8"
                        class="btn btn-primary float-right" @click="generateBestPrices">Generate Best Prices</button>

                    <div class="form-row">
                        <div class="col-md-8 col-lg-6 my-3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" v-on:change="checkDataExcel(); checkAccountValidity()" enctype="multipart/form-data" ref="excel" name="file">
                                        <div class="input-group-btn">
                                            <button
                                                title="Upload CSV File"
                                                class="btn btn-primary btn-flat"
                                                :disabled="isEnableBtn || checkAccountValidity()"

                                                @click="submitUpload">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            <button
                                                title="Download CSV Template"
                                                class="btn btn-primary btn-flat"

                                                @click="downloadTemplate">
                                                <i class="fa fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                    <span v-if="messageForms.action == 'uploaded'" class="text-success">{{ messageForms.message }}</span>
                                </div>
                            </div>

                            <div class="row mt-3" v-show="showLang">
                                <div class="col-sm-12">
                                    <select class="form-control" name="language" ref="language" v-on:change="checkData">
                                        <option value="">Select language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div
                                v-if="checkAccountValidity() && showLang"
                                class="alert alert-error">

                                <p class="mb-0">
                                    <strong>Unable to add URL because of the following:</strong>
                                </p>

                                <ul class="font-italic">
                                    <li v-if="isAccountPaymentNotComplete">Account <strong>payment information</strong> not complete.</li>
                                    <li v-if="isAccountInvalid">Account status is <strong>invalid</strong>.</li>
                                </ul>

                                <div class="mb-0">
                                    <span>
                                        <span v-if="this.isAccountPaymentNotComplete">
                                            Please
                                            <router-link :to="{ path: `/profile/${user.id}` }">
                                                Click here
                                            </router-link>
                                            to complete your payment information in profile settings.
                                        </span>

                                        <span v-if="this.isAccountInvalid">
                                            Contact an administrator or person in charge to request
                                            for validation.
                                        </span>

                                        Logout to complete the process.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <small v-show="user.isOurs == 0" class="text-secondary">Reminder: The uploaded data is for Seller -List Publisher. The columns for the CSV file are URL, Price, Inc Article, Seller ID, Accept, Language and Topic. The columns should be separated using comma (,). Price are in USD. Inc Article and Accept value is Yes /No . Do not forget to select the language of the site.</small>
                            <small v-show="user.isOurs == 1" class="text-secondary">Reminder: The uploaded data is for Seller -List Publisher. The columns for the CSV file are URL, Price, Inc Article and KW Anchor. The columns should be separated using comma. (,) If you only have URL and Price is fine too. Price are in USD. Inc Article value is Yes /No. KW Anchor value is Yes/No. Do not forget to select the language of the site.</small>
                        </div>
                    </div>

                </div>

                <div class="box-body no-padding" style="overflow:auto!important;">
                    <div class="col-md-2 my-3">

                        <div class="input-group">
                            <button class="btn btn-default mr-2"
                                @click="selectAll">{{
                                                   allSelected
                                                   ?
                                                   "Deselect"
                                                   : "Select"
                                                   }} All
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Selected Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" @click="doMultipleEdit" data-toggle="modal" data-target="#modal-multiple-edit" href="#">Edit</a>
                                    <a class="dropdown-item" @click="doMultipleDelete" href="#">Delete</a>
                                    <a class="dropdown-item " @click="getAhrefs()" v-if="user.isAdmin || user.isOurs == 0">Get Ahref</a>
                                    <a class="dropdown-item " @click="validData('valid')" v-if="user.isAdmin || user.role_id != 6">Valid</a>
                                    <a class="dropdown-item " @click="validData('invalid')" v-if="user.isAdmin || user.role_id != 6">Invalid</a>
                                    <a class="dropdown-item " @click="validData('unchecked')" v-if="user.isAdmin || user.isOurs == 0">Unchecked</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="pagination-custom-footer-text">
                        <b>Showing {{ listPublish.from }} to {{ listPublish.to }} of {{ listPublish.total }} entries.</b>
                    </span>

                    <vue-virtual-table
                        v-if="!tableLoading"
                        width="100%"
                        :height="600"
                        :bordered="true"
                        :item-height="60"
                        :config="tableConfig"
                        :data="listPublish.data">
                        <template
                            slot-scope="scope"
                            slot="actionSelectRow">
                            <input type="checkbox"
                                   class="custom-checkbox"
                                   @change="checkSelected"
                                   :id="scope.row.id"
                                   :value="scope.row.id"
                                   v-model="checkIds">
                        </template>

                        <template
                            slot-scope="scope"
                            slot="createdData">
                            {{
                            displayDate(scope.row.created_at) }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="updatedData">
                            {{
                            displayDate(scope.row.updated_at) }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="continentData">
                            {{ scope.row.country_continent ?
                            scope.row.country_continent :
                            scope.row.publisher_continent }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="topicData">
                            {{ scope.row.topic == null ?
                            'N/A':scope.row.topic }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="inChargeData">
                            {{ scope.row.in_charge == null ?
                            'N/A':scope.row.in_charge }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="usernameData">
                            {{ scope.row.username ?
                            scope.row.username :
                            scope.row.user_name }}

                            <span
                                v-if="scope.row.user_account_validation === 'invalid'
                                && (user.isAdmin
                                || user.role_id === 8
                                || user.role_id === 6)"
                                class="badge badge-danger">
                                Invalid
                            </span>
                        </template>

                        <template
                            slot-scope="scope"
                            slot="urlData">
                            {{
                            replaceCharacters(scope.row.url) }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="priceData">
                            {{ scope.row.price == '' ||
                            scope.row.price == null ?
                            '':'$'}} {{
                            scope.row.price }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="priceBasisData">
                            <i
                                v-if="scope.row.price_basis == 'Good'" class="fa fa-star"
                                style="color: green;"></i>
                            <i
                                v-else-if="scope.row.price_basis == 'Average'" class="fa fa-star"
                               style="color: orange;"></i>
                            <i
                                v-else-if="scope.row.price_basis == 'High'"
                               class="fa fa-star"
                               style="color: red;"></i>
                        </template>

                        <template
                            slot-scope="scope"
                            slot="orgKeywordData">
                            {{
                            formatPrice(scope.row.org_keywords) }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="orgTrafficData">
                            {{
                            formatPrice(scope.row.org_traffic) }}
                        </template>

                        <template
                            slot-scope="scope"
                            slot="actionButtons">
                            <div class="btn-group">
                                <button
                                    data-toggle="modal"
                                    @click="doUpdate(scope.row)" data-target="#modal-update-publisher" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                            </div>
                        </template>
                    </vue-virtual-table>
                    <pagination :data="listPublish" @pagination-change-page="getPublisherList" :limit="8"></pagination>

                </div>
            </div>

        </div>

        <!-- Modal Update Publisher -->
        <div class="modal fade" id="modal-update-publisher" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input type="text" v-model="updateModel.company_name" class="form-control" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Username</label>
                                    <input type="text" v-model="updateModel.username" class="form-control" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.url}" class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" v-model="updateModel.url" class="form-control" placeholder="" :disabled="user.isOurs != 0">
                                    <span v-if="messageForms.errors.url" v-for="err in messageForms.errors.url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.language_id}" class="form-group">
                                    <label for="">Language</label>
                                    <select class="form-control" v-model="updateModel.language_id">
                                        <option value="">Select Language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.language_id" v-for="err in messageForms.errors.language_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label for="">Country</label>-->
<!--                                    <select class="form-control" v-model="updateModel.country_id">-->
<!--                                        <option value="">Select Country</option>-->
<!--                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">-->
<!--                                            {{ option.name }}-->
<!--                                        </option>-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Continent</label>
                                    <select class="form-control" v-model="updateModel.continent_id">
                                        <option value="">Select Continent</option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.price}" class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" v-model="updateModel.price" class="form-control" placeholder="">
                                    <span v-if="messageForms.errors.price" v-for="err in messageForms.errors.price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Include Article</label>
                                    <select class="form-control" v-model="updateModel.inc_article">
                                        <option value=""></option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.casino_sites}" class="form-group">
                                    <label for="">Accept Casino & Betting Sites</label>
                                    <select class="form-control" v-model="updateModel.casino_sites">
                                        <option value="">Select Casino & Bettings Sites</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.casino_sites" v-for="err in messageForms.errors.casino_sites" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.topic}" class="form-group">
                                    <label for="">Topic</label>
                                    <!-- <select class="form-control" v-model="updateModel.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select multiple v-model="updateModel.topic" :options="topic" :searchable="false" placeholder="Select Topic"/>
                                    <span v-if="messageForms.errors.topic" v-for="err in messageForms.errors.topic" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.kw_anchor}" class="form-group">
                                    <label for="">Keyword Anchor</label>
                                    <select class="form-control" v-model="updateModel.kw_anchor">
                                        <option value="">Select Option</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.kw_anchor" v-for="err in messageForms.errors.kw_anchor" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="user.isAdmin || user.role_id == 8">
                                <div class="form-group">
                                    <label for="">QC Validation</label>
                                    <select class="form-control" v-model="updateModel.qc_validation">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label for="">Team in Charge</label>
                                    <select class="form-control" v-model="updateModel.team_in_charge">
                                        <option value=""></option>
                                        <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username}}
                                        </option>
                                    </select>
                                </div>
                            </div> -->


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal update publisher -->

        <!-- Modal Add Publisher-->
        <div class="modal fade" id="modal-add-url" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add URL</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    v-if="checkAccountValidity()"
                                    class="alert alert-error">

                                    <p class="mb-0">
                                        <strong>Unable to add URL because of the following:</strong>
                                    </p>

                                    <ul class="font-italic">
                                        <li v-if="isAccountPaymentNotComplete">Account <strong>payment information</strong> not complete.</li>
                                        <li v-if="isAccountInvalid">Account status is <strong>invalid</strong>.</li>
                                    </ul>

                                    <div class="mb-0">
                                        <span>
                                            <span v-if="this.isAccountPaymentNotComplete">
                                                Please
                                                <router-link :to="{ path: `/profile/${user.id}` }">
                                                    Click here
                                                </router-link>
                                                to complete your payment information in profile settings.
                                            </span>

                                            <span v-if="this.isAccountInvalid">
                                                Contact an administrator or person in charge to request
                                                for validation.
                                            </span>

                                            Logout to complete the process.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.seller}" class="form-group">
                                    <label for="">Seller</label>
                                    <select class="form-control" v-model="addModel.seller" :disabled="user.role_id == 6 && user.isOurs == 1">
                                        <option value="">Select Seller</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.seller" v-for="err in messageForms.errors.seller" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.inc_article}" class="form-group">
                                    <label for="">Include Article</label>
                                    <select class="form-control" v-model="addModel.inc_article">
                                        <option value="">Select Include Article</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.inc_article" v-for="err in messageForms.errors.inc_article" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.url}" class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" v-model="addModel.url" class="form-control" placeholder="" >
                                    <span v-if="messageForms.errors.url" v-for="err in messageForms.errors.url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.language_id}" class="form-group">
                                    <label for="">Language</label>
                                    <select class="form-control" v-model="addModel.language_id">
                                        <option value="">Select Language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.language_id" v-for="err in messageForms.errors.language_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

<!--                            <div class="col-md-6">-->
<!--                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">-->
<!--                                    <label for="">Country</label>-->
<!--                                    <select class="form-control" v-model="addModel.country_id">-->
<!--                                        <option value="">Select Country</option>-->
<!--                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">-->
<!--                                            {{ option.name }}-->
<!--                                        </option>-->
<!--                                    </select>-->
<!--                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>-->
<!--                                </div>-->
<!--                            </div>-->

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.continent_id}" class="form-group">
                                    <label for="">Continent</label>
                                    <select class="form-control" v-model="addModel.continent_id">
                                        <option value="">Select Continent</option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.continent_id" v-for="err in messageForms.errors.continent_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.price}" class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" v-model="addModel.price" class="form-control" placeholder="">
                                    <span v-if="messageForms.errors.price" v-for="err in messageForms.errors.price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.casino_sites}" class="form-group">
                                    <label for="">Accept Casino & Betting Sites</label>
                                    <select class="form-control" v-model="addModel.casino_sites">
                                        <option value="">Select Casino & Bettings Sites</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <span v-if="messageForms.errors.casino_sites" v-for="err in messageForms.errors.casino_sites" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.topic}" class="form-group">
                                    <label for="">Topic</label>
                                    <!-- <select class="form-control" v-model="addModel.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select multiple v-model="addModel.topic" :options="topic" :searchable="false" placeholder="Select Topic"/>
                                    <span v-if="messageForms.errors.topic" v-for="err in messageForms.errors.topic" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Publisher -->

        <!-- Modal Settings -->
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
                            <div class="checkbox col-md-6" v-if="user.isAdmin || user.isOurs == 0">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(2,
                                tblPublisherOpt.created)"  :checked="tblPublisherOpt.created ? 'checked':''" v-model="tblPublisherOpt.created">Uploaded</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isAdmin || user.isOurs == 0">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(3,
                                tblPublisherOpt.uploaded)"
                                    :checked="tblPublisherOpt.uploaded ? 'checked':''" v-model="tblPublisherOpt.uploaded">Updated</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(4,
                                tblPublisherOpt.language)"
                                    :checked="tblPublisherOpt.language ? 'checked':''" v-model="tblPublisherOpt.language">Language</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(5,
                                tblPublisherOpt.country)"
                                    :checked="tblPublisherOpt.country ? 'checked':''" v-model="tblPublisherOpt.country">Country</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(6,
                                tblPublisherOpt.continent)"
                                    :checked="tblPublisherOpt.continent ? 'checked':''" v-model="tblPublisherOpt.continent">Continent</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(7,
                                tblPublisherOpt.topic)"
                                    :checked="tblPublisherOpt.topic ? 'checked':''" v-model="tblPublisherOpt.topic">Topic</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"@click="toggleColumn(8,
                                tblPublisherOpt.casino_sites)"
                                    :checked="tblPublisherOpt.casino_sites ? 'checked':''" v-model="tblPublisherOpt.casino_sites">Casino & Betting Sites</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(9,
                                tblPublisherOpt.in_charge)"
                                    :checked="tblPublisherOpt.in_charge ? 'checked':''" v-model="tblPublisherOpt.in_charge">In-charge</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(10,
                                tblPublisherOpt.seller)"
                                    :checked="tblPublisherOpt.seller ? 'checked':''" v-model="tblPublisherOpt.seller">Seller</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(11,
                                tblPublisherOpt.valid)"
                                    :checked="tblPublisherOpt.valid ? 'checked':''" v-model="tblPublisherOpt.valid">Valid</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(12,
                                tblPublisherOpt.url)"
                                    :checked="tblPublisherOpt.url ? 'checked':''" v-model="tblPublisherOpt.url">URL</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(13,
                                tblPublisherOpt.price)"
                                    :checked="tblPublisherOpt.price ? 'checked':''" v-model="tblPublisherOpt.price">Price</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(14,
                                tblPublisherOpt.price_basis)"  :checked="tblPublisherOpt.price_basis ? 'checked':''" v-model="tblPublisherOpt.price_basis">Price Basis</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(15,
                                tblPublisherOpt.inc_article)"  :checked="tblPublisherOpt.inc_article ? 'checked':''" v-model="tblPublisherOpt.inc_article">Inc Article</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(16,
                                tblPublisherOpt.kw_anchor)"
                                    :checked="tblPublisherOpt.kw_anchor ? 'checked':''" v-model="tblPublisherOpt.kw_anchor">Kw Anchor</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(17,
                                tblPublisherOpt.ur)"
                                    :checked="tblPublisherOpt.ur ? 'checked':''" v-model="tblPublisherOpt.ur">UR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(18,
                                tblPublisherOpt.dr)"
                                    :checked="tblPublisherOpt.dr ? 'checked':''" v-model="tblPublisherOpt.dr">DR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(19,
                                tblPublisherOpt.backlinks)"
                                    :checked="tblPublisherOpt.backlinks ? 'checked':''" v-model="tblPublisherOpt.backlinks">Backlinks</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(20,
                                tblPublisherOpt.ref_domain)"
                                    :checked="tblPublisherOpt.ref_domain ? 'checked':''" v-model="tblPublisherOpt.ref_domain">Ref Domains</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(21,
                                tblPublisherOpt.org_keywords)" :checked="tblPublisherOpt.org_keywords ? 'checked':''" v-model="tblPublisherOpt.org_keywords">Organic Keywords</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(22,
                                tblPublisherOpt.org_traffic)" :checked="tblPublisherOpt.org_traffic ? 'checked':''" v-model="tblPublisherOpt.org_traffic">Organic Traffic</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-primary" @click="saveColumnSetting" data-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->


        <!-- Modal Failed to Upload -->
        <div class="modal fade" id="modal-failed-upload">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Failed to Upload</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed">
                            <tr>
                                <th colspan="2">Total Failed to Upload ({{failedUpload.total}})</th>
                            </tr>
                            <tr v-for="(ext, index) in failedUpload.message" :key="index">
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
        <!-- End Modal Failed to Upload -->


        <!-- Modal Multiple edit -->
        <div class="modal fade" id="modal-multiple-edit">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Multiple Edit (Selected: {{checkIds.length}} items)</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Language</label>
                                    <select class="form-control" v-model="updateMultiple.language">
                                        <option value=""></option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label>Country</label>-->
<!--                                    <select class="form-control" v-model="updateMultiple.country">-->
<!--                                        <option value=""></option>-->
<!--                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">-->
<!--                                            {{ option.name }}-->
<!--                                        </option>-->
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Continent</label>
                                    <select class="form-control" v-model="updateMultiple.continent">
                                        <option value=""></option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" class="form-control" placeholder="" v-model="updateMultiple.price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Accept Casino & Betting Sites</label>
                                    <select class="form-control" v-model="updateMultiple.casino_sites">
                                        <option value=""></option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keyword Anchor</label>
                                    <select class="form-control" v-model="updateMultiple.kw_anchor">
                                        <option value=""></option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Topic</label>
                                    <!-- <select class="form-control" v-model="updateMultiple.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select multiple v-model="updateMultiple.topic" :options="topic" :searchable="false" placeholder="All"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Include Article</label>
                                    <select class="form-control" v-model="updateMultiple.inc_article">
                                        <option value=""></option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" v-show="user.isAdmin || user.role_id == 8">
                                <div class="form-group">
                                    <label>QC Validation</label>
                                    <select class="form-control" v-model="updateMultiple.qc_validation">
                                        <option value=""></option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitMultipleEdit" class="btn btn-primary" data-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Multiple Edit -->

    </div>
</template>

<style>
    .pagination-custom-footer-text {
        margin: 20px;
        margin-top: -40px;
    }
    #vs1__combobox {
        height: 38px;
    }

    #tbl-publisher {
        table-layout: fixed;
        width: 100% !important;
    }
    #tbl-publisher .resize{
         /*width: auto !important;*/
        white-space: normal;
        text-overflow: initial;
        overflow: hidden;
    }
</style>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';
    import VueVirtualTable from 'vue-virtual-table';
    import { csvTemplateMixin } from "../../../mixins/csvTemplateMixin";

    export default {
        components: {
            VueVirtualTable,
        },
        name: '',
        mixins: [csvTemplateMixin],
        data(){
            return {
                paginate: [50,150,250,350,'All'],
                updateMultiple: {
                    inc_article: '',
                    topic: '',
                    kw_anchor: '',
                    casino_sites: '',
                    price: '',
                    // country: '',
                    continent: '',
                    language: '',
                    qc_validation: '',
                },
                updateModel: {
                    id: '',
                    company_name: '',
                    name: '',
                    username:'',
                    url: '',
                    ur: '',
                    dr: '',
                    backlinks: '',
                    ref_domain: '',
                    org_keywords: '',
                    org_traffic: '',
                    price: '',
                    language: '',
                    inc_article: '',
                    language_id: '',
                    casino_sites: '',
                    topic: '',
                    kw_anchor: '',
                    // country_id: '',
                    continent_id: '',
                    // team_in_charge: '',
                    // team_in_charge_old: '',
                    user_id: '',
                    qc_validation: '',
                },
                isEnableBtn: true,
                isPopupLoading: false,
                filterModel: {
                    // country_id: this.$route.query.country_id || '',
                    continent_id: parseInt(this.$route.query.continent_id) || '',
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    inc_article: this.$route.query.inc_article || '',
                    seller: this.$route.query.seller || '',
                    paginate: this.$route.query.paginate || 50,
                    got_ahref: this.$route.query.got_ahref || '',
                    date: this.$route.query.date || '',
                    valid: this.$route.query.valid || '',
                    in_charge: this.$route.query.in_charge || '',
                    casino_sites: this.$route.query.casino_sites || '',
                    topic: this.$route.query.topic || '',
                    kw_anchor: this.$route.query.kw_anchor || '',
                    price_basis: this.$route.query.price_basis || '',
                    qc_validation: this.$route.query.qc_validation || '',
                    show_duplicates:
                        this.$route.query.show_duplicates
                        || 'no',
                    account_validation: this.$route.query.account_validation || '',
                },
                searchLoading: false,
                checkIds: [],
                isDisabled: true,
                showLang: false,
                isSeller: false,
                allSelected: false,
                isSearching: false,
                tableLoading: false,
                addModel: {
                    seller: '',
                    url: '',
                    language_id: '',
                    price: '',
                    inc_article: '',
                    casino_sites: '',
                    topic: '',
                    // country_id: '',
                    continent_id: '',
                },
                topic: [
                    'Beauty',
                    'Charity',
                    'Cooking',
                    'Crypto',
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
                    'Travel',
                    'Unlisted',
                ],
                failedUpload: {
                    total: 0,
                    message: [],
                },
                isAccountInvalid: false,
                isAccountPaymentNotComplete: false,
            }
        },

        async created() {
            this.getPublisherList();
            this.checkAccountType();
            this.getListSeller();
            this.getListContinents();

            // let countries = this.listCountries.data;
            // if( countries.length === 0 ){
                this.getListCountries();
            // }

            let in_charge = this.listIncharge.data;
            if( in_charge.length === 0 ){
                this.getTeamInCharge();
            }

            let language = this.listLanguages.data;
            if ( language.length === 0 ) {
                this.getListLanguages();
            }
        },

        mounted() {
            pusher.logToConsole = true;

            const channel = pusher.subscribe('private-user.' +
                this.user.id);

            channel.bind('prices.generate', (e) => {
                this.getPublisherList();

                swal.fire({
                    icon: 'success',
                    title: "Success",
                    text:
                        'Best Price generation has been completed..',
                    confirmButtonText: "Ok",
                });
            });
        },

        computed:{
            ...mapState({
                tblPublisherOpt: state => state.storePublisher.tblPublisherOpt,
                listPublish: state => state.storePublisher.listPublish,
                messageForms: state => state.storePublisher.messageForms,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listContinent: state => state.storePublisher.listContinent,
                listCountryContinent: state => state.storePublisher.listCountryContinent,
                user: state => state.storeAuth.currentUser,
                listSeller: state => state.storePublisher.listSeller,
                listAhrefsPublisher: state => state.storePublisher.listAhrefsPublisher,
                listIncharge: state => state.storeAccount.listIncharge,
                listLanguages: state => state.storePublisher.listLanguages,
            }),

            tableConfig() {
                return [
                    {
                        prop : '_index',
                        name : '#',
                        width : '50',
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : ' ',
                        actionName : 'actionSelectRow',
                        width: '50',
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : 'Uploaded',
                        actionName : 'createdData',
                        width: 100,
                        isHidden: !this.user.isAdmin ||
                            this.user.isOurs != 0 ||
                            this.user.role_id != 6
                    },
                    {
                        prop : '_action',
                        name : 'Updated',
                        actionName : 'updatedData',
                        width: 100,
                        isHidden: !this.user.isAdmin ||
                            this.user.isOurs != 0
                    },
                    {
                        prop : 'language_name',
                        name : 'Language',
                        sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.language
                    },
                    {
                        prop : 'country_name',
                        name : 'Country',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.country
                    },
                    {
                        prop : '_action',
                        name : 'Continent',
                        actionName : 'continentData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.continent
                    },
                    {
                        prop : '_action',
                        name : 'Topic',
                        actionName : 'topicData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.topic
                    },
                    {
                        prop : 'casino_sites',
                        name : 'Casino & Betting Sites',
                        sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.casino_sites
                    },
                    {
                        prop : '_action',
                        name : 'In-charge',
                        actionName : 'inChargeData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.in_charge
                    },
                    {
                        prop : '_action',
                        name : 'Seller',
                        actionName : 'usernameData',
                        width: 100,
                        isHidden: !this.user.isAdmin ||
                            this.user.isOurs != 0
                    },
                    {
                        prop : 'valid',
                        name : 'Valid',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.valid
                    },
                    {
                        prop : '_action',
                        name : 'URL',
                        actionName : 'urlData',
                        width: 150,
                        isHidden: !this.tblPublisherOpt.url
                    },
                    {
                        prop : '_action',
                        name : 'Price',
                        actionName : 'priceData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.price
                    },
                    {
                        prop : '_action',
                        name : 'Price Basis',
                        actionName : 'priceBasisData',
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.price_basis
                    },
                    {
                        prop : 'inc_article',
                        name : 'Inc Article',
                        sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.inc_article
                    },
                    {
                        prop : 'kw_anchor',
                        name : 'KW Anchor',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.kw_anchor
                    },
                    {
                        prop : 'ur',
                        name : 'UR',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.ur
                    },
                    {
                        prop : 'dr',
                        name : 'DR',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.dr
                    },
                    {
                        prop : 'backlinks',
                        name : 'Backlinks',
                        sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.backlinks
                    },
                    {
                        prop : 'ref_domain',
                        name : 'Ref Domain',
                        sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.ref_domain
                    },
                    {
                        prop : '_action',
                        name : 'Org Keywords',
                        actionName : 'orgKeywordData',
                        width: 120,
                        isHidden: !this.tblPublisherOpt.org_keywords
                    },
                    {
                        prop : '_action',
                        name : 'Org Traffic',
                        actionName : 'orgTrafficData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.org_traffic
                    },
                    {
                        prop : '_action',
                        name : 'Action',
                        actionName : 'actionButtons',
                        width : '150',
                        isHidden: false
                    },
                ];
            }
        },

        methods: {
            async generateBestPrices() {
                await
                    this.$store.dispatch('generateBestPrices');

                swal.fire({
                    icon: 'success',
                    title: "Success",
                    text:
                        'The system is now generating best prices, please wait...',
                    confirmButtonText: "Ok",
                });
            },

            async saveColumnSetting() {
                let loader = this.$loading.show();
                this.toggleTableLoading();

                await new Promise(resolve => {
                    setTimeout(resolve, 2000)
                })

                this.toggleTableLoading();
                loader.hide();
            },

            toggleColumn(index, columnState) {
                this.tableConfig[index].isHidden =
                    columnState;
            },

            toggleTableLoading() {
                if (this.tableLoading) {
                    this.tableLoading = false;
                } else {
                    this.tableLoading = true;
                }
            },

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            // displayStar(price_basis) {
            //     let star = '';
            //     if( price_basis == 'Good'  ){
            //         star = '<i class="fa fa-star" style="color: green;"></i>';
            //     }
            //
            //     if( price_basis == 'High'  ){
            //         star = '<i class="fa fa-star" style="color: red;"></i>';
            //     }
            //
            //     if( price_basis == 'Average'  ){
            //         star = '<i class="fa fa-star" style="color: orange;"></i>';
            //     }
            //
            //     return star;
            // },

            setDefaultSettings() {
                if( this.user.isOurs == 0 ){
                    this.tblPublisherOpt.casino_sites = false;
                    this.tblPublisherOpt.topic = false;
                    this.tblPublisherOpt.in_charge = false;
                }

                if (this.user.role_id == 6){
                    this.tblPublisherOpt.uploaded = false;
                    this.tblPublisherOpt.topic = false;
                    this.tblPublisherOpt.casino_sites = false;
                    this.tblPublisherOpt.in_charge = true;
                }

                this.tblPublisherOpt.country = false;
            },

            async getTeamInCharge(){
                await this.$store.dispatch('actionGetTeamInCharge');
            },

            async getPublisherList(page = 1) {
                let loader = this.$loading.show();
                this.searchLoading = true;
                this.isSearching = true;
                if(this.filterModel.paginate == 'All')
                {

                    await this.$store.dispatch('getListPublisher', {
                        params: {
                            // country_id: this.filterModel.country_id,
                            continent_id: this.filterModel.continent_id,
                            search: this.filterModel.search,
                            language_id: this.filterModel.language_id,
                            inc_article: this.filterModel.inc_article,
                            seller: this.filterModel.seller,
                            paginate: 1000000,
                            got_ahref: this.filterModel.got_ahref,
                            date: this.filterModel.date,
                            valid: this.filterModel.valid,
                            in_charge: this.filterModel.in_charge,
                            casino_sites: this.filterModel.casino_sites,
                            topic: this.filterModel.topic,
                            kw_anchor: this.filterModel.kw_anchor,
                            price_basis: this.filterModel.price_basis,
                            qc_validation: this.filterModel.qc_validation,
                            page: page,
                            show_duplicates:
                            this.filterModel.show_duplicates,
                            account_validation: this.filterModel.account_validation
                        }
                    });
                }else{
                    await this.$store.dispatch('getListPublisher', {
                        params: {
                            // country_id: this.filterModel.country_id,
                            continent_id: this.filterModel.continent_id,
                            search: this.filterModel.search,
                            language_id: this.filterModel.language_id,
                            inc_article: this.filterModel.inc_article,
                            seller: this.filterModel.seller,
                            paginate: this.filterModel.paginate,
                            got_ahref: this.filterModel.got_ahref,
                            date: this.filterModel.date,
                            valid: this.filterModel.valid,
                            in_charge: this.filterModel.in_charge,
                            casino_sites: this.filterModel.casino_sites,
                            topic: this.filterModel.topic,
                            kw_anchor: this.filterModel.kw_anchor,
                            price_basis: this.filterModel.price_basis,
                            qc_validation: this.filterModel.qc_validation,
                            page: page,
                            show_duplicates:
                            this.filterModel.show_duplicates,
                            account_validation: this.filterModel.account_validation
                        }
                    });
                }

                let columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13, className: 'text-center' },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: true, targets: 16 },
                        { orderable: true, targets: 17 },
                        { orderable: true, targets: 18 },
                        { orderable: true, targets: 19 },
                        { orderable: true, targets: 20 },
                        { orderable: true, targets: 21 },
                        { orderable: false, targets: '_all' }
                    ];

                if( this.user.isOurs == 0 && !this.user.isAdmin){
                    columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13, className: 'text-center' },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: true, targets: 16 },
                        { orderable: true, targets: 17 },
                        { orderable: true, targets: 18 },
                        { orderable: true, targets: 19 },
                        { orderable: true, targets: 20 },
                        { orderable: true, targets: 21 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                if( this.user.isOurs == 1 ){
                    columnDefs = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: true, targets: 9 },
                        { orderable: true, targets: 10 },
                        { orderable: true, targets: 11 },
                        { orderable: true, targets: 12 },
                        { orderable: true, targets: 13 },
                        { orderable: true, targets: 14 },
                        { orderable: true, targets: 15 },
                        { orderable: true, targets: 16 },
                        { orderable: true, targets: 17 },
                        { orderable: true, targets: 18 },
                        { orderable: false, targets: '_all' }
                    ]
                }

                this.searchLoading = false;
                this.isSearching = false;

                this.setDefaultSettings()

                loader.hide();
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            checkAhref( publish ) {
                let check = false;

                if( publish.ur != 0 ){
                    check = true;
                }

                return check;
            },

            selectAll: function() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var publisher in this.listPublish.data) {
                        // let ahref = this.checkAhref(this.listPublish.data[publisher])
                        // if( !ahref ){
                            this.checkIds.push(this.listPublish.data[publisher].id);
                        // }

                    }
                    this.isDisabled = false;
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
            },

            async validData(valid) {
                await this.$store.dispatch('actionValidData', {
                    valid: valid,
                    ids: this.checkIds,
                });

                if( this.messageForms.action === 'saved' ){

                    if (this.messageForms.errors) {

                        let html = '';
                        for (var index in this.messageForms.errors) {
                            var txt = this.messageForms.errors[index].message == 'existing' ? '<b style="color:red;">Invalid</b> URL has already existing Valid URL ':'<b style="color:green;">Successfully validated</b> '

                            html += txt +'<b>' + this.messageForms.errors[index].url + '</b> <br/>';
                        }
                       
                        swal.fire({
                            icon: '',
                            title: " ",
                            html: html,
                            confirmButtonText: "Ok",
                        });

                        this.getPublisherList();
                        
                    }
                }

                this.checkIds = [];
            },

            select: function() {
                this.allSelected = false;
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
                return val;
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");
                let char4 = char3.replace("/", "");

                return char4;
            },

            displayDate(date) {
                return date.split(" ")[0];
            },

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    console.log(this.checkIds.length);
                    this.isDisabled = false;
                }
            },

            doMultipleEdit() {
                this.updateMultiple = {
                    language: '',
                    country: '',
                    price: '',
                    casino_sites: '',
                    kw_anchor: '',
                    topic: '',
                    inc_article: '',
                    qc_validation: '',
                }
            },

            submitMultipleEdit() {
                axios.post('/api/update-multiple-publisher',{
                    ids: this.checkIds,
                    language: this.updateMultiple.language,
                    // country: this.updateMultiple.country,
                    continent_id: this.updateMultiple.continent,
                    price: this.updateMultiple.price,
                    casino_sites: this.updateMultiple.casino_sites,
                    kw_anchor: this.updateMultiple.kw_anchor,
                    topic: this.updateMultiple.topic,
                    inc_article: this.updateMultiple.inc_article,
                    qc_validation: this.updateMultiple.qc_validation,
                })
                .then((res) => {
                    this.checkIds = [];

                    this.getPublisherList();
                    this.isDisabled = true;

                    swal.fire(
                        'Saved!',
                        'Successfully Updated.',
                        'success'
                    )

                })
            },

            async doMultipleDelete(){
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm("Are you sure you want to delete selected records?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: this.checkIds,
                        }
                    });

                    this.getPublisherList();
                    this.checkIds = []
                    this.isDisabled = true;
                }
            },

            async submitAdd() {
                this.addModel.account_valid = this.checkAccountValidity();

                await this.$store.dispatch('actionAddUrl', this.addModel);

                if (this.messageForms.action === 'saved'){
                    $("#modal-add-url").modal('hide')
                    this.getPublisherList();

                    swal.fire(
                        'Saved!',
                        'URL has been saved.',
                        'success'
                        )


                    this.addModel = {
                        seller: '',
                        inc_article: '',
                        url: '',
                        language_id: '',
                        price: '',
                        casino_sites: '',
                        topic: '',
                        // country_id: '',
                        continent_id: '',
                    }
                } else {
                    swal.fire(
                        'Failed!',
                        'URL has not been saved.',
                        'error'
                    )
                }
            },

            async submitUpload() {
                $('#tbl-publisher').DataTable().destroy();

                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);
                this.formData.append('language', this.$refs.language.value);
                this.formData.append('account_valid', this.checkAccountValidity());

                await this.$store.dispatch('actionUploadCsv', this.formData);

                this.isEnableBtn = true;

                if (this.messageForms.action === 'uploaded') {
                    this.getPublisherList();
                    this.$refs.excel.value = '';
                    this.$refs.language.value = '';
                    this.showLang = false;

                    swal.fire(
                        'Uploaded!',
                        'Successfully Uploaded.',
                        'success'
                        )

                    console.log(this.messageForms.errors)

                    let cnt_failed = this.messageForms.errors.length;
                    if (cnt_failed > 0){
                        for (let key in this.messageForms.errors ){
                            this.failedUpload.message.push(this.messageForms.errors[key].message)
                        }

                        this.failedUpload.total = cnt_failed;
                        $("#modal-failed-upload").modal('show')
                    }
                } else {
                    swal.fire(
                        'Failed!',
                        'Upload Failed.',
                        'error'
                    )

                }
            },

            checkAccountType() {
                let that = this.user
                if( that.user_type ){
                    if( that.user_type.type == 'Seller' ){
                        this.isSeller = true;
                    }
                }
            },

            clearSearch() {
                $('#tbl-publisher').DataTable().destroy();

                this.filterModel = {
                    continent_id: '',
                    // country_id: '',
                    search: '',
                    language_id: '',
                    inc_article: '',
                    seller: '',
                    paginate: 50,
                    got_ahref: '',
                    date: '',
                    valid: '',
                    in_charge: '',
                    casino_sites: '',
                    topic: '',
                    kw_anchor: '',
                    price_basis: '',
                    qc_validation: '',
                    account_validation: ''
                }

                this.getPublisherList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
                $('#tbl-publisher').DataTable().destroy();

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdatePublisher', this.updateModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_publisher') {
                    this.getPublisherList();
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async getListContinents(params) {
                await this.$store.dispatch('actionGetListContinents', params);
            },

            async getAhrefs() {
                $('#tbl-publisher').DataTable().destroy();

                swal.fire({
                    title: "Getting Ahrefs...",
                    text: "Please wait",
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });

                var listIds = (this.checkIds).join(',');
                this.isLoadingTable = true;
                await this.$store.dispatch('getListAhrefsPublisher', { params: { domain_ids: listIds } });
                this.isLoadingTable = false;

                swal.fire(
                        'Updated!',
                        'Ahrefs has been updated.',
                        'success'
                        )

                this.checkIds = [];

                this.getPublisherList();
            },

            doUpdate(publish) {
                this.clearMessageform()
                let that = JSON.parse(JSON.stringify(publish))
                let topic = '';

                if(that.topic != null && that.topic != '') {
                    let _topic = that.topic;
                    if (_topic.indexOf(',') > -1) {
                        topic = _topic.split(',')
                    } else {
                        topic = _topic;
                    }
                }

                this.updateModel = {
                    id: that.id,
                    name: that.name,
                    username: that.username,
                    url: this.replaceCharacters(that.url),
                    language_id: that.language_id,
                    ur: that.ur,
                    dr: that.dr,
                    backlinks: that.backlinks,
                    ref_domain: that.ref_domain,
                    org_keywords: that.org_keywords,
                    org_traffic: that.org_traffic,
                    price: that.price,
                    anchor_text: that.anchor_text,
                    link: that.link,
                    inc_article: that.inc_article,
                    topic: topic,
                    casino_sites: that.casino_sites,
                    kw_anchor: that.kw_anchor,
                    // country_id: that.country_id,
                    continent_id: that.continent_id,
                    // team_in_charge: that.team_in_charge,
                    user_id: that.user_id,
                    qc_validation: that.qc_validation,
                    // team_in_charge_old: that.team_in_charge,
                }

                this.updateModel.company_name = that.isOurs == '0' ? 'Stalinks':that.company_name;

                $('#modal-update-publisher').modal({
                    show: true
                });
            },

            async doDelete(id){
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm("Do you want to delete these record?") ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: id,
                        }
                    });

                    this.getPublisherList();
                }
            },

            doSearch() {
                $('#tbl-publisher').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getPublisherList({
                    params: {
                        // country_id: this.filterModel.country_id,
                        continent_id: this.filterModel.continent_id,
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        inc_article: this.filterModel.inc_article,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        got_ahref: this.filterModel.got_ahref,
                        date: this.filterModel.date,
                        valid: this.filterModel.valid,
                        in_charge: this.filterModel.in_charge,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        kw_anchor: this.filterModel.kw_anchor,
                        price_basis: this.filterModel.price_basis,
                        qc_validation: this.filterModel.qc_validation,
                        show_duplicates:
                        this.filterModel.show_duplicates,
                        account_validation: this.filterModel.account_validation
                    }
                });
            },

            checkData() {
                this.isEnableBtn = true;
                if (this.$refs.language.value){
                    this.isEnableBtn = false;
                }
            },

            checkDataExcel() {
                if( this.user.isOurs == 1 ){
                    this.showLang = false;
                    if (this.$refs.excel.value){
                        this.showLang = true;
                    }
                }

                if( this.user.isOurs == 0 ){
                    this.isEnableBtn = true;
                    if (this.$refs.excel.value){
                        this.isEnableBtn = false;
                    }
                }
            },

            checkSeller(){
                this.addModel.seller = this.user.role_id === 6 ? this.user.id : '';
            },

            checkAccountValidity(){
                if (this.user.role_id == 6 && this.user.isOurs == 1) {
                    if (this.user.id_payment_type == null) {
                        this.isAccountPaymentNotComplete = true;
                    }

                    if (this.user.user_type) {
                        if (this.user.user_type.account_validation == 'invalid') {
                            this.isAccountInvalid = true;
                        }
                    }

                    return this.user.id_payment_type == null || this.isAccountInvalid;
                } else {
                    return false;
                }
            },

            // getCountriesByContinent() {
            //     this.filterModel.country_id = '';
            //     this.$store.dispatch('actionGetCountriesByContinentId', {
            //         continent_id: this.filterModel.continent_id
            //     })
            // },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },

            downloadTemplate() {
                let headers = [];

                let rows = this.user.isOurs === 0
                    ? ['URL', 'Price', 'Inc Article', 'Seller ID', 'Accept','Language', 'Topic']
                    : ['URL', 'Price', 'Inc Article', 'KW Anchor'];

                headers.push(rows);

                this.downloadCsvTemplate(headers, 'list_publisher_csv_template');
            }
        }

    }
</script>
