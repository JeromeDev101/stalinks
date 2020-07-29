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
                                <input type="text" class="form-control" name="" v-model="filterModel.search_backlink" aria-describedby="helpId" placeholder="Type here">
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button class="btn btn-default">Clear</button>
                            <button class="btn btn-default">Search <i v-if="false" class="fa fa-refresh fa-spin" ></i></button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Buyer Billing</h3>
                </div>

                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <thead>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>ID Backlink</th>
                                <th>User Buyer</th>
                                <th>Buyer Price</th>
                                <th>Date Completed</th>
                                <th>Status Billing</th>
                                <th>Status Payment</th>
                                <th>Billing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(buyer, index) in listBuyerBilling.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ buyer.id }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

    export default {
        data() {
            return {
                filterModel: {
                    search_backlink: this.$route.query.search_backlink || '',
                }
            }
        },

        async created() {
            this.getListBuyerBilling();
        },

        computed: {
            ...mapState({
                listBuyerBilling: state => state.storeBillingBuyer.listBuyerBilling,
            }),
        }, 

        methods: {
            async getListBuyerBilling(params){
                await this.$store.dispatch('actionGetBuyerBilling', params);
            }
        },
        
    }
</script>