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

        <!-- FILTERS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $t('message.module_page.filter_title') }}</h3>
                        <div class="card-tools" style="float: left!important;">
                            <button
                                type="button"
                                class="btn btn-primary ml-5"
                                data-toggle="collapse"
                                data-target="#collapsePurchaseManual"
                                aria-expanded="false"
                                aria-controls="collapsePurchaseManual">

                                <i class="fa fa-plus"></i>
                                {{ $t('message.module_page.filter_show') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapsePurchaseManual">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchase Category</label>

                                    <v-select
                                        v-model="filterModel.category_id"
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Purchase Category"
                                        :searchable="true"
                                        :reduce="category => category.id"
                                        :options="categoriesSelection.data"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchase Type</label>

                                    <v-select
                                        v-model="filterModel.type_id"
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Purchase Type"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="typesSelection.data"/>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchased From</label>

                                    <input
                                        v-model="filterModel.from"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Purchased From">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchased By</label>

                                    <v-select
                                        v-model="filterModel.user_id"
                                        label="username"
                                        class="style-chooser"
                                        placeholder="Select Purchased By"
                                        :searchable="true"
                                        :reduce="user => user.id"
                                        :options="purchasesBuyers.data"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchased Via</label>

                                    <v-select
                                        v-model="filterModel.payment_type_id"
                                        label="type"
                                        class="style-chooser"
                                        placeholder="Select Purchase Via"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="listPayment.data"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.wallet_transaction.filter_date') }}</label>

                                    <div class="input-group">
                                        <date-range-picker
                                            v-model="filterModel.date"
                                            opens="left"
                                            ref="picker"
                                            style="width: 100%"
                                            :linkedCalendars="true"
                                            :dateRange="filterModel.date"
                                            :ranges="generateDefaultDateRange()"
                                            :locale-data="{ firstDay: 1, format: 'mm/dd/yyyy' }"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchased Module</label>

                                    <v-select
                                        v-model="filterModel.module"
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Purchase Module"
                                        :searchable="true"
                                        :reduce="module => module.value"
                                        :options="moduleSelection.data"/>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearFilter">
                                    {{ $t('message.module_page.clear') }}
                                </button>
                                <button class="btn btn-default" @click="filterPurchases">
                                    {{ $t('message.module_page.search') }}
                                    <i v-if="false" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CATEGORY REPORT -->
        <category-report-graph
            v-if="(user.isAdmin || user.role_id === 8) && purchases.data.length"
            ref="categoryReportGraph"
            :filter-model="filterModel">

        </category-report-graph>

        <!-- PURCHASE TYPE REPORT -->
        <purchase-type-report-graph
            v-if="(user.isAdmin || user.role_id === 8) && purchases.data.length"
            ref="purchaseTypeReportGraph"
            :filter-model="filterModel">

        </purchase-type-report-graph>

        <!-- PAYMENT METHODS REPORT -->
        <payment-method-report-graph
            v-if="(user.isAdmin || user.role_id === 8) && purchases.data.length"
            ref="paymentMethodReportGraph"
            :filter-model="filterModel">

        </payment-method-report-graph>

        <!-- TABLE AND GRAPHS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Purchase: Summary</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="purchases.total" class="pagination-custom-footer-text m-0 mt-2">
                                    <b v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: purchases.from, to: purchases.to, end: purchases.total }) }}
                                    </b>

                                    <b v-else>
                                        {{ $t('message.others.table_all_entries', { total: purchases.total }) }}
                                    </b>
                                </span>
                            </div>

                            <div class="col-6">
                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control"
                                        style="height: 37px;"

                                        @change="getPurchases()">

                                        <option v-for="value in pageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-if="purchases.data.length">
                            <vue-virtual-table
                                width="100%"
                                :min-width="450"
                                :height="600"
                                :bordered="true"
                                :item-height="60"
                                :hover-highlight="true"
                                :config="tableConfig"
                                :enable-export="true"
                                :data="purchases.data">

                                <template slot-scope="scope" slot="id">
                                    <div style="display: block">
                                        {{ scope.row.id }}

                                        <div>
                                            <span class="badge badge-primary">
                                                {{ getModuleName(scope.row.purchaseable_type) }}
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <template slot-scope="scope" slot="category">
                                    <span>
                                        {{
                                            scope.row.type
                                                ? scope.row.type['category']
                                                ? scope.row.type['category'].name
                                                : 'N/A'
                                                : 'N/A'
                                        }}
                                    </span>
                                </template>

                                <template slot-scope="scope" slot="type">
                                    <span>
                                        {{ scope.row.type ? scope.row.type.name : 'N/A' }}
                                    </span>
                                </template>

                                <template slot-scope="scope" slot="purchase-by">
                                    <span>
                                        {{ scope.row.user ? scope.row.user.username : 'N/A' }}
                                    </span>
                                </template>

                                <template slot-scope="scope" slot="purchase-via">
                                    <span>
                                        {{ scope.row.payment_type ? scope.row.payment_type.type : 'N/A' }}
                                    </span>
                                </template>

                                <template slot-scope="scope" slot="action-buttons">
                                    <button
                                        title="Edit"
                                        class="btn btn-primary m-1"
                                        :disabled="!user.isAdmin || user.role_id === 8"

                                        @click="fillPurchaseData(scope.row); modalOpener('Update')">

                                        <i class="fas fa-pen-square"></i>
                                    </button>

                                    <button
                                        title="Delete"
                                        class="btn btn-danger"
                                        :disabled="!user.isAdmin || user.role_id === 8"

                                        @click="deletePurchase(scope.row.id)">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                </template>

                            </vue-virtual-table>

                            <pagination
                                :limit="8"
                                :data="purchases"

                                @pagination-change-page="getPurchases">

                            </pagination>
                        </div>

                        <div v-else class="col-md-12 text-center card">
                            <div class="card-body">
                                <i class="fas fa-exclamation-circle"></i>
                                No results found
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Purchase -->
        <div
            class="modal fade"
            ref="modalPurchase"
            style="display: none;"
            id="modal-purchase"
            data-backdrop="static">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            Purchase
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase Date</label>

                                    <input
                                        v-model="modelDate"
                                        type="date"
                                        class="form-control">

                                    <span
                                        v-if="messageFormsPurchases.errors.purchased_at"
                                        v-for="err in messageFormsPurchases.errors.purchased_at"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase From</label>

                                    <input
                                        v-model="modelFrom"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Purchase From">

                                    <span
                                        v-if="messageFormsPurchases.errors.from"
                                        v-for="err in messageFormsPurchases.errors.from"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase Type</label>

                                    <v-select
                                        v-model="modelTypeId"
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Purchase Type"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="typesSelection.data"/>

                                    <span
                                        v-if="messageFormsPurchases.errors.type_id"
                                        v-for="err in messageFormsPurchases.errors.type_id"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>

                                    <input
                                        v-model="modelAmount"
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Price">

                                    <span
                                        v-if="messageFormsPurchases.errors.amount"
                                        v-for="err in messageFormsPurchases.errors.amount"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase Via</label>

                                    <v-select
                                        v-model="modelPaymentTypeId"
                                        label="type"
                                        class="style-chooser"
                                        placeholder="Select Purchase Via"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="listPayment.data"/>

                                    <span
                                        v-if="messageFormsPurchases.errors.payment_type_id"
                                        v-for="err in messageFormsPurchases.errors.payment_type_id"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div v-if="modalMode !== 'Update'" class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Photo</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="purchaseFile"
                                        name="purchaseFile"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsPurchases.errors.file"
                                        v-for="err in messageFormsPurchases.errors.file"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">Notes</label>

                                    <textarea
                                        v-model="modelNotes"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Purchase Note">

                                    </textarea>

                                    <span
                                        v-if="messageFormsPurchases.errors.notes"
                                        v-for="err in messageFormsPurchases.errors.notes"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'Update' && updatePurchaseModel.file" class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">Uploaded Photo:</label>

                                    <div class="card" style="cursor: pointer;" @click="previewFile">
                                        <div class="card-body">
                                            <img
                                                v-if="updatePurchaseModel.file"
                                                width="350"
                                                style="min-height: 200px; height: 200px"
                                                alt="Uploaded purchase photo"
                                                class="img-thumbnail"
                                                :src="updatePurchaseModel.file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'Update'" class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">Update Photo:</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="purchaseFileUpdate"
                                        name="purchaseFileUpdate"
                                        enctype="multipart/form-data">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalMode === 'Add'" type="button" @click="addPurchase" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updatePurchase" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Purchase End -->

        <!-- Modal Image Preview -->
        <div
            class="modal fade"
            ref="modalPurchaseImage"
            style="display: none;"
            id="modal-purchase-image">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img
                            class="img-fluid img-thumbnail rounded mx-auto d-block"
                            alt="Uploaded purchase photo"
                            :src="updatePurchaseModel.file">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import VueVirtualTable from 'vue-virtual-table';
