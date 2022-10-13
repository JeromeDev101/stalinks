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
                        <h3 class="card-title text-primary">{{ $t('message.publisher.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.publisher.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <!-- external users filters -->
                        <div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_search_url') }}</label>
                                        <input
                                            v-model="filterModel.search"
                                            type="text"
                                            class="form-control"
                                            :placeholder="$t('message.publisher.type')"
                                            aria-describedby="helpId">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_lang') }}</label>
                                        <select class="form-control" v-model="filterModel.language_id">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="na">N/A</option>
                                            <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_continent') }}</label>
                                        <v-select
                                            v-model="filterModel.continent_id"
                                            multiple
                                            label="name"
                                            :placeholder="$t('message.publisher.all')"
                                            :options="listContinent.data"
                                            :searchable="true"
                                            :reduce="continent => continent.id"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_country') }}</label>
                                        <v-select
                                            v-model="filterModel.country_id"
                                            multiple
                                            label="name"
                                            :placeholder="$t('message.publisher.all')"
                                            :searchable="true"
                                            :options="filterCountrySelect"
                                            :reduce="country => country.id"/>
                                        <!--                                        <select class="form-control" v-model="filterModel.country_id">-->
                                        <!--                                            <option value="">All</option>-->
                                        <!--                                            <option v-for="option in listCountryContinent.data" v-bind:value="option.id">-->
                                        <!--                                                {{ option.name }}-->
                                        <!--                                            </option>-->
                                        <!--                                        </select>-->

                                        <small class="font-italic text-primary" v-if="country_continent_filter_info">
                                            <i class="fa fa-exclamation-circle"></i>
                                            {{ country_continent_filter_info }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_dz') }}</label>
                                        <v-select
                                            v-model="filterModel.domain_zone"
                                            multiple
                                            label="name"
                                            :placeholder="$t('message.publisher.all')"
                                            :options="listDomainZones.data"
                                            :searchable="true"
                                            :reduce="domain => domain.name"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_https') }}</label>
                                        <select class="form-control" v-model="filterModel.is_https">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                            <option value="no">{{ $t('message.publisher.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_topic') }}</label>
                                        <!-- <select class="form-control" v-model="filterModel.topic">
                                            <option value="">All</option>
                                            <option v-for="option in topic" v-bind:value="option">
                                                {{ option }}
                                            </option>
                                        </select> -->
                                        <v-select
                                            v-model="filterModel.topic"
                                            multiple
                                            :placeholder="$t('message.publisher.all')"
                                            :options="topicFilter"
                                            :searchable="false"/>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="!isQc">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_ka') }}</label>
                                        <select class="form-control" v-model="filterModel.kw_anchor">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                            <option value="no">{{ $t('message.publisher.no') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_inc_art') }}</label>
                                        <select class="form-control" v-model="filterModel.inc_article">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="Yes">{{ $t('message.publisher.yes') }}</option>
                                            <option value="No">{{ $t('message.publisher.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="!isQc || user.role_id === 8">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_cb') }}</label>
                                        <select class="form-control" v-model="filterModel.casino_sites">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                            <option value="no">{{ $t('message.publisher.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_price_basis') }}</label>
                                        <v-select
                                            v-model="filterModel.price_basis"
                                            multiple
                                            :placeholder="$t('message.publisher.all')"
                                            :searchable="false"
                                            :options="['Good', 'Average', 'High']"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- internal team filters -->
                        <div v-if="user.isAdmin || user.isOurs === 0">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_in_charge') }}</label>
                                        <select class="form-control" v-model="filterModel.in_charge">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option v-for="option in listIncharge.data" v-bind:value="option.id">
                                                {{ option.username == null ? option.name:option.username}}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_seller') }}</label>
                                        <v-select
                                            v-model="filterModel.seller"
                                            multiple
                                            label="username"
                                            :placeholder="$t('message.publisher.all')"
                                            :searchable="true"
                                            :options="listSeller.data"
                                            :reduce="seller => seller.id"/>

                                        <!--                                        <select class="form-control" v-model="filterModel.seller">-->
                                        <!--                                            <option value="">All</option>-->
                                        <!--                                            <option v-for="option in listSeller.data" v-bind:value="option.id">-->
                                        <!--                                                {{ option.username == null ? option.name:option.username }}-->
                                        <!--                                            </option>-->
                                        <!--                                        </select>-->
                                    </div>
                                </div>

                                <div class="col-md-3" v-show="user.isAdmin || user.role_id === 8 || user.role_id === 10 || user.role_id === 6">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_account_validation') }}</label>
                                        <select class="form-control" v-model="filterModel.account_validation">
                                            <!-- <option value="">{{ $t('message.publisher.all') }}</option> -->
                                            <option value="valid">{{ $t('message.publisher.valid') }}</option>
                                            <option value="invalid">{{ $t('message.publisher.invalid') }}</option>
                                            <!-- <option value="processing">{{ $t('message.publisher.filter_processing') }}</option> -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_uploaded') }}</label>
                                        <!--                                    <input type="date" class="form-control" v-model="filterModel.date">-->

                                        <date-range-picker
                                            v-model="filterModel.uploaded"
                                            ref="picker"
                                            opens="right"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :dateRange="filterModel.uploaded"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"/>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="!isQc">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_updated') }}</label>
                                        <!--                                    <input type="date" class="form-control" v-model="filterModel.date">-->

                                        <date-range-picker
                                            v-model="filterModel.date"
                                            opens="left"
                                            ref="picker"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :dateRange="filterModel.date"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_got_ahref') }}</label>
                                        <select class="form-control" v-model="filterModel.got_ahref">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="With">{{ $t('message.publisher.with') }}</option>
                                            <option value="Without">{{ $t('message.publisher.without') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"
                                     v-show="user.isAdmin || user.role_id === 8 || (user.role_id === 6 && user.isOurs === 0) || user.role_id === 10">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_qc_valid') }}</label>
                                        <select name="" class="form-control" v-model="filterModel.qc_validation">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="na">N/A</option>
                                            <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                            <option value="no">{{ $t('message.publisher.no') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.valid') }}</label>
                                        <v-select
                                            v-model="filterModel.valid"
                                            multiple
                                            :placeholder="$t('message.publisher.all')"
                                            :searchable="false"
                                            :options="['valid','invalid','unchecked']"/>
                                    </div>
                                </div>

                                <div class="col-md-3" v-show="user.isAdmin || user.role_id === 8 || user.role_id === 10">
                                    <div class="form-group">
                                        <label>{{ $t('message.publisher.filter_show_dups') }}</label>
                                        <select class="form-control" v-model="filterModel.show_duplicates">
                                            <option value="">{{ $t('message.publisher.all') }}</option>
                                            <option value="no">{{ $t('message.publisher.no') }}</option>
                                            <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.publisher.clear') }}
                                </button>

                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.publisher.search') }}
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
                        <h3 class="card-title text-primary">{{ $t('message.publisher.pu_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-4 mt-2">
                                <div class="input-group">
                                    <input type="file"
                                           class="form-control"
                                           v-on:change="checkDataExcel(); checkAccountValidity()"
                                           enctype="multipart/form-data"
                                           ref="excel"
                                           name="file">

                                        <button
                                            :title="$t('message.publisher.pu_upload')"
                                            class="btn btn-primary btn-flat"
                                            :disabled="isEnableBtn || checkAccountValidity()"

                                            @click="submitUpload">
                                            <i class="fa fa-upload"></i>
                                        </button>

                                        <button
                                            :title="$t('message.publisher.pu_download')"
                                            class="btn btn-primary btn-flat"

                                            @click="downloadTemplate">
                                            <i class="fa fa-download"></i>
                                        </button>
                                </div>

                                <span v-if="messageForms.errors.file"
                                      v-for="err in messageForms.errors.file"
                                      class="text-danger">{{ err }}</span>
                                <span v-if="messageForms.action == 'uploaded'"
                                      class="text-success">{{ messageForms.message }}</span>
                            </div>

                            <div class="col-12 col-md-12 col-xl-8 mt-2">

                                <div class="input-group justify-content-end">
                                    <button
                                        v-if="user.isAdmin || user.role_id == 8"
                                        :disabled="isGenerating"
                                        class="btn btn-primary mr-2"

                                        @click="generateBestPrices">

                                        <span v-if="isGenerating">
                                            <i class="fa fa-spin fa-cog"></i> {{ $t('message.publisher.pu_generate') }}
                                        </span>

                                        <span v-else>
                                            {{ $t('message.publisher.pu_generate_best') }}
                                        </span>
                                    </button>

                                    <button
                                        data-toggle="modal"
                                        data-target="#modal-add-url"
                                        class="btn btn-success mr-2"

                                        @click="clearMessageform; checkSeller(); checkAccountValidity(); clearCountryContinentInfo()">

                                        <i class="fa fa-plus"></i> {{ $t('message.publisher.pu_add') }}
                                    </button>

                                    <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default mr-2">
                                        <i class="fa fa-cog"></i>
                                    </button>

                                    <select class="form-control w-25 d-inline-block"
                                            @change="getPublisherList"
                                            v-model="filterModel.paginate"
                                            style="min-width: 100px; max-width: 100px">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div
                                    v-if="checkAccountValidity() && showLang"
                                    class="alert alert-error">

                                    <p class="mb-0">
                                        <strong>{{ $t('message.publisher.pu_unable_to_add') }}</strong>
                                    </p>

                                    <ul class="font-italic">
                                        <li v-if="isAccountPaymentNotComplete">
                                            {{ $t('message.publisher.pu_account_info') }}
                                        </li>
                                        <li v-if="isAccountInvalid">
                                            {{ $t('message.publisher.pu_account_status') }}
                                            <strong>{{ user.user_type ? user.user_type.account_validation : 'invalid' }}</strong>.
                                        </li>
                                    </ul>

                                    <div class="mb-0">
                                    <span>
                                        {{ $t('message.publisher.pu_relog') }}
                                        <span v-if="this.isAccountPaymentNotComplete">
                                            {{ $t('message.publisher.pu_please') }}
                                            <router-link :to="{ path: `/profile/${user.id}` }">
                                                {{ $t('message.publisher.pu_click') }}
                                            </router-link>
                                            {{ $t('message.publisher.pu_complete') }}
                                        </span>

                                        <span v-if="this.isAccountInvalid">
                                            {{ $t('message.publisher.pu_contact') }}
                                        </span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <small v-show="user.isOurs == 0" class="text-secondary">
                                    {{ $t('message.publisher.pu_reminder') }}
                                    {{ $t('message.publisher.pu_columns') }}
                                </small>

                                <small v-show="user.isOurs == 1" class="text-secondary">
                                    {{ $t('message.publisher.pu_reminder_uploaded') }}
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 my-3">

                                <div class="input-group">
                                    <button
                                        class="btn btn-default mr-2"
                                        @click="selectAll">
                                        {{ allSelected ? $t('message.publisher.ab_deselect') : $t('message.publisher.ab_select') }}
                                        {{ $t('message.publisher.all') }}
                                    </button>

                                    <div class="dropdown mr-2">
                                        <button
                                            class="btn btn-default dropdown-toggle"
                                            :disabled="isDisabled"
                                            type="button"
                                            id="dropdownMenuButton"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">

                                            {{ $t('message.publisher.ab_selected_action') }}
                                        </button>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a
                                                class="dropdown-item"
                                                @click="doMultipleEdit"
                                                data-toggle="modal"
                                                data-target="#modal-multiple-edit"
                                                href="#">
                                                {{ $t('message.publisher.ab_edit') }}
                                            </a>

                                            <a class="dropdown-item" @click="doMultipleDelete" href="#">
                                                {{ $t('message.publisher.ab_delete') }}
                                            </a>

                                            <a
                                                class="dropdown-item "
                                                @click="getAhrefs()"
                                                v-if="user.isAdmin || user.isOurs == 0">
                                                {{ $t('message.publisher.ab_get_ahref') }}
                                            </a>
                                            <!--                                    <a class="dropdown-item " @click="validData('valid')" v-if="user.isAdmin || user.role_id != 6">Valid</a>-->
                                            <!--                                    <a class="dropdown-item " @click="validData('invalid')" v-if="user.isAdmin || user.role_id != 6">Invalid</a>-->
                                            <!--                                    <a class="dropdown-item " @click="validData('unchecked')" v-if="user.isAdmin || user.isOurs == 0">Unchecked</a>-->
                                            <a
                                                class="dropdown-item "
                                                @click="qcValidationUpdate('yes')"
                                                v-if="user.isAdmin || user.role_id == 8 || user.role_id === 10 ">
                                                {{ $t('message.publisher.ab_qc_yes') }}
                                            </a>

                                            <a
                                                class="dropdown-item "
                                                @click="qcValidationUpdate('no')"
                                                v-if="user.isAdmin || user.role_id == 8 || user.role_id === 10">
                                                {{ $t('message.publisher.ab_qc_no') }}
                                            </a>

                                            <a
                                                class="dropdown-item "
                                                @click="generateCountry"
                                                v-if="user.isAdmin || user.role_id == 8 || user.role_id === 10">
                                                {{ $t('message.publisher.ab_generate_country') }}
                                            </a>
                                        </div>
                                    </div>

                                    <export-excel
                                        :data=masterListDataMethod()
                                        type="csv"
                                        name="master_list.xls"
                                        worksheet="My Worksheet"
                                        class="btn btn-default">

                                        {{ $t('message.publisher.ab_download_list') }}
                                    </export-excel>

                                    <i
                                        class="fa fa-question-circle text-primary"
                                        :title="$t('message.publisher.ab_download_note')">

                                    </i>

                                </div>
                            </div>

                            <div class="col-md-4 my-3">
                                <div class="input-group justify-content-end">
                                    <Sort
                                        ref="sortComponent"
                                        :sorted="isSorted"
                                        :items="sortOptions"
                                        :custom-class="['w-25']"

                                        @submitSort="sortPublisher"
                                        @updateOptions="updateSortOptions">
                                    </Sort>
                                </div>
                            </div>
                        </div>

                        <span class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b>{{ $t('message.others.table_entries', { from: listPublish.from, to: listPublish.to, end: listPublish.total }) }}</b>
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

                            <!-- <template
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
                            </template> -->

                            <template
                                slot-scope="scope"
                                slot="continentData">
                                {{
                                    (scope.row.country_continent == null && scope.row.publisher_continent == null)
                                        ? 'N/A'
                                        : scope.row.publisher_continent
                                            ? scope.row.publisher_continent
                                            : scope.row.country_continent
                                }}
                            </template>

                            <!-- <template
                                slot-scope="scope"
                                slot="topicData">
                                {{ scope.row.topic == null ?
                                'N/A':scope.row.topic }}
                            </template> -->

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
                                || user.role_id === 10
                                || user.role_id === 6)"
                                    class="badge badge-danger">
                                {{ $t('message.publisher.invalid') }}
                            </span>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="urlData">
                                <!--                            {{ replaceCharacters(scope.row.url) }}-->

                                <a :href="(scope.row.is_https == 'yes' ? 'https://':'http://') + scope.row.url" target="_blank">
                                    {{ scope.row.url }}
                                </a>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="priceData">
                                {{ scope.row.price == '' || scope.row.price == null ? '' : '$' }} {{ formatPrice(scope.row.price) }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="pricesData">
                                {{ scope.row.price == '' || scope.row.price == null ? '' : '$' }}
                                {{ computePriceStalinks(scope.row.price, scope.row.inc_article) }}
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
                                        @click="doUpdate(scope.row)"
                                        data-target="#modal-update-publisher"
                                        title="Edit"
                                        class="btn btn-default">

                                        <i class="fa fa-fw fa-edit"></i>
                                    </button>
                                </div>
                            </template>
                        </vue-virtual-table>
                        <pagination :data="listPublish" @pagination-change-page="getPublisherList" :limit="8"></pagination>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Update Publisher -->
        <div class="modal fade" id="modal-update-publisher" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.publisher.ui_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.ui_company_name') }}</label>
                                    <input type="text" v-model="updateModel.company_name" class="form-control" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.ui_username') }}</label>
                                    <input type="text" v-model="updateModel.username" class="form-control" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div :class="{'has-error': messageForms.errors.url}" class="form-group">
                                    <label>{{ $t('message.publisher.ui_url') }}</label>
                                    <input type="text" v-model="updateModel.url" class="form-control" placeholder="" :disabled="user.isOurs != 0">
                                    <span v-if="messageForms.errors.url" v-for="err in messageForms.errors.url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.language_id}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_lang') }}</label>
                                    <select class="form-control" v-model="updateModel.language_id">
                                        <option value="">{{ $t('message.publisher.ui_select_language') }}</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.language_id" v-for="err in messageForms.errors.language_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_country') }}</label>

                                    <select class="form-control" v-model="updateModel.country_id"  @change="selectCountry('update')">
                                        <option value="">{{ $t('message.publisher.ui_select_country') }}</option>
                                        <option v-for="option in updateCountrySelect" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <small class="font-italic text-primary" v-if="country_continent_info">
                                        <i class="fa fa-exclamation-circle"></i>
                                        {{ country_continent_info }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_continent') }}</label>
                                    <select class="form-control" v-model="updateModel.continent_id">
                                        <option value="">{{ $t('message.publisher.ui_select_continent') }}</option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.price}" class="form-group">
                                    <label>{{ $t('message.publisher.t_price') }}</label>
                                    <input type="number" v-model="updateModel.price" class="form-control" placeholder="">
                                    <span v-if="messageForms.errors.price" v-for="err in messageForms.errors.price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.ui_inc_article') }}</label>
                                    <select class="form-control" v-model="updateModel.inc_article">
                                        <option value=""></option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.casino_sites}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_cb') }}</label>
                                    <select class="form-control" v-model="updateModel.casino_sites">
                                        <option value="">{{ $t('message.publisher.ui_select_cb') }}</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.casino_sites" v-for="err in messageForms.errors.casino_sites" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.topic}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_topic') }}</label>
                                    <!-- <select class="form-control" v-model="updateModel.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select
                                        multiple
                                        v-model="updateModel.topic"
                                        :options="topic"
                                        :searchable="false"
                                        :placeholder="$t('message.publisher.ui_select_topic')"/>

                                    <span
                                        v-if="messageForms.errors.topic"
                                        v-for="err in messageForms.errors.topic"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'has-error': messageForms.errors.kw_anchor}" class="form-group">
                                    <label>{{ $t('message.publisher.ui_keyword_anchor') }}</label>
                                    <select class="form-control" v-model="updateModel.kw_anchor">
                                        <option value="">{{ $t('message.publisher.ui_select_option') }}</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.kw_anchor" v-for="err in messageForms.errors.kw_anchor" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="user.isAdmin || user.role_id == 8 || user.role_id === 10">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_qc_valid') }}</label>
                                    <select class="form-control" v-model="updateModel.qc_validation">
                                        <option value="">N/A</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-md-6" >
                                <div class="form-group">
                                    <label>Team in Charge</label>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>

                        <button type="button" @click="submitUpdate" class="btn btn-primary">
                            {{ $t('message.publisher.update') }}
                        </button>
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
                        <h5 class="modal-title">{{ $t('message.publisher.au_title') }}</h5>
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
                                        <strong>{{ $t('message.publisher.pu_unable_to_add') }}</strong>
                                    </p>

                                    <ul class="font-italic">
                                        <li v-if="isAccountPaymentNotComplete">
                                            {{ $t('message.publisher.pu_account_info') }}
                                        </li>
                                        <li v-if="isAccountInvalid">
                                            {{ $t('message.publisher.pu_account_status') }}
                                            <strong>{{ user.user_type ? user.user_type.account_validation : 'invalid' }}</strong>.
                                        </li>
                                    </ul>

                                    <div class="mb-0">
                                        <span>
                                            {{ $t('message.publisher.pu_relog') }}
                                            <span v-if="this.isAccountPaymentNotComplete">
                                                {{ $t('message.publisher.pu_please') }}
                                                <router-link :to="{ path: `/profile/${user.id}` }">
                                                    {{ $t('message.publisher.pu_click') }}
                                                </router-link>
                                                {{ $t('message.publisher.pu_complete') }}
                                            </span>

                                            <span v-if="this.isAccountInvalid">
                                                {{ $t('message.publisher.pu_contact') }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" v-show="user.isOurs == 0">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.seller}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_seller') }}</label>
                                    <select class="form-control" v-model="addModel.seller" :disabled="user.role_id == 6 && user.isOurs == 1">
                                        <option value="">{{ $t('message.publisher.au_select_seller') }}</option>
                                        <option v-for="option in computedListSeller" v-bind:value="option.id">
                                            {{ option.username == null ? option.name:option.username }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.seller" v-for="err in messageForms.errors.seller" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.inc_article}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_inc_art') }}</label>
                                    <select class="form-control" v-model="addModel.inc_article">
                                        <option value="">{{ $t('message.publisher.au_select_inc_art') }}</option>
                                        <option value="Yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="No">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.inc_article" v-for="err in messageForms.errors.inc_article" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.url}" class="form-group">
                                    <label>{{ $t('message.publisher.t_url') }}</label>
                                    <input type="text" v-model="addModel.url" class="form-control" placeholder="" >
                                    <span v-if="messageForms.errors.url" v-for="err in messageForms.errors.url" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.language_id}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_lang') }}</label>
                                    <select class="form-control" v-model="addModel.language_id">
                                        <option value="">{{ $t('message.publisher.ui_select_language') }}</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.language_id" v-for="err in messageForms.errors.language_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_country') }}</label>

                                    <select class="form-control" v-model="addModel.country_id" @change="selectCountry('add')">
                                        <option value="">{{ $t('message.publisher.ui_select_country') }}</option>
                                        <option v-for="option in addCountrySelect" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <small class="font-italic text-primary" v-if="country_continent_info">
                                        <i class="fa fa-exclamation-circle"></i>
                                        {{ country_continent_info }}
                                    </small>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.continent_id}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_continent') }}</label>
                                    <select class="form-control" v-model="addModel.continent_id">
                                        <option value="">{{ $t('message.publisher.ui_select_continent') }}</option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.continent_id" v-for="err in messageForms.errors.continent_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.price}" class="form-group">
                                    <label>{{ $t('message.publisher.t_price') }}</label>
                                    <input type="number" v-model="addModel.price" class="form-control" placeholder="">
                                    <span v-if="messageForms.errors.price" v-for="err in messageForms.errors.price" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.casino_sites}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_cb') }}</label>
                                    <select class="form-control" v-model="addModel.casino_sites">
                                        <option value="">{{ $t('message.publisher.ui_select_cb') }}</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.casino_sites" v-for="err in messageForms.errors.casino_sites" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="user.role_id === 6 && user.isOurs === 1">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.kw_anchor}" class="form-group">
                                    <label>{{ $t('message.publisher.t_kw_anchor') }}</label>
                                    <select class="form-control" v-model="addModel.kw_anchor">
                                        <option value="">{{ $t('message.publisher.au_select_kw_anchor') }}</option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.kw_anchor" v-for="err in messageForms.errors.kw_anchor" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.topic}" class="form-group">
                                    <label>{{ $t('message.publisher.filter_topic') }}</label>
                                    <!-- <select class="form-control" v-model="addModel.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select
                                        multiple
                                        v-model="addModel.topic"
                                        :options="topic"
                                        :searchable="false"
                                        :placeholder="$t('message.publisher.ui_select_topic')"/>
                                    <span v-if="messageForms.errors.topic" v-for="err in messageForms.errors.topic" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                        <button type="button" @click="submitAdd" class="btn btn-primary" :disabled="isSubmitAdd">
                            {{ $t('message.publisher.add') }}
                        </button>
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
                        <h4 class="modal-title">{{ $t('message.publisher.sd_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6" v-if="user.isAdmin || user.isOurs == 0">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(2,
                                tblPublisherOpt.created)"  :checked="tblPublisherOpt.created ? 'checked':''" v-model="tblPublisherOpt.created">{{ $t('message.publisher.filter_uploaded') }}</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isAdmin || user.isOurs == 0">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(3,
                                tblPublisherOpt.uploaded)"
                                    :checked="tblPublisherOpt.uploaded ? 'checked':''" v-model="tblPublisherOpt.uploaded">{{ $t('message.publisher.filter_updated') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(4,
                                tblPublisherOpt.language)"
                                    :checked="tblPublisherOpt.language ? 'checked':''" v-model="tblPublisherOpt.language">{{ $t('message.publisher.filter_lang') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(5,
                                tblPublisherOpt.country)"
                                    :checked="tblPublisherOpt.country ? 'checked':''" v-model="tblPublisherOpt.country">{{ $t('message.publisher.filter_country') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(6,
                                tblPublisherOpt.continent)"
                                    :checked="tblPublisherOpt.continent ? 'checked':''" v-model="tblPublisherOpt.continent">{{ $t('message.publisher.filter_continent') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(7,
                                tblPublisherOpt.topic)"
                                    :checked="tblPublisherOpt.topic ? 'checked':''" v-model="tblPublisherOpt.topic">{{ $t('message.publisher.filter_topic') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"@click="toggleColumn(8,
                                tblPublisherOpt.casino_sites)"
                                    :checked="tblPublisherOpt.casino_sites ? 'checked':''" v-model="tblPublisherOpt.casino_sites">{{ $t('message.publisher.t_cb') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(9,
                                tblPublisherOpt.in_charge)"
                                    :checked="tblPublisherOpt.in_charge ? 'checked':''" v-model="tblPublisherOpt.in_charge">{{ $t('message.publisher.t_in_charge') }}</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(10,
                                tblPublisherOpt.seller)"
                                    :checked="tblPublisherOpt.seller ? 'checked':''" v-model="tblPublisherOpt.seller">{{ $t('message.publisher.filter_seller') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(11,
                                tblPublisherOpt.valid)"
                                    :checked="tblPublisherOpt.valid ? 'checked':''" v-model="tblPublisherOpt.valid">{{ $t('message.publisher.valid') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(12,
                                tblPublisherOpt.org_traffic)" :checked="tblPublisherOpt.qc_validation ? 'checked':''" v-model="tblPublisherOpt.qc_validation">{{ $t('message.publisher.filter_qc_valid') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(13,
                                tblPublisherOpt.url)"
                                    :checked="tblPublisherOpt.url ? 'checked':''" v-model="tblPublisherOpt.url">{{ $t('message.publisher.ui_url') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(15,
                                tblPublisherOpt.price)"
                                    :checked="tblPublisherOpt.price ? 'checked':''" v-model="tblPublisherOpt.price">{{ $t('message.publisher.t_price') }}</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(16,
                                tblPublisherOpt.prices)"
                                    :checked="tblPublisherOpt.prices ? 'checked':''" v-model="tblPublisherOpt.prices">{{ $t('message.publisher.t_prices') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(17,
                                tblPublisherOpt.price_basis)"  :checked="tblPublisherOpt.price_basis ? 'checked':''" v-model="tblPublisherOpt.price_basis">{{ $t('message.publisher.filter_price_basis') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(18,
                                tblPublisherOpt.inc_article)"  :checked="tblPublisherOpt.inc_article ? 'checked':''" v-model="tblPublisherOpt.inc_article">{{ $t('message.publisher.t_inc_article') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(19,
                                tblPublisherOpt.kw_anchor)"
                                    :checked="tblPublisherOpt.kw_anchor ? 'checked':''" v-model="tblPublisherOpt.kw_anchor">{{ $t('message.publisher.t_kw_anchor') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(20,
                                tblPublisherOpt.ur)"
                                    :checked="tblPublisherOpt.ur ? 'checked':''" v-model="tblPublisherOpt.ur">UR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(21,
                                tblPublisherOpt.dr)"
                                    :checked="tblPublisherOpt.dr ? 'checked':''" v-model="tblPublisherOpt.dr">DR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(22,
                                tblPublisherOpt.backlinks)"
                                    :checked="tblPublisherOpt.backlinks ? 'checked':''" v-model="tblPublisherOpt.backlinks">{{ $t('message.publisher.tb_backlinks') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(23,
                                tblPublisherOpt.ref_domain)"
                                    :checked="tblPublisherOpt.ref_domain ? 'checked':''" v-model="tblPublisherOpt.ref_domain">{{ $t('message.publisher.t_ref_dom') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(24,
                                tblPublisherOpt.org_keywords)" :checked="tblPublisherOpt.org_keywords ? 'checked':''" v-model="tblPublisherOpt.org_keywords">{{ $t('message.publisher.t_org_keywords') }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(25,
                                tblPublisherOpt.org_traffic)" :checked="tblPublisherOpt.org_traffic ? 'checked':''" v-model="tblPublisherOpt.org_traffic">{{ $t('message.publisher.t_org_traffic') }}</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isOurs == 0">
                                <label><input
                                    type="checkbox"
                                    @click="toggleColumn(26,
                                tblPublisherOpt.code_price)" :checked="tblPublisherOpt.code_price ? 'checked':''" v-model="tblPublisherOpt.code_price">{{ $t('message.publisher.t_code_price') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" @click="saveColumnSetting" data-dismiss="modal">
                            {{ $t('message.publisher.save') }}
                        </button>
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
                        <h4 class="modal-title">{{ $t('message.publisher.fu_title') }}</h4>
                        <div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <table class="table table-condensed">
                            <tr>
                                <th>
                                    <span class="text-danger">
                                        {{ $t('message.publisher.fu_total_failed') }} ({{failedUpload.invalid}})
                                    </span>
                                </th>

                                <th>
                                    <span class="text-success">
                                        Total uploaded ({{failedUpload.valid}})
                                    </span>
                                </th>
                            </tr>
                            <tr v-for="(ext, index) in failedUpload.message" :key="index">
                                <td>{{ ext }}</td>
                                <td class="text-danger">{{ $t('message.publisher.fu_not_uploaded') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
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
                        <h4 class="modal-title">{{ $t('message.publisher.me_title') }} (Selected: {{checkIds.length}} items)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_lang') }}</label>
                                    <select class="form-control" v-model="updateMultiple.language">
                                        <option value=""></option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_country') }}</label>

                                    <select class="form-control" v-model="updateMultiple.country" @change="selectCountry('multi')">
                                        <option value=""></option>
                                        <option v-for="option in multipleCountrySelect" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <small class="font-italic text-primary" v-if="country_continent_info">
                                        <i class="fa fa-exclamation-circle"></i>
                                        {{ country_continent_info }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_continent') }}</label>
                                    <select class="form-control" v-model="updateMultiple.continent_id">
                                        <option value=""></option>
                                        <option v-for="option in listContinent.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.t_price') }}</label>
                                    <input type="number" class="form-control" placeholder="" v-model="updateMultiple.price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_cb') }}</label>
                                    <select class="form-control" v-model="updateMultiple.casino_sites">
                                        <option value=""></option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_ka') }}</label>
                                    <select class="form-control" v-model="updateMultiple.kw_anchor">
                                        <option value=""></option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_topic') }}</label>
                                    <!-- <select class="form-control" v-model="updateMultiple.topic">
                                        <option value="">Select Topic</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select
                                        multiple
                                        v-model="updateMultiple.topic"
                                        :options="topic"
                                        :searchable="false"
                                        :placeholder="$t('message.publisher.all')"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_inc_art') }}</label>
                                    <select class="form-control" v-model="updateMultiple.inc_article">
                                        <option value=""></option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" v-show="user.isAdmin || user.role_id == 8 || user.role_id === 10">
                                <div class="form-group">
                                    <label>{{ $t('message.publisher.filter_qc_valid') }}</label>
                                    <select class="form-control" v-model="updateMultiple.qc_validation">
                                        <option value=""></option>
                                        <option value="yes">{{ $t('message.publisher.yes') }}</option>
                                        <option value="no">{{ $t('message.publisher.no') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                        <button type="button" @click="submitMultipleEdit" class="btn btn-primary" data-dismiss="modal">
                            {{ $t('message.publisher.save') }}
                        </button>
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
    /*#vs1__combobox {*/
    /*    height: 38px;*/
    /*}*/

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
import {mapState} from 'vuex';
import axios from 'axios';
import VueVirtualTable from 'vue-virtual-table';
import {csvTemplateMixin} from "../../../mixins/csvTemplateMixin";
import Sort from '@/components/sort/Sort';
import {dateRange} from "../../../mixins/dateRange";

export default {
        components: {
            VueVirtualTable,
            Sort,
        },
        name: '',
        mixins: [csvTemplateMixin, dateRange],
        data(){
            return {
                paginate: [50,150,250,350,'All'],
                updateMultiple: {
                    inc_article: '',
                    topic: '',
                    kw_anchor: '',
                    casino_sites: '',
                    price: '',
                    country: '',
                    continent_id: '',
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
                    country_id: '',
                    continent_id: '',
                    // team_in_charge: '',
                    // team_in_charge_old: '',
                    user_id: '',
                    qc_validation: '',
                },
                isEnableBtn: true,
                isPopupLoading: false,
                filterModel: {
                    continent_id: this.$route.query.continent_id
                        ? Array.isArray(this.$route.query.continent_id)
                            ? this.$route.query.continent_id.map(function (val) {
                                return parseInt(val, 10);
                            })
                            : [parseInt(this.$route.query.continent_id)]
                        : '',
                    country_id: this.$route.query.country_id
                        ? Array.isArray(this.$route.query.country_id)
                            ? this.$route.query.country_id.map(function (val) {
                                return parseInt(val, 10);
                            })
                            : [parseInt(this.$route.query.country_id)]
                        : '',
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    inc_article: this.$route.query.inc_article || '',
                    seller: this.$route.query.seller || '',
                    paginate: this.$route.query.paginate || 50,
                    got_ahref: this.$route.query.got_ahref || '',
                    date: {
                        startDate: '',
                        endDate: ''
                    },
                    uploaded: {
                        startDate: '',
                        endDate: ''
                    },
                    valid: this.$route.query.valid || '',
                    in_charge: this.$route.query.in_charge || '',
                    casino_sites: this.$route.query.casino_sites || '',
                    topic: this.$route.query.topic || '',
                    kw_anchor: this.$route.query.kw_anchor || '',
                    price_basis: this.$route.query.price_basis || '',
                    qc_validation: this.$route.query.qc_validation || '',
                    show_duplicates:
                        this.$route.query.show_duplicates
                        || '',
                    account_validation: this.$route.query.account_validation || 'valid',
                    domain_zone: this.$route.query.domain_zone || '',
                    is_https: this.$route.query.is_https || '',
                    sort: ''
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
                    country_id: '',
                    continent_id: '',
                    kw_anchor: '',
                },
                topicFilter: [
                    'N/A',
                    'Adult',
                    'Art',
                    'Beauty',
                    'CBD',
                    'Charity',
                    'Cooking',
                    'Crypto',
                    'Education',
                    'Fashion',
                    'Finance',
                    'Gambling',
                    'Games',
                    'Health',
                    'History',
                    'Job',
                    'Marketing',
                    'Movies & Music',
                    'News',
                    'Pet',
                    'Photograph',
                    'Real Estate',
                    'Religion',
                    'Shopping',
                    'Sports',
                    'Tech',
                    'Travel',
                    'Unlisted',
                ],
                topic: [
                    'Adult',
                    'Art',
                    'Beauty',
                    'CBD',
                    'Charity',
                    'Cooking',
                    'Crypto',
                    'Education',
                    'Fashion',
                    'Finance',
                    'Gambling',
                    'Games',
                    'Health',
                    'History',
                    'Job',
                    'Marketing',
                    'Movies & Music',
                    'News',
                    'Pet',
                    'Photograph',
                    'Real Estate',
                    'Religion',
                    'Shopping',
                    'Sports',
                    'Tech',
                    'Travel',
                    'Unlisted',
                ],
                failedUpload: {
                    valid: 0,
                    invalid: 0,
                    total: 0,
                    message: [],
                },
                isAccountInvalid: false,
                isAccountPaymentNotComplete: false,
                isGenerating: false,

                country_continent_info: '',
                country_continent_filter_info: '',

                sort_options: [],
                updateFormula: {},

                // for removed items in list

                removedItems: [],

                isSubmitAdd: false,
            }
        },

        async created() {
            await
                this.$store.dispatch('getGeneratorLogs');
            if (this.isGeneratorOn) {
                this.isGenerating = true;
            }

            this.getPublisherList();
            this.checkAccountType();
            this.getListSeller();
            this.getListSellerIncharge();
            this.getListContinents();
            this.getListDomainZones();
            this.getFormula();

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
            let self = this;

            pusher.logToConsole = true;

            const channel = pusher.subscribe('private-user.' +
                this.user.id);

            if (this.user.isAdmin || this.user.role_id == 8) {
                const channel2 =
                    pusher.subscribe('BestPriceChannel');

                channel2.bind('bestprices.start', (e) => {
                    this.isGenerating = true;
                });

                channel2.bind('bestprices.end', (e) => {
                    this.isGenerating = false;
                });
            }

            channel.bind('prices.generate', (e) => {
                this.getPublisherList();

                swal.fire({
                    icon: 'success',
                    title: self.$t('message.publisher.alert_success'),
                    text: self.$t('message.publisher.alert_completed_best_price'),
                    confirmButtonText: "Ok",
                });
            });

            this.setDefaultSettings()
        },

        watch: {
            'filterModel.continent_id': function() {
                if (this.filterModel.country_id != null
                    && this.filterModel.country_id !== ''
                    && this.filterModel.continent_id !== 0
                    && this.filterModel.continent_id !== ''
                    && this.filterModel.country_id.length !== 0
                    && this.filterModel.continent_id.length !== 0
                    && this.filterModel.continent_id.includes(0) === false) {

                    // get all the countries within the selected continent
                    let filtered = this.listCountryContinent.data
                        .filter(item => this.filterModel.continent_id
                            .includes(item.continent_id))

                    // extract id to array
                    let continentCountries = filtered.map(e => e.id);

                    // check if every id of country is included on the filtered countries according to continent
                    let is_gone = this.filterModel.country_id.every( country => continentCountries.includes(country) );

                    // extract id of removed countries
                    let removedCountries = this.filterModel.country_id.filter(e => !continentCountries.includes(e))

                    // remove id of country filter value that is removed from filtered countries via continent
                    let filteredCountryIds = this.filterModel.country_id.filter(e => !removedCountries.includes(e))

                    // display message
                    this.country_continent_filter_info = is_gone
                        ? ''
                        : this.$t('message.publisher.alert_some_countries');

                    this.filterModel.country_id = is_gone
                        ? this.filterModel.country_id
                        : filteredCountryIds.length !== 0 ? filteredCountryIds : '';
                }
            },

            'addModel.continent_id': function() {
                if (this.addModel.country_id != null
                    && this.addModel.country_id !== ''
                    && this.addModel.continent_id !== 0
                    && this.addModel.continent_id !== '') {

                    let filtered = this.listCountryAll.data.filter(item => item.continent_id === this.addModel.continent_id)
                    let is_gone = filtered.some(el => el.id === this.addModel.country_id);
                    let index = this.listCountryAll.data.map(e => e.id).indexOf(this.addModel.country_id);

                    this.country_continent_info = is_gone
                        ? ''
                        : this.listCountryAll.data[index].name + this.$t('message.publisher.alert_not_within_continent');

                    this.addModel.country_id = is_gone
                        ? this.addModel.country_id
                        : '';
                }
            },

            'updateModel.continent_id': function() {
                if (this.updateModel.country_id != null
                    && this.updateModel.country_id !== ''
                    && this.updateModel.continent_id !== 0
                    && this.updateModel.continent_id !== '') {

                    let filtered = this.listCountryAll.data.filter(item => item.continent_id === this.updateModel.continent_id)
                    let is_gone = filtered.some(el => el.id === this.updateModel.country_id);
                    let index = this.listCountryAll.data.map(e => e.id).indexOf(this.updateModel.country_id);

                    this.country_continent_info = is_gone
                        ? ''
                        : this.listCountryAll.data[index].name + this.$t('message.publisher.alert_not_within_continent');

                    this.updateModel.country_id = is_gone
                        ? this.updateModel.country_id
                        : '';
                }
            },

            'updateMultiple.continent_id': function() {
                if (this.updateMultiple.country != null
                    && this.updateMultiple.country !== ''
                    && this.updateMultiple.continent_id !== 0
                    && this.updateMultiple.continent_id !== '') {

                    let filtered = this.listCountryAll.data.filter(item => item.continent_id === this.updateMultiple.continent_id)
                    let is_gone = filtered.some(el => el.id === this.updateMultiple.country);
                    let index = this.listCountryAll.data.map(e => e.id).indexOf(this.updateMultiple.country);

                    this.country_continent_info = is_gone
                        ? ''
                        : this.listCountryAll.data[index].name + this.$t('message.publisher.alert_not_within_continent');

                    this.updateMultiple.country = is_gone
                        ? this.updateMultiple.country
                        : '';
                }
            },
        },

        computed:{
            ...mapState({
                tblPublisherOpt: state => state.storePublisher.tblPublisherOpt,
                listPublish: state => state.storePublisher.listPublish,
                messageForms: state => state.storePublisher.messageForms,
                listCountryAll: state => state.storePublisher.listCountryAll,
                listContinent: state => state.storePublisher.listContinent,
                listCountryContinent: state => state.storePublisher.listCountryContinent,
                listDomainZones: state => state.storePublisher.listDomainZones,
                user: state => state.storeAuth.currentUser,
                listSeller: state => state.storePublisher.listSeller,
                listSellerIncharge: state => state.storePublisher.listSellerIncharge,
                listAhrefsPublisher: state => state.storePublisher.listAhrefsPublisher,
                listIncharge: state => state.storeAccount.listIncharge,
                listLanguages: state => state.storePublisher.listLanguages,
                formula : state => state.storeSystem.formula,
                isGeneratorOn: state =>
                    state.storePublisher.bestPriceGeneratorOn
            }),

            isQc() {
                let qcRoleIds = [5, 8, 9, 10];
                return qcRoleIds.includes(this.user.role_id);
            },

            computedListSeller() {
                return this.user.role_id == 6 && this.user.isOurs == 0
                    ? this.listSellerIncharge.data
                    : this.listSeller.data;
            },

            filterCountrySelect() {
                return (this.filterModel.continent_id == null
                    || this.filterModel.continent_id === ''
                    || this.filterModel.continent_id === 0
                    || this.filterModel.continent_id.length === 0
                    || this.filterModel.continent_id.includes(0))

                    ? this.listCountryContinent.data
                    : this.listCountryContinent.data.filter(item => this.filterModel.continent_id.includes(item.continent_id))
            },

            addCountrySelect() {
                return (this.addModel.continent_id == null || this.addModel.continent_id === '' || this.addModel.continent_id === 0)
                    ? this.listCountryAll.data
                    : this.listCountryAll.data.filter(item => item.continent_id === this.addModel.continent_id)
            },

            updateCountrySelect() {
                return (this.updateModel.continent_id == null || this.updateModel.continent_id === '' || this.updateModel.continent_id === 0)
                    ? this.listCountryAll.data
                    : this.listCountryAll.data.filter(item => item.continent_id === this.updateModel.continent_id)
            },

            multipleCountrySelect() {
                return (this.updateMultiple.continent_id == null || this.updateMultiple.continent_id === '' || this.updateMultiple.continent_id === 0)
                    ? this.listCountryAll.data
                    : this.listCountryAll.data.filter(item => item.continent_id === this.updateMultiple.continent_id)
            },

            isSorted() {
                return this.filterModel.sort !== '' && this.filterModel.sort.length !== 0;
            },

            sortOptions() {
                return [
                    {
                        name: this.$t('message.publisher.filter_uploaded'),
                        sort: '',
                        column: 'created_at',
                        hidden: !this.tblPublisherOpt.created
                    },
                    {
                        name: this.$t('message.publisher.filter_updated'),
                        sort: '',
                        column: 'updated_at',
                        hidden: !this.user.isAdmin || this.user.isOurs !== 0 || !this.tblPublisherOpt.uploaded
                    },
                    {
                        name: this.$t('message.publisher.filter_lang'),
                        sort: '',
                        column: 'languages.name',
                        hidden: !this.tblPublisherOpt.language
                    },
                    {
                        name: this.$t('message.publisher.filter_country'),
                        sort: '',
                        column: 'countries.name',
                        hidden: !this.tblPublisherOpt.country
                    },
                    {
                        name: this.$t('message.publisher.filter_continent'),
                        sort: '',
                        column: 'continent_name',
                        hidden: !this.tblPublisherOpt.continent
                    },
                    {
                        name: this.$t('message.publisher.filter_seller'),
                        sort: '',
                        column: 'A.username',
                        hidden: this.user.isOurs !== 0 || !this.tblPublisherOpt.seller
                    },
                    {
                        name: this.$t('message.publisher.valid'),
                        sort: '',
                        column: 'valid',
                        hidden: !this.tblPublisherOpt.valid,
                    },
                    {
                        name: this.$t('message.publisher.t_qc_valid'),
                        sort: '',
                        column: 'qc_validation',
                        hidden: !this.tblPublisherOpt.qc_validation
                    },
                    {
                        name: this.$t('message.publisher.t_url'),
                        sort: '',
                        column: 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(url,\'http://\',\'\'), \'https://\', \'\'), \'www.\', \'\'), \'/\', \'\'), \' \', \'\')',
                        hidden: !this.tblPublisherOpt.url
                    },
                    {
                        name: this.$t('message.publisher.t_https'),
                        sort: '',
                        column: 'is_https'
                    },
                    {
                        name: this.$t('message.publisher.t_price'),
                        sort: '',
                        column: 'cast(price as unsigned)',
                        hidden: !this.tblPublisherOpt.price
                    },
                    {
                        name: this.$t('message.publisher.filter_price_basis'),
                        sort: '',
                        column: 'price_basis',
                        hidden: !this.tblPublisherOpt.price_basis
                    },
                    {
                        name: this.$t('message.publisher.filter_inc_art'),
                        sort: '',
                        column: 'inc_article',
                        hidden: !this.tblPublisherOpt.inc_article
                    },
                    {
                        name: this.$t('message.publisher.t_kw_anchor'),
                        sort: '',
                        column: 'kw_anchor',
                        hidden: !this.tblPublisherOpt.kw_anchor
                    },
                    {
                        name: 'UR',
                        sort: '',
                        column: 'cast(ur as unsigned)',
                        hidden: !this.tblPublisherOpt.ur
                    },
                    {
                        name: 'DR',
                        sort: '',
                        column: 'cast(dr as unsigned)',
                        hidden: !this.tblPublisherOpt.dr
                    },
                    {
                        name: this.$t('message.publisher.tb_backlinks'),
                        sort: '',
                        column: 'cast(backlinks as unsigned)',
                        hidden: !this.tblPublisherOpt.backlinks
                    },
                    {
                        name: this.$t('message.publisher.t_ref_dom'),
                        sort: '',
                        column: 'cast(ref_domain as unsigned)',
                        hidden: !this.tblPublisherOpt.ref_domain
                    },
                    {
                        name: this.$t('message.publisher.t_org_keywords'),
                        sort: '',
                        column: 'cast(org_keywords as unsigned)',
                        hidden: !this.tblPublisherOpt.org_keywords
                    },
                    {
                        name: this.$t('message.publisher.t_org_traffic'),
                        sort: '',
                        column: 'cast(org_traffic as unsigned)',
                        hidden: !this.tblPublisherOpt.org_traffic
                    },
                    {
                        name: this.$t('message.publisher.filter_topic'),
                        sort: '',
                        column: 'topic',
                        hidden: !this.tblPublisherOpt.topic
                    },
                    {
                        name: this.$t('message.publisher.t_in_charge'),
                        sort: '',
                        column: 'B.username',
                        hidden: !this.tblPublisherOpt.in_charge
                    },
                    {
                        name: this.$t('message.publisher.t_cb'),
                        sort: '',
                        column: 'casino_sites',
                        hidden: !this.tblPublisherOpt.casino_sites
                    },
                ]
            },

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
                        prop : 'created_at',
                        name : this.$t('message.publisher.filter_uploaded'),
                        // actionName : 'createdData',
                        width: 100,
                        // sortable: true,
                        isHidden:
                            !this.tblPublisherOpt.created
                    },
                    {
                        prop : 'updated_at',
                        name : this.$t('message.publisher.filter_updated'),
                        // actionName : 'updatedData',
                        width: 100,
                        // sortable: true,
                        isHidden: !this.user.isAdmin ||
                            this.user.isOurs != 0 || !this.tblPublisherOpt.uploaded
                    },
                    {
                        prop : 'language_name',
                        name : this.$t('message.publisher.filter_lang'),
                        // sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.language
                    },
                    {
                        prop : 'country_name',
                        name : this.$t('message.publisher.filter_country'),
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.country
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.filter_continent'),
                        // sortable: true,
                        actionName : 'continentData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.continent
                    },
                    {
                        prop : 'topic',
                        name : this.$t('message.publisher.filter_topic'),
                        // actionName : 'topicData',
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.topic
                    },
                    {
                        prop : 'casino_sites',
                        name : this.$t('message.publisher.t_cb'),
                        // sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.casino_sites
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_in_charge'),
                        actionName : 'inChargeData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.in_charge
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.filter_seller'),
                        actionName : 'usernameData',
                        // sortable: true,
                        width: 100,
                        isHidden: this.user.isOurs != 0 || !this.tblPublisherOpt.seller
                    },
                    {
                        prop : 'valid',
                        name : this.$t('message.publisher.valid'),
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.valid,
                    },
                    {
                        prop : 'qc_validation',
                        name : this.$t('message.publisher.t_qc_valid'),
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.qc_validation
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_url'),
                        actionName : 'urlData',
                        width: 150,
                        // sortable: true,
                        isHidden: !this.tblPublisherOpt.url
                    },
                    {
                        prop : 'is_https',
                        name : this.$t('message.publisher.t_https'),
                        // sortable: true,
                        width: 100,
                        isHidden: false
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_price'),
                        actionName : 'priceData',
                        width: 100,
                        // sortable: true,
                        // prefix: '$ ',
                        isHidden: !this.tblPublisherOpt.price
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_prices'),
                        actionName : 'pricesData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.prices
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.filter_price_basis'),
                        actionName : 'priceBasisData',
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.price_basis
                    },
                    {
                        prop : 'inc_article',
                        name : this.$t('message.publisher.t_inc_article'),
                        // sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.inc_article
                    },
                    {
                        prop : 'kw_anchor',
                        name : this.$t('message.publisher.t_kw_anchor'),
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.kw_anchor
                    },
                    {
                        prop : 'ur',
                        name : 'UR',
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.ur
                    },
                    {
                        prop : 'dr',
                        name : 'DR',
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.dr
                    },
                    {
                        prop : 'backlinks',
                        name : this.$t('message.publisher.tb_backlinks'),
                        // sortable: true,
                        width: 100,
                        isHidden: !this.tblPublisherOpt.backlinks
                    },
                    {
                        prop : 'ref_domain',
                        name : this.$t('message.publisher.t_ref_dom'),
                        // sortable: true,
                        width: 100,
                        isHidden:
                            !this.tblPublisherOpt.ref_domain
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_org_keywords'),
                        actionName : 'orgKeywordData',
                        width: 120,
                        isHidden: !this.tblPublisherOpt.org_keywords
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_org_traffic'),
                        actionName : 'orgTrafficData',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.org_traffic
                    },
                    {
                        prop : 'code_price',
                        name : this.$t('message.publisher.t_code_price'),
                        prefix: '$ ',
                        width: 100,
                        isHidden: !this.tblPublisherOpt.code_price || this.user.isOurs != 0
                    },
                    {
                        prop : '_action',
                        name : this.$t('message.publisher.t_action'),
                        actionName : 'actionButtons',
                        width : '150',
                        isHidden: false
                    },
                ];
            }
        },

        methods: {
            async getFormula() {
                await this.$store.dispatch('actionGetFormula');
                this.updateFormula = this.formula.data[0];
            },

            generateCountry() {
                let self = this;
                axios.post('/api/publisher/generate-country', {
                    ids : this.checkIds
                }).then((response) => {
                    swal.fire({
                        icon: 'success',
                        title: self.$t('message.publisher.alert_success'),
                        text: self.$t('message.publisher.alert_generating_countries'),
                        confirmButtonText: "Ok",
                    });
                }).catch((err) => {
                    swal.fire({
                        icon: 'error',
                        title: self.$t('message.publisher.alert_error'),
                        text: self.$t('message.publisher.alert_error_encountered'),
                    });
                })
            },

            sortPublisher(data) {
                this.filterModel.sort = data.filter(item => item.sort !== '' && item.hidden !== true)
                this.getPublisherList();
            },

            updateSortOptions(data) {
                this.sort_options = data;
            },

            masterListDataMethod() {
                let obj = [];
                let countries = this.listCountryAll.data.map(country => country.name);
                let languages = this.listLanguages.data.map(language => language.name);
                let listTopics = this.topic;

                // get all country name
                for (let i = 0; i < countries.length; i++) {
                    obj[i] = {
                        country: countries[i]
                    }
                }

                // add language
                for (let i = 0; i < languages.length; i++) {
                    obj[i].language = languages[i]
                }

                // add topics
                if (obj.length) {
                    for (let i = 0; i < listTopics.length; i++) {
                        obj[i].topic = listTopics[i]
                    }
                }

                return obj;
            },

            clearCountryContinentInfo() {
                this.country_continent_info = '';
            },

            selectCountry(mod) {
                let self = this;
                let model_id = mod === 'add'
                    ? this.addModel.country_id
                    : mod === 'update'
                        ? this.updateModel.country_id
                        : this.updateMultiple.country

                if (model_id) {
                    let index = this.listCountryAll.data.map(e => e.id).indexOf(model_id);
                    let continent_id = this.listCountryAll.data[index].continent_id ?? '';

                    if (mod === 'add') {
                        this.addModel.continent_id = continent_id;
                    } else if(mod === 'update') {
                        this.updateModel.continent_id = continent_id;
                    } else {
                        this.updateMultiple.continent_id = continent_id;
                    }

                    this.country_continent_info = continent_id === ''
                        ? self.$t('message.publisher.alert_continent_not_set')
                        : '';
                }
            },

            async generateBestPrices() {
                let self = this;
                await
                    this.$store.dispatch('generateBestPrices');

                swal.fire({
                    icon: 'success',
                    title: self.$t('message.publisher.alert_success'),
                    text: self.$t('message.publisher.alert_generating_best_price'),
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

                // this.tblPublisherOpt.country = false;
            },

            async getTeamInCharge(){
                await this.$store.dispatch('actionGetTeamInCharge');
            },

            async getPublisherList(page = 1) {
                let loader = this.$loading.show();
                this.searchLoading = true;
                this.isSearching = true;

                // change the format of date
                this.filterModel.uploaded = this.formatFilterDates(this.filterModel.uploaded)
                this.filterModel.date = this.formatFilterDates(this.filterModel.date)

                if (this.isSorted) {
                    this.filterModel.sort = this.getSortData()
                }

                if(this.filterModel.paginate == 'All')
                {

                    await this.$store.dispatch('getListPublisher', {
                        params: {
                            country_id: this.filterModel.country_id,
                            continent_id: this.filterModel.continent_id,
                            search: this.filterModel.search,
                            language_id: this.filterModel.language_id,
                            inc_article: this.filterModel.inc_article,
                            seller: this.filterModel.seller,
                            paginate: 1000000,
                            got_ahref: this.filterModel.got_ahref,
                            date: this.filterModel.date,
                            uploaded: this.filterModel.uploaded,
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
                            account_validation: this.filterModel.account_validation,
                            domain_zone:
                            this.filterModel.domain_zone,
                            is_https: this.filterModel.is_https,
                            sort: this.filterModel.sort
                        }
                    });
                }else{
                    await this.$store.dispatch('getListPublisher', {
                        params: {
                            country_id: this.filterModel.country_id,
                            continent_id: this.filterModel.continent_id,
                            search: this.filterModel.search,
                            language_id: this.filterModel.language_id,
                            inc_article: this.filterModel.inc_article,
                            seller: this.filterModel.seller,
                            paginate: this.filterModel.paginate,
                            got_ahref: this.filterModel.got_ahref,
                            date: this.filterModel.date,
                            uploaded:
                            this.filterModel.uploaded,
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
                            account_validation: this.filterModel.account_validation,
                            domain_zone:
                            this.filterModel.domain_zone,
                            is_https: this.filterModel.is_https,
                            sort: this.filterModel.sort
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

                if (this.messageForms.action === 'get_list') {
                    this.removeItemsFromList(this.removedItems);
                }

                loader.hide();
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            },

            async getListSellerIncharge() {
                await this.$store.dispatch('actionGetListSellerIncharge', this.user.id);
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
                let self = this;
                await this.$store.dispatch('actionValidData', {
                    valid: valid,
                    ids: this.checkIds,
                });

                if( this.messageForms.action === 'saved' ){

                    if (this.messageForms.errors) {

                        let html = '';
                        for (var index in this.messageForms.errors) {
                            var txt = this.messageForms.errors[index].message == 'existing' ? '<b style="color:red;">' +
                                'Invalid</b> URL has already existing Valid URL ':'<b style="color:green;">' +
                                'Successfully validated' +
                                '</b> '

                            html += txt +'<b>' + this.messageForms.errors[index].url + '</b> <br/>';
                        }

                        swal.fire({
                            icon: '',
                            title: " ",
                            html: html,
                            confirmButtonText: self.$t('message.publisher.ok'),
                        });

                        this.getPublisherList();

                    }
                }

                this.checkIds = [];
            },

            async qcValidationUpdate(value){
                let loader = this.$loading.show();
                let self = this;
                await this.$store.dispatch('actionQcValidationUpdate', {
                    qc_validation: value,
                    ids: this.checkIds,
                });

                if( this.messageForms.action === 'saved' ){
                    loader.hide();

                    await swal.fire({
                        icon: 'success',
                        title: self.$t('message.publisher.alert_success'),
                        text: self.$t('message.publisher.alert_qc_valid_updated'),
                        confirmButtonText: self.$t('message.publisher.ok'),
                    });

                    await this.getPublisherList();
                } else {
                    loader.hide();

                    await swal.fire({
                        icon: 'error',
                        title: self.$t('message.publisher.alert_error'),
                        text: self.$t('message.publisher.alert_qc_valid_error'),
                        confirmButtonText: self.$t('message.publisher.ok'),
                    });
                }

                this.checkIds = [];
                this.allSelected = false;
            },

            select: function() {
                this.allSelected = false;
            },

            formatPrice(value) {
                return (value / 1).toFixed(0);
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

            computePriceStalinks(price, article) {

                let selling_price = price
                let percent = parseFloat(this.formula.data[0].percentage);
                let additional = parseFloat(this.formula.data[0].additional);

                let commission = 'yes';

                if (price != '' && price != null) { // check if price has a value

                    if (article.toLowerCase() == 'yes') { //check if with article

                        if (commission == 'yes') {
                            let percentage = this.percentage(percent, price)
                            selling_price = parseFloat(percentage) + parseFloat(price)
                        }
                    }

                    if (article.toLowerCase() == 'no') { //check if without article

                        if (commission == 'yes') {
                            let percentage = this.percentage(percent, price)
                            selling_price = parseFloat(percentage) + parseFloat(price) + additional
                        }

                    }

                }

                selling_price = parseFloat(selling_price).toFixed(0);

                return selling_price;
            },

            percentage(percent, total) {
                return ((percent / 100) * total).toFixed(2)
            },

            doMultipleEdit() {
                this.clearCountryContinentInfo();
                this.updateMultiple = {
                    language: '',
                    country: '',
                    continent_id: '',
                    price: '',
                    casino_sites: '',
                    kw_anchor: '',
                    topic: '',
                    inc_article: '',
                    qc_validation: '',
                }
            },

            submitMultipleEdit() {
                let self = this;
                axios.post('/api/update-multiple-publisher',{
                    ids: this.checkIds,
                    language: this.updateMultiple.language,
                    country: this.updateMultiple.country,
                    continent_id: this.updateMultiple.continent_id,
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
                        self.$t('message.publisher.alert_saved'),
                        self.$t('message.publisher.alert_success_update'),
                        'success'
                    )

                })
            },

            async doMultipleDelete(){
                let self = this;
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm(self.$t('message.publisher.alert_confirm_delete')) ){
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
                let self = this;
                self.isSubmitAdd = true;
                this.addModel.account_valid = this.checkAccountValidity();

                await this.$store.dispatch('actionAddUrl', this.addModel);

                if (this.messageForms.action === 'saved'){
                    $("#modal-add-url").modal('hide')
                    this.getPublisherList();

                    swal.fire(
                        self.$t('message.publisher.alert_saved'),
                        self.$t('message.publisher.alert_saved_url'),
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
                        country_id: '',
                        continent_id: '',
                        kw_anchor: '',
                    }

                    self.isSubmitAdd = false;
                } else {
                    swal.fire(
                        self.$t('message.publisher.alert_failed'),
                        self.$t('message.publisher.alert_failed_url'),
                        'error'
                    )

                    self.isSubmitAdd = false;
                }
            },

            async submitUpload() {
                let self = this;
                $('#tbl-publisher').DataTable().destroy();

                // clear error messages

                this.isEnableBtn = true;

                this.failedUpload.total = 0;
                this.failedUpload.message = [];
                this.failedUpload.valid = 0;
                this.failedUpload.invalid = 0;

                swal.fire({
                    title: self.$t('message.publisher.alert_uploading_csv'),
                    text: self.$t('message.publisher.alert_please_wait'),
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });

                this.formData = new FormData();
                this.formData.append('file', this.$refs.excel.files[0]);
                // this.formData.append('language', this.$refs.language.value);
                this.formData.append('account_valid', this.checkAccountValidity());

                await this.$store.dispatch('actionUploadCsv', this.formData);

                if (this.messageForms.action === 'uploaded') {
                    this.getPublisherList();
                    this.$refs.excel.value = '';
                    // this.$refs.language.value = '';
                    this.showLang = false;

                    swal.fire(
                        self.$t('message.publisher.alert_uploaded'),
                        self.$t('message.publisher.alert_upload_success'),
                        'success'
                        )

                    console.log(this.messageForms.errors)

                    let cnt_failed = this.messageForms.errors.length;
                    if (cnt_failed > 0){
                        for (let key in this.messageForms.errors ){
                            this.failedUpload.message.push(this.messageForms.errors[key].message)
                        }

                        this.failedUpload.total = cnt_failed;
                        this.failedUpload.valid = this.messageForms.valid;
                        this.failedUpload.invalid = this.messageForms.invalid;
                        $("#modal-failed-upload").modal('show')
                    }
                } else {
                    swal.fire(
                        self.$t('message.publisher.alert_failed'),
                        self.$t('message.publisher.alert_upload_fail'),
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

                // checking is external seller
                if(that.isOurs == 1) {
                    this.tblPublisherOpt.prices = false;
                }
            },

            clearSearch() {
                this.removedItems = [];

                this.filterModel = {
                    continent_id: '',
                    country_id: '',
                    search: '',
                    language_id: '',
                    inc_article: '',
                    seller: '',
                    paginate: 50,
                    got_ahref: '',
                    valid: '',
                    in_charge: '',
                    casino_sites: '',
                    topic: '',
                    kw_anchor: '',
                    price_basis: '',
                    qc_validation: '',
                    account_validation: 'valid',
                    date: {
                        startDate: null,
                        endDate: null
                    },
                    uploaded: {
                        startDate: null,
                        endDate: null
                    },
                    domain_zone: '',
                    is_https : '',
                    sort: ''
                }

                this.country_continent_filter_info = '';

                this.getPublisherList({
                    params: this.filterModel
                });

                this.$refs.sortComponent.resetSort();

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
                let self = this;
                $('#tbl-publisher').DataTable().destroy();

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdatePublisher', this.updateModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_publisher') {

                    // for qc seller
                    if (this.user.role_id === 10) {
                        swal.fire({
                            title: self.$t('message.publisher.alert_publisher_updated'),
                            text: self.$t('message.publisher.alert_remove_date'),
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: self.$t('message.publisher.yes'),
                            cancelButtonText: self.$t('message.publisher.no')
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                this.removeItemFromList(this.updateModel.id)

                                // add to removed items
                                this.removedItems.push(this.updateModel.id)

                                $("#modal-update-publisher").modal('hide')
                            } else {
                                this.getPublisherList();
                            }
                        });
                    } else {

                        this.getPublisherList();

                        swal.fire(
                            self.$t('message.publisher.alert_updated'),
                            self.$t('message.publisher.alert_publisher_updated'),
                            'success'
                        )
                    }

                }
            },

            removeItemFromList(id) {
                let index = this.listPublish.data.map(x => x.id).indexOf(id)

                this.listPublish.data.splice(index, 1);
            },

            removeItemsFromList(ids) {
                if (this.user.role_id === 10) {
                    if (this.removedItems.length !== 0) {
                        this.listPublish.data = this.listPublish.data.filter(el => !ids.includes(el.id));
                    }
                }
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async getListContinents(params) {
                await this.$store.dispatch('actionGetListContinents', params);
            },

            async getListDomainZones(params) {
                await this.$store.dispatch('actionGetListDomainZones', params);
            },

            async getAhrefs() {
                let self = this;
                $('#tbl-publisher').DataTable().destroy();

                swal.fire({
                    title: self.$t('message.publisher.alert_getting_ahrefs'),
                    text: self.$t('message.publisher.alert_please_wait'),
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
                    self.$t('message.publisher.alert_updated'),
                    self.$t('message.publisher.alert_ahrefs_updated'),
                        'success'
                        )

                this.checkIds = [];

                this.getPublisherList();
            },

            doUpdate(publish) {
                this.clearMessageform()
                this.clearCountryContinentInfo()
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
                    inc_article: that.inc_article.toLowerCase(),
                    topic: topic,
                    casino_sites: that.casino_sites.toLowerCase(),
                    kw_anchor: that.kw_anchor.toLowerCase(),
                    country_id: that.country_id,
                    continent_id: that.continent_id,
                    // team_in_charge: that.team_in_charge,
                    user_id: that.user_id,
                    qc_validation: that.qc_validation == null ? '':that.qc_validation,
                    // team_in_charge_old: that.team_in_charge,
                }

                this.updateModel.company_name = that.isOurs == '0' ? 'Stalinks':that.company_name;

                $('#modal-update-publisher').modal({
                    show: true
                });
            },

            async doDelete(id){
                let self = this;
                $('#tbl-publisher').DataTable().destroy();

                this.clearMessageform()
                if( confirm(self.$t('message.publisher.alert_delete_records')) ){
                    await this.$store.dispatch('actionDeletePublisher', {
                        params: {
                            id: id,
                        }
                    });

                    this.getPublisherList();
                }
            },

            doSearch() {
                this.removedItems = [];

                // change the format of date
                this.filterModel.uploaded = this.formatFilterDates(this.filterModel.uploaded)
                this.filterModel.date = this.formatFilterDates(this.filterModel.date)

                this.$router.push({
                    query: this.filterModel,
                });

                if (this.isSorted) {
                    this.filterModel.sort = this.getSortData()
                }

                this.getPublisherList({
                    params: {
                        country_id: this.filterModel.country_id,
                        continent_id: this.filterModel.continent_id,
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        inc_article: this.filterModel.inc_article,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        got_ahref: this.filterModel.got_ahref,
                        date: this.filterModel.date,
                        uploaded: this.filterModel.uploaded,
                        valid: this.filterModel.valid,
                        in_charge: this.filterModel.in_charge,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        kw_anchor: this.filterModel.kw_anchor,
                        price_basis: this.filterModel.price_basis,
                        qc_validation: this.filterModel.qc_validation,
                        show_duplicates:
                        this.filterModel.show_duplicates,
                        account_validation: this.filterModel.account_validation,
                        domain_zone:
                        this.filterModel.domain_zone,
                        is_https: this.filterModel.is_https,
                        sort: this.filterModel.sort
                    }
                });
            },

            getSortData() {
                return this.sort_options.filter(item => item.sort !== '' && item.hidden !== true)
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
                    this.isEnableBtn = true;
                    if (this.$refs.excel.value){
                        this.showLang = true;
                        this.isEnableBtn = false;
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
                        if (this.user.user_type.account_validation == 'invalid' || this.user.user_type.account_validation == 'processing') {
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
                    ? ['URL', 'Price', 'Inc Article',
                       'Seller ID',
                       'Accept C&B','Language', 'Topic', 'Country',
                       'KW Anchor']
                    : ['URL', 'Price', 'Inc Article',
                       'Accept C&B', 'KW Anchor',
                       'Language', 'Topic', 'Country'];

                headers.push(rows);

                this.downloadCsvTemplate(headers, 'list_publisher_csv_template');
            }
        }

    }
</script>
