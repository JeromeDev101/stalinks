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

        <!-- <div class="alert alert-info" v-if="isBuyerHasDiscount">
            {{ $t('message.list_backlinks.promo') }}
        </div> -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.list_backlinks.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.list_backlinks.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_search_url') }}</label>
                                    <input
                                        v-model="filterModel.search"
                                        name=""
                                        type="text"
                                        class="form-control"
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.list_backlinks.type')">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_status_purchased') }}</label>

                                    <div v-if="(user.role_id == 5 && user.sub_buyers_count > 0) || (user.role_id == 5 && (user.registration != null && user.registration.is_sub_account == 1))" class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                @click="buttonState.status_purchase = buttonState.status_purchase === 'User' ? 'Team' : 'User'">

                                                {{ buttonState.status_purchase }}
                                            </button>
                                        </div>

                                        <v-select
                                            v-model="filterModel.status_purchase"
                                            multiple
                                            class="style-chooser-group style-chooser"
                                            :options="['New', 'Interested', 'Not interested', 'Purchased']"
                                            :searchable="false"
                                            :placeholder="$t('message.list_backlinks.all')"/>
                                    </div>

                                    <div v-else class="input-group mb-3">
                                        <v-select
                                            v-model="filterModel.status_purchase"
                                            multiple
                                            class="style-chooser-group style-chooser"
                                            :options="['New', 'Interested', 'Not interested', 'Purchased']"
                                            :searchable="false"
                                            :placeholder="$t('message.list_backlinks.all')"/>
                                    </div>

                                    <!-- <v-select multiple v-model="filterModel.status_purchase" :options="['New', 'Interested', 'Not interested', 'Purchased']" id="custom" ></v-select> -->
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_lang') }}</label>
                                    <v-select
                                        v-model="filterModel.language_id"
                                        multiple
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.list_backlinks.all')"
                                        :searchable="true"
                                        :options="listLanguages.data"
                                        :reduce="language => language.id"/>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_continent') }}</label>
                                    <v-select
                                        v-model="filterModel.continent_id"
                                        multiple
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.list_backlinks.all')"
                                        :searchable="true"
                                        :options="listContinent.data"
                                        :reduce="continent => continent.id"/>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_country') }}</label>
                                    <v-select
                                        v-model="filterModel.country_id"
                                        multiple
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.list_backlinks.all')"
                                        :searchable="true"
                                        :options="filterCountrySelect"
                                        :reduce="country => country.id"/>
                                    <!--                                <select class="form-control" v-model="filterModel.country_id">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option v-for="option in listCountryAll.data" v-bind:value="option.id">-->
                                    <!--                                        {{ option.name }}-->
                                    <!--                                    </option>-->
                                    <!--                                </select>-->

                                    <small class="font-italic text-primary" v-if="country_continent_filter_info">
                                        <i class="fa fa-exclamation-circle"></i>
                                        {{ country_continent_filter_info }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3"
                                 v-if="user.isAdmin || (user.isOurs == 0 && [7, 14].includes(user.role_id))">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_seller') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.list_backlinks.all') }}</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_accept_cb') }}</label>
                                    <select name="" class="form-control" v-model="filterModel.casino_sites">
                                        <option value="">{{ $t('message.list_backlinks.all') }}</option>
                                        <option value="yes">{{ $t('message.list_backlinks.yes') }}</option>
                                        <option value="no">{{ $t('message.list_backlinks.no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_topic') }}</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.topic">
                                        <option value="">All</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select
                                        multiple
                                        class="style-chooser"
                                        v-model="filterModel.topic"
                                        :options="topic"
                                        :searchable="false"
                                        :placeholder="$t('message.list_backlinks.all')"/>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_dz') }}</label>
                                    <v-select
                                        v-model="filterModel.domain_zone"
                                        multiple
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.list_backlinks.all')"
                                        :options="listDomainZones.data"
                                        :searchable="true"
                                        :reduce="domain => domain.name"/>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_https') }}</label>
                                    <select
                                        class="form-control"
                                        v-model="filterModel.is_https">
                                        <option value="">{{ $t('message.list_backlinks.all') }}</option>
                                        <option value="yes">{{ $t('message.list_backlinks.yes') }}</option>
                                        <option value="no">{{ $t('message.list_backlinks.no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_ur') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.ur = buttonState.ur === 'Above' ? $t('message.list_backlinks.below') : $t('message.list_backlinks.above')">
                                                {{ buttonState.ur }}
                                            </button>
                                        </div>
                                        <input type="text"
                                            class="form-control"
                                            :placeholder="$t('message.list_backlinks.type')"
                                            aria-label=""
                                            aria-describedby="basic-addon1"
                                            v-model="filterModel.ur">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_dr') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.dr = buttonState.dr === 'Above' ? $t('message.list_backlinks.below') : $t('message.list_backlinks.above')">
                                                {{ buttonState.dr }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.list_backlinks.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.dr">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_org_kw') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_kw = buttonState.org_kw === 'Above' ? $t('message.list_backlinks.below') : $t('message.list_backlinks.above')">
                                                {{ buttonState.org_kw }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.list_backlinks.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_kw">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_org_traffic') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_traffic = buttonState.org_traffic === 'Above' ? $t('message.list_backlinks.below') : $t('message.list_backlinks.above')">
                                                {{ buttonState.org_traffic }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.list_backlinks.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_traffic">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_price') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                @click="buttonState.price = buttonState.price === 'Above' ? $t('message.list_backlinks.below') : $t('message.list_backlinks.above')">
                                                {{ buttonState.price }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               :placeholder="$t('message.list_backlinks.type')"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.price">
                                    </div>
                                </div>
                            </div>

<!--                            <div-->
<!--                                class="col-md-2"-->
<!--                                v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 7) || user.role_id === 5">-->

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">

                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_code') }}</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.code">
                                        <option value="">All</option>
                                        <option v-for="option in listCode" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->

                                    <v-select
                                        v-model="filterModel.code"
                                        multiple
                                        class="style-chooser"
                                        :placeholder="$t('message.list_backlinks.all')"
                                        :options="listCode"
                                        :searchable="false"
                                    />
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3" v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.filter_price_basis') }}</label>
                                    <v-select
                                        v-model="filterModel.price_basis"
                                        multiple
                                        class="style-chooser"
                                        :options="['Good', 'Average', 'High']"
                                        :searchable="false"
                                        :placeholder="$t('message.list_backlinks.all')"/>
                                    <!--                                <select name="" class="form-control" v-model="filterModel.price_basis">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option value="Good">Good</option>-->
                                    <!--                                    <option value="Average">Average</option>-->
                                    <!--                                    <option value="High">High</option>-->
                                    <!--                                </select>-->
                                </div>
                            </div>

                            <div v-if="user.isAdmin || user.role_id == 5" class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label>Interested Domain Name</label>
                                    <input
                                        v-model="filterModel.interested_domain_name"
                                        name=""
                                        type="text"
                                        class="form-control"
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.list_backlinks.type')">
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">
                                    {{ $t('message.list_backlinks.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">
                                    {{ $t('message.list_backlinks.search') }}
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i>
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
                        <div class="card-title text-primary">
                            <span>
                                {{ $t('message.list_backlinks.bb_title') }}
                            </span>

                            <span v-if="user.role_id == 5 && isInterestedFiltered" class="ml-3" style="font-size: 1rem !important">
                                | Estimation purchases: <b>${{ estimationPurchases }}</b>
                            </span>
                        </div>

                        <div class="card-tools mr-2">
                            <span class="text-primary" v-show="[5, 14].includes(user.role_id)">
                                {{ $t('message.list_backlinks.bb_credit') }} <b>${{listBuy.credit }}</b>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-12 mt-0 pt-0">
                                <div v-if="isCreditAuth" class="alert alert-warning">
                                    {{ $t('message.list_backlinks.bb_sorry') }}
                                    <button class="btn btn-link" @click="checkCreditAuth">
                                        {{ $t('message.list_backlinks.bb_retry') }}
                                    </button>
                                    {{ $t('message.list_backlinks.bb_permission') }}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8 mb-2">
                                <div v-if="user.permission_list.includes('update-buyer-list-backlinks-to-buy')" class="input-group">
                                    <button
                                        class="btn btn-default mr-2"
                                        @click="selectAll">
                                        {{ allSelected ? $t('message.publisher.ab_deselect') : $t('message.publisher.ab_select') }}
                                        {{ $t('message.publisher.all') }}
                                    </button>

                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle"
                                                :disabled="isDisabled"
                                                type="button"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            {{ $t('message.list_backlinks.bb_selected_action') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item " @click="interestedSelected">
                                                {{ $t('message.list_backlinks.bb_interested') }}
                                            </a>
<!--                                            <a class="dropdown-item " @click="uninterestedSelected">-->
<!--                                                Uninterested-->
<!--                                            </a>-->
                                            <a class="dropdown-item " @click="notInterestedSelected">
                                                {{ $t('message.list_backlinks.bb_not_interested') }}
                                            </a>
                                            <a class="dropdown-item" @click="buySelected" data-target="#modal-buy-selected" data-toggle="modal">Buy</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 d-flex" :class="currentWindowWidth > 800 ? 'justify-content-end' : ''">

                                <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default mr-2">
                                    <i class="fa fa-cog"></i>
                                </button>

                                <div class="mr-2 w-25">
                                    <Sort
                                        ref="sortComponent"
                                        :sorted="isSorted"
                                        :items="sortOptions"
                                        :custom-class="['w-100']"

                                        @submitSort="sortBuyBacklinks"
                                        @updateOptions="updateSortOptions">
                                    </Sort>
                                </div>

                                <select
                                    class="form-control w-25"
                                    @change="getBuyList"
                                    v-model="filterModel.paginate"
                                    style="height: 37px; min-width: 100px">

                                    <option v-for="option in paginate" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <span v-if="listBuy.total > 10" class="pagination-custom-footer-text" style="margin-left: 0 !important;">
                            <b>{{ $t('message.others.table_entries', { from: listBuy.from, to: listBuy.to, end: listBuy.total }) }}</b>
                        </span>

                        <vue-virtual-table
                            v-if="!tableLoading"
                            width="100%"
                            :height="800"
                            :bordered="true"
                            :item-height="100"
                            :config="tableConfig"
                            :data="listBuy.data">
                            <template
                                slot-scope="scope"
                                slot="actionSelectRow">
                                <input type="checkbox"
                                    class="custom-checkbox"
                                    @change="checkSelected"
                                    :id="scope.row"
                                    :value="scope.row"
                                    v-model="checkIds">
                            </template>

                            <template
                                slot-scope="scope"
                                slot="topicData">
                                <div
                                    style="cursor: pointer"
                                    data-toggle="modal"
                                    data-target="#modal-view-topic-list"
                                    @click="showTopicList(scope.row.id, scope.row.topic)"
                                >
                                    <span class="badge badge-pill badge-info">
                                        {{ splitCommaSeparated(scope.row.topic)[0] }}
                                    </span>

                                    <span v-if="splitCommaSeparated(scope.row.topic).length > 1" class="badge badge-pill badge-info ml-1">
                                        +{{ splitCommaSeparated(scope.row.topic).length - 1 }}
                                    </span>
                                </div>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="casinoSiteData">
                                {{
                                    scope.row.casino_sites ==
                                    null ?
                                        'N/A' :
                                        scope.row.casino_sites
                                }}
                            </template>

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

                            <template slot-scope="scope" slot="sellerData">
                                <span :class="{'badge badge-pill badge-success': scope.row.is_recommended === 'yes'}">
                                    {{ scope.row.user_name }}
                                </span>
                            </template>

                            <template slot-scope="scope" slot="usernameData">
                                <span>
                                    {{ scope.row.user_name }}
                                </span>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="urlData">
                                <!--                            {{ replaceCharacters(scope.row.url) }}-->

                                <a :href="'//' + scope.row.url" target="_blank">
                                    {{ scope.row.url }}
                                </a>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="interestedDomainName">
                                <div v-if="scope.row.backlinks_interested.length">
                                    <a :href="'//' + scope.row.backlinks_interested[0].url_advertiser" target="_blank">
                                        {{ scope.row.backlinks_interested[0].url_advertiser }}
                                    </a>
                                </div>

                                <div v-else>
                                    <span class="badge badge-pill badge-secondary">
                                        N/A
                                    </span>
                                </div>
                            </template>

                            <template
                                slot-scope="scope"
                                slot="orgKeywordData">
                                {{
                                    formatPrice(scope.row.org_keywords)
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="ratioData">
                                {{
                                    getRatio(scope.row.org_keywords,
                                        scope.row.org_traffic)
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="orgTrafficData">
                                {{
                                    formatPrice(scope.row.org_traffic)
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="priceData">

                                <!-- <span v-if="computePrice(scope.row.price,
                                        scope.row.inc_article, 'yes') != computePrice(scope.row.price, scope.row.inc_article, 'no')"
                                        class="text-danger" style="text-decoration: line-through;">
                                    ${{ computePrice(scope.row.price, scope.row.inc_article, 'no') }}
                                    &nbsp;
                                </span> -->

                                {{
                                    scope.row.price == '' ||
                                    scope.row.price
                                    == null ? '' : '$'
                                }} {{
                                    computePrice(scope.row.price,
                                        scope.row.inc_article, 'yes')
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="pricesData">
                                {{
                                    scope.row.price == '' ||
                                    scope.row.price
                                    == null ? '' : '$'
                                }} {{
                                    computePriceStalinks(scope.row.price,
                                        scope.row.inc_article)
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="statusData">
                                {{
                                    scope.row.status_purchased ==
                                    null
                                        ?
                                        'New' : scope.row.status_purchased
                                }}
                            </template>

                            <template
                                slot-scope="scope"
                                slot="actionButtons">
                                    <div>
                                        <button
                                            v-if="(scope.row.price != '' && scope.row.price != null) && user.permission_list.includes('create-buyer-list-backlinks-to-buy')"
                                            class="btn btn-default action-btns"
                                            data-toggle="modal"
                                            data-target="#modal-buy-update"
                                            :disabled="isCreditAuth"
                                            :title="$t('message.list_backlinks.t_buy')"

                                            @click="doUpdate(scope.row)">

                                            <i class="fas fa-dollar-sign"></i>
                                        </button>

                                        <button
                                            class="btn btn-default action-btns"
                                            data-toggle="modal"
                                            data-target="#modal-link-injection"
                                            :disabled="isCreditAuth"
                                            title="Link Injection Request"

                                            @click="doLinkInjection(scope.row)">

                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>

                                    <div>
                                        <button
                                            v-if="user.permission_list.includes('update-buyer-list-backlinks-to-buy')"
                                            class="btn btn-default action-btns"
                                            :title="$t('message.list_backlinks.bb_interested')"
                                            :disabled="scope.row.status_purchased == 'Interested' || scope.row.status_purchased == 'Purchased'"

                                            @click="doLike(scope.row.id)">

                                            <i class="fa fa-fw fa-thumbs-up"></i>
                                        </button>

                                        <button
                                            v-if="user.permission_list.includes('update-buyer-list-backlinks-to-buy')"
                                            class="btn btn-default action-btns"
                                            :title="$t('message.list_backlinks.bb_not_interested')"
                                            :disabled="scope.row.status_purchased == 'Not interested' || scope.row.status_purchased == 'Purchased'"

                                            @click="doDislike(scope.row.id)"><i class="fa fa-fw fa-thumbs-down"></i>
                                        </button>
                                    </div>
                            </template>
                        </vue-virtual-table>
                        <pagination :data="listBuy" @pagination-change-page="getBuyList" :limit="5"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Buy -->
        <div class="modal fade"
            id="modal-buy-update"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.list_backlinks.b_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                            :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_url') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="updateModel.url"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder=""
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        {{ $t('message.list_backlinks.t_prices') }}
                                        <!-- <span v-if="isBuyerHasDiscount" class="text-danger">( 15% Off )</span> -->
                                    </label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="number"
                                            class="form-control"
                                            v-model="updateModel.price"
                                            name=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                            disabled>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_anchor_text') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="updateModel.anchor_text"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_link_to') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="updateModel.link"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_url_advertiser') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="updateModel.url_advertiser"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Idea for Title</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="updateModel.idea_for_title"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>You Provide Article?</label>
                                    <v-select
                                        :searchable="true"
                                        :options="['Yes','No']"/>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer custom-footer">
                        <div class="pull-left">
                            <button
                                v-if="updateModel.status_purchased === 'Interested' && user.role_id == 5"
                                type="button"
                                class="btn btn-info"
                                :disabled="btnBuy"

                                @click="saveInterestedDetails(updateModel)">

                                {{ $t('message.list_backlinks.save') }}
                            </button>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                {{ $t('message.list_backlinks.close') }}
                            </button>

                            <button
                                v-if="updateModel.status_purchased === 'Interested'"
                                type="button"
                                class="btn btn-danger"
                                :disabled="btnBuy"

                                @click="doUninterested(updateModel.id)">

                                {{ $t('message.list_backlinks.uninterested') }}
                            </button>

                            <button
                                v-if="updateModel.status_purchased === 'Not interested' || !updateModel.status_purchased"
                                type="button"
                                class="btn btn-success"
                                :disabled="btnBuy"

                                @click="doInterested(updateModel)">

                                Interested
                            </button>

                            <button
                                type="button"
                                class="btn btn-primary"
                                :disabled="btnBuy"

                                @click="submitBuy">

                                {{ $t('message.list_backlinks.t_buy') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Buy -->

        <!-- Modal Link Injection -->
        <div class="modal fade"
            id="modal-link-injection"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Link Injection Request</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_url') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="linkInjectionModel.url"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder=""
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Price for Injection
                                    </label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="number"
                                            class="form-control"
                                            v-model="linkInjectionModel.injection_price"
                                            name=""
                                            disabled
                                            aria-describedby="helpId"
                                            placeholder=""
                                            min="0">
                                    </div>

                                    <span
                                        v-if="errorMessages.injection_price"
                                        v-for="err in errorMessages.injection_price"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>URL Article</label>
                                    <input
                                        v-model="linkInjectionModel.url_article"
                                        type="text"
                                        class="form-control"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">

                                    <span
                                        v-if="errorMessages.url_article"
                                        v-for="err in errorMessages.url_article"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_anchor_text') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="linkInjectionModel.anchor_text"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_link_to') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="linkInjectionModel.link"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_url_advertiser') }}</label>
                                    <input type="text"
                                        class="form-control"
                                        v-model="linkInjectionModel.url_advertiser"
                                        name=""
                                        aria-describedby="helpId"
                                        placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer custom-footer">
                        <div class="pull-left">
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                {{ $t('message.list_backlinks.close') }}
                            </button>

                            <button
                                type="button"
                                class="btn btn-success"
                                :disabled="btnLinkInjection"

                                @click="submitLinkInjection()">

                                Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Link Injection -->

        <!-- Modal Interested -->
        <div class="modal fade"
             id="modal-interested-update"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.list_backlinks.i_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_url') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="updateModel.url"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_prices') }}</label>
                                    <input type="number"
                                           class="form-control"
                                           v-model="updateModel.price"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_anchor_text') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="updateModel.anchor_text"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_link_to') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="updateModel.link"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.b_url_advertiser') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="updateModel.url_advertiser"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.list_backlinks.close') }}
                        </button>
                        <button type="button" @click="submitInterest" class="btn btn-primary">
                            {{ $t('message.list_backlinks.bb_interested') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Interested -->

        <!-- Modal Buy Selected -->
        <div class="modal fade"
             id="modal-buy-selected"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.list_backlinks.bs_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-for="(buy, index) in buyData" :key="index">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_url') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           :value="buy.url"
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $t('message.list_backlinks.t_price') }}</label>
                                    <input type="number"
                                           class="form-control"
                                           :value="buy.price"
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.list_backlinks.b_anchor_text') }}</label>
                                            <input type="text"
                                                    class="form-control"
                                                    name=""
                                                    v-model="buySelectedModel.anchor_text"
                                                    aria-describedby="helpId"
                                                    placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.list_backlinks.b_link_to') }}</label>
                                            <input type="text"
                                                    class="form-control"
                                                    name=""
                                                    v-model="buySelectedModel.link"
                                                    aria-describedby="helpId"
                                                    placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ $t('message.list_backlinks.b_url_advertiser') }}</label>
                                            <input type="text"
                                                    class="form-control"
                                                    name=""
                                                    v-model="buySelectedModel.url_advertiser"
                                                    aria-describedby="helpId"
                                                    placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Idea for Title</label>
                                            <input type="text"
                                                class="form-control"
                                                name=""
                                                v-model="buySelectedModel.idea_for_title"
                                                aria-describedby="helpId"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.list_backlinks.close') }}
                        </button>
                        <button type="button" class="btn btn-primary" :disabled="btnBuySelected" @click="submitBuySelected">
                            {{ $t('message.list_backlinks.bs_buy_all') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Buy Selected -->

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.list_backlinks.sd_title') }}</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6" v-if="user.role_id != 5 && user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.seller ? 'checked':''" v-model="tblBuyOptions.seller">
                                    {{ $t('message.list_backlinks.filter_seller') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.username ? 'checked':''" v-model="tblBuyOptions.username">
                                    {{ $t('message.list_backlinks.filter_username') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.topic ? 'checked':''"
                                    v-model="tblBuyOptions.topic">
                                    {{ $t('message.list_backlinks.filter_topic') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.casino_sites ? 'checked':''"
                                    v-model="tblBuyOptions.casino_sites">
                                    {{ $t('message.list_backlinks.t_casino') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.language ? 'checked':''"
                                    v-model="tblBuyOptions.language">
                                    {{ $t('message.list_backlinks.filter_lang') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.country ? 'checked':''"
                                    v-model="tblBuyOptions.country">
                                    {{ $t('message.list_backlinks.filter_country') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.continent ? 'checked':''"
                                    v-model="tblBuyOptions.continent">
                                    {{ $t('message.list_backlinks.filter_continent') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.url ? 'checked':''"
                                    v-model="tblBuyOptions.url">
                                    {{ $t('message.list_backlinks.t_url') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.interested_domain_name ? 'checked':''"
                                    v-model="tblBuyOptions.interested_domain_name">
                                    Interested Domain Name
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ur ? 'checked':''"
                                    v-model="tblBuyOptions.ur">
                                    {{ $t('message.list_backlinks.filter_ur') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.dr ? 'checked':''"
                                    v-model="tblBuyOptions.dr">
                                    {{ $t('message.list_backlinks.filter_dr') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.backlinks ? 'checked':''"
                                    v-model="tblBuyOptions.backlinks">
                                    {{ $t('message.list_backlinks.sd_backlinks') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ref_domains ? 'checked':''"
                                    v-model="tblBuyOptions.ref_domains">
                                    {{ $t('message.list_backlinks.t_ref_dom') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.org_keywords ? 'checked':''"
                                    v-model="tblBuyOptions.org_keywords">
                                    {{ $t('message.list_backlinks.filter_org_kw') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ratio ? 'checked':''"
                                    v-model="tblBuyOptions.ratio">
                                    {{ $t('message.list_backlinks.t_ratio') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.org_traffic ? 'checked':''"
                                    v-model="tblBuyOptions.org_traffic">
                                    {{ $t('message.list_backlinks.filter_org_traffic') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.price ? 'checked':''"
                                    v-model="tblBuyOptions.price">
                                    {{ [5, 14].includes(user.role_id) ? $t('message.list_backlinks.t_prices') : $t('message.list_backlinks.t_price') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.is_https ? 'checked':''"
                                    v-model="tblBuyOptions.is_https">
                                    Https
                                </label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isAdmin">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.prices ? 'checked':''"
                                    v-model="tblBuyOptions.prices">
                                    {{ $t('message.list_backlinks.t_prices') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.status ? 'checked':''"
                                    v-model="tblBuyOptions.status">
                                    {{ $t('message.list_backlinks.t_status') }}
                                </label>
                            </div>
                            <div class="checkbox col-md-6" v-show="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.code_comb ? 'checked':''"
                                    v-model="tblBuyOptions.code_comb">
                                    {{ $t('message.list_backlinks.t_code_comb') }}
                                </label>
                            </div>
                            <div
                                class="checkbox col-md-6"
                                v-show="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">
                                <label><input type="checkbox"
                                              :checked="tblBuyOptions.code_price ? 'checked':''"
                                              v-model="tblBuyOptions.code_price">
                                    {{ $t('message.list_backlinks.t_code_price') }}
                                </label>
                            </div>
                            <div
                                class="checkbox col-md-6"
                                v-if="user.isAdmin || user.isOurs !== 1 || isShowPriceBasis(user)">
                                <label><input type="checkbox"
                                              :checked="tblBuyOptions.price_basis ? 'checked':''"
                                              v-model="tblBuyOptions.price_basis">
                                    {{ $t('message.list_backlinks.filter_price_basis') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.list_backlinks.close') }}
                        </button>
                        <button type="button"
                                class="btn btn-primary"
                                @click="saveColumnSetting"
                                data-dismiss="modal">
                            {{ $t('message.list_backlinks.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->

        <!-- Modal View Topics -->
        <div class="modal fade" id="modal-view-topic-list">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Topic list for  ID# {{ topicModel.publisher_id }}</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-group">
                                    <li v-for="(topic, index) in topicModel.topics" :key="index" class="list-group-item p-2">{{ topic }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            {{ $t('message.publisher.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal View Topics -->
    </div>
</template>

<style>
#custom .vs__dropdown-toggle {
    min-height: 37px;
}

#tbl_buy_backlink {
    /* table-layout: fixed; */
    overflow: scroll;
    width: 100% !important;
}

#tbl_buy_backlink .resize {
    width: auto !important;
    white-space: normal;
    text-overflow: clip;
    overflow: hidden;
}

.box-body.no-padding.relative {
    overflow: scroll;
}

/** .item-line {
    height: auto !important;
} **/

.item-line .action-column .item-cell-inner {
    height: auto !important;
    display: flex;
    flex-direction: column;
}

.header-line .header-cell .header-cell-inner {
    word-break: break-word !important;
    text-align: center !important;
    font-weight: bold;
}

.action-btns {
    width: 50px;
    margin: 2px;
}
</style>

<script>
import {mapState} from 'vuex';
import VueVirtualTable from 'vue-virtual-table';
import Sort from '@/components/sort/Sort';
import {buyerAccess} from "../../../mixins/buyerAccess";
import {splitList} from "../../../mixins/splitList";

export default {
    components : {
        VueVirtualTable,
        Sort,
    },
    mixins: [buyerAccess, splitList],
    data() {
        return {
            paginate : [
                50,
                150,
                250,
                350,
                500,
                'All'
            ],
            updateModel : {
                id : '',
                url : '',
                price : '',
                anchor_text : '',
                link : '',
                url_advertiser : '',
                idea_for_title: '',
            },
            buySelectedModel : {
                anchor_text : '',
                link : '',
                url_advertiser : '',
                idea_for_title: '',
            },
            linkInjectionModel : {
                id : '',
                url : '',
                price : '',
                anchor_text : '',
                link : '',
                url_advertiser : '',
                url_article: '',
                injection_price: '',
            },
            tableLoading : false,
            isPopupLoading : false,
            filterModel : {
                continent_id : this.$route.query.continent_id
                    ? Array.isArray(this.$route.query.continent_id)
                        ? this.$route.query.continent_id.map(function (val) {
                            return parseInt(val, 10);
                        })
                        : [parseInt(this.$route.query.continent_id)]
                    : '',
                country_id : this.$route.query.country_id
                    ? Array.isArray(this.$route.query.country_id)
                        ? this.$route.query.country_id.map(function (val) {
                            return parseInt(val, 10);
                        })
                        : [parseInt(this.$route.query.country_id)]
                    : '',
                search : this.$route.query.search || '',
                interested_domain_name : this.$route.query.interested_domain_name || '',
                language_id : '',
                status_purchase : this.$route.query.status_purchase || '',
                seller : this.$route.query.seller || '',
                code : this.$route.query.code || '',
                casino_sites : this.$route.query.casino_sites || '',
                topic : this.$route.query.topic || '',
                domain_zone : this.$route.query.domain_zone || '',
                price_basis : this.$route.query.price_basis || '',
                paginate : this.$route.query.paginate || 50,
                ur : this.$route.query.ur || 0,
                dr : this.$route.query.dr || 0,
                org_kw : this.$route.query.org_kw || 0,
                org_traffic :
                    this.$route.query.org_traffic || 0,
                price : this.$route.query.price || 0,
                is_https : this.$route.query.is_https
                    || ''
            },
            buttonState : {
                ur : 'Above',
                dr : 'Above',
                org_kw : 'Above',
                org_traffic : 'Above',
                price : 'Above',
                status_purchase: 'User',
            },
            searchLoading : false,
            dataTable : null,
            isCreditAuth : false,
            listCode : [
                '4A',
                '3A',
                '2A',
                '1A',
                '0A',
                '4B',
                '3B',
                '2B',
                '1B',
                '0B',
                '4C',
                '3C',
                '2C',
                '1C',
                '0C',
                '4D',
                '3D',
                '2D',
                '1D',
                '0D',
                '4E',
                '3E',
                '2E',
                '1E',
                '0E',
            ],
            checkIds : [],
            isDisabled : true,
            allSelected : false,
            buyData : [],
            isSearching : false,
            topic : [
                'N/A',
                'Adult',
                'Art',
                'Automotive',
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

            isExtBuyerWithCommission : false,
            updateFormula : {},
            generatorLoader : null,

            country_continent_filter_info : '',
            sort_options: [],
            btnBuy: false,
            btnLinkInjection: false,

            btnBuySelected: false,

            currentWindowWidth: window.innerWidth,
            isBuyerHasDiscount: false,

            estimationPurchases: 0,
            isInterestedFiltered: false,

            errorMessages: [],

            topicModel: {
                publisher_id: '',
                topics: []
            }
        }
    },

    mounted() {
        let self = this;

        pusher.logToConsole = true;

        const channel = pusher.subscribe('private-user.' +
            this.user.id);

        const channel2 =
            pusher.subscribe('BestPriceChannel');

        channel.bind('buyer.bought', (e) => {
            this.getBuyList();
        });

        channel2.bind('bestprices.start', (e) => {
            $('.modal').modal('hide');
            this.generatorLoader = this.$loading.show({
                container : this.$refs.buyerBody
            }, {
                default :
                    self.$t('message.list_backlinks.alert_generate')
            });
        });

        channel2.bind('bestprices.end', (e) => {
            this.getBuyList();
            this.generatorLoader.hide();
        });

        this.columnShow();


    },
    watch : {
        'filterModel.continent_id' : function () {
            if (this.filterModel.country_id != null
                && this.filterModel.country_id !== ''
                && this.filterModel.continent_id !== 0
                && this.filterModel.continent_id !== ''
                && this.filterModel.country_id.length !== 0
                && this.filterModel.continent_id.length !== 0
                && this.filterModel.continent_id.includes(0) === false) {

                // get all the countries within the selected continent
                let filtered = this.listCountryAll.data
                    .filter(item => this.filterModel.continent_id
                        .includes(item.continent_id))

                // extract id to array
                let continentCountries = filtered.map(e => e.id);

                // check if every id of country is included on the filtered countries according to continent
                let is_gone = this.filterModel.country_id.every(country => continentCountries.includes(country));

                // extract id of removed countries
                let removedCountries = this.filterModel.country_id.filter(e => !continentCountries.includes(e))

                // remove id of country filter value that is removed from filtered countries via continent
                let filteredCountryIds = this.filterModel.country_id.filter(e => !removedCountries.includes(e))

                // display message
                this.country_continent_filter_info = is_gone
                    ? ''
                    : this.$t('message.list_backlinks.alert_countries');

                this.filterModel.country_id = is_gone
                    ? this.filterModel.country_id
                    : filteredCountryIds.length !== 0 ? filteredCountryIds : '';
            }
        },
    },

    computed : {
        ...mapState({
            tblBuyOptions : state => state.storeBuy.tblBuyOptions,
            listBuy : state => state.storeBuy.listBuy,
            listCountryAll : state => state.storePublisher.listCountryAll,
            listContinent : state => state.storePublisher.listContinent,
            listDomainZones : state => state.storePublisher.listDomainZones,
            messageForms : state => state.storeBuy.messageForms,
            user : state => state.storeAuth.currentUser,
            listSeller : state => state.storeBuy.listSeller,
            formula : state => state.storeSystem.formula,
            listLanguages : state => state.storePublisher.listLanguages,
            isGeneratorOn : state =>
                state.storePublisher.bestPriceGeneratorOn
        }),

        filterCountrySelect() {
            return (this.filterModel.continent_id == null
                || this.filterModel.continent_id === ''
                || this.filterModel.continent_id === 0
                || this.filterModel.continent_id.length === 0
                || this.filterModel.continent_id.includes(0))

                ? this.listCountryAll.data
                : this.listCountryAll.data.filter(item => this.filterModel.continent_id.includes(item.continent_id))
        },

        isSorted() {
            // return this.filterModel.sort !== '' && this.filterModel.sort.length !== 0;
            return this.filterModel.sort !== '';
        },

        sortOptions() {
            let self = this;

            return [
                {
                    name: self.$t('message.list_backlinks.filter_seller'),
                    sort: '',
                    column: 'users.username',
                    hidden: !this.tblBuyOptions.seller
                },
                {
                    name: self.$t('message.list_backlinks.filter_username'),
                    sort: '',
                    column: 'users.username',
                    hidden: !this.tblBuyOptions.username
                },
                {
                    name: self.$t('message.list_backlinks.filter_topic'),
                    sort: '',
                    column: 'topic',
                    hidden: !this.tblBuyOptions.topic
                },
                {
                    name: self.$t('message.list_backlinks.t_casino'),
                    sort: '',
                    column: 'casino_sites',
                    hidden: !this.tblBuyOptions.casino_sites
                },
                {
                    name: self.$t('message.list_backlinks.filter_lang'),
                    sort: '',
                    column: 'languages.name',
                    hidden: !this.tblBuyOptions.language
                },
                {
                    name: self.$t('message.list_backlinks.filter_country'),
                    sort: '',
                    column: 'countries.name',
                    hidden: !this.tblBuyOptions.country
                },
                {
                    name: self.$t('message.list_backlinks.filter_continent'),
                    sort: '',
                    column: 'publisher_continent',
                    hidden: !this.tblBuyOptions.continent
                },
                {
                    name: self.$t('message.list_backlinks.t_url'),
                    sort: '',
                    column: 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(url,\'http://\',\'\'), \'https://\', \'\'), \'www.\', \'\'), \'/\', \'\'), \' \', \'\')',
                    hidden: !this.tblBuyOptions.url
                },
                {
                    name: self.$t('message.list_backlinks.t_https'),
                    sort: '',
                    column: 'is_https',
                    hidden: !this.tblBuyOptions.is_https
                },
                {
                    name: self.$t('message.list_backlinks.filter_ur'),
                    sort: '',
                    column: 'ur',
                    hidden: !this.tblBuyOptions.ur
                },
                {
                    name: self.$t('message.list_backlinks.filter_dr'),
                    sort: '',
                    column: 'dr',
                    hidden: !this.tblBuyOptions.dr
                },
                {
                    name: self.$t('message.list_backlinks.t_backlinks'),
                    sort: '',
                    column: 'backlinks',
                    hidden: !this.tblBuyOptions.backlinks
                },
                {
                    name: self.$t('message.list_backlinks.t_ref_dom'),
                    sort: '',
                    column: 'ref_domain',
                    hidden: !this.tblBuyOptions.ref_domains
                },
                {
                    name: self.$t('message.list_backlinks.filter_org_kw'),
                    sort: '',
                    column: 'org_keywords',
                    hidden: !this.tblBuyOptions.org_keywords
                },
                {
                    name: self.$t('message.list_backlinks.t_ratio'),
                    sort: '',
                    column: 'ratio_value',
                    hidden: !this.tblBuyOptions.ratio
                },
                {
                    name: self.$t('message.list_backlinks.filter_org_traffic'),
                    sort: '',
                    column: 'cast(org_traffic as unsigned)',
                    hidden: !this.tblBuyOptions.org_traffic
                },
                {
                    name: [5, 14].includes(self.user.role_id) ? self.$t('message.list_backlinks.t_prices') : self.$t('message.list_backlinks.t_price'),
                    sort: '',
                    column: [5, 14].includes(self.user.role_id) ? 'computed_price' : 'cast(price as unsigned)',
                    hidden: !this.tblBuyOptions.price
                },
                // buyer price sort for internal users
                {
                    name: self.$t('message.list_backlinks.t_prices'),
                    sort: '',
                    column: 'computed_price',
                    hidden: !(this.user.isAdmin || this.user.isOurs !== 1)
                },
                // {
                //     name: 'Prices',
                //     sort: '',
                //     column: 'cast(dr as unsigned)', // for checking
                //     hidden: !this.tblBuyOptions.prices
                // },
                // {
                //     name: 'Status',
                //     sort: '',
                //     column: 'cast(backlinks as unsigned)', // for checking
                //     hidden: !this.tblBuyOptions.status
                // },
                {
                    name: self.$t('message.list_backlinks.t_code_comb'),
                    sort: '',
                    column: 'code_comb',
                    hidden: !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.code_comb
                },
                {
                    name: self.$t('message.list_backlinks.t_code_price'),
                    sort: '',
                    column: 'code_price',
                    hidden: !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.code_price
                },
                {
                    name: self.$t('message.list_backlinks.filter_price_basis'),
                    sort: '',
                    column: 'price_basis',
                    hidden: !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.price_basis
                },
                {
                    name: 'Interested Domain Name',
                    sort: '',
                    column: 'interested_domain_name',
                    hidden : !this.tblBuyOptions.interested_domain_name
                },
            ]
        },

        tableConfig() {
            return [
                {
                    prop : '_index',
                    name : '#',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : ' ',
                    actionName : 'actionSelectRow',
                    width : '50',
                    isHidden : false
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_seller'),
                    // sortable : true,
                    actionName : 'sellerData',
                    width : 160,
                    isHidden : (this.user.role_id == 5 && this.user.isOurs == 1) || !this.tblBuyOptions.seller
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_username'),
                    // sortable : true,
                    actionName : 'usernameData',
                    width : 160,
                    isHidden : !this.tblBuyOptions.username
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_topic'),
                    actionName : 'topicData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.topic
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_casino'),
                    actionName : 'casinoSiteData',
                    width : 175,
                    isHidden : !this.tblBuyOptions.casino_sites
                },
                {
                    prop : 'language_name',
                    name : this.$t('message.list_backlinks.filter_lang'),
                    // sortable : true,
                    width : 110,
                    isHidden : !this.tblBuyOptions.language
                },
                {
                    prop : 'country_name',
                    name : this.$t('message.list_backlinks.filter_country'),
                    // sortable : true,
                    width : 120,
                    isHidden : !this.tblBuyOptions.country
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_continent'),
                    actionName : 'continentData',
                    width : 150,
                    isHidden : !this.tblBuyOptions.continent
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_url'),
                    actionName : 'urlData',
                    width : 175,
                    isHidden : !this.tblBuyOptions.url
                },
                {
                    prop : '_action',
                    name : 'Interested Domain Name',
                    actionName : 'interestedDomainName',
                    width : 175,
                    isHidden : !this.tblBuyOptions.interested_domain_name
                },
                {
                    prop : 'is_https',
                    name : this.$t('message.list_backlinks.t_https'),
                    // sortable : true,
                    width : 200,
                    isHidden : !this.tblBuyOptions.is_https
                },
                {
                    prop : 'ur',
                    name : this.$t('message.list_backlinks.filter_ur'),
                    // sortable : true,
                    width : 50,
                    isHidden : !this.tblBuyOptions.ur
                },
                {
                    prop : 'dr',
                    name : this.$t('message.list_backlinks.filter_dr'),
                    // sortable : true,
                    width : 50,
                    isHidden : !this.tblBuyOptions.dr
                },
                {
                    prop : 'backlinks',
                    name : this.$t('message.list_backlinks.t_backlinks'),
                    // sortable : true,
                    width : 100,
                    isHidden : !this.tblBuyOptions.backlinks
                },
                {
                    prop : 'ref_domain',
                    name : this.$t('message.list_backlinks.t_ref_dom'),
                    // sortable : true,
                    width : 125,
                    isHidden : !this.tblBuyOptions.ref_domains
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_org_kw'),
                    actionName : 'orgKeywordData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.org_keywords
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_ratio'),
                    actionName : 'ratioData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.ratio
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.filter_org_traffic'),
                    actionName : 'orgTrafficData',
                    width : 120,
                    isHidden : !this.tblBuyOptions.org_traffic
                },
                {
                    prop : '_action',
                    name : !this.user.isAdmin && [5, 14].includes(this.user.role_id) ? this.$t('message.list_backlinks.t_prices'):this.$t('message.list_backlinks.t_price'),
                    actionName : 'priceData',
                    // sortable: true,
                    // prefix: '$',
                    width : 100,
                    isHidden : !this.tblBuyOptions.price
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_prices'),
                    actionName : 'pricesData',
                    // sortable: true,
                    // prefix: '$',
                    width : 100,
                    isHidden : (!this.user.isAdmin && this.user.role_id != 8) || !this.tblBuyOptions.prices
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_status'),
                    actionName : 'statusData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.status
                },
                {
                    prop : 'code_comb',
                    name : this.$t('message.list_backlinks.t_code_comb'),
                    // sortable : true,
                    width : 125,
                    isHidden : !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.code_comb
                },
                {
                    prop : 'code_price',
                    name : this.$t('message.list_backlinks.t_code_price'),
                    // sortable : true,
                    prefix : '$ ',
                    width : 120,
                    isHidden : !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.code_price
                },
                {
                    prop : 'price_basis',
                    name : this.$t('message.list_backlinks.filter_price_basis'),
                    // sortable : true,
                    width : 120,
                    isHidden :
                        !(this.user.isAdmin || this.user.isOurs !== 1 || this.isShowPriceBasis(this.user)) || !this.tblBuyOptions.price_basis
                },
                {
                    prop : '_action',
                    name : this.$t('message.list_backlinks.t_action'),
                    actionName : 'actionButtons',
                    width : 300,
                    isHidden : false,
                    eClass: {'action-column': 'true'}
                },
            ];
        }
    },

    async created() {
        this.getFormula();
        this.getBuyList();
        this.getListCountries();
        this.getListContinents();
        this.checkCreditAuth();
        this.getListSeller();
        this.getListDomainZones();
        // this.columnShow();
        this.checkBuyerCommission();

        let language = this.listLanguages.data;
        if (language.length === 0) {
            this.getListLanguages();
        }


        window.addEventListener("resize", this.resizeEventHandler);
    },

    destroyed() {
        window.removeEventListener("resize", this.resizeEventHandler);
    },

    methods : {
        // disabled these function
        checkBuyerDiscount() {
            let user = this.user;

            if(user.role_id == 5) {
                let date_reg = user.created_at
                let registered_date = new Date(date_reg)
                let deposited = this.listBuy.deposited

                // promo date range
                let date1 = new Date('11/30/2022');
                let date2 = new Date('01/06/2023');

                // check if user has registered within these range date and has minimum deposit of $200
                if(registered_date >=  date1 && registered_date <= date2) {
                    if(deposited >= 200) {
                        this.isBuyerHasDiscount = true;
                    }
                }

            }
        },

        resizeEventHandler(e) {
            this.currentWindowWidth = window.innerWidth;
        },

        updateSortOptions(data) {
            this.sort_options = data;
        },

        sortBuyBacklinks(data) {
            this.filterModel.sort = data.filter(item => item.sort !== '' && item.hidden !== true)
            this.getBuyList();
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
                !columnState;
        },

        toggleTableLoading() {
            if (this.tableLoading) {
                this.tableLoading = false;
            } else {
                this.tableLoading = true;
            }
        },

        toggleSearchLoading() {
            if (this.searchLoading) {
                this.searchLoading = false;
            } else {
                this.searchLoading = true;
            }
        },

        getRatio(org_kw, org_traffic) {
            let result = 0;

            if (org_traffic != 0) {
                result = parseFloat(org_kw) / parseFloat(org_traffic)
            }

            return result.toFixed(3);
        },

        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

        checkBuyerCommission() {
            let email = this.user.email;
            this.getBuyerCommission(email);
        },

        async getFormula() {
            await this.$store.dispatch('actionGetFormula');
            this.updateFormula = this.formula.data[0];
        },

        async getBuyerCommission(email) {
            try {
                const response = await axios.get('api/buyer-commission', {
                    params : {
                        email : email,
                    }
                }).then(res => {
                    let commission = res.data.commission;
                    if (commission == 'yes') {
                        this.isExtBuyerWithCommission = true;
                    }
                });
            } catch (error) {
                console.error(error);
            }
        },

        columnShow() {
            if ([5, 14].includes(this.user.role_id)) {
                this.tblBuyOptions.casino_sites = false;
                this.tblBuyOptions.seller = false;
                this.tblBuyOptions.code_comb = false;
                this.tblBuyOptions.code_price = false;
            }

            // this.tblBuyOptions.country = false;
        },

        formatPrice(value) {
            let val = (value / 1).toFixed(0)
            return val;
        },

        async checkCreditAuth(params) {
            // this.isCreditAuth = false;
            // await this.$store.dispatch('actionCheckCreditAuth', params)
            // if( this.messageForms.message == "No"){
            //     this.isCreditAuth = true;
            // }
        },

        async getBuyList(page = 1) {
            let self = this;

            await
                this.$store.dispatch('getGeneratorLogs');

            if (this.isGeneratorOn) {
                this.generatorLoader = this.$loading.show({
                    container : this.$refs.buyerBody
                }, {
                    default :
                        self.$t('message.list_backlinks.alert_generate')
                });
                return;
            }

            let loader = this.$loading.show();
            this.toggleSearchLoading();
            this.isSearching = true;

            await this.$store.dispatch('actionGetBuyList', {
                params : {
                    continent_id : this.filterModel.continent_id,
                    country_id : this.filterModel.country_id,
                    search : this.filterModel.search,
                    interested_domain_name : this.filterModel.interested_domain_name,
                    language_id : this.filterModel.language_id,
                    status_purchase : this.filterModel.status_purchase,
                    status_purchase_mode : this.buttonState.status_purchase,
                    seller : this.filterModel.seller,
                    code : this.filterModel.code,
                    paginate : this.filterModel.paginate,
                    casino_sites : this.filterModel.casino_sites,
                    topic : this.filterModel.topic,
                    price_basis : this.filterModel.price_basis,
                    ur : this.filterModel.ur,
                    ur_direction : this.buttonState.ur,
                    dr : this.filterModel.dr,
                    dr_direction : this.buttonState.dr,
                    org_kw : this.filterModel.org_kw,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    org_traffic_direction :
                    this.buttonState.org_traffic,
                    price : this.filterModel.price,
                    price_direction :
                    this.buttonState.price,
                    page : page,
                    domain_zone : this.filterModel.domain_zone,
                    is_https : this.filterModel.is_https,
                    sort: this.filterModel.sort
                }
            })

            this.isSearching = false;
            this.toggleSearchLoading();

            // this.checkBuyerDiscount();

            this.btnBuy = false;
            loader.hide();
            this.calculateEstimationPurchases();

            if (this.filterModel.status_purchase == 'Interested') {
                this.isInterestedFiltered = true;
            } else {
                this.isInterestedFiltered = false;
            }
        },

        async getListSeller(params) {
            await this.$store.dispatch('actionGetListSeller', params);
        },

        async getListDomainZones(params) {
            await this.$store.dispatch('actionGetListDomainZones', params);
        },

        checkSelected() {
            this.isDisabled = true;
            if (this.checkIds.length > 0) {
                this.isDisabled = false;
            }
        },

        select : function () {
            this.allSelected = false;
        },

        selectAll : function () {
            this.checkIds = [];
            if (!this.allSelected) {
                for (let buy in this.listBuy.data) {
                    this.checkIds.push(buy);
                }
                this.isDisabled = false;
                this.allSelected = true;
            } else {
                this.isDisabled = true;
                this.allSelected = false;
            }
        },

        clearSearch() {
            $('#tbl_buy_backlink').DataTable().destroy();

            this.filterModel = {
                continent_id : '',
                country_id : '',
                search : '',
                interested_domain_name : '',
                language_id : '',
                status_purchase : '',
                seller : '',
                code : '',
                casino_sites : '',
                topic : '',
                domain_zone : '',
                price_basis : '',
                paginate : 50,
                is_https : ''
            }

            this.country_continent_filter_info = '';

            this.getBuyList({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
        },

        buySelected() {
            let ids = this.checkIds.map(a => a.id);
            this.buyData = [];
            for (var id in ids) {
                for (var buy in this.listBuy.data) {
                    if (this.listBuy.data[buy].id == ids[id]) {
                        this.buyData.push({
                            "id" : this.listBuy.data[buy].id,
                            "url" : this.listBuy.data[buy].url,
                            "price" : this.listBuy.data[buy].price,
                        })
                    }
                }
            }

            // console.log(this.buyData, this.listBuy.data, ids)
        },

        interestedSelected() {
            let self = this;

            let allNotPurchased = self.checkIds.every(item => item.status_purchased !== 'Purchased')

            if (allNotPurchased) {
                let ids = self.checkIds.map(a => a.id);
                this.doLike(ids)
                this.checkIds = [];
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.list_backlinks.selected_items_not_purchased'),
                    'error'
                );
            }
        },

        notInterestedSelected() {
            let self = this;

            let allNotPurchased = self.checkIds.every(item => item.status_purchased !== 'Purchased')

            if (allNotPurchased) {
                let ids = self.checkIds.map(a => a.id);
                this.doDislike(ids)
                this.checkIds = [];
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.list_backlinks.selected_items_not_purchased'),
                    'error'
                );
            }
        },

        uninterestedSelected () {
            let self = this;

            let allInterested = self.checkIds.every(item => item.status_purchased === 'Interested')

            if (allInterested) {
                let ids = self.checkIds.map(a => a.id);
                this.doUninterested(ids)
                self.checkIds = [];
            } else {
                swal.fire(
                    self.$t('message.registration_accounts.alert_invalid'),
                    self.$t('message.list_backlinks.selected_items_interested'),
                    'error'
                );
            }
        },

        doSearch() {
            $('#tbl_buy_backlink').DataTable().destroy();

            this.$router.replace({'query' : null});

            this.$router.push({
                query : this.filterModel,
            });

            this.getBuyList({
                params : {
                    continent_id : this.filterModel.continent_id,
                    country_id : this.filterModel.country_id,
                    search : this.filterModel.search,
                    interested_domain_name : this.filterModel.interested_domain_name,
                    language_id : this.filterModel.language_id,
                    status_purchase : this.filterModel.status_purchase,
                    status_purchase_mode : this.buttonState.status_purchase,
                    seller : this.filterModel.seller,
                    code : this.filterModel.code,
                    paginate : this.filterModel.paginate,
                    casino_sites : this.filterModel.casino_sites,
                    topic : this.filterModel.topic,
                    domain_zone : this.filterModel.domain_zone,
                    price_basis : this.filterModel.price_basis,
                    ur : this.filterModel.ur,
                    ur_direction : this.buttonState.ur,
                    dr : this.filterModel.dr,
                    dr_direction : this.buttonState.dr,
                    org_kw : this.filterModel.org_kw,
                    org_kw_direction : this.buttonState.org_kw,
                    org_traffic : this.filterModel.org_traffic,
                    org_traffic_direction :
                    this.buttonState.org_traffic,
                    is_https : this.filterModel.is_https,
                    sort: this.filterModel.sort
                }
            });
        },

        doUpdate(buy) {
            let self = this;

            if(buy.status_purchased == 'Purchased') {
                swal.fire({
                    title : self.$t('message.list_backlinks.alert_purchase_url'),
                    text : self.$t('message.list_backlinks.alert_purchase_confirm'),
                    icon : "question",
                    showCloseButton: true,
                    showCancelButton: true,
                    showConfirmButton: true,
                    cancelButtonText: self.$t('message.list_backlinks.no'),
                    confirmButtonText: self.$t('message.list_backlinks.yes'),
                    cancelButtonColor: 'red',
                    confirmButtonColor: 'green',
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.clearMessageform();
                        let that = JSON.parse(JSON.stringify(buy))

                        this.updateModel = that
                        this.updateModel.seller_price = that.price;
                        this.updateModel.price = this.computePrice(that.price, that.inc_article, 'yes');
                        this.updateModel.prices = this.updateModel.price

                        $('#modal-buy-update').modal({
                            show : true
                        });
                    }
                });
            } else {
                this.clearMessageform();
                let that = JSON.parse(JSON.stringify(buy))

                this.updateModel = that
                this.updateModel.seller_price = that.price;
                this.updateModel.price = this.computePrice(that.price, that.inc_article, 'yes');
                this.updateModel.prices = this.updateModel.price

                // display interested details if any
                if(buy.status_purchased == 'Interested') {
                    this.updateModel.link = buy.backlinks_interested.length ? buy.backlinks_interested[0].link : null;
                    this.updateModel.url_advertiser = buy.backlinks_interested.length ? buy.backlinks_interested[0].url_advertiser : null;
                    this.updateModel.anchor_text = buy.backlinks_interested.length ? buy.backlinks_interested[0].anchor_text : null;
                }

                $('#modal-buy-update').modal({
                    show : true
                });
            }

        },

        doLinkInjection (data) {
            let self = this;
            self.clearMessageform();

            let temp = JSON.parse(JSON.stringify(data));

            self.linkInjectionModel = temp;
            self.linkInjectionModel.seller_price = temp.price;
            self.linkInjectionModel.price = self.computePrice(temp.price, temp.inc_article, 'yes');
            self.linkInjectionModel.prices = self.linkInjectionModel.price;

            // display interested details if any
            if(data.status_purchased == 'Interested') {
                this.linkInjectionModel.link = data.backlinks_interested.length ? data.backlinks_interested[0].link : null;
                this.linkInjectionModel.url_advertiser = data.backlinks_interested.length ? data.backlinks_interested[0].url_advertiser : null;
                this.linkInjectionModel.anchor_text = data.backlinks_interested.length ? data.backlinks_interested[0].anchor_text : null;
            }

            $('#modal-link-injection').modal({
                show : true
            });
        },

        async doDislike(id) {
            let self = this;

            // if (confirm(self.$t('message.list_backlinks.alert_not_interested'))) {
            //     $('#tbl_buy_backlink').DataTable().destroy();
            //
            //     this.searchLoading = true;
            //     await this.$store.dispatch('actionDislike', {id : id})
            //     this.searchLoading = false;
            //
            //     this.getBuyList();
            // }

            swal.fire({
                title : self.$t('message.list_backlinks.mark_not_interested'),
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    this.$store.dispatch('actionDislike', {id : id})
                    this.searchLoading = false;

                    this.getBuyList();
                }
            });
        },

        async doLike(id) {
            // let that = JSON.parse(JSON.stringify(data))
            //
            // this.updateModel = that
            // this.updateModel.seller_price = that.price;
            // this.updateModel.price = this.computePrice(that.price, that.inc_article);
            // this.updateModel.prices = this.updateModel.price
            //
            // $('#modal-interested-update').modal({
            //     show : true
            // });

            let self = this;

            swal.fire({
                title : self.$t('message.list_backlinks.mark_interested'),
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then(async (result) => {
                if (result.isConfirmed) {
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    await this.$store.dispatch('actionLike', {id : id})
                    this.searchLoading = false;

                    this.getBuyList();
                }
            });
        },

        async doInterested (data) {
            let self = this;

            swal.fire({
                title : self.$t('message.list_backlinks.mark_interested'),
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    this.$store.dispatch('actionInterested', data)
                    this.searchLoading = false;

                    this.getBuyList();

                    $("#modal-buy-update").modal('hide');
                }
            });
        },

        async doUninterested (id) {
            let self = this;

            swal.fire({
                title : self.$t('message.list_backlinks.remove_interested'),
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    this.$store.dispatch('actionUninterested', {id : id})
                    this.searchLoading = false;

                    this.getBuyList();

                    $("#modal-buy-update").modal('hide');
                }
            });
        },

        async saveInterestedDetails (data) {
            let self = this;

            swal.fire({
                title : 'Save interested details?',
                icon : "question",
                showCancelButton : true,
                confirmButtonText : self.$t('message.article.yes'),
                cancelButtonText : self.$t('message.article.no')
            })
            .then(async (result) => {
                if (result.isConfirmed) {
                    let loader = this.$loading.show();
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    await this.$store.dispatch('saveInterestedDetails', this.updateModel)
                    this.searchLoading = false;

                    if (this.messageForms.action === 'saved') {
                        this.getBuyList();

                        $("#modal-buy-update").modal('hide');

                        loader.hide();

                        await swal.fire(
                            self.$t('message.list_backlinks.alert_success'),
                            'Interested details successfully saved',
                            'success'
                        )
                    } else {
                        loader.hide();

                        await swal.fire(
                            this.messageForms.message,
                            '',
                            'error'
                        )
                    }
                }
            });
        },

        computePriceStalinks(price, article) {

            let selling_price = price
            let percent = parseFloat(this.formula.data[0].percentage);
            let additional = parseFloat(this.formula.data[0].additional);

            let commission = 'yes';

            if (price != '' && price != null) { // check if price has a value

                if(article){

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

            }

            selling_price = parseFloat(selling_price).toFixed(0);

            return selling_price;
        },

        computePrice(price, article, show_discount) {

            let activeUser = this.user
            let selling_price = price
            let percent = parseFloat(this.formula.data[0].percentage);
            let additional = parseFloat(this.formula.data[0].additional);
            let buyer_type_basic = parseFloat(this.formula.data[0].basic);
            let buyer_type_medium = parseFloat(this.formula.data[0].medium);
            let buyer_type_premium = parseFloat(this.formula.data[0].premium);

            if (activeUser.user_type) { //check if has user_type value

                let type = activeUser.user_type.type
                let buyer_type = activeUser.user_type.buyer_type
                let commission = (activeUser.user_type.commission).toLowerCase()

                if(buyer_type == 'Basic') {
                    percent = buyer_type_basic;
                } else if (buyer_type == 'Medium') {
                    percent = buyer_type_medium;
                } else {
                    percent = buyer_type_premium;
                }

                if (price != '' && price != null) { // check if price has a value

                    if (type == 'Buyer') { // check if the user_type is a 'Buyer'

                        if(article) {

                            if (article.toLowerCase() == 'yes') { //check if with article

                                if (commission == 'no') {
                                    selling_price = price
                                }

                                if (commission == 'yes') {
                                    let percentage = this.percentage(percent, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price)
                                }
                            }

                            if (article.toLowerCase() == 'no') { //check if without article

                                if (commission == 'no') {
                                    selling_price = parseFloat(price) + additional
                                }

                                if (commission == 'yes') {
                                    let percentage = this.percentage(percent, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price) + additional
                                }

                            }
                        }
                    }

                }
            }

            selling_price = parseFloat(selling_price).toFixed(0);

            // if buyer has a discount
            // if(this.isBuyerHasDiscount && show_discount == 'yes') {
            //     let discount = selling_price * .15
            //     selling_price = selling_price - discount
            // }

            return selling_price;
        },

        replaceCharacters(str) {
            let char1 = str.replace("http://", "");
            let char2 = char1.replace("https://", "");
            let char3 = char2.replace("www.", "");
            let char4 = char3.replace("/", "");

            return char4;
        },

        percentage(percent, total) {
            return ((percent / 100) * total).toFixed(2)
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        async getListContinents(params) {
            await this.$store.dispatch('actionGetListContinents', params);
        },

        async submitBuySelected() {

            let self = this;
            let loader = this.$loading.show();
            let credit_left = parseInt(this.listBuy.credit);
            let anchor_text = this.buySelectedModel.anchor_text
            let link = this.buySelectedModel.link
            let url_advertiser = this.buySelectedModel.url_advertiser
            let idea_for_title = this.buySelectedModel.idea_for_title
            $('#tbl_buy_backlink').DataTable().destroy();

            this.btnBuySelected = true;

            let datas = this.checkIds
            for (var data in datas) {
                let that = datas[data];
                this.buySelectedModel = datas[data]
                this.buySelectedModel.credit_left = credit_left;
                this.buySelectedModel.anchor_text = anchor_text;
                this.buySelectedModel.link = link;
                this.buySelectedModel.url_advertiser = url_advertiser;
                this.buySelectedModel.idea_for_title = idea_for_title;

                this.buySelectedModel.seller_price = that.price;
                this.buySelectedModel.price = this.computePrice(that.price, that.inc_article, 'yes');
                this.buySelectedModel.prices = this.buySelectedModel.price

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateBuy', this.buySelectedModel);
                this.isPopupLoading = false;
            }

            if (this.messageForms.action === 'updated') {
                this.getBuyList();
                this.$root.$refs.AppHeader.liveGetWallet()

                $("#modal-buy-selected").modal('hide');

                loader.hide();

                await swal.fire(
                    self.$t('message.list_backlinks.alert_success'),
                    self.$t('message.list_backlinks.alert_order'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    this.messageForms.message,
                    '',
                    'error'
                )
            }

            this.checkIds = [];

            if(this.messageForms.errors) {
                this.btnBuySelected = false;
            }

            loader.hide();

        },

        async submitBuy(params) {
            let self = this;
            let loader = this.$loading.show();
            let credit_left = parseInt(this.listBuy.credit);
            $('#tbl_buy_backlink').DataTable().destroy();

            /*if (this.user.isOurs == 1 && this.user.role_id == 5 && (this.user.registration != null && this.user.registration.is_sub_account == 1)) { // check if sub buyer account
                if (credit_left <= 0) { // check if credit is 0
                    swal.fire('error', 'No credits left', 'error');
                    return false;
                }
            }*/

            this.updateModel.credit_left = credit_left;
            this.btnBuy = true;

            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateBuy', this.updateModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated') {
                this.getBuyList();
                this.$root.$refs.AppHeader.liveGetWallet()

                $("#modal-buy-update").modal('hide');

                loader.hide();

                await swal.fire(
                    self.$t('message.list_backlinks.alert_success'),
                    self.$t('message.list_backlinks.alert_order'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    this.messageForms.message,
                    '',
                    'error'
                )
            }

            if(this.messageForms.errors) {
                this.btnBuy = false;
            }

            loader.hide();
        },

        submitLinkInjection () {
            let self = this;
            let loader = self.$loading.show();
            self.btnLinkInjection = true;

            axios.post('/api/link-injection-request', self.linkInjectionModel)
            .then((res) => {
                loader.hide();

                swal.fire(
                    self.$t('message.registration_accounts.alert_success'),
                    self.$t('message.registration_accounts.alert_updated_successfully'),
                    'success'
                )

                self.btnLinkInjection = false;
                $('#modal-link-injection').modal('hide');
            })
            .catch(err => {
                loader.hide();

                swal.fire(
                    self.$t('message.draft.error'),
                    err.response.data.message,
                    'error'
                )

                self.errorMessages = err.response.data.errors

                self.btnLinkInjection = false;
            })
        },

        async submitInterest(params) {
            $('#tbl_buy_backlink').DataTable().destroy();

            this.isPopupLoading = true;
            await this.$store.dispatch('actionLike', this.updateModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated') {
                this.getBuyList();
                this.$root.$refs.AppHeader.liveGetWallet()
            }
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },

        setSelectedOption(options, value) {
            console.log(value);
        },

        selectAll: function() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (let buy in this.listBuy.data) {
                    this.checkIds.push(this.listBuy.data[buy]);
                }
                this.isDisabled = false;
                this.allSelected = true;
            } else {
                this.allSelected = false;
            }
        },

        calculateEstimationPurchases() {
            let list = this.listBuy.data
            let total_price = [];
            let total = 0;

            list.forEach(function(item, index){
                if (item.computed_price !== null && typeof item.computed_price !== 'undefined') {
                    total_price.push( parseFloat(item.computed_price))
                }
            })

            if( total_price.length > 0 ){
                total = total_price.reduce(this.calcSum)
            }

            this.estimationPurchases = total.toFixed(2);
        },

        calcSum(total, num) {
            return total + num
        },

        showTopicList (publisher_id, topics) {
            let self = this;
            self.topicModel.publisher_id = publisher_id;
            self.topicModel.topics = self.splitCommaSeparated(topics);

            $('#modal-view-topic-list').modal({
                show: true
            });
        },
    },
}
</script>

<style>
    .style-chooser-group {
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
        margin-bottom: 0;
    }

    .custom-footer {
        display: flex;
        justify-content: space-between;
    }

    .custom-footer .btn-info.pull-left {
        order: -1;
    }
</style>
