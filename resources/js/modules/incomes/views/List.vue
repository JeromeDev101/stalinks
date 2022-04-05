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
                        <h3 class="card-title text-primary">{{ $t('message.incomes.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.incomes.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.incomes.filter_id_backlink') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           v-model="filterModel.backlink_id"
                                           :placeholder="$t('message.incomes.filter_type')">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.incomes.filter_status_payment') }}</label>
                                    <select name="" id="" class="form-control" v-model="filterModel.payment_status">
                                        <option value="">{{ $t('message.incomes.all') }}</option>
                                        <option value="Paid">{{ $t('message.incomes.filter_paid') }}</option>
                                        <option value="Not paid">{{ $t('message.incomes.filter_not_paid') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2" v-if="user.isAdmin">
                                <div class="form-group">
                                    <label for="">{{ $t('message.incomes.seller') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.incomes.all') }}</option>
                                        <option v-for="seller in listIncomes.sellers" v-bind:value="seller.user_id">
                                            {{ seller.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2" v-if="user.isAdmin">
                                <div class="form-group">
                                    <label for="">{{ $t('message.incomes.buyer') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.buyer">
                                        <option value="">{{ $t('message.incomes.all') }}</option>
                                        <option v-for="buyer in listIncomes.buyers" v-bind:value="buyer.user_id_buyer">
                                            {{ buyer.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">{{ $t('message.incomes.filter_date_completed') }}</label>
                                    <input type="date" class="form-control" v-model="filterModel.date">
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearchingLoading">
                                    {{ $t('message.incomes.clear') }}
                                </button>
                                <button class="btn btn-default" @click="doSearch" :disabled="isSearchingLoading">
                                    {{ $t('message.incomes.search') }}
                                    <i v-if="isSearching" class="fa fa-refresh fa-spin"></i>
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
                        <h3 class="card-title text-primary">{{ $t('message.incomes.i_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="d-inline pull-right">{{ $t('message.incomes.i_amount') }} $ {{ totalAmount }}</h5>

                        <table width="100%">
                            <tr>
                                <td>
                                    <div class="input-group input-group-sm float-right" style="width: 100px">
                                        <select name=""
                                                class="form-control float-right"
                                                @change="getListIncomes"
                                                v-model="filterModel.paginate"
                                                style="height: 37px;">
                                            <option v-for="option in paginate" v-bind:value="option">
                                                {{ option }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <span class="pagination-custom-footer-text">
                            <b>Showing {{ listIncomes.from }} to {{ listIncomes.to }} of {{ listIncomes.total }} entries.</b>
                        </span>

                        <table id="tbl-income" class="table table-hover table-bordered table-striped rlink-table">
                            <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>{{ $t('message.incomes.i_id') }}</th>
                                <th>{{ $t('message.incomes.i_in_charge') }}</th>
                                <th v-if="isSeller">{{ $t('message.incomes.seller') }}</th>
                                <th v-if="user.isOurs == 0">{{ $t('message.incomes.buyer') }}</th>
                                <th>{{ $t('message.incomes.i_url_pub') }}</th>
                                <th>{{ $t('message.incomes.i_price') }}</th>
                                <th>{{ $t('message.incomes.filter_date_completed') }}</th>
                                <th>{{ $t('message.incomes.i_status') }}</th>
                                <th>{{ $t('message.incomes.filter_status_payment') }}</th>
                                <th>{{ $t('message.incomes.i_action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(incomes, index) in listIncomes.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ incomes.id }}</td>
                                <td>{{ incomes.in_charge == null ? 'N/A' : incomes.in_charge }}</td>
                                <td v-if="isSeller">
                                    {{
                                        incomes.publisher == null
                                            ? 'Record Deleted'
                                            : incomes.publisher.user
                                                ? incomes.publisher.user.name
                                                : 'Record Deleted'
                                    }}
                                </td>
                                <td v-if="user.isOurs == 0">{{ incomes.user == null ? '' : incomes.user.name }}</td>
                                <td>
                                    <!--                                    {{ incomes.publisher == null ? 'Record Deleted':replaceCharacters(incomes.publisher.url) }}-->
                                    <span v-if="incomes.publisher == null">
                                        {{ $t('message.incomes.i_record_deleted') }}
                                    </span>
                                    <span v-else>
                                        <a :href="'//' + replaceCharacters(incomes.publisher.url)" target="_blank">
                                            {{ replaceCharacters(incomes.publisher.url) }}
                                        </a>
                                    </span>
                                </td>
                                <td>{{
                                        incomes.publisher == null ? 'Record Deleted' : '$ ' + incomes.publisher.price
                                    }}
                                </td>
                                <td>{{ incomes.live_date }}</td>
                                <td>{{ incomes.status }}</td>
                                <td>{{ incomes.payment_status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-toggle="modal"
                                                @click="doUpdate(incomes)"
                                                data-target="#modal-update-incomes"
                                                :title="$t('message.incomes.edit')"
                                                class="btn btn-default"><i class="fa fa-fw fa-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade"
             id="modal-update-incomes"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.incomes.ii_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.incomes.ii_seller') }}</label>
                                    <input type="text"
                                           v-model="updateModel.seller"
                                           :disabled="true"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.incomes.ii_buyer') }}</label>
                                    <input type="text"
                                           v-model="updateModel.buyer"
                                           :disabled="true"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.incomes.i_price') }}</label>
                                    <input type="text"
                                           v-model="updateModel.price"
                                           :disabled="true"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.incomes.ii_payment_status') }}</label>
                                    <input type="text"
                                           v-model="updateModel.payment_status"
                                           :disabled="true"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12" v-show="updateModel.proof_doc_path != null">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.incomes.ii_proof') }}</label>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.incomes.close') }}
                        </button>
                        <!-- <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal -->
    </div>
</template>

<script>
import {mapState} from 'vuex';

export default {
    data() {
        return {
            paginate : [
                50,
                150,
                250,
                350,
                'All'
            ],
            statusBaclink : [
                'Processing',
                'Content writing',
                'Content sent',
                'Live'
            ],
            updateModel : {
                seller : '',
                buyer : '',
                price : '',
                payment_status : '',
                proof_doc_path : '',
            },
            isPopupLoading : false,
            filterModel : {
                backlink_id : this.$route.query.backlink_id || '',
                payment_status : this.$route.query.payment_status || '',
                buyer : this.$route.query.buyer || '',
                seller : this.$route.query.seller || '',
                paginate : this.$route.query.paginate || '50',
                date : this.$route.query.date || '',
            },
            isSearching : false,
            isSeller : true,
            totalAmount : 0,
            isSearchingLoading : false,
        }
    },

    async created() {
        this.getListIncomes();
        this.checkAccountType();
    },

    computed : {
        ...mapState({
            listIncomes : state => state.storeIncomes.listIncomes,
            messageForms : state => state.storeIncomes.messageForms,
            user : state => state.storeAuth.currentUser,
        })
    },

    methods : {
        async getListIncomes(params) {
            $('#tbl-income').DataTable().destroy();

            this.isSearching = true;
            this.isSearchingLoading = true;
            if (this.filterModel.paginate == 'All') {
                await this.$store.dispatch('actionGetListIncomes', {
                    params : {
                        paginate : 1000000,
                        backlink_id : this.filterModel.backlink_id,
                        payment_status : this.filterModel.payment_status,
                        buyer : this.filterModel.buyer,
                        seller : this.filterModel.seller,
                        date : this.filterModel.date,
                    }
                });
            } else {
                await this.$store.dispatch('actionGetListIncomes', {
                    params : {
                        paginate : this.filterModel.paginate,
                        backlink_id : this.filterModel.backlink_id,
                        payment_status : this.filterModel.payment_status,
                        buyer : this.filterModel.buyer,
                        seller : this.filterModel.seller,
                        date : this.filterModel.date,
                    }
                });
            }
            //await this.$store.dispatch('actionGetListIncomes', params);

            var columnSort = [];

            if (this.user.isOurs == 0) {
                columnSort = [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 2},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : true, targets : 7},
                    {orderable : true, targets : 8},
                    {orderable : true, targets : 9},
                    {orderable : false, targets : '_all'}
                ];
            } else {
                columnSort = [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 3},
                    {orderable : true, targets : 4},
                    {orderable : true, targets : 5},
                    {orderable : true, targets : 6},
                    {orderable : false, targets : '_all'}
                ];
            }

            $('#tbl-income').DataTable({
                paging : false,
                searching : false,
                columnDefs : columnSort
            });

            this.isSearching = false;
            this.isSearchingLoading = false;
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
            incomes.forEach(function (item, index) {
                total_price.push(parseFloat(item.price))
            })

            if (total_price.length > 0) {
                total = total_price.reduce(this.calcSum)
            }
            this.totalAmount = total.toFixed(2);
        },

        calcSum(total, num) {
            return total + num
        },

        checkAccountType() {
            let that = this.user
            if (that.user_type) {
                if (that.user_type.type == 'Seller') {
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
                query : this.filterModel,
            });

            this.getListIncomes({
                params : {
                    backlink_id : this.filterModel.backlink_id,
                    payment_status : this.filterModel.payment_status,
                    buyer : this.filterModel.buyer,
                    seller : this.filterModel.seller,
                    paginate : this.filterModel.paginate,
                    date : this.filterModel.date,
                }
            });
        },

        clearSearch() {
            $('#tbl-income').DataTable().destroy();

            this.filterModel = {
                backlink_id : '',
                payment_status : '',
                buyer : '',
                seller : '',
                date : '',
                paginate : '50',
            }

            this.getListIncomes({
                params : this.filterModel
            });

            this.$router.replace({'query' : null});
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
            let that = JSON.parse(JSON.stringify(incomes))
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
