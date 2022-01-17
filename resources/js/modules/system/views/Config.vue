<template>
    <div class="card card-outline card-secondary" v-if="typeConfig !== 'crypto'">
        <div class="card-header">
            <h3 class="card-title">Config {{ typeConfig }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <form action="" role="form">
                <div v-for="(item) in configs" class="form-group">
                    <label>{{ item.name }}</label>
                    <input type="text" class="form-control" v-model="item.value" :placeholder="'Enter ' + item.name">
                </div>
            </form>
        </div>

        <div class="card-footer">
            <button type="button" @click="submitSaveConfig(typeConfig)" class="btn btn-primary">Save</button>
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
            configList : state => state.storeSystem.configList,
        }),
    },

    methods : {
        async submitSaveConfig(configType) {
            this.isLoadingConfig[configType] = true;
            await this.saveListConfig(this.configList.data[configType])
            this.isLoadingConfig[configType] = false;

            if (this.messageForms.action === 'saved_config') {

            }

            alert('Saved configs success');
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
