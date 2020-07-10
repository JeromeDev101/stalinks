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
                                <label for="">Search User</label>
                                <input type="text" class="form-control" v-model="filterModel.user" name="" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Payment Status</label>
                                <select name="" id="" class="form-control" v-model="filterModel.payment_status">
                                    <option value="">All</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Not paid">Not paid</option>
                                </select>
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
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID</th>
                                <th v-if="isSeller">User Seller</th>
                                <th>User Buyer</th>
                                <th>URL Publisher</th>
                                <th>Price</th>
                                <th>Date Completed</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(incomes, index) in listIncomes.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ incomes.id }}</td>
                                <td v-if="isSeller">{{ incomes.publisher.user.name }}</td>
                                <td>{{ incomes.user.name }}</td>
                                <td>{{ incomes.publisher.url }}</td>
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
                            <div class="col-sm-12">
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
                },
                isSearching: false,
                isSeller: true,
                totalAmount: 0,
            }
        },

        async created() {
            this.getListIncomes();
            this.checkAccountType();
            this.getTotalAmount()
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
                this.isSearching = true;
                await this.$store.dispatch('actionGetListIncomes', {
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                    }
                });
                this.isSearching = false;
            },

            getTotalAmount() {
                let incomes = this.listIncomes

                console.log(incomes)
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
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListIncomes({
                    params: {
                        user: this.filterModel.user,
                        payment_status: this.filterModel.payment_status,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    user: '',
                    payment_status: '',
                }

                this.getListIncomes({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            async submitUpdate(params) {
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