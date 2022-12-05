<template>
    <div class="card card-outline card-secondary" v-if="typeConfig === 'affiliate'">
        <div class="card-header">
            <h3 class="card-title">{{ $t('message.IT.c_title') }} {{ typeConfig }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <form action="" role="form">
                <div v-for="(item) in configs" class="form-group">
                    <label>{{ item.name }}</label>
                    <input type="text" class="form-control" v-model="item.value" :placeholder="$t('message.IT.c_enter') + item.name">
                </div>
            </form>
        </div>

        <div class="card-footer">
            <button
                v-if="user.permission_list.includes('update-admin-settings-finance')"
                type="button"
                class="btn btn-primary"

                @click="submitSaveConfig(typeConfig)">

                {{ $t('message.IT.save') }}
            </button>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "Config",

    props : [
        'typeConfig',
        'configs',
        'messageForms'
    ],

    data() {
        return {
            isLoadingConfig : {
                alexa : false,
                ahrefs : false,
                crypto : false
            },
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            configList : state => state.storeSystem.configList,
        }),
    },

    methods : {
        async submitSaveConfig(configType) {
            let self = this;
            this.isLoadingConfig[configType] = true;
            await this.saveListConfig(this.configList.data[configType])
            this.isLoadingConfig[configType] = false;

            if (this.messageForms.action === 'updated_conf') {
                await swal.fire(
                    self.$t('message.IT.alert_saved'),
                    self.$t('message.IT.alert_saved_config'),
                    'success'
                )
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
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

<style scoped>

</style>
