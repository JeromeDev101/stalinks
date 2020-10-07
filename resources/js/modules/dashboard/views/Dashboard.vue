<template>
    <div class="row">
        <section class="content-header col-sm-12">
            <h1>Dashboard</h1>
            <br>
        </section>

        <div class="col-lg-6" v-if="user.isAdmin || (user.isOurs == 0 && user.role_id == 6)">
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
                                        <th>Team In-charge <span class="text-primary">( {{ ext_domain.total }} )</span></th>
                                        <th>InTouched <span class="text-primary">( {{ ext_domain.num_in_touched }} )</span></th>
                                        <th>Qualified <span class="text-primary">( {{ ext_domain.num_qualified }} )</span></th>
                                        <th>Unqualified <span class="text-primary">( {{ ext_domain.num_unqualified }} )</span></th>
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
                                        <th>Seller <span class="text-primary">( {{ total_seller.total }} )</span></th>
                                        <th>No. Sites <span class="text-primary">( {{ total_seller.num_sites }} )</span></th>
                                        <th>No. Valid <span class="text-primary">( {{ total_seller.num_valid }} )</span></th>
                                        <th>No. Unchecked <span class="text-primary">( {{ total_seller.num_unchecked }} )</span></th>
                                        <th>No. Invalid <span class="text-primary">( {{ total_seller.num_invalid }} )</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(seller, index) in listData.total_seller" :key="index">
                                        <td>{{ upperCase(seller.username) }}</td>
                                        <td>{{ seller.num_sites }}</td>
                                        <td>{{ seller.num_valid }}</td>
                                        <td>{{ seller.num_unchecked }}</td>
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
                                        <th>Seller <span class="text-primary">( {{ backlink_seller.total }} )</span></th>
                                        <th>Processing <span class="text-primary">( {{ backlink_seller.num_processing }} )</span></th>
                                        <th>Content Writing <span class="text-primary">( {{ backlink_seller.writing }} )</span></th>
                                        <th>Content Done <span class="text-primary">( {{ backlink_seller.num_done }} )</span></th>
                                        <th>Content Sent <span class="text-primary">( {{ backlink_seller.num_sent }} )</span></th>
                                        <th>Live <span class="text-primary">( {{ backlink_seller.num_live }} )</span></th>
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
                                        <th>Buyer <span class="text-primary">( {{ backlink_buyer.total }} )</span></th>
                                        <th>Processing <span class="text-primary">( {{ backlink_buyer.num_processing }} )</span></th>
                                        <th>Content Writing <span class="text-primary">( {{ backlink_buyer.writing }} )</span></th>
                                        <th>Content Done <span class="text-primary">( {{ backlink_buyer.num_done }} )</span></th>
                                        <th>Content Sent <span class="text-primary">( {{ backlink_buyer.num_sent }} )</span></th>
                                        <th>Live <span class="text-primary">( {{ backlink_buyer.num_live }} )</span></th>
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


        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 6">
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
                                        <th>Seller <span class="text-primary">( {{ incomes.total }} )</span></th>
                                        <th>No. Backlinks <span class="text-primary">( {{ incomes.num_backlink }} )</span></th>
                                        <th>Unpaid <span class="text-primary">( {{ incomes.num_unpaid }} )</span></th>
                                        <th>Paid <span class="text-primary">( {{ incomes.num_paid }} )</span></th>
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
                                        <th>Buyer <span class="text-primary">( {{ purchase.total }} )</span></th>
                                        <th>No. Backlinks <span class="text-primary">( {{ purchase.num_backlink }} )</span></th>
                                        <th>Unpaid <span class="text-primary">( {{ purchase.num_unpaid }} )</span></th>
                                        <th>Paid <span class="text-primary">( {{ purchase.num_paid }} )</span></th>
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

        <div class="col-lg-6" v-if="user.isAdmin || user.role_id == 5">
            <div class="box box-primary" style="padding-bottom:0.5em;">

                <div class="box-header">
                    <h3 class="box-title text-primary">List backlinks to Buy</h3>
                </div>

                <div class="box-body custom-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover tbl-custom">
                                <thead>
                                    <tr>
                                        <th v-if="user.isAdmin">Buyer <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.total }} )</span></th>
                                        <th>New <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.num_new }} )</span></th>
                                        <th>Interested <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.num_interested }} )</span></th>
                                        <th>Purchased <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.num_purchased }} )</span></th>
                                        <th>Not Interested <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.num_not_interested }} )</span></th>
                                        <th>Total <span v-if="user.isAdmin" class="text-primary">( {{ backlink_to_buy.num_total }} )</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(buy, index) in listData.backlink_to_buy" :key="index">
                                        <td v-if="user.isAdmin">{{ upperCase(buy.username) }}</td>
                                        <td>{{ buy.num_new }}</td>
                                        <td>{{ buy.num_interested }}</td>
                                        <td>{{ buy.num_purchased }}</td>
                                        <td>{{ buy.num_not_interested }}</td>
                                        <td>{{ buy.num_total }}</td>
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
            total_seller: {
                total: 0,
                num_sites: 0,
                num_unchecked: 0,
                num_valid: 0,
                num_invalid: 0,
            },

            incomes: {
                total: 0,
                num_backlink: 0,
                num_unpaid: 0,
                num_paid: 0,
            },

            purchase: {
                total: 0,
                num_backlink: 0,
                num_unpaid: 0,
                num_paid: 0,
            },

            backlink_seller:{
                total: 0,
                num_processing: 0,
                writing: 0,
                num_done: 0,
                num_sent: 0,
                num_live: 0,
            },

            backlink_buyer:{
                total: 0,
                num_processing: 0,
                writing: 0,
                num_done: 0,
                num_sent: 0,
                num_live: 0,
            },

            ext_domain:{
                total: 0,
                num_in_touched: 0,
                num_qualified: 0,
                num_unqualified: 0,
            },

            backlink_to_buy: {
                total: 0,
                username: 0,
                num_new: 0,
                num_not_interested: 0,
                num_interested: 0,
                num_purchased: 0,
                num_total: 0,
            },


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

            this.displayTotal();
        },

        displayTotal() {
            let total_seller = this.listData.total_seller
            let incomes = this.listData.incomes
            let purchase = this.listData.purchase
            let backlink_seller = this.listData.backlink_seller
            let backlink_buyer = this.listData.backlink_buyer
            let ext_domain = this.listData.ext_domain
            let backlink_to_buy = this.listData.backlink_to_buy

            for( var index in total_seller ){
                this.total_seller.num_sites += parseInt(total_seller[index].num_sites);
                this.total_seller.num_unchecked += parseInt(total_seller[index].num_unchecked);
                this.total_seller.num_valid += parseInt(total_seller[index].num_valid);
                this.total_seller.num_invalid += parseInt(total_seller[index].num_invalid);
            }
            this.total_seller.total = total_seller.length;


            for( var index in incomes ){
                this.incomes.num_backlink += parseInt(incomes[index].num_backlink);
                this.incomes.num_unpaid += parseInt(incomes[index].num_unpaid);
                this.incomes.num_paid += parseInt(incomes[index].num_paid);
            }
            this.incomes.total = incomes.length;

            for( var index in purchase ){
                this.purchase.num_backlink += parseInt(purchase[index].num_backlink);
                this.purchase.num_unpaid += parseInt(purchase[index].num_unpaid);
                this.purchase.num_paid += parseInt(purchase[index].num_paid);
            }
            this.purchase.total = purchase.length;

            for( var index in backlink_seller ){
                this.backlink_seller.num_processing += parseInt(backlink_seller[index].num_processing);
                this.backlink_seller.writing += parseInt(backlink_seller[index].writing);
                this.backlink_seller.num_done += parseInt(backlink_seller[index].num_done);
                this.backlink_seller.num_sent += parseInt(backlink_seller[index].num_sent);
                this.backlink_seller.num_live += parseInt(backlink_seller[index].num_live);
            }
            this.backlink_seller.total = backlink_seller.length;

            for( var index in backlink_buyer ){
                this.backlink_buyer.num_processing += parseInt(backlink_buyer[index].num_processing);
                this.backlink_buyer.writing += parseInt(backlink_buyer[index].writing);
                this.backlink_buyer.num_done += parseInt(backlink_buyer[index].num_done);
                this.backlink_buyer.num_sent += parseInt(backlink_buyer[index].num_sent);
                this.backlink_buyer.num_live += parseInt(backlink_buyer[index].num_live);
            }
            this.backlink_buyer.total = backlink_buyer.length;

            for( var index in ext_domain ){
                this.ext_domain.num_in_touched += parseInt(ext_domain[index].num_in_touched);
                this.ext_domain.num_qualified += parseInt(ext_domain[index].num_qualified);
                this.ext_domain.num_unqualified += parseInt(ext_domain[index].num_unqualified);
            }
            this.ext_domain.total = ext_domain.length;

            for( var index in backlink_to_buy ){
                this.backlink_to_buy.num_new += parseInt(backlink_to_buy[index].num_new);
                this.backlink_to_buy.num_not_interested += parseInt(backlink_to_buy[index].num_not_interested);
                this.backlink_to_buy.num_interested += parseInt(backlink_to_buy[index].num_interested);
                this.backlink_to_buy.num_purchased += parseInt(backlink_to_buy[index].num_purchased);
                this.backlink_to_buy.num_total += parseInt(backlink_to_buy[index].num_total);
            }
            this.backlink_to_buy.total = backlink_to_buy.length;

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