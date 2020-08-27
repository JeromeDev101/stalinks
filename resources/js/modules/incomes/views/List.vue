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
                                <label for="">Status Payment</label>
                                <select name="" id="" class="form-control" v-model="filterModel.payment_status">
                                    <option value="">All</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Not paid">Not paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="" id="" class="form-control" v-model="filterModel.status">
                                    <option value="" selected>All</option>
                                    <option value="Live">Live</option>
                                    <option value="Processing">Processing</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin">
                            <div class="form-group">
                                <label for="">Seller</label>
                                <select class="form-control" name="" v-model="filterModel.seller">
                                    <option value="">All</option>
                                    <option v-for="seller in listIncomes.sellers" v-bind:value="seller.user_id">{{ seller.username }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" v-if="user.isAdmin">
                            <div class="form-group">
                                <label for="">Buyer</label>
                                <select class="form-control" name="" v-model="filterModel.buyer">
                                    <option value="">All</option>
                                    <option v-for="buyer in listIncomes.buyers" v-bind:value="buyer.user_id_buyer">{{ buyer.username }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Date Completed</label>
                                <input type="date" class="form-control" v-model="filterModel.date">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch">Clear</button>
                            <button class="btn btn-default" @click="doSearch">Search <i v-if="isSearching" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Incomes</h3>

                    <h5 class="d-inline pull-right">Amount: $ {{ totalAmount }}</h5>

                    <table width="100%">
                        <tr>
                            <td>
                                <div class="input-group input-group-sm float-right" style="width: 100px">
                                    <select name="" class="form-control float-right" @change="getListIncomes" v-model="filterModel.paginate" style="height: 37px;">
                                        <option v-for="option in paginate" v-bind:value="option">
                                            {{ option }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>     

                </div>

                <div class="box-body table-responsive no-padding">
                    <table  id="tbl-income" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID</th>
                                <th v-if="isSeller">User Seller</th>
                                <th v-if="user.isOurs == 0">User Buyer</th>
                                <th>URL Publisher</th>
                                <th>Price</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(incomes, index) in listIncomes.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ incomes.id }}</td>
                                <td v-if="isSeller">{{ incomes.publisher.user.name }}</td>
                                <td v-if="user.isOurs == 0">{{ incomes.user.name }}</td>
                                <td>{{ replaceCharacters(incomes.publisher.url) }}</td>
                                <td>$ {{ incomes.price }}</td>
                                <td>{{ incomes.live_date }}</td>
                                <td>{{ incomes.status }}</td>
                                <td>{{ incomes.payment_status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal" @click="doUpdate(incomes)" data-target="#modal-update-incomes" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="height:50px;"></div>
                    <span v-if="listIncomes.total > 10" class="pagination-custom-footer-text float-right">
                        <b>Showing {{ listIncomes.from }} to {{ listIncomes.to }} of {{ listIncomes.total }} entries.</b>
                    </span>

                </div>

            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-update-incomes" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Incomes Information</h5>
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
                                    <input type="text" v-model="updateModel.seller" :disabled="true" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">User Buyer</label>
                                    <input type="text" v-model="updateModel.buyer" :disabled="true" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>
                                    <input type="text" v-model="updateModel.price" :disabled="true" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Payment Status</label>
                                    <input type="text" v-model="updateModel.payment_status" :disabled="true" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12" v-show="updateModel.proof_doc_path != null">
                                <div class="form-group">
                                    <label style="color: #333">Proof of Documents</label>
                                    <img :src="updateModel.proof_doc_path" class="img-fluid" alt="Proof of Document">
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">Payment Status</label>
                                    <select name="" id="" v-model="updateModel.payment_status" class="form-control">
                                        <option value="Paid">Paid</option>
                                        <option value="Not Paid">Not Paid</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!-- <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal -->

    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        data() {
            return {
                paginate: [25,50,100,200,250],
                statusBaclink: ['Processing', 'Content writing', 'Content sent', 'Live'],
                updateModel: {
                    seller: '',
                    buyer: '',
                    price: '',
                    payment_status: '',
                    proof_doc_path: '',
                },
                isPopupLoading: false,
                filterModel: {
                    user: this.$route.query.user || '',
                    payment_status: this.$route.query.payment_status || '',
                    status: this.$route.query.status || '',
                    buyer: this.$route.query.buyer || '',
                    seller: this.$route.query.seller || '',
                    paginate: this.$route.query.paginate || '25',
                    date: this.$route.query.date || '',
                },
                isSearching: false,
                isSeller: true,
                totalAmount: 0,
            }
        },

        async created() {
            this.getListIncomes();
            this.checkAccountType();

        },

        computed: {
            ...mapState({
                listIncomes: state => state.storeIncomes.listIncomes,
                messageForms: state => state.storeIncomes.messageForms,
                user: state => state.storeAuth.currentUser,
            })
        },

        methods: {
            async getListIncomes(params){
                $('#tbl-income').DataTable().destroy();

                this.isSearching = true;
                await this.$store.dispatch('actionGetListIncomes', {
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                        status: this.filterModel.status,
                        buyer: this.filterModel.buyer,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        date: this.filterModel.date,
                    }
                });

                var columnSort = [];

                if(this.user.isOurs == 0) {
                    columnSort = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 2 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: true, targets: 7 },
                        { orderable: true, targets: 8 },
                        { orderable: false, targets: '_all' }
                    ];
                } else {
                    columnSort = [
                        { orderable: true, targets: 0 },
                        { orderable: true, targets: 1 },
                        { orderable: true, targets: 3 },
                        { orderable: true, targets: 4 },
                        { orderable: true, targets: 5 },
                        { orderable: true, targets: 6 },
                        { orderable: false, targets: '_all' }
                    ];
                }

                $('#tbl-income').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: columnSort
                });

                this.isSearching = false;
                this.getTotalAmount()
            },

            replaceCharacters(str) {
                let char1 = str.replace("http://", "");
                let char2 = char1.replace("https://", "");
                let char3 = char2.replace("www.", "");

                return char3;
            },

            getTotalAmount() {
                let incomes = this.listIncomes.data
                let total_price = [];
                let total = 0;
                incomes.forEach(function(item, index){
                    total_price.push( parseFloat(item.price))
                })

                if( total_price.length > 0 ){
                    total = total_price.reduce(this.calcSum)
                }
                this.totalAmount = total;
            },

            calcSum(total, num) {
                return total + num
            },

            checkAccountType() {
                let that = this.user
                if( that.user_type ){
                    if( that.user_type.type == 'Seller' ){
                        this.isSeller = false;
                    }
                }
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },

            doSearch() {
                $('#tbl-income').DataTable().destroy();

                this.$router.push({
                    query: this.filterModel,
                });

                this.getListIncomes({
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                        status: this.filterModel.status,
                        buyer: this.filterModel.buyer,
                        seller: this.filterModel.seller,
                        paginate: this.filterModel.paginate,
                        date: this.filterModel.date,
                    }
                });
            },

            clearSearch() {
                $('#tbl-income').DataTable().destroy();

                this.filterModel = {
                    user: '',
                    payment_status: '',
                    status: '',
                    buyer: '',
                    seller: '',
                    date: '',
                    paginate: '25',
                }

                this.getListIncomes({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
                $('#tbl-income').DataTable().destroy();

                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdateIncomes', this.updateModel);
                this.isPopupLoading = false;

                this.getListIncomes();
            },

            doUpdate(incomes) {
                this.clearMessageform()
                let that = JSON.parse( JSON.stringify(incomes) )
                this.updateModel = that

                this.updateModel.seller = that.publisher.user.name
                this.updateModel.buyer = that.user.name
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageform');
            },
        }
    }
</script>