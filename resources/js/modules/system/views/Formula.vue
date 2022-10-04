<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{ $t('message.finance.for_title') }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">{{ $t('message.finance.for_additional') }}</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.additional">
                                    <span v-if="messageForms.errors.additional"
                                          v-for="err in messageForms.errors.additional"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                <label class="col-sm-2 col-form-label">{{ $t('message.finance.for_percentage') }}</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.percentage">
                                    <span v-if="messageForms.errors.percentage"
                                          v-for="err in messageForms.errors.percentage"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div> -->

                            <!-- <h6 class="mt-4 text-primary">Buyer Type Discount</h6>
                            <hr/> -->

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Basic Account</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.basic">
                                    <span v-if="messageForms.errors.basic"
                                          v-for="err in messageForms.errors.basic"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Medium Account</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.medium">
                                    <span v-if="messageForms.errors.medium"
                                          v-for="err in messageForms.errors.medium"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Premium Account</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" v-model="updateFormula.premium">
                                    <span v-if="messageForms.errors.premium"
                                          v-for="err in messageForms.errors.premium"
                                          class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                        </form>
                        <hr/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-sm-12">
                        {{ $t('message.finance.for_with_article') }}
                    </div>
                    <div class="col-sm-12 p-3 text-primary">{{ $t('message.finance.for_com_no') }}</div>
                    <div class="col-sm-12 text-danger">{{ $t('message.finance.for_formula_remain') }} (Remain selling price)</div>
                    <hr/>

                    <div class="col-sm-12 p-3 text-primary">{{ $t('message.finance.for_com_yes') }}</div>
                    <div class="col-sm-12 text-danger">{{ $t('message.finance.for_formula_remain') }} (Percentage * selling_price) + selling_price</div>
                </div>
                <div class="col-md-6">
                    <div class="col-sm-12">
                        {{ $t('message.finance.for_without_article') }}
                    </div>

                    <div class="col-sm-12 p-3 text-primary">{{ $t('message.finance.for_com_no') }}</div>
                    <div class="col-sm-12 text-danger">{{ $t('message.finance.for_formula_remain') }} selling price + Additional</div>
                    <hr/>

                    <div class="col-sm-12 p-3 text-primary">{{ $t('message.finance.for_com_yes') }}</div>
                    <div class="col-sm-12 text-danger">{{ $t('message.finance.for_formula_remain') }} (Percentage * selling_price) + selling_price + Additional</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="button" @click="submitFormula" class="btn btn-primary">
                {{ $t('message.finance.save') }}
            </button>
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
                basic : '',
                medium : '',
                premium : '',
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
            let self = this;
            await this.$store.dispatch('actionUpdateFormula', this.updateFormula);

            if (this.messageForms.action == 'save_formula') {
                swal.fire(
                    self.$t('message.finance.alert_saved'),
                    self.$t('message.finance.alert_successfully_updated'),
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
