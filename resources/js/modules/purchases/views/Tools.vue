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
                                    <label>{{ $t('message.purchases_summary.f_pur_cat') }}</label>

                                    <v-select
                                        v-model="filterModel.category_id"
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.f_select_pur_cat')"
                                        :searchable="true"
                                        :reduce="category => category.id"
                                        :options="categoriesSelection.data"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchases_summary.f_pur_type') }}</label>

                                    <v-select
                                        v-model="filterModel.type_id"
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.f_select_pur_type')"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="typesSelection.data"/>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchases_summary.f_pur_from') }}</label>

                                    <input
                                        v-model="filterModel.from"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_summary.f_enter_pur_from')">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchases_summary.f_pur_by') }}</label>

                                    <v-select
                                        v-model="filterModel.user_id"
                                        label="username"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.f_select_pur_by')"
                                        :searchable="true"
                                        :reduce="user => user.id"
                                        :options="toolBuyers.data"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ $t('message.purchases_summary.f_pur_via') }}</label>

                                    <v-select
                                        v-model="filterModel.payment_type_id"
                                        label="type"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.f_select_pur_via')"
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
                                <button class="btn btn-default" @click="filterToolPurchases()">
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
                        <h3 class="card-title text-primary">{{ $t('message.purchases_summary.t_title') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center">
                            <div class="col-6">
                                <span v-if="tools.total" class="pagination-custom-footer-text m-0 mt-2">
                                    <b v-if="filterModel.paginate !== 'All'">
                                        {{ $t('message.others.table_entries', { from: tools.from, to: tools.to, end: tools.total }) }}
                                    </b>

                                    <b v-else>
                                        {{ $t('message.others.table_all_entries', { total: tools.total }) }}
                                    </b>
                                </span>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm float-right mr-2" style="width: 65px">
                                    <select
                                        v-model="filterModel.paginate"
                                        class="form-control"
                                        style="height: 37px;"

                                        @change="getToolPurchases()">

                                        <option v-for="value in pageOptions" :value="value">{{ value }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div v-if="tools.data.length">
                            <vue-virtual-table
                                width="100%"
                                :min-width="450"
                                :height="600"
                                :bordered="true"
                                :item-height="60"
                                :hover-highlight="true"
                                :config="tableConfig"
                                :enable-export="true"
                                :data="tools.data">

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
                                        v-if="user.permission_list.includes('update-purchases-tools')"
                                        :title="$t('message.purchases_summary.edit')"
                                        class="btn btn-primary m-1"

                                        @click="fillToolData(scope.row); modalOpener('Update')">

                                        <i class="fas fa-pen-square"></i>
                                    </button>

                                    <button
                                        v-if="user.permission_list.includes('delete-purchases-tools')"
                                        :title="$t('message.purchases_summary.delete')"
                                        class="btn btn-danger"

                                        @click="deleteTool(scope.row.id)">

                                        <i class="fas fa-trash"></i>
                                    </button>
                                </template>

                            </vue-virtual-table>

                            <pagination
                                :limit="8"
                                :data="tools"

                                @pagination-change-page="getToolPurchases">

                            </pagination>
                        </div>

                        <div v-else class="col-md-12 text-center card">
                            <div class="card-body">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $t('message.purchases_summary.no_results') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Purchase -->
        <div
            class="modal fade"
            ref="modalToolPurchase"
            style="display: none;"
            id="modal-tool-purchase"
            data-backdrop="static">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ modalMode === 'Add' ? $t('message.signature.add') : $t('message.signature.update') }}
                            {{ $t('message.purchases_summary.mp_title') }}
                        </h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_pur_date') }}</label>

                                    <input
                                        v-model="modelToolDate"
                                        type="date"
                                        class="form-control">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.purchased_at"
                                        v-for="err in messageFormsToolsPurchases.errors.purchased_at"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_pur_from') }}</label>

                                    <input
                                        v-model="modelToolFrom"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_summary.mp_enter_pur_from')">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.from"
                                        v-for="err in messageFormsToolsPurchases.errors.from"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_pur_type') }}</label>

                                    <v-select
                                        v-model="modelToolTypeId"
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.mp_select_pur_type')"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="typesSelection.data"/>

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.type_id"
                                        v-for="err in messageFormsToolsPurchases.errors.type_id"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_price') }}</label>

                                    <input
                                        v-model="modelToolAmount"
                                        type="number"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_summary.mp_enter_price')">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.amount"
                                        v-for="err in messageFormsToolsPurchases.errors.amount"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_pur_via') }}</label>

                                    <v-select
                                        v-model="modelToolPaymentTypeId"
                                        label="type"
                                        class="style-chooser"
                                        :placeholder="$t('message.purchases_summary.mp_select_pur_via')"
                                        :searchable="true"
                                        :reduce="type => type.id"
                                        :options="listPayment.data"/>

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.payment_type_id"
                                        v-for="err in messageFormsToolsPurchases.errors.payment_type_id"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_notes') }}</label>

                                    <textarea
                                        v-model="modelToolNotes"
                                        rows="5"
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('message.purchases_summary.mp_enter_notes')">

                                    </textarea>

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.notes"
                                        v-for="err in messageFormsToolsPurchases.errors.notes"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode !== 'Update'" class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_receipt') }}</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="toolPurchaseReceipt"
                                        name="toolPurchaseReceipt"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.receipt"
                                        v-for="err in messageFormsToolsPurchases.errors.receipt"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_invoice') }}</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="toolPurchaseInvoice"
                                        name="toolPurchaseInvoice"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.invoice"
                                        v-for="err in messageFormsToolsPurchases.errors.invoice"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'Update' && updateToolPurchaseModel.files.length" class="row">
                            <div v-if="checkFile(updateToolPurchaseModel.files, 'is_receipt')" class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_uploaded_receipt') }}</label>

                                    <div
                                        v-if="checkFileExtension(getFileExtension(getFile(updateToolPurchaseModel.files, 'is_receipt'))) === 'image'"
                                        class="card"
                                        style="cursor: pointer;"

                                        @click="previewFile(getFile(updateToolPurchaseModel.files, 'is_receipt'))">

                                        <div class="card-body">
                                            <img
                                                width="350"
                                                style="min-height: 200px; height: 200px"
                                                alt="Uploaded purchase receipt"
                                                class="img-thumbnail"
                                                :src="getFile(updateToolPurchaseModel.files, 'is_receipt')">
                                        </div>
                                    </div>

                                    <div v-else class="card">
                                        <div class="card-body">
                                            <a
                                                :href="getFile(updateToolPurchaseModel.files, 'is_receipt')"
                                                download
                                                class="btn btn-default"
                                                :title="$t('message.purchases_summary.mp_dl_uploaded_receipt')">
                                                {{ getFileName(getFile(updateToolPurchaseModel.files, 'is_receipt')) }}

                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="checkFile(updateToolPurchaseModel.files, 'is_invoice')" class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_uploaded_invoice') }}</label>

                                    <div
                                        v-if="checkFileExtension(getFileExtension(getFile(updateToolPurchaseModel.files, 'is_invoice'))) === 'image'"
                                        class="card"
                                        style="cursor: pointer;"

                                        @click="previewFile(getFile(updateToolPurchaseModel.files, 'is_invoice'))">

                                        <div class="card-body">
                                            <img
                                                width="350"
                                                style="min-height: 200px; height: 200px"
                                                alt="Uploaded purchase receipt"
                                                class="img-thumbnail"
                                                :src="getFile(updateToolPurchaseModel.files, 'is_invoice')">
                                        </div>
                                    </div>

                                    <div v-else class="card">
                                        <div class="card-body">
                                            <a
                                                :href="getFile(updateToolPurchaseModel.files, 'is_invoice')"
                                                download
                                                class="btn btn-default"
                                                :title="$t('message.purchases_summary.mp_dl_uploaded_invoice')">
                                                {{ getFileName(getFile(updateToolPurchaseModel.files, 'is_invoice')) }}

                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="modalMode === 'Update'" class="row mt-4">
                            <div class="col-12">
                                <div class="form-divider">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_update_receipt') }}</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="toolPurchaseReceiptUpdate"
                                        name="toolPurchaseReceiptUpdate"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.receipt"
                                        v-for="err in messageFormsToolsPurchases.errors.receipt"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.purchases_summary.mp_update_invoice') }}</label>

                                    <input
                                        type="file"
                                        class="form-control"
                                        ref="toolPurchaseInvoiceUpdate"
                                        name="toolPurchaseInvoiceUpdate"
                                        enctype="multipart/form-data">

                                    <span
                                        v-if="messageFormsToolsPurchases.errors.invoice"
                                        v-for="err in messageFormsToolsPurchases.errors.invoice"
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
                        <button v-if="modalMode === 'Add'" type="button" @click="addToolPurchase" class="btn btn-primary">
                            {{ $t('message.signature.save') }}
                        </button>
                        <button v-else type="button" @click="updateToolPurchase" class="btn btn-primary">
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
            ref="modalToolPurchaseImage"
            style="display: none;"
            id="modal-tool-purchase-image">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img
                            class="img-fluid img-thumbnail rounded mx-auto d-block"
                            alt="Uploaded purchase photo"
                            :src="filePreview">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import {files} from "../../../mixins/files";
import VueVirtualTable from 'vue-virtual-table';
import {Constants} from "../../../mixins/constants";
import {dateRange} from "../../../mixins/dateRange";
import {filterModel} from "../../../mixins/filterModel";

export default {
    mixins: [Constants, dateRange, filterModel, files],
    components: { VueVirtualTable },

    data() {
        return {
            filterModel: {
                mode: 'tools',
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

            addToolPurchaseModel: {
                from: '',
                notes: '',
                amount: '',
                type_id: '',
                mode: 'tools',
                purchased_at: '',
                payment_type_id: '',
            },

            updateToolPurchaseModel: {
                id: '',
                from: '',
                files: [],
                notes: '',
                amount: '',
                type_id: '',
                mode: 'tools',
                purchased_at: '',
                payment_type_id: '',
            },

            modalMode: 'Add',
            pageOptions: [10, 25, 50, 100, 'All'],

            filePreview: '',
        }
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            tools : state => state.storePurchases.tools,
            toolBuyers : state => state.storePurchases.toolBuyers,
            typesSelection : state => state.storePurchases.typesSelection,
            listPayment : state => state.storeWalletTransaction.listPayment,
            categoriesSelection : state => state.storePurchases.categoriesSelection,
            messageFormsToolsPurchases : state => state.storePurchases.messageFormsToolsPurchases,
        }),

        modelToolDate: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.purchased_at
                    : this.updateToolPurchaseModel.purchased_at
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.purchased_at = val
                } else {
                    this.updateToolPurchaseModel.purchased_at = val
                }
            }
        },

        modelToolFrom: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.from
                    : this.updateToolPurchaseModel.from
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.from = val
                } else {
                    this.updateToolPurchaseModel.from = val
                }
            }
        },

        modelToolTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.type_id
                    : this.updateToolPurchaseModel.type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.type_id = val
                } else {
                    this.updateToolPurchaseModel.type_id = val
                }
            }
        },

        modelToolAmount: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.amount
                    : this.updateToolPurchaseModel.amount
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.amount = val
                } else {
                    this.updateToolPurchaseModel.amount = val
                }
            }
        },

        modelToolPaymentTypeId: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.payment_type_id
                    : this.updateToolPurchaseModel.payment_type_id
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.payment_type_id = val
                } else {
                    this.updateToolPurchaseModel.payment_type_id = val
                }
            }
        },

        modelToolNotes: {
            get () {
                return this.modalMode === 'Add'
                    ? this.addToolPurchaseModel.notes
                    : this.updateToolPurchaseModel.notes
            },
            set (val) {
                if (this.modalMode === 'Add') {
                    this.addToolPurchaseModel.notes = val
                } else {
                    this.updateToolPurchaseModel.notes = val
                }
            }
        },

        tableConfig() {
            return [
                {
                    prop : 'id',
                    name : this.$t('message.purchases_summary.t_id'),
                    sortable: true,
                },
                {
                    prop : 'full_clean_date',
                    name : this.$t('message.purchases_summary.t_date'),
                },
                {
                    prop : '_action',
                    name : this.$t('message.purchases_summary.t_cat'),
                    actionName : 'category',
                },
                {
                    prop : '_action',
                    name : this.$t('message.purchases_summary.t_type'),
                    actionName : 'type',
                },
                {
                    prop : 'from',
                    name : this.$t('message.purchases_summary.t_from'),
                },
                {
                    prop : 'amount',
                    name : this.$t('message.purchases_summary.t_amount'),
                    prefix : '$ '
                },
                {
                    prop : '_action',
                    name : this.$t('message.purchases_summary.t_purchased_by'),
                    actionName : 'purchase-by',
                },
                {
                    prop : '_action',
                    name : this.$t('message.purchases_summary.t_purchased_via'),
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
        this.getToolPurchases(this.$route.query.page);
        this.getToolsBuyers();
        this.getPaymentTypes();
        this.getPurchaseTypes();
        this.getPurchaseCategories();
    },

    methods: {
        async getToolPurchases (page = 1) {
            let self = this;
            let loader = self.$loading.show();

            self.filterModel.page = page;

            this.$router.push({
                query : this.cleanFilterModel(this.filterModel)
            }).then(r => {

            })

            await self.$store.dispatch('actionGetToolsPurchases', {params : self.filterModel});

            loader.hide();
        },

        filterToolPurchases () {
            // change the format of date
            this.filterModel.date = this.formatFilterDates(this.filterModel.date)

            this.getToolPurchases(1);
        },

        clearFilter () {
            this.clearModel('filter')
            this.getToolPurchases(1);
        },

        addToolPurchase () {

        },

        async updateToolPurchase () {
            let self = this;
            let loader = self.$loading.show();

            let formDataToolPurchaseUpdate = self.generateFormData(self.updateToolPurchaseModel)

            // add _method put to read formData as put request - make axios into post instead of put
            formDataToolPurchaseUpdate.append("_method", "PUT")
            formDataToolPurchaseUpdate.append('receipt', self.$refs.toolPurchaseReceiptUpdate.files[0] ? self.$refs.toolPurchaseReceiptUpdate.files[0] : '');
            formDataToolPurchaseUpdate.append('invoice', self.$refs.toolPurchaseInvoiceUpdate.files[0] ? self.$refs.toolPurchaseInvoiceUpdate.files[0] : '');

            await self.$store.dispatch('actionUpdateToolsPurchase', formDataToolPurchaseUpdate);

            if (self.messageFormsToolsPurchases.action === 'updated') {

                self.modalCloser();
                self.clearModel('update');
                await self.getToolPurchases(self.filterModel.page);
                loader.hide();

                // clear file input
                this.$refs.toolPurchaseReceiptUpdate.value = '';
                this.$refs.toolPurchaseInvoiceUpdate.value = '';

                await swal.fire(
                    self.$t('message.tools.alert_success'),
                    self.$t('message.purchases_summary.ta_purchase_updated'),
                    'success'
                )
            } else {
                loader.hide();

                await swal.fire(
                    self.$t('message.tools.alert_error'),
                    self.messageFormsToolsPurchases.message,
                    'error'
                )
            }
        },

        async deleteTool (id) {
            let self = this;

            let result = await swal.fire({
                title : self.$t('message.purchases_summary.ta_delete_purchase'),
                text : self.$t('message.purchases_summary.ta_delete_purchase_confirm'),
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : self.$t('message.tools.yes_delete'),
                cancelButtonText : self.$t('message.tools.no_delete')
            })

            if (result.isConfirmed) {
                await self.$store.dispatch('actionDeleteToolsPurchase', id);

                if (self.messageFormsToolsPurchases.action === 'deleted') {

                    let page = self.tools.data.length <= 1 ? 1 : self.filterModel.page;

                    await self.getToolPurchases(page);

                    await swal.fire(
                        self.$t('message.tools.alert_deleted'),
                        self.$t('message.purchases_summary.ta_purchase_deleted'),
                        'success'
                    )
                } else {
                    await swal.fire(
                        self.$t('message.tools.alert_error'),
                        self.messageFormsToolsPurchases.message,
                        'error'
                    )
                }
            }
        },

        async getToolsBuyers () {
            await this.$store.dispatch('actionGetToolsPurchaseBuyerSelection', 'tools');
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

        fillToolData (data) {
            this.updateToolPurchaseModel.id = data.id;
            this.updateToolPurchaseModel.from = data.from;
            this.updateToolPurchaseModel.files = data.files;
            this.updateToolPurchaseModel.notes = data.notes;
            this.updateToolPurchaseModel.amount = data.amount;
            this.updateToolPurchaseModel.type_id = data.type_id;
            this.updateToolPurchaseModel.purchased_at = data.purchased_at;
            this.updateToolPurchaseModel.payment_type_id = data.payment_type_id;
        },

        clearModel (mod) {
            switch(mod) {
                case 'add':
                    this.addToolPurchaseModel = {
                        from: '',
                        notes: '',
                        amount: '',
                        type_id: '',
                        mode: 'tools',
                        purchased_at: '',
                        payment_type_id: '',
                    }

                    break;
                case 'update':
                    this.updateToolPurchaseModel = {
                        id: '',
                        from: '',
                        files: [],
                        notes: '',
                        amount: '',
                        type_id: '',
                        mode: 'tools',
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
                        mode: 'tools',
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
                    this.clearMessageFormToolPurchases();
            }
        },

        clearMessageFormToolPurchases() {
            this.$store.dispatch('clearMessageFormToolPurchases');
        },

        modalOpener (mode) {
            this.modalMode = mode

            let element = this.$refs.modalToolPurchase
            $(element).modal('show')
        },

        modalCloser () {
            let element = this.$refs.modalToolPurchase
            $(element).modal('hide')

            this.clearModel('error');
        },

        previewFile (file) {
            this.filePreview = file
            let element = this.$refs.modalToolPurchaseImage
            $(element).modal('show')
        },
    },
}
</script>
