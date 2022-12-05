<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">Language - Writer Price Rate</h3>
        </div>

        <div class="card-body" style="height: 650px; overflow: scroll;">
            <table class="table table-hover table-bordered table-striped rlink-table">
                <thead>
                <tr class="label-primary">
                    <th>ID</th>
                    <th>{{ $t('message.IT.l_name') }}</th>
                    <th>PPA Rate</th>
                    <th>PPW Rate</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in languageList.data" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ checkPriceRate(item.ppa_rate) }}</td>
                    <td>{{ checkPriceRate(item.ppw_rate) }}</td>
                    <td>
                        <button
                            v-if="user.permission_list.includes('update-admin-settings-finance')"
                            class="btn btn-default"
                            data-toggle="modal"
                            data-target="#modal-update-language-rates"
                            :title="$t('message.IT.edit')"

                            @click="updateLanguageRate(item)">

                            <i class="fa fa-fw fa-edit"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Update Language -->
        <div
            tabindex="-1"
            role="dialog"
            class="modal fade"
            aria-hidden="true"
            id="modal-update-language-rates"
            aria-labelledby="modal-update-language-rates">

            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Language Rates</h5>
                    </div>

                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': errorMessages.name}" class="form-group">
                            <label>{{ $t('message.IT.l_name') }}</label>

                            <input type="text" v-model="languageRateModel.name" class="form-control" disabled>

                            <span
                                v-if="errorMessages.name"
                                v-for="err in errorMessages.name"
                                class="text-danger">

                                {{ err }}
                            </span>
                        </div>

                        <div class="form-group">
                            <label>PPA Rate</label>

                            <input
                                v-model="languageRateModel.ppa_rate"
                                type="number"
                                class="form-control"
                                :class="{'is-invalid' : errorMessages.hasOwnProperty('ppa_rate')}"

                                @wheel="$event.target.blur()">

                            <span
                                v-if="errorMessages.ppa_rate"
                                v-for="err in errorMessages.ppa_rate"
                                class="text-danger">

                                {{ err }}
                            </span>
                        </div>

                        <div class="form-group">
                            <label>PPW Rate</label>

                            <input
                                v-model="languageRateModel.ppw_rate"
                                type="number"
                                class="form-control"
                                :class="{'is-invalid' : errorMessages.hasOwnProperty('ppw_rate')}"

                                @wheel="$event.target.blur()">

                            <span
                                v-if="errorMessages.ppw_rate"
                                v-for="err in errorMessages.ppw_rate"
                                class="text-danger">

                                {{ err }}
                            </span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="submitUpdateLanguageRate">
                            {{ $t('message.IT.save') }}
                        </button>

                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="errorMessages = [];">
                            {{ $t('message.IT.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Update Language -->
    </div>
</template>

<script>
import {mapState} from "vuex";
import axios from "axios";

export default {
    props: [],

    data() {
        return {
            languageRateModel: {
                id: '',
                name: '',
                ppa_rate: 0,
                ppw_rate: 0,
            },

            errorMessages: [],
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            languageList : state => state.storeSystem.langaugeList,
        }),
    },

    mounted() {
        this.getLanguageList();
    },

    methods: {
        async getLanguageList () {
            await this.$store.dispatch('actionGetLanguageList');
        },

        updateLanguageRate (item) {
            let self = this;

            self.languageRateModel.id = item.id;
            self.languageRateModel.name = item.name;
            self.languageRateModel.ppa_rate = self.checkPriceRate(item.ppa_rate);
            self.languageRateModel.ppw_rate = self.checkPriceRate(item.ppw_rate);
        },

        submitUpdateLanguageRate () {
            let self = this;
            let loader = self.$loading.show();

            axios.post('/api/admin/language-price-rates', self.languageRateModel)
            .then((res) => {
                self.getLanguageList();

                loader.hide();

                swal.fire(
                    'Success!',
                    'Language price rates successfully updated.',
                    'success'
                )
            }).catch((err) => {
                loader.hide();

                self.errorMessages = err.response.data.errors

                swal.fire(
                    'Error!',
                    err.response.data.message,
                    'error'
                )
            });
        },

        checkPriceRate (rate) {
            return rate % 1 !== 0 ? rate * 1 : Math.trunc(rate);
        }
    },
}
</script>
