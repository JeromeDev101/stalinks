<template>
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filter</h3>
                    <button class="btn btn-primary ml-5" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-plus"></i> Show Filter
                    </button>
                </div>

                <div class="box-body m-3 collapse" id="collapseExample">

<!--                    {{ this.$route. }}-->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Search ID Backlink</label>
                                <input type="text" class="form-control" v-model="filterModel.search_id" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-4" v-if="user.isOurs != 1">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select class="form-control" name="" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="seller in listPurchase.sellers" v-bind:value="seller.user_id">{{ seller.username }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4" v-if="user.isAdmin || user.user_type.is_sub_account != 1">
                            <div class="form-group">
                                <label for="">Buyer</label>
                                <select class="form-control" name="" v-model="filterModel.buyer">
                                    <option value="">All</option>
                                    <option v-for="buyer in listPurchase.buyers" v-bind:value="buyer.user_id_buyer">{{ buyer.username }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Search URL Publisher</label>
                                <input type="text" class="form-control" v-model="filterModel.search_url_publisher" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Payment Status</label>
                                <select name="" id="" class="form-control" v-model="filterModel.payment_status">
                                    <option value="">All</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Not paid">Not paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date Completed</label>
                                <div class="input-group">
                                    <date-range-picker
                                        v-model="filterModel.date_completed"
                                        :linkedCalendars="true"
                                        :dateRange="filterModel.date_completed"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        ref="picker"
                                        opens="left"
                                        style="width: 100%"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearchingLoading">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearchingLoading">Search <i v-if="isSearching" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Purchase</h3>
                    <span class="ml-5 text-primary" v-show="user.role_id == 5">Wallet: <b>${{ (typeof listPurchase.wallet !== 'undefined') ? listPurchase.wallet:0 }}</b></span>
                    <span class="ml-5 text-primary" v-show="user.role_id == 5">Deposit: <b>${{ (typeof listPurchase.deposit !== 'undefined') ? listPurchase.deposit:0 }}</b></span>

                    <h5 class="d-inline pull-right">Amount: $ {{ totalAmount }}</h5>

                    <table width="100%">
                        <tr>
                            <td>
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select name="" class="form-control float-right" @change="getPurchaseList" v-model="filterModel.paginate" style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>

                <div class="box-body no-padding">

                    <span v-if="listPurchase.total > 10" class="pagination-custom-footer-text">
                        <b>Showing {{ listPurchase.from }} to {{ listPurchase.to }} of {{ listPurchase.total }} entries.</b>
                    </span>

                    <table id="tbl-purchase" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Backlink ID</th>
                                <th v-show="user.isAdmin || user.isOurs === 0">User Seller</th>
                                <th>User Buyer</th>
                                <th>URL Publisher</th>
                                <th>Prices</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchase, index) in listPurchase.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ purchase.id }}</td>
                                <td v-show="user.isAdmin || user.isOurs === 0">{{ purchase.publisher == null ? 'Record Deleted':purchase.publisher.user.username == null ? purchase.publisher.user.name : purchase.publisher.user.username}}</td>
                                <td>{{ purchase.user.username == null ? purchase.user.name : purchase.user.username }}</td>
                                <td>
