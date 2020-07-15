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
                                <label for="">Search Company and User</label>
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

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-if="searchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Buy Backlinks</h3>

                    <div v-if="isCreditAuth" class="alert alert-warning my-3">
                        Sorry you cannot Purchase backlinks due to lack of Wallet. Click 
                        <button class="btn btn-link" @click="checkCreditAuth">
                            Retry
                        </button> if you have given permission to purchased
                    </div>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table id="tbl_buy_backlink" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th v-if="user.isAdmin">Company</th>
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
                                <th>Buy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(buy, index) in listBuy.data" :key="index">
                                <td>{{ index + 1}}</td>
                                <td v-if="user.isAdmin">{{ buy.isOurs == '0' ? 'Stalinks':buy.company_name}}</td>
                                <td>{{ buy.username ? buy.username : buy.user_name}}</td>
                                <td>{{ buy.country_name }}</td>
                                <td>{{ buy.url }}</td>
                                <td>{{ buy.ur }}</td>
                                <td>{{ buy.dr }}</td>
                                <td>{{ buy.backlinks }}</td>
                                <td>{{ buy.ref_domain }}</td>
                                <td>{{ formatPrice(buy.org_keywords) }}</td>
                                <td>{{ formatPrice(buy.org_traffic) }}</td>
                                <td>{{ buy.price == '' || buy.price == null ? '':'$'}} {{ computePrice(buy.price, buy.inc_article) }}</td>
                                <td>{{ buy.status_purchased == null ? 'New':buy.status_purchased}}</td>
                                <td>
                                    <div class="btn-group" ref="text">
                                        <button :disabled="isCreditAuth" title="Buy" data-target="#modal-buy-update" @click="doUpdate(buy)" data-toggle="modal" class="btn btn-default"><i class="fa fa-fw fa-dollar"></i></button>
                                        <button :disabled="buy.status_purchased == 'Interested'" @click="doLike(buy.id)" title="Interested" class="btn btn-default"><i class="fa fa-fw fa-thumbs-up"></i></button>
                                        <button :disabled="buy.status_purchased == 'Not interested'" @click="doDislike(buy.id)" title="Not Interested" class="btn btn-default"><i class="fa fa-fw fa-thumbs-down"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal Update -->
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
                                    <label for="">Link</label>
                                    <input type="text" class="form-control" v-model="updateModel.link" name="" aria-describedby="helpId" placeholder="">
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
                updateModel: {
                    id: '',
                    url: '',
                    price: '',
                    anchor_text: '',
                    link: '',
                },
                isPopupLoading: false,
                filterModel: {
                    search: this.$route.query.search || '',
                    language_id: this.$route.query.language_id || '',
                    status_purchase: this.$route.query.status_purchase || '',
                },
                searchLoading: false,
                dataTable: null,
                isCreditAuth: false,
            }
        },

        computed: {
            ...mapState({
                listBuy: state => state.storeBuy.listBuy,
                listCountries: state => state.storeBuy.listCountries,
                messageForms: state => state.storeBuy.messageForms,
                user: state => state.storeAuth.currentUser,
            }),
        },

        async created() {
            this.getBuyList();
            this.getListCountries();
            this.checkCreditAuth();
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

            async getBuyList(params) {
                this.searchLoading = true;
                await this.$store.dispatch('actionGetBuyList', {
                    params: {
                        search: this.filterModel.search,
                        language_id: this.filterModel.language_id,
                        status_purchase: this.filterModel.status_purchase,
                    }
                });

                $('#tbl_buy_backlink').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: false, targets: '_all' }
                    ],
                });

                this.searchLoading = false;
            },

            clearSearch() {
                $('#tbl_buy_backlink').DataTable().destroy();

                this.filterModel = {
                    search: '',
                    language_id: '',
                    status_purchase: '',
                }

                this.getBuyList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});

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