import {Constants} from "../../../mixins/constants";
import {dateRange} from "../../../mixins/dateRange";
import {filterModel} from "../../../mixins/filterModel";
import CategoryReportGraph from "../../../components/graphs/Purchases/CategoryReportGraph";
import PurchaseTypeReportGraph from "../../../components/graphs/Purchases/PurchaseTypeReportGraph";
import PaymentMethodReportGraph from "../../../components/graphs/Purchases/PaymentMethodReportGraph";

export default {
    mixins: [Constants, dateRange, filterModel],
    components : {
        VueVirtualTable,
        CategoryReportGraph,
        PurchaseTypeReportGraph,
        PaymentMethodReportGraph
    },
    data() {
        return {
            filterModel: {
                mode: 'summary',
                from : this.$route.query.from || '',
                page : this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 10,
                module: this.$route.query.module || '',
                type_id: parseInt(this.$route.query.type_id) || '',
                user_id: parseInt(this.$route.query.user_id) || '',
                category_id: parseInt(this.$route.query.category_id) || '',
                payment_type_id: parseInt(this.$route.query.payment_type_id) || '',
                date : {
                    startDate : null,
                    endDate : null
                },
            },

            addPurchaseModel: {
                from: '',
                notes: '',
                amount: '',
                type_id: '',
                mode: 'manual',
                purchased_at: '',
                payment_type_id: '',
            },

            updatePurchaseModel: {
                id: '',
                from: '',
                file: '',
                notes: '',
                amount: '',
                type_id: '',
                mode: 'manual',
                purchased_at: '',
                payment_type_id: '',
            },

            modalMode: 'Add',
            pageOptions: [10, 25, 50, 100, 'All'],
        }
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            purchases : state => state.storePurchases.purchases,
            purchasesBuyers : state => state.storePurchases.purchasesBuyers,
            typesSelection : state => state.storePurchases.typesSelection,
            moduleSelection : state => state.storePurchases.moduleSelection,
            listPayment : state => state.storeWalletTransaction.listPayment,
            categoriesSelection : state => state.storePurchases.categoriesSelection,
            messageFormsPurchases : state => state.storePurchases.messageFormsPurchases,
        }),

        modelDate: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.purchased_at
                    : this.updatePurchaseModel.purchased_at
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.purchased_at = val
                } else {
                    this.updatePurchaseModel.purchased_at = val
                }
            }
        },

        modelFrom: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.from
                    : this.updatePurchaseModel.from
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.from = val
                } else {
                    this.updatePurchaseModel.from = val
                }
            }
        },

        modelTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.type_id
                    : this.updatePurchaseModel.type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.type_id = val
                } else {
                    this.updatePurchaseModel.type_id = val
                }
            }
        },

        modelAmount: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.amount
                    : this.updatePurchaseModel.amount
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.amount = val
                } else {
                    this.updatePurchaseModel.amount = val
                }
            }
        },

        modelPaymentTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.payment_type_id
                    : this.updatePurchaseModel.payment_type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.payment_type_id = val
                } else {
                    this.updatePurchaseModel.payment_type_id = val
                }
            }
        },

        modelNotes: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addPurchaseModel.notes
                    : this.updatePurchaseModel.notes
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addPurchaseModel.notes = val
                } else {
                    this.updatePurchaseModel.notes = val
                }
            }
        },

        tableConfig() {
            return [
                {
                    prop : '_action',
                    name : 'ID',
                    sortable: true,
                    actionName : 'id',
                },
                {
                    prop : 'full_clean_date',
                    name : 'Date',
                },
                {
                    prop : '_action',
                    name : 'Category',
                    actionName : 'category',
                },
                {
                    prop : '_action',
                    name : 'Type',
                    actionName : 'type',
                },
                {
                    prop : 'from',
                    name : 'From',
                },
                {
                    prop : 'amount',
                    name : 'Amount',
                    prefix : '$ '
                },
                {
                    prop : '_action',
                    name : 'Purchased By',
                    actionName : 'purchase-by',
                },
                {
                    prop : '_action',
                    name : 'Purchased Via',
                    actionName : 'purchase-via',
                },
                {
                    prop : '_action',
                    name : this.$t('message.publisher.t_action'),
                    actionName : 'action-buttons',
                    width : '150',
                },
            ];
        },
    },

    mounted () {
        this.getPurchases(this.$route.query.page);
        this.getBuyers();
        this.getPaymentTypes();
        this.getPurchaseTypes();
        this.getPurchaseModules();
        this.getPurchaseCategories();
    },

    methods: {
        async getPurchases (page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            this.$router.push({
                query : this.cleanFilterModel(this.filterModel)
            }).then(r => {

            })

            await self.$store.dispatch('actionGetPurchases', {params : self.filterModel});

            loader.hide();
        },

        filterPurchases () {
            // change the format of date
            this.filterModel.date = this.formatFilterDates(this.filterModel.date)

            this.getPurchases(1);
            this.$refs.categoryReportGraph.getCategoryReportData();
            this.$refs.purchaseTypeReportGraph.getPurchaseTypeReportData();
            this.$refs.paymentMethodReportGraph.getPaymentMethodReportData();
        },

        addPurchase () {

        },

        async updatePurchase () {
            let self = this;
            let loader = self.$loading.show();

            let formDataPurchaseUpdate = self.generateFormData(self.updatePurchaseModel)

            // add _method put to read formData as put request - make axios into post instead of put
            formDataPurchaseUpdate.append("_method", "PUT")
            formDataPurchaseUpdate.append('file', self.$refs.purchaseFileUpdate.files[0]);

            await self.$store.dispatch('actionUpdatePurchase', formDataPurchaseUpdate);

            if (self.messageFormsPurchases.action === 'updated') {

                self.modalCloser();
                self.clearModel('update');
                await self.getPurchases(self.filterModel.page);
                loader.hide();

                // clear file input
                this.$refs.purchaseFileUpdate.value = '';

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    'Purchase successfully updated.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    'There were some errors while updating the purchase details.',
                    'error'
                )
            }
        },

        async deletePurchase (id) {
            let self = this;

            let result = await swal.fire({
                title : 'Delete Purchase',
                text : 'Are you sure that you want to delete this purchase?',
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })

            if (result.isConfirmed) {
                await self.$store.dispatch('actionDeletePurchase', id);

                if (self.messageFormsPurchases.action === 'deleted') {

                    let page = self.purchases.data.length <= 1 ? 1 : self.filterModel.page;

                    await self.getPurchases(page);

                    await swal.fire(
                        self.$t('message.tools.alert_deleted'),
                        'Purchase successfully deleted.',
                        'success'
                    )
                } else {
                    await swal.fire(
                        self.$t('message.tools.alert_error'),
                        'There were some errors while deleting the purchase details.',
                        'error'
                    )
                }
            }
        },

        fillPurchaseData (data) {
            this.updatePurchaseModel.id = data.id;
            this.updatePurchaseModel.from = data.from;
            this.updatePurchaseModel.file = data.file ? data.file.path : '';
            this.updatePurchaseModel.notes = data.notes;
            this.updatePurchaseModel.amount = data.amount;
            this.updatePurchaseModel.type_id = data.type_id;
            this.updatePurchaseModel.purchased_at = data.purchased_at;
            this.updatePurchaseModel.payment_type_id = data.payment_type_id;
        },

        async clearFilter () {
            this.clearModel('filter')
            await this.getPurchases(1);

            await this.$refs.categoryReportGraph.getCategoryReportData();
            await this.$refs.purchaseTypeReportGraph.getPurchaseTypeReportData();
            await this.$refs.paymentMethodReportGraph.getPaymentMethodReportData();
        },

        async getBuyers () {
            await this.$store.dispatch('actionGetPurchaseBuyerSelection', 'summary');
        },

        async getPaymentTypes (params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },

        async getPurchaseCategories () {
            await this.$store.dispatch('actionGetPurchaseCategoriesSelection');
        },

        async getPurchaseTypes () {
            await this.$store.dispatch('actionGetPurchaseTypesSelection');
        },

        async getPurchaseModules () {
            await this.$store.dispatch('actionGetPurchaseModuleSelection');
        },

        clearModel (mod) {
            switch(mod) {
                case 'add':
                    this.addPurchaseModel = {
                        from: '',
                        notes: '',
                        amount: '',
                        type_id: '',
                        purchased_at: '',
                        payment_type_id: '',
                    }

                    break;
                case 'update':
                    this.updatePurchaseModel = {
                        id: '',
                        from: '',
                        file: '',
                        notes: '',
                        amount: '',
                        type_id: '',
                        purchased_at: '',
                        payment_type_id: '',
                    }

                    break;
                case 'filter':
                    this.filterModel = {
                        page: 1,
                        from : '',
                        module: '',
                        type_id: '',
                        user_id: '',
                        mode: 'summary',
                        category_id: '',
                        payment_type_id: '',
                        paginate: this.filterModel.paginate,
                        date : {
                            startDate : null,
                            endDate : null
                        },
                    }

                    break;
                default:
                    this.clearMessageFormPurchases();
            }
        },

        clearMessageFormPurchases() {
            this.$store.dispatch('clearMessageFormPurchases');
        },

        modalOpener (mode) {
            this.modalMode = mode

            let element = this.$refs.modalPurchase
            $(element).modal('show')
        },

        modalCloser () {
            let element = this.$refs.modalPurchase
            $(element).modal('hide')

            this.clearModel('error');
        },

        previewFile () {
            let element = this.$refs.modalPurchaseImage
            $(element).modal('show')
        },

        getModuleName (type) {
            let module = this.moduleSelection.data.find(item => item.purchaseable_type === type)

            if (module) {
                return module.name
            }
        }
    },
}
</script>

<style scoped>

</style>
