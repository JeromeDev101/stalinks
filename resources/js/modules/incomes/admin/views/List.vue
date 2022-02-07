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
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search ID Backlink</label>
                                    <input type="text"
                                           class="form-control"
                                           name=""
                                           v-model="filterModel.backlink_id"
                                           aria-describedby="helpId"
                                           placeholder="Type here">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Date Completed</label>

                                    <date-range-picker
                                        v-model="filterModel.date_completed"
                                        ref="picker"
                                        opens="right"
                                        style="width: 100%"
                                        :linkedCalendars="true"
                                        :dateRange="filterModel.date_completed"
                                        :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                    />
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearchingLoading">
                                    Clear
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearchingLoading">Search
                                    <i v-show="isSearchLoading" class="fa fa-refresh fa-spin"></i></button>
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
                        <h3 class="card-title text-primary">Incomes</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-sm float-right" style="width: 100px">
                            <select name=""
                                    class="form-control float-right"
                                    @change="getListIncomesAdmin"
                                    v-model="filterModel.paginate"
                                    style="height: 37px;">
                                <option v-for="option in paginate" v-bind:value="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <button data-toggle="modal"
                                data-target="#modal-setting-incomes"
                                class="btn btn-default float-right"><i class="fa fa-cog"></i></button>

                        <div class="d-flex justify-content-center">
                            <div v-if="user.role_id !== 11"  class="col">
                                <h6>Seller Price:
                                    ${{ listIncomesAdmin.seller_price_sum.toFixed(2) }}</h6>
                            </div>

                            <div v-if="user.role_id !== 11"  class="col">
                                <h6>Buyer Price:
                                    ${{ listIncomesAdmin.buyer_price_sum.toFixed(2) }}</h6>
                            </div>

                            <div class="col">
                                <h6>Fee Charges:
                                    $0.00</h6>
                            </div>

                            <div class="col">
                                <h6>Content Charges:
                                    ${{ totalContentCharges }}</h6>
                            </div>

                            <div class="col">
                                <h6>Net Incomes:
                                    ${{ totalNetIncome }}</h6>
                            </div>

                            <div class="col">
                                <h6>Affiliate Commission:
                                    ${{ listIncomesAdmin.affiliate_commission_sum.toFixed(2) }}</h6>
                            </div>

                        </div>

                        <span class="pagination-custom-footer-text">
                        <b>Showing {{ listIncomesAdmin.from }} to {{ listIncomesAdmin.to }} of {{
                                listIncomesAdmin.total
                           }} entries.</b>
                    </span>

                        <div class="table-responsive">
                            <table id="tbl_incomes_admin"
                                   class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th v-show="tblOptIncomesAdmin.backlink_id">ID Backlink</th>
                                    <th v-show="tblOptIncomesAdmin.live_date">Date Completed</th>
                                    <th v-if="user.role_id !== 11"  v-show="tblOptIncomesAdmin.selling_price">Seller Price</th>
                                    <th v-if="user.role_id !== 11">Buyer Commission</th>
                                    <th v-if="user.role_id !== 11"  v-show="tblOptIncomesAdmin.price">Buyer Price</th>
                                    <th v-show="tblOptIncomesAdmin.fee_charges">Fee Charges</th>
                                    <th v-show="tblOptIncomesAdmin.content_charges">Content Charges</th>
                                    <th v-show="tblOptIncomesAdmin.net_incomes">Net Incomes</th>
                                    <th v-show="tblOptIncomesAdmin.affiliate_commission">Affiliate Commission</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(incomes_admin, index) in listIncomesAdmin.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td v-show="tblOptIncomesAdmin.backlink_id">{{ incomes_admin.id }}</td>
                                    <td v-show="tblOptIncomesAdmin.live_date">{{ incomes_admin.live_date }}</td>
                                    <td v-if="user.role_id !== 11"  v-show="tblOptIncomesAdmin.selling_price">$
                                        {{
                                            incomes_admin.price == null || incomes_admin.price == '' ? 0 : number_format(incomes_admin.price)
                                        }}
                                    </td>
                                    <td v-if="user.role_id !== 11">{{ incomes_admin.user.user_type == null ? 'N/A':incomes_admin.user.user_type.commission }}</td>
                                    <td v-if="user.role_id !== 11"  v-show="tblOptIncomesAdmin.price">
                                        {{
                                            incomes_admin.prices == '' || incomes_admin.prices == null ? 0 : '$ ' + number_format(incomes_admin.prices)
                                        }}
                                    </td>
                                    <td v-show="tblOptIncomesAdmin.fee_charges">$0</td>
                                    <td v-show="tblOptIncomesAdmin.content_charges">$ {{ computeWriterPrice(incomes_admin, incomes_admin.billing_count) }}</td>
                                    <!-- static only -->
                                    <td v-show="tblOptIncomesAdmin.net_incomes">${{ computeNetIncomes(incomes_admin) }}</td>
                                    <td v-show="tblOptIncomesAdmin.affiliate_commission">
                                        <span v-if="incomes_admin.user.user_type == null">
                                            <small>
                                                <span class="badge badge-secondary">No Affiliate</span>
                                            </small>
                                        </span>

                                        <span v-else>
                                            <span v-if="incomes_admin.user.user_type.affiliate_id == null">
                                                <small>
                                                    <span class="badge badge-secondary">No Affiliate</span>
                                                </small>
                                            </span>

                                            <span v-else>
                                                <span v-if="incomes_admin.user.user_type.affiliate.status !== 'inactive'
                                                && incomes_admin.user.user_type.affiliate.status !== 'Inactive'">
                                                    {{
                                                        incomes_admin.affiliate_commission == ''
                                                        || incomes_admin.affiliate_commission == null
                                                            ? 0
                                                            : '$ ' + Math.round(incomes_admin.affiliate_commission)
                                                    }}
                                                </span>

                                                <small v-else>
                                                    <span class="badge badge-danger">Inactive Affiliate</span>
                                                </small>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <pagination
                            :limit="8"
                            :data="listIncomesAdmin"
                            class="mt-2"

                            @pagination-change-page="getListIncomesAdmin">

                        </pagination>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Settings -->
        <div class="modal fade" id="modal-setting-incomes" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Setting Default</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="form-group row">
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.backlink_id ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.backlink_id">ID Backlink</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.selling_price ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.selling_price">Selling Price</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.price ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.price">Price</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.gross_income ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.gross_income">Gross Income</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.fee_charges ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.fee_charges">Fee Charges</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.content_charges ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.content_charges">Content Charges</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.net_incomes ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.net_incomes">Net Incomes</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.live_date ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.live_date">Date Completed</label>
                            </div>
                            <div class="checkbox col-md-6">
                                <label><input type="checkbox"
                                              :checked="tblOptIncomesAdmin.affiliate_commission ? 'checked':''"
                                              v-model="tblOptIncomesAdmin.affiliate_commission">Affiliate Commission</label>
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
import {mapState} from 'vuex';
import _ from 'lodash';

