<template>
    <nav class="main-header navbar navbar-expand main-header-custom">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav" style="margin-left: 600px">
            <li
                v-if="isBuyer"
                class="nav-item"
            >
                <a href="#" class="nav-link">
                    Credit: <strong>$ {{ money.credit }}</strong>
                </a>
            </li>

            <li class="nav-item">
                <button
                    v-if="user.registration && user.registration.is_sub_account === 0 && user.registration.account_validation === 'valid' && user.role_id === 5"
                    class="btn btn-round btn-success"
                    data-toggle="modal"
                    data-target="#modal-add-wallet-header"><i
                    class="fa fa-plus"></i> Add Credit
                </button>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="flag-icon"
                       :class="pageLanguage === 'en' ? 'flag-icon-us' : 'flag-icon-' + pageLanguage"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0">
                    <a href="#" class="dropdown-item"
                       :class="{'active' : pageLanguage === 'en'}"
                       @click="pageLanguage = 'en'">
                        <i class="flag-icon flag-icon-us mr-2"></i> English
                    </a>
                    <a href="#" class="dropdown-item"
                       :class="{'active' : pageLanguage === 'jp'}"
                       @click="pageLanguage = 'jp'">
                        <i class="flag-icon flag-icon-jp mr-2"></i> Japan
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown notification-dropdown">
                <a
                    class="nav-link notification-dropdown-toggle"
                    data-toggle="dropdown"
                    aria-expanded="false"

                    @click="notificationsSeen">

                    <i class="far fa-bell"></i>
                    <span
                        class="badge badge-danger navbar-badge"
                        v-if="notifications.new_notifications >= 1">

                        {{ notifications.new_notifications }}
                    </span>
                </a>
                <div
                    class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    style="left: inherit; right: 0px; max-width: 750px;">

                    <p class="m-2 text-center">
                        {{ notifications.new_notifications }}
                        New Notifications
                    </p>

                    <ul class="p-0" style="list-style-type: none; overflow-wrap: break-word; width: 750px">
                        <li
                            v-for="notification in notifications.data"
                            class="border border-bottom p-3"
                            style="">

                            <small v-if="notification.read_at === null" class="badge badge-primary text-uppercase">new</small>

                            <span v-if="notification.data">
                                {{ JSON.parse(notification.data).message }}
                            </span>

                            <small class="text-primary d-block mt-2">
                                {{ notification.human_date }}
                            </small>
                        </li>
                    </ul>

                    <p v-if="notifications.all_notifications !== 0" class="m-2 text-center">
                        <button class="btn btn-primary btn-sm" @click="viewAllNotifications()">
                            View All {{ notifications.all_notifications }} Notifications
                        </button>
                    </p>
                </div>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img :src="user.avatar" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{
                            user.username
                                                     }}({{
                            user.isOurs == 0 && user.role ? user.role.name : null
                                                     }})</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                    <!-- User image -->
                    <li class="user-header">
                        <img :src="user.avatar" class="img-circle elevation-2" alt="User Image">

                        <p>
                            {{ user.username }}
                            <small v-if="user.isOurs == 0">{{ user.role ? user.role.name : null }}</small>
                            <small v-else-if="user.isOurs == 1">{{ user.user_type ? user.user_type.type : "" }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <router-link
                            :to="{ path: `/profile/${user.id}` }"
                        >
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </router-link>
                        <a
                            href="#"
                            @click="logoutAndRedirect"
                            class="btn btn-default btn-flat float-right"
                        >Sign out</a
                        >
                    </li>
                </ul>
            </li>
        </ul>

        <!--        <ul class="nav navbar-nav">
                    <li
                        v-if="isBuyer"
                        style="margin-left:-400px;margin-bottom:-55px;"
                    >
                        <a href="#">
                            &lt;!&ndash; Deposit: <strong>$ {{ money.deposit }} <span ref="deposit"></span></strong>
                            &nbsp;
                            Wallet: <strong>$ {{ money.wallet }}</strong>
                            &nbsp; &ndash;&gt;
                            Credit: <strong>$ {{ money.credit }}</strong>
                        </a>
                        <button
                            v-if="user.registration.is_sub_account == 0 && user.registration.account_validation == 'valid'"
                            class="btn btn-round btn-success"
                            data-toggle="modal"
                            data-target="#modal-add-wallet-header"><i
                            class="fa fa-plus"></i> Add Credit
                        </button>
                    </li>

                    <li class="nav-item d-none d-sm-inline-block">
                        <select name="language" id="language" class="form-control" v-model="pageLanguage">
                            <option value="en">English</option>
                            <option value="jp">Japanese</option>
                        </select>
                    </li>

                    <li class="nav-item d-none d-sm-inline-block">
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
                                       v-for="notification in notifications.data">{{ notification.data.message }}</a>
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
                </ul>-->

        <!-- Modal Add Wallet -->
        <div class="modal fade"
             id="modal-add-wallet-header"
             tabindex="-1" role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true" style="z-index: 9999"
             ref="addWalletModal"
             data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Wallet Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetAddWalletModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                        <span v-if="messageForms.message != '' && !isPopupLoading"
                              :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                            {{ messageForms.message }}
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-if="step
                            == 0">
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
                                    <input type="number"
                                           class="form-control"
                                           name=""
                                           placeholder="0.00"
                                           v-model="updateModel.amount_usd">
                                    <span v-if="messageForms.errors.amount_usd"
                                          v-for="err in messageForms.errors.amount_usd"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.payment_type}">
                                    <label for="">Payment Via</label>
                                    <select name="" class="form-control" v-model="updateModel.payment_type">
                                        <option v-for="option in listPayment.data"
                                                v-bind:value="option.id"
                                                v-if="option.receive_payment === 'yes'">
                                            {{ option.type }}
                                        </option>
                                    </select>
                                    <span v-if="messageForms.errors.payment_type"
                                          v-for="err in messageForms.errors.payment_type"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div v-if="updateModel.payment_type === 3" class="col-md-12">
                                <img src="storage/btc.jpg" width="250px" alt="" class="mx-auto d-block">
                                <p class="text-center">{{ listPayment.data[2].address }}</p>
                            </div>

                            <div v-if="updateModel.payment_type === 7" class="col-md-12">
                                <img src="storage/eth.jpg" width="250px" alt="" class="mx-auto d-block">
                                <p class="text-center">{{ listPayment.data[3].address }}</p>
                            </div>

                            <div v-if="updateModel.payment_type === 6" class="col-md-12">
                                <img src="storage/usdt.jpg" width="250px" alt="" class="mx-auto d-block">
                                <p class="text-center">{{ listPayment.data[4].address }}</p>
                            </div>

                            <div v-if="updateModel.payment_type === 2" class="col-md-12">
                                <p class="text-center">
                                    Skrill E-mail: {{ listPayment.data[1].email }}
                                </p>
                            </div>

                            <div class="col-md-12"
                                 v-if="updateModel.payment_type != 1">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.file}">
                                    <label for="">Proof of Documents</label>
                                    <input type="file"
                                           class="form-control"
                                           enctype="multipart/form-data"
                                           ref="proof"
                                           name="file">
                                    <small class="text-muted">Note: It must be image type. ( jpg, jpeg, gif and png
                                                              )</small><br/>
                                    <span v-if="messageForms.errors.file"
                                          v-for="err in messageForms.errors.file"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="col-md-6"
                                 v-if="updateModel.payment_type == 1">
                                <div class="form-group">
                                    <label
                                        for="">Your
                                               Paypal
                                               Region
                                    </label>
                                    <select name=""
                                            id=""
                                            class="form-control"
                                            v-model="updateModel.payment_region">
                                        <option
                                            value="domestic">Hong Kong
                                        </option>
                                        <option
                                            value="international">Non-Hong Kong
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12"
                                 v-if="updateModel.payment_type == 1">
                                <b>Total Amount: ${{
                                        updateModel.payment_region == 'international' ? internationalTotalPaymentAmount : domesticTotalPaymentAmount
                                   }}</b>
                            </div>

                            <div class="col-md-12 text-center">
                                <img v-for="img in paymentImages" class="payment-logo" :src="'storage/' + img.path" alt="">
                            </div>
                        </div>

                        <div class="row" v-else-if="step
                        == 1">
                            <div class="col-sm-12">
                                <div id="smart-button-container">
                                    <div style="text-align: center;">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer"
                         v-if="step == 0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"
                                @click="nextPage"
                                class="btn btn-primary">Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Wallet -->


        <!-- Modal All Notifications -->
        <div
            style="z-index: 9999"
            role="dialog"
            tabindex="-1"
            class="modal fade"
            ref="addWalletModal"
            id="modal-all-notifications"
            aria-hidden="true"
            data-backdrop="static"
            aria-labelledby="modelTitleId">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">All Notifications</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetAddWalletModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div v-if="allNotifications.hasOwnProperty('data')">
                            <table id="all-notifications-table" class="table table-hover table-bordered">
                                <tbody>
                                <tr v-for="(notification, index) in allNotifications.data" :key="index">
                                    <td>
                                        <span v-if="notification.data">
                                            {{ JSON.parse(notification.data).message }}
                                        </span>

                                        <small class="text-primary d-block mt-2">
                                            {{ notification.human_date }}
                                        </small>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <pagination
                                :limit="8"
                                :data="allNotifications"
                                @pagination-change-page="viewAllNotifications">

                            </pagination>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of modal All notifications -->
    </nav>
