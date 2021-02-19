<template>
    <header class="main-header main-header-custom">
        <a href="#" class="logo logo-custom" style="position:fixed;">
            <img class="" src="../../../images/stalinks.png" alt="User Image" />
        </a>
        <nav class="navbar navbar-static-top">
            <a
                href="#"
                class="sidebar-toggle"
                data-toggle="push-menu"
                role="button"
            >
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li
                        v-if="isBuyer"
                        style="margin-left:-400px;margin-bottom:-55px;"
                    >
                        <a href="#">
                            <!-- Deposit: <strong>$ {{ money.deposit }} <span ref="deposit"></span></strong>
                            &nbsp;
                            Wallet: <strong>$ {{ money.wallet }}</strong>
                            &nbsp; -->
                            Credit: <strong>$ {{ money.credit }}</strong>
                        </a>
                    </li>

                    <li style="margin-left:-50px;margin-bottom:-55px;">
                        <a
                            href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                            @click="notificationsSeen"
                            >
                            <i
                                class="fa fa-bell-o"
                                v-if="notifications.new_notifications < 1"></i>
                            <i
                                class="fa fa-bell text-red"
                                v-else></i>
                        </a>

                        <ul class="dropdown-menu notification-menu-custom overflow-auto" style="height: 300px;">
                            <li>
                                <div class="vertical-menu">
                                    <a href="#"
                                       v-for="notification in notifications.data">{{ notification.notification }}</a>
                                </div>

                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <li class="dropdown user user-menu">
                        <a
                            href="#"
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <img
                                :src="user.avatar"
                                class="user-image"
                                alt="User Image"
                            />
                            <span class="hidden-xs">{{ user.username }}</span>
                            <span class="hidden-xs" v-if="user.isOurs == 0"
                                >({{ user.role ? user.role.name : null }})</span
                            >
                            <span class="hidden-xs" v-if="user.isOurs == 1"
                                >({{
                                    user.user_type ? user.user_type.type : ""
                                }})</span
                            >
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img
                                    :src="user.avatar"
                                    class="img-circle"
                                    alt="User Image"
                                />
                                <p>{{ user.username }}</p>
                                <p
                                    style="margin-top:-5%"
                                    v-if="user.isOurs == 0"
                                >
                                    {{ user.role ? user.role.name : null }}
                                </p>
                                <p
                                    style="margin-top:-5%"
                                    v-if="user.isOurs == 1"
                                >
                                    {{
                                        user.user_type
                                            ? user.user_type.type
                                            : ""
                                    }}
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <router-link
                                        :to="{ path: `/profile/${user.id}` }"
                                    >
                                        <a
                                            href="#"
                                            class="btn btn-default btn-flat"
                                            >Profile</a
                                        >
                                    </router-link>
                                </div>
                                <div class="pull-right">
                                    <a
                                        href="#"
                                        @click="logoutAndRedirect"
                                        class="btn btn-default btn-flat"
                                        >Sign out</a
                                    >
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
import axios from "axios";
import { mapActions, mapState, mapGetters } from "vuex";
import Cookies from "js-cookie";
export default {
    name: "AppHeader",

    data() {
        return {
            isBuyer: false,
            userInfo: {},
            error: null,
            money: {
                wallet: "",
                credit: "",
                deposit: "",
                total_paid: "",
                total_purchased: "",
                total_purchased_paid: "",
            },
        };
    },

    created() {
        this.$root.$refs.AppHeader = this;
        this.checkAccountType();
    },

    mounted() {
        this.liveGetWallet();
        this.getNotifications(this.user.id);

        pusher.logToConsole = true;

        const channel = pusher.subscribe('private-user.' +
            this.user.id);

        channel.bind('buyer.bought', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('backlink.live', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('seller.receives.order', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('backlink.status.changed', (e) => {
            this.getNotifications(this.user.id);
        })
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            notifications: state => state.storeNotification.notifications
        })
    },

    methods: {
        ...mapActions({
            logout: "auth/logout",
            getNotifications: "getUserNotifications",
            seenNotifications: "seenUserNotifications"
        }),

        calcSum(total, num) {
            return total + num;
        },

        async logoutAndRedirect() {
            await this.$store.dispatch("logout");
            localStorage.clear();
            Cookies.remove("vuex");
            this.$router.push({ name: "login" });
        },

        liveGetWallet() {
            let app = this

            if (this.user.role_id == 5) {
                let wallet = {};

                axios
                    .get("api/wallet-credit")
                    .then(function(res) {
                        let result = res.data;
                        if (typeof result === "object") {
                            localStorage.setItem(
                                "wallet",
                                JSON.stringify(res.data)
                            );
                        }

                        wallet = JSON.parse(localStorage.getItem("wallet"));

                        if(wallet){
                            app.money = Number.isInteger(wallet.wallet) ? wallet : app.money;
                        }
                    })
                    .catch(error => console.log(error));
            }
        },

        checkAccountType() {
            let that = this.user;

            // not employee
            if (that.user_type) {
                if (that.user_type.type == "Buyer") {
                    this.isBuyer = true;
                }
            }

            // for emaployee with a role of seller/buyer
            if (that.role.description == "Buyer") {
                this.isBuyer = true;
            }
        },

        notificationsSeen() {
            if (this.notifications.new_notifications !==
                0) {
                this.seenNotifications(this.user.id);
                this.getNotifications(this.user.id);
            }
        }
    }
};
</script>
