<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $t('message.IT.it_title') }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-6">
                <div class="col-12">
                    <language :message-forms="messageForms"></language>
                </div>

                <div class="col-12">
                    <mail-access :is-popup-loading="isPopupLoading" :message-forms="messageForms"></mail-access>
                </div>
            </div>

            <div class="col-6">
                <div class="col-12">
                    <ahref></ahref>
                </div>

                <div class="col-12" v-for="(configs, typeConfig) in configList.data" v-if="typeConfig !== 'crypto' && typeConfig !== 'skrill' && typeConfig !== 'affiliate'">
                    <config :configs="configs" :message-forms="messageForms" :type-config="typeConfig"></config>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import Language from "./Language";
import MailAccess from "./MailAccess";
import Ahref from "./Ahref";
import Config from "./Config";

export default {
    name : "IT",

    components : {
        Language,
        MailAccess,
        Ahref,
        Config
    },

    created() {
        this.getConfigList();
    },

    data() {
        return {
            isPopupLoading : false,
        }
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
