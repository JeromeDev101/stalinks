<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title text-primary">Payment Method Report</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="small">
                        <apexchart
                            type="bar"
                            height="350"
                            :options="paymentMethodReportChartOptions"
                            :series="paymentMethodData">

                        </apexchart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import payment_method_report from "../../../graph_settings/purchases/payment_method_report";

export default {
    name : "PaymentMethodReportGraph",

    props: [
        'filterModel',
    ],

    data() {
        return {
            paymentMethodData: []
        };
    },

    mounted() {
        this.getPaymentTypes();
        this.getPaymentMethodReportData();
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listPayment : state => state.storeWalletTransaction.listPayment,
            paymentMethodReport : state => state.storePurchases.paymentMethodReport,
        }),

        paymentMethodReportChartOptions () {
            return payment_method_report.paymentMethodReportGraphSettings(this.paymentMethodReport);
        },
    },

    methods: {
        async getPaymentMethodReportData () {
            await this.$store.dispatch('actionGetPaymentMethodReportData', {params : this.filterModel});

            if (this.paymentMethodReport) {
                this.paymentMethodData = payment_method_report.paymentMethodReportGraphData(this.paymentMethodReport, this.listPayment.data);
            }
        },

        async getPaymentTypes (params) {
            await this.$store.dispatch('actionGetListPaymentType', params);
        },
    }
}
</script>
