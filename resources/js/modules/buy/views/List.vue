<template>
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                </div>

                <div class="box-body m-3">

                    <div class="row">
                        <div class="col-md-3">
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
                                    <option v-for="option in listCountries.data" v-bind:value="option.id">
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

                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="getBuyList" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

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
                                    Select
                                </th>
                                <!-- <th v-if="user.isAdmin">Company</th> -->
                                <th>Username</th>
                                <th>Language</th>
                                <th>URL</th>
                                <th>UR</th>
                                <th>DR</th>
                                <th>Backlinks</th>
                                <th>Ref Domains</th>
                                <th>Organic Keywords</th>
                                <th>Organic Traffic</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th v-if="user.isOurs == 0">Code Combination</th>
                                <th v-if="user.isOurs == 0">Code Price</th>
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
                                <!-- <td v-if="user.isAdmin">{{ buy.isOurs == '0' ? 'Stalinks':buy.company_name}}</td> -->
                                <td>{{ buy.username ? buy.username : buy.user_name}}</td>
                                <td>{{ buy.country_name }}</td>
                                <td>{{ replaceCharacters(buy.url) }}</td>
                                <td>{{ buy.ur }}</td>
                                <td>{{ buy.dr }}</td>
                                <td>{{ buy.backlinks }}</td>
                                <td>{{ buy.ref_domain }}</td>
                                <td>{{ formatPrice(buy.org_keywords) }}</td>
                                <td>{{ formatPrice(buy.org_traffic) }}</td>
                                <td>{{ buy.price == '' || buy.price == null ? '':'$'}} {{ computePrice(buy.price, buy.inc_article) }}</td>
                                <td>{{ buy.status_purchased == null ? 'New':buy.status_purchased}}</td>
                                <td v-if="user.isOurs== 0" class="text-center font-weight-bold">{{ buy.code_combination}}</td>
                                <td v-if="user.isOurs== 0"> $ {{ buy.code_price}}</td>
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

    </div>
</template>

<style>
    #custom .vs__dropdown-toggle
    {
        min-height: 37px;
    }
</style>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                paginate: [25,50,100,200,250, 'All'],
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
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    status_purchase: this.$route.query.status_purchase || '',
                    seller: this.$route.query.seller || '',
                    code: this.$route.query.code || '',
                    paginate: this.$route.query.paginate || 25,
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
            }
        },

        computed: {
            ...mapState({
                listBuy: state => state.storeBuy.listBuy,
                listCountries: state => state.storeBuy.listCountries,
                messageForms: state => state.storeBuy.messageForms,
                user: state => state.storeAuth.currentUser,
                listSeller: state => state.storeBuy.listSeller,
            }),
        },

        async created() {
            this.getBuyList();
            this.getListCountries();
            this.checkCreditAuth();
            this.getListSeller();
        },

        methods: {
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
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        status_purchase: this.filterModel.status_purchase,
                        seller: this.filterModel.seller,
                        code: this.filterModel.code,
                        paginate: this.filterModel.paginate,
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
                        { orderable: false, targets: '_all' }
                    ];
                }

                $('#tbl_buy_backlink').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnsOrder,
                });


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
                }
            },

            clearSearch() {
                $('#tbl_buy_backlink').DataTable().destroy();

                this.filterModel = {
                    search: '',
                    language_id: '',
                    status_purchase: '',
                    seller: '',
                    code: '',
                    paginate: 25,
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
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        status_purchase: this.filterModel.status_purchase,
                        seller: this.filterModel.seller,
                        code: this.filterModel.code,
                        paginate: this.filterModel.paginate,
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

            computePrice(price, article) {

                let activeUser = this.user
                let selling_price = price

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
                                    let percentage = this.percentage(10, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price)
                                }
                            }

                            if( article == 'No' ){ //check if without article

                                if( commission == 'no' ){
                                    selling_price = parseFloat(price) + 10
                                }

                                if( commission == 'yes' ){
                                    let percentage = this.percentage(10, price)
                                    selling_price = parseFloat(percentage) + parseFloat(price) + 10
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
                $('#tbl_buy_backlink').DataTable().destroy();

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