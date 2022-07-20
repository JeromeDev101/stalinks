<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title text-primary">Purchase Type Report</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="small">
                        <apexchart
                            type="bar"
                            height="350"
                            :options="purchaseTypeReportChartOptions"
                            :series="purchaseTypeData">

                        </apexchart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import purchase_type_report from "../../../graph_settings/purchases/purchase_type_report";

export default {
    name : "PurchaseTypeReportGraph",

    props: [
        'filterModel',
    ],

    data() {
        return {
            purchaseTypeData: []
        };
    },

    mounted() {
        this.getPurchaseTypes();
        this.getPurchaseTypeReportData();
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            typesSelection : state => state.storePurchases.typesSelection,
            purchaseTypeReport : state => state.storePurchases.purchaseTypeReport,
        }),

        purchaseTypeReportChartOptions () {
            return purchase_type_report.purchaseTypeReportGraphSettings(this.purchaseTypeReport);
        },
    },

    methods: {
        async getPurchaseTypeReportData () {
            await this.$store.dispatch('actionGetPurchaseTypeReportData', {params : this.filterModel});

            if (this.purchaseTypeReport) {
                this.purchaseTypeData = purchase_type_report.purchaseTypeReportGraphData(this.purchaseTypeReport, this.typesSelection.data);
            }
        },

        async getPurchaseTypes () {
            await this.$store.dispatch('actionGetPurchaseTypesSelection');
        },
    }
}
</script>
