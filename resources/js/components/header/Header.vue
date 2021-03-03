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
                        <button
                            v-if="user.registration.is_sub_account == 0"
                            class="btn btn-round btn-success"
                            data-toggle="modal" data-target="#modal-add-wallet"><i
                            class="fa fa-plus"></i>
                        </button>
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

            <!-- Modal Add Wallet -->
            <div class="modal fade" id="modal-add-wallet"
                 tabindex="-1" role="dialog"
                 aria-labelledby="modelTitleId"
                 aria-hidden="true" style="z-index: 9999">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Wallet Transaction</h5>
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!--                                    <div class="col-md-12">-->
                                <!--                                        <div :class="{'form-group': true, 'has-error': messageForms.errors.user_id_buyer}">-->
                                <!--                                            <label for="">User Buyer</label>-->
                                <!--                                            <select name="" class="form-control" v-model="updateModel.user_id_buyer">-->
                                <!--                                                <option value="">Select Buyer</option>-->
                                <!--                                                <option v-for="option in listBuyer.data" v-bind:value="option.id">-->
                                <!--                                                    {{ 'ID: ' + option.id + ' - Name: ' + option.name }}-->
                                <!--                                                </option>-->
                                <!--                                            </select>-->
                                <!--                                            <span v-if="messageForms.errors.user_id_buyer" v-for="err in messageForms.errors.user_id_buyer" class="text-danger">{{ err }}</span>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.amount_usd}">
                                        <label for="">Amount USD</label>
                                        <input type="number" class="form-control" name="" placeholder="0.00" v-model="updateModel.amount_usd">
                                        <span v-if="messageForms.errors.amount_usd" v-for="err in messageForms.errors.amount_usd" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                        <label for="">Payment Via</label>
                                        <select name="" class="form-control" v-model="updateModel.payment_type">
                                            <option v-for="option in listPayment.data" v-bind:value="option.id">
                                                {{ option.type }}
                                            </option>
                                        </select>
                                        <span v-if="messageForms.errors.payment_type" v-for="err in messageForms.errors.payment_type" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                        <label for="">Proof of Documents</label>
                                        <input type="file" class="form-control" enctype="multipart/form-data" ref="proof" name="file">
                                        <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png )</small><br/>
                                        <span v-if="messageForms.errors.file" v-for="err in messageForms.errors.file" class="text-danger">{{ err }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" @click="submitPay" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal Add Wallet -->
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
            isPopupLoading: false,
            updateModel: {
                user_id_buyer: '',
                payment_type: '',
                amount_usd: '',
            },
        };
    },

    created() {
        this.$root.$refs.AppHeader = this;
        this.checkAccountType();
        this.getListPaymentType();
    },

    beforeMount() {
        this.liveGetWallet();
    },

    mounted() {
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
        });

        channel.bind('buyer.debited', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('seller.paid', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('writer.new.article', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('writer.article.done', (e) => {
            this.getNotifications(this.user.id);
        });

        channel.bind('writer.paid', (e) => {
            this.getNotifications(this.user.id);
        });
    },

    computed: {
        ...mapState({
            user: state => state.storeAuth.currentUser,
            notifications: state =>
                state.storeNotification.notifications,
            messageForms: state => state.storeWalletTransaction.messageForms,
            listPayment: state => state.storeWalletTransaction.listPayment,
        })
    },

    methods: {
        ...mapActions({
            logout: "auth/logout",
            getNotifications: "getUserNotifications",
            seenNotifications: "seenUserNotifications"
        }),

        async submitPay() {
            this.formData = new FormData();
            this.formData.append('file', this.$refs.proof.files[0]);
            this.formData.append('payment_type', this.updateModel.payment_type);
            this.formData.append('amount_usd', this.updateModel.amount_usd);
            this.formData.append('user_id_buyer',
                this.user.id);

            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddWallet', this.formData)
            this.isPopupLoading = false;

            if( this.messageForms.action == 'success' ){

                $("#modal-add-wallet").modal('hide');
                this.updateModel = {
                    user_id_buyer: '',
                    payment_type: '',
                    amount_usd: '',
                }

                this.$refs.proof.value = '';

                swal.fire(
                    'Success',
                    'Successfully Added',
                    'success'
                )

            }

            this.liveGetWallet();
        },

        async getListPaymentType(params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },

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
                axios
                    .get("api/wallet-credit")
                    .then(function(res) {
                        let result = res.data;
                        console.log(result)
                        if (typeof result === "object") {
                            localStorage.setItem(
                                "wallet",
                                JSON.stringify(res.data)
                            );

                            app.money = res.data
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
<style>
    .modal-backdrop {
        z-index: -1;
    }
</style>
