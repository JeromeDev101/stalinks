<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard</h1>
            <br>
        </section>

        <div class="col-lg-6" v-if="user.isAdmin">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Total External Domain</h3>
                </div>

                <div class="box-body custom-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr class="white">
                                        <th>Team In-charge</th>
                                        <th>InTouched</th>
                                        <th>Qualified</th>
                                        <th>Unqualified</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ext_domain, index) in listData.ext_domain" :key="index">
                                        <td>{{ upperCase(ext_domain.username) }}</td>
                                        <td>{{ ext_domain.num_in_touched }}</td>
                                        <td>{{ ext_domain.num_qualified }}</td>
                                        <td>{{ ext_domain.num_unqualified }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 6">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Total Seller</h3>
                </div>
                
                <div class="box-body custom-box">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr class="white">
                                        <th>Seller</th>
                                        <th>No. Sites</th>
                                        <th>No. Unchecked</th>
                                        <th>No. Valid</th>
                                        <th>No. Invalid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(seller, index) in listData.total_seller" :key="index">
                                        <td>{{ upperCase(seller.username) }}</td>
                                        <td>{{ seller.num_sites }}</td>
                                        <td>{{ seller.num_unchecked }}</td>
                                        <td>{{ seller.num_valid }}</td>
                                        <td>{{ seller.num_invalid }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 6">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Total Backlink (Seller)</h3>
                </div>

                <div class="box-body custom-box">
                   
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr>
                                        <th>Seller</th>
                                        <th>Processing</th>
                                        <th>Content Writing</th>
                                        <th>Content Done</th>
                                        <th>Content Sent</th>
                                        <th>Live</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(seller, index) in listData.backlink_seller" :key="index">
                                        <td>{{ upperCase(seller.username) }}</td>
                                        <td>{{ seller.num_processing }}</td>
                                        <td>{{ seller.writing }}</td>
                                        <td>{{ seller.num_done }}</td>
                                        <td>{{ seller.num_sent }}</td>
                                        <td>{{ seller.num_live }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 5">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Total Backlink (Buyer)</h3>
                </div>

                <div class="box-body custom-box">
                   
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr>
                                        <th>Buyer</th>
                                        <th>Processing</th>
                                        <th>Content Writing</th>
                                        <th>Content Done</th>
                                        <th>Content Sent</th>
                                        <th>Live</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(buyer, index) in listData.backlink_buyer" :key="index">
                                        <td>{{ upperCase(buyer.username) }}</td>
                                        <td>{{ buyer.num_processing }}</td>
                                        <td>{{ buyer.writing }}</td>
                                        <td>{{ buyer.num_done }}</td>
                                        <td>{{ buyer.num_sent }}</td>
                                        <td>{{ buyer.num_live }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Incomes</h3>
                </div>

                <div class="box-body custom-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr>
                                        <th>Seller</th>
                                        <th>No. Backlinks</th>
                                        <th>Unpaid</th>
                                        <th>Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(incomes, index) in listData.incomes" :key="index">
                                        <td>{{ upperCase(incomes.username) }}</td>
                                        <td>{{ incomes.num_backlink }}</td>
                                        <td>{{ incomes.num_unpaid }}</td>
                                        <td>{{ incomes.num_paid }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 5">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">Purchase</h3>
                </div>

                <div class="box-body custom-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr>
                                        <th>Buyer</th>
                                        <th>No. Backlinks</th>
                                        <th>Unpaid</th>
                                        <th>Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(purchase, index) in listData.purchase" :key="index">
                                        <td>{{ upperCase(purchase.username) }}</td>
                                        <td>{{ purchase.num_backlink }}</td>
                                        <td>{{ purchase.num_unpaid }}</td>
                                        <td>{{ purchase.num_paid }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { mapState } from 'vuex';
import config from '@/config';
import Hepler from '@/library/Helper';

export default {
    name: 'Dashboard',

    data() {
        return {
            //
        };
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            listData: state => state.storeDashboard.listData,
        }),
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
        let userId = this.$route.params.id || '';
        this.getData();
    },

    methods: {
        upperCase(string) {
            return string.toUpperCase();
        },

        async getData() {
            await this.$store.dispatch('actionGetData');

            $(".tbl-custom").DataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bInfo": false,
                "bAutoWidth": false,
            })
        },

        convertPrice(price) {
            return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }
}
</script>

<style scoped>
    .avatar li{
        width: 100%;
    }
    .custom-box {
        height: 330px;
        overflow-y: auto;
        overflow-x: hidden;
        padding-top: 0px;
        margin-bottom: 0.5em;
    }
    .tbl-custom {
        text-align: left;
        position: relative;
        border-collapse: collapse; 
    }
    .tbl-custom thead tr th, .tbl-custom tbody tr td {
        padding: 0.8rem;
    }
    .tbl-custom thead tr th {
        background: white;
        position: sticky;
        top: 0; 
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }

</style>