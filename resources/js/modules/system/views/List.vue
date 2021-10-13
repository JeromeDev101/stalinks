<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin Settings</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="col-sm-12">
                    <language :message-forms="messageForms"></language>
                </div>

                <div class="col-sm-12">
                    <mail-access :is-popup-loading="isPopupLoading" :message-forms="messageForms"></mail-access>
                </div>

                <div class="col-sm-12">
                    <payment-method :is-popup-loading="isPopupLoading" :message-forms="messageForms"></payment-method>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="col-sm-12">
                    <ahref></ahref>
                </div>

                <div class="col-sm-12">
                    <Formula :message-forms="messageForms"></Formula>
                </div>

                <div class="col-sm-12" v-for="(configs, typeConfig) in configList.data" v-if="typeConfig !== 'crypto'">
                    <config :configs="configs" :message-forms="messageForms" :type-config="typeConfig"></config>
                </div>

                <div class="col-sm-12">
                    <Usdt v-on:getconfiglist="getConfigList"></Usdt>
                </div>

                <div class="col-sm-12">
                    <btc v-on:getconfiglist="getConfigList"></btc>
                </div>

                <div class="col-sm-12">
                    <eth v-on:getconfiglist="getConfigList"></eth>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import _ from 'lodash';
import Language from "./Language";
import MailAccess from "./MailAccess";
import PaymentMethod from "./PaymentMethod";
import Ahref from "./Ahref";
import Formula from "./Formula";
import Config from "./Config";
import Usdt from "./Usdt";
import Btc from "./Btc";
import Eth from "./Eth";

export default {
    name : 'System',

    components : {
        Language,
        MailAccess,
        PaymentMethod,
        Ahref,
        Formula,
        Config,
        Usdt,
        Btc,
        Eth
    },

    data() {
        return {
            countryModel : {
                id : 0,
                name : '',
                code : '',
            },

            countryUpdate : {
                id : 0,
                name : '',
                code : ''
            },

            skrillModel : {
                email : ''
            },

            filterModel : {
                name : this.$route.query.name || '',
                code : this.$route.query.name || '',
                name_temp : this.$route.query.name_temp || '',
                code_temp : this.$route.query.code_temp || '',
                page : this.$route.query.page || 0,
                per_page : this.$route.query.per_page || 10,
                paginate : 1
            },

            listPageOptions : [
                5,
                10,
                25,
                50,
                100
            ],
            isLoadingTable : false,
            isPopupLoading : false,
            formData : null
        };
    },

    async created() {
        await this.$store.dispatch('actionCheckAdminCurrentUser', {vue : this});
        if (!this.user.isAdmin) {
            window.location.href = '/';
            return;
        }

        this.getConfigList();
        // this.getCountryList({
        //     params: this.filterModel
        // });
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            countryList : state => state.storeSystem.countryList,
            paymentList : state => state.storeSystem.paymentList,
            messageForms : state => state.storeSystem.messageForms,
            configList : state => state.storeSystem.configList,
            Info : state => state.storeSystem.Info,
            formula : state => state.storeSystem.formula,
        }),
    },

    methods : {
        cryptoValue(code) {
            let val = _.find(this.configList.data.crypto, function (o) {
                return o.code === code
            }).value;

            return val;
        },

        async getConfigList() {
            await this.$store.dispatch('actionGetConfigList');

            for (let configType in this.configList.data) {
                this.isLoadingConfig[configType] = false;
            }
        },

        async getCountryList(params) {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetCountryList', params);
            this.isLoadingTable = false;
        },

        async doSearchList() {
            let that = this;
            that.filterModel.name = that.filterModel.name_temp;
            that.filterModel.code = that.filterModel.code_temp;

            this.$router.push({
                query : that.filterModel,
            });

            this.getCountryList({
                params : that.filterModel
            });
        },

        clearModel() {
            this.countryModel = {
                id : 0,
                name : '',
                code : ''
            };
        },

        async goToPage(pageNum) {
            let that = this;
            that.filterModel.page = pageNum;
            this.$router.push({
                query : that.filterModel,
            });

            await this.getCountryList({
                params : that.filterModel
            });
        },

        doAdd() {
            this.$store.dispatch('clearMessageFormSystem');
        },

        doEdit(item) {
            this.$store.dispatch('clearMessageFormSystem');
            this.countryUpdate = JSON.parse(JSON.stringify(item))
        },

        async submitAdd() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddCountry', that.countryModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_country') {
                this.clearModel();
                this.getCountryList({
                    params : this.filterModel
                });
            }
        },

        async submitUpdate() {
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdateCountry', this.countryUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_country') {
                for (var index in this.countryList.data) {
                    if (this.countryList.data[index].id === this.countryUpdate.id) {
                        this.countryList.data[index] = this.countryUpdate;
                        break;
                    }
                }
            }
        },

        async saveListConfig(configs) {
            for (let index in configs) {
                await this.saveConfig(configs[index]);
            }
        },

        async saveConfig(config) {
            await this.$store.dispatch('actionUpdateConfig', config);
        },
    },
}
</script>
