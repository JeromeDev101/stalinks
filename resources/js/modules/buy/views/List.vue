<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Search URL</label>
                                <input type="text" class="form-control" v-model="filterModel.search" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status of Purchased</label>
                                <select name="" v-model="filterModel.status_purchase" class="form-control">
                                    <option value="">All</option>
                                    <option value="New">New</option>
                                    <option value="Interested">Interested</option>
                                    <option value="Not interested">Not interested</option>
                                    <option value="Purchased">Purchased</option>
                                </select>
                                <!-- <v-select multiple v-model="filterModel.status_purchase" :options="['New', 'Interested', 'Not interested', 'Purchased']" id="custom" ></v-select> -->
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Language</label>
                                <select name="" class="form-control" v-model="filterModel.language_id">
                                    <option value="">All</option>
                                    <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Country</label>
                                <select class="form-control" v-model="filterModel.country_id">
                                    <option value="">All</option>
                                    <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                        {{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 7) || (user.isOurs == 0 && user.role_id == 5)">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select name="" class="form-control" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="option in listSeller.data" v-bind:value="option.id">
                                        {{ option.username == null ? option.name:option.username }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 7) || (user.isOurs == 0 && user.role_id == 5) ">
                            <div class="form-group">
                                <label for="">Code</label>
                                <select name="" class="form-control" v-model="filterModel.code">
                                    <option value="">All</option>
                                    <option v-for="option in listCode" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>
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
                                 <v-select multiple v-model="filterModel.topic" :options="topic" :searchable="false" placeholder="All"/>
                            </div>
                        </div>

                        <div class="col-md-2">
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

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearching">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearching">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Buy Backlinks</h3>
                    <span class="ml-5 text-primary" v-show="user.role_id == 5">Credit Left: <b>${{listBuy.credit}}</b></span>

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="getBuyList" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <button data-toggle="modal" data-target="#modal-setting" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>

                    <div v-if="isCreditAuth" class="alert alert-warning my-3">
                        Sorry you cannot Purchase backlinks due to lack of Wallet. Click
                        <button class="btn btn-link" @click="checkCreditAuth">
                            Retry
                        </button> if you have given permission to purchased
                    </div>

                    <div class="col-md-2 my-3">
                        <div class="input-group">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" :disabled="isDisabled" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                </div>

                <div class="box-body no-padding relative">

                    <span v-if="listBuy.total > 10" class="pagination-custom-footer-text">
                        <b>Showing {{ listBuy.from }} to {{ listBuy.to }} of {{ listBuy.total }} entries.</b>
                    </span>

                    <table id="tbl_buy_backlink" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>
                                    <input type="checkbox" @click="selectAll" v-model="allSelected">
                                </th>
                                <th class="resize" v-show="tblBuyOptions.seller" v-if="user.role_id != 5 && user.isOurs != 1">Seller</th>
                                <th class="resize" v-show="tblBuyOptions.topic">Topic</th>
                                <th class="resize" v-show="tblBuyOptions.casino_sites">Casino & Betting Sites</th>
                                <th class="resize" v-show="tblBuyOptions.language">Language</th>
                                <th class="resize" v-show="tblBuyOptions.country">Country</th>
                                <th v-show="tblBuyOptions.url">URL</th>
                                <th class="resize" v-show="tblBuyOptions.ur">UR</th>
                                <th class="resize" v-show="tblBuyOptions.dr">DR</th>
                                <th class="resize" v-show="tblBuyOptions.backlinks">Blinks</th>
                                <th class="resize" v-show="tblBuyOptions.ref_domains">Ref Domain</th>
                                <th class="resize" v-show="tblBuyOptions.org_keywords">Org Kw</th>
                                <th class="resize" v-show="tblBuyOptions.ratio">Ratio</th>
                                <th class="resize" v-show="tblBuyOptions.org_traffic">Org Traffic</th>
                                <th class="resize" v-show="tblBuyOptions.price">{{ user.role_id == 5 ? 'Prices':'Price' }}</th>
                                <th class="resize" v-show="tblBuyOptions.prices">Prices</th>
                                <th class="resize" v-show="tblBuyOptions.status">Status</th>
                                <th class="resize" v-show="tblBuyOptions.code_comb">Code Comb</th>
                                <th class="resize" v-show="tblBuyOptions.code_price">Code Price</th>
                                <th class="resize" v-show="tblBuyOptions.price_basis" v-if="isExtBuyerWithCommission">Price Basis</th>
                                <th>Buy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(buy, index) in listBuy.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default">
                                            <input type="checkbox" v-on:change="checkSelected" :id="buy.id" :value="buy.id" v-model="checkIds">
                                        </button>
                                    </div>
                                </td>
                                <td class="resize" v-show="tblBuyOptions.seller" v-if="user.role_id != 5 && user.isOurs != 1" >{{ buy.username ? buy.username : buy.user_name}}</td>
                                <td class="resize" v-show="tblBuyOptions.topic">{{ buy.topic == null ? 'N/A':buy.topic }}</td>
                                <td class="resize" v-show="tblBuyOptions.casino_sites">{{ buy.casino_sites == null ? 'N/A':buy.casino_sites }}</td>
                                <td class="resize" v-show="tblBuyOptions.language">{{ buy.language_name }}</td>
                                <td class="resize" v-show="tblBuyOptions.country">{{ buy.country_name }}</td>
                                <td v-show="tblBuyOptions.url">{{ replaceCharacters(buy.url) }}</td>
                                <td class="resize" v-show="tblBuyOptions.ur">{{ buy.ur }}</td>
                                <td class="resize" v-show="tblBuyOptions.dr">{{ buy.dr }}</td>
                                <td class="resize" v-show="tblBuyOptions.backlinks">{{ buy.backlinks }}</td>
                                <td class="resize" v-show="tblBuyOptions.ref_domains">{{ buy.ref_domain }}</td>
                                <td class="resize" v-show="tblBuyOptions.org_keywords">{{ formatPrice(buy.org_keywords) }}</td>
                                <td class="resize" v-show="tblBuyOptions.ratio">{{ getRatio(buy.org_keywords, buy.org_traffic) }}</td>
                                <td class="resize" v-show="tblBuyOptions.org_traffic">{{ formatPrice(buy.org_traffic) }}</td>
                                <td class="resize" v-show="tblBuyOptions.price">{{ buy.price == '' || buy.price == null ? '':'$'}} {{ computePrice(buy.price, buy.inc_article) }}</td>
                                <td class="resize" v-show="tblBuyOptions.prices">{{ buy.price == '' || buy.price == null ? '':'$'}} {{ computePriceStalinks(buy.price, buy.inc_article) }}</td>
                                <td class="resize" v-show="tblBuyOptions.status">{{ buy.status_purchased == null ? 'New':buy.status_purchased}}</td>
                                <td class="resize text-center font-weight-bold" v-show="tblBuyOptions.code_comb" >{{ buy.code_combination}}</td>
                                <td class="resize" v-show="tblBuyOptions.code_price" > $ {{ buy.code_price}}</td>
                                <td class="resize" v-show="tblBuyOptions.price_basis" v-if="isExtBuyerWithCommission">{{ buy.price_basis }}</td>
                                <td>
                                    <div class="btn-group" ref="text">
                                        <button v-if="buy.price != '' && buy.price != null" :disabled="isCreditAuth" title="Buy" data-target="#modal-buy-update" @click="doUpdate(buy)" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-dollar"></i></button>
                                        <button :disabled="buy.status_purchased == 'Interested'" @click="doLike(buy.id)" title="Interested" class="btn btn-default"><i class="fa fa-fw fa-thumbs-up"></i></button>
                                        <button :disabled="buy.status_purchased == 'Not interested'" @click="doDislike(buy.id)" title="Not Interested" class="btn btn-default"><i class="fa fa-fw fa-thumbs-down"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- <pagination :data="listBuy" @pagination-change-page="getBuyList" :limit="8"></pagination> -->
                </div>
            </div>

        </div>

        <!-- Modal Buy -->
        <div class="modal fade" id="modal-buy-update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buy Backlink</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" class="form-control" v-model="updateModel.url" name="" aria-describedby="helpId" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" class="form-control" v-model="updateModel.price" name="" aria-describedby="helpId" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Anchor Text</label>
                                    <input type="text" class="form-control" v-model="updateModel.anchor_text" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Link To</label>
                                    <input type="text" class="form-control" v-model="updateModel.link" name="" aria-describedby="helpId" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL Advertiser</label>
                                    <input type="text" class="form-control" v-model="updateModel.url_advertiser" name="" aria-describedby="helpId" placeholder="">
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
        <div class="modal fade" id="modal-buy-selected" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Buy Selected Backlink</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-for="(buy, index) in buyData" :key="index">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">URL</label>
                                    <input type="text" class="form-control" :value="buy.url" aria-describedby="helpId" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="number" class="form-control" :value="buy.price" aria-describedby="helpId" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="col-md-2 my-auto">
                                <button type="button" class="btn btn-link" data-toggle="collapse" :data-target="'#collapseExample'+index">View more</button>
                            </div>
                            <div class="col-md-12">
                                <div class="collapse" :id="'collapseExample'+index">
                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Anchor Text</label>
                                                    <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Link To</label>
                                                    <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">URL Advertiser</label>
                                                    <input type="text" class="form-control" name="" aria-describedby="helpId" placeholder="">
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
        <div class="modal fade" id="modal-setting" >
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6" v-if="user.role_id != 5 && user.isOurs != 1">
                                <label><input type="checkbox" :checked="tblBuyOptions.seller ? 'checked':''" v-model="tblBuyOptions.seller">Seller</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.topic ? 'checked':''" v-model="tblBuyOptions.topic">Topic</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.casino_sites ? 'checked':''" v-model="tblBuyOptions.casino_sites">Casino & Betting Sites</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.language ? 'checked':''" v-model="tblBuyOptions.language">Language</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.country ? 'checked':''" v-model="tblBuyOptions.country">Country</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.url ? 'checked':''" v-model="tblBuyOptions.url">URL</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.ur ? 'checked':''" v-model="tblBuyOptions.ur">UR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.dr ? 'checked':''" v-model="tblBuyOptions.dr">DR</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.backlinks ? 'checked':''" v-model="tblBuyOptions.backlinks">Backlinks</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.ref_domains ? 'checked':''" v-model="tblBuyOptions.ref_domains">Ref Domains</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.org_keywords ? 'checked':''" v-model="tblBuyOptions.org_keywords">Organic Keywords</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.ratio ? 'checked':''" v-model="tblBuyOptions.ratio">Ratio</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.org_traffic ? 'checked':''" v-model="tblBuyOptions.org_traffic">Organic Traffic</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.price ? 'checked':''" v-model="tblBuyOptions.price">{{ user.role_id == 5 ? 'Price_S':'Price' }}</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.prices ? 'checked':''" v-model="tblBuyOptions.prices">Prices</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblBuyOptions.status ? 'checked':''" v-model="tblBuyOptions.status">Status</label>
                            </div>
                            <div class="checkbox col-md-6" v-show="user.role_id != 5">
                                <label><input type="checkbox" :checked="tblBuyOptions.code_comb ? 'checked':''" v-model="tblBuyOptions.code_comb">Code Combination</label>
                            </div>
                            <div class="checkbox col-md-6" v-show="user.role_id != 5">
                                <label><input type="checkbox" :checked="tblBuyOptions.code_price ? 'checked':''" v-model="tblBuyOptions.code_price">Code Price</label>
                            </div>
                            <div class="checkbox col-md-6" v-if="isExtBuyerWithCommission">
                                <label><input type="checkbox" :checked="tblBuyOptions.code_price ? 'checked':''" v-model="tblBuyOptions.price_basis">Price Basis</label>
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
        <!-- End of Modal Settings -->

    </div>
</template>

<style>
    #custom .vs__dropdown-toggle
    {
        min-height: 37px;
    }
    #tbl_buy_backlink {
        table-layout: fixed;
        width: 100% !important;
    }
    #tbl_buy_backlink .resize{
        width: auto !important;
        white-space: normal;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                paginate: [50,150,250,350,500,'All'],
                updateModel: {
                    id: '',
                    url: '',
                    price: '',
                    anchor_text: '',
                    link: '',
                    url_advertiser: '',
                },
                isPopupLoading: false,
                filterModel: {
                    country_id: this.$route.query.country_id || '',
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    status_purchase: this.$route.query.status_purchase || '',
                    seller: this.$route.query.seller || '',
                    code: this.$route.query.code || '',
                    casino_sites: this.$route.query.casino_sites || '',
                    topic: this.$route.query.topic || '',
                    price_basis: this.$route.query.price_basis || '',
                    paginate: this.$route.query.paginate || 50,
                },
                searchLoading: false,
                dataTable: null,
                isCreditAuth: false,
                listCode: ['4A', '3A', '2A', '1A', '0A'],
                checkIds: [],
                isDisabled: true,
                allSelected: false,
                buyData: [],
                isSearching: false,
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

                isExtBuyerWithCommission: true,
                updateFormula: {},
            }
        },

        computed: {
            ...mapState({
                tblBuyOptions: state => state.storeBuy.tblBuyOptions,
                listBuy: state => state.storeBuy.listBuy,
                listCountryAll: state => state.storePublisher.listCountryAll,
                messageForms: state => state.storeBuy.messageForms,
                user: state => state.storeAuth.currentUser,
                listSeller: state => state.storeBuy.listSeller,
                formula: state => state.storeSystem.formula,
                listLanguages: state => state.storePublisher.listLanguages,
            }),
        },

        async created() {
            this.getFormula();
            this.getBuyList();
            this.getListCountries();
            this.checkCreditAuth();
            this.getListSeller();
            this.columnShow();
            // this.checkBuyerCommission();

            let language = this.listLanguages.data;
            if ( language.length === 0 ) {
                this.getListLanguages();
            }
        },

        methods: {
            getRatio(org_kw, org_traffic) {
                let result = 0;

                if(org_traffic != 0){
                    result = parseFloat(org_kw)/parseFloat(org_traffic)
                }
                
                return result.toFixed(3);
            },

            async getListLanguages() {
                await this.$store.dispatch('actionGetListLanguages');
            },

            checkBuyerCommission() {
                let email = this.user.email;
                this.getBuyerCommission(email).then(res => {
                    let commission = res.data.commission;
                    if (commission == 'yes'){
                        this.isExtBuyerWithCommission = true;
                    }
                })
            },

            async getFormula() {
                await this.$store.dispatch('actionGetFormula');
                this.updateFormula = this.formula.data[0];
            },

            async getBuyerCommission(email) {
                try {
                    const response = await axios.get('api/buyer-commission', {
                                            params: {
                                                email: email,
                                            }
                                        });
                    return response;
                } catch (error) {
                    console.error(error);
                }
            },

            columnShow() {
                if (this.user.role_id == 5){
                    this.tblBuyOptions.casino_sites = false;
                    this.tblBuyOptions.seller = false;
                    this.tblBuyOptions.code_comb = false;
                    this.tblBuyOptions.code_price = false;
                }
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
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

                $('#tbl_buy_backlink').DataTable().destroy();

                this.searchLoading = true;
                this.isSearching = true;
                await this.$store.dispatch('actionGetBuyList', {
                    params: {
                        country_id: this.filterModel.country_id,
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        status_purchase: this.filterModel.status_purchase,
                        seller: this.filterModel.seller,
                        code: this.filterModel.code,
                        paginate: this.filterModel.paginate,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        price_basis: this.filterModel.price_basis,
                        page: page,
                    }
                });


                var columnsOrder = [];
                
                if(this.user.isOurs == 0 || this.user.isAdmin) {
                    columnsOrder = [
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
                        { orderable: true, targets: 19 },
                        { orderable: true, targets: 20 },
                        { orderable: false, targets: '_all' }
                    ];
                } else {
                    columnsOrder = [
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
                        { orderable: false, targets: '_all' }
                    ];
                }

                var table = $('#tbl_buy_backlink').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnsOrder,
                    autoWidth: true,
                });

                table.columns.adjust().draw();


                this.searchLoading = false;
                this.isSearching = false;
            },

            async getListSeller(params) {
                await this.$store.dispatch('actionGetListSeller', params);
            }, 

            checkSelected() {
                this.isDisabled = true;
                if( this.checkIds.length > 0 ){
                    this.isDisabled = false;
                }
            },

            select: function() {
                this.allSelected = false;
            },

            selectAll: function() {
                this.checkIds = [];
                if (!this.allSelected) {
                    for (var buy in this.listBuy.data) {
                        this.checkIds.push(this.listBuy.data[buy].id);
                    }
                    this.isDisabled = false;
                } else {
                    this.isDisabled = true;
                }
            },

            clearSearch() {
                $('#tbl_buy_backlink').DataTable().destroy();

                this.filterModel = {
                    country_id: '',
                    search: '',
                    language_id: '',
                    status_purchase: '',
                    seller: '',
                    code: '',
                    casino_sites: '',
                    topic: '',
                    price_basis: '',
                    paginate: 50,
                }

                this.getBuyList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});

            },

            buySelected() {
                let ids = this.checkIds;
                this.buyData = [];
                for( var id in ids){
                    for (var buy in this.listBuy.data) {
                        if( this.listBuy.data[buy].id == ids[id] ){
                            this.buyData.push({
                                "id": this.listBuy.data[buy].id,
                                "url": this.listBuy.data[buy].url,
                                "price": this.listBuy.data[buy].price,
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

                this.$router.replace({'query': null});

                this.$router.push({
                    query: this.filterModel,
                });

                this.getBuyList({
                    params: {
                        country_id: this.filterModel.country_id,
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        status_purchase: this.filterModel.status_purchase,
                        seller: this.filterModel.seller,
                        code: this.filterModel.code,
                        paginate: this.filterModel.paginate,
                        casino_sites: this.filterModel.casino_sites,
                        topic: this.filterModel.topic,
                        price_basis: this.filterModel.price_basis,
                    }
                });
            },

            doUpdate(buy) {
                this.clearMessageform();
                let that = JSON.parse(JSON.stringify(buy))

                this.updateModel = that
                this.updateModel.price = this.computePrice(that.price, that.inc_article);
            },

            async doDislike(id) {
                if( confirm("Are you sure you're Not Interested in these backlink?") ){
                    $('#tbl_buy_backlink').DataTable().destroy();

                    this.searchLoading = true;
                    await this.$store.dispatch('actionDislike', { id:id })
                    this.searchLoading = false;

                    this.getBuyList();
                }
            },

            async doLike(id) {
                $('#tbl_buy_backlink').DataTable().destroy();

                this.searchLoading = true;
                await this.$store.dispatch('actionLike', { id:id })
                this.searchLoading = false;

                this.getBuyList();
            },

            computePriceStalinks(price, article) {

                let activeUser = this.user
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

                selling_price = selling_price.toFixed(0);

                return selling_price;
            },



            computePrice(price, article) {

                let activeUser = this.user
                let selling_price = price
                let percent = parseFloat(this.formula.data[0].percentage);
                let additional = parseFloat(this.formula.data[0].additional);

                if( activeUser.user_type ){ //check if has user_type value

                    let type = activeUser.user_type.type
                    let commission = activeUser.user_type.commission

                    if( price != '' && price != null ){ // check if price has a value

                        if( type == 'Buyer' ){ // check if the user_type is a 'Buyer'

                            if( article == 'Yes' ){ //check if with article

                                if( commission == 'no' ){
                                    selling_price = price
                                }

                                if( commission == 'yes' ){
                                    let percentage = this.percentage(percent, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price)
                                }
                            }

                            if( article == 'No' ){ //check if without article

                                if( commission == 'no' ){
                                    selling_price = parseFloat(price) + additional
                                }

                                if( commission == 'yes' ){
                                    let percentage = this.percentage(percent, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price) + additional
                                }

                            }
                        }

                    }
                }

                return selling_price;
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            percentage(percent, total) {
                return ((percent/ 100) * total).toFixed(2)
            },

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async submitBuy(params) {
                let credit_left = parseInt(this.listBuy.credit);
                $('#tbl_buy_backlink').DataTable().destroy();


                if (this.user.isOurs == 1 && this.user.role_id == 5 && (this.user.registration != null && this.user.registration.is_sub_account == 1) ) { // check if sub buyer account
                    if (credit_left <= 0 ) { // check if credit is 0
                        swal.fire('error', 'No credits left', 'error');
                        return false;
                    }
                } 

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
        },
    }
</script>