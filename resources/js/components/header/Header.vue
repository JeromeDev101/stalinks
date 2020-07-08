<template>
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">STA</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>StaLink </b>System</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li v-if="isBuyer" style="margin-left:-300px;margin-bottom:-55px;">
                        <a href="#">
                            Wallet: <strong>$ {{ user.wallet_transaction == null ? '0':user.wallet_transaction.amount_usd }}</strong> 
                            &nbsp;
                            Credit: <strong>$ {{ user.wallet_transaction == null ? '0':computeCredit(user.wallet_transaction.amount_usd) }}</strong> 
                        </a>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img :src="user.avatar" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ user.name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img :src="user.avatar" class="img-circle" alt="User Image">
                                <p>{{ user.name }}</p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <router-link  :to="{ path: `/profile/${user.id}` }">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </router-link>
                                </div>
                                <div class="pull-right">
                                    <a href="#" @click="logoutAndRedirect" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="dropdown user user-menu">
                        <a href="#">
                        <img :src="user.avatar" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ user.name }}</span>
                        </a>
                        </li> -->
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
import axios from 'axios';
import { mapActions, mapState, mapGetters } from 'vuex';
import Cookies from 'js-cookie';
export default {
    name: 'AppHeader',

    data() {
        return {
            isBuyer: false,
            amount_usd: null,
            error: null,

        }
    },

    created() {
        this.checkAccountType();
        this.liveGetWallet();

        let storage = JSON.parse(localStorage.getItem('vuex'))
        let purchased = storage.storeAuth.currentUser.purchased

        // console.log(purchased)
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        })
    },

    methods: {
        ...mapActions({
            logout: 'auth/logout',
        }),

        computeCredit(wallet) {
            let buyer_purchased = this.user.purchased
            let price_list = [];
            let total_purchased = 0;
            let credit = 0;

            if( buyer_purchased != null ){

                buyer_purchased.forEach( function(item, index) {
                    if( typeof item.publisher !== 'undefined' ){
                        price_list.push( parseFloat(item.publisher[0].price) )
                    }
                })
            }

            if( price_list.length > 0 ){
                total_purchased = price_list.reduce(this.calcSum);
                credit = parseFloat(wallet) - total_purchased;
            }

            return credit.toFixed(2);
        },

        calcSum(total, num) {
            return total + num
        }, 

        async logoutAndRedirect() {
            await this.$store.dispatch('logout')
            localStorage.clear();
            Cookies.remove('vuex');
            this.$router.push({ name: 'login' });
        },

        liveGetWallet() {
            axios.get('api/current-user')
                .then(response => (this.amount_usd = response))
                .catch(error => (this.error = error))
        },

        checkAccountType() {
            let that = this.user

            // not employee
            if( that.user_type ){
                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }

            // for emaployee with a role of seller/buyer
            if( that.role.description == 'Buyer' ){
                this.isBuyer = true;
            }

        },
    }
}
</script>
