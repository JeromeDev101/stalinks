<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-divider">
                    <h6 class="font-weight-bold">{{ $t('message.purchase_details.title') }}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.price') }}</label>

                    <input
                        v-model="componentDetails.amount"
                        type="number"
                        class="form-control"
                        ref="purchaseAmount"
                        :placeholder="$t('message.purchase_details.enter_price')">

                    <span
                        v-if="errors.errors.purchase_amount"
                        v-for="err in errors.errors.purchase_amount"
                        class="text-danger">

                        {{ err }}
                    </span>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.purchase_type') }}</label>

                    <v-select
                        v-model="componentDetails.type_id"
                        label="name"
                        class="style-chooser"
                        :placeholder="$t('message.purchase_details.select_purchase_type')"
                        :searchable="true"
                        :reduce="type => type.id"
                        :options="typesSelection.data"
                    />

                    <span
                        v-if="errors.errors.purchase_type_id"
                        v-for="err in errors.errors.purchase_type_id"
                        class="text-danger">

                        {{ err }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.purchase_via') }}</label>

                    <v-select
                        v-model="componentDetails.payment_type_id"
                        label="type"
                        class="style-chooser"
                        :placeholder="$t('message.purchase_details.select_purchase_via')"
                        :searchable="true"
                        :reduce="type => type.id"
                        :options="listPayment.data"/>

                        <span
                            v-if="errors.errors.purchase_payment_type_id"
                            v-for="err in errors.errors.purchase_payment_type_id"
                            class="text-danger">

                            {{ err }}
                        </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.purchase_notes') }}</label>

                    <textarea
                        v-model="componentDetails.notes"
                        rows="5"
                        type="text"
                        class="form-control"
                        :placeholder="$t('message.purchase_details.enter_purchase_notes')">

                    </textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.receipt') }}</label>

                    <input
                        type="file"
                        class="form-control"
                        ref="purchaseReceipt"
                        name="purchaseReceipt"
                        enctype="multipart/form-data"

                        @change="previewFilesReceipt">

                    <span
                        v-if="errors.errors.purchase_receipt"
                        v-for="err in errors.errors.purchase_receipt"
                        class="text-danger">

                        {{ err }}
                    </span>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">{{ $t('message.purchase_details.invoice') }}</label>

                    <input
                        type="file"
                        class="form-control"
                        ref="purchaseInvoice"
                        name="purchaseInvoice"
                        enctype="multipart/form-data"

                        @change="previewFilesInvoice">

                    <span
                        v-if="errors.errors.purchase_invoice"
                        v-for="err in errors.errors.purchase_invoice"
                        class="text-danger">

                        {{ err }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';

export default {
    props: {
        details: {
            type: Object,
            default: () => ({
                notes: '',
                amount: '',
                type_id: '',
                payment_type_id: '',
            })
        },

        errors: {
            type: Object,
            default: () => ({
                action: '',
                message: '',
                errors: {}
            })
        }
    },

    data() {
        return {
            componentDetails: {
                notes: '',
                amount: '',
                type_id: '',
                payment_type_id: '',
                receipt: '',
                invoice: '',
            }
        }
    },

    mounted () {
        this.getPaymentTypes();
        this.getPurchaseTypes();
    },

    computed: {
        ...mapState({
            typesSelection : state => state.storePurchases.typesSelection,
            listPayment : state => state.storeWalletTransaction.listPayment,
        }),
    },

    watch: {
        componentDetails: {
            handler (newVal) {
                this.updateDetails(newVal);
            },

            deep: true
        }
    },

    methods: {
        async getPaymentTypes (params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },

        async getPurchaseTypes () {
            await this.$store.dispatch('actionGetPurchaseTypesSelection');
        },

        updateDetails (details) {
            let self = this;

            self.$emit('input', {
                notes: details.notes,
                amount: details.amount,
                type_id: details.type_id,
                payment_type_id: details.payment_type_id,
                receipt: details.receipt,
                invoice: details.invoice,
            })
        },

        previewFilesReceipt (event) {
            this.componentDetails.receipt = event.target.files[0]
        },

        previewFilesInvoice (event) {
            this.componentDetails.invoice = event.target.files[0]
        },
    },
}
</script>

<style scoped>
    .form-divider {
        margin-bottom: 10px;
        border-bottom: 3px solid #ced4da;
    }

    .form-divider span {
        margin-bottom: 5px;
    }
</style>
