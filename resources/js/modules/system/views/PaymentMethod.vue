<template>
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">{{ $t('message.finance.pm_title') }}</h3>
            <div class="card-tools">
                <button
                    v-if="user.permission_list.includes('create-admin-settings-finance')"
                    class="btn btn-success float-right"
                    data-toggle="modal"
                    data-target="#modal-add-payment"

                    @click="clearMessageform">

                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="height: 650px; overflow: scroll;">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <thead>
                <tr class="label-primary">
                    <th>#</th>
                    <th>{{ $t('message.finance.pm_type') }}</th>
                    <th>{{ $t('message.finance.pm_we_can_receive_payment') }}</th>
                    <th>{{ $t('message.finance.pm_we_can_send_payment') }}</th>
                    <th>{{ $t('message.finance.pm_action') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in paymentList.data" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.receive_payment }}</td>
                    <td>{{ item.send_payment }}</td>
                    <td>
                        <div class="btn-group">
                            <button
                                v-if="user.permission_list.includes('update-admin-settings-finance')"
                                type="submit"
                                class="btn btn-default"
                                data-toggle="modal"
                                data-target="#modal-update-payment"
                                :title="$t('message.finance.edit')"

                                @click="doEditPayment(item)">

                                <i class="fa fa-fw fa-edit"></i>
                            </button>
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
                        <h5 class="modal-title">{{ $t('message.finance.apt_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                            <label>{{ $t('message.finance.apt_payment_type') }}</label>
                            <input type="text"
                                   v-model="paymentModel.type"
                                   class="form-control"
                                   :placeholder="$t('message.finance.apt_enter_payment_type')"
                                   required>
                            <span v-if="messageForms.errors.type"
                                  v-for="err in messageForms.errors.type"
                                  class="text-danger">{{ err }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.finance.close') }}
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"

                            @click="submitAddPayment">

                            {{ $t('message.finance.save') }}
                        </button>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('message.finance.upt_title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.type}" class="form-group">
                                    <label>{{ $t('message.finance.apt_payment_type') }}</label>
                                    <input type="text"
                                        v-model="paymentUpdate.type"
                                        class="form-control"
                                        :placeholder="$t('message.finance.apt_enter_payment_type')"
                                        required>
                                    <span v-if="messageForms.errors.type"
                                        v-for="err in messageForms.errors.type"
                                        class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.receive_payment}"
                                    class="form-group">
                                    <label>{{ $t('message.finance.upt_receive_payment') }}</label>
                                    <select name="" class="form-control" v-model="paymentUpdate.receive_payment">
                                        <option value="yes">{{ $t('message.finance.yes') }}</option>
                                        <option value="no">{{ $t('message.finance.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.receive_payment"
                                        v-for="err in messageForms.errors.receive_payment"
                                        class="text-danger">{{ err }}</span>
                                </div>

                            </div>

                            <div class="col-sm-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.send_payment}"
                                    class="form-group">
                                    <label>{{ $t('message.finance.upt_send_payment') }}</label>
                                    <select name="" class="form-control" v-model="paymentUpdate.send_payment">
                                        <option value="yes">{{ $t('message.finance.yes') }}</option>
                                        <option value="no">{{ $t('message.finance.no') }}</option>
                                    </select>
                                    <span v-if="messageForms.errors.send_payment"
                                        v-for="err in messageForms.errors.send_payment"
                                        class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.email_value}"
                                    class="form-group">
                                    <label>{{ $t('message.finance.upt_email') }}</label>

                                    <input type="text"
                                        v-model="paymentUpdate.email_value"
                                        class="form-control"
                                        :placeholder="$t('message.finance.upt_enter_email')"
                                        required>

                                    <span v-if="messageForms.errors.email_value"
                                        v-for="err in messageForms.errors.email_value"
                                        class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.account_value}"
                                    class="form-group">
                                    <label>{{ $t('message.finance.upt_account') }}</label>

                                    <input type="text"
                                        v-model="paymentUpdate.account_value"
                                        class="form-control"
                                        :placeholder="$t('message.finance.upt_enter_account')"
                                        required>

                                    <span v-if="messageForms.errors.account_value"
                                        v-for="err in messageForms.errors.account_value"
                                        class="text-danger">{{ err }}</span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div :class="{'form-group': true, 'has-error': messageForms.errors.address_value}"
                                    class="form-group">
                                    <label>{{ $t('message.finance.upt_address') }}</label>
                                    <small class="text-danger"><i>{{ $t('message.finance.upt_for_crypto') }}</i></small>

                                    <input type="text"
                                        v-model="paymentUpdate.address_value"
                                        class="form-control"
                                        :placeholder="$t('message.finance.upt_enter_address')"
                                        required>

                                    <span v-if="messageForms.errors.address_value"
                                        v-for="err in messageForms.errors.address_value"
                                        class="text-danger">{{ err }}</span>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <h4 class="text-primary">Other Details</h4>

                        <div class="row">
                            <table class="table">
                                <tr>
                                    <td class="align-middle">
                                        <label>Bank Name</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.bank_name">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Account Name</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.account_name">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Account IBAN</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.account_iban">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>SWIFT Code</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.swift_code">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Beneficiary Address</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.beneficiary_add">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Account Holder</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.account_holder">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Account Type</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.account_type">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Routing Number</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.routing_num">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        <label>Wire Routing Number</label>
                                    </td>
                                    <td class="text-center">
                                        <label>Show</label>
                                        <input type="checkbox" class="form-control" v-model="paymentUpdate.wire_routing_num">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <hr/>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.finance.upt_upload_logo') }}</label>
                                    <div class="row">
                                        <input type="file" class="form-control col mr-2" enctype="multipart/form-data" ref="logo">

                                        <button type="button" @click="uploadImage('logo')" class="btn btn-primary col-2"><i class="fas fa-upload"></i></button>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>{{ $t('message.finance.upt_qr_code') }}</label>
                                    <div class="row">
                                        <input type="file" class="form-control col mr-2" enctype="multipart/form-data" ref="logo2">

                                        <button type="button" @click="uploadImage('qr')" class="btn btn-primary col-2"><i class="fas fa-upload"></i></button>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="row mb-4" v-for="image in paymentImages">
                                    <div class="col-2 d-flex align-items-center">
                                        ({{ image.image_type }})
                                    </div>
                                    <div class="col-2 mr-4">
                                        <img :src="'/storage/' + image.path" width="100%" alt="">
                                    </div>
                                    <div class="col-3 d-flex align-items-center mr-4">
                                        <a href="">{{ image.path }}</a>
                                    </div>
                                    <div class="col-1 d-flex align-items-center">
                                        <a href="" @click.prevent="deleteImage(image.id)" title="Delete" style="color: #444"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            {{ $t('message.finance.close') }}
                        </button>
                        <button type="button" @click="submitUpdatePayment" class="btn btn-primary">
                            {{ $t('message.finance.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import _ from 'lodash';

export default {
    name : "PaymentMethod",

    props : [
        'messageForms',
        // 'isPopupLoading'
    ],

    data() {
        return {
            paymentUpdate : {
                id : 0,
                type : '',
                receive_payment : '',
                send_payment : '',
                account_value : '',
                address_value : '',
                email_value : '',
            },
            paymentModel : {
                id : 0,
                type : ''
            },
            paymentImages : null
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            paymentList : state => state.storeSystem.paymentList,
        }),
    },

    mounted() {
        this.getPaymentList();
    },

    methods : {
        uploadImage(type) {
            let self = this;
            let formData = new FormData();

            if(type === 'logo') {
                formData.append('file', this.$refs.logo.files[0]);
            } else{
                formData.append('file', this.$refs.logo2.files[0]);
            }

            formData.append('image_type', type);


            axios.post('/api/admin/payment-type/image/' + this.paymentUpdate.id, formData)
                .then((response) => {
                    swal.fire(
                        self.$t('message.finance.alert_success'),
                        self.$t('message.finance.alert_image_uploaded'),
                        'success'
                    )

                    this.getPaymentImages(response.data.payment_type_id)
                });
        },

        deleteImage(id) {
            let self = this;
            axios.delete('/api/admin/payment-type/image/' + id)
            .then((response) => {
                swal.fire(
                    self.$t('message.finance.alert_success'),
                    self.$t('message.finance.alert_image_deleted'),
                    'success'
                )

                this.getPaymentImages(response.data)
            })
        },

        getPaymentImages(id) {
            axios.get('/api/payments/image?payment_type_id=' + id)
                .then((response) => {
                    this.paymentImages = response.data;
                });
        },

        clearMessageform() {
            this.$store.dispatch('clearMessageFormSystem');
        },

        doEditPayment(item) {
            // console.log(item)
            this.clearMessageform();
            this.$store.dispatch('clearMessageFormSystem');
            this.paymentUpdate = JSON.parse(JSON.stringify(item))
            this.getPaymentImages(this.paymentUpdate.id);
        },

        async submitAddPayment() {
            let that = this;
            // this.isPopupLoading = true;
            await this.$store.dispatch('actionAddPayment', that.paymentModel);
            // this.isPopupLoading = false;

            if (this.messageForms.action === 'saved_payment') {
                await swal.fire(
                    that.$t('message.finance.alert_success'),
                    that.$t('message.finance.alert_payment_type_added'),
                    'success'
                )

                this.closeModal('add')
                this.clearModel();
                this.getPaymentList();
            } else {
                await swal.fire(
                    that.$t('message.finance.alert_error'),
                    that.messageForms.message,
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
            let self = this;
            // this.isPopupLoading = true;

            await this.$store.dispatch('actionUpdatePayment', this.paymentUpdate);
            // this.isPopupLoading = false;

            if (this.messageForms.action === 'updated_payment') {
                await swal.fire(
                    self.$t('message.finance.alert_success'),
                    self.$t('message.finance.alert_payment_type_updated'),
                    'success',
                )

                this.closeModal('update')
            } else {
                await swal.fire(
                    self.$t('message.finance.alert_error'),
                    self.messageForms.message,
                    'error',
                )
            }

            this.getPaymentList();
        },
    },
}
</script>

<style scoped>

</style>
