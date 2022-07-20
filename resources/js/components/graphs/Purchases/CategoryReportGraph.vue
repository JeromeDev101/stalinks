<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title text-primary">Category Report</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="small">
                        <apexchart
                            type="bar"
                            height="350"
                            :options="categoryReportChartOptions"
                            :series="categoryData">

                        </apexchart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import category_report from "../../../graph_settings/purchases/category_report";

export default {
    name : "CategoryReportGraph",

    props: [
        'filterModel',
    ],

    data() {
        return {
            categoryData: []
        };
    },

    mounted() {
        this.getCategoryReportData();
    },

    computed: {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            categoryReport : state => state.storePurchases.categoryReport,
        }),

        categoryReportChartOptions () {
            return category_report.categoryReportGraphSettings();
        },
    },

    methods: {
        async getCategoryReportData () {
            await this.$store.dispatch('actionGetCategoryReportData', {params : this.filterModel});

            if (this.categoryReport) {
                this.categoryData = category_report.categoryReportGraphData(this.categoryReport);
            }
        },
    }
}
</script>
