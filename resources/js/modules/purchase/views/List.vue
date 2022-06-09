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
                        <h3 class="card-title text-primary">{{ $t('message.purchase.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button class="btn btn-primary ml-5"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseExample"
                                    aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> {{ $t('message.purchase.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseExample">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_search_backlink') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.search_id"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.purchase.type')">
                                </div>
                            </div>

                            <div class="col-md-4" v-if="user.isOurs != 1">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_seller') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.seller">
                                        <option value="">{{ $t('message.purchase.all') }}</option>
                                        <option
                                            v-for="seller in listPurchase.sellers"
                                            v-bind:value="seller.user_id">
                                            {{ seller.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4" v-if="user.isAdmin || user.role_id === 8 || isSubAccount">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_buyer') }}</label>
                                    <select class="form-control" name="" v-model="filterModel.buyer">
                                        <option value="">{{ $t('message.purchase.all') }}</option>
                                        <option
                                            v-for="buyer in listPurchase.buyers"
                                            v-bind:value="buyer.user_id_buyer">
                                            {{ buyer.username }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_search_pub') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="filterModel.search_url_publisher"
                                        name=""
                                        aria-describedby="helpId"
                                        :placeholder="$t('message.purchase.type')">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_payment_status') }}</label>
                                    <select name="" id="" class="form-control" v-model="filterModel.payment_status">
                                        <option value="">{{ $t('message.purchase.all') }}</option>
                                        <option value="Paid">{{ $t('message.purchase.paid') }}</option>
                                        <option value="Not paid">{{ $t('message.purchase.not_paid') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchase.filter_date_completed') }}</label>
                                    <div class="input-group">
                                        <date-range-picker
                                            v-model="filterModel.date_completed"
                                            :ranges="generateDefaultDateRange()"
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
                                <button class="btn btn-default" @click="clearSearch" :disabled="isSearchingLoading">
                                    {{ $t('message.purchase.clear') }}
                                </button>

                                <button class="btn btn-default" @click="doSearch" :disabled="isSearchingLoading">
                                    {{ $t('message.purchase.search') }}
                                    <i v-if="isSearching" class="fa fa-refresh fa-spin" ></i>
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
                        <h3 class="card-title text-primary">{{ $t('message.purchase.p_title') }}</h3>

                        <span
                            v-if="user.role_id === 5"
                            class="ml-5 text-primary card-title"
                            style="font-size:15px !important; line-height: normal !important; font-weight: normal !important;">

                            {{ $t('message.purchase.p_wallet') }}
                            <b>${{ (typeof listPurchase.wallet !== 'undefined') ? listPurchase.wallet : 0 }}</b>
                        </span>

                        <span
                            v-if="user.role_id === 5"
                            class="ml-5 text-primary card-title"
                            style="font-size:15px !important; line-height: normal !important; font-weight: normal !important;">

                            {{ $t('message.purchase.p_deposit') }}
                            <b>${{ (typeof listPurchase.deposit !== 'undefined') ? listPurchase.deposit : 0 }}</b>
                        </span>

                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6 d-flex align-items-end">
                                <span class="font-weight-bold" v-if="listPurchase.total > 10">
                                    <span v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: listPurchase.from, to: listPurchase.to, end: listPurchase.total }) }}
                                    </span>

                                    <span v-else>
                                        {{ $t('message.others.table_all_entries', { total: listPurchase.total }) }}
                                    </span>
                                </span>

                                <span class="mb-0 ml-5 font-weight-bold">
                                    {{ $t('message.purchase.p_amount') }} $ {{ totalAmount }}
                                </span>
                            </div>

                            <div class="col-6">
                                <select
                                    v-model="filterModel.paginate"
                                    class="form-control float-right"
                                    style="height: 37px; min-width: 100px; width: 100px"

                                    @change="getPurchaseList">

                                    <option v-for="option in paginate" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tbl-purchase" class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.purchase.t_title') }}</th>
                                    <th v-show="user.isAdmin || user.isOurs === 0">
                                        {{ $t('message.purchase.t_user_seller') }}
                                    </th>
                                    <th>{{ $t('message.purchase.t_user_buyer') }}</th>
                                    <th>{{ $t('message.purchase.t_url_pub') }}</th>
                                    <th>{{ $t('message.purchase.t_prices') }}</th>
                                    <th>{{ $t('message.purchase.filter_date_completed') }}</th>
                                    <th>{{ $t('message.purchase.t_status') }}</th>
                                    <th>{{ $t('message.purchase.t_status_payment') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(purchase, index) in listPurchase.data" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ purchase.id }}</td>
                                    <td v-show="user.isAdmin || user.isOurs === 0">
                                        {{
                                            purchase.publisher == null
                                                ? $t('message.purchase.t_record_deleted')
                                                : purchase.publisher.user == null
                                                    ? 'N/A'
                                                    : purchase.publisher.user.username
                                                        ? purchase.publisher.user.username
                                                        : purchase.publisher.user.name
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            purchase.user == null
                                                ? 'N/A'
                                                : purchase.user.username
                                                    ? purchase.user.username
                                                    : purchase.user.name
                                        }}
                                    </td>
                                    <td>
                                        <!--                                    {{ purchase.publisher == null ? 'Record Deleted':replaceCharacters(purchase.publisher.url) }}-->
                                        <span v-if="purchase.publisher == null">
                                            {{ $t('message.purchase.t_record_deleted') }}
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
            </div>
        </div>

        <!-- Modal Update -->
        <div class="modal fade" id="modal-update-purchase" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.purchase.up_title') }}</h5>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchase.t_user_seller') }}</label>
                                    <input type="text" v-model="updateModel.seller" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchase.t_user_buyer') }}</label>
                                    <input type="text" v-model="updateModel.buyer" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchase.up_price') }}</label>
                                    <input type="text" v-model="updateModel.price" :disabled="true" class="form-control" required="required" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchase.filter_payment_status') }}</label>
                                    <select name="" v-model="updateModel.payment_status" class="form-control">
                                        <option value="Paid">{{ $t('message.purchase.paid') }}</option>
                                        <option value="Not Paid">{{ $t('message.purchase.not_paid') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.purchase.close') }}
                        </button>

                        <button type="button" class="btn btn-primary">
                            {{ $t('message.purchase.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Update -->
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import {dateRange} from "../../../mixins/dateRange";

    export default {
        mixins: [dateRange],
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
                isSubAccount: false,
            }
        },

        async created() {
            this.getPurchaseList()
            this.checkSubAccount();
        },

        computed: {
             ...mapState({
                listPurchase: state => state.storePurchase.listPurchase,
                messageForms: state => state.storePurchase.messageForms,
                user: state => state.storeAuth.currentUser,
            })
        },

        methods: {
            checkSubAccount() {
                if(this.user.user_type) {
                    if(this.user.user_type.is_sub_account != 1) {
                        this.isSubAccount = true;
                    }
                }
            },

            async getPurchaseList(params){
                $('#tbl-purchase').DataTable().destroy();

                // change the format of date
                this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)

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

                // change the format of date
                this.filterModel.date_completed = this.formatFilterDates(this.filterModel.date_completed)

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
                let incomes = this.listPurchase.data;

                self.totalAmount = _.sumBy(incomes, function
                    (o) {
                    return parseFloat(o.prices);
                }).toFixed(0);
            },

            // calcSum(total, num) {
            //     return total + num
            // },

            doUpdate(purchase) {
                let that = JSON.parse( JSON.stringify(purchase) )

                this.updateModel = that
                this.updateModel.seller = that.publisher.user.name
                this.updateModel.buyer = that.user.name
            },
        }
    }
</script>
