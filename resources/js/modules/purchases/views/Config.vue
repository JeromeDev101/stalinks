<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.purchases_config.c_title') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="categories.total" class="pagination-custom-footer-text m-0 mt-2">
                                    <b v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: categories.from, to: categories.to, end: categories.total }) }}
                                    </b>

                                    <b v-else>
                                        {{ $t('message.others.table_all_entries', { total: purchases.total }) }}
                                    </b>
                                </span>
                            </div>
                            <div class="col-6">
                                <button
                                    v-if="user.permission_list.includes('create-purchases-config')"
                                    title="Add Purchase Category"
                                    :title="$t('message.purchases_config.c_add_category')"
                                    class="btn btn-success float-right"

                                    @click="modalOpener('Add')">

                                    <i class="fas fa-plus"></i>
                                </button>

                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control"
                                        style="height: 37px;"

                                        @change="getPurchaseCategories()">

                                        <option v-for="value in pageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" v-if="categories.data.length">
                            <table id="tbl-purchase-categories" class="table table-hover table-bordered">
                                <thead>
                                    <tr class="label-primary">
                                        <th></th>
                                        <th>{{ $t('message.purchases_config.c_id') }}</th>
                                        <th>{{ $t('message.purchases_config.c_name') }}</th>
                                        <th>{{ $t('message.purchases_config.c_desc') }}</th>
                                        <th>{{ $t('message.purchases_config.c_action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template v-for="(category, index) in categories.data">
                                        <tr>
                                            <td class="text-center">
                                                <button
                                                    :title="$t('message.purchases_config.c_toggle')"
                                                    class="btn btn-default m-1 accordion-toggle"
                                                    data-toggle="collapse"
                                                    :ref="'purchase-collapse-' + category.id"
                                                    :data-target="'#purchase-category-collapse' + category.id"

                                                    @click="checkExpanded(category.id); showPurchaseTypes(category.id)">

                                                    <i :ref="'purchase-caret-' + category.id" class="fas fa-angle-down"></i>
                                                </button>
                                            </td>
                                            <td>{{ category.id }}</td>
                                            <td>{{ category.name }}</td>
                                            <td>{{ category.description }}</td>
                                            <td class="text-center">
                                                <button
                                                    v-if="user.permission_list.includes('update-purchases-config')"
                                                    title="Edit Category"
                                                    :title="$t('message.purchases_config.c_edit_category')"
                                                    class="btn btn-primary m-1"

                                                    @click="fillCategoryData(category); modalOpener('Update')">

                                                    <i class="fas fa-pen-square"></i>
                                                </button>

                                                <button
                                                    v-if="user.permission_list.includes('create-purchases-config')"
                                                    title="Add Purchase Type"
                                                    :title="$t('message.purchases_config.c_add_type')"
                                                    class="btn btn-success m-1"

                                                    @click="fillTypeData('Add', category) ;modalOpenerType('Add')">

                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="12" class="p-0" style="background: #629aa957">
                                                <div
                                                    class="accordion-body collapse m-3"
                                                    :id="'purchase-category-collapse' + category.id">

                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table
                                                                style="background: #f5f8fa"
                                                                class="table table-hover"
                                                                :id="'tbl-purchase-type-' + category.id">

                                                                <thead>
                                                                <tr class="info">
                                                                    <th>{{ $t('message.purchases_config.c_id') }}</th>
                                                                    <th>{{ $t('message.purchases_config.c_name') }}</th>
                                                                    <th>{{ $t('message.purchases_config.c_desc') }}</th>
                                                                    <th>{{ $t('message.purchases_config.c_action') }}</th>
                                                                </tr>
                                                                </thead>

                                                                <tbody>
                                                                <tr v-for="(type, index) in category.types" :key="type.id">
                                                                    <td>{{ type.id }}</td>
                                                                    <td>{{ type.name }}</td>
                                                                    <td>{{ type.description }}</td>
                                                                    <td class="text-center">
                                                                        <button
                                                                            v-if="user.permission_list.includes('update-purchases-config')"
                                                                            title="Edit Category"
                                                                            :title="$t('message.purchases_config.c_edit_type')"
                                                                            class="btn btn-primary m-1"

                                                                            @click="fillTypeData('Update', category, type); modalOpenerType('Update')">

                                                                            <i class="fas fa-pen-square"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <pagination
                                :limit="8"
                                :data="categories"

                                @pagination-change-page="getPurchaseCategories">

                            </pagination>
                        </div>

                        <div v-else class="col-md-12 text-center card">
                            <div class="card-body">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $t('message.purchases_config.c_no_results') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Category -->
        <div
            class="modal fade"
            ref="modalCategory"
            style="display: none;"
            id="modal-purchase-category"
            data-backdrop="static">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            {{ $t('message.purchases_config.c_purchase_category') }}
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_config.c_cat_name') }}</label>

                                    <input
                                        v-model="modelCategoryName"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_config.c_enter_cat_name')">

                                    <span
                                        v-if="messageFormsCategories.errors.name"
                                        v-for="err in messageFormsCategories.errors.name"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_config.c_cat_desc') }}</label>

                                    <textarea
                                        v-model="modelCategoryDescription"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_config.c_enter_cat_desc')">

                                    </textarea>

                                    <span
                                        v-if="messageFormsCategories.errors.description"
                                        v-for="err in messageFormsCategories.errors.description"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalMode === 'Add'" type="button" @click="addCategory" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updateCategory" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Type -->
        <div
            class="modal fade"
            ref="modalType"
            style="display: none;"
            id="modal-purchase-types"
            data-backdrop="static">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalModeType === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            {{ $t('message.purchases_config.c_purchase_type') }}
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_config.c_purchase_category') }}</label>

                                    <input
                                        v-model="modelTypeCategory"
                                        disabled
                                        type="text"
                                        class="form-control">

                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_config.c_type_name') }}</label>

                                    <input
                                        v-model="modelTypeName"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_config.c_enter_type_name')">

                                    <span
                                        v-if="messageFormsTypes.errors.name"
                                        v-for="err in messageFormsTypes.errors.name"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_config.c_type_desc') }}</label>

                                    <textarea
                                        v-model="modelTypeDescription"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_config.c_enter_type_desc')">

                                    </textarea>

                                    <span
                                        v-if="messageFormsTypes.errors.description"
                                        v-for="err in messageFormsTypes.errors.description"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloserType">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalModeType === 'Add'" type="button" @click="addType" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updateType" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import axios from 'axios';
import VueVirtualTable from 'vue-virtual-table';
import {Constants} from "../../../mixins/constants";

export default {
    mixins: [Constants],
    components : {
        VueVirtualTable,
    },

    data() {
        return {
            filterModel: {
                page : this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 10,
            },

            addCategoryModel: {
                name: '',
                description: '',
            },

            updateCategoryModel: {
                id: '',
                name: '',
                description: '',
            },

            addTypeModel: {
                name: '',
                description: '',
                category_name: '',
                purchase_category_id: '',
            },

            updateTypeModel: {
                id: '',
                name: '',
                description: '',
                category_name: '',
                purchase_category_id: ''
            },

            modalMode: 'Add',
            modalModeType: 'Add',
            pageOptions: [10, 25, 50, 100, 'All'],
        }
    },

    mounted () {
        this.getPurchaseCategories(this.$route.query.page)
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            categories : state => state.storePurchases.categories,
            messageFormsCategories : state => state.storePurchases.messageFormsCategories,
            messageFormsTypes : state => state.storePurchases.messageFormsTypes,
        }),

        modelCategoryName: {
            get () {
                return this.modalMode === 'Add' ? this.addCategoryModel.name : this.updateCategoryModel.name
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addCategoryModel.name = val
                } else {
                    this.updateCategoryModel.name = val
                }
            }
        },

        modelCategoryDescription: {
            get () {
                return this.modalMode === 'Add' ? this.addCategoryModel.description : this.updateCategoryModel.description
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addCategoryModel.description = val
                } else {
                    this.updateCategoryModel.description = val
                }
            }
        },

        modelTypeCategory: {
            get () {
                return this.modalModeType === 'Add' ? this.addTypeModel.category_name : this.updateTypeModel.category_name
            },
            set (val) {
                if (this.modalModeType === 'Add') {
                    this.addTypeModel.category_name = val
                } else {
                    this.updateTypeModel.category_name = val
                }
            }
        },

        modelTypeCategoryId: {
            get () {
                return this.modalModeType === 'Add' ? this.addTypeModel.purchase_category_id : this.updateTypeModel.purchase_category_id
            },
            set (val) {
                if (this.modalModeType === 'Add') {
                    this.addTypeModel.purchase_category_id = val
                } else {
                    this.updateTypeModel.purchase_category_id = val
                }
            }
        },

        modelTypeName: {
            get () {
                return this.modalModeType === 'Add' ? this.addTypeModel.name : this.updateTypeModel.name
            },
            set (val) {
                if (this.modalModeType === 'Add') {
                    this.addTypeModel.name = val
                } else {
                    this.updateTypeModel.name = val
                }
            }
        },

        modelTypeDescription: {
            get () {
                return this.modalModeType === 'Add' ? this.addTypeModel.description : this.updateTypeModel.description
            },
            set (val) {
                if (this.modalModeType === 'Add') {
                    this.addTypeModel.description = val
                } else {
                    this.updateTypeModel.description = val
                }
            }
        },
    },

    methods: {
        async getPurchaseCategories (page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            this.$router.push({
                query: {
                    page: self.filterModel.page
                }
            }).then(r => {

            })

            await self.$store.dispatch('actionGetPurchaseCategories', {params : self.filterModel});

            loader.hide();
        },

        async addCategory () {
            let self = this;
            let loader = self.$loading.show();

            await self.$store.dispatch('actionAddPurchaseCategory', self.addCategoryModel);

            if (self.messageFormsCategories.action === 'added') {

                self.modalCloser();
                self.clearModel('add');
                await self.getPurchaseCategories(self.filterModel.page);
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.purchases_config.a_category_added'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsCategories.message,
                    'error'
                )
            }
        },

        async updateCategory () {
            let self = this;
            let loader = self.$loading.show();

            await self.$store.dispatch('actionUpdatePurchaseCategory', self.updateCategoryModel);

            if (self.messageFormsCategories.action === 'updated') {

                self.modalCloser();
                self.clearModel('update');
                await self.getPurchaseCategories(self.filterModel.page);
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.purchases_config.a_category_updated'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsCategories.message,
                    'error'
                )
            }
        },

        async addType () {
            let self = this;
            let loader = self.$loading.show();

            await self.$store.dispatch('actionAddPurchaseType', self.addTypeModel);

            if (self.messageFormsTypes.action === 'added') {

                self.clearPurchaseTypeTable(self.addTypeModel.purchase_category_id);
                self.modalCloserType();
                self.clearModelType('add');
                await self.getPurchaseCategories(self.filterModel.page);
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.purchases_config.a_type_added'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsTypes.message,
                    'error'
                )
            }
        },

        async updateType () {
            let self = this;
            let loader = self.$loading.show();

            await self.$store.dispatch('actionUpdatePurchaseType', self.updateTypeModel);

            if (self.messageFormsTypes.action === 'updated') {

                self.modalCloserType();
                self.clearModelType('update');
                await self.getPurchaseCategories(self.filterModel.page);
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.purchases_config.a_type_updated'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsTypes.message,
                    'error'
                )
            }
        },

        fillCategoryData (data) {
            this.updateCategoryModel.id = data.id;
            this.updateCategoryModel.name = data.name;
            this.updateCategoryModel.description = data.description;
        },

        fillTypeData (mod, category, type = null) {
            if (mod === 'Add') {
                this.addTypeModel.category_name = category.name;
                this.addTypeModel.purchase_category_id = category.id;
            } else {
                this.updateTypeModel.id = type ? type.id : '';
                this.updateTypeModel.name = type ? type.name : '';
                this.updateTypeModel.category_name = category.name
                this.updateTypeModel.purchase_category_id = category.id;
                this.updateTypeModel.description = type ? type.description : '';
            }
        },

        clearModel (mod) {
            switch(mod) {
                case 'add':
                    this.addCategoryModel = {
                        name: '',
                        description: '',
                    }

                    break;
                case 'update':
                    this.updateCategoryModel = {
                        id: '',
                        name: '',
                        description: '',
                    }

                    break;
                case 'filter':
                    this.filterModel = {
                        page: 1,
                        paginate: this.filterModel.paginate
                    }

                    break;
                default:
                    this.clearCategoryMessageForm();
            }
        },

        clearModelType (mod) {
            switch(mod) {
                case 'add':
                    this.addTypeModel = {
                        name: '',
                        description: '',
                        category_name: '',
                        purchase_category_id: '',
                    }

                    break;
                case 'update':
                    this.updateTypeModel = {
                        id: '',
                        name: '',
                        description: '',
                        category_name: '',
                        purchase_category_id: ''
                    }

                    break;
                case 'filter':
                    this.filterModel = {
                        page: 1,
                        paginate: this.filterModel.paginate
                    }

                    break;
                default:
                    this.clearTypeMessageForm();
            }
        },

        modalOpener (mode) {
            this.modalMode = mode

            let element = this.$refs.modalCategory
            $(element).modal('show')
        },

        modalOpenerType (mode) {
            this.modalModeType = mode

            let element = this.$refs.modalType
            $(element).modal('show')
        },

        modalCloser () {
            let element = this.$refs.modalCategory
            $(element).modal('hide')

            this.clearModel('error');
        },

        modalCloserType () {
            let element = this.$refs.modalType
            $(element).modal('hide')

            this.clearModelType('error');
        },

        clearCategoryMessageForm() {
            this.$store.dispatch('clearMessageFormCategories');
        },

        clearTypeMessageForm() {
            this.$store.dispatch('clearMessageFormTypes');
        },

        checkExpanded (id) {
            let self = this;
            let expanded = self.$refs['purchase-collapse-' + id][0].getAttribute('aria-expanded')
            let caret = this.$refs['purchase-caret-' + id][0];

            if (expanded == null || expanded === false || expanded === 'false') {
                caret.classList.value = 'fas fa-angle-up'
            } else {
                caret.classList.value = 'fas fa-angle-down'
            }
        },

        showPurchaseTypes (id) {
            let dataTable = $('#tbl-purchase-type-' + id);

            dataTable.DataTable().destroy();

            dataTable.DataTable({
                paging : true,
                searching : true,
                "sPaginationType":"full_numbers",
                // "dom": '<"top"fli>rt<"bottom"p><"clear">',
                "dom": '<"row align-items-center"<"col-sm-4"l><"col-sm-4 text-center"i><"col-sm-4"f>>rt<"bottom"p>',
                columnDefs : [
                    {orderable : true, targets : 0},
                    {orderable : true, targets : 1},
                    {orderable : true, targets : 2},
                    {orderable : false, targets : '_all'}
                ],
                fixedHeader: {
                    header: true,
                    footer: true
                }
            });
        },

        clearPurchaseTypeTable (id) {
            let dataTable = $('#tbl-purchase-type-' + id);
            dataTable.DataTable().destroy();
        }
    },
}
</script>

<style>
    .dataTables_wrapper .dataTables_info {
        clear: both !important;
        display: revert !important;
        padding-top: 0 !important;
        margin-bottom: .5rem;
        font-weight: 700;
    }
</style>