<!--                                    {{ purchase.publisher == null ? 'Record Deleted':replaceCharacters(purchase.publisher.url) }}-->
                                    <span v-if="purchase.publisher == null">
                                        Record Deleted
                                    </span>
                                    <span v-else>
                                        <a :href="'//' + replaceCharacters(purchase.publisher.url)" target="_blank">
                                            {{ replaceCharacters(purchase.publisher.url) }}
                                        </a>
                                    </span>
                                </td>
                                <td>$ {{ formatPrice(purchase.prices) }}</td>
                                <td>{{ purchase.live_date }}</td>
                                <td>{{ purchase.status }}</td>
                                <td>{{ purchase.payment_status }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <!-- Modal Update -->
        <div class="modal fade" id="modal-update-purchase" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Purchase Information</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">User Seller</label>
                                    <input type="text" v-model="updateModel.seller" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">User Buyer</label>
                                    <input type="text" v-model="updateModel.buyer" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="text" v-model="updateModel.price" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Payment Status</label>
                                    <select name="" v-model="updateModel.payment_status" class="form-control">
                                        <option value="Paid">Paid</option>
                                        <option value="Not Paid">Not Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                paginate: [50,150,250,350,500, 'All'],
                updateModel: {
                    seller: '',
                    buyer: '',
                    price: '',
                    payment_status: '',
                },
                filterModel: {
                    user: this.$route.query.user || '',
                    payment_status: this.$route.query.payment_status || '',
                    buyer: this.$route.query.buyer || '',
                    seller: this.$route.query.seller || '',
                    search_id: this.$route.query.search_id || '',
                    search_url_publisher: this.$route.query.search_url_publisher || '',
                    paginate: this.$route.query.paginate || '50',
                    date_completed: {
                        startDate: null,
                        endDate: null
                    }
                },
                isPopupLoading: false,
                totalAmount: 0,
                isSearching: false,
                isSearchingLoading: false,
            }
        },

        async created() {
            this.getPurchaseList()
        },

        computed: {
             ...mapState({
                listPurchase: state => state.storePurchase.listPurchase,
                messageForms: state => state.storePurchase.messageForms,
                user: state => state.storeAuth.currentUser,
            })
        },

        methods: {
            async getPurchaseList(params){
                $('#tbl-purchase').DataTable().destroy();

                this.isSearching = true;
                this.isSearchingLoading = true;

                await this.$store.dispatch('actionGetPurchaseList', {
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                        buyer: this.filterModel.buyer,
                        seller: this.filterModel.seller,
                        search_id: this.filterModel.search_id,
                        search_url_publisher: this.filterModel.search_url_publisher,
                        paginate: this.filterModel.paginate,
                        date_completed: this.filterModel.date_completed
                    }
                });

                $('#tbl-purchase').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: [
                        { orderable: true, targets: 0},
                        { orderable: true, targets: 1},
                        { orderable: true, targets: 2},
                        { orderable: true, targets: 3},
                        { orderable: true, targets: 4},
                        { orderable: true, targets: 5},
                        { orderable: true, targets: 6},
                        { orderable: true, targets: 7},
                        { orderable: true, targets: 8},
                        { orderable: false, targets: '_all' }
                    ],
                });

                this.isSearching = false;
                this.isSearchingLoading = false;

                this.getTotalAmount();
            },

            clearSearch() {
                $('#tbl-purchase').DataTable().destroy();

                this.filterModel = {
                    user: '',
                    payment_status: '',
                    buyer: '',
                    seller: '',
                    search_id: '',
                    search_url_publisher: '',
                    paginate: '50',
                    date_completed: {
                        startDate: null,
                        endDate: null
                    }
                }

                this.getPurchaseList({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            doSearch() {
                $('#tbl-purchase').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getPurchaseList({
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                        buyer: this.filterModel.buyer,
                        seller: this.filterModel.seller,
                        search_id: this.filterModel.search_id,
                        search_url_publisher: this.filterModel.search_url_publisher,
                        paginate: this.filterModel.paginate,
                        date_completed: this.filterModel.date_completed
                    }
                });
            },

            formatPrice(value) {
                let val = (value/1).toFixed(0)
                return val;
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let self = this;
                let incomes = this.listPurchase.data
                let total_price = [];
                let total = 0;
                incomes.forEach(function(item, index){
                    if( item.prices != null && item.prices != '' ) {
                        total_price.push( parseFloat(self.formatPrice(item.prices)))
                    }
                })

                console.log(total_price)

                if( total_price.length > 0 ){
                    total = total_price.reduce(this.calcSum)
                }
                this.totalAmount = total.toFixed(2);
            },

            calcSum(total, num) {
                return total + num
            },

            doUpdate(purchase) {
                let that = JSON.parse( JSON.stringify(purchase) )

                this.updateModel = that
                this.updateModel.seller = that.publisher.user.name
                this.updateModel.buyer = that.user.name
            },
        }
    }
</script>
