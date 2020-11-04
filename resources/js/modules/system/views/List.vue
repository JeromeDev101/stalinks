<template>
    <div class="row">

        <!-- <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ countryList.total }}</h3>
                    <p>Total Countries</p>
                </div>
                <div class="icon">
                    <i class="fa fa-anchor"></i>
                </div>
            </div>
        </div> -->

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Language List</h3>
                            <button @click="clearMessageform" data-toggle="modal" data-target="#modal-add-language" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                        </div>

                        <div class="box-body no-padding relative">
                            <table class="table table-hover table-bordered table-striped rlink-table">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in langaugeList.data" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.code }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button @click="doEditlanguage(item)" data-toggle="modal" data-target="#modal-update-language" type="submit" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Countries List</h3>
                            <button @click="doAdd" data-toggle="modal" data-target="#modal-add" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                            <div class="input-group input-group-sm float-right" style="width: 65px">
                                <select @change="doSearchList" class="form-control pull-right" v-model="filterModel.per_page" style="height: 37px;">
                                    <option v-for="value in listPageOptions" :value="value">{{ value }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="box-body no-padding relative">
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
                        <div class="box-footer clearfix">
                            <div class="paging_simple_numbers">
                                <pagination :data="countryList" @pagination-change-page="goToPage"></pagination>
                            </div>
                        </div>
                    </div> -->

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Payment Method</h3>
                            <button data-toggle="modal" @click="clearMessageform" data-target="#modal-add-payment" class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
                        </div>

                        <div class="box-body no-padding relative">
                            <table class="table table-condensed table-hover table-striped table-bordered">
                                <thead>
                                    <tr class="label-primary">
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in paymentList.data" :key="index">
                                        <td>{{ index + 1}}</td>
                                        <td>{{ item.type }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="submit" @click="doEditPayment(item)" data-toggle="modal" data-target="#modal-update-payment" title="Edit" class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                            </div>
                                            <!-- <div class="btn-group">
                                                <button title="Delete" class="btn btn-default"><i class="fa fa-fw fa-trash"></i></button>
                                            </div> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="row">
                        
                        <div class="col-sm-12">
                            <div class="box p-4">
                                <div class="box-body">
                                    <div class="progress-group">
                                        <span class="progress-text">Ahref API rows consume</span>
                                        <span class="progress-number"><b>{{ rows_consume }}</b>/500,000</span>

                                        <div class="progress sm my-3">
                                            <div class="progress-bar progress-bar-aqua" :style="'width:'+ consume_percentage + '%'"></div>
                                        </div>

                                        <b>Used: </b> {{ rows_consume }} <br/>
                                        <b>Remaining: </b> {{ rows_remaining }} <br/>
                                    </div>

                                    <button class="btn btn-link pull-right" @click="getSubscriptionInfo">Check update <i v-if="loadingUpdate" class="fa fa-refresh fa-spin"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="box pb-4">
                                <div class="box-header">
                                    <h3 class="box-title">Formula for External Buyer</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <form>
                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-2 col-form-label">Additional</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" class="form-control" v-model="updateFormula.additional">
                                                            <span v-if="messageForms.errors.additional" v-for="err in messageForms.errors.additional" class="text-danger">{{ err }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-2 col-form-label">Percentage</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" class="form-control" v-model="updateFormula.percentage">
                                                            <span v-if="messageForms.errors.percentage" v-for="err in messageForms.errors.percentage" class="text-danger">{{ err }}</span>
                                                        </div>
                                                    </div>
                                                </form>
                                                <hr/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-sm-12">
                                                WITH ARTICLE
                                            </div>
                                            <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                            <div class="col-sm-12 text-danger">Formula: (Remain selling price)</div>

                                            <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                            <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-sm-12">
                                                WITHOUT ARTICLE
                                            </div>
                                            <div class="col-sm-12 p-3 text-primary">Commission = No <hr/></div>
                                            <div class="col-sm-12 text-danger">Formula: selling price + Additional</div>
                                            
                                            <div class="col-sm-12 p-3 text-primary">Commission = Yes <hr/></div>
                                            <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price + Additional</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer clearfix">
                                    <button type="button" @click="submitFormula" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>


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
        
        <!-- Modal Update Payment Type -->
        <div class="modal fade" id="modal-update-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Payment Type</h5>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text" v-model="paymentUpdate.type" class="form-control" placeholder="Enter Payment Type" required>
                            <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdatePayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Language -->
        <div class="modal fade" id="modal-add-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Language</h5>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                            <label for="">Name</label>
                            <input type="text" v-model="languageModel.name" class="form-control" required>
                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                        </div>
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                            <label for="">Code</label>
                            <input type="text" v-model="languageModel.code" class="form-control" required>
                            <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddLanguage" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add Modal Language -->


        <!-- Modal Update Language -->
        <div class="modal fade" id="modal-update-language" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Language</h5>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.name}" class="form-group">
                            <label for="">Name</label>
                            <input type="text" v-model="languageUpdate.name" class="form-control" required>
                            <span v-if="messageForms.errors.name" v-for="err in messageForms.errors.name" class="text-danger">{{ err }}</span>
                        </div>
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.code}" class="form-group">
                            <label for="">Code</label>
                            <input type="text" v-model="languageUpdate.code" class="form-control" required>
                            <span v-if="messageForms.errors.code" v-for="err in messageForms.errors.code" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdateLanguage" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add Update Language -->
        
        <!-- Modal Add Payment-->
        <div class="modal fade" id="modal-add-payment" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment Type</h5>
                        <div class="modal-load overlay float-right">
                            <i class="fa fa-refresh fa-spin" v-if="isPopupLoading"></i>

                            <span v-if="messageForms.message != '' && !isPopupLoading" :class="'text-' + ((Object.keys(messageForms.errors).length > 0) ? 'danger' : 'success')">
                                {{ messageForms.message }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text" v-model="paymentModel.type" class="form-control" placeholder="Enter Payment Type" required>
                            <span v-if="messageForms.errors.type" v-for="err in messageForms.errors.type" class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddPayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Add Payment-->

        <!-- Modal Add Country -->
        <!-- <div class="modal fade" id="modal-add" style="display: none;">
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
            </div>
        </div> -->
        <!-- End Modal Add Country -->

        <!-- Modal Update Country -->
        <!-- <div class="modal fade" id="modal-update" style="display: none;">
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
            </div>
        </div> -->
        <!-- End Modal Update Country -->
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import axios from 'axios';

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

                paymentModel: {
                    id: 0,
                    type: ''
                },

                paymentUpdate: {
                    id: 0,
                    type: ''
                },

                languageModel: {
                    name: '',
                    code: '',
                },

                languageUpdate: {
                    id: '',
                    name: '',
                    code: '',
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
                },
                rows_consume: 0,
                consume_percentage: 0,
                rows_remaining: 0,
                loadingUpdate: false,
                updateFormula: {
                    id: '',
                    additional: '',
                    percentage: '',
                },
            };
        },

        async created() {
            await this.$store.dispatch('actionCheckAdminCurrentUser', { vue: this });
            if (!this.user.isAdmin) {
                window.location.href = '/';
                return;
            }

            this.getConfigList();
            this.getPaymentList();

            // this.getCountryList({
            //     params: this.filterModel
            // });

            this.getSubscriptionInfo();
            this.getFormula();
            this.getLanguageList();
        },

        computed: {
            ...mapState({
                user: state => state.storeAuth.currentUser,
                countryList: state => state.storeSystem.countryList,
                paymentList: state => state.storeSystem.paymentList,
                messageForms: state => state.storeSystem.messageForms,
                configList: state => state.storeSystem.configList,
                Info: state => state.storeSystem.Info,
                formula: state => state.storeSystem.formula,
                langaugeList: state => state.storeSystem.langaugeList,
            }),
        },

        methods: {
            async getLanguageList() {
                await this.$store.dispatch('actionGetLanguageList');
            },

            async submitAddLanguage() {
                await this.$store.dispatch('actionAddLanguage', this.languageModel);

                this.getLanguageList();

                this.languageModel = {
                    name: '',
                    code: '',
                }
            },

            async submitUpdateLanguage() {
                await this.$store.dispatch('actionUpdateLanguage', this.languageUpdate);
                this.getLanguageList();
            },

            doEditlanguage(item){
                this.$store.dispatch('clearMessageFormSystem');
                this.languageUpdate = JSON.parse(JSON.stringify(item))
            },

            async submitFormula(){
                await this.$store.dispatch('actionUpdateFormula', this.updateFormula);

                if (this.messageForms.action == 'save_formula') {
                    swal.fire(
                        'Saved!',
                        'Successfully Updated.',
                        'success'
                    )

                    this.getFormula();
                }
            },

            async getFormula() {
                await this.$store.dispatch('actionGetFormula');

                this.updateFormula = this.formula.data[0];
            },

            async getConfigList() {
                await this.$store.dispatch('actionGetConfigList');

                for (let configType in this.configList.data) {
                    this.isLoadingConfig[configType] = false;
                }
            },

            async getSubscriptionInfo() {
                this.loadingUpdate = true;
                await this.$store.dispatch('actionGetSubscriptionInfo');

                let rows_left = this.Info.info.rows_left
                let rows_limit = this.Info.info.rows_limit
                let consume_rows = parseFloat(rows_limit) - parseFloat(rows_left);

                this.rows_remaining = rows_left;
                this.rows_consume = consume_rows;
                this.consume_percentage = ( parseFloat(consume_rows)/parseFloat(rows_limit) )*100;

                this.loadingUpdate = false;
            },

            async getCountryList(params) {
                this.isLoadingTable = true;
                await this.$store.dispatch('actionGetCountryList', params);
                this.isLoadingTable = false;
            },


            async getPaymentList(params) {
                this.isLoadingTable = true;
                await this.$store.dispatch('actionGetPaymentList', params);
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

                this.paymentModel = {
                    id: 0,
                    type: ''
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

            doEditPayment(item) {
                this.clearMessageform();
                this.$store.dispatch('clearMessageFormSystem');
                this.paymentUpdate = JSON.parse(JSON.stringify(item))
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

            async submitAddPayment() {
                let that = this;
                this.isPopupLoading = true;
                await this.$store.dispatch('actionAddPayment', that.paymentModel);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'saved_payment') {
                    this.clearModel();
                    this.getPaymentList();
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

            async submitUpdatePayment() {
                this.isPopupLoading = true;
                await this.$store.dispatch('actionUpdatePayment', this.paymentUpdate);
                this.isPopupLoading = false;

                if (this.messageForms.action === 'updated_payment') {
                    for (var index in this.paymentList.data) {
                        if (this.paymentList.data[index].id === this.paymentUpdate.id) {
                            this.paymentList.data[index] = this.paymentUpdate;
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
            },

            clearMessageform() {
                this.$store.dispatch('clearMessageFormSystem');
            },
        },
    }
</script>
