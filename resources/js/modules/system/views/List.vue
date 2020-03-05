<template>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ countryList.total }}</h3>
                    <p>Total Countries</p>
                </div>
                <div class="icon">
                    <i class="fa fa-anchor"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Countries List</h3>
                            <button @click="doAdd" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                            <div class="input-group input-group-sm float-right" style="width: 65px">
                                <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                                    <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding relative">
                            <table class="table table-hover table-bordered table-striped rlink-table">
                                <tbody>
                                <tr class="label-primary">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td style="max-width: 30px;">
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.name_temp"  class="form-control pull-right" placeholder="Search Name">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" v-model="filterModel.code_temp"  class="form-control pull-right" placeholder="Search Code">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button @click="doSearchList" type="submit" title="Filter" class="btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in countryList.data" :key="index">
                                    <td class="center-content">{{ index + 1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.code }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button @click="doEdit(item)" data-toggle="modal" data-target="#modal-update" type="submit" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                        </div>
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
                            <div class="paging_simple_numbers">
                                <pagination :data="countryList" @pagination-change-page="goToPage"></pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12" v-for="(configs, typeConfig) in configList.data">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Config {{ typeConfig }}</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body table-responsive relative">

                                    <form action="" role="form">
                                        <div v-for="(item) in configs" class="form-group">
                                            <label>{{ item.name }}</label>
                                            <input type="text" class="form-control" v-model="item.value" :placeholder="'Enter' + item.name">
                                        </div>
                                    </form>

                                    <div class="overlay" v-if="isLoadingConfig[typeConfig]">
                                        <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                    <button type="button" @click="submitSaveConfig(typeConfig)" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--    Modal Add-->
        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Country</h4>
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
                                    <label style="color: #333">Name</label>
                                    <input type="text" v-model="countryModel.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                    <label style="color: #333">Code</label>
                                    <input type="text" v-model="countryModel.code" class="form-control" value="" required="required" placeholder="Enter Code">
                                    <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
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

        <!--    Modal Add-->
        <div class="modal fade" id="modal-update" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Country {{ countryUpdate.name }}</h4>
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
                                    <label style="color: #333">Name</label>
                                    <input type="text" v-model="countryUpdate.name" class="form-control" value="" required="required" placeholder="Enter Name">
                                    <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                                    <label style="color: #333">Code</label>
                                    <input type="text" v-model="countryUpdate.code" class="form-control" value="" required="required" placeholder="Enter Code">
                                    <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
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

    export default {
        name: 'System',

        data() {
            return {
                countryModel: {
                    id: 0,
                    name: '',
                    code: '',
                },

                countryUpdate: {
                    id: 0,
                    name: '',
                    code: ''
                },

                filterModel: {
                    name: this.$route.query.name || '',
                    code: this.$route.query.name || '',
                    name_temp: this.$route.query.name_temp || '',
                    code_temp: this.$route.query.code_temp || '',
                    page: this.$route.query.page || 0,
                    per_page: this.$route.query.per_page || 10,
                    paginate: 1
                },

                listPageOptions: [5, 10, 25, 50, 100],
                isLoadingTable: false,
                isPopupLoading: false,
                isLoadingConfig: {
                    alexa: false,
                    ahrefs: false
                }
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            if (!this.user.isAdmin) {
                window.location.href = '/';
                return;
            }

            this.getConfigList();
            this.getCountryList({
                params: this.filterModel
            });
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                countryList: state => state.storeSystem.countryList,
                messageForms: state => state.storeSystem.messageForms,
                configList: state => state.storeSystem.configList,
            }),
        },

        methods: {

            async getConfigList() {
                await this.$store.dispatch('actionGetConfigList');

                for (let configType in this.configList.data) {
                    this.isLoadingConfig[configType] = false;
                }

                console.log(this.isLoadingConfig);
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
                    query: that.filterModel,
                });

                this.getCountryList({
                    params: that.filterModel
                });
            },

            clearModel() {
                this.countryModel = {
                    id: 0,
                    name: '',
                    code: ''
                };
            },

            async goToPage(pageNum) {
                let that = this;
                that.filterModel.page = pageNum;
                this.$router.push({
                    query: that.filterModel,
                });

                await this.getCountryList({
                    params: that.filterModel
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
                        params: this.filterModel
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
            }
        },
    }
</script>
