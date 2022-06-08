<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $t('message.finance.f_title') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <Formula :message-forms="messageForms"></Formula>
            </div>

            <div class="col-12" v-for="(configs, typeConfig) in configList.data" v-if="typeConfig === 'affiliate'">
                <config :configs="configs" :message-forms="messageForms" :type-config="typeConfig"></config>
            </div>

            <div class="col-sm-12">
                <payment-method :is-popup-loading="isPopupLoading" :message-forms="messageForms"></payment-method>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-sm-6">


            </div>

            <div class="col-sm-6">

                <div class="col-sm-12">
                    <Usdt v-on:getconfiglist="getConfigList"></Usdt>
                </div>

                <div class="col-sm-12">
                    <eth v-on:getconfiglist="getConfigList"></eth>
                </div>

            </div>
        </div> -->
    </div>
</template>

<script>
import PaymentMethod from "./PaymentMethod";
import Formula from "./Formula";
import Usdt from "./Usdt";
import Btc from "./Btc";
import Eth from "./Eth";
import {mapState} from "vuex";
import Config from "./Config";

export default {
    name : "Finance",

    data() {
        return {
            isPopupLoading : false,
        }
    },

    components : {
        PaymentMethod,
        Formula,
        Usdt,
        Btc,
        Eth,
        Config
    },

    created() {
        this.getConfigList();
    },

    computed : {
        ...mapState({
            messageForms : state => state.storeSystem.messageForms,
            configList : state => state.storeSystem.configList,

        }),
    },

    methods : {
        async getConfigList() {
            await this.$store.dispatch('actionGetConfigList');
        },
    }
}
</script>

<style scoped>

</style>
