<template>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto;">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu tree" data-widget="tree">
                <li v-if="user.isAdmin || isManager || isSeller || isBuyer" class="header">ADMIN</li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'system' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/system' }">
                        <i class="fa fa-fw fa-cog"></i> <span>Admin setting</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'List User' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/users' }">
                        <i class="fa fa-fw fa-users"></i> <span>Team</span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'logs' }">
                    <router-link class="page-sidebar__item" :to="{ path: '/logs' }">
                        <i class="fa fa-fw fa-circle"></i> <span>System Logs</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin || isManager || isSeller" :class="{ active: $route.name == 'AlexaDomain' }">
                    <router-link :to="{ path: '/ext/alexa' }">
                        <i class="fa fa-circle-o"></i>
                        <span>Get Alexa</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'Registration' }">
                    <router-link :to="{ path: '/accounts' }">
                        <i class="fa fa-user"></i>
                        <span>Registration Accounts</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin || isBuyer" :class="{ active: $route.name == 'wallet-transaction' }">
                    <router-link :to="{ path: '/wallet-transaction' }">
                        <i class="fa fa-money"></i>
                        <span>Wallet Transaction</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin || isManager || isSeller || isBuyer" :class="{ active: $route.name == 'articles-list' }">
                    <router-link :to="{ path: '/articles-list' }">
                        <i class="fa fa-file-text-o"></i>
                        <span>Article</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin" :class="{ active: $route.name == 'seller-billing' || $route.name == 'buyer-billing' || $route.name == 'writer-billing', 'treeview': true, 'menu-open': $route.name == 'buyer-billing' || $route.name == 'seller-billing'  || $route.name == 'writer-billing'}" >
                    <a href="#">
                        <i class="fa fa-btc"></i>
                        <span>Billing</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{ active: $route.name == 'seller-billing' }">
                            <router-link :to="{ path: '/seller-billing' }">
                                <i class="fa fa-fw fa-user-o"></i>
                                <span>Seller Billing</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'buyer-billing' }">
                            <router-link :to="{ path: '/buyer-billing' }">
                                <i class="fa fa-fw fa-money"></i>
                                <span>Buyer Billing</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'writer-billing' }">
                            <router-link :to="{ path: '/writer-billing' }">
                                <i class="fa fa-fw fa-newspaper-o"></i>
                                <span>Writer Billing</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li class="header">MAIN NAVIGATION</li>
                <li :class="{ active: $route.name == 'Dashboard' }">
                    <router-link  :to="{ path: '/' }">
                      <i class="fa fa-dashboard"></i> <span> Dashboard</span>
                    </router-link>
                </li>

                <li v-if="user.isAdmin || isManager || isSeller" :class="{ active: $route.name == 'ExtDomain' || $route.name == 'mail-logs' || $route.name == 'mail-template', 'treeview': true, 'menu-open': $route.name == 'ExtDomain' || $route.name == 'mail-logs' || $route.name == 'mail-template' }">
                    <a href="#">
                        <i class="fa fa-search"></i>
                        <span>Search  Domains</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{ active: $route.name == 'ExtDomain' }">
                            <router-link :to="{ path: '/ext' }">
                                <i class="fa fa-fw fa-reorder"></i>
                                <span>Summary</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'mail-logs' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/mail-logs' }">
                                <i class="fa fa-fw fa-square"></i> <span>Mail Logs</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'mail-template' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/mail-template' }">
                                <i class="fa fa-fw fa-envelope-o"></i> <span>Mail Template</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li :class="{ active: $route.name == 'articles' || $route.name == 'articles-edit'}">
                    <router-link :to="{ path: '/articles' }">
                        <i class="fa fa-file-text-o"></i>
                        <span>Article</span>
                        <span class="pull-right-container"></span>
                    </router-link>
                </li>

                <li v-if="isSeller || user.isAdmin || isManager" :class="{ active: $route.name == 'publisher' || $route.name == 'followup-sales' || $route.name == 'incomes', 'treeview': true, 'menu-open': $route.name == 'publisher' || $route.name == 'followup-sales' || $route.name == 'incomes'}">
                    <a href="#">
                        <i class="fa fa-fw fa-user-o"></i>
                        <span>Seller</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{ active: $route.name == 'publisher' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/publisher' }">
                                <i class="fa fa-fw fa-reorder"></i>
                                <span>List Publisher</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'followup-sales' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/followup-sales' }">
                                <i class="fa fa-fw fa-share"></i>
                                <span>Follow up Sale</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'incomes' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/incomes' }">
                                <i class="fa fa-fw fa-dollar"></i>
                                <span>Incomes</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                    </ul>

                </li>

                <li v-if="isBuyer || user.isAdmin || isManager" :class="{ active: $route.name == 'BackLink' || $route.name == 'list-backlinks' || $route.name == 'purchase', 'treeview': true, 'menu-open': $route.name == 'BackLink' || $route.name == 'list-backlinks' || $route.name == 'purchase' }">
                    <a href="#">
                        <i class="fa fa-fw fa-money"></i>
                        <span>Buyer</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{ active: $route.name == 'list-backlinks' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/list-backlinks' }">
                                <i class="fa fa-fw fa-reorder"></i>
                                <span>List Backlinks to Buy</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'BackLink' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/followup-backlinks' }">
                                <i class="fa fa-fw fa-share"></i>
                                <span>Follow up Backlinks</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                        <li :class="{ active: $route.name == 'purchase' }">
                            <router-link class="page-sidebar__item" :to="{ path: '/purchase' }">
                                <i class="fa fa-fw fa-btc"></i>
                                <span>Purchase</span>
                                <span class="pull-right-container"></span>
                            </router-link>
                        </li>
                    </ul>

                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
</template>

<script>

import { mapActions, mapState } from 'vuex';
export default {
    name: 'AppAside',
    data() {
        return {
            isSeller: false,
            isBuyer: false,
            isManager: false,
        }
    },
    created() {
        this.checkAccountType()
    },
    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
        }),

    },

    methods: {
        checkAccountType() {
            let that = this.user

            // not employee
            if( that.user_type ){
                if( that.user_type.type == 'Seller' ){
                    this.isSeller = true;
                }

                if( that.user_type.type == 'Buyer' ){
                    this.isBuyer = true;
                }
            }

            // for emaployee with a role of seller/buyer
            if( that.role.description == 'Buyer' ){
                this.isBuyer = true;
            }

            if( that.role.description == 'Seller' ){
                this.isSeller = true;
            }

            if( that.role.description == 'Manager' ){
                this.isManager = true;
            }
        },
    },

}
</script>
