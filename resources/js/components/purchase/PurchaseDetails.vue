<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-divider">
                    <h6 class="font-weight-bold">Purchase Details</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">Price</label>

                    <input
                        v-model="componentDetails.amount"
                        type="number"
                        class="form-control"
                        ref="purchaseAmount"
                        placeholder="Enter Price">

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
                    <label style="color: #333">Purchase Type</label>

                    <v-select
                        v-model="componentDetails.type_id"
                        label="name"
                        class="style-chooser"
                        placeholder="Select Purchase Type"
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
            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">Purchase Via</label>

                    <v-select
                        v-model="componentDetails.payment_type_id"
                        label="type"
                        class="style-chooser"
                        placeholder="Select Purchase Via"
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

            <div class="col-6">
                <div class="form-group">
                    <label style="color: #333">Photo</label>

                    <input
                        type="file"
                        class="form-control"
                        ref="purchaseFile"
                        name="purchaseFile"
                        enctype="multipart/form-data"

                        @change="previewFiles">

                    <span
                        v-if="errors.errors.purchase_file"
                        v-for="err in errors.errors.purchase_file"
                        class="text-danger">

                        {{ err }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label style="color: #333">Purchase Notes</label>

                    <textarea
                        v-model="componentDetails.notes"
                        rows="5"
                        type="text"
                        class="form-control"
                        placeholder="Enter Purchase Note">

                    </textarea>
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
                file: '',
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
                file: details.file,
            })
        },

        previewFiles (event) {
            this.componentDetails.file = event.target.files[0]
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
