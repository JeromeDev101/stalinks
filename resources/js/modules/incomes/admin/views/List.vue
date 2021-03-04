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
                                <label for="">Search ID Backlink</label>
                                <input type="text" class="form-control" name="" v-model="filterModel.backlink_id" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch" :disabled="isSearchingLoading">Clear</button>
                            <button class="btn btn-default" @click="doSearch" :disabled="isSearchingLoading">Search <i v-show="isSearchLoading" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Incomes</h3>


                    <div class="input-group input-group-sm float-right" style="width: 100px">
                        <select name="" class="form-control float-right" @change="getListIncomesAdmin" v-model="filterModel.paginate" style="height: 37px;">
                            <option v-for="option in paginate" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <button data-toggle="modal" data-target="#modal-setting-incomes" class="btn btn-default float-right"><i class="fa fa-cog"></i></button>

                </div>

                <div class="box-body no-padding">

                    <span class="pagination-custom-footer-text">
                        <b>Showing {{ listIncomesAdmin.from }} to {{ listIncomesAdmin.to }} of {{ listIncomesAdmin.total }} entries.</b>
                    </span>

                    <table id="tbl_incomes_admin" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th v-show="tblOptIncomesAdmin.backlink_id">ID Backlink</th>
                                <th v-show="tblOptIncomesAdmin.selling_price">Seller Price</th>
                                <th v-show="tblOptIncomesAdmin.price">Buyer Price</th>
                                <th v-show="tblOptIncomesAdmin.fee_charges">Fee Charges</th>
                                <th v-show="tblOptIncomesAdmin.content_charges">Content Charges</th>
                                <th v-show="tblOptIncomesAdmin.net_incomes">Net Incomes</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr v-for="(incomes_admin, index) in listIncomesAdmin.data" :key="index">
                               <td>{{ index + 1 }}</td>
                               <td v-show="tblOptIncomesAdmin.backlink_id">{{ incomes_admin.id }}</td>
                               <td v-show="tblOptIncomesAdmin.selling_price">$ {{ incomes_admin.publisher == null ? 0 : number_format(incomes_admin.publisher.price) }}</td>
                               <td v-show="tblOptIncomesAdmin.price">$ {{ number_format(incomes_admin.price) }}</td>
                               <td v-show="tblOptIncomesAdmin.fee_charges">0</td>
                               <td v-show="tblOptIncomesAdmin.content_charges">0</td>
                               <!-- static only -->
                               <td v-show="tblOptIncomesAdmin.net_incomes">$ {{ incomes_admin.publisher == null ? 0 : number_format(incomes_admin.publisher.price) }}</td>
                           </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting-incomes" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                        <div class="modal-load overlay float-right">
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.backlink_id ? 'checked':''" v-model="tblOptIncomesAdmin.backlink_id">ID Backlink</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.selling_price ? 'checked':''" v-model="tblOptIncomesAdmin.selling_price">Selling Price</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.price ? 'checked':''" v-model="tblOptIncomesAdmin.price">Price</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.gross_income ? 'checked':''" v-model="tblOptIncomesAdmin.gross_income">Gross Income</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.fee_charges ? 'checked':''" v-model="tblOptIncomesAdmin.fee_charges">Fee Charges</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.content_charges ? 'checked':''" v-model="tblOptIncomesAdmin.content_charges">Content Charges</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox" :checked="tblOptIncomesAdmin.net_incomes ? 'checked':''" v-model="tblOptIncomesAdmin.net_incomes">Net Incomes</label>
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

<script>
    import { mapState } from 'vuex';
    export default {
        data() {
            return {
                paginate: [50,150,250,350,'All'],
                isSearchLoading: false,
                filterModel: {
                    paginate: this.$route.query.paginate || '50',
                    backlink_id: this.$route.query.backlink_id || '',
                },
                isSearchingLoading: false,
            }
        },

        async created() {
            this.getListIncomesAdmin();
        },

        computed: {
            ...mapState({
                listIncomesAdmin: state => state.storeIncomesAdmin.listIncomesAdmin,
                tblOptIncomesAdmin: state => state.storeIncomesAdmin.tblOptIncomesAdmin,
                messageForms: state => state.storeIncomesAdmin.messageForms,
                user: state => state.storeAuth.currentUser,
            })
        },

        methods: {
            computeGross(selling_price, price){
                let p1 = parseFloat(selling_price);
                let p2 = parseFloat(price);
                let result = 0;

                if( p1 != 0 ){
                    result = 0;
                }
                
                return result;

            },

            number_format(num){
                let n = parseFloat(num);
                return n.toFixed(0);
            },

            async getListIncomesAdmin(params){
                $('#tbl_incomes_admin').DataTable().destroy();

                this.isSearchLoading = true;
                this.isSearchingLoading = true;
                await this.$store.dispatch('actionGetListIncomesAdmin', params);

                $('#tbl_incomes_admin').DataTable({
                    paging: false,
                    searching: false,
                    columnDefs: { orderable: true, targets: '_all' }
                });

                this.isSearchLoading = false;
                this.isSearchingLoading = false;
            },

            doSearch() {
                this.$router.push({
                    query: this.filterModel,
                });

                this.getListIncomesAdmin({
                    params: {
                        paginate: this.filterModel.paginate,
                        backlink_id: this.filterModel.backlink_id,
                    }
                });
            },

            clearSearch() {
                this.filterModel = {
                    paginate: '50',
                    backlink_id: '',
                }

                this.getListIncomesAdmin({
                    params: this.filterModel
                });

                this.$router.replace({'query': null});
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageformIncomesAdmin');
            },
    
        },
    }
</script>