<template>
    <!-- LANGUAGE LIST -->
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{ $t('message.IT.l_title') }}</h3>
            <div class="card-tools">
                <button
                    v-if="user.permission_list.includes('create-admin-settings-it')"
                    class="btn btn-success float-right"
                    data-toggle="modal"
                    data-target="#modal-add-language"

                    @click="clearMessageform">

                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="height: 650px; overflow: scroll;">
            <table class="table table-hover table-bordered table-striped rlink-table">
                <thead>
                <tr class="label-primary">
                    <th>#</th>
                    <th>{{ $t('message.IT.l_name') }}</th>
                    <th>{{ $t('message.IT.l_code') }}</th>
                    <th>{{ $t('message.IT.l_action') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in langaugeList.data" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.code }}</td>
                    <td>
                        <div class="btn-group">
                            <button
                                v-if="user.permission_list.includes('update-admin-settings-it')"
                                class="btn btn-default"
                                data-toggle="modal"
                                data-target="#modal-update-language"
                                :title="$t('message.IT.edit')"

                                @click="doEditLanguage(item)">

                                <i class="fa fa-fw fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Add Language -->
        <div class="modal fade" id="modal-add-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.IT.l_add_lang') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                            <label>{{ $t('message.IT.l_name') }}</label>
                            <input type="text" v-model="languageModel.name" class="form-control" required>
                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name"
                                  class="text-danger">{{ err }}</span>
                        </div>
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                            <label>{{ $t('message.IT.l_code') }}</label>
                            <input type="text" v-model="languageModel.code" class="form-control" required>
                            <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code"
                                  class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddLanguage" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add Language -->

        <!-- Update Language -->
        <div class="modal fade" id="modal-update-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.IT.l_update_lang') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                            <label>{{ $t('message.IT.l_name') }}</label>
                            <input type="text" v-model="languageUpdate.name" class="form-control" disabled>
                            <span
                                v-if="messageForms.errors.name"
                                v-for="err in messageForms.errors.name"
                                class="text-danger">

                                {{ err }}
                            </span>
                        </div>
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                            <label>{{ $t('message.IT.l_code') }}</label>
                            <input type="text" v-model="languageUpdate.code" class="form-control" required>
                            <span
                                v-if="messageForms.errors.code"
                                v-for="err in messageForms.errors.code"
                                class="text-danger">

                                {{ err }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.IT.close') }}
                        </button>
                        <button type="button" @click="submitUpdateLanguage" class="btn btn-primary">
                            {{ $t('message.IT.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Update Language -->

    </div>
    <!-- END LANGUAGE LIST -->
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "Language",

    props : [
        'messageForms'
    ],

    data() {
        return {
            languageUpdate : {
                id : '',
                name : '',
                code : '',
            },

            languageModel : {
                name : '',
                code : '',
            },
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            langaugeList : state => state.storeSystem.langaugeList,
        }),
    },

    mounted() {
        this.getLanguageList();
    },

    methods : {
        clearMessageform() {
            this.$emit('clearMessageForm');
        },

        doEditLanguage(item) {
            this.$store.dispatch('clearMessageFormSystem');
            this.languageUpdate = JSON.parse(JSON.stringify(item))
        },

        async submitUpdateLanguage() {
            let self = this;
            await this.$store.dispatch('actionUpdateLanguage', this.languageUpdate);

            if (this.messageForms.action === 'update_language') {
                await swal.fire(
                    self.$t('message.IT.alert_success'),
                    self.$t('message.IT.alert_success_updated'),
                    'success'
                )
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }

            await this.getLanguageList();
        },

        async submitAddLanguage() {
            let self = this;
            await this.$store.dispatch('actionAddLanguage', this.languageModel);

            if (this.messageForms.action === 'saved_language') {
                await swal.fire(
                    self.$t('message.IT.alert_success'),
                    self.$t('message.IT.alert_success_added'),
                    'success'
                )
            } else {
                await swal.fire(
                    self.$t('message.draft.error'),
                    self.messageForms.message,
                    'error'
                )
            }

            await this.getLanguageList();

            this.languageModel = {
                name : '',
                code : '',
            }
        },

        async getLanguageList() {
            await this.$store.dispatch('actionGetLanguageList');
        },
    }
}
</script>

<style scoped>

</style>
