<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.get_alexa.ga_title') }}</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row alexa-get-row">
                            <div class="col-md-3 col-lg-2">
                                <div class="input-group input-group-sm">
                                    <label>{{ $t('message.get_alexa.ga_country') }}: </label>

                                    <select v-model="filterModel2.country_id" class="form-control pull-right">
                                        <option v-for="option in listCountryAll.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                    <span
                                        v-if="messageForms.errors.country_id"
                                        v-for="err in messageForms.errors.country_id"
                                        class="text-danger">
                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-2">
                                <div class="input-group input-group-sm">
                                    <label>{{ $t('message.get_alexa.ga_start') }}: </label>

                                    <input
                                        v-model="filterModel2.start"
                                        type="text"
                                        :placeholder="$t('message.get_alexa.ga_start')"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-2">
                                <div class="input-group input-group-sm">
                                    <label>{{ $t('message.get_alexa.ga_count') }}: </label>

                                    <input
                                        v-model="filterModel2.count"
                                        type="text"
                                        :placeholder="$t('message.get_alexa.ga_count')"
                                        class="form-control pull-right">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-2">
                                <button @click="doGetAlexaRank" type="submit" class="btn btn-default">
                                    <i v-if="isLoadingTable" class="fa fa-refresh fa-spin"></i>
                                    <i v-if="!isLoadingTable" class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <table class="table table-hover table-bordered table-striped rlink-table">
                            <tbody>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>{{ $t('message.get_alexa.ga_country') }}</th>
                                    <th>{{ $t('message.get_alexa.ga_domain') }}</th>
                                    <th>{{ $t('message.get_alexa.ga_emails') }}</th>
                                    <th>{{ $t('message.get_alexa.ga_facebook') }}</th>
                                    <th>{{ $t('message.get_alexa.ga_rank') }}</th>
                                    <th>{{ $t('message.get_alexa.ga_status') }}</th>
                                </tr>

                                <tr v-for="(ext, index) in listAlexa.extDomains" :key="index">
                                    <td class="center-content">{{ index + 1 }}</td>
                                    <td>{{ ext.country.name }}</td>
                                    <td><a :href="'http://' + ext.domain" target="_blank">{{ ext.domain }}</a></td>
                                    <td style="max-width: 200px">
                                        <ol v-if="ext.email != '' && ext.email != null" class="pl-15">
                                            <li v-for="item in ext.email.split('|')" key="item">{{ item }}</li>
                                        </ol>
                                    </td>
                                    <td style="max-width: 200px" >
                                        <ol v-if="ext.facebook != '' && ext.facebook != null" class="pl-15">
                                            <li v-for="item in ext.facebook.split('|')" key="item">
                                                <a target="_blank" :href="item">{{ item }}<br/></a>
                                            </li>
                                        </ol>
                                    </td>
                                    <td>{{ ext.alexa_rank }}</td>
                                    <td>
                                        <span :class="['badge', 'badge-' + listStatusText[ext.status].label]">
                                            {{ listStatusText[ext.status].text }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

    export default {
        name: 'AlexaList',
        data() {
            return {
                filterModel2: {
                    country_id: 0,
                    countryList: [],
                    start: 0,
                    count: 50,
                },

                isLoadingTable: false,
                isAlexaConsumed: false,
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            this.updateUserPermission();
            this.initFilter();
            this.getListCountries();
            this.checkConsumeAlexa()
            
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                listAlexa: state => state.storeExtDomain.listAlexa,
                listStatusText: state => state.storeExtDomain.listStatusText,
                messageForms: state => state.storeExtDomain.messageForms,
                // listCountryUpdate: state => state.storeUser.listCountryUpdate,
                listCountryAll: state => state.storePublisher.listCountryAll,
            }),
        },

        methods: {

            async getListCountries(params) {
                await this.$store.dispatch('actionGetListCountries', params);
            },

            async updateUserPermission() {
                let that = this;
                await this.$store.dispatch('actionGetListCountryId', { vue: this, params: { user_id: that.user.id , for_ext: 1, for_assign: 1} });
            },

            async getAlexaRank(params) {
                let self = this;

                swal.fire({
                    title: self.$t('message.get_alexa.alert_get_alexa'),
                    text: self.$t('message.get_alexa.alert_please_wait'),
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });

                //this.isLoadingTable = true;
                await this.$store.dispatch('getAlexaList', params);

                if (this.messageForms.errors.country_id) {

                    swal.fire({
                        icon: 'error',
                        title: this.messageForms.errors.country_id,
                        showConfirmButton: false,
                        timer: 2000,
                        height: 100,
                    })
                }

                if(this.listAlexa.status == false) {
                    swal.fire({
                        icon: 'error',
                        title: self.$t('message.get_alexa.alert_limit') + this.listAlexa.total+'.',
                        showConfirmButton: true,
                    })
                } else if(this.listAlexa.status == true){
                    swal.close()
                }


                //this.isLoadingTable = false;
            },

            checkConsumeAlexa() {
                axios.get('/api/alexa-consume')
                .then((res)=> {
                    let result = res.data
                    let val = parseInt(result.value)

                    console.log(val)
                    if(val > 40000) {
                        this.isAlexaConsumed = true;
                    } else {
                        this.isAlexaConsumed = false;
                    }
                })
            },

            async doGetAlexaRank() {
                if (this.isAlexaConsumed) {
                    swal.fire('Invalid', 'Sorry getting Alexa is already reach the limit', 'error')
                    return false;
                }

                if (this.isLoadingTable) return;
                let that = this;
                await this.getAlexaRank({
                        country_id: that.filterModel2.country_id,
                        start: that.filterModel2.start,
                        count: that.filterModel2.count
                    });

                // console.log(this.listAlexa.status)
                // this.checkConsumeAlexa()
            },

            initFilter() {
                let that = this;
            },

        },
    }
</script>
