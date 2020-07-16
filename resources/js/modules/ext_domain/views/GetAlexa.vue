<template>
    <div class="alexa-container">
        <div class="row alexa-get-row">
            <div class="col-md-3 col-lg-2">
                <div class="input-group input-group-sm">
                    <label>Country: </label>
                    <select v-model="filterModel2.country_id" class="form-control pull-right">
                        <option v-for="option in listCountryUpdate.data" v-bind:value="option.id">
                            {{ option.name }}
                        </option>
                    </select>
                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                </div>
            </div>

            <div class="col-md-3 col-lg-2">
                <div class="input-group input-group-sm">
                    <label>Start: </label>
                    <input type="text" v-model="filterModel2.start"  class="form-control pull-right" placeholder="Start">
                </div>
            </div>

            <div class="col-md-3 col-lg-2">
                <div class="input-group input-group-sm">
                    <label>Count: </label>
                    <input type="text" v-model="filterModel2.count"  class="form-control pull-right" placeholder="Count">
                </div>
            </div>

            <div class="col-md-3 col-lg-2">
                <button @click="doGetAlexaRank" type="submit" class="btn btn-default">
                    <i class="fa fa-refresh fa-spin" v-if="isLoadingTable"></i><i v-if="!isLoadingTable" class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Get Alexa External Domain</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered table-striped rlink-table">
                            <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>Country</th>
                                <th>Domain</th>
                                <th>Emails</th>
                                <th>Facebook</th>
                                <th>Rank</th>
                                <th>Status</th>
                            </tr>
                            <tr v-for="(ext, index) in listAlexa.extDomains" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ ext.country.name }}</td>
                                <td><a :href="'http://' + ext.domain" target="_blank">{{ ext.domain }}</a></td>
                                <td style="max-width: 200px"><ol v-if="ext.email != ''" class="pl-15"><li v-for="item in ext.email.split('|')" key="item">{{ item }}</li></ol></td>
                                <td style="max-width: 200px" ><ol v-if="ext.email != ''" class="pl-15"><li v-for="item in ext.facebook.split('|')" key="item"><a target="_blank" :href="item">{{ item }}<br/></a></li></ol></td>
                                <td>{{ ext.alexa_rank }}</td>
                                <td><span :class="['label', 'label-' + listStatusText[ext.status].label]">{{ listStatusText[ext.status].text }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

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
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            this.updateUserPermission();
            this.initFilter();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                listAlexa: state => state.storeExtDomain.listAlexa,
                listStatusText: state => state.storeExtDomain.listStatusText,
                messageForms: state => state.storeExtDomain.messageForms,
                listCountryUpdate: state => state.storeUser.listCountryUpdate,
            }),
        },

        methods: {
            async updateUserPermission() {
                let that = this;
                await this.$store.dispatch('actionGetListCountryId', { vue: this, params: { user_id: that.user.id , for_ext: 1, for_assign: 1} });
            },

            async getAlexaRank(params) {
                swal.fire({
                    title: "Getting Alexa...",
                    text: "Please wait",
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
                        title: 'The limit number of Top site in this country is '+this.listAlexa.total+'.',
                        showConfirmButton: true,
                    })
                } else if(this.listAlexa.status == true){
                    swal.close()
                }


                //this.isLoadingTable = false;
            },

            async doGetAlexaRank() {
                if (this.isLoadingTable) return;
                let that = this;
                await this.getAlexaRank({
                        country_id: that.filterModel2.country_id,
                        start: that.filterModel2.start,
                        count: that.filterModel2.count
                    });

                console.log(this.listAlexa.status)
            },

            initFilter() {
                let that = this;
            },

        },
    }
</script>
