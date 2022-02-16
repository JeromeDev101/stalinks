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
                                <label for="">Buyer</label>
                                <select name="" class="form-control" v-model="filterModel.buyer">
                                    <option value="">All</option>
                                    <option v-for="option in summaryData" v-bind:value="option.id">
                                        {{ option.username }}
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default" @click="clearSearch()">Clear</button>
                            <button class="btn btn-default" @click="getWalletSummary()">Search </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Wallet Summarize</h3>
                </div>

                <div class="box-body no-padding relative">
                    <table id="tbl_wallet_transaction" class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Buyer</th>
                                <th>Deposit</th>
                                <th>Orders</th>
                                <th>Orders Cancelled</th>
                                <th>Valid Orders</th>
                                <th>Credit Left</th>
                                <th>Purchase</th>
                                <th>Wallet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(summary, index) in summaryData" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ summary.username == null ? summary.name : summary.username }}</td>
                                <td>{{ summary.deposit == null ? 0 : '$ ' + summary.deposit  }}</td>
                                <td>{{ '$ ' + (summary.orders).toFixed(0) }}</td>
                                <td>{{ '$ ' + (summary.order_cancel).toFixed(0) }}</td>
                                <td>{{ '$ ' + (summary.valid_orders).toFixed(0) }}</td>
                                <td>{{ '$ ' + (summary.credit_left).toFixed(0) }}</td>
                                <td>{{ '$ ' + (summary.order_live).toFixed(0) }}</td>
                                <td>{{ '$ ' + (summary.wallet).toFixed(0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        data() {
            return {
                summaryData: [],
                filterModel: {
                    buyer: this.$route.query.buyer || '',
                },
            }
        },

        async created() {
            this.getWalletSummary();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
            }),
        },

        methods: {
            getWalletSummary() {
                let loader = this.$loading.show();

                axios.get('/api/wallet-summary',{
                    params: {
                        buyer: this.filterModel.buyer
                    }
                }).then((res) => {
                    this.summaryData = res.data
                    loader.hide();
                })
            },

            clearSearch() {
                this.filterModel = {
                    buyer: '',
                }

                this.$router.replace({'query': null});
                this.getWalletSummary();
            }
        },

    }
</script>
