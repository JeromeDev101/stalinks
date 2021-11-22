<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">Formula for External Buyer</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group row">
                                <label for="" class="col-sm-2 col-form-label">Additional</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.additional">
                                    <span v-if="messageForms.errors.additional"
                                          v-for="err in messageForms.errors.additional"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-sm-2 col-form-label">Percentage</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.percentage">
                                    <span v-if="messageForms.errors.percentage"
                                          v-for="err in messageForms.errors.percentage"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </form>
                        <hr/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-sm-12">
                        WITH ARTICLE
                    </div>
                    <div class="col-sm-12 p-3 text-primary">Commission = No</div>
                    <div class="col-sm-12 text-danger">Formula: (Remain selling price)</div>
                    <hr/>

                    <div class="col-sm-12 p-3 text-primary">Commission = Yes</div>
                    <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price</div>
                </div>
                <div class="col-md-6">
                    <div class="col-sm-12">
                        WITHOUT ARTICLE
                    </div>

                    <div class="col-sm-12 p-3 text-primary">Commission = No</div>
                    <div class="col-sm-12 text-danger">Formula: selling price + Additional</div>
                    <hr/>

                    <div class="col-sm-12 p-3 text-primary">Commission = Yes</div>
                    <div class="col-sm-12 text-danger">Formula: (Percentage * selling_price) + selling_price + Additional</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="button" @click="submitFormula" class="btn btn-primary">Save</button>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "Formula",

    props : [
        'messageForms'
    ],

    data() {
        return {
            updateFormula : {
                id : '',
                additional : '',
                percentage : '',
            },
        }
    },

    computed : {
        ...mapState({
            formula : state => state.storeSystem.formula,
        }),
    },

    mounted() {
        this.getFormula();
    },

    methods : {
        async submitFormula() {
            await this.$store.dispatch('actionUpdateFormula', this.updateFormula);

            if (this.messageForms.action == 'save_formula') {
                swal.fire(
                    'Saved!',
                    'Successfully Updated.',
                    'success'
                )

                this.getFormula();
            }
        },

        async getFormula() {
            await this.$store.dispatch('actionGetFormula');

            this.updateFormula = this.formula.data[0];
        },
    }
}
</script>

<style scoped>

</style>
