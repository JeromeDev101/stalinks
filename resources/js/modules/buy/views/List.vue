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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Search URL</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="filterModel.search"
                                           name=""
                                           aria-describedby="helpId"
                                           placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Status of Purchased</label>
                                    <v-select multiple
                                              v-model="filterModel.status_purchase"
                                              :options="['New', 'Interested', 'Not interested', 'Purchased']"
                                              :searchable="false"
                                              placeholder="All"/>
                                    <!-- <v-select multiple v-model="filterModel.status_purchase" :options="['New', 'Interested', 'Not interested', 'Purchased']" id="custom" ></v-select> -->
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Language</label>
                                    <v-select
                                        v-model="filterModel.language_id"
                                        multiple
                                        label="name"
                                        placeholder="All"
                                        :searchable="true"
                                        :options="listLanguages.data"
                                        :reduce="language => language.id"/>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Continent</label>
                                    <v-select
                                        v-model="filterModel.continent_id"
                                        multiple
                                        label="name"
                                        placeholder="All"
                                        :searchable="true"
                                        :options="listContinent.data"
                                        :reduce="continent => continent.id"/>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Country</label>
                                    <v-select
                                        v-model="filterModel.country_id"
                                        multiple
                                        label="name"
                                        placeholder="All"
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

                            <div class="col-md-2"
                                 v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 7) || (user.isOurs == 0 && user.role_id == 5)">
                                <div class="form-group">
                                    <label for="">Seller</label>
                                    <select name="" class="form-control" v-model="filterModel.seller">
                                        <option value="">All</option>
                                        <option v-for="option in listSeller.data" v-bind:value="option.id">
                                            {{ option.username == null ? option.name : option.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2"
                                 v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 7) || user.role_id === 5">
                                <div class="form-group">
                                    <label for="">Code</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.code">
                                        <option value="">All</option>
                                        <option v-for="option in listCode" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->

                                    <v-select
                                        v-model="filterModel.code"
                                        multiple
                                        placeholder="All"
                                        :options="listCode"
                                        :searchable="false"
                                    />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Accept Casino & Betting Sites</label>
                                    <select name="" class="form-control" v-model="filterModel.casino_sites">
                                        <option value="">All</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Topic</label>
                                    <!-- <select name="" class="form-control" v-model="filterModel.topic">
                                        <option value="">All</option>
                                        <option v-for="option in topic" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select> -->
                                    <v-select multiple
                                              v-model="filterModel.topic"
                                              :options="topic"
                                              :searchable="false"
                                              placeholder="All"/>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Domain Zone</label>
                                    <v-select
                                        v-model="filterModel.domain_zone"
                                        multiple
                                        label="name"
                                        placeholder="All"
                                        :options="listDomainZones.data"
                                        :searchable="true"
                                        :reduce="domain => domain.name"/>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Price Basis</label>
                                    <v-select multiple
                                              v-model="filterModel.price_basis"
                                              :options="['Good', 'Average', 'High']"
                                              :searchable="false"
                                              placeholder="All"/>
                                    <!--                                <select name="" class="form-control" v-model="filterModel.price_basis">-->
                                    <!--                                    <option value="">All</option>-->
                                    <!--                                    <option value="Good">Good</option>-->
                                    <!--                                    <option value="Average">Average</option>-->
                                    <!--                                    <option value="High">High</option>-->
                                    <!--                                </select>-->
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Is Https?</label>
                                    <select
                                        class="form-control"
                                        v-model="filterModel.is_https">
                                        <option value="">All</option>
                                        <option
                                            value="yes">Yes
                                        </option>
                                        <option
                                            value="no">
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">UR</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.ur = buttonState.ur === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.ur }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.ur">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">DR</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.dr = buttonState.dr === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.dr }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.dr">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Org Kw</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_kw = buttonState.org_kw === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.org_kw }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_kw">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Org Traffic</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary"
                                                    type="button"
                                                    @click="buttonState.org_traffic = buttonState.org_traffic === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.org_traffic }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.org_traffic">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                @click="buttonState.price = buttonState.price === 'Above' ? 'Below' : 'Above'">
                                                {{ buttonState.price }}
                                            </button>
                                        </div>
                                        <input type="text"
                                               class="form-control"
                                               placeholder="Type here"
                                               aria-label=""
                                               aria-describedby="basic-addon1"
                                               v-model="filterModel.price">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search
                                    <i v-if="searchLoading" class="fa fa-refresh fa-spin"></i></button>
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
                        <h3 class="card-title text-primary">Buy Backlinks</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="ml-5 text-primary" v-show="user.role_id == 5">
                            Credit Left: <b>${{listBuy.credit }}</b>
                        </span>

                        <div class="input-group input-group-sm float-right ml-3" style="width: 100px">
                            <select name=""
                                    class="form-control float-right"
                                    @change="getBuyList"
                                    v-model="filterModel.paginate"
                                    style="height: 37px;">
                                <option v-for="option in paginate" v-bind:value="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i
                            class="fa fa-cog"></i></button>

                        <div v-if="isCreditAuth" class="alert alert-warning my-3">
                            Sorry you cannot Purchase backlinks due to lack of Wallet. Click
                            <button class="btn btn-link" @click="checkCreditAuth">
                                Retry
                            </button>
                            if you have given permission to purchased
                        </div>

                        <div class="col-md-2 my-3">
                            <div class="input-group">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle"
                                            :disabled="isDisabled"
                                            type="button"
                                            id="dropdownMenuButton"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                        Selected Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <!-- <a class="dropdown-item" @click="buySelected" data-target="#modal-buy-selected" data-toggle="modal">Buy</a> -->
                                        <a class="dropdown-item " @click="interestedSelected">Interested</a>
                                        <a class="dropdown-item " @click="notInterestedSelected">Not Interested</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="float-right col-md-1 mb-3">
                            <Sort
                                ref="sortComponent"
                                :sorted="isSorted"
                                :items="sortOptions"

                                @submitSort="sortBuyBacklinks"
                                @updateOptions="updateSortOptions">
                            </Sort>
                        </div>

                        <span v-if="listBuy.total > 10" class="pagination-custom-footer-text">
                            <b>Showing {{ listBuy.from }} to {{ listBuy.to }} of {{ listBuy.total }} entries.</b>
                        </span>

                        <vue-virtual-table
                            v-if="!tableLoading"
                            width="100%"
                            :height="600"
                            :bordered="true"
                            :item-height="60"
                            :config="tableConfig"
                            :data="listBuy.data">
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
                                slot="topicData">
                                {{
                                    scope.row.topic == null ?
                                        'N/A' : scope.row.topic
                                }}
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
                                {{
                                    scope.row.price == '' ||
                                    scope.row.price
                                    == null ? '' : '$'
                                }} {{
                                    computePrice(scope.row.price,
                                        scope.row.inc_article)
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
                                <div class="btn-group" ref="text">
                                    <button
                                        v-if="scope.row.price
                                 != '' && scope.row.price
                                  != null"
                                        :disabled="isCreditAuth"
                                        title="Buy"
                                        data-target="#modal-buy-update"
                                        @click="doUpdate(scope.row)"
                                        data-toggle="modal"
                                        class="btn btn-default"><i class="fas fa-dollar-sign"></i></button>
                                    <button
                                        :disabled="scope.row.status_purchased == 'Interested'"
                                        @click="doLike(scope.row.id)"
                                        title="Interested"
                                        class="btn btn-default"><i class="fa fa-fw fa-thumbs-up"></i></button>
                                    <button
                                        :disabled="scope.row.status_purchased == 'Not interested'"
                                        @click="doDislike(scope.row.id)"
                                        title="Not Interested"
                                        class="btn btn-default"><i class="fa fa-fw fa-thumbs-down"></i></button>
                                </div>
                            </template>
                        </vue-virtual-table>
                        <pagination :data="listBuy" @pagination-change-page="getBuyList" :limit="8"></pagination>
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
                        <h5 class="modal-title">Buy Backlink</h5>
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
                                    <label for="">URL</label>
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
                                    <label for="">Prices</label>
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
                                    <label for="">Anchor Text</label>
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
                                    <label for="">Link To</label>
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
                                    <label for="">URL Advertiser</label>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitBuy" class="btn btn-primary">Buy</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Buy -->

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
                        <h5 class="modal-title">Buy Selected Backlink</h5>
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
                                    <label for="">URL</label>
                                    <input type="text"
                                           class="form-control"
                                           :value="buy.url"
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number"
                                           class="form-control"
                                           :value="buy.price"
                                           aria-describedby="helpId"
                                           placeholder=""
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-2 my-auto">
                                <button type="button"
                                        class="btn btn-link"
                                        data-toggle="collapse"
                                        :data-target="'#collapseExample'+index">View more
                                </button>
                            </div>
                            <div class="col-md-12">
                                <div class="collapse" :id="'collapseExample'+index">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Anchor Text</label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name=""
                                                           aria-describedby="helpId"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Link To</label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name=""
                                                           aria-describedby="helpId"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">URL Advertiser</label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name=""
                                                           aria-describedby="helpId"
                                                           placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Buy All</button>
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
                        <h4 class="modal-title">Setting Default</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6" v-if="user.role_id != 5 && user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.seller ? 'checked':''" v-model="tblBuyOptions.seller">Seller</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.topic ? 'checked':''"
                                    v-model="tblBuyOptions.topic">Topic</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.casino_sites ? 'checked':''"
                                    v-model="tblBuyOptions.casino_sites">Casino & Betting Sites</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.language ? 'checked':''"
                                    v-model="tblBuyOptions.language">Language</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.country ? 'checked':''"
                                    v-model="tblBuyOptions.country">Country</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.continent ? 'checked':''"
                                    v-model="tblBuyOptions.continent">Continent</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.url ? 'checked':''"
                                    v-model="tblBuyOptions.url">URL</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ur ? 'checked':''"
                                    v-model="tblBuyOptions.ur">UR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.dr ? 'checked':''"
                                    v-model="tblBuyOptions.dr">DR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.backlinks ? 'checked':''"
                                    v-model="tblBuyOptions.backlinks">Backlinks</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ref_domains ? 'checked':''"
                                    v-model="tblBuyOptions.ref_domains">Ref Domains</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.org_keywords ? 'checked':''"
                                    v-model="tblBuyOptions.org_keywords">Organic Keywords</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.ratio ? 'checked':''"
                                    v-model="tblBuyOptions.ratio">Ratio</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.org_traffic ? 'checked':''"
                                    v-model="tblBuyOptions.org_traffic">Organic Traffic</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.price ? 'checked':''"
                                    v-model="tblBuyOptions.price">{{ user.role_id == 5 ? 'Prices' : 'Price' }}</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="user.isAdmin">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.prices ? 'checked':''"
                                    v-model="tblBuyOptions.prices">Prices</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.status ? 'checked':''"
                                    v-model="tblBuyOptions.status">Status</label>
                            </div>
                            <div class="checkbox col-md-6" v-show="user.role_id != 5 && user.isOurs != 1">
                                <label><input
                                    type="checkbox"
                                    :checked="tblBuyOptions.code_comb ? 'checked':''"
                                    v-model="tblBuyOptions.code_comb">Code Combination</label>
                            </div>
                            <div
                                class="checkbox col-md-6"
                                v-show="user.role_id != 5 && user.isOurs != 1">
                                <label><input type="checkbox"
                                              :checked="tblBuyOptions.code_price ? 'checked':''"
                                              v-model="tblBuyOptions.code_price">Code Price</label>
                            </div>
                            <div
                                class="checkbox col-md-6"
                                v-if="isExtBuyerWithCommission">
                                <label><input type="checkbox"
                                              :checked="tblBuyOptions.code_price ? 'checked':''"
                                              v-model="tblBuyOptions.price_basis">Price Basis</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-primary"
                                @click="saveColumnSetting"
                                data-dismiss="modal">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Settings -->
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
</style>

<script>
import {mapState} from 'vuex';
import VueVirtualTable from 'vue-virtual-table';
import Sort from '@/components/sort/Sort';

export default {
    components : {
        VueVirtualTable,
        Sort,
    },
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
                price : 'Above'
            },
            searchLoading : false,
            dataTable : null,
            isCreditAuth : false,
            listCode : [
                '4A',
                '3A',
                '2A',
                '1A',
                '0A'
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
        }
    },

    mounted() {
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
                    'We are generating the URLs\' best prices, please wait...'
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
                    : 'Some selected countries that were not within the selected continent(s) are removed.';

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
                return [
                    {
                        name: 'Seller',
                        sort: '',
                        column: 'users.username',
                        hidden: !this.tblBuyOptions.seller
                    },
                    {
                        name: 'Topic',
                        sort: '',
                        column: 'topic',
                        hidden: !this.tblBuyOptions.topic
                    },
                    {
                        name: 'Casino & Betting sites',
                        sort: '',
                        column: 'casino_sites',
                        hidden: !this.tblBuyOptions.casino_sites
                    },
                    {
                        name: 'Language',
                        sort: '',
                        column: 'languages.name',
                        hidden: !this.tblBuyOptions.language
                    },
                    {
                        name: 'Country',
                        sort: '',
                        column: 'countries.name',
                        hidden: !this.tblBuyOptions.country
                    },
                    {
                        name: 'Continent',
                        sort: '',
                        column: 'publisher_continent',
                        hidden: !this.tblBuyOptions.continent
                    },
                    {
                        name: 'URL',
                        sort: '',
                        column: 'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(url,\'http://\',\'\'), \'https://\', \'\'), \'www.\', \'\'), \'/\', \'\'), \' \', \'\')',
                        hidden: !this.tblBuyOptions.url
                    },
                    {
                        name: 'Https',
                        sort: '',
                        column: 'is_https',
                        hidden: false
                    },
                    {
                        name: 'UR',
                        sort: '',
                        column: 'ur',
                        hidden: !this.tblBuyOptions.ur
                    },
                    {
                        name: 'DR',
                        sort: '',
                        column: 'dr',
                        hidden: !this.tblBuyOptions.dr
                    },
                    {
                        name: 'Blinks',
                        sort: '',
                        column: 'backlinks',
                        hidden: !this.tblBuyOptions.backlinks
                    },
                    {
                        name: 'Ref Domain',
                        sort: '',
                        column: 'ref_domain',
                        hidden: !this.tblBuyOptions.ref_domains
                    },
                    {
                        name: 'Org Kw',
                        sort: '',
                        column: 'org_keywords',
                        hidden: !this.tblBuyOptions.org_keywords
                    },
                    {
                        name: 'Ratio',
                        sort: '',
                        column: 'ratio_value',
                        hidden: !this.tblBuyOptions.ratio
                    },
                    {
                        name: 'Org Traffic',
                        sort: '',
                        column: 'cast(org_traffic as unsigned)',
                        hidden: !this.tblBuyOptions.org_traffic
                    },
                    {
                        name: 'Price',
                        sort: '',
                        column: 'cast(price as unsigned)',
                        hidden: !this.tblBuyOptions.price
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
                        name: 'Code Comb',
                        sort: '',
                        column: 'code_comb',
                        hidden: !this.tblBuyOptions.code_comb
                    },
                    {
                        name: 'Code Price',
                        sort: '',
                        column: 'code_price',
                        hidden: !this.tblBuyOptions.code_price
                    },
                    {
                        name: 'Price Basis',
                        sort: '',
                        column: 'price_basis',
                        hidden: !this.tblBuyOptions.price_basis
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
                    prop : 'user_name',
                    name : 'Seller',
                    // sortable : true,
                    width : 100,
                    isHidden : this.user.role_id == 5 &&
                        this.user.isOurs == 1
                },
                {
                    prop : '_action',
                    name : 'Topic',
                    actionName : 'topicData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.topic
                },
                {
                    prop : '_action',
                    name : 'Casino & Betting sites',
                    actionName : 'casinoSiteData',
                    width : 175,
                    isHidden : !this.tblBuyOptions.casino_sites
                },
                {
                    prop : 'language_name',
                    name : 'Language',
                    // sortable : true,
                    width : 110,
                    isHidden : !this.tblBuyOptions.language
                },
                {
                    prop : 'country_name',
                    name : 'Country',
                    // sortable : true,
                    width : 120,
                    isHidden : !this.tblBuyOptions.country
                },
                {
                    prop : '_action',
                    name : 'Continent',
                    actionName : 'continentData',
                    width : 150,
                    isHidden : !this.tblBuyOptions.continent
                },
                {
                    prop : '_action',
                    name : 'URL',
                    actionName : 'urlData',
                    width : 175,
                    isHidden : !this.tblBuyOptions.url
                },
                {
                    prop : 'is_https',
                    name : 'Https',
                    // sortable : true,
                    width : 200,
                    isHidden : false
                },
                {
                    prop : 'ur',
                    name : 'UR',
                    // sortable : true,
                    width : 50,
                    isHidden : !this.tblBuyOptions.ur
                },
                {
                    prop : 'dr',
                    name : 'DR',
                    // sortable : true,
                    width : 50,
                    isHidden : !this.tblBuyOptions.dr
                },
                {
                    prop : 'backlinks',
                    name : 'Blinks',
                    // sortable : true,
                    width : 100,
                    isHidden : !this.tblBuyOptions.backlinks
                },
                {
                    prop : 'ref_domain',
                    name : 'Ref Domain',
                    // sortable : true,
                    width : 125,
                    isHidden : !this.tblBuyOptions.ref_domains
                },
                {
                    prop : '_action',
                    name : 'Org Kw',
                    actionName : 'orgKeywordData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.org_keywords
                },
                {
                    prop : '_action',
                    name : 'Ratio',
                    actionName : 'ratioData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.ratio
                },
                {
                    prop : '_action',
                    name : 'Org Traffic',
                    actionName : 'orgTrafficData',
                    width : 120,
                    isHidden : !this.tblBuyOptions.org_traffic
                },
                {
                    prop : '_action',
                    name : 'Price',
                    actionName : 'priceData',
                    // sortable: true,
                    // prefix: '$',
                    width : 100,
                    isHidden : !this.tblBuyOptions.price
                },
                {
                    prop : '_action',
                    name : 'Prices',
                    actionName : 'pricesData',
                    // sortable: true,
                    // prefix: '$',
                    width : 100,
                    isHidden : !this.user.isAdmin || !this.tblBuyOptions.prices
                },
                {
                    prop : '_action',
                    name : 'Status',
                    actionName : 'statusData',
                    width : 100,
                    isHidden : !this.tblBuyOptions.status
                },
                {
                    prop : 'code_comb',
                    name : 'Code Comb',
                    // sortable : true,
                    width : 125,
                    isHidden : !this.tblBuyOptions.code_comb
                },
                {
                    prop : 'code_price',
                    name : 'Code Price',
                    // sortable : true,
                    prefix : '$ ',
                    width : 120,
                    isHidden : !this.tblBuyOptions.code_price
                },
                {
                    prop : 'price_basis',
                    name : 'Price Basis',
                    // sortable : true,
                    width : 120,
                    isHidden :
                        !this.isExtBuyerWithCommission || !this.tblBuyOptions.price_basis
                },
                {
                    prop : '_action',
                    name : 'Action',
                    actionName : 'actionButtons',
                    width : '200',
                    isHidden : false
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
    },

    methods : {
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
            if (this.user.role_id == 5) {
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
            await
                this.$store.dispatch('getGeneratorLogs');

            if (this.isGeneratorOn) {
                this.generatorLoader = this.$loading.show({
                    container : this.$refs.buyerBody
                }, {
                    default :
                        'We are generating the URLs\' best prices, please wait...'
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
                    language_id : this.filterModel.language_id,
                    status_purchase : this.filterModel.status_purchase,
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
            loader.hide();
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
                for (var buy in this.listBuy.data) {
                    this.checkIds.push(this.listBuy.data[buy].id);
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
            let ids = this.checkIds;
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
        },

        interestedSelected() {
            this.doLike(this.checkIds)
            this.checkIds = [];
        },

        notInterestedSelected() {
            this.doDislike(this.checkIds)
            this.checkIds = [];
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
                    language_id : this.filterModel.language_id,
                    status_purchase : this.filterModel.status_purchase,
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
            this.clearMessageform();
            let that = JSON.parse(JSON.stringify(buy))

            this.updateModel = that
            this.updateModel.seller_price = that.price;
            this.updateModel.price = this.computePrice(that.price, that.inc_article);
            this.updateModel.prices = this.updateModel.price

            $('#modal-buy-update').modal({
                show : true
            });
        },

        async doDislike(id) {
            if (confirm("Are you sure you're Not Interested in these backlink?")) {
                $('#tbl_buy_backlink').DataTable().destroy();

                this.searchLoading = true;
                await this.$store.dispatch('actionDislike', {id : id})
                this.searchLoading = false;

                this.getBuyList();
            }
        },

        async doLike(id) {
            $('#tbl_buy_backlink').DataTable().destroy();

            this.searchLoading = true;
            await this.$store.dispatch('actionLike', {id : id})
            this.searchLoading = false;

            this.getBuyList();
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

        computePrice(price, article) {

            let activeUser = this.user
            let selling_price = price
            let percent = parseFloat(this.formula.data[0].percentage);
            let additional = parseFloat(this.formula.data[0].additional);

            if (activeUser.user_type) { //check if has user_type value

                let type = activeUser.user_type.type
                let commission = (activeUser.user_type.commission).toLowerCase()

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

        async submitBuy(params) {
            let credit_left = parseInt(this.listBuy.credit);
            $('#tbl_buy_backlink').DataTable().destroy();

            /*if (this.user.isOurs == 1 && this.user.role_id == 5 && (this.user.registration != null && this.user.registration.is_sub_account == 1)) { // check if sub buyer account
                if (credit_left <= 0) { // check if credit is 0
                    swal.fire('error', 'No credits left', 'error');
                    return false;
                }
            }*/

            this.updateModel.credit_left = credit_left;

            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateBuy', this.updateModel);
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
        }
    },
}
</script>
