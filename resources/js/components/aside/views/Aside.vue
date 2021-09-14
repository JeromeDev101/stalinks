<template>
    <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link navbar-light logo-switch">
            <img src="../../../../images/stalinks2.png" alt="Stalinks Logo" class="brand-image-xl img-fluid">
        </a>

        <!-- Sidebar -->
        <div class="sidebar" style="overflow-x: hidden;">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar text-sm nav-flat nav-legacy nav-compact nav-child-indent flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-header"
                        v-if="user.isAdmin || (user.isOurs === 0 && (isManager || isSeller || isBuyer || isQc))">
                        ADMIN
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/system' }"
                            :class="{ 'active': $route.name == 'system' }"
                        >
                            <img src="../../../../images/admin-settings.png"/>
                            <span>Admin Settings</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/tools' }"
                            :class="{ 'active': $route.name == 'tools' }"
                        >
                            <img src="../../../../images/admin-settings.png"/>
                            <span>Tools</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin || (user.isOurs === 0 && (isQc || isBuyer || isQcBilling || isQcSeller || isSeller))">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/dashboard' }"
                            :class="{ active: $route.name == 'dashboard' }"
                        >
                            <img src="../../../../images/dashboard.png"/>
                            <span>Dashboard</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/users' }"
                            :class="{ active: $route.name == 'List User' }"
                        >
                            <img src="../../../../images/team.png"/>
                            <span>Team</span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin ||
                            (user.isOurs == 0 && (isManager || isSeller || isQc || isQcBilling || isQcSeller || isQcBuyer ))">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/mail-logs' }"
                            :class="{ active: $route.name == 'mail-logs' }"
                        >
                            <img src="../../../../images/mail.png"/>
                            <span>Mail Logs</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/logs' }"
                            :class="{ active: $route.name == 'logs' }"
                        >
                            <img src="../../../../images/system-logs.png"/>
                            <span>System Logs</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                        user.isAdmin ||
                            (user.isOurs == 0 && (isManager || isSeller))
                    ">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/ext/alexa' }"
                            :class="{ active: $route.name == 'AlexaDomain' }"
                        >
                            <img src="../../../../images/alexa.png"/>
                            <span>Get Alexa</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                        user.isAdmin ||
                            (user.isOurs == 0 && (isManager || isSeller || isQc || isQcBilling || isQcSeller || isQcBuyer ))
                    ">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/generate-list' }"
                            :class="{ active: $route.name == 'generate-list' }"
                        >
                            <i class="fas fa-bars nav-icon"></i>
                            <span>Generate List</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                        user.isAdmin ||
                            (user.role_id == 3 && user.isOurs == 0) ||
                            (user.role_id == 5 && user.isOurs == 0) ||
                            (user.role_id == 6 && user.isOurs == 0) ||
                            (user.role_id == 7 && user.isOurs == 0) ||
                            (user.role_id == 8 && user.isOurs == 0)
                    ">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/accounts' }"
                            :class="{ active: $route.name == 'Registration' }"
                        >
                            <img src="../../../../images/registration.png"/>
                            <span>Registration Accounts</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                        user.isAdmin ||
                            (user.isOurs == 0 &&
                                (isManager || isSeller || isPostingWriter))
                    ">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/articles-list' }"
                            :class="{ active: $route.name == 'articles-list' }"
                        >
                            <img src="../../../../images/article.png"/>
                            <span>Article</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="user.isAdmin">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/overall-incomes' }"
                            :class="{ active: $route.name == 'overall-incomes' }"
                        >
                            <img src="../../../../images/incomes.png"/>
                            <span>Incomes</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                        user.isAdmin ||
                            ((isManager ||
                                    isSeller ||
                                    isBuyer ||
                                    isPostingWriter ||
                                    isQc || isQcSeller ||
                                     isQcBilling))
                    "
                        :class="{
                        active:
                            $route.name == 'seller-billing' ||
                            $route.name == 'wallet-transaction' ||
                            $route.name == 'wallet-summary' ||
                            $route.name == 'writer-billing',
                        'menu-open':
                            $route.name == 'wallet-transaction' ||
                            $route.name == 'seller-billing' ||
                            $route.name == 'wallet-summary' ||
                            $route.name == 'writer-billing'
                    }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/billing.png"/>
                            <p>
                                Billing
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"
                                v-if="isSeller || user.isAdmin || isQc || isQcSeller ||
                                     isQcBilling">
                                <router-link :to="{ path: '/seller-billing' }"
                                             class="nav-link"
                                             :class="{ active: $route.name == 'seller-billing' }">
                                    <i class="far fa-user nav-icon"></i>
                                    <span>Seller Billing</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                            <li class="nav-item"
                                v-if="isPostingWriter ||
                                user.isAdmin || isQc || isQcSeller ||
                                         isQcBilling">
                                <router-link :to="{ path: '/writer-billing' }" class="nav-link"
                                             :class="{ active: $route.name == 'writer-billing' }">
                                    <i class="far fa-newspaper nav-icon"></i>
                                    <span>Writer Billing</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                            <li class="nav-item"
                                v-if="user.isAdmin ||
                                isManager || isBuyer || isQc || isQcSeller ||
                                         isQcBilling">
                                <router-link :to="{ path: '/wallet-transaction' }" class="nav-link"
                                             :class="{
                                    active: $route.name == 'wallet-transaction'
                                }">
                                    <i class="far fa-money-bill-alt"></i>
                                    <span>Wallet Transaction</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">
                        MAIN NAVIGATION
                    </li>

                    <li class="nav-item">
                        <router-link :to="{ path: '/' }" class="nav-link"
                                     :class="{ active: $route.name == 'Dashboard' }">
                            <img src="../../../../images/dashboard.png"/>
                            <span> Dashboard</span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                            user.isAdmin ||
                                (user.isOurs == 0 &&
                                    (isManager || isSeller || isQc))
                        "
                        :class="{
                            active:
                                $route.name == 'Sent' ||
                                $route.name == 'Starred' ||
                                $route.name == 'Trash' ||
                                $route.name == 'Inbox' ||
                                $route.name == 'url-prospect' ||
                                $route.name == 'mail-logs' ||
                                $route.name == 'mail-template',
                            'menu-open':
                                $route.name == 'url-prospect' ||
                                $route.name == 'mail-logs' ||
                                $route.name == 'mail-template' ||
                                $route.name == 'Inbox' ||
                                $route.name == 'Sent' ||
                                $route.name == 'Trash' ||
                                $route.name == 'Starred'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/search-domains.png"/>
                            <p>
                                Search Domains
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link :to="{ path: '/url-prospect' }" class="nav-link"
                                             :class="{ active: $route.name == 'ExtDomain' }">
                                    <i class="fas fa-bars"></i>
                                    <span>URL Prospect</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>

                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/mails/inbox' }"
                                    :class="{
                                    active:
                                        $route.name == 'Inbox' ||
                                        $route.name == 'Sent' ||
                                        $route.name == 'Starred' ||
                                        $route.name == 'Trash'
                                }"
                                >
                                    <i class="fas fa-envelope-open"></i>
                                    <span>Mails</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <router-link
                            :to="{ path: '/articles' }"
                            class="nav-link"
                            :class="{ active: $route.name == 'articles' }"
                        >
                            <img src="../../../../images/article.png"/>
                            <span>Article</span>
                            <span class="pull-right-container"></span>
                        </router-link>
                    </li>

                    <li class="nav-item"
                        v-if="
                            isSeller ||
                                user.isAdmin ||
                                isManager ||
                                isPostingWriter ||
                                isQc
                        "
                        :class="{
                            active:
                                $route.name == 'publisher' ||
                                $route.name == 'followup-sales' ||
                                $route.name == 'incomes',
                            'menu-open':
                                $route.name == 'publisher' ||
                                $route.name == 'followup-sales' ||
                                $route.name == 'incomes'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/seller.png"/>
                            <p>
                                Seller
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/publisher' }"
                                    :class="{ active: $route.name == 'publisher' }"
                                >
                                    <i class="fa fa-fw fa-reorder"></i>
                                    <span>List Publisher</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>

                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/followup-sales' }"
                                    :class="{ active: $route.name == 'followup-sales' }"
                                >
                                    <i class="fa fa-fw fa-share"></i>
                                    <span>Follow up Sale</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>

                            <li class="nav-item">
                                <router-link
                                    class="page-sidebar__item"
                                    :to="{ path: '/incomes' }"
                                    :class="{ active: $route.name == 'incomes' }"
                                >
                                    <i class="fa fa-fw fa-dollar"></i>
                                    <span>Incomes</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item"
                        v-if="
                            isBuyer ||
                                user.isAdmin ||
                                (user.isOurs == 0 &&
                                    (isManager || isPostingWriter || isQc))
                        "
                        :class="{
                            active:
                                $route.name == 'BackLink' ||
                                $route.name == 'list-backlinks' ||
                                $route.name == 'purchase',
                            'menu-open':
                                $route.name == 'BackLink' ||
                                $route.name == 'list-backlinks' ||
                                $route.name == 'purchase'
                        }">
                        <a href="#" class="nav-link">
                            <img src="../../../../images/buyer.png"/>
                            <p>
                                Buyer
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/list-backlinks' }"
                                    :class="{ active: $route.name == 'list-backlinks' }"
                                >
                                    <i class="fa fa-fw fa-reorder"></i>
                                    <span>List Backlinks to Buy</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>

                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/followup-backlinks' }"
                                    :class="{ active: $route.name == 'BackLink' }"
                                >
                                    <i class="fa fa-fw fa-share"></i>
                                    <span>Follow up Backlinks</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>

                            <li class="nav-item">
                                <router-link
                                    class="nav-link"
                                    :to="{ path: '/purchase' }"
                                    :class="{ active: $route.name == 'purchase' }"
                                >
                                    <i class="fa fa-fw fa-btc"></i>
                                    <span>Purchase</span>
                                    <span class="pull-right-container"></span>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="{ path: '/help' }"
                            :class="{
                        active:
                            $route.name == 'help' ||
                            $route.name == 'seller-guide-1' ||
                            $route.name == 'seller-guide-2' ||
                            $route.name == 'seller-guide-3' ||
                            $route.name == 'seller-guide-4'
                    }"
                        >
                            <i class="fa fa-question-circle"></i>
                            <span>Help</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
