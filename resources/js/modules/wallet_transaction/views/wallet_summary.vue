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
                                <select name="" class="form-control" >
                                    <option value="">All</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default"  >Clear</button>
                            <button class="btn btn-default">Search </button>
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
                                <th>Orders Live</th>
                                <th>Credit Left</th>
                                <th>Wallet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(summary, index) in summaryData" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ summary.username == null ? summary.name : summary.username }}</td>
                                <td>{{ summary.deposit == null ? 0 : '$ ' + summary.deposit  }}</td>
                                <td>{{ '$ ' + summary.orders }}</td>
                                <td>{{ '$ ' + summary.order_cancel }}</td>
                                <td>{{ '$ ' + summary.order_live }}</td>
                                <td>{{ '$ ' + summary.credit_left }}</td>
                                <td>{{ '$ ' + summary.wallet }}</td>
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
                axios.get('/api/wallet-summary')
                    .then((res) => {
                        this.summaryData = res.data
                    })
            }
        },

    }
</script>
