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
                                        :options="manualBuyers.data"/>
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
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-default" @click="clearFilter()">
                                    {{ $t('message.module_page.clear') }}
                                </button>
                                <button class="btn btn-default" @click="filterManualPurchases()">
                                    {{ $t('message.module_page.search') }}
                                    <i v-if="false" class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">Purchase: Manual</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="manuals.total" class="pagination-custom-footer-text m-0 mt-2">
                                    <b v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: manuals.from, to: manuals.to, end: manuals.total }) }}
                                    </b>

                                    <b v-else>
                                        {{ $t('message.others.table_all_entries', { total: manuals.total }) }}
                                    </b>
                                </span>
                            </div>
                            <div class="col-6">
                                <button
                                    v-if="user.isAdmin || user.role_id === 8"
                                    title="Add Manual Purchase"
                                    class="btn btn-success float-right"

                                    @click="modalOpener('Add')">

                                    <i class="fas fa-plus"></i>
                                </button>

                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control"
                                        style="height: 37px;"

                                        @change="getManualPurchases()">

                                        <option v-for="value in pageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-if="manuals.data.length">
                            <vue-virtual-table
                                width="100%"
                                :min-width="450"
                                :height="600"
                                :bordered="true"
                                :item-height="60"
                                :hover-highlight="true"
                                :config="tableConfig"
                                :enable-export="true"
                                :data="manuals.data">

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

                                        @click="fillManualData(scope.row); modalOpener('Update')">

                                        <i class="fas fa-pen-square"></i>
                                    </button>

                                    <button
                                        title="Delete"
                                        class="btn btn-danger"
                                        :disabled="!user.isAdmin || user.role_id === 8"

                                        @click="deleteManual(scope.row.id)">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                </template>

                            </vue-virtual-table>

                            <pagination
                                :limit="8"
                                :data="manuals"

                                @pagination-change-page="getManualPurchases">

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

        <!-- Modal Manual Purchase -->
        <div
            class="modal fade"
            ref="modalManualPurchase"
            style="display: none;"
            id="modal-manual-purchase"
            data-backdrop="static">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            Manual Purchase
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase Date</label>

                                    <input
                                        v-model="modelManualDate"
                                        type="date"
                                        class="form-control">

                                    <span
                                        v-if="messageFormsManualPurchases.errors.purchased_at"
                                        v-for="err in messageFormsManualPurchases.errors.purchased_at"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Purchase From</label>

                                    <input
                                        v-model="modelManualFrom"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Purchase From">

                                    <span
                                        v-if="messageFormsManualPurchases.errors.from"
                                        v-for="err in messageFormsManualPurchases.errors.from"
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
                                        v-model="modelManualTypeId"
                                        label="name"
                                        class="style-chooser"
                                        placeholder="Select Purchase Type"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="typesSelection.data"/>

                                    <span
                                        v-if="messageFormsManualPurchases.errors.type_id"
                                        v-for="err in messageFormsManualPurchases.errors.type_id"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">Price</label>

                                    <input
                                        v-model="modelManualAmount"
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Price">

                                    <span
                                        v-if="messageFormsManualPurchases.errors.amount"
                                        v-for="err in messageFormsManualPurchases.errors.amount"
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
                                        v-model="modelManualPaymentTypeId"
                                        label="type"
                                        class="style-chooser"
                                        placeholder="Select Purchase Via"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="listPayment.data"/>

                                    <span
                                        v-if="messageFormsManualPurchases.errors.payment_type_id"
                                        v-for="err in messageFormsManualPurchases.errors.payment_type_id"
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
                                        ref="manualPurchaseFile"
                                        name="manualPurchaseFile"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsManualPurchases.errors.file"
                                        v-for="err in messageFormsManualPurchases.errors.file"
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
                                        v-model="modelManualNotes"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Purchase Note">

                                    </textarea>

                                    <span
                                        v-if="messageFormsManualPurchases.errors.notes"
                                        v-for="err in messageFormsManualPurchases.errors.notes"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'Update' && updateManualPurchaseModel.file" class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">Uploaded Photo:</label>

                                    <div class="card" style="cursor: pointer;" @click="previewFile">
                                        <div class="card-body">
                                            <img
                                                v-if="updateManualPurchaseModel.file"
                                                width="350"
                                                style="min-height: 200px; height: 200px"
                                                alt="Uploaded purchase photo"
                                                class="img-thumbnail"
                                                :src="updateManualPurchaseModel.file">
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
                                        ref="manualPurchaseFileUpdate"
                                        name="manualPurchaseFileUpdate"
                                        enctype="multipart/form-data">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="modalCloser">
                            {{ $t('message.signature.close') }}
                        </button>
                        <button v-if="modalMode === 'Add'" type="button" @click="addManualPurchase" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updateManualPurchase" class="btn btn-primary">
                            {{ $t('message.signature.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Manual Purchase End -->

        <!-- Modal Image Preview -->
        <div
            class="modal fade"
            ref="modalManualPurchaseImage"
            style="display: none;"
            id="modal-manual-purchase-image">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img
                            class="img-fluid img-thumbnail rounded mx-auto d-block"
                            alt="Uploaded purchase photo"
                            :src="updateManualPurchaseModel.file">
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

export default {
    mixins: [Constants, dateRange, filterModel],
    components : {
        VueVirtualTable,
    },

    data() {
        return {
            filterModel: {
                mode: 'manual',
                from : this.$route.query.from || '',
                page : this.$route.query.page || 0,
                paginate: this.$route.query.paginate || 10,
                type_id: parseInt(this.$route.query.type_id) || '',
                user_id: parseInt(this.$route.query.user_id) || '',
                category_id: parseInt(this.$route.query.category_id) || '',
                payment_type_id: parseInt(this.$route.query.payment_type_id) || '',
                date : {
                    startDate : null,
                    endDate : null
                },
            },

            addManualPurchaseModel: {
                from: '',
                notes: '',
                amount: '',
                type_id: '',
                mode: 'manual',
                purchased_at: '',
                payment_type_id: '',
            },

            updateManualPurchaseModel: {
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

    mounted () {
        this.getManualPurchases(this.$route.query.page);
        this.getPaymentTypes();
        this.getManualBuyers();
        this.getPurchaseTypes();
        this.getPurchaseCategories();
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            manuals : state => state.storePurchases.manuals,
            manualBuyers : state => state.storePurchases.manualBuyers,
            typesSelection : state => state.storePurchases.typesSelection,
            listPayment : state => state.storeWalletTransaction.listPayment,
            categoriesSelection : state => state.storePurchases.categoriesSelection,
            messageFormsManualPurchases : state => state.storePurchases.messageFormsManualPurchases,
        }),

        modelManualDate: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.purchased_at
                    : this.updateManualPurchaseModel.purchased_at
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.purchased_at = val
                } else {
                    this.updateManualPurchaseModel.purchased_at = val
                }
            }
        },

        modelManualFrom: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.from
                    : this.updateManualPurchaseModel.from
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.from = val
                } else {
                    this.updateManualPurchaseModel.from = val
                }
            }
        },

        modelManualTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.type_id
                    : this.updateManualPurchaseModel.type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.type_id = val
                } else {
                    this.updateManualPurchaseModel.type_id = val
                }
            }
        },

        modelManualAmount: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.amount
                    : this.updateManualPurchaseModel.amount
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.amount = val
                } else {
                    this.updateManualPurchaseModel.amount = val
                }
            }
        },

        modelManualPaymentTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.payment_type_id
                    : this.updateManualPurchaseModel.payment_type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.payment_type_id = val
                } else {
                    this.updateManualPurchaseModel.payment_type_id = val
                }
            }
        },

        modelManualNotes: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addManualPurchaseModel.notes
                    : this.updateManualPurchaseModel.notes
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addManualPurchaseModel.notes = val
                } else {
                    this.updateManualPurchaseModel.notes = val
                }
            }
        },

        tableConfig() {
            return [
                {
                    prop : 'id',
                    name : 'ID',
                    sortable: true,
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

    methods: {
        async getManualPurchases (page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            this.$router.push({
                query : this.cleanFilterModel(this.filterModel)
            }).then(r => {

            })

            await self.$store.dispatch('actionGetManualPurchase', {params : self.filterModel});

            loader.hide();
        },

        filterManualPurchases () {
            // change the format of date
            this.filterModel.date = this.formatFilterDates(this.filterModel.date)

            this.getManualPurchases(1);
        },

        clearFilter () {
            this.clearModel('filter')
            this.getManualPurchases(1);
        },

        async addManualPurchase () {
            let self = this;
            let loader = self.$loading.show();

            let formDataManualPurchase = self.generateFormData(self.addManualPurchaseModel)

            formDataManualPurchase.append('file', self.$refs.manualPurchaseFile.files[0]);

            await self.$store.dispatch('actionAddManualPurchase', formDataManualPurchase);

            if (self.messageFormsManualPurchases.action === 'added') {

                self.modalCloser();
                self.clearModel('add');
                await self.getManualPurchases(self.filterModel.page);
                loader.hide();

                self.$refs.manualPurchaseFile.value = '';

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    'Manual Purchase successfully added.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    'There were some errors while adding the manual purchase.',
                    'error'
                )
            }
        },

        async updateManualPurchase () {
            let self = this;
            let loader = self.$loading.show();

            let formDataManualPurchaseUpdate = self.generateFormData(self.updateManualPurchaseModel)

            // add _method put to read formData as put request - make axios into post instead of put
            formDataManualPurchaseUpdate.append("_method", "PUT")
            formDataManualPurchaseUpdate.append('file', self.$refs.manualPurchaseFileUpdate.files[0]);

            await self.$store.dispatch('actionUpdateManualPurchase', formDataManualPurchaseUpdate);

            if (self.messageFormsManualPurchases.action === 'updated') {

                self.modalCloser();
                self.clearModel('update');
                await self.getManualPurchases(self.filterModel.page);
                loader.hide();

                // clear file input
                this.$refs.manualPurchaseFileUpdate.value = '';

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    'Manual purchase successfully updated.',
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    'There were some errors while updating the manual purchase details.',
                    'error'
                )
            }
        },

        async deleteManual (id) {
            let self = this;

            let result = await swal.fire({
                title : 'Delete Manual Purchase',
                text : 'Are you sure that you want to delete this manual purchase?',
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })

            if (result.isConfirmed) {
                await self.$store.dispatch('actionDeleteManualPurchase', id);

                if (self.messageFormsManualPurchases.action === 'deleted') {

                    let page = self.manuals.data.length <= 1 ? 1 : self.filterModel.page;

                    await self.getManualPurchases(page);

                    await swal.fire(
                        self.$t('message.tools.alert_deleted'),
                        'Manual purchase successfully deleted.',
                        'success'
                    )
                } else {
                    await swal.fire(
                        self.$t('message.tools.alert_error'),
                        'There were some errors while deleting the manual purchase details.',
                        'error'
                    )
                }
            }
        },

        async getManualBuyers () {
            await this.$store.dispatch('actionGetManualPurchaseBuyerSelection', 'manual');
        },

        async getPurchaseCategories () {
            await this.$store.dispatch('actionGetPurchaseCategoriesSelection');
        },

        async getPurchaseTypes () {
            await this.$store.dispatch('actionGetPurchaseTypesSelection');
        },

        async getPaymentTypes (params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },

        fillManualData (data) {
            this.updateManualPurchaseModel.id = data.id;
            this.updateManualPurchaseModel.from = data.from;
            this.updateManualPurchaseModel.file = data.file ? data.file.path : '';
            this.updateManualPurchaseModel.notes = data.notes;
            this.updateManualPurchaseModel.amount = data.amount;
            this.updateManualPurchaseModel.type_id = data.type_id;
            this.updateManualPurchaseModel.purchased_at = data.purchased_at;
            this.updateManualPurchaseModel.payment_type_id = data.payment_type_id;
        },

        clearModel (mod) {
            switch(mod) {
                case 'add':
                    this.addManualPurchaseModel = {
                        from: '',
                        notes: '',
                        amount: '',
                        type_id: '',
                        mode: 'manual',
                        purchased_at: '',
                        payment_type_id: '',
                    }

                    break;
                case 'update':
                    this.updateManualPurchaseModel = {
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
                        type_id: '',
                        user_id: '',
                        mode: 'manual',
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
                    this.clearMessageFormManualPurchases();
            }
        },

        clearMessageFormManualPurchases() {
            this.$store.dispatch('clearMessageFormManualPurchases');
        },

        modalOpener (mode) {
            this.modalMode = mode

            let element = this.$refs.modalManualPurchase
            $(element).modal('show')
        },

        modalCloser () {
            let element = this.$refs.modalManualPurchase
            $(element).modal('hide')

            this.clearModel('error');
        },

        previewFile () {
            let element = this.$refs.modalManualPurchaseImage
            $(element).modal('show')
        },
    },
}
</script>
