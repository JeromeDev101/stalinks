<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">Payment Method</h3>
            <div class="card-tools">
                <button data-toggle="modal"
                        @click="clearMessageform"
                        data-target="#modal-add-payment"
                        class="btn btn-success float-right"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body" style="height: 650px; overflow: scroll;">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <thead>
                <tr class="label-primary">
                    <th>#</th>
                    <th>Type</th>
                    <th>Receive Payment</th>
                    <th>Send Payment</th>
                    <th>Registration</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in paymentList.data" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.receive_payment }}</td>
                    <td>{{ item.send_payment }}</td>
                    <td>{{ item.show_registration }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="submit"
                                    @click="doEditPayment(item)"
                                    data-toggle="modal"
                                    data-target="#modal-update-payment"
                                    title="Edit"
                                    class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade"
             id="modal-add-payment"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true"
            ref="modalAddPaymentType">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment Type</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text"
                                   v-model="paymentModel.type"
                                   class="form-control"
                                   placeholder="Enter Payment Type"
                                   required>
                            <span v-if="messageForms.errors.type"
                                  v-for="err in messageForms.errors.type"
                                  class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitAddPayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"
             id="modal-update-payment"
             tabindex="-1"
             role="dialog"
             aria-labelledby="modelTitleId"
             aria-hidden="true"
            ref="modalUpdatePaymentType">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Payment Type</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label for="">Payment Type</label>
                            <input type="text"
                                   v-model="paymentUpdate.type"
                                   class="form-control"
                                   placeholder="Enter Payment Type"
                                   required>
                            <span v-if="messageForms.errors.type"
                                  v-for="err in messageForms.errors.type"
                                  class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.receive_payment}"
                             class="form-group">
                            <label for="">Receive Payment</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.receive_payment">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.receive_payment"
                                  v-for="err in messageForms.errors.receive_payment"
                                  class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.send_payment}"
                             class="form-group">
                            <label for="">Send Payment</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.send_payment">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.send_payment"
                                  v-for="err in messageForms.errors.send_payment"
                                  class="text-danger">{{ err }}</span>
                        </div>

                        <div :class="{'form-group': true, 'has-error': messageForms.errors.show_registration}"
                             class="form-group">
                            <label for="">Show in Registration</label>
                            <select name="" id="" class="form-control" v-model="paymentUpdate.show_registration">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <span v-if="messageForms.errors.show_registration"
                                  v-for="err in messageForms.errors.show_registration"
                                  class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="submitUpdatePayment" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name : "PaymentMethod",

    props : [
        'messageForms',
        'isPopupLoading'
    ],

    data() {
        return {
            paymentUpdate : {
                id : 0,
                type : '',
                show_registration : '',
                receive_payment : '',
                send_payment : ''
            },
            paymentModel : {
                id : 0,
                type : ''
            },
        }
    },

    computed : {
        ...mapState({
            paymentList : state => state.storeSystem.paymentList,
        }),
    },

    mounted() {
        this.getPaymentList();
    },

    methods : {
        clearMessageform() {
            this.$store.dispatch('clearMessageFormSystem');
        },

        doEditPayment(item) {
            this.clearMessageform();
            this.$store.dispatch('clearMessageFormSystem');
            this.paymentUpdate = JSON.parse(JSON.stringify(item))
        },

        async submitAddPayment() {
            let that = this;
            this.isPopupLoading = true;
            await this.$store.dispatch('actionAddPayment', that.paymentModel);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_payment') {
                await swal.fire(
                    'Success!',
                    'Payment type successfully added.',
                    'success'
                )

                this.closeModal('add')
                this.clearModel();
                this.getPaymentList();
            } else {
                await swal.fire(
                    'Error!',
                    'There were some errors while adding the payment type.',
                    'error'
                )
            }
        },

        closeModal(mod) {
            let element = mod === 'add' ? this.$refs.modalAddPaymentType : this.$refs.modalUpdatePaymentType
            $(element).modal('hide')
        },

        clearModel() {
            this.paymentModel = {
                id : 0,
                type : ''
            };
        },

        async getPaymentList(params) {
            this.isLoadingTable = true;
            await this.$store.dispatch('actionGetPaymentList', params);
            this.isLoadingTable = false;
        },

        async submitUpdatePayment() {
            this.isPopupLoading = true;
            await this.$store.dispatch('actionUpdatePayment', this.paymentUpdate);
            this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_payment') {
                for (var index in this.paymentList.data) {
                    if (this.paymentList.data[index].id === this.paymentUpdate.id) {
                        this.paymentList.data[index] = this.paymentUpdate;
                        break;
                    }
                }

                await swal.fire(
                    'Success!',
                    'Payment type successfully updated.',
                    'success'
                )

                this.closeModal('update')
            }  else {
                await swal.fire(
                    'Error!',
                    'There were some errors while updating the payment type.',
                    'error'
                )
            }
        },
    },
}
</script>

<style scoped>

</style>