<!--        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">-->
<!--            <div class="os-scrollbar-track">-->
<!--                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">-->
<!--            <div class="os-scrollbar-track">-->
<!--                <div class="os-scrollbar-handle" style="height: 69.1117%; transform: translate(0px, 0px);"></div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="os-scrollbar-corner"></div>-->
        <!-- /.sidebar -->
    </aside>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    name : "AppAside",
    data() {
        return {
            isSeller : false,
            isBuyer : false,
            isManager : false,
            isPostingWriter : false,
            isQc : false,
            isQcBuyer : false,
            isQcSeller : false,
            isQcBilling : false,
        };
    },
    created() {
        this.checkAccountType();
    },
    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser
        })
    },

    methods : {
        checkAccountType() {
            let that = this.user;

            // not employee
            if (that.user_type) {
                if (that.user_type.type == "Seller") {
                    this.isSeller = true;
                }

                if (that.user_type.type == "Buyer") {
                    this.isBuyer = true;
                }
            }

            // checking role type
            if (that.role.id == 5) {
                this.isBuyer = true;
            }

            if (that.role.id == 6) {
                this.isSeller = true;
            }

            if (that.role.id == 7) {
                this.isManager = true;
            }

            if (that.role.id == 4) {
                this.isPostingWriter = true;
            }

            if (that.role.id == 8) {
                this.isQc = true;
            }

            if (that.role.id == 9) {
                this.isQcBilling = true;
            }

            if (that.role.id == 10) {
                this.isQcSeller = true;
            }
        }
    }
};
</script>
