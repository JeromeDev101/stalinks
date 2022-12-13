<template>
    <div>
        <div
            tabindex="-1"
            role="dialog"
            style="display:none"
            class="modal fade show"
            data-keyboard="false"
            data-backdrop="static"
            aria-modal="true"
            aria-labelledby="exampleModalLabel"
            :id="refs"
            :ref="refs">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('message.renew.title') }} {{ mode }}</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.renew.name') }}</label>

                                    <input
                                        v-model="renewDetails.name"
                                        disabled
                                        type="text"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.renew.reg_date') }}</label>

                                    <input
                                        v-model="renewDetails.registered_at"
                                        disabled
                                        type="date"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.renew.exp_date') }}</label>

                                    <input
                                        v-model="renewDetails.expired_at"
                                        disabled
                                        type="date"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label style="color: #333">{{ $t('message.renew.renewal_period') }}</label>

                                    <v-select
                                        v-model="renewDetails.renewal_period"
                                        label="name"
                                        class="style-chooser"
                                        :placeholder="$t('message.renew.select_renewal_period')"
                                        :searchable="true"
                                        :reduce="period => period.value"
                                        :options="renewalPeriod"
                                    />

                                    <span
                                        v-if="renewErrors.errors.renewal_period"
                                        v-for="err in renewErrors.errors.renewal_period"
                                        class="text-danger">

                                        {{ err }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="purchaseable" class="row">
                            <div class="col-md-12">
                                <div class="form-group form-check">
                                    <input
                                        v-model="renewDetails.is_purchased"
                                        type="checkbox"
                                        id="isPurchasedRenew"
                                        class="form-check-input">

                                    <label class="form-check-label" for="isPurchasedRenew">{{ $t('message.renew.is_purchased') }}</label>
                                </div>
                            </div>
                        </div>

                        <div v-if="renewDetails.is_purchased">
                            <purchase-details v-model="purchaseDetailsRenew" :errors="renewErrors" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" @click="$emit('close')">
                            {{ $t('message.tools.close') }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"

                            @click="submitRenew">

                            {{ $t('message.tools.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {filterModel} from "../../mixins/filterModel";
import PurchaseDetails from '@/components/purchase/PurchaseDetails';
import axios from "axios";

export default {
    props: {
        visible: {
            type: Boolean,
            required: true,
            default: false
        },

        refs: {
            type: String,
            required: true,
            default: 'defaultModal'
        },

        mode: {
            type: String,
            required: true,
            default: 'Tool'
        },

        details: {
            type: Object,
        },

        purchaseable: {
            type: Boolean,
            default: false,
        },

        model: {
            type: String,
            default: 'App\Models\Tool'
        }
    },

    mixins: [filterModel],

    components: { PurchaseDetails },

    data() {
        return {
            renewDetails: {
                id: '',
                name: '',
                model: '',
                expired_at: '',
                registered_at: '',
                renewal_period: '',
                is_purchased: false,
            },

            purchaseDetailsRenew: {
                receipt: '',
                invoice: '',
                notes: '',
                amount: '',
                type_id: '',
                payment_type_id: '',
            },

            renewErrors: {
                action: '',
                message: '',
                errors: {}
            },

            renewalPeriod: [
                {
                    name: this.$t('message.renew.one'),
                    value: 1,
                },
                {
                    name: this.$t('message.renew.two'),
                    value: 2,
                },
                {
                    name: this.$t('message.renew.three'),
                    value: 3,
                },
                {
                    name: this.$t('message.renew.four'),
                    value: 4,
                },
                {
                    name: this.$t('message.renew.five'),
                    value: 5,
                },
                {
                    name: this.$t('message.renew.six'),
                    value: 6,
                },
                {
                    name: this.$t('message.renew.seven'),
                    value: 7,
                },
                {
                    name: this.$t('message.renew.eight'),
                    value: 8,
                },
                {
                    name: this.$t('message.renew.nine'),
                    value: 9,
                },
                {
                    name: this.$t('message.renew.ten'),
                    value: 10,
                },
                {
                    name: this.$t('message.renew.eleven'),
                    value: 11,
                },
                {
                    name: this.$t('message.renew.one_year'),
                    value: 12,
                },
                {
                    name: this.$t('message.renew.two_years'),
                    value: 24,
                },
            ]
        }
    },

    watch: {
        visible(newVal, oldVal) {
            this.toggleModal(newVal)
        },

        details: {
            handler (newVal) {
                this.updateDetails(newVal);
            },

            deep: true
        }
    },

    methods: {
        submitRenew () {
            let self = this;
            let loader = self.$loading.show();

            self.renewDetails.model = self.model;

            let formDataRenew = self.generateFormData(self.renewDetails)

            // append purchase details
            if (self.renewDetails.is_purchased) {
                formDataRenew = self.appendPurchaseFormData(formDataRenew, self.purchaseDetailsRenew);
            }

            axios.post('/api/renew', formDataRenew)
                .then((res) => {
                    loader.hide();

                    swal.fire(
                        self.$t('message.tools.alert_success'),
                        self.mode + ' successfully renewed.',
                        'success'
                    )

                    self.toggleModal(false);

                    self.$emit('renew');
                    self.$emit('close');

                    self.clearDetails();
                })
                .catch((err) => {
                    loader.hide();

                    self.renewErrors = err.response.data

                    swal.fire(
                        self.$t('message.tools.alert_error'),
                        'There were some errors while renewing the ' + self.mode,
                        'error'
                    )
                });
        },

        toggleModal (show) {
            let element = this.$refs[this.refs]

            if (show) {
                $(element).modal('show')

                this.renewErrors = {
                    action: '',
                    message: '',
                    errors: {}
                }
            } else {
                $(element).modal('hide')
            }
        },

        updateDetails (data) {
            this.renewDetails.id = data.id;
            this.renewDetails.name = data.name;
            this.renewDetails.expired_at = data.expired_at;
            this.renewDetails.registered_at = data.registered_at;
        },

        clearDetails () {
            this.renewDetails = {
                id: '',
                name: '',
                model: '',
                expired_at: '',
                registered_at: '',
                renewal_period: '',
                is_purchased: false,
            }

            this.purchaseDetailsRenew = {
                receipt: '',
                invoice: '',
                notes: '',
                amount: '',
                type_id: '',
                payment_type_id: '',
            }
        },
    },
}
</script>