</template>

<script>
import axios from "axios";
import {mapActions, mapState, mapGetters} from "vuex";
import Cookies from "js-cookie";

export default {
    name : "AppHeader",

    data() {
        return {
            isBuyer : false,
            userInfo : {},
            error : null,
            money : {
                wallet : "",
                credit : "",
                deposit : "",
                total_paid : "",
                total_purchased : "",
                total_purchased_paid : "",
            },
            isPopupLoading : false,
            updateModel : {
                user_id_buyer : '',
                payment_type : '',
                amount_usd : '',
                payment_region : 'domestic',
            },
            step : 0,
            pageLanguage : 'en',
            paymentImages : null,

            allNotifications: []
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

        channel.bind('user.notify', (e) => {
            this.getNotifications(this.user.id);
        });

        this.getPaymentTypeImages();

        // prevent notifiation dropdown from closing when clicked
        $(document).on('click', '.notification-dropdown .dropdown-menu', function (e) {
            e.stopPropagation();
        });
    },

    watch : {
        pageLanguage(newvalue, oldValue) {
            this.$i18n.locale = newvalue;
        },
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            notifications : state =>
                state.storeNotification.notifications,
            messageForms : state => state.storeWalletTransaction.messageForms,
            listPayment : state => state.storeWalletTransaction.listPayment,
        }),

        domesticTotalPaymentAmount() {
            var total = (+this.updateModel.amount_usd +
                    3e-1) /
                (1 - (39e-1 / 100));

            return total.toFixed(2);
        },

        internationalTotalPaymentAmount() {
            var total = (+this.updateModel.amount_usd +
                    3e-1) /
                (1 - (44e-1 / 100));

            return total.toFixed(2);
        },
    },

    methods : {
        ...mapActions({
            logout : "auth/logout",
            getNotifications : "getUserNotifications",
            seenNotifications : "seenUserNotifications",
        }),

        getPaymentTypeImages() {
            axios.get('/api/payments/image')
            .then((response) => {
                this.paymentImages = response.data;
            });
        },

        initPaypalButtons() {
            let vm = this;
            paypal.Buttons({
                style : {
                    shape : 'rect',
                    color : 'black',
                    layout : 'vertical',
                    label : 'pay',

                },

                createOrder : function (data, actions) {
                    return axios.post('/api/paypal/order/create', {
                        amount :
                            vm.updateModel.payment_region ===
                            'international' ?
                                vm.internationalTotalPaymentAmount : vm.domesticTotalPaymentAmount,
                    })
                        .then(response => {
                            return response.data.result;
                        })
                        .then(data => {
                            return data.id;
                        });
                },

                onApprove : function (data, actions) {
                    return axios.post('/api/paypal/order/' +
                        data.orderID
                        + '/capture')
                        .then(response => {
                            vm.submitPay(response);
                        });
                },

                onError : function (err) {
                    swal.fire(
                        'Error',
                        'There was an error on processing your payment.',
                        'error',
                    )
                },
            }).render('#paypal-button-container');
        },

        nextPage() {
            if (this.updateModel.payment_type === 1) {
                this.step = 1;

                setTimeout(this.initPaypalButtons, 300);
            } else {
                this.submitPay();
            }
        },

        async submitPay(paypalPayload) {
            let loading =
                this.$loading.show();
            this.formData = new FormData();
            this.formData.append('payment_type', this.updateModel.payment_type);
            this.formData.append('amount_usd', this.updateModel.amount_usd);
            this.formData.append('user_id_buyer',
                this.user.id);

            if (this.updateModel.payment_type !== 1) {
                this.formData.append('file', this.$refs.proof.files[0]);
            } else {
                this.formData.append('payload',
                    JSON.stringify(paypalPayload));
            }

            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddWallet', this.formData)
            this.isPopupLoading = false;

            if (this.messageForms.action === 'success') {

                this.liveGetWallet();

                $("#modal-add-wallet-header").modal('hide');
                this.updateModel = {
                    user_id_buyer : '',
                    payment_type : '',
                    amount_usd : '',
                }

                this.step = 0;
                loading.hide();

                swal.fire(
                    'Success',
                    'Successfully Added',
                    'success',
                )

                this.$refs.proof.value = '';
            }

            this.liveGetWallet();
            loading.hide();
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
            this.$router.push({name : "login"});
        },

        liveGetWallet() {
            let app = this

            if (this.user.role_id == 5) {
                axios
                    .get("api/wallet-credit")
                    .then(function (res) {
                        let result = res.data;
                        console.log(result)
                        if (typeof result === "object") {
                            localStorage.setItem(
                                "wallet",
                                JSON.stringify(res.data),
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
        },

        resetAddWalletModal() {
            this.updateModel = {
                user_id_buyer : '',
                    payment_type : '',
                    amount_usd : '',
                    payment_region : 'domestic',
            };

            this.step = 0;
        },

        viewAllNotifications(page = 1) {
            $("#modal-all-notifications").modal('show');

            // close notification dropdown
            $(".notification-dropdown .notification-dropdown-toggle").dropdown('toggle')

            let loader = this.$loading.show();

            axios.get('/api/notifications-all/' + this.user.id,{
                params: {
                    page: page
                }
            })
            .then((res) => {
                this.allNotifications = res.data.data
                loader.hide();
            })
            .catch((err) => {
                console.log(err)
                loader.hide();
            })
        },
    },
};
</script>
<style>
.modal-backdrop {
    z-index: -1;
}

.payment-logo {
    height: 40px;
}
</style>
