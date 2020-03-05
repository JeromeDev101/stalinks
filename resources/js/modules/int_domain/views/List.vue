<template>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ listInt.total }}</h3>
                    <p>Total Internal Domains</p>
                </div>
                <div class="icon">
                    <i class="fa fa-anchor"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Internal Domain List</h3>
                    <button @click="doAdd" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                    <div class="input-group input-group-sm float-right" style="width: 65px">
                        <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                            <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                        </select>
                    </div>
                    <div v-if="user.isAdmin" class="input-group input-group-sm float-right" style="min-width: 200px; max-width: 300px;">
                        <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.employee_id_temp" style="height: 37px;">
                            <option value="0">Select Employee</option>
                            <option v-for="user in listUser.data" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding relative">
                    <table class="table table-hover table-bordered table-striped rlink-table">
                        <tbody>
                        <tr class="label-primary">
                            <th>#</th>
                            <th>Country</th>
                            <th>Domain</th>
                            <th>Hosting</th>
                            <th>Domain Provider</th>
                            <th>User</th>
                            <th>Total Spent</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td style="max-width: 30px;">
                                Filter
                            </td>

                            <td tyle="max-width: 30px;">
                                <div class="input-group input-group-sm" style="width: 100%;">
                                    <select v-model="filterModel.country_id_temp" class="form-control pull-right">
                                        <option value="0"></option>
                                        <option v-for="option in filterModel.countryList" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </td>

                            <td style="max-width: 30px;">
                                <div class="input-group input-group-sm">
                                    <input type="text" v-model="filterModel.domain_temp"  class="form-control pull-right" placeholder="Search Domain">
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="btn-group">
                                    <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in listInt.data" :key="index">
                            <td class="center-content">{{ index + 1 }}</td>
                            <td>{{ item.country.name }}</td>
                            <td><a :href="'http://' + item.domain" target="_blank">{{ item.domain }}</a></td>
                            <td><router-link :to="{ path: `/hostings/${item.hosting_provider.id}` }">{{ item.hosting_provider.name }}</router-link></td>
                            <td><router-link :to="{ path: `/domains/${item.domain_provider.id}` }">{{ item.domain_provider.name }}</router-link></td>
                            <td>{{ item.user.name }}</td>
                            <td>{{ convertPrice(item.total_spent) }}$</td>
                            <td>
                                <div class="btn-group">
                                    <button @click="doEdit(item)" data-toggle="modal" data-target="#modal-update" class="btn btn-default" title="Edit"><i class="fa fa-fw fa-edit"></i></button>
                                    <button @click="doShowBackLink(item)" data-toggle="modal" data-target="#modal-backlink" type="submit" title="Back Link" class="btn btn-default"><i class="fa fa-fw fa-link"></i></button>
                                </div>
                                <!--                                <router-link class="btn btn-success" :to="{ path: `/profile/${user.id}` }"><i class="fa fa-fw fa-eye"></i> View</router-link>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="overlay" v-if="isLoadingTable">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <component :is="pagination" :callMethod="goToPage"></component>
                </div>
            </div>
            <!-- /.box -->
        </div>

        <!--    Modal Backlink-->
        <div class="modal fade" id="modal-backlink" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ listBackLink.total }} Back Links: <a target="_blank" :href="'http://' + intBackLink.domain">{{ intBackLink.domain }}</a></h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <table class="table table-responsive table-hover table-bordered table-striped rlink-table">
                            <tbody>
                            <tr class="label-primary">
                                <th>#</th>
                                <th>External Domain</th>
                                <th>Internal Domain</th>
                                <th>Link</th>
                                <th>Price</th>
                                <th>Anchor Text</th>
                                <th>Live Date</th>
                                <th>Status</th>
                                <th>User</th>
                            </tr>
                            <tr v-for="(backLink, index) in listBackLink.data" :key="index">
                                <td class="center-content">{{ index + 1 }}</td>
                                <td>{{ backLink.ext_domain.domain }}</td>
                                <td>{{ backLink.int_domain.domain }}</td>
                                <td><a href="backLink.link">{{ backLink.link }}</a></td>
                                <td>{{ convertPrice(backLink.price) }}$</td>
                                <td>{{ backLink.anchor_text }}</td>
                                <td>{{ backLink.live_date }}</td>
                                <td>{{ backLink.status }}</td>
                                <td>{{ backLink.user.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="resetFillter()" data-dismiss="modal">Close</button>

                        <download-csv
                                :data = "listBackLink.data"
                                :fileds = "csvExport.data_filled"
                                :nameFile = "intBackLink.domain + '_' + csvExport.file_csv">
                        </download-csv>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Backlink-->


        <!--    Modal Add-->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Internal Domain</h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                    <label style="color: #333">Domain</label>
                                    <input type="text" v-model="intModel.domain" class="form-control" value="" required="required" placeholder="Enter domain">
                                    <span v-if="messageForms.errors.domain" v-for="err in messageForms.errors.domain" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Country</label>
                                    <div>
                                        <select v-model="intModel.country_id" class="form-control pull-right">
                                            <option value="0">-- Select country --</option>
                                            <option v-for="option in filterModel.countryList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Domain Provider</label>
                                    <div>
                                        <select v-model="intModel.domain_provider_id" class="form-control pull-right">
                                            <option value="0">-- Select Provider --</option>
                                            <option v-for="option in domainList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.domain_provider_id" v-for="err in messageForms.errors.domain_provider_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Hosting Provider</label>
                                    <div>
                                        <select v-model="intModel.hosting_provider_id" class="form-control pull-right">
                                            <option value="0">-- Select Hosting --</option>
                                            <option v-for="option in hostingList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.hosting_provider_id" v-for="err in messageForms.errors.hosting_provider_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAdd" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->

        <!--    Modal Update-->
        <div class="modal fade" id="modal-update" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Internal Domain <b>{{ modelUpdate.domain }}</b></h4>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body relative">
                        <form class="row" action="">
                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.domain}" class="form-group">
                                    <label style="color: #333">Domain</label>
                                    <input type="text" v-model="modelUpdate.domain" class="form-control" value="" required="required" placeholder="Enter domain">
                                    <span v-if="messageForms.errors.domain" v-for="err in messageForms.errors.domain" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Country</label>
                                    <div>
                                        <select v-model="modelUpdate.country_id" class="form-control pull-right">
                                            <option value="0">-- Select country --</option>
                                            <option v-for="option in filterModel.countryList" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.country_id" v-for="err in messageForms.errors.country_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Domain Provider</label>
                                    <div>
                                        <select v-model="modelUpdate.domain_provider_id" class="form-control pull-right">
                                            <option value="0">-- Select Provider --</option>
                                            <option v-for="option in domainList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.domain_provider_id" v-for="err in messageForms.errors.domain_provider_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.country_id}" class="form-group">
                                    <label style="color: #333">Hosting Provider</label>
                                    <div>
                                        <select v-model="modelUpdate.hosting_provider_id" class="form-control pull-right">
                                            <option value="0">-- Select Hosting --</option>
                                            <option v-for="option in hostingList.data" v-bind:value="option.id">
                                                {{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <span v-if="messageForms.errors.hosting_provider_id" v-for="err in messageForms.errors.hosting_provider_id" class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </form>
                        <div class="overlay" v-if="isPopupLoading"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdate" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--    End Modal Add-->
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import DownloadCsv from '@/components/export-csv/Csv.vue'

    export default {
        name: 'IntList',

        data() {
            return {
                csvExport: {
                    file_csv: 'internal.xls',
                    data_filled: {
                        'Ext Domain': 'ext_domain.domain',
                        'Int Domain': 'int_domain.domain',
                        'Link': 'link',
                        'Price': 'price',
                        'Anchor Text': 'anchor_text',
                        'Live Date': 'live_date',
                        'Status': 'status',
                        'User': 'user.name'
                    },
                    json_meta: [
                        [{
                            'key': 'charset',
                            'value': 'utf-8'
                        }]
                    ]
                },

                intModel: {
                    id: 0,
                    domain: '',
                    country_id: 0,
                    domain_provider_id: 0,
                    hosting_provider_id: 0
                },

                modelUpdate: {
                    id: 0,
                    domain: '',
                    country_id: 0,
                    domain_provider_id: 0,
                    hosting_provider_id: 0
                },

                checkMapIdCountry: [],

                filterModel: {
                    country_id: this.$route.query.country_id || 0,
                    country_id_temp: this.$route.query.country_id || 0,
                    countryList: [],
                    domain: this.$route.query.domain || '',
                    domain_temp: this.$route.query.domain_temp || '',
                    employee_id: this.$route.query.employee_id || 0,
                    employee_id_temp: this.$route.query.employee_id_temp || 0,
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 10,
                },

                listPageOptions: [5, 10, 25, 50, 100],
                intBackLink: {},
                isLoadingTable: false,
                isPopupLoading: false
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            await this.$store.dispatch('getListCountriesInt', { vue: this });
            this.getUserList();
            this.getHostingList();
            this.getDomainProviderList();
            this.initFilter();
            this.getIntList({
                params: this.filterModel
            });
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                listInt: state => state.storeIntDomain.listInt,
                hostingList: state => state.storeIntDomain.hostingList,
                domainList: state => state.storeIntDomain.domainList,
                messageForms: state => state.storeIntDomain.messageForms,
                listUser: state => state.storeUser.listUser,
                listBackLink: state => state.storeBackLink.listBackLink,
                filterBackLink: state => state.storeBackLink.fillter,
                listCountriesInt: state => state.storeExtDomain.listCountriesInt,
            }),

            pagination() {
                return {
                    props: {
                        callMethod: ""
                    },
                    template: `<div class="paging_simple_numbers">${this.listInt.pagination}</div>`,
                    methods: {
                        async goToPage(pageNum) {
                            this.callMethod(pageNum);
                        }
                    }
                }
            },
        },

        methods: {
            initFilter() {
                this.filterModel.countryList = this.listCountriesInt.data;
            },

            async getUserList() {
                await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
                if (this.user.isAdmin) {
                    await this.$store.dispatch('actionGetUser', {
                        vue: this,
                        params: { params: { full_data: true } },
                        showMainLoading: false
                    });
                }
            },

            async getIntList(params) {
                this.isLoadingTable = true;
                await this.$store.dispatch('getListInt', params);
                this.isLoadingTable = false;
            },

            async getHostingList() {
                await this.$store.dispatch('getFullHostingList');
            },

            async getDomainProviderList() {
                await this.$store.dispatch('getFullDomainProviderList');
            },

            async doSearchList() {
                let that = this;
                that.filterModel.country_id = that.filterModel.country_id_temp;
                that.filterModel.employee_id = that.filterModel.employee_id_temp;
                that.filterModel.domain = that.filterModel.domain_temp;

                this.$router.push({
                    query: that.filterModel,
                });

                this.getIntList({
                    params: {
                        country_id: that.filterModel.country_id,
                        employee_id: that.filterModel.employee_id,
                        domain: that.filterModel.domain,
                        per_page: that.filterModel.per_page,
                    }
                });
            },

            clearIntModel() {
                this.intModel = {
                    id: 0,
                        domain: '',
                        country_id: 0,
                        domain_provider_id: 0,
                        hosting_provider_id: 0
                };
            },

            async goToPage(pageNum) {
                let that = this;
                this.$router.push({
                    query: that.filterModel,
                });

                await this.getIntList({
                    params: {
                        page: pageNum,
                        country_id: that.filterModel.country_id,
                        employee_id: that.filterModel.employee_id_temp,
                        domain: that.filterModel.domain,
                        per_page: that.filterModel.per_page,
                    }
                });
            },

            async doShowBackLink(intDomain) {
                this.intBackLink = intDomain;
                this.filterBackLink.int_id = intDomain.id;
                this.filterBackLink.full_data = true;
                this.isPopupLoading = true;
                await this.$store.dispatch('actionGetBackLink', {
                    vue: this,
                    params: this.filterBackLink
                });
                this.isPopupLoading = false;
            },

            doAdd() {
                this.$store.dispatch('clearMessageFormInt');
            },

            async submitAdd() {
                let that = this;
                this.isPopupLoading = true;
                await this.$store.dispatch('addInt', that.intModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'saved_int') {
                    this.clearIntModel();
                    this.getIntList({
                        params: this.filterModel
                    });
                }
            },

            doEdit(intDomain) {
                this.$store.dispatch('clearMessageFormInt');
                this.modelUpdate = JSON.parse(JSON.stringify(intDomain))
            },

            resetFillter()
            {
                return this.$store.dispatch('actionResetFillterBacklink');
            },

            convertPrice(price) {
                return parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            },

            async submitUpdate() {
                this.isPopupLoading = true;
                await this.$store.dispatch('updateInt', this.modelUpdate);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_int') {
                    for (var index in this.listInt.data) {
                        if (this.listInt.data[index].id === this.modelUpdate.id) {
                            this.listInt.data[index] = this.modelUpdate;

                            for (var indct in this.filterModel.countryList) {
                                if (this.filterModel.countryList[indct].id === this.modelUpdate.country_id) {
                                    this.listInt.data[index].country = this.filterModel.countryList[indct];
                                    break;
                                }
                            }
                            break;
                        }
                    }
                }
            },

        },

        components: {
            DownloadCsv
        }
    }
</script>