export default {
    data() {
        return {
            paginate : [
                20,
                50,
                150,
                250,
                350,
                'All',
            ],
            isSearchLoading : false,
            filterModel : {
                paginate : this.$route.query.paginate || '50',
                backlink_id : this.$route.query.backlink_id || '',
                date_completed : {
                    startDate : null,
                    endDate : null,
                },
            },
            isSearchingLoading : false,
            totalContentCharges: 0,
        }
    },

    async created() {
        this.getListIncomesAdmin();
    },

    computed : {
        ...mapState({
            listIncomesAdmin : state => state.storeIncomesAdmin.listIncomesAdmin,
            tblOptIncomesAdmin : state => state.storeIncomesAdmin.tblOptIncomesAdmin,
            messageForms : state => state.storeIncomesAdmin.messageForms,
            user : state => state.storeAuth.currentUser,
        }),

        totalNetIncome() {
            let result = parseFloat(parseFloat(this.listIncomesAdmin.buyer_price_sum - this.listIncomesAdmin.seller_price_sum)) - parseFloat(this.listIncomesAdmin.billing_count_sum);

            return result.toFixed(2);
        },
    },

    methods : {
        computeSummary() {
            let total = [];
            for(let i in this.listIncomesAdmin.data) {
                total.push( this.computeWriterPrice(this.listIncomesAdmin.data[i], this.listIncomesAdmin.data[i].billing_count) )
            }

            this.totalContentCharges = total.reduce(this.add,0)
        },

        add(accumulator, a) {
            return accumulator + a;
        },

        computeWriterPrice(incomes, billing_count) {
            let price = 0;
            let rate_type = (incomes.rate_type == null || incomes.rate_type == '') ? 'ppw' : (incomes.rate_type).toLowerCase();
            let fee = billing_count ? billing_count : 0;

            if(incomes.writer_price == null || incomes.article == null || incomes.article.contentnohtml == null) {
                price = fee;
            } else {
                var num_words = parseInt(incomes.article.num_words);
                var writer_price = parseFloat(incomes.writer_price);


                if (rate_type == 'ppw') {
                    price = (writer_price * num_words) + fee;
                } else {
                    price = writer_price + fee
                }
            }

            return price;
        },

        computeNetIncomes(incomes) {
            let writer_price = this.computeWriterPrice(incomes, incomes.billing_count);

            let result = parseInt(incomes.prices) - parseInt(incomes.price) - parseInt(writer_price);

            return result;
        },

        computeGross(selling_price, price) {
            let p1 = parseFloat(selling_price);
            let p2 = parseFloat(price);
            let result = 0;

            if (p1 != 0) {
                result = 0;
            }

            return result;

        },

        number_format(num) {
            let n = parseFloat(num);
            return n.toFixed(0);
        },

        async getListIncomesAdmin(page = 1) {
            $('#tbl_incomes_admin').DataTable().destroy();

            this.filterModel.page = page;

            this.isSearchLoading = true;
            this.isSearchingLoading = true;
            await this.$store.dispatch('actionGetListIncomesAdmin', {
                params : this.filterModel,
            });

            $('#tbl_incomes_admin').DataTable({
                paging : false,
                searching : false,
                columnDefs : {orderable : true, targets : '_all'},
            });

            this.isSearchLoading = false;
            this.isSearchingLoading = false;

            this.computeSummary();
        },

        doSearch() {
            this.$router.push({
                query : this.filterModel,
            });

            this.getListIncomesAdmin({
                params : {
                    paginate : this.filterModel.paginate,
                    backlink_id : this.filterModel.backlink_id,
                    date_completed : this.filterModel.date_completed,
                },
            });
        },

        clearSearch() {
            this.filterModel = {
                paginate : '50',
                backlink_id : '',
                date_completed : {
                    startDate : null,
                    endDate : null,
                },
            }

            this.getListIncomesAdmin();

            this.$router.replace({'query' : null});
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageformIncomesAdmin');
        },

    },
}
</script>
